<?php

namespace App\Models;
use CodeIgniter\Model;

class HabitacionTipoModel extends Model
{
    protected $table = 'habitaciones_tipos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'clave',
        'precio_base',
        'precio_persona_extra',
        'iva',
        'ish',
        'precio_total',
        'personas_max',
        'color',
        'icono',
        'orden',
        'activo'
    ];
}