<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroCargoModel extends Model
{
    protected $table = 'registro_cargos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'registro_id',
        'concepto',
        'tipo',
        'cantidad',
        'precio_unitario',
        'departamento', // 🔥 nuevo
        'subtotal',
        'iva',
        'ish',
        'total',
        'aplica_iva',
        'aplica_ish',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;

    // =========================
    // 📊 OBTENER CARGOS POR REGISTRO
    // =========================
    public function getByRegistro($registro_id)
    {
        return $this->where('registro_id', $registro_id)
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    // =========================
    // 💰 TOTAL DE CARGOS
    // =========================
    public function getTotalByRegistro($registro_id)
    {
        return $this->select('IFNULL(SUM(total),0) as total')
                    ->where('registro_id', $registro_id)
                    ->where('estado', 'ACTIVO')
                    ->first();
    }
}