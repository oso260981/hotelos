<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroAcompanantesModel extends Model
{
    protected $table            = 'registro_acompanantes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'registro_id',
        'nombre',
        'apellido',
        'tipo_identificacion',
        'numero_identificacion',
        'parentesco',
        'es_menor',
        'orden_llegada',
        'hora_entrada',
        'hora_salida',
        'fotografia',
        'est_ext',
        'identificacion',
        'firma_path',
        'observaciones',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /* =========================================
       Obtener acompañantes por registro
       ========================================= */
    public function getByRegistro($registro_id)
    {
        return $this
            ->where('registro_id', $registro_id)
            ->orderBy('orden_llegada', 'ASC')
            ->findAll();
    }

    /* =========================================
       Obtener acompañantes hospedados
       ========================================= */
    public function getHospedados($registro_id)
    {
        return $this
            ->where('registro_id', $registro_id)
            ->where('estado_estancia', 'Hospedado')
            ->findAll();
    }

    /* =========================================
       Marcar salida acompañante
       ========================================= */
    public function marcarSalida($id)
    {
        return $this->update($id, [
            'hora_salida' => date('Y-m-d H:i:s'),
            'estado_estancia' => 'Salida'
        ]);
    }

}