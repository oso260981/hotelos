<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT estado_servicio, COUNT(*) as total FROM registros GROUP BY estado_servicio");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($rows);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
