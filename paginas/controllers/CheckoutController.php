<?php
require_once "../config/db.php";

$habitacion = $_POST['habitacion_id'];

$conn->query("
UPDATE registros 
SET status='FINALIZADO', hora_salida = NOW()
WHERE habitacion_id = $habitacion
AND status='ACTIVO'
");

$conn->query("
UPDATE habitaciones
SET status='S'
WHERE id = $habitacion
");

echo "ok";