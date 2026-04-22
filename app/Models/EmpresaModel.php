<?php

namespace App\Models;
use CodeIgniter\Model;

class EmpresaModel extends Model
{
    protected $table = 'razones_sociales';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre',
        'rfc',
        'telefono',
        'direccion',
        'logo',
        'activo'
    ];
}
