<?php
require 'app/Config/Constants.php';
require 'system/bootstrap.php';
$db = \Config\Database::connect();
$habitaciones = $db->query("SELECT r.tipo_estadia_id AS tipo_estadia FROM registros r LIMIT 5")->getResultArray();
echo json_encode($habitaciones);
