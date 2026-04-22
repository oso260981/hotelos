<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Operación diaria de turnos
 */
class TurnoOperacionModel extends Model
{
    protected $table = 'turnos_operacion';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'turno_id',
        'usuario_id',
        'fecha',
        'hora_inicio_real',
        'hora_fin_real',
        'estado',
        'observaciones'
    ];
}
