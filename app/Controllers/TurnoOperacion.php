<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TurnoOperacion extends Controller
{
    // =========================
    // 🔥 ABRIR TURNO
    // =========================
    public function abrir()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Sin datos"
            ]);
        }

        $turno_id   = $data['turno_id'] ?? null;
        $usuario_id = $data['usuario_id'] ?? null;

        if (!$turno_id || !$usuario_id) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Datos incompletos"
            ]);
        }

        // 🔥 validar si ya hay turno abierto hoy
        $existe = $db->table('turnos_operacion')
            ->where('fecha', date('Y-m-d'))
            ->where('estado', 'ABIERTO')
            ->countAllResults();

        if ($existe > 0) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Ya hay un turno abierto"
            ]);
        }

        $fondo = $data['fondo_caja_inicial'] ?? 0;

        // 🔥 insertar turno real
        $db->table('turnos_operacion')->insert([
            'turno_id'           => $turno_id,
            'usuario_id'         => $usuario_id,
            'fecha'              => date('Y-m-d'),
            'hora_inicio_real'   => date('Y-m-d H:i:s'),
            'estado'             => 'ABIERTO',
            'observaciones'      => '',
            'fondo_caja_inicial' => $fondo,
            'created_at'         => date('Y-m-d H:i:s')
        ]);

        // 🔥 CAMBIO AUTOMÁTICO DE ESTATUS DE HABITACIONES
        // Pasar todas las habitaciones Sucias (estado_id = 2) a Limpias (estado_id = 1)
        // Excepto Mantenimiento (ID 3 u otros)
        $db->table('habitaciones')
           ->where('estado_id', 2)
           ->update(['estado_id' => 1]);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Turno iniciado correctamente"
        ]);
    }

    // =========================
    // 🔥 CERRAR TURNO
    // =========================
    private function getReportData($turno_id)
    {
        $db = \Config\Database::connect();
        
        $turno = $db->table('turnos_operacion')->where('id', $turno_id)->get()->getRowArray();
        if (!$turno) return null;

        $fondo_inicial = floatval($turno['fondo_caja_inicial'] ?? 0);
        $efectivo_real = floatval($turno['efectivo_real_entregado'] ?? 0);

        // 1. RESUMEN DE OCUPACIÓN
        $ocupacion = $db->table('registros')
            ->select("
                COUNT(CASE WHEN estado_registro = 'CHECKIN' THEN 1 END) as total_checkins,
                COUNT(CASE WHEN estado_registro = 'CHECKOUT' THEN 1 END) as total_checkouts,
                SUM(adultos + niños) as total_personas
            ")
            ->where('turno_id', $turno_id)
            ->where('incluir_en_reporte', 1)
            ->get()->getRowArray();

        // 2. INGRESOS POR HOSPEDAJE
        $finanzas = $db->table('registros')
            ->select("
                forma_pago_id,
                SUM(total) as total_ingresado,
                SUM(iva) as total_iva,
                SUM(ish) as total_ish
            ")
            ->where('turno_id', $turno_id)
            ->where('incluir_en_reporte', 1)
            ->where('estado_registro !=', 'CANCELADO')
            ->groupBy('forma_pago_id')
            ->get()->getResultArray();

        $ingresos_efectivo = 0;
        $ingresos_tarjeta = 0;
        $total_iva = 0;
        $total_ish = 0;

        foreach ($finanzas as $f) {
            if (in_array($f['forma_pago_id'], [1, 2])) {
                $ingresos_efectivo += floatval($f['total_ingresado']);
            } elseif (in_array($f['forma_pago_id'], [3, 4, 5, 6])) {
                $ingresos_tarjeta += floatval($f['total_ingresado']);
            }
            $total_iva += floatval($f['total_iva']);
            $total_ish += floatval($f['total_ish']);
        }

        // 3. ROOM SERVICE
        $rs = $db->table('registro_room_service')
            ->selectSum('subtotal', 'total_rs_efectivo')
            ->where('turno_id', $turno_id)
            ->where('estado_cargo', 'ENTREGADO')
            ->like('observaciones', 'PAGO EFECTIVO')
            ->get()->getRowArray();

        $total_rs_efectivo = floatval($rs['total_rs_efectivo'] ?? 0);
        $efectivo_esperado = $fondo_inicial + $ingresos_efectivo + $total_rs_efectivo;
        $diferencia = $efectivo_real - $efectivo_esperado;

        return [
            "turno_info" => [
                "id" => $turno_id,
                "numero" => $turno['turno_id'],
                "usuario" => "Recepcionista", // Opcional: buscar nombre de usuario si es necesario
                "fecha" => $turno['fecha'],
                "hora_apertura" => $turno['hora_inicio_real'],
                "hora_cierre" => $turno['hora_fin_real']
            ],
            "ocupacion" => [
                "checkins" => intval($ocupacion['total_checkins'] ?? 0),
                "checkouts" => intval($ocupacion['total_checkouts'] ?? 0),
                "personas" => intval($ocupacion['total_personas'] ?? 0)
            ],
            "finanzas" => [
                "hospedaje_efectivo" => $ingresos_efectivo,
                "hospedaje_tarjeta" => $ingresos_tarjeta,
                "room_service_efectivo" => $total_rs_efectivo,
                "iva" => $total_iva,
                "ish" => $total_ish
            ],
            "arqueo" => [
                "fondo_inicial" => $fondo_inicial,
                "efectivo_esperado" => $efectivo_esperado,
                "efectivo_real" => $efectivo_real,
                "diferencia" => $diferencia
            ]
        ];
    }

    public function cerrar()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true);

        $turno = $db->table('turnos_operacion')->where('estado', 'ABIERTO')->get()->getRowArray();
        if (!$turno) return $this->failNotFound("No hay turno abierto.");

        $turno_id = $turno['id'];
        $efectivo_real = floatval($data['fondo_caja_final'] ?? 0);

        // Calcular datos para el arqueo (usando lógica temporal antes de update)
        $tempReport = $this->getReportDataForCierre($turno, $efectivo_real);

        $db->table('turnos_operacion')
            ->where('id', $turno_id)
            ->update([
                'estado'                    => 'CERRADO',
                'hora_fin_real'             => date('Y-m-d H:i:s'),
                'efectivo_real_entregado'   => $efectivo_real,
                'efectivo_esperado'         => $tempReport['arqueo']['efectivo_esperado'],
                'diferencia_arqueo'         => $tempReport['arqueo']['diferencia'],
                'fondo_caja_final'          => $efectivo_real
            ]);

        $db->table('registros')
            ->where('estado_registro', 'CHECKOUT')
            ->update(['estado_registro' => 'CERRADO']);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Turno cerrado.",
            "reporte" => $this->getReportData($turno_id)
        ]);
    }

    private function getReportDataForCierre($turno, $efectivo_real)
    {
        // Esta es una versión ligera para el cálculo previo al update
        $db = \Config\Database::connect();
        $turno_id = $turno['id'];
        
        $finanzas = $db->table('registros')
            ->selectSum('total', 'total_ingresado')
            ->where('turno_id', $turno_id)
            ->where('incluir_en_reporte', 1)
            ->whereIn('forma_pago_id', [1, 2])
            ->where('estado_registro !=', 'CANCELADO')
            ->get()->getRowArray();

        $rs = $db->table('registro_room_service')
            ->selectSum('subtotal', 'total_rs_efectivo')
            ->where('turno_id', $turno_id)
            ->where('estado_cargo', 'ENTREGADO')
            ->like('observaciones', 'PAGO EFECTIVO')
            ->get()->getRowArray();

        $ing_efec = floatval($finanzas['total_ingresado'] ?? 0);
        $rs_efec = floatval($rs['total_rs_efectivo'] ?? 0);
        $fondo = floatval($turno['fondo_caja_inicial'] ?? 0);
        
        $esperado = $fondo + $ing_efec + $rs_efec;
        return [
            "arqueo" => [
                "efectivo_esperado" => $esperado,
                "diferencia" => $efectivo_real - $esperado
            ]
        ];
    }

    public function pdf($id)
    {
        $data = $this->getReportData($id);
        if (!$data) return "Reporte no encontrado";

        $dompdf = new \Dompdf\Dompdf();
        $html = view('reportes/turno_pdf', ['r' => $data]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Reporte_Turno_{$id}.pdf", ["Attachment" => false]);
        exit;
    }

    public function excel($id)
    {
        $data = $this->getReportData($id);
        if (!$data) return "Reporte no encontrado";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Reporte_Turno_{$id}.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo view('reportes/turno_excel', ['r' => $data]);
        exit;
    }

    // =========================
    // 🔥 VALIDAR TURNO ABIERTO
    // =========================
    public function turnoAbierto()
    {
        $db = \Config\Database::connect();

        $turno = $db->table('turnos_operacion')
            ->where('fecha', date('Y-m-d'))
            ->where('estado', 'ABIERTO')
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $turno
        ]);
    }
}