<?= view('layout/header') ?>


<link rel="stylesheet" href="<?= base_url('assets/css/reportes.css') ?>">

<!-- ========================= -->
<!-- 📊 REPORTES -->
<!-- ========================= -->


<div class="app-container">
    <!-- todo tu contenido -->


<div class="scr visible" id="screen3">

  

    <div class="inner-body">

        <!-- HEADER -->
        <div class="inner-topbar">

            <button class="btn-back">← Menú</button>

            <div class="inner-title">📊 Reportes</div>

            <div class="inner-subtitle">

            </div>

            <div style="margin-left:auto;display:flex;gap:8px;">
                <button class="btn-primary btn-green">🖨 Imprimir</button>
                <button class="btn-primary btn-gray">💾 Exportar</button>
            </div>

        </div>

        <!-- ========================= -->
        <!-- 🧭 TABS (ESTO TE FALTABA) -->
        <!-- ========================= -->
        <div class="rtab-bar">
            <div class="rtab active" onclick="showRep(1,this)">📋 Reporte X (Turno)</div>
            <div class="rtab inactive" onclick="showRep(2,this)">🔲 Consolidado X</div>
            <div class="rtab inactive" onclick="showRep(3,this)">🗂 Expediente de Identidad</div>
            <div class="rtab inactive" onclick="showRep(4,this)">📅 Reporte Z (Día)</div>
            <div class="rtab inactive" onclick="showRep(5,this)">✏️ Reporte Fiscal</div>
        </div>

        <!-- ========================= -->
        <!-- REPORTE 1 -->
        <!-- ========================= -->
        <div id="rep1" class="scroll-body">

            <!-- CARDS -->
            <div class="summary-row">

                <div class="sum-card blue">
                    <div class="sum-label">Habitaciones ocupadas</div>
                    <div class="sum-value" id="kpi-ocupadas">0</div>
                    <div class="sum-sub">de <span id="kpi-total-habitaciones">0</span></div>
                </div>

                <div class="sum-card green">
                    <div class="sum-label">Total recaudado</div>
                    <div class="sum-value" id="kpi-total">$0.00</div>
                    <div class="sum-sub">Turno 1</div>
                </div>

                <div class="sum-card orange">
                    <div class="sum-label">Entradas</div>
                    <div class="sum-value" id="kpi-entradas">0</div>
                    <div class="sum-sub">de <span id="kpi-total-habitaciones">0</span></div>
                </div>

                <div class="sum-card red">
                    <div class="sum-label">Salidas</div>
                    <div class="sum-value" id="kpi-salidas">0</div>
                    <div class="sum-sub">de <span id="kpi-total-habitaciones">0</span></div>
                </div>

            </div>

            <table class="g-table">
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
                        <th>H.ENTRADA</th>
                        <th>H.SALIDA</th>
                        <th>NOMBRE</th>
                    </tr>
                </thead>

                <tbody id="tabla-trabajo">
                    <tr>
                        <td colspan="11" class="text-center text-slate-400 py-6">
                            Cargando...
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div id="rep2" class="scroll-body" style="display:none;">

            <!-- 🔥 BOTONES -->
            <div style="margin-bottom:10px; display:flex; gap:10px;">

                <button onclick="nuevoReporte()">
                    🆕 Nuevo
                </button>

                <button onclick="enviarSeleccion()">
                    💾 Generar Reporte
                </button>

                <button onclick="abrirModalReportes()">
                    📂 Ver Reportes
                </button>

            </div>

            <div class="reporte-turnos">

                <!-- CARDS -->
                <div class="summary-row">

                    <div class="sum-card blue">
                        <div class="sum-label">Turno 1</div>
                        <div class="sum-value" id="t1_total">$0</div>
                    </div>

                    <div class="sum-card green">
                        <div class="sum-label">Turno 2</div>
                        <div class="sum-value" id="t2_total">$0</div>
                    </div>

                    <div class="sum-card orange">
                        <div class="sum-label">Turno 3</div>
                        <div class="sum-value" id="t3_total">$0</div>
                    </div>

                    <div class="sum-card red">
                        <div class="sum-label">Total del día</div>
                        <div class="sum-value" id="total_dia">$0</div>
                    </div>

                </div>

                <!-- TABLA -->
                <table class="tabla-turnos">
                    <thead>
                        <tr>
                            <th></th>
                            <th>HAB</th>
                            <th>T1 PAGO</th>
                            <th>T1 PRECIO</th>
                            <th>T1 NOMBRE</th>
                            <th>T2 PAGO</th>
                            <th>T2 PRECIO</th>
                            <th>T2 NOMBRE</th>
                            <th>T3 PAGO</th>
                            <th>T3 PRECIO</th>
                            <th>T3 NOMBRE</th>
                        </tr>
                    </thead>

                    <tbody id="tablaReporteTurnos"></tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2"><b>TOTAL</b></td>
                            <td colspan="3" id="total_t1"></td>
                            <td colspan="3" id="total_t2"></td>
                            <td colspan="3" id="total_t3"></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>


        <div id="modalReportes" class="modal-overlay">

            <div class="modal-box">

                <div class="modal-header">
                    <h3>📂 Reportes guardados</h3>
                    <button onclick="cerrarModalReportes()">✖</button>
                </div>

                <table class="tabla-turnos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaModalReportes"></tbody>
                </table>

            </div>

        </div>

        <!-- ========================= -->
        <!-- 🗂 REPORTE 3 - DATOS -->
        <!-- ========================= -->
        <div id="rep3" class="scroll-body" style="display:none">
            <div class="print-bar" id="filtrosHuespedes">
                <span class="filtro-label">Filtrar:</span>

                <span class="chip chip-blue" data-filter="doc">🪪 ID / Documento</span>
                <span class="chip chip-green" data-filter="firma">✍️ Firma</span>
                <span class="chip chip-orange" data-filter="obs">💬 Observaciones</span>
                <span class="chip chip-red" data-filter="negra">⛔ Lista Negra</span>
            </div>

            <div class="tabla-wrapper">
                <table class="g-table" id="tablaHuespedes">
                    <thead>
                        <tr>
                            <th>HAB</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>DOC</th>
                            <th>N° DOCUMENTO</th>
                            <th>FIRMA</th>
                            <th>FOTO</th>
                            <th>OBSERVACIONES</th>
                            <th>LISTA</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>


        <!-- ========================= -->
        <!-- 📅 REPORTE R DEL DÍA -->
        <!-- ========================= -->
        <div id="rep4" class="scroll-body" style="display:none;">



            <!-- 🔹 ALERTA -->
            <div class="alert-dia">
                📊 Reporte Z — Consolidado del día <b id="fechaDia"></b>
            </div>

            <!-- 🔹 FILTRO FECHA -->
            <div class="filtro-bar">
                <label>Fecha:</label>
                <input type="date" id="filtroFecha">
                <button class="btn-buscar" onclick="cargarReporteDia()">
                    🔍 Buscar
                </button>
            </div>


            




            <!-- 🔹 KPIs -->
            <div class="kpi-row">
                <div class="kpi-box kpi-blue">
                    <span>TOTAL 3 TURNOS</span>
                    <b id="kpi_total">$0</b>
                </div>

                <div class="kpi-box kpi-green">
                    <span>EFECTIVO</span>
                    <b id="kpi_efectivo">$0</b>
                </div>

                <div class="kpi-box kpi-orange">
                    <span>TARJETA</span>
                    <b id="kpi_tarjeta">$0</b>
                </div>

                <div class="kpi-box kpi-red">
                    <span>FACTURAS</span>
                    <b id="kpi_facturas">0</b>
                </div>
            </div>

            <!-- 🔹 TABLA -->
            <div class="tabla-wrapper">
                <table class="g-table" id="tablaReporteDia">
                    <thead>
                        <tr>
                            <th>TURNO</th>
                            <th>HAB</th>
                            <th>NOMBRE</th>
                            <th>PAGO</th>
                            <th>SUBTOTAL</th>
                            <th>IVA 16%</th>
                            <th>ISH 3%</th>
                            <th>TOTAL</th>
                            <th>ENTRADA</th>
                            <th>SALIDA</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>



        </div>


        <!-- ========================= -->
        <!-- ✏️ REPORTE F EDITABLE -->
        <!-- ========================= -->
        <div id="rep5" class="scroll-body" style="display:none;">






            <!-- 🔹 HEADER VERDE -->
            <div class="fiscal-header">
                <div class="fiscal-text">
                    🧾 <b>Reporte Fiscal Editable</b> — Ajusta valores antes de declarar ante SAT · Fiscalía · PROFECO
                </div>

                <!-- 🔹 FILTRO FECHA -->
                <div class="filtro-bar">
                    <label>Fecha:</label>
                    <input type="date" id="filtroFechaFiscal">
                    <button class="btn-buscar" onclick="cargarFiscal()">
                        🔍 Buscar
                    </button>
                </div>

                <button class="btn-save" onclick="guardarFiscal()">
                    💾 Guardar cambios
                </button>
            </div>


            <!-- 🔹 KPIs FISCALES -->
            <div class="kpi-row">

                <div class="kpi-box kpi-blue">
                    <span>SUBTOTAL</span>
                    <b id="kpi_subtotal">$0</b>
                </div>

                <div class="kpi-box kpi-green">
                    <span>IVA</span>
                    <b id="kpi_iva">$0</b>
                </div>

                <div class="kpi-box kpi-orange">
                    <span>ISH</span>
                    <b id="kpi_ish">$0</b>
                </div>

                <div class="kpi-box kpi-red">
                    <span>TOTAL</span>
                    <b id="kpi_totalsat">$0</b>
                </div>

            </div>


            <!-- 🔹 TABLA -->
            <div class="tabla-wrapper">
                <table class="g-table" id="tablaFiscal">
                    <thead>
                        <tr>
                            <th>HAB</th>
                            <th>NOMBRE</th>
                            <th>PAGO</th>
                            <th>SUBTOTAL</th>
                            <th>IVA 16%</th>
                            <th>ISH 3%</th>
                            <th>TOTAL</th>
                            <th>RFC FACTURA</th>
                            <th>NOTAS</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>



        </div>


</div>

        <script>
        window.base_url = "<?= site_url('/') ?>";
        </script>
        <script src="<?= base_url('assets/js/reportes.js') ?>"></script>
        <?= view('layout/footer') ?>