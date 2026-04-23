<?php
require 'app/Config/Database.php';
$dbConfig = new \Config\Database();
$config = $dbConfig->default;

try {
    $dsn = "mysql:host={$config['hostname']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión exitosa a {$config['database']} en {$config['hostname']}\n\n";

    $table = 'salidas_clientes';
    $stmt = $pdo->query("DESCRIBE $table");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Estructura de $table:\n";
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
    }

    echo "\nÚltimos 5 registros:\n";
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
