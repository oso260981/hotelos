<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SELECT * FROM vw_trabajo LIMIT 1");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array_keys($row));
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
