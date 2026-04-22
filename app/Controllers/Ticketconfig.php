<?php

/* =====================================================
   CONTROLLER: Ticketconfig
   CRUD catálogo configuraciones ticket
=====================================================*/

namespace App\Controllers;
use App\Models\TicketConfigModel;

class Ticketconfig extends BaseController
{

    /* =========================================
       LISTAR CONFIGURACIONES
    =========================================*/
    public function list()
{
    $db = \Config\Database::connect();

    $data = $db->table('ticket_config tc')
       ->select('tc.id, tc.nombre, tc.descripcion, rs.nombre as razon_social, tc.created_at, tc.activo')
        ->join('razones_sociales rs','rs.id = tc.razon_social_id','left')
        ->orderBy('tc.id','DESC')
        ->get()
        ->getResult();

    return $this->response->setJSON($data);
}

   /* =====================================================
   CREAR NUEVA CONFIGURACIÓN TICKET
=====================================================*/
public function create()
{
    $model = new TicketConfigModel();

    $data = [
    'nombre' => $this->request->getPost('nombre'),
    'descripcion' => $this->request->getPost('descripcion'),
    'razon_social_id' => $this->request->getPost('razon_social_id') ?: null,
    'activo' => 0
    ];

    $model->insert($data);

    return $this->response->setJSON([
        "ok"=>true
    ]);
}




    /* =========================================
       ACTIVAR CONFIGURACIÓN
       Solo una puede quedar activa
    =========================================*/
    public function activate()
{
    $id = $this->request->getVar('id');

    if(!$id){
        return $this->response->setJSON([
            "ok"=>false,
            "msg"=>"ID vacío"
        ]);
    }

    $db = \Config\Database::connect();
    $builder = $db->table('ticket_config');

    // desactivar todas
    $builder->update(['activo'=>0]);

    // activar seleccionada
    $builder->where('id',$id)
            ->update(['activo'=>1]);

    return $this->response->setJSON([
        "ok"=>true,
    ]);
}



    /* =========================================
       GUARDAR (ALTA)
       La edición será en pantalla principal
    =========================================*/
    public function save()
    {
        $model = new TicketConfigModel();

        $data = [
            'razon_social_id' => $this->request->getPost('razon_social_id'),
            'ancho_mm' => $this->request->getPost('ancho_mm'),
            'copias' => $this->request->getPost('copias'),
            'logo_visible' => $this->request->getPost('logo_visible'),
            'mensaje_pie' => $this->request->getPost('mensaje_pie'),

            'ver_habitacion' => $this->request->getPost('ver_habitacion'),
            'ver_huesped' => $this->request->getPost('ver_huesped'),
            'ver_fecha' => $this->request->getPost('ver_fecha'),
            'ver_desglose' => $this->request->getPost('ver_desglose'),
            'ver_pago' => $this->request->getPost('ver_pago'),
            'ver_folio' => $this->request->getPost('ver_folio'),

            'activo' => 0
        ];

        $model->insert($data);

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }


    /* =========================================
       ELIMINAR CONFIGURACIÓN
    =========================================*/
    public function delete()
    {
        $model = new TicketConfigModel();

        $id = $this->request->getPost('id');

        $model->delete($id);

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }

    /* =====================================================
   OBTENER CONFIGURACIÓN ACTIVA
   Se usa al abrir pantalla de tickets
=====================================================*/
public function getActive()
{
    $model = new TicketConfigModel();

    $data = $model
        ->where('activo',1)
        ->first();

    return $this->response->setJSON($data);
}

/* =====================================================
   ACTUALIZAR CONFIGURACIÓN ACTIVA
   Se usa desde pantalla principal (editar sistema)
=====================================================*/
public function updateActive()
{
    $model = new TicketConfigModel();

    // obtener configuración activa actual
    $cfg = $model->where('activo',1)->first();

    if(!$cfg){
        return $this->response->setJSON([
            "ok"=>false,
            "msg"=>"No existe configuración activa"
        ]);
    }

    // datos recibidos desde frontend
    $data = [
        'ancho_mm' => $this->request->getPost('ancho_mm'),
        'copias' => $this->request->getPost('copias'),
        'logo_visible' => $this->request->getPost('logo_visible'),
        'mensaje_pie' => $this->request->getPost('mensaje_pie'),
        'razon_social_id' => $this->request->getPost('razon_social_id'),
        'ver_habitacion' => $this->request->getPost('ver_habitacion'),
        'ver_huesped' => $this->request->getPost('ver_huesped'),
        'ver_fecha' => $this->request->getPost('ver_fecha'),
        'ver_desglose' => $this->request->getPost('ver_desglose'),
        'ver_pago' => $this->request->getPost('ver_pago'),
        'ver_folio' => $this->request->getPost('ver_folio'),
    ];

    // actualizar registro activo
    $model->update($cfg['id'],$data);

    return $this->response->setJSON([
        "ok"=>true
    ]);
}


/* =====================================================
   OBTENER DETALLE CONFIGURACIÓN TICKET
   Se usa al seleccionar registro en modal catálogo
=====================================================*/
public function getDetail()
{
    $model = new TicketConfigModel();

    $id = $this->request->getGet('id');

    $data = $model->find($id);

    return $this->response->setJSON($data);
}


/* =====================================================
   OBTENER CONFIGURACIÓN ACTIVA + DATOS RAZÓN SOCIAL
=====================================================*/
public function active()
{
    $db = \Config\Database::connect();

    $cfg = $db->table('ticket_config tc')
        ->select('tc.*,
                  rs.id as rs_id,
                  rs.nombre as rs_nombre,
                  rs.rfc as rs_rfc,
                  rs.telefono as rs_tel,
                  rs.logo as rs_logo')
        ->join('razones_sociales rs','rs.id = tc.razon_social_id','left')
        ->where('tc.activo',1)
        ->get()
        ->getRow();

    return $this->response->setJSON($cfg);
}





}
