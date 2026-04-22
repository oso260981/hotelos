<?php

namespace App\Controllers;

use App\Models\TrabajoModel;

class Trabajo extends BaseController
{

    public function index()
    {
        return view('trabajo/index');
    }

    /* =====================================================
       LISTAR HABITACIONES (solo 20 registros)
    ===================================================== */
   public function listar()
{
    $model = new TrabajoModel();

    $piso   = $this->request->getGet('piso');
    $estado = $this->request->getGet('estado');

    $filtros = [
        'piso'   => $piso,
        'estado' => $estado
    ];

    $data = $model->listar($filtros);

    return $this->response->setJSON($data);
}

    /* =====================================================
       OBTENER DETALLE HABITACION
    ===================================================== */
   public function obtener($id)
{
    $db = \Config\Database::connect();

    $row = $db->table('vw_trabajo')
        ->where('Numero_Habitacion', $id)
        ->get()
        ->getRowArray();

    if(!$row){
        return $this->response->setJSON([
            "ok"=>false,
            "msg"=>"Habitación no encontrada"
        ]);
    }

    // ===============================================
    // OBTENER CATÁLOGO TIPO ESTADÍA
    // ===============================================
     $tipos = $db->table('tipo_estadia')
    ->select("id, CONCAT(codigo,' - ',nombre) AS label")
    ->get()
    ->getResultArray();

    // ======================================================
    // CATÁLOGO TIPOS HABITACIÓN
    // ======================================================
     $tiposHab = $db->table('habitaciones_tipos')
    ->select('id, nombre, precio_base, iva, ish, precio_total')
    ->get()
    ->getResultArray();


    return $this->response->setJSON([
    "ok" => true,
    "data" => $row,
    "tipos_estadia" => $tipos,
    "tipos_habitacion" => $tiposHab
    ]);
  }

 

}