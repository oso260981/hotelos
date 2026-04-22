<?php

namespace App\Models;
use CodeIgniter\Model;

class EstanciaModel extends Model
{
    protected $table = 'estancias';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'huesped_id',
        'habitacion_id',
        'fecha_checkin',
        'fecha_checkout',
        'precio',
        'forma_pago',
        'personas',
        'estado',
        'notas'
    ];
}