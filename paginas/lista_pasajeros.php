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
<title>HotelOS Pasajeros</title>

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

/* TABS */
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
background:#eef2f7;
border-radius:14px;
border:2px solid #93c5fd;
display:flex;
flex-direction:column;
overflow:hidden;
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
background:#dde3ea;
text-align:center;
padding:6px;
font-size:11px;
letter-spacing:3px;
color:#475569;
}

/* CONTENT */
.content{
padding:20px;
overflow:auto;
}

/* HEADER */
.headerLine{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:15px;
}

.btn{
padding:8px 16px;
border-radius:8px;
border:none;
cursor:pointer;
font-weight:600;
}

.btnGreen{background:#2e7d32;color:white}
.btnGray{background:#607d8b;color:white}
.btnBack{background:#e0e7ff;color:#1e40af}

/* FILTER CHIPS */
.chips{
display:flex;
gap:10px;
margin-bottom:20px;
}

.chip{
padding:6px 14px;
border-radius:20px;
font-size:12px;
font-weight:600;
cursor:pointer;
border:2px solid;
}

.blue{background:#e3f2fd;border-color:#2563eb;color:#2563eb}
.green{background:#e8f5e9;border-color:#2e7d32;color:#2e7d32}
.orange{background:#fff3e0;border-color:#ef6c00;color:#ef6c00}
.red{background:#ffebee;border-color:#c62828;color:#c62828}
.purple{background:#f3e5f5;border-color:#7b1fa2;color:#7b1fa2}

/* ROOM TITLE */
.roomTitle{
font-size:11px;
letter-spacing:2px;
color:#64748b;
margin-top:20px;
margin-bottom:6px;
}

/* PASSENGER CARD */
.card{
background:white;
border-radius:12px;
padding:14px;
display:flex;
align-items:center;
justify-content:space-between;
box-shadow:0 4px 12px rgba(0,0,0,.12);
margin-bottom:10px;
}

.info{
display:flex;
align-items:center;
gap:12px;
}

.avatar{
width:42px;
height:42px;
border-radius:50%;
background:#e3f2fd;
display:flex;
align-items:center;
justify-content:center;
font-size:20px;
}

.name{
font-weight:700;
font-size:13px;
}

.meta{
font-size:11px;
color:#64748b;
margin-top:3px;
}

.status{
font-size:11px;
text-align:right;
}

.ok{color:#2e7d32}
.warn{color:#ef6c00}
.bad{color:#c62828}

</style>
</head>

<body>

<div class="tabs">
<div class="tab">🏠 Inicio</div>
<div class="tab">📋 Trabajo</div>
<div class="tab">📊 Reportes</div>
<div class="tab">⚙️ Editar Sistema</div>
<div class="tab active">🛂 Lista Pasajeros</div>
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

<div class="title">LISTA DE PASAJEROS — TURNO 1</div>

<div class="content">

<div class="headerLine">
<div>
<button class="btn btnBack">← Menú</button>
<span style="margin-left:10px;font-weight:700;color:#2563eb">Lista de Pasajeros</span>
</div>

<div>
<button class="btn btnGreen">🖨 Imprimir</button>
<button class="btn btnGray">📤 Exportar</button>
</div>
</div>

<div class="chips">
<div class="chip blue">Todos (11)</div>
<div class="chip green">Activos (7)</div>
<div class="chip orange">Con menores (1)</div>
<div class="chip red">Lista negra (1)</div>
<div class="chip purple">VIP (1)</div>
</div>

<div class="roomTitle">HABITACIÓN 101</div>

<div class="card">
<div class="info">
<div class="avatar">👤</div>
<div>
<div class="name">GARCÍA LÓPEZ, JUAN</div>
<div class="meta">INE · Hab 101 · $350 · Entrada 09:15</div>
</div>
</div>

<div class="status ok">
✔ Firma digital<br>
✔ Foto capturada<br>
✔ ID escaneado
</div>
</div>

<div class="card" style="margin-left:40px;border:2px dashed #cbd5e1">
<div class="info">
<div class="avatar" style="background:#f3e5f5">👤</div>
<div>
<div class="name">ACOMPAÑANTE</div>
<div class="meta">Cónyuge verificado</div>
</div>
</div>
</div>

<div class="roomTitle">HABITACIÓN 106</div>

<div class="card">
<div class="info">
<div class="avatar" style="background:#fff3e0">👤</div>
<div>
<div class="name">RODRÍGUEZ SOTO, PEDRO</div>
<div class="meta">INE · Hab 106 · $700</div>
</div>
</div>

<div class="status warn">
⚠ Firma manual<br>
✔ Foto capturada<br>
Menor acompañante
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