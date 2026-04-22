<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo Usuarios PMS
 * Maneja CRUD completo y seguridad básica
 */
class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'password_hash',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'rol_id',       
        'activo'
    ];

    protected $useTimestamps = false;

}
