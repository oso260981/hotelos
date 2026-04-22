<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolModel;

/**
 * Controlador CRUD Roles - HotelOS
 */
class Roles extends BaseController
{

    /**
     * LISTAR ROLES (AJAX)
     */
    public function list()
    {
        $model = new RolModel();

        $roles = $model
            ->where('activo', 1)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return $this->response->setJSON($roles);
    }


    /**
     * OBTENER UN ROL
     */
    public function get($id = null)
    {
        $model = new RolModel();

        $rol = $model->find($id);

        return $this->response->setJSON($rol);
    }


    /**
     * GUARDAR (ALTA / EDICIÓN)
     */
    public function save()
    {
        $model = new RolModel();

        $id = $this->request->getPost('id');

        $data = [
            'nombre'         => $this->request->getPost('nombre'),
            'recepcion'      => $this->request->getPost('recepcion') ? 1 : 0,
            'reportes'       => $this->request->getPost('reportes') ? 1 : 0,
            'editar_sistema' => $this->request->getPost('editar_sistema') ? 1 : 0,
            'activo'         => 1
        ];

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
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

        $model = new RolModel();

        $model->update($id, [
            'activo' => 0
        ]);

        return $this->response->setJSON([
            "ok" => true
        ]);
    }

}
