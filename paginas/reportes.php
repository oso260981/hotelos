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
<title>HotelOS Reportes</title>

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

/* ===== TABS SUPERIOR ===== */
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

/* ===== FRAME ===== */
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

/* ===== HEADER ===== */
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

/* ===== TITLE ===== */
.title{
background:#dde3ea;
text-align:center;
padding:6px;
font-size:11px;
letter-spacing:3px;
color:#475569;
}

/* ===== CONTENT ===== */
.content{
padding:20px;
overflow:auto;
}

/* ===== HEADER LINE ===== */
.repHead{
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

/* ===== SUBTABS ===== */
.subtabs{
display:flex;
gap:20px;
border-bottom:2px solid #cbd5e1;
padding-bottom:8px;
margin-bottom:20px;
}

.subtab{
font-size:13px;
cursor:pointer;
color:#64748b;
}

.subtab.active{
color:#2563eb;
font-weight:700;
}

/* ===== KPI ===== */
.kpis{
display:flex;
gap:20px;
margin-bottom:20px;
}

.kpi{
flex:1;
background:white;
border-radius:12px;
padding:16px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
border-left:6px solid #2563eb;
}

.kpi.green{border-left-color:#2e7d32}
.kpi.orange{border-left-color:#ef6c00}
.kpi.red{border-left-color:#c62828}

.kpiTitle{
font-size:11px;
color:#64748b;
letter-spacing:1px;
}

.kpiValue{
font-size:26px;
font-weight:700;
margin-top:5px;
}

/* ===== TABLE ===== */
.tablewrap{
background:white;
border-radius:10px;
overflow:auto;
max-height:420px;
box-shadow:0 4px 15px rgba(0,0,0,.15);
}

table{
border-collapse:collapse;
width:100%;
font-size:12px;
}

th{
background:#2563eb;
color:white;
padding:8px;
position:sticky;
top:0;
}

td{
padding:7px;
border-bottom:1px solid #e2e8f0;
}

tbody tr:nth-child(even){
background:#f8fafc;
}

tbody tr:hover{
background:#e3f2fd;
}

</style>
</head>

<body>

<div class="tabs">
<div class="tab">🏠 Inicio</div>
<div class="tab">📋 Trabajo</div>
<div class="tab active">📊 Reportes</div>
<div class="tab">⚙️ Editar Sistema</div>
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

<div class="title">SACAR REPORTE</div>

<div class="content">

<div class="repHead">
<div>
<button class="btn btnBack">← Menú</button>
<span style="margin-left:10px;font-weight:600;color:#2563eb">📊 Reportes</span>
<span style="margin-left:10px;color:#64748b">09/03/2026 — Turno 1</span>
</div>

<div>
<button class="btn btnGreen">🖨 Imprimir</button>
<button class="btn btnGray">💾 Exportar</button>
</div>
</div>

<div class="subtabs">
<div class="subtab active">📄 Reporte Real de Turno</div>
<div class="subtab">🟪 Sombreados 3 Turnos</div>
<div class="subtab">📁 Datos ID/Firma/OBS</div>
<div class="subtab">🧾 Reporte R del Día</div>
<div class="subtab">✏️ Reporte Editable</div>
</div>

<div class="kpis">
<div class="kpi">
<div class="kpiTitle">HABITACIONES OCUPADAS</div>
<div class="kpiValue">9</div>
</div>

<div class="kpi green">
<div class="kpiTitle">TOTAL RECAUDADO</div>
<div class="kpiValue">$3,870</div>
</div>

<div class="kpi orange">
<div class="kpiTitle">ENTRADAS</div>
<div class="kpiValue">11</div>
</div>

<div class="kpi red">
<div class="kpiTitle">SALIDAS</div>
<div class="kpiValue">6</div>
</div>
</div>

<div class="tablewrap">

<table>
<thead>
<tr>
<th>HAB</th>
<th>STATUS</th>
<th>TIPO</th>
<th>PERS</th>
<th>PAGO</th>
<th>SUBTOTAL</th>
<th>IVA</th>
<th>ISH</th>
<th>TOTAL</th>
<th>H. ENTRADA</th>
<th>H. SALIDA</th>
<th>NOMBRE</th>
</tr>
</thead>

<tbody id="tablaReporte"></tbody>

</table>

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

let html=""

for(let i=101;i<=114;i++){
html+=`
<tr>
<td>${i}</td>
<td>✦ Sucia</td>
<td>S</td>
<td>2</td>
<td>E</td>
<td>$294.12</td>
<td>$47.06</td>
<td>$8.82</td>
<td><b>$350</b></td>
<td>09:15</td>
<td>—</td>
<td>CLIENTE DEMO</td>
</tr>
`
}

tablaReporte.innerHTML=html

</script>

</body>
</html>