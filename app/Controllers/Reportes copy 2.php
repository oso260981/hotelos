<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ReportesModel;

class Reportes extends Controller
{
    public function index()
    {
        

        return view('reportes/index');
    }

    public function getReporteTrabajo()
{
    try {

        $db = \Config\Database::connect();

        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');
        $turno = $this->request->getGet('turno');

        // =========================
        // 📊 BASE
        // =========================
        $builder = $db->table('Reporte_general');

        if (!empty($turno)) {
            $builder->where('turno_id', $turno);
        }

        // 👉 importante: filtrar por fecha (ajusta si usas otro campo)
      //  $builder->where('DATE(hora_entrada)', $fecha);

        $data = $builder->get()->getResultArray();

        // =========================
        // 📈 KPIs
        // =========================
        $ocupadas = 0;
        $entradas = 0;
        $salidas  = 0;
        $total    = 0;

        foreach ($data as $row) {

            // 🟢 ocupadas
            if (stripos($row['estados_habitacion'], 'Suc') !== false) {
                $ocupadas++;
            }

            // 🟡 entradas
            if (!empty($row['hora_entrada'])) {
                $entradas++;
            }

            // 🔴 salidas
            if (!empty($row['hora_salida_real'])) {
                $salidas++;
            }

            // 💰 total
            $total += (float)$row['precio_base'];
        }

        return $this->response->setJSON([
            "ok" => true,
            "kpi" => [
                "ocupadas" => $ocupadas,
                "entradas" => $entradas,
                "salidas"  => $salidas,
                "total"    => round($total, 2)
            ],
            "data" => $data
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}

public function getReporteTurnos()
{
    $db = \Config\Database::connect();

    $data = $db->table('vw_reporte_turnos')
        ->orderBy('habitacion','ASC')
        ->get()
        ->getResultArray();

    // 🔥 KPIs
    $t1 = 0;
    $t2 = 0;
    $t3 = 0;

    foreach ($data as $row) {

        $t1 += (float)$row['t1_precio'];
        $t2 += (float)$row['t2_precio'];
        $t3 += (float)$row['t3_precio'];
    }

    return $this->response->setJSON([
        "ok" => true,
        "kpi" => [
            "t1" => $t1,
            "t2" => $t2,
            "t3" => $t3,
            "total" => $t1 + $t2 + $t3
        ],
        "data" => $data
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


public function datosHuespedes()
{
    try {

        $db = \Config\Database::connect();

        // 🔥 filtros
        $doc   = $this->request->getGet('doc');
        $firma = $this->request->getGet('firma');
        $obs   = $this->request->getGet('obs');
        $negra = $this->request->getGet('negra');

        $builder = $db->table('Reporte_datos');

        if ($doc) {
            $builder->where("DOC IS NOT NULL");
            $builder->where("DOC !=", "");
        }

        if ($firma) {
            $builder->where("firma_path IS NOT NULL");
            $builder->where("firma_path !=", "");
        }

        if ($obs) {
            $builder->where("OBSERVACIONES IS NOT NULL");
            $builder->where("OBSERVACIONES !=", "");
        }

        if ($negra) {
            $builder->like("LISTA", "NEGRA");
        }

        $data = $builder
            ->orderBy("HAB", "ASC")
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $data
        ]);

    } catch (\Exception $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}


public function reporteDia()
{
    try {

        $db = \Config\Database::connect();

        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');

        // 🔹 DATA
        $data = $db->query("
            SELECT 
                CASE 
                    WHEN r.turno_id = 1 THEN 'T1'
                    WHEN r.turno_id = 2 THEN 'T2'
                    WHEN r.turno_id = 3 THEN 'T3'
                    ELSE 'OTRO'
                END AS turno,

                h.numero AS habitacion,

                CONCAT(hu.apellido, ' ', hu.nombre) AS nombre,

                fp.codigo AS pago,

                r.precio AS subtotal,
                r.iva,
                r.ish,
                r.total,

                TIME(r.hora_entrada) AS entrada,
                TIME(r.hora_salida) AS salida

            FROM registros r
            INNER JOIN habitaciones h ON h.id = r.habitacion_id
            LEFT JOIN huespedes hu ON hu.id = r.huesped_id
            LEFT JOIN formas_pago fp ON fp.id = r.forma_pago_id

            WHERE r.fecha_estadia = ?

            ORDER BY r.turno_id, CAST(h.numero AS UNSIGNED)
        ", [$fecha])->getResultArray();

        // 🔹 KPIs
        $kpi = $db->query("
            SELECT 
                SUM(r.total) AS total,

                SUM(CASE 
                    WHEN fp.codigo = 'E' THEN r.total 
                    ELSE 0 
                END) AS efectivo,

                SUM(CASE 
                    WHEN fp.codigo IN ('TC','TD') THEN r.total 
                    ELSE 0 
                END) AS tarjeta,

                SUM(CASE 
                    WHEN r.ticket_emitido = 1 THEN 1 
                    ELSE 0 
                END) AS facturas

            FROM registros r
            LEFT JOIN formas_pago fp ON fp.id = r.forma_pago_id

            WHERE r.fecha_estadia = ?
        ", [$fecha])->getRowArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $data,
            "kpi" => $kpi
        ]);

    } catch (\Exception $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}

public function reporteFiscal()
{
    try {

        $db = \Config\Database::connect();

        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');

        $data = $db->query("
            SELECT 
                r.id,
                h.numero AS habitacion,
                CONCAT(hu.apellido, ' ', hu.nombre) AS nombre,
                fp.codigo AS pago,
				pf.id_perfil,
                COALESCE(pf.rfc, '') AS rfc,
                COALESCE(r.observaciones, '') AS notas,
                r.precio AS subtotal,
                r.iva,
                r.ish,
                r.total
            FROM registros r
            INNER JOIN habitaciones h ON h.id = r.habitacion_id
            LEFT JOIN huespedes hu ON hu.id = r.huesped_id
            LEFT JOIN formas_pago fp ON fp.id = r.forma_pago_id
            LEFT JOIN Perfiles_Fiscales pf  ON r.id_perfil= pf.id_perfil  
           WHERE r.fecha_estadia = ?
            ORDER BY h.numero
        ", [$fecha])->getResultArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $data
        ]);

    } catch (\Exception $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}


public function guardarFiscal()
{
    try {

        $db = \Config\Database::connect();
        $json = $this->request->getJSON(true);

        $usuario = 1;
        $fecha = date('Y-m-d');

        $agrupado = [];

        foreach ($json['data'] as $item) {

            $id = $item['id'];
            $campo = $item['campo'];
            $valor = $item['valor'];

            if (!isset($agrupado[$id])) {
                $agrupado[$id] = [];
            }

            $agrupado[$id][$campo] = $valor;
        }

        foreach ($agrupado as $registro_id => $valores) {

            // 🔥 1. TRAER DATOS ACTUALES + RFC DESDE PERFIL
            $registro = $db->table('registros r')
                ->select('
                    r.*,
                    pf.rfc
                ')
                ->join('Perfiles_Fiscales pf', 'pf.id_perfil = r.id_perfil', 'left')
                ->where('r.id', $registro_id)
                ->get()
                ->getRowArray();

            if (!$registro) continue;

            // =========================
            // 🔥 2. GUARDAR HISTÓRICO (VALORES VIEJOS)
            // =========================
            $db->table('registros_fiscal')->insert([
                'registro_id' => $registro_id,
                'id_perfil' => $registro['id_perfil'],
                'rfc' => $registro['rfc'], // 🔥 RFC DESDE PERFIL
                'precio' => $registro['precio'],
                'iva' => $registro['iva'],
                'ish' => $registro['ish'],
                'total' => $registro['total'],
                'observaciones' => $registro['observaciones'],
                'usuario_id' => $usuario,
                'fecha' => $fecha
            ]);

            // =========================
            // 🔥 3. NUEVOS VALORES
            // =========================
            $precio = isset($valores['precio']) ? floatval($valores['precio']) : $registro['precio'];

            $iva = $precio * 0.16;
            $ish = $precio * 0.03;
            $total = $precio + $iva + $ish;

            $observaciones = $valores['notas'] ?? $registro['observaciones'];

            // =========================
            // 🔥 4. UPDATE REGISTROS
            // =========================
            $db->table('registros')
                ->where('id', $registro_id)
                ->update([
                    'precio' => $precio,
                    'iva' => $iva,
                    'ish' => $ish,
                    'total' => $total,
                    'observaciones' => $observaciones
                ]);
        }

        return $this->response->setJSON([
            "ok" => true
        ]);

    } catch (\Exception $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}


public function generar()
{
    $request = service('request');
    $data = $request->getJSON(true);

    $ids = $data['habitaciones'] ?? [];
    $nombre = $data['nombre'] ?? 'Sin nombre';

    if (empty($ids)) {
        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Sin selección"
        ]);
    }

    // 🔥 📍 AQUÍ VA (ANTES DE USAR $db)
    $db = \Config\Database::connect();

    // 🔥 guardar encabezado con nombre
    $db->table('reportes_generados')->insert([
        'nombre' => $nombre,
        'fecha' => date('Y-m-d H:i:s'),
        'usuario' => session()->get('user') ?? 'sistema'
    ]);

    $reporte_id = $db->insertID();

    // 🔥 traer datos
    $rows = $db->table('vw_reporte_turnos')
        ->whereIn('habitacion_id', $ids)
        ->get()
        ->getResultArray();

    foreach ($rows as $r) {

        $db->table('reporte_detalle')->insert([
            'reporte_id' => $reporte_id,
            'habitacion_id' => $r['habitacion_id'],

            't1_pago' => $r['t1_pago'],
            't1_precio' => $r['t1_precio'],
            't1_nombre' => $r['t1_nombre'],

            't2_pago' => $r['t2_pago'],
            't2_precio' => $r['t2_precio'],
            't2_nombre' => $r['t2_nombre'],

            't3_pago' => $r['t3_pago'],
            't3_precio' => $r['t3_precio'],
            't3_nombre' => $r['t3_nombre']
        ]);
    }

    return $this->response->setJSON([
        "ok" => true,
        "reporte_id" => $reporte_id
    ]);
}


public function getFromView($ids)
{
    return $this->db->table('vw_reporte_turnos')
        ->whereIn('habitacion_id', $ids)
        ->get()
        ->getResultArray();
}


public function lista()
{
    $model = new ReportesModel();

    return $this->response->setJSON([
        "ok" => true,
        "data" => $model->orderBy('id', 'DESC')->findAll()
    ]);
}

public function detalle($id)
{
    $db = \Config\Database::connect();

    $detalle = $db->table('reporte_detalle d')
        ->select('
            d.habitacion_id,
            v.habitacion,

            d.t1_pago,
            d.t1_precio,
            d.t1_nombre,

            d.t2_pago,
            d.t2_precio,
            d.t2_nombre,

            d.t3_pago,
            d.t3_precio,
            d.t3_nombre
        ')
        ->join('vw_reporte_turnos v', 'v.habitacion_id = d.habitacion_id', 'left')
        ->where('d.reporte_id', $id)
        ->get()
        ->getResultArray();

    return $this->response->setJSON([
        "ok" => true,
        "data" => $detalle
    ]);
}

public function eliminar($id)
{
    $db = \Config\Database::connect();

    // 🔥 eliminar detalle primero
    $db->table('reporte_detalle')
        ->where('reporte_id', $id)
        ->delete();

    // 🔥 eliminar auditoría
    $db->table('reporte_auditoria')
        ->where('reporte_id', $id)
        ->delete();

    // 🔥 eliminar encabezado
    $db->table('reportes_generados')
        ->where('id', $id)
        ->delete();

    return $this->response->setJSON([
        "ok" => true
    ]);
}

public function actualizar($id)
{
    $data = $this->request->getJSON(true);

    $db = \Config\Database::connect();

    foreach ($data['rows'] as $row) {

        $db->table('reporte_detalle')
            ->where('id', $row['id'])
            ->update([
                't1_precio' => $row['t1_precio'],
                't2_precio' => $row['t2_precio'],
                't3_precio' => $row['t3_precio']
            ]);
    }

    return $this->response->setJSON([
        "ok" => true
    ]);
}

}