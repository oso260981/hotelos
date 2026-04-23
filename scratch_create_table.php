<?php
$mysqli = mysqli_connect('srv940.hstgr.io', 'u653032309_Hotel', 'D@nte011273', 'u653032309_hotel_pms');

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS salidas_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    nombre_huesped VARCHAR(255),
    tipo_salida VARCHAR(50),
    motivo TEXT,
    fecha_salida DATETIME,
    usuario_id INT,
    created_at DATETIME
)";

if (mysqli_query($mysqli, $sql)) {
    echo "Table salidas_clientes created successfully\n";
} else {
    echo "Error creating table: " . mysqli_error($mysqli) . "\n";
}

mysqli_close($mysqli);
?>
