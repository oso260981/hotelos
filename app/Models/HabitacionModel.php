<?php

namespace App\Models;
use CodeIgniter\Model;

class HabitacionModel extends Model
{
    protected $table = 'habitaciones';
    protected $primaryKey = 'id';

    protected $allowedFields = [
    'numero',
    'piso_id',
    'tipo_habitacion_id',
    'status',
    'activa'
];
}