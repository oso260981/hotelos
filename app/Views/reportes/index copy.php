<?= view('layout/header') ?>


<style>
/* ========================= */
/* 🎨 RESET */
/* ========================= */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ========================= */
/* 🎨 VARIABLES */
/* ========================= */
:root {
    --bg: #1a1a2e;
    --blue: #1565c0;
    --green: #2e7d32;
    --orange: #e65100;
    --red: #c62828;
    --gray: #546e7a;
    --border: #90a4ae;
    --col-header: #1565c0;
}

/* ========================= */
/* 🎨 BODY */
/* ========================= */
body {
    background: var(--bg);
    font-family: Arial, sans-serif;
}

/* ========================= */
/* 🧱 CONTENEDOR PRINCIPAL */
/* ========================= */
.screen-wrap {
    max-width: 1280px;
    margin: 20px auto;
    background: #f0f4f8;
    border-radius: 12px;
    padding: 20px;
}

/* ========================= */
/* 🔝 TOPBAR */
/* ========================= */
.topbar {
    background: #0d47a1;
    color: white;
    padding: 10px 20px;
}

.topbar-logo {
    font-weight: bold;
    font-size: 18px;
}

/* ========================= */
/* 🧭 HEADER INTERNO */
/* ========================= */
.inner-topbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.inner-title {
    font-weight: bold;
    color: var(--blue);
}

.inner-subtitle {
    font-size: 12px;
    color: var(--gray);
}

/* ========================= */
/* 🔘 BOTONES */
/* ========================= */
.btn-primary {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    color: white;
}

.btn-green {
    background: var(--green);
}

.btn-gray {
    background: var(--gray);
}

.btn-back {
    padding: 6px 10px;
    background: #e3f2fd;
    border: 1px solid #90caf9;
    cursor: pointer;
}

/* ========================= */
/* 🧭 TABS */
/* ========================= */
.rtab-bar {
    display: flex;
    gap: 5px;
    margin-bottom: 15px;
}

.rtab {
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px 5px 0 0;
}

.rtab.active {
    background: white;
    border: 1px solid var(--border);
    color: var(--blue);
}

.rtab.inactive {
    color: var(--gray);
}

/* ========================= */
/* 📊 CARDS */
/* ========================= */
.summary-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 15px;
}

.sum-card {
    background: white;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid;
}

.sum-card.blue {
    border-color: var(--blue);
}

.sum-card.green {
    border-color: var(--green);
}

.sum-card.orange {
    border-color: var(--orange);
}

.sum-card.red {
    border-color: var(--red);
}

.sum-label {
    font-size: 11px;
    color: var(--gray);
}

.sum-value {
    font-size: 18px;
    font-weight: bold;
}

/* ========================= */
/* 📋 TABLA */
/* ========================= */
.g-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.g-table th {
    background: var(--col-header);
    color: white;
    padding: 8px;
    font-size: 12px;
}

.g-table td {
    padding: 6px;
    border-bottom: 1px solid #ddd;
    font-size: 12px;
}

.g-table tbody tr:nth-child(even) {
    background: #f5f5f5;
}

.g-table tbody tr:hover {
    background: #e3f2fd;
}

/* ========================= */
/* 🔲 FILA SOMBREADA */
/* ========================= */
.shaded td {
    background: #fff8e1 !important;
}

/* ========================= */
/* 🧮 TOTALES */
/* ========================= */
.total-row td {
    background: #e8f5e9 !important;
    font-weight: bold;
    color: var(--green);
}

/* ========================= */
/* 📜 SCROLL */
/* ========================= */
.scroll-body {
    max-height: 400px;
    overflow-y: auto;
}

/* visual extra */
tr td:first-child {
    font-weight:bold;
}

/* ========================= */
/* 🎛 FILTROS */
/* ========================= */
.btn-filter {
    padding: 6px 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background: #f5f5f5;
    font-size: 11px;
    cursor: pointer;
}

.btn-filter.active {
    background: #e3f2fd;
    border-color: #1565c0;
}

.btn-filter.danger {
    background: #ffebee;
    border-color: #c62828;
}

/* ========================= */
/* 🎯 STATUS */
/* ========================= */
.ok {
    color: #2e7d32;
    font-weight: bold;
}

.warn {
    color: #ef6c00;
    font-weight: bold;
}

.danger {
    color: #c62828;
    font-weight: bold;
}

.vip {
    color: #f9a825;
    font-weight: bold;
}

.inp {
    width: 90px;
    padding: 4px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 12px;
}
</style>

<!-- ========================= -->
<!-- 📊 REPORTES -->
<!-- ========================= -->
<div class="scr visible" id="screen3">

    <div class="screen-label">Sacar Reporte</div>

    <div class="inner-body">

        <!-- HEADER -->
        <div class="inner-topbar">

            <button class="btn-back">← Menú</button>

            <div class="inner-title">📊 Reportes</div>

            <div class="inner-subtitle">
                <?= date('d/m/Y', strtotime($fecha)) ?> — Turno <?= $turno ?>
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
            <div class="rtab active" onclick="showRep(1,this)">📋 Reporte Real de Turno</div>
            <div class="rtab inactive" onclick="showRep(2,this)">🔲 Sombreados 3 Turnos</div>
            <div class="rtab inactive" onclick="showRep(3,this)">🗂 Datos Id/Firma/Obs</div>
            <div class="rtab inactive" onclick="showRep(4,this)">📅 Reporte R del Día</div>
            <div class="rtab inactive" onclick="showRep(5,this)">✏️ Editable</div>
        </div>

        <!-- ========================= -->
        <!-- REPORTE 1 -->
        <!-- ========================= -->
        <div id="rep1" class="scroll-body">

            <!-- CARDS -->
            <div class="summary-row">

                <div class="sum-card blue">
                    <div class="sum-label">Habitaciones ocupadas</div>
                    <div class="sum-value"><?= $summary['ocupadas'] ?></div>
                    <div class="sum-sub">de <?= $summary['total'] ?></div>
                </div>

                <div class="sum-card green">
                    <div class="sum-label">Total recaudado</div>
                    <div class="sum-value">$<?= number_format($summary['total_recaudado'],2) ?></div>
                </div>

                <div class="sum-card orange">
                    <div class="sum-label">Entradas</div>
                    <div class="sum-value"><?= $summary['entradas'] ?></div>
                </div>

                <div class="sum-card red">
                    <div class="sum-label">Salidas</div>
                    <div class="sum-value"><?= $summary['salidas'] ?></div>
                </div>

            </div>

            <!-- TABLA -->
            <table class="g-table">
                <thead>
                    <tr>
                        <th>HAB</th>
                        <th>STATUS</th>
                        <th>PERS</th>
                        <th>PAGO</th>
                        <th>SUBTOTAL</th>
                        <th>IVA</th>
                        <th>ISH</th>
                        <th>TOTAL</th>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>NOMBRE</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($registros as $r): ?>
                    <tr class="<?= $r['sombreado'] ? 'shaded' : '' ?>">
                        <td><?= $r['numero'] ?></td>
                        <td><?= $r['status'] ?></td>
                        <td><?= $r['num_personas'] ?></td>
                        <td><?= $r['forma_pago'] ?></td>
                        <td>$<?= number_format($r['precio_base'],2) ?></td>
                        <td>$<?= number_format($r['iva'],2) ?></td>
                        <td>$<?= number_format($r['ish'],2) ?></td>
                        <td><strong>$<?= number_format($r['total'],2) ?></strong></td>
                        <td><?= $r['hora_entrada_1'] ?></td>
                        <td><?= $r['hora_salida_1'] ?? '—' ?></td>
                        <td><?= $r['nombre_huesped'] ?></td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

        </div>

        <div id="rep2" class="scroll-body" style="display:none;">

            <!-- ========================= -->
            <!-- 📊 RESUMEN -->
            <!-- ========================= -->
            <div class="summary-row">

                <div class="sum-card blue">
                    <div class="sum-label">Turno 1</div>
                    <div class="sum-value">$<?= number_format($totales_turno[1] ?? 0,2) ?></div>
                </div>

                <div class="sum-card green">
                    <div class="sum-label">Turno 2</div>
                    <div class="sum-value">$<?= number_format($totales_turno[2] ?? 0,2) ?></div>
                </div>

                <div class="sum-card orange">
                    <div class="sum-label">Turno 3</div>
                    <div class="sum-value">$<?= number_format($totales_turno[3] ?? 0,2) ?></div>
                </div>

                <div class="sum-card red">
                    <div class="sum-label">Total día</div>
                    <div class="sum-value">
                        $<?= number_format(
                    ($totales_turno[1] ?? 0) +
                    ($totales_turno[2] ?? 0) +
                    ($totales_turno[3] ?? 0)
                ,2) ?>
                    </div>
                </div>

            </div>

            <!-- ========================= -->
            <!-- 📋 TABLA SOMBREADOS -->
            <!-- ========================= -->
            <table class="g-table">

                <thead>
                    <tr>
                        <th>HAB</th>
                        <th>TURNO</th>
                        <th>PAGO</th>
                        <th>TOTAL</th>
                        <th>NOMBRE</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach($habitaciones as $hab): ?>

                    <?php for($t=1;$t<=3;$t++): 
                $data = $hab['turnos'][$t] ?? null;
            ?>

                    <tr class="turno-<?= $t ?>">

                        <td><?= $hab['numero'] ?></td>

                        <td><strong>T<?= $t ?></strong></td>

                        <?php if($data): ?>
                        <td><?= $data['forma_pago'] ?></td>
                        <td><strong>$<?= number_format($data['total'],2) ?></strong></td>
                        <td><?= $data['nombre_huesped'] ?></td>
                        <?php else: ?>
                        <td colspan="3" style="text-align:center;color:#999;">
                            Sin registro
                        </td>
                        <?php endif; ?>

                    </tr>

                    <?php endfor; ?>

                    <?php endforeach; ?>

                </tbody>

                <!-- ========================= -->
                <!-- 🧮 TOTAL GENERAL -->
                <!-- ========================= -->
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3">TOTAL GENERAL</td>
                        <td>
                            <strong>
                                $<?= number_format(
                            ($totales_turno[1] ?? 0) +
                            ($totales_turno[2] ?? 0) +
                            ($totales_turno[3] ?? 0)
                        ,2) ?>
                            </strong>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>

            </table>

        </div>

        <!-- ========================= -->
<!-- 🗂 REPORTE 3 - DATOS -->
<!-- ========================= -->
<div id="rep3" class="scroll-body" style="display:none;">

    <!-- ========================= -->
    <!-- 🎛 FILTROS -->
    <!-- ========================= -->
    <div style="margin-bottom:10px; display:flex; gap:8px; align-items:center;">

        <span style="font-size:12px;color:#555;">Filtrar:</span>

        <button class="btn-filter active">🪪 ID / Documento</button>
        <button class="btn-filter">✍ Firma</button>
        <button class="btn-filter">💬 Observaciones</button>
        <button class="btn-filter danger">⛔ Lista Negra</button>

    </div>

    <!-- ========================= -->
    <!-- 📋 TABLA -->
    <!-- ========================= -->
    <table class="g-table">

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

        <tbody>

        <?php foreach($datos as $d): ?>

            <tr>

                <td><?= $d['habitacion'] ?></td>

                <td><?= $d['nombre'] ?></td>

                <td><?= $d['tipo_doc'] ?></td>

                <td><?= $d['numero_doc'] ?></td>

                <!-- FIRMA -->
                <td>
                    <?php if($d['firma'] == 'digital'): ?>
                        <span class="ok">✔ Digital</span>
                    <?php else: ?>
                        <span class="warn">⚠ Manual</span>
                    <?php endif; ?>
                </td>

                <!-- FOTO -->
                <td>
                    <?= $d['foto'] ? '<span class="ok">✔</span>' : '—' ?>
                </td>

                <!-- OBS -->
                <td>
                    <?= $d['observaciones'] ?? '—' ?>
                </td>

                <!-- LISTA -->
                <td>
                    <?php if($d['lista'] == 'negra'): ?>
                        <span class="danger">⛔ L.NEGRA</span>
                    <?php elseif($d['lista'] == 'vip'): ?>
                        <span class="vip">⭐ VIP</span>
                    <?php elseif($d['lista'] == 'menor'): ?>
                        <span class="warn">MENOR</span>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>


<!-- ========================= -->
<!-- 📅 REPORTE R DEL DÍA -->
<!-- ========================= -->
<div id="rep4" class="scroll-body" style="display:none;">

    <!-- ALERTA -->
    <div style="
        background:#fff3e0;
        border:1px solid #ffb74d;
        padding:10px;
        border-radius:8px;
        margin-bottom:10px;
        font-size:12px;
        color:#e65100;
    ">
        📊 Vista Oficina — Consolidado del día <?= date('d/m/Y', strtotime($fecha)) ?>
    </div>

    <!-- ========================= -->
    <!-- 📊 KPI -->
    <!-- ========================= -->
    <div class="summary-row">

        <div class="sum-card blue">
            <div class="sum-label">TOTAL 3 TURNOS</div>
            <div class="sum-value">$<?= number_format($total_dia,2) ?></div>
        </div>

        <div class="sum-card green">
            <div class="sum-label">EFECTIVO</div>
            <div class="sum-value">$<?= number_format($efectivo,2) ?></div>
        </div>

        <div class="sum-card orange">
            <div class="sum-label">TARJETA</div>
            <div class="sum-value">$<?= number_format($tarjeta,2) ?></div>
        </div>

        <div class="sum-card red">
            <div class="sum-label">FACTURAS</div>
            <div class="sum-value"><?= $facturas ?></div>
        </div>

    </div>

    <!-- ========================= -->
    <!-- 📋 TABLA -->
    <!-- ========================= -->
    <table class="g-table">

        <thead>
            <tr>
                <th>TURNO</th>
                <th>HAB</th>
                <th>NOMBRE</th>
                <th>PAGO</th>
                <th>SUBTOTAL</th>
                <th>IVA</th>
                <th>ISH</th>
                <th>TOTAL</th>
                <th>ENTRADA</th>
                <th>SALIDA</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach($r_turnos as $t => $rows): ?>

            <?php foreach($rows as $i => $r): ?>

                <tr>

                    <?php if($i == 0): ?>
                        <td rowspan="<?= count($rows) ?>">
                            <strong style="color:#1565c0;">T<?= $t ?></strong>
                        </td>
                    <?php endif; ?>

                    <td><?= $r['numero'] ?></td>
                    <td><?= $r['nombre_huesped'] ?></td>
                    <td><?= $r['forma_pago'] ?></td>

                    <td>$<?= number_format($r['precio_base'],2) ?></td>
                    <td>$<?= number_format($r['iva'],2) ?></td>
                    <td>$<?= number_format($r['ish'],2) ?></td>
                    <td><strong>$<?= number_format($r['total'],2) ?></strong></td>

                    <td><?= date('H:i', strtotime($r['hora_entrada_1'])) ?></td>
                    <td><?= $r['hora_salida_1'] ? date('H:i', strtotime($r['hora_salida_1'])) : '—' ?></td>

                </tr>

            <?php endforeach; ?>

        <?php endforeach; ?>

        </tbody>

        <!-- TOTAL -->
        <tfoot>
            <tr class="total-row">
                <td colspan="7">TOTAL DEL DÍA</td>
                <td><strong>$<?= number_format($total_dia,2) ?></strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>

    </table>

</div>

<!-- ========================= -->
<!-- ✏️ REPORTE F EDITABLE -->
<!-- ========================= -->
<div id="rep5" class="scroll-body" style="display:none;">

    <!-- ALERTA -->
    <div style="
        background:#e8f5e9;
        border:1px solid #81c784;
        padding:10px;
        border-radius:8px;
        margin-bottom:10px;
        font-size:12px;
        color:#2e7d32;
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">
        ✏️ Reporte Fiscal Editable — Ajusta valores antes de declarar

        <button class="btn-primary btn-green" onclick="guardarCambios()">
            💾 Guardar cambios
        </button>
    </div>

    <!-- TABLA -->
    <table class="g-table">

        <thead>
            <tr>
                <th>HAB</th>
                <th>NOMBRE</th>
                <th>PAGO</th>
                <th>SUBTOTAL</th>
                <th>IVA</th>
                <th>ISH</th>
                <th>TOTAL</th>
                <th>RFC</th>
                <th>NOTAS</th>
            </tr>
        </thead>

        <tbody id="tablaEditable">

        <?php foreach($fdata as $r): ?>

            <tr data-id="<?= $r['id'] ?>">

                <td><?= $r['numero'] ?></td>
                <td><?= $r['nombre_huesped'] ?></td>
                <td><?= $r['forma_pago'] ?></td>

                <td><input class="inp" value="<?= $r['precio_base'] ?>"></td>
                <td><input class="inp" value="<?= $r['iva'] ?>"></td>
                <td><input class="inp" value="<?= $r['ish'] ?>"></td>
                <td><input class="inp total" value="<?= $r['total'] ?>"></td>

                <td><input class="inp" value="<?= $r['rfc'] ?? '' ?>" placeholder="RFC..."></td>
                <td><input class="inp" value="<?= $r['notas'] ?? '' ?>" placeholder="Nota..."></td>

            </tr>

        <?php endforeach; ?>

        </tbody>

        <!-- TOTALES -->
        <tfoot>
            <tr class="total-row">
                <td colspan="3">TOTALES</td>
                <td id="t_subtotal">0</td>
                <td id="t_iva">0</td>
                <td id="t_ish">0</td>
                <td id="t_total">0</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>

    </table>

</div>

    </div>
</div>

<script>
function showRep(n, el) {
    for (let i = 1; i <= 5; i++) {
        const r = document.getElementById('rep' + i);
        if (r) r.style.display = (i === n ? 'block' : 'none');
    }

    document.querySelectorAll('.rtab').forEach(t => {
        t.classList.remove('active');
        t.classList.add('inactive');
    });

    el.classList.remove('inactive');
    el.classList.add('active');
}
</script>

<script>

// =========================
// 🔄 RECALCULAR TOTALES
// =========================
function recalcular(){

    let subtotal=0, iva=0, ish=0, total=0;

    document.querySelectorAll('#tablaEditable tr').forEach(row=>{
        const inputs = row.querySelectorAll('input');

        subtotal += parseFloat(inputs[0].value) || 0;
        iva      += parseFloat(inputs[1].value) || 0;
        ish      += parseFloat(inputs[2].value) || 0;
        total    += parseFloat(inputs[3].value) || 0;
    });

    document.getElementById('t_subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('t_iva').innerText = iva.toFixed(2);
    document.getElementById('t_ish').innerText = ish.toFixed(2);
    document.getElementById('t_total').innerText = total.toFixed(2);
}

// auto recalcular
document.addEventListener('input', recalcular);
window.onload = recalcular;


// =========================
// 💾 GUARDAR
// =========================
function guardarCambios(){

    let data = [];

    document.querySelectorAll('#tablaEditable tr').forEach(row=>{
        const inputs = row.querySelectorAll('input');

        data.push({
            id: row.dataset.id,
            subtotal: inputs[0].value,
            iva: inputs[1].value,
            ish: inputs[2].value,
            total: inputs[3].value,
            rfc: inputs[4].value,
            notas: inputs[5].value
        });
    });

    fetch('<?= base_url("reportes/guardar") ?>',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(data)
    })
    .then(r=>r.json())
    .then(res=>{
        alert('Guardado correctamente');
    });
}

</script>

<?= view('layout/footer') ?>