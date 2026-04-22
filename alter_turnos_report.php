<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Add columns if they don't exist
    $pdo->exec("ALTER TABLE turnos_operacion ADD COLUMN efectivo_real_entregado DECIMAL(10,2) NULL DEFAULT 0.00");
    $pdo->exec("ALTER TABLE turnos_operacion ADD COLUMN diferencia_arqueo DECIMAL(10,2) NULL DEFAULT 0.00");
    $pdo->exec("ALTER TABLE turnos_operacion ADD COLUMN efectivo_esperado DECIMAL(10,2) NULL DEFAULT 0.00");
    echo "Columns added successfully.\n";
} catch (Exception $e) {
    echo "Info: " . $e->getMessage() . "\n";
}
