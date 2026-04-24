<?= view('layout/header') ?>


<style>
/* ======================================================
   🎨 DESIGN SYSTEM & VARIABLES
   ====================================================== */
:root {
    --bg-main: #f8fafc;
    --primary: #1e40af;
    --primary-light: #eff6ff;
    --success: #15803d;
    --success-light: #f0fdf4;
    --warning: #b45309;
    --warning-light: #fffbeb;
    --danger: #b91c1c;
    --danger-light: #fef2f2;
    --slate-600: #475569;
    --slate-400: #94a3b8;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}


/* ======================================================
   🧱 BASE LAYOUT
   ====================================================== */
body {
    background: #0f172a; 
    font-family: 'Inter', -apple-system, sans-serif;
}

.app-container {
    max-width: 100%;
    margin: 0;
    background: var(--bg-main);
    min-height: 100vh;
}

.inner-body {
    padding: 24px;
}

/* ======================================================
   🔝 TOPBAR & NAVIGATION
   ====================================================== */
.inner-topbar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.inner-title {
    font-size: 24px;
    font-weight: 800;
    color: #1e293b;
}

.btn-back {
    padding: 8px 16px;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-weight: 600;
    color: var(--slate-600);
    cursor: pointer;
}

/* ======================================================
   🧭 TABS
   ====================================================== */
.rtab-bar {
    display: flex;
    gap: 4px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 24px;
}

.rtab {
    padding: 5px 12px;
    font-size: 11px;
    font-weight: 600;
    color: var(--slate-600);
    cursor: pointer;
    border-bottom: 2px solid transparent;
}

.rtab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

/* ======================================================
   📊 STATS GRID (KPIs)
   ====================================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stats-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
}

.stats-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--slate-400);
    margin-bottom: 8px;
}

.stats-value {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
}

.stats-card.blue { border-left: 4px solid var(--primary); }
.stats-card.green { border-left: 4px solid var(--success); }
.stats-card.orange { border-left: 4px solid var(--warning); }
.stats-card.red { border-left: 4px solid var(--danger); }


/* ========================= */
/* 📋 TABLA */
/* ========================= */
.g-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}


/* ======================================================
   🛠 TOOLBARS & FILTERS
   ====================================================== */
.report-toolbar {
    background: white;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-sm);
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-chip {
    padding: 6px 16px;
    border-radius: 24px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid var(--border-color);
    background: white;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    color: var(--slate-600);
    display: flex;
    align-items: center;
    gap: 8px;
    user-select: none;
}

.filter-chip:hover {
    border-color: var(--slate-400);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
}

.filter-chip.active {
    border-color: currentColor;
    background: white;
    box-shadow: 0 0 0 1px currentColor;
}

/* Colores específicos para chips */
.f-todas.active { color: var(--primary); background: var(--primary-light); }
.f-dis.active   { color: #0891b2; background: #ecfeff; }
.f-suc.active   { color: var(--danger); background: var(--danger-light); }
.f-limp.active  { color: var(--success); background: var(--success-light); }
.f-mant.active  { color: var(--warning); background: var(--warning-light); }
.f-plant.active { color: #7c3aed; background: #f5f3ff; }

.chip-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.input-date {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    background: white;
}

/* ======================================================
   📋 PREMIUM TABLE (HOTELOS STYLE)
   ====================================================== */
.table-container {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
}

.g-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.g-table thead th {
    background-color: #0c4a6e;
    color: white;
    font-size: 11px;
    text-transform: uppercase;
    padding: 12px 8px;
    position: sticky;
    top: 0;
    z-index: 20;
    border: 1px solid #075985;
}

/* 🔍 ADVANCED GRID FILTERS (REPLICA EXACTA TRABAJO) */
.header-filter {
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.header-filter:hover {
    background-color: #075985 !important;
}

.filter-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    color: #334155;
    min-width: 200px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    border-radius: 1rem;
    z-index: 100;
    border: 1px solid #e2e8f0;
    padding: 15px;
    text-transform: none;
    font-weight: normal;
    text-align: left;
    margin-top: 5px;
}

.header-filter:focus-within .filter-dropdown,
.header-filter.active .filter-dropdown {
    display: block !important;
}

/* Título de selección múltiple */
.dropdown-title {
    font-size: 10px;
    font-weight: 900;
    color: #3b82f6;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}

/* Botones Todos/Resetear */
.dropdown-controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 8px;
}

.btn-control {
    font-size: 9px;
    font-weight: 900;
    text-transform: uppercase;
    color: #6366f1;
    cursor: pointer;
    transition: color 0.2s;
}

.btn-control:hover { color: #4338ca; }
.btn-control.reset { color: #94a3b8; }
.btn-control.reset:hover { color: #f43f5e; }

/* Buscador interno */
.dropdown-search {
    position: relative;
    margin-bottom: 12px;
}

.dropdown-search i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translatey(-50%);
    font-size: 10px;
    color: #cbd5e1;
}

.dropdown-search input {
    width: 100%;
    background: #f8fafc;
    border: none;
    border-radius: 8px;
    padding: 8px 8px 8px 28px;
    font-size: 10px;
    font-weight: 700;
    color: #1e293b;
    outline: none;
}

/* Estilo checkboxes */
.multi-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
}

.multi-option:hover { background: #f1f5f9; }

.multi-option input[type="checkbox"] {
    appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid #cbd5e1;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
}

.multi-option input[type="checkbox"]:checked {
    background: #3b82f6;
    border-color: #3b82f6;
}

.multi-option input[type="checkbox"]:checked::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: white;
    font-size: 9px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.header-content-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0 4px;
    gap: 8px;
}

.sort-icon-container {
    font-size: 10px;
    width: 14px;
    flex-shrink: 0;
    text-align: left;
}

.filter-caret {
    font-size: 10px;
    width: 14px;
    flex-shrink: 0;
    text-align: right;
}

.column-label {
    flex-grow: 1;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.g-table thead th {
    background: #1565c0;
    color: white;
    font-weight: 700;
    text-align: left;
    padding: 6px 12px;
    border-bottom: 2px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 10;
}

.g-table tbody td {
    padding: 4px 12px;
    border-bottom: 1px solid var(--border-color);
    color: #1e293b;
    vertical-align: middle;
}

.g-table tbody tr:hover {
    background: #f1f5f9;
}

.g-table .shaded td {
    background: #fff8e1 !important;
}

/* 🔴 STATUS DOTS (FROM SKETCH) */
.status-dot { 
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:50%; font-size:10px; font-weight:700; 
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.s-dirty { background:#ef9a9a; color:#b71c1c; border: 1px solid #ef5350; }
.s-clean { background:#a5d6a7; color:#1b5e20; border: 1px solid #66bb6a; }
.s-maint { background:#ffcc80; color:#e65100; border: 1px solid #ffa726; }
.s-plant { background:#ce93d8; color:#4a148c; border: 1px solid #ab47bc; }

/* 💳 PAYMENT BADGES (FROM SKETCH) */
.pay-badge { 
    display:inline-block; padding:2px 8px; border-radius:4px; 
    font-size:10px; font-weight:800; text-transform: uppercase;
    border: 1px solid #cbd5e1;
    background: #f1f5f9;
    color: #475569;
}
.pay-E  { background:#e8f5e9; color:#2e7d32; border-color:#a5d6a7; }
.pay-TC { background:#e3f2fd; color:#1565c0; border-color:#90caf9; }
.pay-TD { background:#f3e5f5; color:#6a1b9a; border-color:#ce93d8; }
.pay-EF { background:#fff3e0; color:#e65100; border-color:#ffcc80; }
.pay-TRA, .pay-TRANS, .pay-TRANSFERENCIA { background:#e0f7fa; color:#006064; border-color:#4dd0e1; }
.pay-DEP, .pay-DEPOSITO { background:#ede7f6; color:#311b92; border-color:#9575cd; }
.pay-COR, .pay-CORTESIA { background:#fce4ec; color:#880e4f; border-color:#f06292; }

/* Status Badges en Tabla */
.badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-block;
}
.badge-success { background: var(--success-light); color: var(--success); }
.badge-warning { background: var(--warning-light); color: var(--warning); }
.badge-danger { background: var(--danger-light); color: var(--danger); }
.badge-primary { background: var(--primary-light); color: var(--primary); }

/* ======================================================
   🔘 BUTTONS
   ===================================================== */
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.btn-primary { background: var(--primary); color: white; }
.btn-primary:hover { background: #1e3a8a; }

.btn-success { background: var(--success); color: white; }
.btn-success:hover { background: #166534; }

.btn-outline { background: white; border: 1px solid var(--border-color); color: var(--slate-600); }
.btn-outline:hover { background: #f1f5f9; }

/* ======================================================
   📱 MODALS
   ====================================================== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-box {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 900px;
    max-height: 85vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-content {
    padding: 20px;
    overflow-y: auto;
}

/* ======================================================
   ✏️ EDITABLE STYLES
   ====================================================== */
.input-edit {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 13px;
    background: #f8fafc;
    transition: all 0.2s;
}

.input-edit:focus {
    border-color: var(--primary);
    background: white;
    outline: none;
    box-shadow: 0 0 0 3px var(--primary-light);
}

.input-edit.editado {
    border-color: var(--warning);
    background: var(--warning-light);
}

.fila-editada {
    background: var(--warning-light) !important;
}

/* UTILS */
.text-right { text-align: right; }
.font-bold { font-weight: 700; }
.w-full { width: 100%; }

/* SCROLLBAR CUSTOM */
::-webkit-scrollbar { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--slate-400); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--slate-600); }
</style>

<!-- ========================= -->
<!-- 📊 REPORTES -->
<!-- ========================= -->


<div class="app-container">
    <!-- todo tu contenido -->


<div class="scr visible" id="screen3">

  

    <div class="inner-body">

        <div class="inner-topbar">
            <button class="btn-back" onclick="window.history.back()">← Volver al Dashboard</button>
            <div class="inner-title">Control de Reportes Centralizado</div>
            
            <div style="margin-left:auto; display:flex; gap:12px;">
                <button class="btn-action btn-outline" onclick="abrirModalReportes()">
                    <span class="icon">📂</span> Historial de Reportes
                </button>
                <button class="btn-action btn-success" onclick="window.print()">
                    <span class="icon">🖨️</span> Imprimir Todo
                </button>
            </div>
        </div>

        <!-- ========================= -->
        <!-- 🧭 TABS (ESTO TE FALTABA) -->
        <!-- ========================= -->
        <div class="rtab-bar">
            <div class="rtab active" onclick="showRep(1,this)">Reporte de Operación</div>
            <div class="rtab" onclick="showRep(2,this)">Consolidado de Turnos</div>
            <div class="rtab" onclick="showRep(3,this)">Expediente Huéspedes</div>
            <div class="rtab" onclick="showRep(4,this)">Reporte del Día</div>
            <div class="rtab" onclick="showRep(5,this)">Gestión Fiscal (Auditoría)</div>
        </div>

        <!-- ========================= -->
        <div id="rep1">
            <!-- KPIs -->
            <div class="stats-grid">
                <div class="stats-card blue">
                    <div class="stats-label">Habitaciones ocupadas</div>
                    <div class="stats-value" id="kpi-ocupadas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">de <span id="kpi-total-habs">0</span> total</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-label">Total recaudado</div>
                    <div class="stats-value" id="kpi-total">$0.00</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">Turno 1</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-label">Entradas (Check-in)</div>
                    <div class="stats-value" id="kpi-entradas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">huéspedes</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-label">Salidas (Check-out)</div>
                    <div class="stats-value" id="kpi-salidas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">habitaciones liberadas</div>
                </div>
            </div>

            <!-- Toolbar Filtros -->
            <div class="report-toolbar">
                <div class="toolbar-group" id="filtrosOperacion">
                    <div class="filter-chip f-todas active" data-filter="Todas">
                        <span class="chip-dot"></span> Todas
                    </div>
                    <div class="filter-chip f-dis" data-filter="Limp">
                        <span class="chip-dot"></span> Disponibles
                    </div>
                    <div class="filter-chip f-suc" data-filter="Suc">
                        <span class="chip-dot"></span> Sucias/Ocupadas
                    </div>
                    <div class="filter-chip f-mant" data-filter="Mant">
                        <span class="chip-dot"></span> Mantenimiento
                    </div>
                    <div class="filter-chip f-plant" data-filter="Plant">
                        <span class="chip-dot"></span> Planta
                    </div>
                </div>

                <div class="toolbar-group" id="filtrosTurno">
                    <span style="font-size: 10px; font-weight: 900; color: var(--slate-400); text-transform: uppercase; margin-right: 8px;">Turnos:</span>
                    <div class="filter-chip f-todas active" data-turno="Todas">Todas</div>
                    <div class="filter-chip f-dis" data-turno="1">T1</div>
                    <div class="filter-chip f-dis" data-turno="2">T2</div>
                    <div class="filter-chip f-dis" data-turno="3">T3</div>
                </div>
                <div class="toolbar-group">
                    <span style="font-size: 10px; font-weight: 900; color: var(--slate-400); text-transform: uppercase; margin-right: 8px;">Entrada:</span>
                    <input type="date" id="filtroFechaEntrada" class="input-date" style="font-size: 11px; padding: 6px 10px; height: 32px; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>

                <div class="toolbar-group">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 900; color: #0c4a6e; cursor: pointer; text-transform: uppercase; background: #e0f2fe; padding: 6px 12px; border-radius: 8px; border: 1px solid #bae6fd;">
                        <input type="checkbox" id="filtroSoloIncluidos" style="width: 16px; height: 16px; cursor: pointer; accent-color: #0369a1;">
                        Solo Incluidos
                    </label>
                </div>

                <div class="toolbar-group">
                    <div style="position:relative;">
                        <input type="text" id="busquedaTabla" placeholder="Buscar habitación o huésped..." 
                               style="padding: 8px 12px 8px 32px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 11px; width: 240px;">
                        <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); opacity:0.5;">🔍</span>
                    </div>
                    <button class="btn-action btn-outline">📥 Excel</button>
                </div>
            </div>

            <div class="table-container">
                <table class="g-table">
                    <thead>
                        <tr id="headersOperacion">
                            <!-- Generado dinámicamente por buildHeaderOperacion() -->
                        </tr>
                    </thead>
                    <tbody id="tabla-trabajo">
                        <tr>
                            <td colspan="12" style="text-align:center; padding: 40px; color: var(--slate-400);">
                                <div style="font-size: 24px; margin-bottom: 8px;">⏳</div>
                                Cargando datos del reporte...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="rep2" style="display:none;">
            <!-- Actions Toolbar -->
            <div class="report-toolbar">
                <div class="toolbar-group">
                    <button class="btn-action btn-primary" onclick="nuevoReporte()">
                        <span>🆕</span> Nuevo Consolidado
                    </button>
                    <button class="btn-action btn-success" onclick="enviarSeleccion()">
                        <span>💾</span> Guardar Selección
                    </button>
                    <button class="btn-action btn-outline" onclick="abrirModalReportes()">
                        <span>📂</span> Abrir Historial
                    </button>
                </div>
                <div class="toolbar-group">
                    <div id="contadorSeleccion" class="badge badge-primary" style="font-size: 14px;">0</div>
                    <span style="font-size: 12px; color: var(--slate-400);">Habs. seleccionadas</span>
                </div>
            </div>

            <!-- Stats Grid for Turnos -->
            <div class="stats-grid">
                <div class="stats-card blue">
                    <div class="stats-label">Turno Matutino (T1)</div>
                    <div class="stats-value" id="t1_total">$0.00</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-label">Turno Vespertino (T2)</div>
                    <div class="stats-value" id="t2_total">$0.00</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-label">Turno Nocturno (T3)</div>
                    <div class="stats-value" id="t3_total">$0.00</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-label">Venta Total Bruta</div>
                    <div class="stats-value" id="total_dia">$0.00</div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="g-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>HAB</th>
                            <th>T1 PAGO</th>
                            <th class="text-right">T1 PRECIO</th>
                            <th>T1 NOMBRE</th>
                            <th>T2 PAGO</th>
                            <th class="text-right">T2 PRECIO</th>
                            <th>T2 NOMBRE</th>
                            <th>T3 PAGO</th>
                            <th class="text-right">T3 PRECIO</th>
                            <th>T3 NOMBRE</th>
                        </tr>
                    </thead>
                    <tbody id="tablaReporteTurnos"></tbody>
                    <tfoot>
                        <tr class="font-bold" style="background: #f8fafc;">
                            <td colspan="2">TOTALES PARCIALES</td>
                            <td></td>
                            <td class="text-right" id="total_t1">$0.00</td>
                            <td></td>
                            <td></td>
                            <td class="text-right" id="total_t2">$0.00</td>
                            <td></td>
                            <td></td>
                            <td class="text-right" id="total_t3">$0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div id="modalReportes" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <h3 class="font-bold">📂 Historial de Reportes Guardados</h3>
                    <button class="btn-action btn-outline" style="padding: 4px 8px;" onclick="cerrarModalReportes()">✖</button>
                </div>
                <div class="modal-content">
                    <div class="table-container">
                        <table class="g-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Reporte</th>
                                    <th>Fecha Creación</th>
                                    <th>Usuario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaModalReportes"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================= -->
        <!-- 🗂 REPORTE 3 - DATOS -->
        <!-- ========================= -->
        <div id="rep3" style="display:none">
            <div class="report-toolbar" id="filtrosHuespedes">
                <div class="toolbar-group">
                    <span style="font-size: 13px; font-weight: 700; color: var(--slate-600);">Filtrar por:</span>
                    <div class="filter-chip" data-filter="doc">🪪 Documentación</div>
                    <div class="filter-chip" data-filter="firma">✍️ Firma Digital</div>
                    <div class="filter-chip" data-filter="obs">💬 Observaciones</div>
                    <div class="filter-chip" data-filter="negra">⛔ Lista Negra</div>
                </div>
                <div class="toolbar-group">
                    <button class="btn-action btn-outline">🖨️ Imprimir Expedientes</button>
                </div>
            </div>

            <div class="table-container">
                <table class="g-table" id="tablaHuespedes">
                    <thead>
                        <tr>
                            <th>HAB</th>
                            <th>NOMBRE DEL HUÉSPED</th>
                            <th>TIPO DOC</th>
                            <th>N° DOCUMENTO</th>
                            <th class="text-center">FIRMA</th>
                            <th class="text-center">FOTO</th>
                            <th>OBSERVACIONES</th>
                            <th>ESTADO SEGURIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 40px; color: var(--slate-400);">
                                Consultando base de datos de huéspedes...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ========================= -->
        <!-- 📅 REPORTE R DEL DÍA -->
        <!-- ========================= -->
        <div id="rep4" style="display:none;">
            <!-- Toolbar Filtros -->
            <div class="report-toolbar">
                <div class="toolbar-group">
                    <label style="font-size: 13px; font-weight: 700; color: var(--slate-600);">Seleccionar Fecha:</label>
                    <input type="date" id="filtroFecha" class="input-date">
                    <button class="btn-action btn-primary" onclick="cargarReporteDia()">
                        🔍 Generar Reporte
                    </button>
                </div>
                <div class="toolbar-group">
                    <div class="badge badge-success" id="fechaDiaDisplay">Día Actual</div>
                </div>
            </div>


            




            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stats-card blue">
                    <div class="stats-label">Venta Total (3 Turnos)</div>
                    <div class="stats-value" id="kpi_total">$0.00</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-label">Ingresos en Efectivo</div>
                    <div class="stats-value" id="kpi_efectivo">$0.00</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-label">Ingresos en Tarjeta</div>
                    <div class="stats-value" id="kpi_tarjeta">$0.00</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-label">Comprobantes Fiscales</div>
                    <div class="stats-value" id="kpi_facturas">0</div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="g-table" id="tablaReporteDia">
                    <thead>
                        <tr>
                            <th>TURNO</th>
                            <th>HAB</th>
                            <th>NOMBRE DEL HUÉSPED</th>
                            <th>FORMA PAGO</th>
                            <th class="text-right">SUBTOTAL</th>
                            <th class="text-right">IVA</th>
                            <th class="text-right">ISH</th>
                            <th class="text-right">TOTAL</th>
                            <th>ENTRADA</th>
                            <th>SALIDA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="text-center" style="padding: 40px; color: var(--slate-400);">
                                Seleccione una fecha para visualizar el reporte consolidado.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ========================= -->
        <!-- ✏️ REPORTE F EDITABLE -->
        <!-- ========================= -->
        <div id="rep5" style="display:none;">
            <!-- Auditor Toolbar -->
            <div class="report-toolbar">
                <div class="toolbar-group">
                    <span style="font-size: 11px; font-weight: 700; color: var(--primary);">🧾 Auditoría Fiscal:</span>
                    <input type="date" id="filtroFechaFiscal" class="input-date" style="padding: 4px 8px; font-size: 11px;">
                    <button class="btn-action btn-primary" onclick="cargarFiscal()" style="padding: 6px 12px;">
                        🔍 Consultar
                    </button>
                </div>
                <div class="toolbar-group">
                    <button class="btn-action btn-success" onclick="guardarFiscal()" style="padding: 6px 12px;">
                        💾 Guardar Auditoría
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stats-card blue">
                    <div class="stats-label">Subtotal Acumulado</div>
                    <div class="stats-value" id="kpi_subtotal">$0.00</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-label">IVA (16%) Calculado</div>
                    <div class="stats-value" id="kpi_iva">$0.00</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-label">ISH (3%) Calculado</div>
                    <div class="stats-value" id="kpi_ish">$0.00</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-label">Total Declarado</div>
                    <div class="stats-value" id="kpi_totalsat">$0.00</div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="g-table" id="tablaFiscal">
                    <thead>
                        <tr>
                            <th>HAB</th>
                            <th>HUESPED</th>
                            <th>PAGO</th>
                            <th class="text-right">SUBTOTAL</th>
                            <th class="text-right">IVA (16%)</th>
                            <th class="text-right">ISH (3%)</th>
                            <th class="text-right">TOTAL</th>
                            <th>RFC / FACTURA</th>
                            <th>NOTAS DE AUDITORÍA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 40px; color: var(--slate-400);">
                                Cargando registros fiscales para auditoría...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


</div>

        <script>
        window.base_url = "<?= site_url('/') ?>";
       
       function sanitize(val, fallback = '-') {
    if (val === null || val === undefined || String(val).trim().toLowerCase() === 'null' || String(val).trim().toLowerCase() === 'undefined' || String(val).trim() === '') return fallback;
    return val;
}

document.addEventListener("input", (e) => {
    

    if (e.target.classList.contains("input-edit")) {

        const input = e.target;
        const original = input.dataset.original;

        if (input.value != original) {

            input.classList.add("editado");
            input.closest("tr").classList.add("fila-editada");

        } else {

            input.classList.remove("editado");

            // si toda la fila ya no tiene edits
            const fila = input.closest("tr");
            if (!fila.querySelector(".editado")) {
                fila.classList.remove("fila-editada");
            }
        }
    }

});

document.addEventListener("DOMContentLoaded", () => {
    cargarReportes();
});




document.addEventListener("DOMContentLoaded", () => {
    cargarImpuestos();
    cargarFiscal();
});

async function cargarImpuestos() {
    try {
        const res = await fetch(window.base_url + 'reservacion/obtenerImpuestos');
        const data = await res.json();

        let ish = data.find(i => i.nombre === 'ISH');
        let iva = data.find(i => i.nombre === 'IVA');

        if (ish) window.TASA_ISH = parseFloat(ish.tasa);
        if (iva) window.TASA_IVA = parseFloat(iva.tasa);
        
        console.log("📈 Tasas cargadas:", { IVA: window.TASA_IVA, ISH: window.TASA_ISH });
    } catch (e) {
        console.warn("⚠️ No se pudieron cargar los impuestos:", e);
    }
}


document.addEventListener("DOMContentLoaded", () => {
    cargarReporteTrabajo();
});

document.addEventListener("DOMContentLoaded", () => {
    cargarReporteTurnos();
});

document.addEventListener("DOMContentLoaded", () => {

    setTimeout(() => {
        cargarReporteHuespedes();
    }, 300);

});

document.addEventListener("DOMContentLoaded", () => {
    cargarReporteDia();
});


document.addEventListener("DOMContentLoaded", () => {

    // 🔥 Inicializa filtros
    window.FILTROS = {
        doc: false,
        firma: false,
        obs: false,
        negra: false
    };

    // 🔥 Eventos de chips Huéspedes
    document.querySelectorAll("#filtrosHuespedes .filter-chip").forEach(chip => {
        chip.addEventListener("click", () => {
            const tipo = chip.dataset.filter;
            FILTROS[tipo] = !FILTROS[tipo];
            chip.classList.toggle("active");
            cargarReporteHuespedes();
        });
    });

    // 🔥 Eventos de chips Operación
    document.querySelectorAll("#filtrosOperacion .filter-chip").forEach(chip => {
        chip.addEventListener("click", () => {
            document.querySelectorAll("#filtrosOperacion .filter-chip").forEach(c => c.classList.remove("active"));
            chip.classList.add("active");
            window.CURRENT_OPERACION_FILTER = chip.dataset.filter;
            aplicarFiltrosYOrden();
        });
    });

    // 🔥 Buscador
    document.getElementById("busquedaTabla")?.addEventListener("input", (e) => {
        window.CURRENT_OPERACION_SEARCH = e.target.value.toLowerCase();
        aplicarFiltrosYOrden();
    });

    // 🔥 Inicializar Grid Pro
    buildHeaderOperacion();

    // Filtros por Turno
    document.querySelectorAll("#filtrosTurno .filter-chip").forEach(chip => {
        chip.addEventListener("click", () => {
            document.querySelectorAll("#filtrosTurno .filter-chip").forEach(c => c.classList.remove("active"));
            chip.classList.add("active");
            window.CURRENT_OPERACION_TURNO = chip.dataset.turno;
            aplicarFiltrosYOrden();
        });
    });

    // Filtro de Fecha
    document.getElementById('filtroFechaEntrada')?.addEventListener("change", (e) => {
        window.CURRENT_OPERACION_FECHA = e.target.value;
        aplicarFiltrosYOrden();
    });

    // Filtro Solo Incluidos
    document.getElementById('filtroSoloIncluidos')?.addEventListener("change", (e) => {
        window.CURRENT_OPERACION_SOLO_INCLUIDOS = e.target.checked;
        aplicarFiltrosYOrden();
    });

});

const gridColsOperacion = [
    { id: 'Hab', label: 'HAB', w: '80px', filter: 'select', multi: true },
    { id: 'Nombre_estado', label: 'ESTADO', w: '120px', filter: 'select', multi: true },
    { id: 'Tipo', label: 'TIPO', w: '120px', filter: 'select', multi: true },
    { id: 'Pers', label: 'PERS', w: '70px', filter: 'range' },
    { id: 'Pago', label: 'PAGO', w: '100px', filter: 'select', multi: true },
    { id: 'SUBTOTAL', label: 'SUBTOTAL', w: '110px', filter: 'range', align: 'center' },
    { id: 'IVA', label: 'IVA', w: '90px', filter: 'range', align: 'center' },
    { id: 'ISH', label: 'ISH', w: '90px', filter: 'range', align: 'center' },
    { id: 'TOTAL', label: 'TOTAL', w: '110px', filter: 'range', align: 'center' },
    { id: 'Entrada', label: 'ENTRADA', w: '120px', filter: 'none' },
    { id: 'Salida', label: 'SALIDA', w: '120px', filter: 'none' },
    { id: 'Huesped', label: 'HUESPED', w: '250px', filter: 'text' }
];

window.CURRENT_OPERACION_FILTER = 'Todas'; // Chips superiores
window.CURRENT_OPERACION_TURNO = 'Todas'; // Filtro de Turno
window.CURRENT_OPERACION_FECHA = ''; // Filtro de Fecha
window.CURRENT_OPERACION_SOLO_INCLUIDOS = false; // Filtro Incluidos
window.CURRENT_OPERACION_SEARCH = '';
window.CURRENT_OPERACION_SORT_FIELD = 'Hab';
window.CURRENT_OPERACION_SORT_DIR = 'asc';
window.ACTIVE_FILTERS_OPERACION = {};

function buildHeaderOperacion() {
    const headerRow = document.getElementById('headersOperacion');
    if (!headerRow) return;
    headerRow.innerHTML = '';

    gridColsOperacion.forEach(col => {
        const th = document.createElement('th');
        th.className = `header-filter ${col.align === 'center' ? 'text-center' : ''}`;
        th.style.width = col.w;
        th.tabIndex = 0;

        let filterHtml = '';
        if (col.filter === 'text') {
            filterHtml = `
                <div class="filter-dropdown">
                    <p class="dropdown-title">Buscar ${col.label}</p>
                    <div class="dropdown-search">
                        <i class="fas fa-search"></i>
                        <input type="text" oninput="updateFilterOperacion('${col.id}', this.value)" 
                               onclick="event.stopPropagation()" placeholder="...">
                    </div>
                </div>`;
        } else if (col.filter === 'select' && col.multi) {
            filterHtml = `
                <div class="filter-dropdown p-4 min-w-[200px]">
                    <p class="dropdown-title">Selección Múltiple</p>
                    <div class="dropdown-controls">
                        <span class="btn-control" onclick="event.stopPropagation(); selectAllMultiFilterOperacion('${col.id}', true)">Todos</span>
                        <span class="btn-control reset" onclick="event.stopPropagation(); selectAllMultiFilterOperacion('${col.id}', false)">Resetear</span>
                    </div>
                    <div class="dropdown-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar..." oninput="filterMultiOptionsOperacion('${col.id}', this.value)" onclick="event.stopPropagation()">
                    </div>
                    <div class="space-y-1 max-h-48 overflow-y-auto custom-scrollbar" id="list-multi-${col.id}">
                        ${getMultiOptionsForOperacion(col.id)}
                    </div>
                </div>`;
        } else if (col.filter === 'range') {
            const current = window.ACTIVE_FILTERS_OPERACION[col.id] || { min: '', max: '' };
            filterHtml = `
                <div class="filter-dropdown p-4 min-w-[200px]">
                    <p class="dropdown-title">Rango de ${col.label}</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 mb-1 uppercase">Mín</p>
                            <input type="number" step="0.01" value="${current.min || ''}" oninput="updateRangeFilterOperacion('${col.id}', 'min', this.value)" 
                                   onclick="event.stopPropagation()" class="w-full bg-slate-100 rounded-lg py-1.5 px-3 text-[10px] font-black outline-none">
                        </div>
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 mb-1 uppercase">Máx</p>
                            <input type="number" step="0.01" value="${current.max || ''}" oninput="updateRangeFilterOperacion('${col.id}', 'max', this.value)" 
                                   onclick="event.stopPropagation()" class="w-full bg-slate-100 rounded-lg py-1.5 px-3 text-[10px] font-black outline-none">
                        </div>
                    </div>
                </div>`;
        }

        const isSortable = !['Entrada', 'Salida'].includes(col.id);
        th.innerHTML = `
            <div class="header-content-wrapper">
                <div class="sort-icon-container" id="sort-icon-${col.id}" 
                     ${isSortable ? `onclick="event.stopPropagation(); toggleSortOperacion('${col.id}')"` : ''}>
                    <i class="fas fa-sort opacity-30 ${isSortable ? 'cursor-pointer' : ''}"></i>
                </div>
                
                <span class="column-label">${col.label}</span>

                ${col.filter !== 'none' ? '<i class="fas fa-caret-down filter-caret opacity-40"></i>' : '<div class="filter-caret"></div>'}
            </div>
            ${filterHtml}
        `;
        headerRow.appendChild(th);
    });
}

function getMultiOptionsForOperacion(colId, searchTerm = '') {
    if (!window.RAW_DATA_OPERACION) return '';
    const currentVals = window.ACTIVE_FILTERS_OPERACION[colId] || [];
    let uniqueValues = [...new Set(window.RAW_DATA_OPERACION.map(r => r[colId]))].filter(v => v && v !== '-').sort();

    if (searchTerm) {
        uniqueValues = uniqueValues.filter(v => String(v).toLowerCase().includes(searchTerm.toLowerCase()));
    }

    let html = '';
    uniqueValues.forEach(val => {
        const isChecked = currentVals.includes(val);
        html += `
            <label class="multi-option" onclick="event.stopPropagation()">
                <input type="checkbox" ${isChecked ? 'checked' : ''} onchange="toggleMultiFilterOperacion('${colId}', '${val}')">
                <span class="truncate">${val}</span>
            </label>
        `;
    });
    return html;
}

function filterMultiOptionsOperacion(colId, term) {
    const list = document.getElementById(`list-multi-${colId}`);
    if (list) list.innerHTML = getMultiOptionsForOperacion(colId, term);
}

function toggleMultiFilterOperacion(colId, val) {
    if (!window.ACTIVE_FILTERS_OPERACION[colId]) window.ACTIVE_FILTERS_OPERACION[colId] = [];
    const idx = window.ACTIVE_FILTERS_OPERACION[colId].indexOf(val);
    if (idx > -1) window.ACTIVE_FILTERS_OPERACION[colId].splice(idx, 1);
    else window.ACTIVE_FILTERS_OPERACION[colId].push(val);
    aplicarFiltrosYOrden();
}

function selectAllMultiFilterOperacion(colId, checkAll) {
    if (checkAll) {
        window.ACTIVE_FILTERS_OPERACION[colId] = [...new Set(window.RAW_DATA_OPERACION.map(r => r[colId]))].filter(v => v && v !== '-');
    } else {
        window.ACTIVE_FILTERS_OPERACION[colId] = [];
    }
    
    // Actualizar UI del dropdown sin cerrarlo
    const list = document.getElementById(`list-multi-${colId}`);
    if (list) list.innerHTML = getMultiOptionsForOperacion(colId);
    
    aplicarFiltrosYOrden();
}

function updateFilterOperacion(colId, val) {
    window.ACTIVE_FILTERS_OPERACION[colId] = val.toLowerCase();
    aplicarFiltrosYOrden();
}

function updateRangeFilterOperacion(colId, type, val) {
    if (!window.ACTIVE_FILTERS_OPERACION[colId]) window.ACTIVE_FILTERS_OPERACION[colId] = { min: '', max: '' };
    window.ACTIVE_FILTERS_OPERACION[colId][type] = val;
    aplicarFiltrosYOrden();
}

function toggleSortOperacion(colId) {
    if (window.CURRENT_OPERACION_SORT_FIELD === colId) {
        window.CURRENT_OPERACION_SORT_DIR = window.CURRENT_OPERACION_SORT_DIR === 'asc' ? 'desc' : 'asc';
    } else {
        window.CURRENT_OPERACION_SORT_FIELD = colId;
        window.CURRENT_OPERACION_SORT_DIR = 'asc';
    }
    aplicarFiltrosYOrden();
    updateSortUIOperacion();
}

function updateSortUIOperacion() {
    gridColsOperacion.forEach(col => {
        const iconCont = document.getElementById(`sort-icon-${col.id}`);
        if (!iconCont) return;
        if (window.CURRENT_OPERACION_SORT_FIELD === col.id) {
            iconCont.innerHTML = window.CURRENT_OPERACION_SORT_DIR === 'asc' ? '<i class="fas fa-sort-up text-blue-300"></i>' : '<i class="fas fa-sort-down text-blue-300"></i>';
        } else {
            iconCont.innerHTML = '<i class="fas fa-sort opacity-10"></i>';
        }
    });
}

function aplicarFiltrosYOrden() {
    if (!window.RAW_DATA_OPERACION) return;

    let data = [...window.RAW_DATA_OPERACION];

    // 1. Filtro de Chips (Estado)
    if (window.CURRENT_OPERACION_FILTER !== 'Todas') {
        data = data.filter(row => sanitize(row.Nombre_estado).includes(window.CURRENT_OPERACION_FILTER));
    }

    // 1.5 Filtro de Turno
    if (window.CURRENT_OPERACION_TURNO !== 'Todas') {
        data = data.filter(row => String(row.Turno) === window.CURRENT_OPERACION_TURNO);
    }

    // 1.7 Filtro de Fecha
    if (window.CURRENT_OPERACION_FECHA) {
        data = data.filter(row => {
            if (!row.Entrada) return false;
            return row.Entrada.startsWith(window.CURRENT_OPERACION_FECHA);
        });
    }

    // 1.9 Filtro Solo Incluidos
    if (window.CURRENT_OPERACION_SOLO_INCLUIDOS) {
        data = data.filter(row => parseInt(row.incluir_en_reporte) === 1);
    }

    // 2. Filtro de Búsqueda Global (Input superior)
    if (window.CURRENT_OPERACION_SEARCH) {
        data = data.filter(row => {
            return String(row.Hab).toLowerCase().includes(window.CURRENT_OPERACION_SEARCH) ||
                   String(row.Huesped).toLowerCase().includes(window.CURRENT_OPERACION_SEARCH);
        });
    }

    // 3. Filtros Avanzados de Cabecera
    Object.keys(window.ACTIVE_FILTERS_OPERACION).forEach(colId => {
        const filterVal = window.ACTIVE_FILTERS_OPERACION[colId];
        if (!filterVal) return;

        const colConfig = gridColsOperacion.find(c => c.id === colId);
        if (!colConfig) return;

        if (colConfig.filter === 'text' && filterVal) {
            data = data.filter(row => String(row[colId] || '').toLowerCase().includes(filterVal));
        } 
        else if (colConfig.filter === 'select' && Array.isArray(filterVal) && filterVal.length > 0) {
            data = data.filter(row => filterVal.includes(row[colId]));
        } 
        else if (colConfig.filter === 'range') {
            const { min, max } = filterVal;
            if (min !== '') data = data.filter(row => parseFloat(row[colId] || 0) >= parseFloat(min));
            if (max !== '') data = data.filter(row => parseFloat(row[colId] || 0) <= parseFloat(max));
        }
    });

    // 4. Ordenar
    const field = window.CURRENT_OPERACION_SORT_FIELD;
    if (field) {
        const dir = window.CURRENT_OPERACION_SORT_DIR === 'asc' ? 1 : -1;
        data.sort((a, b) => {
            let valA = a[field], valB = b[field];
            if (!isNaN(parseFloat(valA)) && isFinite(valA)) {
                valA = parseFloat(valA); valB = parseFloat(valB);
            } else {
                valA = String(valA || '').toLowerCase();
                valB = String(valB || '').toLowerCase();
            }
            if (valA < valB) return -1 * dir;
            if (valA > valB) return 1 * dir;
            return 0;
        });
    }

    renderReporteTrabajo(data, 'Todas');
    actualizarKPIsOperacion(data);
    
    // Actualizar dinámicamente las listas de multiselect
    gridColsOperacion.forEach(col => {
        if (col.filter === 'select' && col.multi) {
            const list = document.getElementById(`list-multi-${col.id}`);
            if (list) list.innerHTML = getMultiOptionsForOperacion(col.id);
        }
    });
}

function actualizarKPIsOperacion(data) {
    let ocupadas = 0, entradas = 0, salidas = 0, total = 0;
    
    data.forEach(row => {
        if (row.estado_registro === 'CHECKIN') ocupadas++;
        if (row.Huesped && String(row.Huesped).trim() !== '') entradas++;
        if (row.estado_registro === 'CHECKOUT') salidas++;
        total += parseFloat(row.TOTAL || 0);
    });

    setText('kpi-ocupadas', ocupadas);
    setText('kpi-total', "$" + total.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    setText('kpi-entradas', entradas);
    setText('kpi-salidas', salidas);
    // Nota: El total de habs se mantiene constante desde el load inicial o se puede calcular
    // Para simplificar, si kpi-total-habs no cambia, se puede dejar fuera o resetear aquí.
}







function cargarReporteTrabajo() {

    if (typeof base_url === "undefined") {
        console.error("❌ base_url no está definido");
        return;
    }

    fetch(base_url + "reportes/trabajo")
        .then(r => r.json())
        .then(resp => {
            if (!resp || resp.ok === false) return;
            
            // Guardar para filtrado local
            window.RAW_DATA_OPERACION = resp.data || [];
            
            // Total constante
            const kpi = resp.kpi || {};
            setText('kpi-total-habs', kpi.total_habs ?? 0);

            aplicarFiltrosYOrden();
        });
}

function renderReporteTrabajo(data, filtro = 'Todas') {
    const tbody = document.getElementById('tabla-trabajo');
    if (!tbody) return;
    tbody.innerHTML = '';

    const filteredData = data.filter(row => {
        if (filtro === 'Todas') return true;
        return sanitize(row.Nombre_estado).includes(filtro);
    });

    if (!filteredData.length) {
        tbody.innerHTML = '<tr><td colspan="12" style="text-align:center; padding: 20px;">Sin registros para este filtro</td></tr>';
        return;
    }

    filteredData.forEach((row, index) => {
        const entrada = row.Entrada ? new Date(row.Entrada).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }) : '-';
        const salida = row.Salida ? new Date(row.Salida).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }) : '-';
        
        const precio = Number(row.SUBTOTAL || 0);
        const iva = Number(row.IVA || 0);
        const ish = Number(row.ISH || 0);
        const total = Number(row.TOTAL || 0);

        const tr = document.createElement('tr');
        if (index % 2 !== 0) tr.classList.add('shaded');

        let statusRaw = sanitize(row.Nombre_estado);
        let dotClass = "", icon = "";
        if (statusRaw.includes('Suc')) { dotClass = "s-dirty"; icon = "✦"; }
        else if (statusRaw.includes('Limp')) { dotClass = "s-clean"; icon = "X"; }
        else if (statusRaw.includes('Mant')) { dotClass = "s-maint"; icon = "M"; }
        else if (statusRaw.includes('Plant')) { dotClass = "s-plant"; icon = "P"; }

        let statusHTML = statusRaw;
        if (dotClass) {
            statusHTML = `<div style="display:flex; align-items:center; gap:8px;">
                            <span class="status-dot ${dotClass}">${icon}</span>
                            <span>${statusRaw}</span>
                          </div>`;
        }

        let pagoRaw = sanitize(row.Pago);
        let pagoHTML = '-';
        if (pagoRaw !== '-') {
            const cleanPago = pagoRaw.replace(/[^a-zA-Z0-9]/g, '');
            pagoHTML = `<span class="pay-badge pay-${cleanPago}">${pagoRaw}</span>`;
        }

        tr.innerHTML = `
            <td><b style="font-size:13px">${sanitize(row.Hab)}</b></td>
            <td>${statusHTML}</td>
            <td>${sanitize(row.Tipo)}</td>
            <td class="text-center">${sanitize(row.Pers, '0')}</td>
            <td class="text-center">${pagoHTML}</td>
            <td class="text-center">$${precio.toLocaleString('es-MX')}</td>
            <td class="text-center">$${iva.toLocaleString('es-MX')}</td>
            <td class="text-center">$${ish.toLocaleString('es-MX')}</td>
            <td class="text-center"><b style="color:var(--primary)">$${total.toLocaleString('es-MX')}</b></td>
            <td style="font-family:'Roboto Mono'">${entrada}</td>
            <td style="font-family:'Roboto Mono'">${salida}</td>
            <td class="font-bold">${sanitize(row.Huesped)}</td>
        `;
        tbody.appendChild(tr);
    });
}

function animateValue(id, value) {
    const el = document.getElementById(id);
    el.innerText = value;
}

function showRep(n, el) {
    // Ocultar todos los reportes
    for (let i = 1; i <= 5; i++) {
        const r = document.getElementById('rep' + i);
        if (r) r.style.display = 'none';
    }
    
    // Mostrar el seleccionado
    const target = document.getElementById('rep' + n);
    if (target) target.style.display = 'block';

    // Toggle active class on tabs
    document.querySelectorAll('.rtab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}




function cargarReporteTurnos() {

    fetch(base_url + "reportes/turnos")
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert("Error al cargar reporte");
            return;
        }

        pintarKPIsTurnos(resp.kpi);
        renderReporteTurnos(resp.data);

    })
    .catch(err => console.error(err));
}

function pintarKPIsTurnos(kpi) {

    document.getElementById("t1_total").textContent = formatoMoneda(kpi.t1);
    document.getElementById("t2_total").textContent = formatoMoneda(kpi.t2);
    document.getElementById("t3_total").textContent = formatoMoneda(kpi.t3);
    document.getElementById("total_dia").textContent = formatoMoneda(kpi.total);

}


document.addEventListener("click", (e) => {

    const tr = e.target.closest("#tablaReporteTurnos tr");
    if (!tr) return;

    const checkbox = tr.querySelector(".row-check");
    if (!checkbox) return;

    if (e.target.type !== "checkbox") {
        checkbox.checked = !checkbox.checked;
    }

    tr.classList.toggle("selected", checkbox.checked);

    actualizarSeleccionUI();
});



function renderReporteTurnos(data) {

    const tbody = document.getElementById("tablaReporteTurnos");
    tbody.innerHTML = "";

    let t1 = 0, t2 = 0, t3 = 0;

    data.forEach(row => {

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>
                <input type="checkbox" 
                       class="row-check" 
                       data-id="${row.habitacion_id}">
            </td>

            <td class="hab">${row.habitacion}</td>

            <td class="t1">${row.t1_pago || '-'}</td>
            <td class="t1">${formatoMoneda(row.t1_precio)}</td>
            <td class="t1">${row.t1_nombre || '-'}</td>

            <td class="t2">${row.t2_pago || '-'}</td>
            <td class="t2">${formatoMoneda(row.t2_precio)}</td>
            <td class="t2">${row.t2_nombre || '-'}</td>

            <td class="t3">${row.t3_pago || '-'}</td>
            <td class="t3">${formatoMoneda(row.t3_precio)}</td>
            <td class="t3">${row.t3_nombre || '-'}</td>
        `;

        tbody.appendChild(tr);

        // 🔥 SUMAS
        t1 += Number(row.t1_precio || 0);
        t2 += Number(row.t2_precio || 0);
        t3 += Number(row.t3_precio || 0);
    });

    // 🔥 KPIs
    document.getElementById("t1_total").innerText = formatoMoneda(t1);
    document.getElementById("t2_total").innerText = formatoMoneda(t2);
    document.getElementById("t3_total").innerText = formatoMoneda(t3);
    document.getElementById("total_dia").innerText = formatoMoneda(t1 + t2 + t3);

    // 🔥 FOOTER
    document.getElementById("total_t1").innerText = formatoMoneda(t1);
    document.getElementById("total_t2").innerText = formatoMoneda(t2);
    document.getElementById("total_t3").innerText = formatoMoneda(t3);

    actualizarSeleccionUI();
}

function getSeleccionIds() {
    return Array.from(document.querySelectorAll(".row-check:checked"))
        .map(chk => chk.dataset.id);
}

function enviarSeleccion() {

    const ids = getSeleccionIds();

    if (ids.length === 0) {
        alert("Selecciona al menos una habitación");
        return;
    }

    const nombre = prompt("Nombre del reporte:");

    if (!nombre || nombre.trim() === "") {
        alert("Debes ingresar un nombre");
        return;
    }

    console.log("Enviando:", { ids, nombre });

    fetch(base_url + "reportes/generar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            habitaciones: ids,
            nombre: nombre
        })
    })
    .then(r => r.json())
    .then(resp => {

        console.log("RESPUESTA:", resp);

        if (!resp.ok) {
            alert(resp.msg || "Error al guardar");
            return;
        }

        alert("Reporte generado correctamente");

        // 🔥 📍 AQUÍ VA (DESPUÉS de guardar)
        cargarReporteTurnos();

    })
    .catch(err => {
        console.error("Error:", err);
    });


}

function formatoMoneda(valor) {

    if (!valor) return "-";

    return "$" + parseFloat(valor).toLocaleString("es-MX", {
        minimumFractionDigits: 0
    });
}


function cargarReporteHuespedes() {

    let params = new URLSearchParams();

    if (FILTROS.doc) params.append("doc", 1);
    if (FILTROS.firma) params.append("firma", 1);
    if (FILTROS.obs) params.append("obs", 1);
    if (FILTROS.negra) params.append("negra", 1);

    fetch(base_url + "reportes/huespedes?" + params.toString())
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderTabla(resp.data);

        })
        .catch(err => console.error("Error reporte:", err));
}





function renderTabla(data) {

    const tbody = document.querySelector("#tablaHuespedes tbody");
    tbody.innerHTML = "";

    data.forEach(row => {

        // 🔍 FILTROS
        if (FILTROS.doc && (!row.DOC || row.DOC === '')) return;
        if (FILTROS.firma && !row.firma_path) return;
        if (FILTROS.obs && (!row.OBSERVACIONES || row.OBSERVACIONES === '-')) return;
        if (FILTROS.negra && !row.LISTA.includes('NEGRA')) return;

        // FIRMA
        let firmaHTML = row.firma_path
            ? `<img src="${base_url + row.firma_path}" width="50">`
            : `<span class="badge-pend">⚠ Pend.</span>`;

        // FOTO
        let fotoHTML = row.FOTO === '✔'
            ? `<span class="badge-ok">✔</span>`
            : `<span class="badge-pend">⚠</span>`;

        // LISTA
        let listaHTML = '-';
        if (row.LISTA.includes('NEGRA')) {
            listaHTML = `<span class="badge-danger">⛔ L.NEGRA</span>`;
        } else if (row.LISTA.includes('VIP')) {
            listaHTML = `<span class="badge-vip">⭐ VIP</span>`;
        }

        tbody.innerHTML += `
            <tr>
                <td>${sanitize(row.HAB)}</td>
                <td>${sanitize(row.NOMBRE_COMPLETO)}</td>
                <td>${sanitize(row.DOC)}</td>
                <td>${sanitize(row.NUMERO_DOCUMENTO)}</td>
                <td>${firmaHTML}</td>
                <td>${fotoHTML}</td>
                <td>${sanitize(row.OBSERVACIONES)}</td>
                <td>${listaHTML}</td>
            </tr>
        `;
    });
}

function cargarReporteDia() {

    const fecha = document.getElementById("filtroFecha")?.value || '';

    fetch(base_url + "reportes/reporteDia?fecha=" + fecha)
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderTablaDia(resp.data);
            renderKPI(resp.kpi);

        })
        .catch(err => console.error("Error:", err));
}

function renderKPI(kpi) {

    document.getElementById("kpi_total").innerText = "$" + formatMoney(kpi.total);
    document.getElementById("kpi_efectivo").innerText = "$" + formatMoney(kpi.efectivo);
    document.getElementById("kpi_tarjeta").innerText = "$" + formatMoney(kpi.tarjeta);
    document.getElementById("kpi_facturas").innerText = kpi.facturas || 0;
}


function renderTablaDia(data) {

    const tbody = document.querySelector("#tablaReporteDia tbody");
    tbody.innerHTML = "";

    let turnoActual = "";

    data.forEach(row => {

        // 🔹 HEADER DE TURNO
        if (turnoActual !== row.turno) {
            turnoActual = row.turno;

            tbody.innerHTML += `
                <tr class="turno-header">
                    <td colspan="10"><b>${row.turno}</b></td>
                </tr>
            `;
        }

        tbody.innerHTML += `
            <tr>
                <td>${sanitize(row.turno)}</td>
                <td>${sanitize(row.habitacion)}</td>
                <td>${sanitize(row.nombre)}</td>
                <td>${sanitize(row.pago)}</td>
                <td>$${formatMoney(row.subtotal)}</td>
                <td>$${formatMoney(row.iva)}</td>
                <td>$${formatMoney(row.ish)}</td>
                <td><b>$${formatMoney(row.total)}</b></td>
                <td>${sanitize(row.entrada)}</td>
                <td>${sanitize(row.salida)}</td>
            </tr>
        `;
    });

    agregarTotalDia(data);
}


function agregarTotalDia(data) {

    let subtotal = 0, iva = 0, ish = 0, total = 0;

    data.forEach(r => {
        subtotal += parseFloat(r.subtotal || 0);
        iva += parseFloat(r.iva || 0);
        ish += parseFloat(r.ish || 0);
        total += parseFloat(r.total || 0);
    });

    const tbody = document.querySelector("#tablaReporteDia tbody");

    tbody.innerHTML += `
        <tr class="total-row">
            <td colspan="4">TOTAL DEL DÍA</td>
            <td>$${formatMoney(subtotal)}</td>
            <td>$${formatMoney(iva)}</td>
            <td>$${formatMoney(ish)}</td>
            <td>$${formatMoney(total)}</td>
            <td colspan="2"></td>
        </tr>
    `;
}


function formatMoney(num) {
    return parseFloat(num || 0).toLocaleString('es-MX', {
        minimumFractionDigits: 2
    });
}




function cargarFiscal() {

    const fecha = document.getElementById("filtroFechaFiscal")?.value || '';

    fetch(base_url + "reportes/reporteFiscal?fecha=" + fecha)
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderFiscal(resp.data);

        })
        .catch(err => console.error(err));
}


function renderFiscal(data) {

    const tbody = document.querySelector("#tablaFiscal tbody");
    tbody.innerHTML = "";

    data.forEach(row => {

        tbody.innerHTML += `
            <tr>
                <td>${row.habitacion}</td>
                <td>${row.nombre}</td>
                <td>${row.pago}</td>

                <!-- SUBTOTAL -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="precio"
                        data-original="${row.subtotal}"
                        value="${row.subtotal}">
                </td>

                <!-- IVA -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="iva"
                        data-original="${row.iva}"
                        value="${row.iva}"
                        readonly>
                </td>

                <!-- ISH -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="ish"
                        data-original="${row.ish}"
                        value="${row.ish}"
                        readonly>
                </td>

                <!-- TOTAL -->
                <td class="total-cell">
                    <b>$${parseFloat(row.total).toFixed(2)}</b>
                </td>

                <!-- RFC -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="rfc"
                        data-original="${row.rfc || ''}"
                        value="${row.rfc || ''}"
                        placeholder="RFC...">
                </td>

                <!-- NOTAS -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="notas"
                        data-original="${row.notas || ''}"
                        value="${row.notas || ''}"
                        placeholder="Nota...">
                </td>
            </tr>
        `;
    });

    activarControlEdicion();
    calcularKPIFiscal();
}



function guardarFiscal() {

  
    const inputs = document.querySelectorAll(".input-edit.editado");

    let data = [];

    inputs.forEach(inp => {

        data.push({
            id: inp.dataset.id,
            campo: inp.dataset.field,
            valor: inp.value
        });

    });

    fetch(base_url + "reportes/guardarFiscal", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ data })
    })
    .then(r => r.json())
    .then(resp => {

        if (resp.ok) {
            alert("✔ Guardado correctamente");
            cargarFiscal(); // 🔥 refresca
        } else {
            alert(resp.msg);
        }

    });

    calcularKPIFiscal();
}



function activarControlEdicion() {

    document.querySelectorAll(".input-edit").forEach(input => {

        input.addEventListener("input", () => {

            const fila = input.closest("tr");
            const original = input.dataset.original;

            // 🔹 marcar cambio
            if (input.value != original) {
                input.classList.add("editado");
                fila.classList.add("fila-editada");
            } else {
                input.classList.remove("editado");

                if (!fila.querySelector(".editado")) {
                    fila.classList.remove("fila-editada");
                }
            }

            // 🔥 SOLO si cambia precio
            if (input.dataset.field === "precio") {
                recalcularFila(fila);
            }

        });

    });

    calcularKPIFiscal();

}


function recalcularFila(fila) {

    const inputPrecio = fila.querySelector('[data-field="precio"]');
    const inputIva    = fila.querySelector('[data-field="iva"]');
    const inputIsh    = fila.querySelector('[data-field="ish"]');

    let subtotal = parseFloat(inputPrecio.value) || 0;

    // 🔥 calcular impuestos dinámicos
    let tasaIva = (window.TASA_IVA || 16) / 100;
    let tasaIsh = (window.TASA_ISH || 3) / 100;

    let iva = subtotal * tasaIva;
    let ish = subtotal * tasaIsh;

    // 🔥 asignar valores recalculados
    inputIva.value = iva.toFixed(2);
    inputIsh.value = ish.toFixed(2);

    // 🔥 marcar como editados automáticamente
    inputIva.classList.add("editado");
    inputIsh.classList.add("editado");

    // 🔥 total
    let total = subtotal + iva + ish;

    fila.querySelector(".total-cell").innerHTML = `<b>$${total.toFixed(2)}</b>`;

    calcularKPIFiscal()
}


function calcularKPIFiscal() {

    let subtotal = 0;
    let iva = 0;
    let ish = 0;
    let total = 0;

    document.querySelectorAll("#tablaFiscal tbody tr").forEach(fila => {

        const p = parseFloat(fila.querySelector('[data-field="precio"]')?.value) || 0;
        const i = parseFloat(fila.querySelector('[data-field="iva"]')?.value) || 0;
        const s = parseFloat(fila.querySelector('[data-field="ish"]')?.value) || 0;

        subtotal += p;
        iva += i;
        ish += s;
        total += (p + i + s);

    });

    // 🔹 pintar
    document.getElementById("kpi_subtotal").innerText = "$" + formatMoney(subtotal);
    document.getElementById("kpi_iva").innerText = "$" + formatMoney(iva);
    document.getElementById("kpi_ish").innerText = "$" + formatMoney(ish);
    document.getElementById("kpi_totalsat").innerText = "$" + formatMoney(total);
}


function cargarReportes() {

    fetch(base_url + "reportes/lista")
        .then(r => r.json())
        .then(resp => {

            console.log("LISTA:", resp); // 🔥 DEBUG

            const cont = document.getElementById("listaReportes");
            cont.innerHTML = "";

            if (!resp.data || resp.data.length === 0) {
                cont.innerHTML = "<tr><td colspan='4'>Sin reportes</td></tr>";
                return;
            }

            resp.data.forEach(r => {

                cont.innerHTML += `
                    <tr>
                        <td>${r.id}</td>
                         <td>${r.nombre || 'Sin nombre'}</td>
                        <td>${r.fecha}</td>
                        <td>${r.usuario}</td>
                        <td>
                            <button onclick="verReporte(${r.id})">👁 Ver</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error("Error lista:", err));
}


function cargarReporte(id) {

    console.log("Cargando reporte:", id);

    fetch(base_url + "reportes/detalle/" + id)
        .then(r => r.json())
        .then(resp => {

            console.log("DETALLE:", resp);

            if (!resp.ok || !resp.data || resp.data.length === 0) {
                alert("Este reporte no tiene datos");
                return;
            }

            // 🔥 ADAPTAR DATA AL FORMATO ORIGINAL
            const data = resp.data.map(r => ({
                habitacion_id: r.habitacion_id,
                habitacion: r.habitacion,

                t1_pago: r.t1_pago,
                t1_precio: r.t1_precio,
                t1_nombre: r.t1_nombre,

                t2_pago: r.t2_pago,
                t2_precio: r.t2_precio,
                t2_nombre: r.t2_nombre,

                t3_pago: r.t3_pago,
                t3_precio: r.t3_precio,
                t3_nombre: r.t3_nombre
            }));

            // 🔥 REUTILIZAS TU TABLA PRINCIPAL
            renderReporteTurnos(data);

            // 🔥 CERRAR MODAL
            cerrarModalReportes();

        })
        .catch(err => {
            console.error("Error cargando reporte:", err);
            alert("Error al cargar reporte");
        });
}



function eliminarReporte(id) {

    if (!confirm("¿Eliminar reporte?")) return;

    fetch(base_url + "reportes/eliminar/" + id, {
        method: "DELETE"
    })
    .then(r => r.json())
    .then(resp => {

        if (resp.ok) {
            alert("Eliminado");
            cargarReportes();
        }
    });
}


function guardarCambios(id) {

    const rows = [];

    document.querySelectorAll("#tablaDetalle tr").forEach(tr => {

        const tds = tr.querySelectorAll("td");

        rows.push({
            id: tr.dataset.id,
            t1_precio: tds[2].innerText,
            t2_precio: tds[5].innerText,
            t3_precio: tds[8].innerText
        });
    });

    fetch(base_url + "reportes/actualizar/" + id, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ rows })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.ok) {
            alert("Actualizado");
        }
    });
}




function actualizarSeleccionUI() {
    const el = document.getElementById("contadorSeleccion");
    if (!el) return; // 🔥 evita el error
    const total = document.querySelectorAll(".row-check:checked").length;
    el.innerText = total;
}


function formatoMoneda(valor) {
    return "$" + Number(valor || 0).toFixed(2);
}


function abrirModalReportes() {
    document.getElementById("modalReportes").style.display = "flex";
    cargarListaModal();
}

function cerrarModalReportes() {
    document.getElementById("modalReportes").style.display = "none";
}


function cargarListaModal() {

    fetch(base_url + "reportes/lista")
        .then(r => r.json())
        .then(resp => {

            const tbody = document.getElementById("tablaModalReportes");
            tbody.innerHTML = "";

            if (!resp.data || resp.data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='4'>Sin reportes</td></tr>";
                return;
            }

            resp.data.forEach(r => {

                tbody.innerHTML += `
                    <tr>
                        <td>${r.id}</td>
                        <td>${r.nombre || 'Sin nombre'}</td>
                        <td>${r.fecha}</td>
                        <td>${r.usuario}</td>
                        <td>
                            <button onclick="cargarReporte(${r.id})">Cargar</button>
                            <button onclick="eliminarReporte(${r.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

        });
}



function nuevoReporte() {

    if (!confirm("¿Iniciar un nuevo reporte?")) return;

    console.log("Reiniciando reporte...");

    cargarReporteTurnos();
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.innerText = value;
}



function showRep(id, el) {

    document.querySelectorAll('[id^="rep"]').forEach(d => {
        d.style.display = 'none';
    });

    document.getElementById('rep' + id).style.display = 'block';

    document.querySelectorAll('.rtab').forEach(t => {
        t.classList.remove('active');
        t.classList.add('inactive'); // 👈 IMPORTANTE
    });

    el.classList.remove('inactive');
    el.classList.add('active');
}
       
       
       
       </script>
       
        <?= view('layout/footer') ?>