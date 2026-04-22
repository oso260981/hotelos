<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("ALTER TABLE turnos_operacion ADD COLUMN fondo_caja_inicial DECIMAL(10,2) NULL DEFAULT 0.00");
    $pdo->exec("ALTER TABLE turnos_operacion ADD COLUMN fondo_caja_final DECIMAL(10,2) NULL DEFAULT 0.00");
    echo "Columns added successfully.\n";
} catch (Exception $e) {
    echo "Error or already exists: " . $e->getMessage() . "\n";
}
