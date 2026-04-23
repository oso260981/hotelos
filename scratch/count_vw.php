<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SELECT COUNT(*) FROM vw_trabajo");
    echo "COUNT: " . $res->fetchColumn();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
