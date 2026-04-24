<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Ocr extends Controller
{

 public function procesar()
{
    $json = $this->request->getJSON(true);
    $image = $json['image'] ?? null;

    if (!$image) {
        return $this->response->setJSON(['error' => 'No image']);
    }

    // =========================
    // 1. GUARDAR IMAGEN
    // =========================
    list($type, $dataBase64) = explode(';', $image);
    list(, $dataBase64) = explode(',', $dataBase64);

    $data = base64_decode($dataBase64);

    $filename = 'ocr_' . time() . '.jpg';
    $path = FCPATH . 'uploads/fotos/' . $filename;
    if (!is_dir(FCPATH . 'uploads/fotos/')) mkdir(FCPATH . 'uploads/fotos/', 0777, true);

    file_put_contents($path, $data);

    $url = base_url('uploads/fotos/' . $filename);

    // =========================
    // 2. OPENAI
    // =========================
    $apiKey = getenv('TOKEN_OPENAI');

    $payload = [
        "model" => "gpt-4o-mini",
        "messages" => [
            [
                "role" => "user",
                "content" => [
                    ["type" => "text", "text" => "
Extrae los datos del documento y responde SOLO en JSON con esta estructura:

{
  \"tipo_documento\": \"INE|PASAPORTE|OTRO\",
  \"nombre\": \"\",
  \"apellidos\": \"\",
  \"numero_identificacion\": \"\",
  \"fecha_nacimiento\": \"YYYY-MM-DD\",
  \"genero\": \"Masculino|Femenino|Otro\",
  \"nacionalidad\": \"\",
  \"direccion\": \"\",
  \"ciudad\": \"\",
  \"estado\": \"\",
  \"codigo_postal\": \"\"
}

Reglas:
- Detecta si es INE o Pasaporte.
- IMPORTANTE: Si es INE, usa obligatoriamente la 'CLAVE DE ELECTOR' como 'numero_identificacion'.
- Separa nombre y apellidos correctamente.
- Convierte fecha a formato YYYY-MM-DD.
- En la dirección, intenta extraer por separado la Ciudad, Estado y Código Postal (el cual usualmente consiste en 5 dígitos numéricos).
- No agregues texto extra.
"],
                    [
                        "type" => "image_url",
                        "image_url" => ["url" => "data:image/jpeg;base64," . base64_encode($data)]
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $ocr = json_decode($response, true);

    $text = $ocr['choices'][0]['message']['content'] ?? '{}';

    // limpiar ```json
    $clean = preg_replace('/```json|```/', '', $text);
    $dataOCR = json_decode($clean, true);

    // =========================
    // 3. VALIDAR EDAD
    // =========================
    $esMenor = false;

    if (!empty($dataOCR['fecha_nacimiento'])) {
        $dob = new \DateTime($dataOCR['fecha_nacimiento']);
        $hoy = new \DateTime();
        $edad = $hoy->diff($dob)->y;

        if ($edad < 18) {
            $esMenor = true;
        }
    }

    // =========================
    // 4. GUARDAR EN BD
    // =========================
    $db = \Config\Database::connect();

    $db->table('ocr_registros')->insert([
        'imagen' => $filename,
        'tipo_documento' => $dataOCR['tipo_documento'] ?? null,
        'nombre' => $dataOCR['nombre'] ?? null,
        'apellidos' => $dataOCR['apellidos'] ?? null,
        'numero_id' => $dataOCR['numero_identificacion'] ?? null,
        'fecha_nacimiento' => $dataOCR['fecha_nacimiento'] ?? null,
        'genero' => $dataOCR['genero'] ?? null,
        'nacionalidad' => $dataOCR['nacionalidad'] ?? null,
        'es_menor' => $esMenor,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return $this->response->setJSON([
        'ok' => true,
        'data' => $dataOCR,
        'es_menor' => $esMenor,
        'url' => $url,
        'file' => $filename
    ]);
}
   



/* public function procesar()
{
    $json = $this->request->getJSON(true);
    $imageRaw = $json['image'] ?? null;

    if (!$imageRaw) {
        return $this->response->setJSON(['error' => 'No image']);
    }

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

    // 2. LLAMADA A GEMINI API (Modelo Flash 1.5)
    // Usamos env() o getenv() - verificamos que la llave exista
    $apiKey = env('TOKEN_GEMINI') ?: getenv('TOKEN_GEMINI');
    $apiKey = trim($apiKey); // 🔥 Limpiar espacios accidentales del .env
    
    if (!$apiKey) {
        return $this->response->setJSON(['ok' => false, 'error' => 'API Key de Gemini no configurada en .env']);
    }

    $model = "gemini-1.5-flash"; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

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
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt],
                    [
                        "inline_data" => [
                            "mime_type" => "image/jpeg",
                            "data" => $cleanBase64
                        ]
                    ]
                ]
            ]
        ],
        "generationConfig" => [
            "response_mime_type" => "application/json",
            "temperature" => 0.1
        ]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "x-goog-api-key: $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_SSL_VERIFYPEER => false, 
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorMsg = curl_error($ch);
    curl_close($ch);

    // DEBUG: Loguear para revisar en writable/logs
    log_message('debug', "OCR URL: " . $url);
    log_message('debug', "OCR HTTP Code: " . $httpCode);
    log_message('debug', "OCR Response: " . $response);

    if ($httpCode !== 200) {
        return $this->response->setJSON([
            'ok' => false, 
            'error' => 'Error en Gemini API (HTTP ' . $httpCode . ')', 
            'curl_error' => $errorMsg,
            'url_attempted' => $url,
            'payload_debug' => [
                'model' => $model,
                'prompt_len' => strlen($prompt),
                'image_len' => strlen($cleanBase64)
            ],
            'details' => json_decode($response, true),
            'raw' => $response
        ])->setStatusCode(500);
    }

    $result = json_decode($response, true);
    
    // Extraer el texto del JSON que devuelve Gemini
    $jsonString = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

    $dataOCR = json_decode($jsonString, true);

    // 3. VALIDAR EDAD
    $esMenor = false;
    if (!empty($dataOCR['fecha_nacimiento'])) {
        try {
            $dob = new \DateTime($dataOCR['fecha_nacimiento']);
            $hoy = new \DateTime();
            $esMenor = ($hoy->diff($dob)->y < 18);
        } catch (\Exception $e) {
            // Error en formato de fecha
        }
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

    return $this->response->setJSON([
        'ok' => true,
        'data' => $dataOCR,
        'es_menor' => $esMenor,
        'url' => base_url('uploads/fotos/' . $filename)
    ]);
} */

}