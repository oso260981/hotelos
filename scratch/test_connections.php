<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'hotelos'; // Assumption

try {
    $dsn = "mysql:host=$hostname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión a localhost exitosa.\n";
    
    $stmt = $pdo->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Bases de datos disponibles:\n";
    print_r($dbs);

} catch (PDOException $e) {
    echo "Error en localhost: " . $e->getMessage() . "\n";
}

$hostname = 'srv940.hstgr.io';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';
$database = 'u653032309_hotel_pms';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "\nConexión a Hostinger ($hostname) exitosa.\n";
    
    $stmt = $pdo->query("DESCRIBE salidas_clientes");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Estructura de salidas_clientes:\n";
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
    }

} catch (PDOException $e) {
    echo "\nError en Hostinger ($hostname): " . $e->getMessage() . "\n";
}
