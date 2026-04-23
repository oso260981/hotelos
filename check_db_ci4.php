<?php
define('FCPATH', __DIR__ . '/public/');
chdir(__DIR__);
require 'public/index.php';

$db = \Config\Database::connect();
$query = $db->query("SHOW TABLES LIKE '%turno%'");
print_r($query->getResultArray());

$query = $db->query("DESCRIBE turnos_operacion");
print_r($query->getResultArray());

$query = $db->query("DESCRIBE historial_turnos");
if ($query) print_r($query->getResultArray());

$db->close();
