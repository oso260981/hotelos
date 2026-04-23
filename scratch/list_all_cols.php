<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['habitaciones_tipos', 'tipo_estadia', 'formas_pago', 'huespedes'];
    foreach ($tables as $table) {
        echo "COLUMNS IN $table:\n";
        $stmt = $pdo->query("DESCRIBE $table");
        $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo implode(", ", $cols) . "\n\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
