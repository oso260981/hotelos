<?php
try {
    $pdo = new PDO('mysql:host=srv940.hstgr.io;dbname=u653032309_hotel_pms', 'u653032309_Hotel', 'D@nte011273');
    $pdo->exec("ALTER TABLE registros ADD COLUMN id_perfil bigint(20) UNSIGNED NULL AFTER forma_pago_id");
    echo "Column id_perfil added to registros table\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
