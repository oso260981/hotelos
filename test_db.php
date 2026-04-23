<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=3306";
try {
     $pdo = new PDO($dsn, $user, $pass);
     $stmt = $pdo->query("SELECT * FROM estados_habitacion");
     print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}

$pdo = null;
