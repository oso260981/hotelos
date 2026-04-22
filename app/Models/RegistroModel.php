<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroModel extends Model
{
    protected $table      = 'registros';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [

        'habitacion_id',
        'turno_id',
        'huesped_id',
        'tipo_estadia_id',
        'usuario_id',
        'forma_pago_id',

        'estado_servicio',
        'registro_origen_id',
        'estado_registro',
        'fecha_estadia',

        'num_personas',
        
        'noches',
        'precio',
        'precio_base',
        'iva',
        'ish',
        'total',
        'pago_adicional',

        'hora_entrada',
        'hora_salida',
        'hora_salida_real',

        'observaciones',
        'motivo_cancelacion',

        'lista_negra',
        'cliente_frecuente',
        'ticket_emitido',

        'num_personas_ext' ,
        'adultos' ,
        'niños' ,
        'ocupacion_total',
        'incluir_en_reporte',

        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    /* =====================================================
       REGISTRO ACTIVO DE HABITACION
    ===================================================== */
    public function getActivoHabitacion($habitacionId)
    {
        return $this->where('habitacion_id', $habitacionId)
                    ->where('estado_servicio', 'ACTIVO')
                    ->first();
    }


    /* =====================================================
       FINALIZAR ESTANCIA
    ===================================================== */
    public function finalizar($id)
    {
        return $this->update($id, [
            'estado_servicio'   => 'FINALIZADO',
            'hora_salida_real'  => date('Y-m-d H:i:s')
        ]);
    }


    /* =====================================================
       CANCELAR ESTANCIA
    ===================================================== */
    public function cancelar($id, $motivo = null)
    {
        return $this->update($id, [
            'estado_servicio' => 'CANCELADO',
            'motivo_cancelacion' => $motivo
        ]);
    }


    /* =====================================================
       CAMBIO DE HABITACION
    ===================================================== */
    public function marcarCambio($id)
    {
        return $this->update($id, [
            'estado_servicio' => 'CAMBIO'
        ]);
    }


    /* =====================================================
       EXTENDER ESTANCIA
    ===================================================== */
    public function extender($id, $diasExtra = 1)
    {
        $reg = $this->find($id);

        return $this->update($id, [
            'estado_servicio' => 'EXTENDIDO',
            'dias_pagados'    => $reg['dias_pagados'] + $diasExtra
        ]);
    }

}