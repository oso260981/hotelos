<?php
require_once "../config/db.php";

$habitacion = $_POST['habitacion_id'];
$nombre = $_POST['nombre'];
$personas = $_POST['personas'];
$pago = $_POST['pago'];
$precio = $_POST['precio'];

$iva = $precio * 0.16;
$ish = $precio * 0.03;
$total = $precio + $iva + $ish;

$q = $conn->prepare("INSERT INTO registros 
(habitacion_id,nombre,personas,forma_pago,precio,iva,ish,total,hora_entrada)
VALUES (?,?,?,?,?,?,?,?,NOW())");

$q->bind_param("isissddd",$habitacion,$nombre,$personas,$pago,$precio,$iva,$ish,$total);
$q->execute();

$conn->query("UPDATE habitaciones SET status='O', ocupada=1 WHERE id=$habitacion");

echo "ok";