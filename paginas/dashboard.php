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
<title>HotelOS</title>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>

*{box-sizing:border-box}

body{
margin:0;
font-family:Roboto;
background:#e5e7eb;
height:100vh;
overflow:hidden;
}

/* TABS */
.tabs{
display:flex;
gap:4px;
padding:8px 10px 0 10px;
background:#0f172a;
}

.tab{
background:#1f2937;
color:#9ca3af;
padding:8px 14px;
border-radius:6px 6px 0 0;
font-size:13px;
cursor:pointer;
}

.tab.active{
background:#f1f5f9;
color:#2563eb;
font-weight:600;
}

/* MAIN FULL */
.mainbox{
height:calc(100vh - 40px);
background:#f1f5f9;
border-top:3px solid #93c5fd;
display:flex;
flex-direction:column;
}

/* TOPBAR */
.topbar{
background:#1e4fa1;
color:white;
display:flex;
justify-content:space-between;
align-items:center;
padding:8px 15px;
}

.top-left{
display:flex;
align-items:center;
gap:10px;
}

.logo{
font-size:18px;
font-weight:700;
letter-spacing:1px;
}

.clock{
background:rgba(0,0,0,.3);
padding:6px 15px;
border-radius:6px;
font-size:22px;
font-weight:700;
letter-spacing:2px;
}

.date{
background:rgba(0,0,0,.3);
padding:6px 10px;
border-radius:6px;
font-size:13px;
}

.turnos{
display:flex;
gap:5px;
}

.turno{
background:white;
color:#2563eb;
padding:3px 10px;
border-radius:4px;
font-weight:700;
font-size:12px;
}

.turno.off{
background:rgba(255,255,255,.2);
color:white;
}

/* TITLE */
.title{
text-align:center;
padding:6px;
font-size:11px;
letter-spacing:3px;
color:#64748b;
border-bottom:1px solid #cbd5e1;
background:#f8fafc;
}

/* BODY */
.bodybox{
flex:1;
display:flex;
align-items:center;
justify-content:center;
flex-direction:column;
}

.subtitle{
font-size:14px;
letter-spacing:3px;
color:#475569;
margin-bottom:25px;
}

/* CARDS */
.cards{
display:flex;
justify-content:center;
gap:40px;
flex-wrap:wrap;
}

.card{
width:270px;
background:white;
border-radius:12px;
border:2px solid #94a3b8;
overflow:hidden;
cursor:pointer;
transition:.25s;
box-shadow:0 10px 30px rgba(0,0,0,.2);
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 20px 40px rgba(0,0,0,.3);
}

.card-head{
padding:30px 20px 20px;
font-size:15px;
font-weight:700;
}

.card-body{
background:#f1f5f9;
padding:14px;
font-size:12px;
color:#475569;
line-height:1.6;
}

.turnoCard .card-head{
background:linear-gradient(135deg,#dbeafe,#bfdbfe);
color:#2563eb;
}

.reporteCard .card-head{
background:linear-gradient(135deg,#dcfce7,#bbf7d0);
color:#15803d;
}

.editarCard .card-head{
background:linear-gradient(135deg,#fef3c7,#fde68a);
color:#ea580c;
}

.pasajerosCard .card-head{
background:linear-gradient(135deg,#fbcfe8,#f9a8d4);
color:#be185d;
}

</style>
</head>

<body>

<div class="tabs">
<div class="tab active">🏠 Inicio</div>
<div class="tab">📋 Trabajo</div>
<div class="tab">📊 Reportes</div>
<div class="tab">⚙️ Editar Sistema</div>
<div class="tab">🛂 Lista de Pasajeros</div>
</div>

<div class="mainbox">

<div class="topbar">

<div class="top-left">
<div class="logo">HotelOS</div>
<div class="clock" id="clock"></div>
<div class="date" id="date"></div>

<div class="turnos">
<div class="turno">1</div>
<div class="turno off">2</div>
<div class="turno off">3</div>
</div>

</div>

<div style="font-size:10px;text-align:right">
CIMASOL SA de CV<br>
CDMX
</div>

</div>

<div class="title">
PANTALLA DE INICIO — MENÚ PRINCIPAL
</div>

<div class="bodybox">

<div class="subtitle">
SELECCIONA UNA OPCIÓN
</div>

<div class="cards">

<div class="card turnoCard">
<div class="card-head">🔑<br><br>EMPIEZA TURNO</div>
<div class="card-body">
✔ Termina el turno anterior<br>
✔ Baja habitaciones<br>
✔ Permite editar errores<br>
✔ Genera reporte inicio
</div>
</div>

<div class="card reporteCard">
<div class="card-head">📊<br><br>SACAR REPORTE</div>
<div class="card-body">
Recepción: Reporte real turno<br>
Sombreados 3 turnos<br>
Datos ID, Firma, OBS
</div>
</div>

<div class="card editarCard">
<div class="card-head">⚙️<br><br>EDITAR SISTEMA</div>
<div class="card-body">
✔ Pisos y habitaciones<br>
✔ Usuarios y claves<br>
✔ Impuestos<br>
✔ Periféricos
</div>
</div>

<div class="card pasajerosCard">
<div class="card-head">🛂<br><br>LISTA PASAJEROS</div>
<div class="card-body">
Registro oficial huéspedes del día<br>
Disponible para autoridades
</div>
</div>

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