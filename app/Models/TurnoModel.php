<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo catálogo turnos PMS
 */
class TurnoModel extends Model
{
    protected $table      = 'turnos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'usuario_id',
        'hora_inicio',
        'hora_fin',
        'color',
        'icono',
        'activo'
    ];

}
