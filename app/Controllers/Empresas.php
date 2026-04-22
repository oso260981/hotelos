<?php

namespace App\Controllers;
use App\Models\EmpresaModel;

class Empresas extends BaseController
{

    /* =========================================
       LISTAR EMPRESAS (CATÁLOGO MODAL)
    =========================================*/
    public function list()
    {
        $model = new EmpresaModel();

        $data = $model
          //  ->where('activo',1)
            ->orderBy('nombre','ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }


    /* =========================================
       GUARDAR (ALTA / EDICIÓN)
    =========================================*/
    public function save()
{
    $model = new EmpresaModel();

    $id = $this->request->getPost('id');

    $data = [
        'nombre'    => $this->request->getPost('nombre'),
        'rfc'       => $this->request->getPost('rfc'),
        'telefono'  => $this->request->getPost('telefono'),
        'direccion' => $this->request->getPost('direccion'),
        'activo'    => 1
    ];

    /* ==========================================
       PROCESAR LOGO
    ==========================================*/
    $file = $this->request->getFile('logo');

    if($file && $file->isValid() && !$file->hasMoved()){

        // generar nombre único
        $newName = $file->getRandomName();

        // mover archivo
        $file->move(ROOTPATH.'public/uploads/empresas',$newName);

        // guardar nombre en BD
        $data['logo'] = $newName;
    }

    /* ==========================================
       INSERT / UPDATE
    ==========================================*/
    if($id){
        $model->update($id,$data);
    }else{
        $model->insert($data);
    }

    return $this->response->setJSON([
        "ok" => true
    ]);
}



    /* =========================================
       ELIMINAR (SOFT DELETE)
    =========================================*/
   public function delete()
{
    $id = $this->request->getPost('id');

    if(!$id){
        return $this->response->setJSON([
            "ok"=>false,
            "msg"=>"ID inválido"
        ]);
    }

    $model = new EmpresaModel();

    $model->delete($id);   // ⭐ BORRADO REAL

    return $this->response->setJSON([
        "ok"=>true
    ]);
}

}