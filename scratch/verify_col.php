<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW COLUMNS FROM registros LIKE 'id_perfil'");
    if($res->fetch()) echo "EXISTS"; else echo "MISSING";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
