<?php
session_start();
if(!isset($_SESSION['user_id'])){
 header("Location:index.php");
 exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>HotelOS Editar</title>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>

*{box-sizing:border-box}

body{
margin:0;
font-family:Roboto;
background:linear-gradient(135deg,#0b1020,#1a1f3a);
height:100vh;
display:flex;
flex-direction:column;
}

/* TABS SUPERIORES */
.tabs{
display:flex;
gap:6px;
padding:10px;
}

.tab{
background:#1f2937;
color:#9ca3af;
padding:8px 16px;
border-radius:8px 8px 0 0;
font-size:13px;
cursor:pointer;
}

.tab.active{
background:#e5e7eb;
color:#2563eb;
font-weight:600;
}

/* FRAME */
.frame{
flex:1;
margin:0 10px 10px 10px;
background:#f1f5f9;
border-radius:12px;
border:2px solid #93c5fd;
display:flex;
flex-direction:column;
overflow:hidden;
}

/* HEADER */
.topbar{
background:#1e4fa1;
color:white;
display:flex;
justify-content:space-between;
align-items:center;
padding:8px 15px;
}

.logo{font-weight:700;font-size:18px}

.clock{
background:rgba(0,0,0,.3);
padding:6px 15px;
border-radius:6px;
font-size:22px;
font-weight:700;
margin-left:10px;
}

.date{
background:rgba(0,0,0,.3);
padding:6px 12px;
border-radius:6px;
margin-left:10px;
}

.turno{
background:white;
color:#2563eb;
padding:3px 10px;
border-radius:4px;
font-weight:700;
font-size:12px;
margin-left:10px;
}

/* TITLE */
.title{
background:#d1d5db;
text-align:center;
padding:6px;
font-size:11px;
letter-spacing:3px;
color:#334155;
}

/* CONTENT */
.content{
padding:18px;
overflow:auto;
}

/* SUBTABS CONFIG */
.cfgTabs{
display:flex;
gap:10px;
margin-bottom:20px;
border-bottom:2px solid #cbd5e1;
padding-bottom:6px;
}

.cfgTab{
background:#e2e8f0;
padding:6px 14px;
border-radius:8px 8px 0 0;
font-size:12px;
cursor:pointer;
}

.cfgTab.active{
background:white;
color:#2563eb;
font-weight:700;
border:1px solid #cbd5e1;
border-bottom:white;
}

/* CARD */
.card{
background:white;
border-radius:10px;
padding:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
margin-bottom:15px;
}

table{
border-collapse:collapse;
width:100%;
font-size:12px;
}

th{
background:#2563eb;
color:white;
padding:6px;
}

td{
padding:6px;
border-bottom:1px solid #e2e8f0;
}

.btn{
padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;
font-weight:600;
}

.btnBlue{background:#2563eb;color:white}
.btnOrange{background:#ef6c00;color:white}
.btnGreen{background:#2e7d32;color:white}

</style>
</head>

<body>

<div class="tabs">
<div class="tab">🏠 Inicio</div>
<div class="tab">📋 Trabajo</div>
<div class="tab">📊 Reportes</div>
<div class="tab active">⚙️ Editar Sistema</div>
<div class="tab">🛂 Lista Pasajeros</div>
</div>

<div class="frame">

<div class="topbar">

<div style="display:flex;align-items:center">
<div class="logo">HotelOS</div>
<div class="clock" id="clock"></div>
<div class="date" id="date"></div>
<div class="turno">1</div>
</div>

<div style="font-size:10px;text-align:right">
CIMASOL SA de CV<br>CDMX
</div>

</div>

<div class="title">EDITAR SISTEMA — CONFIGURACIÓN</div>

<div class="content">

<div class="cfgTabs">
<div class="cfgTab active">🏨 Pisos</div>
<div class="cfgTab">🎫 Tickets</div>
<div class="cfgTab">🔐 Usuarios</div>
<div class="cfgTab">🕐 Turnos</div>
<div class="cfgTab">💰 Impuestos</div>
<div class="cfgTab">🖨 Periféricos</div>
</div>

<div class="card">

<h4>Pisos registrados</h4>

<table>
<thead>
<tr>
<th>PISO</th>
<th>NOMBRE</th>
<th># HAB</th>
<th>ACTIVAS</th>
<th></th>
</tr>
</thead>

<tbody>
<tr>
<td>PB</td>
<td>Planta Baja</td>
<td>4</td>
<td>4</td>
<td><button class="btn btnBlue">Editar</button></td>
</tr>
</tbody>
</table>

<br>

<button class="btn btnBlue">+ Añadir piso</button>
<button class="btn btnOrange">+ Añadir habitación</button>

</div>

</div>

</div>

<script>

function reloj(){
let f=new Date()
clock.innerHTML=f.toLocaleTimeString()
date.innerHTML=f.toLocaleDateString()
}
setInterval(reloj,1000)
reloj()

</script>

</body>
</html>