<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);

if (in_array('egresos', $tables)) {
    $stmt = $pdo->query("DESCRIBE egresos");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if (in_array('formas_pago', $tables)) {
    $stmt = $pdo->query("DESCRIBE formas_pago");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
