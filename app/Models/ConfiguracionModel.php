<?php

namespace App\Models;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'valor'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getValor($nombre)
    {
        $row = $this->where('nombre',$nombre)->first();
        return $row ? $row['valor'] : null;
    }

    public function setValor($nombre,$valor)
    {
        $row = $this->where('nombre',$nombre)->first();

        if($row)
            return $this->update($row['id'],['valor'=>$valor]);
        else
            return $this->insert([
                'nombre'=>$nombre,
                'valor'=>$valor
            ]);
    }
}