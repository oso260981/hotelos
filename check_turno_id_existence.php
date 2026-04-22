<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$tables = ['registros', 'registro_room_service', 'registro_pagos', 'registro_cargos'];
foreach ($tables as $table) {
    echo "Table: $table -> ";
    $s = $pdo->query("DESCRIBE $table");
    $found = false;
    foreach ($s->fetchAll() as $r) {
        if ($r['Field'] == 'turno_id') {
            $found = true;
            break;
        }
    }
    echo ($found ? "YES" : "NO") . "\n";
}
