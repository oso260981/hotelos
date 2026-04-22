<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomServiceProductoModel extends Model
{
    protected $table = 'room_service_productos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'precio',
        'requiere_inventario',
        'icono',
        'activo',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = 'array';

    // 🔹 Obtener activos (para frontend)
    public function getActivos()
    {
        return $this->where('activo', 1)->findAll();
    }

    // 🔹 Formato listo para JS (tu estructura)
    public function getForFront()
    {
        return $this->select("
                id AS id,
                categoria AS cat,
                nombre AS label,
                precio AS price,
                icono AS icon
            ")
            ->where('activo', 1)
            ->findAll();
    }
}