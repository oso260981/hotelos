<?php

namespace App\Controllers;
use App\Models\PisoModel;
use App\Models\HabitacionModel;
use App\Models\ResumenPisosModel;
use App\Models\EstadoHabitacionModel;
use App\Models\HabitacionTipoModel;

class Sistema extends BaseController
{
    public function index()
    {
        $pisoModel = new PisoModel();
        $habModel  = new HabitacionModel();
        $resumenModel = new ResumenPisosModel();
        $EstadoHabitacionModel = new EstadoHabitacionModel();
        $HabitacionTipoModel = new HabitacionTipoModel();

        $data['pisos'] = $resumenModel->findAll();

        $data['habitaciones'] = $habModel
            ->orderBy('numero','ASC')
            ->findAll();

         $pisoModel = new PisoModel();

        $data['pisosSelect'] = $pisoModel
           ->where('activo',1)
           ->orderBy('piso','ASC')
           ->findAll(); 

         $data['tipHabSelect'] = $HabitacionTipoModel
           ->where('activo',1)
           ->orderBy('nombre','ASC')
           ->findAll(); 
             
           
           

        $data['estados'] = $EstadoHabitacionModel
          
           ->orderBy('nombre','ASC')
           ->findAll();     

        return view('sistema',$data);
    }

 public function guardarPiso()
{
    $model = new PisoModel();

    $model->insert([
        'Piso'   => $this->request->getPost('piso'),   // código PB, 1°, etc
        'Nombre' => $this->request->getPost('nombre'), // descripción
        'activo' => 1
    ]);

    return redirect()->back()->with('ok','Piso creado correctamente');
}

   public function guardarHabitacion()
{
    $model = new HabitacionModel();

    $numero = $this->request->getPost('numero');
    $piso   = $this->request->getPost('piso_id');
    $tipo_habitacion  = $this->request->getPost('tipo_id');

    $existe = $model
        ->where('numero',$numero)
        ->where('piso_id',$piso)
        ->first();

    if($existe){
        return redirect()->back()->with('error','La habitación ya existe en ese piso');
    }

    $model->insert([
        'numero'  => $numero,
        'piso_id' => $piso,
        'tipo_habitacion_id'=> $tipo_habitacion,
        'status'  => 'X',   // limpia default PMS
        'activa'  => 1
    ]);

    return redirect()->back()->with('ok','Habitación creada');
}




public function habitacionesPorPiso($id)
{
    $db = \Config\Database::connect();

    $builder = $db->table('habitaciones h');

    $builder->select('
        h.id,
        h.numero,
        h.activa,
        e.codigo,
        e.nombre,
        e.icono,
        e.color
    ');

    $builder->join('estados_habitacion e','e.id = h.estado_id','left');

    $builder->where('h.piso_id',$id);
    $builder->orderBy('h.numero','ASC');

    $data = $builder->get()->getResultArray();

    return $this->response->setJSON($data);
}

public function cambiarEstadoHabitacion()
{
    $model = new \App\Models\HabitacionModel();

    $id = $this->request->getPost('id');
    $activa = $this->request->getPost('activa');

    $model->update($id,[
        'activa' => $activa
    ]);

    return $this->response->setJSON(['ok'=>true]);
}


public function eliminarHabitacion()
{
    $model = new \App\Models\HabitacionModel();

    $id = $this->request->getPost('id');

    $model->delete($id);

    return $this->response->setJSON(['ok'=>true]);
}

public function crearHabitacionAjax()
{
    $model = new \App\Models\HabitacionModel();

    $data = [
        'numero'  => $this->request->getPost('numero'),
        'piso_id' => $this->request->getPost('piso_id'),
        'status'  => 'X',
        'activa'  => 1
    ];

    $model->insert($data);

    $data['id'] = $model->insertID();

    return $this->response->setJSON($data);
}



public function actualizarPiso()
{
    $model = new \App\Models\PisoModel();

    $id     = $this->request->getPost('id');
    $piso   = $this->request->getPost('piso');
    $nombre = $this->request->getPost('nombre');

    // validar duplicado (muy importante PMS)
    $existe = $model
        ->where('piso', $piso)
        ->where('id !=', $id)
        ->first();

    if($existe){
        return redirect()->back()->with('error','Ya existe ese código de piso');
    }

    $model->update($id,[
        'Piso'   => $piso,
        'Nombre' => $nombre
    ]);

    return redirect()->back()->with('ok','Piso actualizado correctamente');
}

public function guardarEstado()
{
    $model = new EstadoHabitacionModel();

    $id = $this->request->getPost('id');

    $data = [
        'codigo' => $this->request->getPost('codigo'),
        'nombre' => $this->request->getPost('nombre'),
        'descripcion' => $this->request->getPost('descripcion'),
        'icono' => $this->request->getPost('icono')
    ];

    if($id){
        $model->update($id,$data);
    }else{
        $data['activo']=1;
        $model->insert($data);
    }

    return redirect()->back();
}


public function toggleEstado()
{
    $model = new EstadoHabitacionModel();

    $model->update(
        $this->request->getPost('id'),
        ['activo'=>$this->request->getPost('activo')]
    );

    return $this->response->setJSON(['ok'=>true]);
}


public function eliminarEstado()
{
    $model = new EstadoHabitacionModel();

    $model->delete($this->request->getPost('id'));

    return $this->response->setJSON(['ok'=>true]);
}

public function guardarTicket()
{
    $model = new TicketModel();

    $data = [
        'nombre_establecimiento' => $this->request->getPost('nombre'),
        'rfc' => $this->request->getPost('rfc'),
        'telefono' => $this->request->getPost('telefono'),
        'ancho_ticket' => $this->request->getPost('ancho'),
        'logo_ticket' => $this->request->getPost('logo'),
        'copias' => $this->request->getPost('copias'),
        'mensaje_pie' => $this->request->getPost('mensaje'),

        'ver_num_habitacion' => $this->request->getPost('chk_hab') ? 1:0,
        'ver_nombre_huesped' => $this->request->getPost('chk_nombre') ? 1:0,
        'ver_fecha_entrada' => $this->request->getPost('chk_fecha') ? 1:0,
        'ver_desglose' => $this->request->getPost('chk_desglose') ? 1:0,
        'ver_forma_pago' => $this->request->getPost('chk_pago') ? 1:0,
        'ver_folio' => $this->request->getPost('chk_folio') ? 1:0,
    ];

    $model->update(1,$data);

    return redirect()->back()->with('ok','Guardado');
}



}