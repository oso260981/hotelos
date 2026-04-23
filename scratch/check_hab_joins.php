<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SELECT h.id, h.tipo_habitacion_id, h.piso_id FROM habitaciones h WHERE h.activa = 1 LIMIT 5");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
