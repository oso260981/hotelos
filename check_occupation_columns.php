<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$s = $pdo->query('DESCRIBE registros');
foreach($s->fetchAll() as $r) {
    if (in_array($r['Field'], ['adultos', 'niños', 'personas'])) {
        echo $r['Field'] . " exists\n";
    }
}
