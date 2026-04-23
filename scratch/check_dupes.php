<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT habitacion_id, COUNT(*) as qty FROM registros WHERE estado_registro IN ('CHECKIN', 'CHECKOUT') GROUP BY habitacion_id HAVING qty > 1");
    $dupes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Rooms with multiple active registrations:\n";
    print_r($dupes);

    if (!empty($dupes)) {
        foreach ($dupes as $d) {
            $hid = $d['habitacion_id'];
            $stmt = $pdo->query("SELECT id, habitacion_id, estado_registro, created_at FROM registros WHERE habitacion_id = $hid AND estado_registro IN ('CHECKIN', 'CHECKOUT')");
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
