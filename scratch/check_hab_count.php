<?php
try {
    $url = "http://localhost/hotelos/public/reservacion/habitaciones";
    // Si no puedo usar localhost, usaré el controlador directamente
    // Simular query directamente
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("
        SELECT 
            h.id                AS habitacion_id,
            h.numero            AS numero,
            p.piso              AS piso
        FROM habitaciones h
        INNER JOIN habitaciones_tipos hp 
            ON hp.id = h.tipo_habitacion_id
        INNER JOIN pisos p 
            ON p.id = h.piso_id
        WHERE h.activa = 1
    ");
    $data = $res->fetchAll(PDO::FETCH_ASSOC);
    echo "COUNT: " . count($data);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
