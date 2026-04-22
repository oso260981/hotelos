<?php

namespace App\Controllers;

use App\Models\PisoModel;
use CodeIgniter\RESTful\ResourceController;

class Pisos extends ResourceController
{
    protected $modelName = PisoModel::class;
    protected $format    = 'json';

    // ⭐ LISTAR TODOS
    public function index()
    {
        $data = $this->model->orderBy('id','ASC')->findAll();
        return $this->respond($data);
    }

    // ⭐ OBTENER UNO
    public function show($id = null)
    {
        $row = $this->model->find($id);

        if(!$row){
            return $this->failNotFound("Piso no encontrado");
        }

        return $this->respond($row);
    }

    // ⭐ CREAR
    public function create()
    {
        $data = $this->request->getJSON(true);

        $id = $this->model->insert($data);

        return $this->respondCreated([
            "ok" => true,
            "id" => $id
        ]);
    }

    // ⭐ ACTUALIZAR
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        if(!$this->model->find($id)){
            return $this->failNotFound("Piso no existe");
        }

        $this->model->update($id,$data);

        return $this->respond([
            "ok" => true
        ]);
    }

    // ⭐ ELIMINAR
    public function delete($id = null)
    {
        if(!$this->model->find($id)){
            return $this->failNotFound("Piso no existe");
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            "ok" => true
        ]);
    }

    // ⭐🔥 NUEVA FUNCION PARA MODAL PMS
    public function listar_pisos()
    {
        $data = $this->model
            ->select('id, Piso')
            ->where('activo',1)
            ->orderBy('id','ASC')
            ->findAll();

        return $this->respond($data);
    }

    

}