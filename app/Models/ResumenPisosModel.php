<?php

namespace App\Models;
use CodeIgniter\Model;

class ResumenPisosModel extends Model
{
    protected $table = 'Resumen_pisos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [];
}