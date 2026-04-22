<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

echo "--- registro_pagos ---\n";
$stmt = $pdo->query("DESCRIBE registro_pagos");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "--- registro_movimientos ---\n";
$stmt = $pdo->query("DESCRIBE registro_movimientos");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
