<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $stmt = $pdo->query("SHOW TABLES LIKE '%turno%'");
    print_r($stmt->fetchAll());
    
    $stmt = $pdo->query("DESCRIBE historial_turnos");
    if ($stmt) print_r($stmt->fetchAll());
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
