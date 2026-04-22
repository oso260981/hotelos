<?php
require_once "../config/db.php";

$piso = $_GET['piso'];

$sql = "
SELECT h.*,
(
 SELECT COUNT(*) 
 FROM registros r 
 WHERE r.habitacion_id = h.id 
 AND r.status = 'ACTIVO'
) AS ocupada
FROM habitaciones h
WHERE h.piso_id = ?
";

$q = $conn->prepare($sql);
$q->bind_param("i",$piso);
$q->execute();
$r = $q->get_result();

$data = [];

while($row = $r->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);