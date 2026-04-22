<?php session_start(); if(!isset($_SESSION['user_id'])){header("Location:index.php");} ?>
<!DOCTYPE html>
<html>
<head>
<title>Pisos</title>
</head>
<body>
<h2>Modulo Pisos (base CRUD)</h2>
<form>
<input placeholder="Nombre piso">
<button>Guardar</button>
</form>
<br>
<a href="dashboard.php">← volver</a>
</body>
</html>