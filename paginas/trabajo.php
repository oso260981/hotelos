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
<title>HotelOS PMS</title>

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
background:#0f1c3f;
border-radius:12px;
border:2px solid #93c5fd;
display:flex;
flex-direction:column;
overflow:hidden;
box-shadow:0 20px 60px rgba(0,0,0,.6);
position:relative;
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

/* LEGEND */
.legend{
background:#e5e7eb;
padding:6px 10px;
font-size:11px;
}

/* TABLE */
.tablewrap{
flex:1;
background:white;
overflow:auto;
}

table{
border-collapse:collapse;
width:1800px;
font-size:11px;
}

th{
background:#2563eb;
color:white;
padding:6px;
border:1px solid #1e40af;
position:sticky;
top:0;
}

td{
border:1px solid #cbd5e1;
padding:5px;
text-align:center;
}

tbody tr:nth-child(odd){background:#ffffff;}
tbody tr:nth-child(even){background:#f1f5f9;}

tbody tr:hover{
background:#dbeafe;
cursor:pointer;
}

/* STATUS */
.dot{
width:18px;
height:18px;
border-radius:50%;
display:inline-flex;
align-items:center;
justify-content:center;
font-size:10px;
font-weight:700;
}

.sucia{background:#fca5a5}
.limpia{background:#86efac}
.mant{background:#fdba74}
.planta{background:#d8b4fe}
.ocupada{background:#93c5fd}

/* FOOTER */
.footer{
background:#162447;
padding:10px;
color:white;
display:flex;
align-items:center;
justify-content:space-between;
}

.floorbtn{
background:#3b82f6;
padding:6px 14px;
border-radius:5px;
margin-right:5px;
cursor:pointer;
}

/* PANEL ⭐ */
.panel{
position:absolute;
top:0;
right:0;
width:420px;
height:100%;
background:white;
box-shadow:-10px 0 40px rgba(0,0,0,.5);
padding:25px;
transform:translateX(100%);
transition:.35s;
z-index:50;
}

.panel.open{
transform:translateX(0);
}

.input{
width:100%;
padding:10px;
margin-bottom:12px;
border:1px solid #cbd5e1;
border-radius:6px;
}

.btn{
width:100%;
padding:14px;
background:#2563eb;
color:white;
border:none;
border-radius:6px;
font-weight:700;
cursor:pointer;
}

.btn.gray{
background:#64748b;
}

</style>
</head>

<body>

<div class="tabs">
<div class="tab">🏠 Inicio</div>
<div class="tab active">📋 Trabajo</div>
<div class="tab">📊 Reportes</div>
<div class="tab">⚙️ Editar Sistema</div>
<div class="tab">🛂 Lista de Pasajeros</div>
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
CIMASOL SA de CV<br>
CDMX
</div>

</div>

<div class="title">
PANTALLA DE TRABAJO — GESTIÓN DE HABITACIONES
</div>

<div class="legend">
🔴 Sucia &nbsp; 🟢 Limpia &nbsp; 🟠 Mantenimiento &nbsp; 🟣 Planta
</div>

<div class="tablewrap">
<table>
<thead>
<tr>
<th>A</th>
<th>HAB</th>
<th>C</th>
<th>D</th>
<th>E</th>
<th>F — $</th>
<th>H — HORA E</th>
<th>J — HORA S</th>
<th>L — NOMBRE</th>
<th>M+</th>
<th>R⚙</th>
<th>S🎫</th>
<th>›</th>
</tr>
</thead>
<tbody id="tablaHabitaciones"></tbody>
</table>
</div>

<div class="footer">
<div>
PISO:
<span class="floorbtn" onclick="loadFloor('PB')">PB</span>
<span class="floorbtn" onclick="loadFloor('1')">1°</span>
<span class="floorbtn" onclick="loadFloor('2')">2°</span>
<span class="floorbtn" onclick="loadFloor('3')">3°</span>
</div>

<div style="opacity:.6">← Menú</div>
</div>

<!-- PANEL -->
<div class="panel" id="panel">

<h2 id="tituloHab">Habitación</h2>

<input class="input" placeholder="Nombre huésped">
<input class="input" placeholder="Personas">

<select class="input">
<option>Efectivo</option>
<option>Tarjeta</option>
<option>Transferencia</option>
</select>

<input class="input" placeholder="Precio">

<button class="btn">GUARDAR</button>
<br><br>
<button class="btn gray" onclick="cerrarPanel()">CANCELAR</button>

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

function statusDot(s){
let cls="limpia"
let txt="X"
if(s=="S"){cls="sucia";txt="✦"}
if(s=="M"){cls="mant";txt="M"}
if(s=="P"){cls="planta";txt="P"}
return `<span class="dot ${cls}">${txt}</span>`
}

function loadFloor(piso){

let html=""

for(let i=1;i<=15;i++){
let num = piso=="PB" ? 100+i : piso+""+(i<10?"0"+i:i)

html+=`
<tr onclick="abrirPanel('${num}')">
<td>${statusDot("X")}</td>
<td><b>${num}</b></td>
<td></td><td></td><td></td><td></td>
<td></td><td></td><td></td>
<td>＋</td>
<td>⚙</td>
<td>🎫</td>
<td>›</td>
</tr>
`
}

tablaHabitaciones.innerHTML=html

}

function abrirPanel(num){
tituloHab.innerHTML="Habitación "+num
panel.classList.add("open")
}

function cerrarPanel(){
panel.classList.remove("open")
}

loadFloor('PB')

</script>

</body>
</html>