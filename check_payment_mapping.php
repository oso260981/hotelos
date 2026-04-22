<?php
$host = 'srv940.hstgr.io';
$db   = 'u653032309_hotel_pms';
$user = 'u653032309_Hotel';
$pass = 'D@nte011273';
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$s = $pdo->query('SELECT id, descripcion FROM formas_pago');
print_r($s->fetchAll(PDO::FETCH_ASSOC));
