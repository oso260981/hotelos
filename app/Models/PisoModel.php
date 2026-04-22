<?php

namespace App\Models;
use CodeIgniter\Model;

class PisoModel extends Model
{
    protected $table = 'pisos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Piso','Nombre','activo'];
}