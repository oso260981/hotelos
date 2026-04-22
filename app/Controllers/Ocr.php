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
  \"direccion\": \"\"
}

Reglas:
- Detecta si es INE o Pasaporte
- Separa nombre y apellidos correctamente
- Convierte fecha a formato YYYY-MM-DD
- Extrae la dirección completa si está disponible
- No agregues texto extra
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
  

}