<?php

require_once 'app/Config/Database.php';
$dbConfig = new \Config\Database();
$conf = $dbConfig->default;

$c = mysqli_connect($conf['hostname'], $conf['username'], $conf['password'], $conf['database']);

if (!$c) {
    die("Error de conexión: " . mysqli_connect_error());
}

// 1. Crear tabla de sesiones
$sql1 = "CREATE TABLE IF NOT EXISTS registro_sesiones_firma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tablet_id VARCHAR(50) UNIQUE,
    ocr_registro_id INT NULL,
    status ENUM('WAIT', 'READY') DEFAULT 'WAIT',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($c, $sql1)) {
    echo "Tabla registro_sesiones_firma lista.\n";
} else {
    echo "Error creando tabla: " . mysqli_error($c) . "\n";
}

// 2. Insertar registro inicial
mysqli_query($c, "INSERT IGNORE INTO registro_sesiones_firma (id, tablet_id, status) VALUES (1, 'TABLET_01', 'WAIT')");

// 3. Añadir columna firma_path
$sql2 = "ALTER TABLE ocr_registros ADD COLUMN IF NOT EXISTS firma_path VARCHAR(255) NULL AFTER es_menor";
if (mysqli_query($c, $sql2)) {
    echo "Columna firma_path lista.\n";
} else {
    echo "Error alterando tabla ocr_registros: " . mysqli_error($c) . "\n";
}

mysqli_close($c);
echo "PROCESO FINALIZADO.";
