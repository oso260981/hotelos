<?php
$hostname = 'srv940.hstgr.io';
$database = 'u653032309_hotel_pms';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Identificar registros creados hoy por el bot (con pocos datos)
    $stmt = $pdo->query("SELECT id FROM registros WHERE created_at >= '2026-04-22 22:00:00' AND (precio IS NULL OR precio = 0)");
    $bad_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($bad_ids)) {
        $ids_str = implode(',', $bad_ids);
        echo "Deleting bad test records: $ids_str\n";
        
        $pdo->exec("DELETE FROM salidas_clientes WHERE registro_id IN ($ids_str)");
        $pdo->exec("DELETE FROM registros WHERE id IN ($ids_str)");
        
        echo "Cleanup complete.\n";
    } else {
        echo "No bad test records found.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
