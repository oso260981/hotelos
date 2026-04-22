<?php
require_once "../config/db.php";

$id = $_POST['id'];
$status = $_POST['status'];

$q = $conn->prepare("UPDATE habitaciones SET status=? WHERE id=?");
$q->bind_param("si",$status,$id);
$q->execute();

echo "ok";