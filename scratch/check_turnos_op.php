<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $res = $pdo->query("SHOW COLUMNS FROM turnos_operacion");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "--- DATA ---\n";
    $res = $pdo->query("SELECT * FROM turnos_operacion ORDER BY id DESC LIMIT 5");
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
