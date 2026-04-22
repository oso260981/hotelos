<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroPagosModel extends Model
{
    protected $table = 'registro_pagos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'registro_id',
        'forma_pago_id',
        'monto',
        'concepto',
        'hora_pago',
        'referencia_pago',
        'banco',
        'estado',
        'sistema',
        'usuario_id',
        'observaciones',
        'tipo_movimiento'
    ];

    protected $useTimestamps = false;

    // ⭐ obtener ledger completo por registro
    public function getByRegistro($registro_id)
{
    return $this->select("
            registro_pagos.*,
            formas_pago.codigo AS forma_pago_codigo,
            formas_pago.descripcion AS forma_pago_descripcion
        ")
        ->join('formas_pago', 'formas_pago.id = registro_pagos.forma_pago_id', 'left')
        ->where('registro_pagos.registro_id', $registro_id)
        ->where('registro_pagos.estado', 'APLICADO')
        ->orderBy('registro_pagos.hora_pago', 'ASC')
        ->findAll();
}

    // ⭐ total cargos
    public function totalCargos($registro_id)
    {
        return $this->where('registro_id', $registro_id)
            ->where('tipo_movimiento', 'CARGO')
            ->selectSum('monto')
            ->first()['monto'] ?? 0;
    }

    // ⭐ total pagos
    public function totalPagos($registro_id)
    {
        return $this->where('registro_id', $registro_id)
            ->where('tipo_movimiento', 'PAGO')
            ->selectSum('monto')
            ->first()['monto'] ?? 0;
    }

    // ⭐ saldo
    public function saldo($registro_id)
    {
        $cargos = $this->totalCargos($registro_id);
        $pagos  = $this->totalPagos($registro_id);

        return $cargos - $pagos;
    }
}