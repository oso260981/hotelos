<?php

namespace App\Models;
use CodeIgniter\Model;

class AcompananteModel extends Model
{
    protected $table = 'estancias_acompanantes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'estancia_id',
        'nombre',
        'apellido',
        'tipo',
        'edad',
        'notas'
    ];
}