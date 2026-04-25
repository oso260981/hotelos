<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Scan extends BaseController
{
    public function index($token = null)
    {
        if (!$token) {
            return "Token requerido";
        }

        $db = \Config\Database::connect();
        $session = $db->table('scan_sessions')->where('token', $token)->get()->getRowArray();

        if (!$session) {
            return "Sesión no válida o expirada";
        }

        return view('scan/mobile', ['token' => $token]);
    }

    public function upload($token)
    {
        $db = \Config\Database::connect();
        $session = $db->table('scan_sessions')->where('token', $token)->get()->getRowArray();

        if (!$session) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Sesión no válida']);
        }

        $file = $this->request->getFile('image');
        if (!$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Archivo no válido']);
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/fotos', $newName);

        // Actualizar estado a PROCESANDO
        $db->table('scan_sessions')->where('token', $token)->update([
            'status' => 'PROCESANDO',
            'image_path' => $newName
        ]);

        // 🔄 RESTAURADO: Consolidamos el procesamiento usando el motor de OpenAI en Ocr
        $ocrController = new \App\Controllers\Ocr();
        
        // Simulamos el procesamiento enviando la imagen en base64
        $fullPath = FCPATH . 'uploads/fotos/' . $newName;
        $imageData = file_get_contents($fullPath);
        $base64 = 'data:image/jpeg;base64,' . base64_encode($imageData);
        
        $resultado = $ocrController->ejecutarOcrOpenAI($base64);

        if ($resultado['ok']) {
            $db->table('scan_sessions')->where('token', $token)->update([
                'status' => 'COMPLETADO',
                'data_json' => json_encode($resultado['data']),
                'image_path' => $resultado['file'] // Usar el nombre de archivo final del OCR
            ]);
            return $this->response->setJSON(['ok' => true]);
        } else {
            $db->table('scan_sessions')->where('token', $token)->update([
                'status' => 'ERROR'
            ]);
            return $this->response->setJSON(['ok' => false, 'msg' => 'Error en OCR: ' . ($resultado['error'] ?? 'Desconocido')]);
        }
    }
}

