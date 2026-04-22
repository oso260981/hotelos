<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Habitaciones extends ResourceController
{
    protected $format = 'json';

    // ⭐ LISTADO VISUAL PMS
    public function listado_visual()
    {
        $piso = $this->request->getGet('piso');
        $tipo = $this->request->getGet('tipo');

        $db = \Config\Database::connect();

        $builder = $db->table('habitaciones a')
            ->select("
                a.id,
                a.numero AS room_number,
                p.Piso AS floor,
                t.nombre AS type,
                t.personas_max AS capacity,
                t.icono,
                t.precio_base AS price,
                t.precio_persona_extra,
                a.estado_id
            ")
            ->join('habitaciones_tipos t','a.tipo_habitacion_id = t.id')
            ->join('pisos p','a.piso_id = p.id')
            ->where('a.activa',1)
            ->where('a.estado_id',2); // libres

        if(!empty($piso)){
            $builder->where('p.Piso',$piso);
        }

        if(!empty($tipo) && $tipo !== 'Todos'){
            $builder->where('t.nombre',$tipo);
        }

        $data = $builder
            ->orderBy('a.numero','ASC')
            ->get()
            ->getResultArray();

        return $this->respond($data);
    }

    // ⭐ TIPOS HABITACION
    public function listar_tipos()
    {
        $db = \Config\Database::connect();

        $data = $db->table('habitaciones_tipos')
            ->select('id,nombre')
            ->orderBy('nombre','ASC')
            ->get()
            ->getResultArray();

        return $this->respond($data);
    }

    // ⭐ ACTUALIZAR ESTADO GENÉRICO
    public function actualizar_estado()
    {
        try {
            $json = $this->request->getJSON(true);
            $habitacionId = $json['habitacion_id'] ?? null;
            $estadoId = $json['estado_id'] ?? null;

            if (!$habitacionId || !$estadoId) {
                return $this->respond(["ok" => false, "msg" => "Datos incompletos"], 400);
            }

            $db = \Config\Database::connect();
            $db->table('habitaciones')
                ->where('id', $habitacionId)
                ->update(['estado_id' => $estadoId]);

            return $this->respond(["ok" => true, "msg" => "Estado actualizado"]);
        } catch (\Throwable $e) {
            return $this->respond(["ok" => false, "msg" => $e->getMessage()], 500);
        }
    }

    // ⭐ ACTUALIZAR HABITACIÓN A LIMPIA
    public function marcar_limpia()
    {
        // Wrapper para mantener compatibilidad
        $this->request->setBody(json_encode([
            'habitacion_id' => $this->request->getJSON(true)['habitacion_id'] ?? null,
            'estado_id' => 1
        ]));
        return $this->actualizar_estado();
    }
}


