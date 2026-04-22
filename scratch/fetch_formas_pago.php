<?php
$hostname = 'srv940.hstgr.io';
$username = 'u653032309_Hotel';
$password = 'D@nte011273';
$database = 'u653032309_hotel_pms';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, codigo, descripcion FROM formas_pago ORDER BY id ASC";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
echo json_encode($data, JSON_PRETTY_PRINT);
$conn->close();
