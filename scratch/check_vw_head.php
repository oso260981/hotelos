<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW COLUMNS FROM vw_trabajo");
    $i = 0;
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        if ($i < 10) print_r($row);
        $i++;
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
