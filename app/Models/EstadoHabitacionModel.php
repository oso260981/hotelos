<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadoHabitacionModel extends Model
{
    protected $table = 'estados_habitacion';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'codigo',
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activo'
    ];

    protected $returnType = 'array';
}