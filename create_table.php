<?php
// Script temporal para crear la tabla scan_sessions
$db = mysqli_connect('localhost', 'root', '', 'hotelos'); // Ajustar si es necesario, pero usualmente XAMPP es root/vacío

if (!$db) {
    die("Error de conexión: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS scan_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(100) NOT NULL,
    registro_id INT NULL,
    status ENUM('PENDIENTE', 'PROCESANDO', 'COMPLETADO', 'ERROR') DEFAULT 'PENDIENTE',
    image_path VARCHAR(255) NULL,
    data_json TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($db, $sql)) {
    echo "Tabla scan_sessions creada o ya existente.\n";
} else {
    echo "Error creando tabla: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
?>
