<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Reportes extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        // =========================
        // 📅 PARAMS
        // =========================
        $fecha = date('Y-m-d');
        $turno = 1;

        // =========================
        // 📥 REPORTE 1
        // =========================
        $builder = $db->table('registros r');
        $builder->select('r.*, h.numero, h.status');
        $builder->join('habitaciones h', 'h.id = r.habitacion_id');
        $builder->where('DATE(r.created_at)', $fecha);
        $builder->where('r.turno_id', $turno);

        $registros = $builder->get()->getResultArray();

        // =========================
        // 📊 SUMMARY
        // =========================
        $summary = [
            'ocupadas' => count($registros),
            'total' => 14,
            'total_recaudado' => array_sum(array_column($registros,'total')),
            'entradas' => count($registros),
            'salidas' => count(array_filter($registros, fn($r)=>$r['hora_salida']))
        ];

        // =========================
        // 🔲 SOMBREADOS (REP2)
        // =========================
        $builder2 = $db->table('registros r');
        $builder2->select('r.*, h.numero');
        $builder2->join('habitaciones h', 'h.id = r.habitacion_id');
        $builder2->where('DATE(r.created_at)', $fecha);
        $builder2->orderBy('h.numero','ASC');

        $all = $builder2->get()->getResultArray();

        $habitaciones = [];
        $totales_turno = [1=>0,2=>0,3=>0];

        foreach($all as $r){

            $hab = $r['numero'];
            $t = $r['turno_id'];

            if(!isset($habitaciones[$hab])){
                $habitaciones[$hab] = [
                    'numero'=>$hab,
                    'turnos'=>[]
                ];
            }

            $habitaciones[$hab]['turnos'][$t] = [
                'forma_pago'=>$r['forma_pago'],
                'total'=>$r['total'],
                'nombre_huesped'=>$r['nombre_huesped']
            ];

            $totales_turno[$t] += $r['total'];
        }

        // =========================
// 🗂 REPORTE 3 - DATOS ID / FIRMA / OBS
// =========================
$builder3 = $db->table('registros r');

$builder3->select('
   *
');

$builder3->join('habitaciones h', 'h.id = r.habitacion_id');

// mismo filtro del día
$builder3->where('DATE(r.created_at)', $fecha);

// ordenado por habitación
$builder3->orderBy('h.numero','ASC');

$datos = $builder3->get()->getResultArray();


// =========================
// 📅 REPORTE R DEL DÍA
// =========================
$builder4 = $db->table('registros r');

$builder4->select('
    *
');

$builder4->join('habitaciones h','h.id = r.habitacion_id');
$builder4->where('DATE(r.created_at)', $fecha);
$builder4->orderBy('r.turno_id','ASC');

$rdata = $builder4->get()->getResultArray();


// =========================
// 📊 KPIs
// =========================
$total_dia = array_sum(array_column($rdata,'total'));

$efectivo = array_sum(array_map(fn($r)=> $r['forma_pago']=='E' ? $r['total'] : 0, $rdata));

$tarjeta = array_sum(array_map(fn($r)=> in_array($r['forma_pago'],['TC','TD']) ? $r['total'] : 0, $rdata));

$facturas = count(array_filter($rdata, fn($r)=> $r['forma_pago']=='FACT'));


// =========================
// 📦 AGRUPAR POR TURNO
// =========================
$r_turnos = [];

foreach($rdata as $r){
    $r_turnos[$r['turno_id']][] = $r;
}


// =========================
// ✏️ REPORTE F EDITABLE
// =========================
$builder5 = $db->table('registros r');

$builder5->select('
    *
');

$builder5->join('habitaciones h','h.id = r.habitacion_id');
$builder5->where('DATE(r.created_at)', $fecha);

$fdata = $builder5->get()->getResultArray();

        

        return view('reportes/index', [
            'registros'=>$registros,
            'summary'=>$summary,
            'fecha'=>$fecha,
            'turno'=>$turno,
            'habitaciones'=>$habitaciones,
            'totales_turno'=>$totales_turno,
             // 🔥 NUEVO
             'datos'=>$datos,
             'rdata' => $rdata,
'r_turnos' => $r_turnos,
'total_dia' => $total_dia,
'efectivo' => $efectivo,
'tarjeta' => $tarjeta,
'facturas' => $facturas,
'fdata' => $fdata
        ]);
    }


    public function guardar()
{
    $db = \Config\Database::connect();
    $data = $this->request->getJSON(true);

    foreach($data as $row){

        $db->table('registros')
            ->where('id',$row['id'])
            ->update([
                'precio_base'=>$row['subtotal'],
                'iva'=>$row['iva'],
                'ish'=>$row['ish'],
                'total'=>$row['total'],
                'rfc'=>$row['rfc'],
                'notas'=>$row['notas']
            ]);
    }

    return $this->response->setJSON(['ok'=>true]);
}
}