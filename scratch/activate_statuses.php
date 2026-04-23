<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Activating 'Sucia' and 'Mantenimiento' statuses...\n";
    $stmt = $pdo->prepare("UPDATE estados_habitacion SET activo = 1 WHERE codigo IN ('S', 'M')");
    $stmt->execute();
    echo "Done. Rows affected: " . $stmt->rowCount() . "\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
