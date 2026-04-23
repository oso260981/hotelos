<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW CREATE VIEW vw_trabajo");
    print_r($res->fetch(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
