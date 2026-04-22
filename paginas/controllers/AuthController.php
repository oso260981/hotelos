<?php
session_start();
require_once "../config/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$q = $conn->prepare("SELECT * FROM usuarios WHERE username=? AND activo=1");
$q->bind_param("s",$username);
$q->execute();
$r = $q->get_result();

if($r->num_rows==1){
    $u = $r->fetch_assoc();
    if(password_verify($password,$u['password'])){
        $_SESSION['user_id']=$u['id'];
        $_SESSION['username']=$u['username'];
        header("Location:../dashboard.php");
    }else{
        header("Location:../index.php?error=1");
    }
}else{
    header("Location:../index.php?error=1");
}
