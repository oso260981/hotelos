<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>HotelOS Login</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
<style>
body{background:#1a1a2e;font-family:Roboto;display:flex;align-items:center;justify-content:center;height:100vh;}
.login-box{width:380px;background:white;border-radius:10px;padding:40px;box-shadow:0 10px 40px rgba(0,0,0,.4);}
.logo{text-align:center;font-size:28px;font-weight:700;color:#1565c0;margin-bottom:30px;}
input{width:100%;padding:12px;margin-bottom:15px;border:1px solid #ccc;border-radius:6px;}
button{width:100%;padding:12px;background:#1565c0;color:white;border:none;border-radius:6px;font-weight:700;cursor:pointer;}
button:hover{background:#1976d2;}
.error{background:#ffebee;padding:10px;margin-bottom:10px;color:#c62828;border-radius:5px;font-size:14px;}
</style>
</head>
<body>
<div class="login-box">
<div class="logo">HotelOS</div>
<?php if(isset($_GET['error'])){ ?><div class="error">Usuario o contraseña incorrectos</div><?php } ?>
<form action="controllers/AuthController.php" method="POST">
<input type="text" name="username" placeholder="Usuario" required>
<input type="password" name="password" placeholder="Contraseña" required>
<button type="submit">Iniciar sesión</button>
</form>
</div>
</body>
</html>