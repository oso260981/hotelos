<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Ocr extends Controller
{
    public function crearSesion($registroId = null)
    {
        $db = \Config\Database::connect();
        $token = bin2hex(random_bytes(16));
        
        $db->table('scan_sessions')->insert([
            'token' => $token,
            'registro_id' => $registroId,
            'status' => 'PENDIENTE',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'ok' => true,
            'token' => $token,
            'url' => base_url("scan/index/$token")
        ]);
    }

    public function consultarSesion($token)
    {
        $db = \Config\Database::connect();
        $session = $db->table('scan_sessions')->where('token', $token)->get()->getRowArray();

        if (!$session) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Sesión no encontrada']);
        }

        return $this->response->setJSON([
            'ok' => true,
            'status' => $session['status'],
            'data' => json_decode($session['data_json'], true),
            'image' => $session['image_path']
        ]);
    }

    public function consultarFirma($id)
    {
        $db = \Config\Database::connect();
        $registro = $db->table('ocr_registros')
                       ->select('firma_path')
                       ->where('id', $id)
                       ->get()->getRow();
        
        if ($registro && $registro->firma_path) {
            return $this->response->setJSON([
                'ok' => true,
                'firma_path' => $registro->firma_path
            ]);
        }
        
        return $this->response->setJSON(['ok' => false]);
    }

    public function procesar()
    {
        $json = $this->request->getJSON(true);
        $imageRaw = $json['image'] ?? null;

        if (!$imageRaw) {
            return $this->response->setJSON(['ok' => false, 'error' => 'No image']);
        }

        // 🔄 RESTAURADO: Usamos OpenAI (GPT-4o-mini) por solicitud del usuario
        $resultado = $this->ejecutarOcrOpenAI($imageRaw);

        if (!$resultado['ok']) {
            return $this->response->setJSON($resultado)->setStatusCode(500);
        }

        return $this->response->setJSON($resultado);
    }

    public function ejecutarOcrOpenAI($imageRaw)
    {
        // 1. PROCESAR Y GUARDAR LOCALMENTE
        $cleanBase64 = $imageRaw;
        if (preg_match('/^data:image\/(\w+);base64,/', $imageRaw, $type)) {
            $cleanBase64 = substr($imageRaw, strpos($imageRaw, ',') + 1);
        }
        
        $data = base64_decode($cleanBase64);
        $filename = 'ocr_' . time() . '.jpg';
        $uploadPath = FCPATH . 'uploads/fotos/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
        file_put_contents($uploadPath . $filename, $data);

        // 2. LLAMADA A OPENAI API
        $apiKey = env('TOKEN_OPENAI') ?: getenv('TOKEN_OPENAI');
        $apiKey = trim($apiKey);
        
        if (!$apiKey) {
            return ['ok' => false, 'error' => 'API Key de OpenAI no configurada'];
        }

        $url = "https://api.openai.com/v1/chat/completions";

        $prompt = "Extrae los datos del documento de identidad y responde ESTRICTAMENTE en formato JSON.
        Estructura JSON esperada:
        {
          \"tipo_documento\": \"INE|PASAPORTE|OTRO\",
          \"nombre\": \"\",
          \"apellidos\": \"\",
          \"numero_identificacion\": \"\",
          \"fecha_nacimiento\": \"YYYY-MM-DD\",
          \"genero\": \"Masculino|Femenino\",
          \"nacionalidad\": \"\",
          \"direccion\": \"\",
          \"ciudad\": \"\",
          \"estado\": \"\",
          \"codigo_postal\": \"\"
        }
        
        Reglas críticas:
        1. Si el documento es un INE (México), usa la 'CLAVE DE ELECTOR' como 'numero_identificacion'.
        2. Convierte la fecha de nacimiento al formato YYYY-MM-DD.
        3. El código postal debe ser de 5 dígitos.
        4. Responde solo el JSON, sin bloques de código markdown.";

        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        ["type" => "text", "text" => $prompt],
                        [
                            "type" => "image_url",
                            "image_url" => ["url" => "data:image/jpeg;base64," . $cleanBase64]
                        ]
                    ]
                ]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.1
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false, 
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'ok' => false, 
                'error' => 'Error en OpenAI API (HTTP ' . $httpCode . ')',
                'details' => json_decode($response, true)
            ];
        }

        $result = json_decode($response, true);
        $jsonString = $result['choices'][0]['message']['content'] ?? '{}';
        $dataOCR = json_decode($jsonString, true);

        // 3. VALIDAR EDAD
        $esMenor = false;
        if (!empty($dataOCR['fecha_nacimiento'])) {
            try {
                $dob = new \DateTime($dataOCR['fecha_nacimiento']);
                $hoy = new \DateTime();
                $esMenor = ($hoy->diff($dob)->y < 18);
            } catch (\Exception $e) {}
        }

        // 4. GUARDAR EN BD
        $db = \Config\Database::connect();
        $db->table('ocr_registros')->insert([
            'imagen'           => $filename,
            'tipo_documento'   => $dataOCR['tipo_documento'] ?? 'OTRO',
            'nombre'           => $dataOCR['nombre'] ?? null,
            'apellidos'        => $dataOCR['apellidos'] ?? null,
            'numero_id'        => $dataOCR['numero_identificacion'] ?? null,
            'fecha_nacimiento' => $dataOCR['fecha_nacimiento'] ?? null,
            'genero'           => $dataOCR['genero'] ?? null,
            'nacionalidad'     => $dataOCR['nacionalidad'] ?? null,
            'es_menor'         => $esMenor ? 1 : 0,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        return [
            'ok' => true,
            'data' => $dataOCR,
            'es_menor' => $esMenor,
            'url' => base_url('uploads/fotos/' . $filename),
            'file' => $filename
        ];
    }

    public function ejecutarOcrGemini($imageRaw)
    {
        // 🔄 ADAPTADO: Ahora utiliza OpenAI (GPT-4o-mini)
        // 1. PROCESAR Y GUARDAR LOCALMENTE
        $cleanBase64 = $imageRaw;
        if (preg_match('/^data:image\/(\w+);base64,/', $imageRaw, $type)) {
            $cleanBase64 = substr($imageRaw, strpos($imageRaw, ',') + 1);
        }
        
        $data = base64_decode($cleanBase64);
        $filename = 'ocr_' . time() . '.jpg';
        $uploadPath = FCPATH . 'uploads/fotos/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
        file_put_contents($uploadPath . $filename, $data);

        // 2. LLAMADA A OPENAI API
        $apiKey = env('TOKEN_OPENAI') ?: getenv('TOKEN_OPENAI');
        $apiKey = trim($apiKey);
        
        if (!$apiKey) {
            return ['ok' => false, 'error' => 'API Key de OpenAI no configurada'];
        }

        $url = "https://api.openai.com/v1/chat/completions";

        $prompt = "Extrae los datos del documento de identidad y responde ESTRICTAMENTE en formato JSON.
        Estructura JSON esperada:
        {
          \"tipo_documento\": \"INE|PASAPORTE|OTRO\",
          \"nombre\": \"\",
          \"apellidos\": \"\",
          \"numero_identificacion\": \"\",
          \"fecha_nacimiento\": \"YYYY-MM-DD\",
          \"genero\": \"Masculino|Femenino\",
          \"nacionalidad\": \"\",
          \"direccion\": \"\",
          \"ciudad\": \"\",
          \"estado\": \"\",
          \"codigo_postal\": \"\"
        }
        
        Reglas críticas:
        1. Si el documento es un INE (México), usa la 'CLAVE DE ELECTOR' como 'numero_identificacion'.
        2. Convierte la fecha de nacimiento al formato YYYY-MM-DD.
        3. El código postal debe ser de 5 dígitos.
        4. Responde solo el JSON, sin bloques de código markdown.";

        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        ["type" => "text", "text" => $prompt],
                        [
                            "type" => "image_url",
                            "image_url" => ["url" => "data:image/jpeg;base64," . $cleanBase64]
                        ]
                    ]
                ]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.1
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false, 
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'ok' => false, 
                'error' => 'Error en OpenAI API (HTTP ' . $httpCode . ')',
                'details' => json_decode($response, true)
            ];
        }

        $result = json_decode($response, true);
        $jsonString = $result['choices'][0]['message']['content'] ?? '{}';
        $dataOCR = json_decode($jsonString, true);

        // 3. VALIDAR EDAD
        $esMenor = false;
        if (!empty($dataOCR['fecha_nacimiento'])) {
            try {
                $dob = new \DateTime($dataOCR['fecha_nacimiento']);
                $hoy = new \DateTime();
                $esMenor = ($hoy->diff($dob)->y < 18);
            } catch (\Exception $e) {}
        }

        // 4. GUARDAR EN BD
        $db = \Config\Database::connect();
        $db->table('ocr_registros')->insert([
            'imagen'           => $filename,
            'tipo_documento'   => $dataOCR['tipo_documento'] ?? 'OTRO',
            'nombre'           => $dataOCR['nombre'] ?? null,
            'apellidos'        => $dataOCR['apellidos'] ?? null,
            'numero_id'        => $dataOCR['numero_identificacion'] ?? null,
            'fecha_nacimiento' => $dataOCR['fecha_nacimiento'] ?? null,
            'genero'           => $dataOCR['genero'] ?? null,
            'nacionalidad'     => $dataOCR['nacionalidad'] ?? null,
            'es_menor'         => $esMenor ? 1 : 0,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        return [
            'ok' => true,
            'data' => $dataOCR,
            'es_menor' => $esMenor,
            'url' => base_url('uploads/fotos/' . $filename),
            'file' => $filename
        ];
    }

   


}
