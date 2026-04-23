<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=hotel_pms", "root", "");
    $tables = ['registros', 'Perfiles_Fiscales'];
    foreach ($tables as $table) {
        echo "Table: $table\n";
        $stmt = $pdo->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['Field']} ({$row['Type']})\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
