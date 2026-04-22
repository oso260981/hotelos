<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TurnoModel;

/**
 * Configuración de turnos del sistema
 */
class Turnos extends BaseController
{

    /**
     * LISTAR TURNOS ACTIVOS (timeline / combos)
     */
    public function list()
    {
        $model = new TurnoModel();

        $data = $model
            ->where('activo',1)
            ->orderBy('id','ASC')
            ->findAll();

          return $this->response->setJSON([
        "ok" => true,
        "data" => $data
    ]);
    }


    /**
     * GUARDADO MASIVO DESDE GRID CONFIGURACIÓN
     */
 public function save()
{
    $model = new TurnoModel();

    $rows = $this->request->getJSON(true);

    if (!$rows) {
        return $this->response->setJSON([
            "ok" => false,
            "msg" => "No hay datos"
        ]);
    }

    foreach ($rows as $r) {

        $data = [
            'nombre'      => $r['nombre'],
            'hora_inicio' => $r['hora_inicio'],
            'hora_fin'    => $r['hora_fin'],
            'usuario_id'  => !empty($r['usuario_id']) ? $r['usuario_id'] : null, // 🔥 AQUÍ
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        if (!empty($r['id'])) {

            $model->update($r['id'], $data);

        } else {

            $data['activo'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');

            $model->insert($data);
        }
    }

    return $this->response->setJSON([
        "ok" => true
    ]);
}

    /**
     * ELIMINAR (SOFT DELETE)
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        $model = new TurnoModel();

        $model->update($id,[
            'activo'=>0
        ]);

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }

}
