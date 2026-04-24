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

    public function getDashboardData()
    {
        try {
            $db = \Config\Database::connect();
            $hoy = date('Y-m-d');
            
            // 1. KPI SUMMARY
            $totalRooms = $db->table('habitaciones')->where('activa', 1)->countAllResults();
            
            $activeRegistros = $db->query("
                SELECT 
                    COUNT(id) as ocupadas,
                    SUM(total) as ingresos,
                    SUM(precio) as subtotal
                FROM registros 
                WHERE estado_registro IN ('CHECKIN', 'CHECKOUT') 
                AND DATE(hora_entrada) = ?
            ", [$hoy])->getRowArray();

            $ocupadas = (int)($activeRegistros['ocupadas'] ?? 0);
            $ingresos = (float)($activeRegistros['ingresos'] ?? 0);
            $subtotal = (float)($activeRegistros['subtotal'] ?? 0);
            
            $occupancyRate = $totalRooms > 0 ? round(($ocupadas / $totalRooms) * 100, 1) : 0;
            $adr = $ocupadas > 0 ? round($subtotal / $ocupadas, 2) : 0;
            $revpar = $totalRooms > 0 ? round($subtotal / $totalRooms, 2) : 0;

            // 2. WEEKLY TREND (7 Days)
            $weeklyData = [];
            for($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $count = $db->table('registros')
                    ->where('DATE(hora_entrada)', $date)
                    ->whereIn('estado_registro', ['CHECKIN', 'CHECKOUT'])
                    ->countAllResults();
                $weeklyData[] = [
                    'label' => date('D', strtotime($date)),
                    'value' => $count
                ];
            }

            // 3. STATUS DISTRIBUTION
            $distribution = $db->query("
                SELECT eh.nombre as label, COUNT(h.id) as value
                FROM habitaciones h
                JOIN estados_habitacion eh ON h.estado_id = eh.id
                WHERE h.activa = 1
                GROUP BY eh.nombre
            ")->getResultArray();

            // 4. REVENUE BY DEPARTMENT (Using 'tipo' from registro_cargos or just guessing from records)
            $deptRevenue = $db->query("
                SELECT 
                    CASE 
                        WHEN tipo IS NULL OR tipo = '' THEN 'Hospedaje'
                        ELSE tipo 
                    END as label,
                    SUM(total) as value
                FROM registro_cargos
                WHERE DATE(created_at) = ?
                GROUP BY label
            ", [$hoy])->getResultArray();

            // 5. RECENT GUESTS
            $recentGuests = $db->query("
                SELECT 
                    h.numero as habitacion,
                    CONCAT(hu.nombre, ' ', hu.apellido) as huesped,
                    r.estado_registro as estado
                FROM registros r
                JOIN habitaciones h ON r.habitacion_id = h.id
                JOIN huespedes hu ON r.huesped_id = hu.id
                WHERE r.estado_registro != 'DISPONIBLE'
                ORDER BY r.id DESC
                LIMIT 6
            ")->getResultArray();

            return $this->response->setJSON([
                'ok' => true,
                'summary' => [
                    'ingresos' => $ingresos,
                    'ocupacion' => $occupancyRate,
                    'adr' => $adr,
                    'revpar' => $revpar
                ],
                'charts' => [
                    'weekly' => $weeklyData,
                    'distribution' => $distribution,
                    'departments' => $deptRevenue
                ],
                'recentGuests' => $recentGuests
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['ok' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getReporteTrabajo()
{
    try {

        $db = \Config\Database::connect();

        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');
        $turno = $this->request->getGet('turno');

        // =========================
        // 📊 BASE (SQL Query optimizada)
        // =========================
        $sql = "SELECT 
                    h.numero AS Hab,
                    eh.codigo AS Codigo_estado,
                    eh.nombre AS Nombre_estado,
                    ht.clave AS Tipo,
                    (IFNULL(r.adultos,0) + IFNULL(r.niños,0)) AS Pers,
                    fp.codigo AS Pago,
                    r.precio AS SUBTOTAL,
                    r.iva AS IVA,
                    r.ish AS ISH,
                    r.total AS TOTAL,
                    r.hora_entrada AS Entrada,
                    r.hora_salida_real AS Salida,
                    CONCAT(hu.nombre, ' ', hu.apellido) AS Huesped,
                    r.estado_registro,
                    r.estado_servicio,
                    r.turno_id AS Turno,
                    r.incluir_en_reporte
                FROM registros r
                LEFT JOIN habitaciones h ON r.habitacion_id = h.id
                LEFT JOIN habitaciones_tipos ht ON h.tipo_habitacion_id = ht.id
                LEFT JOIN estados_habitacion eh ON r.estado_id = eh.id
                LEFT JOIN formas_pago fp ON r.forma_pago_id = fp.id
                LEFT JOIN huespedes hu ON r.huesped_id = hu.id
                LEFT JOIN turnos_operacion tro ON r.turno_id = tro.id
                WHERE 1=1";

        $params = [];
        
        if (!empty($turno)) {
            $sql .= " AND r.turno_id = ?";
            $params[] = $turno;
        }

        // 👉 Filtrado por fecha (opcional pero recomendado)
        // $sql .= " AND DATE(r.hora_entrada) = ?";
        // $params[] = $fecha;

        $data = $db->query($sql, $params)->getResultArray();

        // =========================
        // 📈 KPIs
        // =========================
        $ocupadas = 0;
        $entradas = 0;
        $salidas  = 0;
        $total    = 0;

        foreach ($data as $row) {
            // 🟢 ocupadas (Habitaciones con registro activo)
            if ($row['estado_registro'] === 'CHECKIN') {
                $ocupadas++;
            }

            // 🟡 entradas (Conteo de huéspedes titulares registrados)
            if (!empty($row['Huesped']) && trim($row['Huesped']) !== '') {
                $entradas++;
            }

            // 🔴 salidas (Habitaciones liberadas)
            if ($row['estado_registro'] === 'CHECKOUT') {
                $salidas++;
            }

            // 💰 total recaudado (usando TOTAL que incluye impuestos)
            $total += (float)$row['TOTAL'];
        }

        $uniqueHabs = count(array_unique(array_column($data, 'Hab')));

        return $this->response->setJSON([
            "ok" => true,
            "kpi" => [
                "ocupadas" => $ocupadas,
                "entradas" => $entradas,
                "salidas"  => $salidas,
                "total"    => round($total, 2),
                "total_habs" => $uniqueHabs
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
    $fecha = $this->request->getGet('fecha') ?: date('Y-m-d');

    $sql = "SELECT 
                h.id AS habitacion_id,
                h.numero AS habitacion,
                MAX(CASE WHEN tro.turno_id = 1 THEN fp.codigo END) AS t1_pago,
                MAX(CASE WHEN tro.turno_id = 1 THEN r.precio END) AS t1_precio,
                MAX(CASE WHEN tro.turno_id = 1 THEN CONCAT(hs.nombre, ' ', hs.apellido) END) AS t1_nombre,
                MAX(CASE WHEN tro.turno_id = 2 THEN fp.codigo END) AS t2_pago,
                MAX(CASE WHEN tro.turno_id = 2 THEN r.precio END) AS t2_precio,
                MAX(CASE WHEN tro.turno_id = 2 THEN CONCAT(hs.nombre, ' ', hs.apellido) END) AS t2_nombre,
                MAX(CASE WHEN tro.turno_id = 3 THEN fp.codigo END) AS t3_pago,
                MAX(CASE WHEN tro.turno_id = 3 THEN r.precio END) AS t3_precio,
                MAX(CASE WHEN tro.turno_id = 3 THEN CONCAT(hs.nombre, ' ', hs.apellido) END) AS t3_nombre
            FROM registros r
            LEFT JOIN habitaciones h ON r.habitacion_id = h.id
            LEFT JOIN formas_pago fp ON fp.id = r.forma_pago_id
            LEFT JOIN huespedes hs ON hs.id = r.huesped_id
            LEFT JOIN turnos_operacion tro ON r.turno_id = tro.id
            WHERE CAST(r.hora_entrada AS DATE) = ?
            GROUP BY h.id, h.numero
            ORDER BY h.numero ASC";

    $data = $db->query($sql, [$fecha])->getResultArray();

    // 🔥 KPIs
    $t1 = 0;
    $t2 = 0;
    $t3 = 0;

    foreach ($data as $row) {
        $t1 += (float)($row['t1_precio'] ?? 0);
        $t2 += (float)($row['t2_precio'] ?? 0);
        $t3 += (float)($row['t3_precio'] ?? 0);
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