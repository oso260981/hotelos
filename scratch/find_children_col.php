<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW COLUMNS FROM registros");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        if (strpos($row['Field'], 'ni') !== false) {
            print_r($row);
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
