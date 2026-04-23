<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW CREATE TABLE registros");
    $row = $res->fetch();
    echo $row[1];
} catch (Exception $e) {
    echo $e->getMessage();
}
