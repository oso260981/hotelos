<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("DESCRIBE salidas_clientes");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($cols);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
