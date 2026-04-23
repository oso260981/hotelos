<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "COLUMNS IN registros:\n";
    $stmt = $pdo->query("DESCRIBE registros");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\nCOLUMNS IN habitaciones:\n";
    $stmt = $pdo->query("DESCRIBE habitaciones");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
