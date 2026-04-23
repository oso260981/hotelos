<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Simular un registro nuevo
    $huesped_id = 1; // Ya sabemos que existe
    $habitacion_id = 1;
    
    // Insertar registro
    $stmt = $pdo->prepare("INSERT INTO registros (huesped_id, habitacion_id, estado_registro, estado_servicio, fecha_estadia) VALUES (?, ?, 'CHECKIN', 'ACTIVO', CURDATE())");
    $stmt->execute([$huesped_id, $habitacion_id]);
    $registro_id = $pdo->lastInsertId();

    // 2. Simular log de entrada (lo que hace Reservacion.php)
    $stmt = $pdo->prepare("SELECT nombre, apellido FROM huespedes WHERE id = ?");
    $stmt->execute([$huesped_id]);
    $huesped = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombreLog = $huesped['nombre'] . ' ' . $huesped['apellido'];

    $stmt = $pdo->prepare("INSERT INTO salidas_clientes (registro_id, habitacion_id, nombre_huesped, tipo_salida, motivo, fecha_salida) VALUES (?, ?, ?, 'ENTRADA', 'TEST CHECKIN', NOW())");
    $stmt->execute([$registro_id, $habitacion_id, $nombreLog]);

    echo "TEST SUCCESS: Registro $registro_id y Entrada logeada para $nombreLog.\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
