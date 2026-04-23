<?php
// Script to test the getHabitaciones endpoint
require_once 'app/Config/Paths.php';
require_once 'vendor/autoload.php';

$app = require_once 'public/index.php'; // This might not work as a standalone script easily

// Let's just check the DB query directly using the same logic as Reservacion.php
try {
    $db = \Config\Database::connect();
    $sql = "
        SELECT 
            h.id                AS habitacion_id,
            h.numero            AS numero,
            p.nombre            AS piso,
            eh.codigo           AS status,
            eh.nombre           AS status_nombre,
            r.id                AS registro_id,
            r.tipo_estadia_id   AS tipo_estadia,
            r.noches            AS noches,
            r.adultos           AS adultos,
            r.niños             AS ninos,
            r.num_personas_ext  AS num_personas_ext,
            r.incluir_en_reporte AS incluir_en_reporte,
            (IFNULL(r.adultos,0) + IFNULL(r.niños,0) + IFNULL(r.num_personas_ext,0)) AS ocupacion_total,

            r.forma_pago_id     AS forma_pago,
            r.precio            AS precio,
            r.iva               AS iva,
            r.ish               AS ish,
            r.total             AS total,
            r.created_at        AS fecha_registro,

            r.hora_entrada      AS hora_entrada_1,
            r.hora_salida       AS hora_salida_1,

            CONCAT(IFNULL(hu.nombre,''), ' ', IFNULL(hu.apellido,'')) AS nombre_huesped,
            COALESCE(r.estado_registro, 'DISPONIBLE') AS estado_registro_val,
            r.observaciones     AS observaciones,

            hu.id               AS huesped_id,
            hu.fotografia       AS fotografia,
            hu.identificacion   AS identificacion,
            hu.firma_path       AS firma_path,

            h.estado_id         AS estado_id,

            hp.nombre           AS tipo_habitacion,
            hp.precio_base,
            hp.precio_persona_extra,
            hp.personas_max     AS capacidad_hab

        FROM habitaciones h
        LEFT JOIN registros r 
            ON r.habitacion_id = h.id 
            AND r.estado_registro IN ('CHECKIN','CHECKOUT', 'DISPONIBLE')
        LEFT JOIN habitaciones_tipos hp 
            ON hp.id = h.tipo_habitacion_id
        LEFT JOIN pisos p 
            ON p.id = h.piso_id
        LEFT JOIN estados_habitacion eh 
            ON eh.id = h.estado_id
        LEFT JOIN huespedes hu 
            ON hu.id = r.huesped_id
        WHERE h.activa = 1
        ORDER BY p.piso ASC, h.numero ASC
    ";
    $res = $db->query($sql)->getResultArray();
    echo "COUNT: " . count($res) . "\n";
    if (count($res) > 0) {
        print_r($res[0]);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
