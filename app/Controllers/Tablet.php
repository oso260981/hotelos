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

        // 1. Obtener la sesión para recuperar registro_id y huesped_id
        $db = \Config\Database::connect();
        $sesion = $db->table('registro_sesiones_firma')
                     ->where('tablet_id', 'TABLET_01')
                     ->get()->getRow();

        // 2. Procesar la imagen de la firma
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

        // 3. ACTUALIZACIÓN TRIPLE
        
        // A. En ocr_registros (El log de identificación)
        $db->table('ocr_registros')
           ->where('id', $json->id_ocr)
           ->update(['firma_path' => $filename]);

        // B. En registros (La estancia actual)
        if (!empty($sesion->registro_id)) {
            $db->table('registros')
               ->where('id', $sesion->registro_id)
               ->update(['firma_path' => $filename]);
        }

        // C. En huespedes (El perfil maestro del cliente)
        if (!empty($sesion->huesped_id)) {
            log_message('debug', 'Actualizando firma para huesped: ' . $sesion->huesped_id);
            $db->table('huespedes')
               ->where('id', $sesion->huesped_id)
               ->update(['firma_path' => $filename]);
        } else {
            log_message('debug', 'No hay huesped_id en la sesión de firma');
        }

        // 4. Limpiar la sesión
        $db->table('registro_sesiones_firma')
           ->where('tablet_id', 'TABLET_01')
           ->update([
               'status' => 'WAIT',
               'ocr_registro_id' => null,
               'registro_id' => null,
               'huesped_id' => null
           ]);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Firma guardada correctamente en expediente']);
    }

    /**
     * (Llamado desde Recepción) Activa la sesión de firma para un registro existente
     */
    public function activarFirma($id_ocr)
    {
        $db = \Config\Database::connect();
        
        $ocr = $db->table('ocr_registros')->where('id', $id_ocr)->get()->getRow();
        if (!$ocr) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Registro no encontrado']);
        }

        // Capturar IDs opcionales desde el query string o post
        $registro_id = $this->request->getVar('registro_id');
        $huesped_id = $this->request->getVar('huesped_id');

        $db->table('registro_sesiones_firma')
           ->where('tablet_id', 'TABLET_01')
           ->update([
               'status' => 'READY',
               'ocr_registro_id' => $id_ocr,
               'registro_id' => (!empty($registro_id)) ? $registro_id : null,
               'huesped_id' => (!empty($huesped_id)) ? $huesped_id : null
           ]);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Firma activada en la tablet exterior']);
    }

    /**
     * (Llamado desde Recepción) Activa la sesión de firma con datos manuales
     */
    public function activarFirmaManual()
    {
        $json = $this->request->getJSON();
        if (!$json || empty($json->nombre)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'El nombre es obligatorio']);
        }

        $db = \Config\Database::connect();

        // 1. Crear registro manual
        $db->table('ocr_registros')->insert([
            'tipo_documento' => 'MANUAL',
            'nombre'         => $json->nombre,
            'apellidos'      => $json->apellidos ?? '',
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        $newId = $db->insertID();

        // 2. Activar tablet con IDs vinculados
        $db->table('registro_sesiones_firma')
           ->where('tablet_id', 'TABLET_01')
           ->update([
               'status' => 'READY',
               'ocr_registro_id' => $newId,
               'registro_id' => (!empty($json->registro_id)) ? $json->registro_id : null,
               'huesped_id' => (!empty($json->huesped_id)) ? $json->huesped_id : null
           ]);

        return $this->response->setJSON([
            'ok' => true, 
            'id' => $newId, 
            'msg' => 'Firma activada (Modo Manual)'
        ]);
    }
}
