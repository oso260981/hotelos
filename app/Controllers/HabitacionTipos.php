<?php

namespace App\Controllers;

use App\Models\HabitacionTipoModel;

class HabitacionTipos extends BaseController
{

    public function index()
    {
        $model = new HabitacionTipoModel();

        $data['tipos'] = $model
            ->where('activo',1)
            ->orderBy('orden','ASC')
            ->findAll();

        return view('habitacion_tipos',$data);
    }

  public function listTiposHabitacion()
{
    $model = new \App\Models\HabitacionTipoModel();

    $data = $model
        ->where('activo', 1)
        ->orderBy('orden', 'ASC')
        ->findAll();

    return $this->response
        ->setContentType('application/json')
        ->setJSON($data);
}
public function saveTipoHabitacion()
{
    $model = new \App\Models\HabitacionTipoModel();

    $id = $this->request->getPost('id');

    $precio_base = (float)$this->request->getPost('precio_base');

    $iva = $precio_base * (cfg('iva')/100);
    $ish = $precio_base * (cfg('ish')/100);
    $total = $precio_base + $iva + $ish;

    $data = [
        'nombre'=>$this->request->getPost('nombre'),
        'precio_base'=>$precio_base,
        'precio_persona_extra'=>$this->request->getPost('precio_persona_extra'),
        'personas_max'=>$this->request->getPost('personas_max'),
        'iva'=>$iva,
        'ish'=>$ish,
        'precio_total'=>$total
    ];

    if($id)
        $model->update($id,$data);
    else
        $model->insert($data);

    return $this->response->setJSON(['ok'=>true]);
}

    public function guardar()
    {
        $model = new HabitacionTipoModel();

        $precio_base = (float)$this->request->getPost('precio_base');
        $precio_extra = (float)$this->request->getPost('precio_persona_extra');
        $personas_extra = (int)$this->request->getPost('personas_extra');

        $iva_cfg = (float)cfg('iva');
        $ish_cfg = (float)cfg('ish');

        // ✔ cálculo PMS real
        $base_calculo = $precio_base + ($precio_extra * $personas_extra);

        $iva = $base_calculo * ($iva_cfg / 100);
        $ish = $base_calculo * ($ish_cfg / 100);
        $total = $base_calculo + $iva + $ish;

        $data = [

            'nombre' => $this->request->getPost('nombre'),
            'clave' => $this->request->getPost('clave'),

            'precio_base' => $precio_base,
            'precio_persona_extra' => $precio_extra,

            'iva' => $iva,
            'ish' => $ish,
            'precio_total' => $total,

            'personas_max' => $this->request->getPost('personas_max'),
            'color' => $this->request->getPost('color'),
            'icono' => $this->request->getPost('icono'),
            'orden' => $this->request->getPost('orden')

        ];

        $model->save($data);

        return redirect()->back();
    }

    public function delete()
{
    $model = new \App\Models\HabitacionTipoModel();

    $id = $this->request->getPost('id');

    if(!$id){
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'ID inválido'
        ]);
    }

    // ⭐ borrado lógico (mejor para PMS)
    $model->update($id, [
        'activo' => 0
    ]);

    return $this->response->setJSON([
        'ok' => true
    ]);
}

public function recalcularImpuestos()
{
    $model = new \App\Models\HabitacionTipoModel();

    $iva_cfg = (float)cfg('iva');
    $ish_cfg = (float)cfg('ish');

    $tipos = $model->where('activo',1)->findAll();

    foreach($tipos as $t){

        $base = (float)$t['precio_base'];

        $iva = $base * ($iva_cfg / 100);
        $ish = $base * ($ish_cfg / 100);
        $total = $base + $iva + $ish;

        $model->update($t['id'],[
            'iva' => $iva,
            'ish' => $ish,
            'precio_total' => $total
        ]);
    }

    return $this->response->setJSON(['ok'=>true]);
}

public function listar_tipos()
{
    $db = \Config\Database::connect();

    $res = $db->table('habitaciones_tipos')
        ->select('id, nombre')
        ->orderBy('nombre','ASC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON($res);
}

}