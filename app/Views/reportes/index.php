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
    padding: 12px 20px;
    font-size: 14px;
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
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid var(--border-color);
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--slate-600);
}

.filter-chip.active {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}

.input-date {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    background: white;
}

/* ======================================================
   📋 PREMIUM TABLE
   ====================================================== */
.table-container {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    overflow: auto;
    box-shadow: var(--shadow-sm);
    max-height: 600px;
}

.g-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.g-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    text-align: left;
    padding: 14px 16px;
    border-bottom: 2px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 10;
}

.g-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    color: #1e293b;
}

.g-table tbody tr:hover {
    background: #f1f5f9;
}

.g-table tbody tr:nth-child(even) {
    background: #fcfdfe;
}

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
                <button class="btn-action btn-outline">
                    <span class="icon">📦</span> Historial
                </button>
                <button class="btn-action btn-primary">
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
                    <div class="stats-label">Ocupación Actual</div>
                    <div class="stats-value" id="kpi-ocupadas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">Habs. activas</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-label">Ingresos Turno</div>
                    <div class="stats-value" id="kpi-total">$0.00</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">Corte parcial</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-label">Entradas (Check-in)</div>
                    <div class="stats-value" id="kpi-entradas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">Hoy</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-label">Salidas (Check-out)</div>
                    <div class="stats-value" id="kpi-salidas">0</div>
                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 4px;">Pendientes</div>
                </div>
            </div>

            <!-- Toolbar Filtros -->
            <div class="report-toolbar">
                <div class="toolbar-group">
                    <div class="filter-chip active">Todas</div>
                    <div class="filter-chip">Ocupadas</div>
                    <div class="filter-chip">Limpieza</div>
                    <div class="filter-chip">Mantenimiento</div>
                </div>
                <div class="toolbar-group">
                    <button class="btn-action btn-outline">📥 Excel</button>
                    <button class="btn-action btn-outline">📄 PDF</button>
                </div>
            </div>

            <div class="table-container">
                <table class="g-table">
                    <thead>
                        <tr>
                            <th>HAB</th>
                            <th>ESTADO</th>
                            <th>TIPO</th>
                            <th>PERS</th>
                            <th>PAGO</th>
                            <th class="text-right">SUBTOTAL</th>
                            <th class="text-right">IVA</th>
                            <th class="text-right">ISH</th>
                            <th class="text-right">TOTAL</th>
                            <th>ENTRADA</th>
                            <th>SALIDA</th>
                            <th>HUESPED</th>
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



        </div>


        <!-- ========================= -->
        <!-- ✏️ REPORTE F EDITABLE -->
        <!-- ========================= -->
        <div id="rep5" style="display:none;">
            <!-- Auditor Toolbar -->
            <div class="report-toolbar" style="border-left: 4px solid var(--success);">
                <div class="toolbar-group">
                    <span style="font-size: 13px; font-weight: 700; color: var(--success);">🧾 Auditoría Fiscal:</span>
                    <input type="date" id="filtroFechaFiscal" class="input-date">
                    <button class="btn-action btn-primary" onclick="cargarFiscal()">
                        🔍 Consultar
                    </button>
                </div>
                <div class="toolbar-group">
                    <button class="btn-action btn-success" onclick="guardarFiscal()">
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

    // 🔥 Eventos de chips
    document.querySelectorAll("#filtrosHuespedes .filter-chip").forEach(chip => {

        chip.addEventListener("click", () => {

            const tipo = chip.dataset.filter;

            FILTROS[tipo] = !FILTROS[tipo];

            chip.classList.toggle("active");

            cargarReporteHuespedes(); // recarga
        });

    });

});







function cargarReporteTrabajo() {

    if (typeof base_url === "undefined") {
        console.error("❌ base_url no está definido");
        return;
    }

    fetch(base_url + "reportes/trabajo")
        .then(r => {
            if (!r.ok) {
                throw new Error("Error HTTP: " + r.status);
            }
            return r.json();
        })
        .then(resp => {

            console.log("✅ RESPUESTA:", resp);

            if (!resp || resp.ok === false) {
                alert(resp?.msg || "Error en la respuesta del servidor");
                return;
            }

            const kpi = resp.kpi || {};
            const data = resp.data || [];

            // =========================
            // 📊 KPIs (SIN romper ejecución)
            // =========================
            try {
                const elOcupadas = document.getElementById('kpi-ocupadas');
                const elTotal = document.getElementById('kpi-total');
                const elEntradas = document.getElementById('kpi-entradas');
                const elSalidas = document.getElementById('kpi-salidas');

                if (elOcupadas) elOcupadas.innerText = kpi.ocupadas ?? 0;
                if (elTotal) elTotal.innerText = "$" + Number(kpi.total || 0).toLocaleString('es-MX');
                if (elEntradas) elEntradas.innerText = kpi.entradas ?? 0;
                if (elSalidas) elSalidas.innerText = kpi.salidas ?? 0;

            } catch (e) {
                console.warn("⚠️ Error pintando KPIs:", e);
            }

            // =========================
            // 📋 TABLA
            // =========================
            const tbody = document.getElementById('tabla-trabajo');

            if (!tbody) {
                console.error("❌ No existe #tabla-trabajo");
                return;
            }

            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="12" style="text-align:center;">
                            Sin registros
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach(row => {

                // 🔥 Validaciones seguras
                const entrada = row.hora_entrada
                    ? new Date(row.hora_entrada).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })
                    : '-';

                const salida = row.hora_salida_real
                    ? new Date(row.hora_salida_real).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })
                    : '-';

                const precio = Number(row.precio_base || 0);
                const iva = Number(row.iva || 0);
                const ish = Number(row.ish || 0);

                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${sanitize(row.Numero_Habitacion)}</td>
                    <td>${sanitize(row.estados_habitacion)}</td>
                    <td>${sanitize(row.cod_tip_habitacion)}</td>
                    <td>${sanitize(row.Total_huespedes, '0')}</td>
                    <td>${sanitize(row.Cod_Forma_pago)}</td>
                    <td>$${precio.toLocaleString('es-MX')}</td>
                    <td>$${iva.toLocaleString('es-MX')}</td>
                    <td>$${ish.toLocaleString('es-MX')}</td>
                    <td><b>$${precio.toLocaleString('es-MX')}</b></td>
                    <td>${entrada}</td>
                    <td>${salida}</td>
                    <td>${sanitize(row.Nombre_Huesped)}</td>
                `;

                tbody.appendChild(tr);
            });

            console.log("✅ Tabla renderizada:", data.length, "filas");

        })
        .catch(e => {
            console.error("❌ Error reporte:", e);
            alert("Error cargando reporte");
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