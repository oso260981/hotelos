<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesModel extends Model
{
    protected $table = 'reportes_generados';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'fecha',
        'usuario'
    ];

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // 🔥 Crear encabezado
    public function crearReporte()
    {
        $this->insert([
            'fecha'   => date('Y-m-d H:i:s'),
            'usuario' => session()->get('user') ?? 'sistema'
        ]);

        return $this->getInsertID();
    }

    // 🔥 Obtener datos desde la vista
    public function getFromView($ids)
    {
        return $this->db->table('vw_reporte_turnos')
            ->whereIn('habitacion_id', $ids)
            ->get()
            ->getResultArray();
    }

    // 🔥 Insertar detalle
    public function insertDetalle($reporte_id, $r)
    {
        return $this->db->table('reporte_detalle')->insert([
            'reporte_id'   => $reporte_id,
            'habitacion_id'=> $r['habitacion_id'],
            'habitacion'   => $r['habitacion'],

            't1_pago'      => $r['t1_pago'],
            't1_precio'    => $r['t1_precio'],
            't1_nombre'    => $r['t1_nombre'],

            't2_pago'      => $r['t2_pago'],
            't2_precio'    => $r['t2_precio'],
            't2_nombre'    => $r['t2_nombre'],

            't3_pago'      => $r['t3_pago'],
            't3_precio'    => $r['t3_precio'],
            't3_nombre'    => $r['t3_nombre']
        ]);
    }

    // 🔥 Insertar auditoría
    public function insertAuditoria($data)
    {
        return $this->db->table('reporte_auditoria')->insert([
            'reporte_id'   => $data['reporte_id'],
            'habitacion_id'=> $data['habitacion_id'],
            'usuario'      => $data['usuario']
        ]);
    }
}