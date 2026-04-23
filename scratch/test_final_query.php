<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT 
            h.id                AS habitacion_id,
            h.numero            AS numero,
            p.nombre            AS piso,
            ht.nombre           AS tipo_habitacion,
            ht.personas_max     AS capacidad_hab,
            ht.precio_persona_extra AS precio_persona_extra,
            h.status            AS status,
            h.estado_id         AS estado_id,

            r.id                AS registro_id,
            r.estado_registro   AS estado_registro_val,
            r.noches            AS noches,
            r.adultos           AS adultos,
            r.niños             AS ninos,
            r.num_personas_ext  AS num_personas_ext,
            (r.adultos + r.niños + r.num_personas_ext) AS ocupacion_total,
            r.precio_base       AS precio_base,
            r.precio            AS precio,
            r.iva               AS iva,
            r.ish               AS ish,
            r.total             AS total,
            r.incluir_en_reporte AS incluir_en_reporte,
            r.observaciones     AS observaciones,
            r.hora_entrada      AS hora_entrada_1,
            r.hora_salida       AS hora_salida_1,
            
            te.nombre           AS tipo_estadia,
            fp.descripcion      AS forma_pago,
            CONCAT(hu.nombre, ' ', hu.apellido) AS nombre_huesped,
            r.huesped_id        AS huesped_id

        FROM habitaciones h
        LEFT JOIN pisos p ON h.piso_id = p.id
        LEFT JOIN habitaciones_tipos ht ON h.tipo_habitacion_id = ht.id
        LEFT JOIN registros r 
            ON r.habitacion_id = h.id 
            AND r.estado_registro IN ('CHECKIN', 'CHECKOUT')
        LEFT JOIN tipo_estadia te ON r.tipo_estadia_id = te.id
        LEFT JOIN formas_pago fp ON r.forma_pago_id = fp.id
        LEFT JOIN huespedes hu ON r.huesped_id = hu.id
        WHERE h.activa = 1
        ORDER BY CAST(h.numero AS UNSIGNED) ASC
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "SUCCESS: Found " . count($rows) . " rooms.\n";
    if (count($rows) > 0) {
        print_r($rows[0]);
    }

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
