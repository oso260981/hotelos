<?php

namespace App\Models;
use CodeIgniter\Model;

class TrabajoModel extends Model
{
    protected $table = 'vw_trabajo';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    public function listar($filtros = [])
{
    $builder = $this->builder();

    // =========================
    // 🏢 FILTRO PISO
    // =========================
    if (!empty($filtros['piso'])) {

        if (is_array($filtros['piso'])) {
            $builder->whereIn('piso', $filtros['piso']);
        } else {
            $builder->where('piso', $filtros['piso']);
        }
    }

    // =========================
    // 🔥 FILTRO ESTADO
    // =========================
    if (!empty($filtros['estado'])) {
        $builder->where('cod_eh', $filtros['estado']);
    }

    // =========================
    // 🔢 ORDEN
    // =========================
    $builder->orderBy('CAST(Numero_Habitacion AS UNSIGNED)', 'ASC');

    return $builder->get()->getResultArray();
}

    public function obtenerHabitacion($id)
    {
        return $this->where('id',$id)->first();
    }
}