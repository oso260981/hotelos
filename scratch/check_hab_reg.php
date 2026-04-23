<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SELECT h.id as hab_id, r.id as reg_id, r.estado_registro 
                        FROM habitaciones h 
                        LEFT JOIN registros r ON h.id = r.habitacion_id AND r.estado_registro = 'DISPONIBLE'
                        ORDER BY h.id");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
