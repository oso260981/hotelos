<?php

namespace App\Models;
use CodeIgniter\Model;

class ConfigPerifericosModel extends Model
{
    protected $table = 'config_perifericos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'clave',
        'activo',
        'nombre_detectado',
        'fecha_actualiza'
    ];
}