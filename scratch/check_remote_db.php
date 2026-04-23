<?php
require 'vendor/autoload.php';
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    foreach(['registros', 'Perfiles_Fiscales'] as $t) {
        echo "Table: $t\n";
        $res = $pdo->query("DESCRIBE $t");
        if ($res) {
            while($r = $res->fetch()) echo "- {$r['Field']} ({$r['Type']})\n";
        } else {
            echo "Table $t not found or error\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
