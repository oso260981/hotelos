<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Tablet extends BaseController
{
    /**
     * Vista principal de la Tablet (Pantalla de bienvenida o firma)
     */
    public function index()
    {
        return view('tablet/index');
    }

    /**
     * Verifica el estado de la sesión de firma para la tablet
     */
    public function checkStatus()
    {
        $db = \Config\Database::connect();
        $sesion = $db->table('registro_sesiones_firma')
                     ->where('tablet_id', 'TABLET_01')
                     ->get()->getRow();

        if ($sesion && $sesion->status == 'READY' && $sesion->ocr_registro_id) {
            // Obtenemos los datos del cliente desde la tabla de OCR
            $cliente = $db->table('ocr_registros')
                          ->where('id', $sesion->ocr_registro_id)
                          ->get()->getRow();

            if ($cliente) {
                return $this->response->setJSON([
                    'accion' => 'mostrar_firma',
                    'id_ocr' => $sesion->ocr_registro_id,
                    'nombre' => trim(($cliente->nombre ?? '') . ' ' . ($cliente->apellidos ?? ''))
                ]);
            }
        }

        return $this->response->setJSON(['accion' => 'esperar']);
    }

    /**
     * Guarda la firma enviada desde la tablet
     */
    public function guardarFirma()
    {
        $json = $this->request->getJSON();
        if (!$json || empty($json->firma) || empty($json->id_ocr)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Datos incompletos']);
        }

        // 1. Procesar la imagen de la firma
        $imageRaw = $json->firma;
        $cleanBase64 = $imageRaw;
        if (preg_match('/^data:image\/(\w+);base64,/', $imageRaw, $type)) {
            $cleanBase64 = substr($imageRaw, strpos($imageRaw, ',') + 1);
        }
        $data = base64_decode($cleanBase64);
        $filename = 'firma_' . $json->id_ocr . '_' . time() . '.png';
        $path = FCPATH . 'uploads/fotos/' . $filename;
        
        if (!is_dir(FCPATH . 'uploads/fotos')) {
            mkdir(FCPATH . 'uploads/fotos', 0777, true);
        }
        
        file_put_contents($path, $data);

        // 2. Actualizar el registro de OCR con la firma
        $db = \Config\Database::connect();
        $db->table('ocr_registros')
           ->where('id', $json->id_ocr)
           ->update(['firma_path' => $filename]);

        // 3. Limpiar la sesión para que la tablet vuelva a espera
        $db->table('registro_sesiones_firma')
           ->where('tablet_id', 'TABLET_01')
           ->update([
               'status' => 'WAIT',
               'ocr_registro_id' => null
           ]);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Firma guardada correctamente']);
    }

    /**
     * (Llamado desde Recepción) Activa la sesión de firma para un registro
     */
    public function activarFirma($id_ocr)
    {
        $db = \Config\Database::connect();
        
        // Verificar si existe el registro de OCR
        $ocr = $db->table('ocr_registros')->where('id', $id_ocr)->get()->getRow();
        if (!$ocr) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Registro no encontrado']);
        }

        $db->table('registro_sesiones_firma')
           ->where('tablet_id', 'TABLET_01')
           ->update([
               'status' => 'READY',
               'ocr_registro_id' => $id_ocr
           ]);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Firma activada en la tablet exterior']);
    }
}
