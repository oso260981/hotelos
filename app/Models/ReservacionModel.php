<?php

class ReservacionModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerRegistroActivo($habitacionId)
    {
        $sql = "SELECT id FROM registros 
                WHERE habitacion_id = ? 
                AND hora_salida_1 IS NULL
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$habitacionId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearRegistro($data)
    {
        $sql = "INSERT INTO registros
        (habitacion_id, turno_id, tipo_estadia, dias_pagados,
         num_personas, forma_pago, nombre_huesped, created_at)
        VALUES (?,?,?,?,?,?,?,datetime('now'))";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['habitacion_id'],
            $data['turno_id'],
            $data['tipo_estadia'],
            $data['dias_pagados'],
            $data['num_personas'],
            $data['forma_pago'],
            $data['nombre_huesped']
        ]);

        return $this->db->lastInsertId();
    }

    public function guardarPrecio($registroId, $base, $iva, $ish, $total)
    {
        $sql = "UPDATE registros
                SET precio_base=?, iva=?, ish=?, total=?,
                hora_entrada_1 = datetime('now')
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$base,$iva,$ish,$total,$registroId]);
    }

    public function registrarSalida($registroId)
    {
        $sql = "UPDATE registros
                SET hora_salida_1 = datetime('now')
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$registroId]);
    }

    public function cambiarStatusHabitacion($habitacionId, $status)
    {
        $sql = "UPDATE habitaciones SET status=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status,$habitacionId]);
    }

    public function agregarPagoAdicional($registroId, $monto)
    {
        $sql = "UPDATE registros
                SET pago_adicional = IFNULL(pago_adicional,0) + ?
                WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$monto,$registroId]);
    }

    public function insertarAcompanante($registroId, $data)
    {
        $sql = "INSERT INTO huespedes_extra
                (registro_id, nombre, id_documento, tipo_documento)
                VALUES (?,?,?,?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $registroId,
            $data['nombre'],
            $data['id_documento'],
            $data['tipo_documento']
        ]);
    }
}