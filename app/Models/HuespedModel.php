<?php

namespace App\Models;
use CodeIgniter\Model;

class HuespedModel extends Model
{
    protected $table = 'huespedes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'tipo_identificacion_id',
        'numero_identificacion',
        'fotografia',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'estado',
        'pais',
        'codigo_postal',
        'nacionalidad',
        'fecha_nacimiento',
        'genero',
        'empresa',
        'notas',
        'activo',
        'fecha_alta',
        'firma_path',
        'identificacion'

    ];
}
