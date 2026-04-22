<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo Roles HotelOS
 */
class RolModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'recepcion',
        'reportes',
        'editar_sistema',
        'activo'
    ];
}
