<?php

namespace App\Controllers;
use App\Models\ConfiguracionModel;

class Configuraciones extends BaseController
{
    public function index()
    {
        $model = new ConfiguracionModel();

        $data['config'] = $model->findAll();

        return view('configuraciones',$data);
    }

   public function guardar()
{
    try {

        $id    = $this->request->getPost('id');
        $valor = $this->request->getPost('valor');

        if (!$id) {
            throw new \Exception("ID requerido");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // =========================
        // 🧩 ACTUALIZA CONFIGURACION
        // =========================
        $model = new \App\Models\ConfiguracionModel();
        $model->update($id, ['valor' => $valor]);

        // =========================
        // 🔄 SINCRONIZA IMPUESTOS
        // =========================

        // Si es IVA
        if ($id == 1) {
            $db->table('impuestos')->update([
                'tasa' => $valor
            ], ['id' => 1]);
        }

        // Si es ISH
        if ($id == 2) {
            $db->table('impuestos')->update([
                'tasa' => $valor
            ], ['id' => 2]);
        }

        $db->transComplete();

        return $this->response->setJSON([
            'ok' => $db->transStatus()
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            'ok' => false,
            'error' => $e->getMessage()
        ]);
    }
}
}