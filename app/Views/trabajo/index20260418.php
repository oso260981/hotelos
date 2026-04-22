<?= view('layout/header') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/trabajo.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/pasajero.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/general.css') ?>">


<div class="work-panel">

    <!-- =====================================================
         TOOLBAR ESTADOS
    ====================================================== -->
    <div class="work-toolbar">

        <div class="legend">

            <span class="badge estado-sucia" onclick="filtrarEstado('S')">◆ Sucia</span>
            <span class="badge estado-limpia" onclick="filtrarEstado('X')">X Limpia</span>
            <span class="badge estado-manto" onclick="filtrarEstado('M')">M Mantenimiento</span>
            <span class="badge estado-planta" onclick="filtrarEstado('P')">P Planta</span>

        </div>

        <div class="hint">
            💡 Toca una fila para gestionar la habitación
        </div>

    </div>


    <!-- =====================================================
         GRID PMS
    ====================================================== -->
    <div class="work-grid">

        <table class="pms-table">
            <!-- ⭐ CAMBIO IMPORTANTE -->

            <thead>

                <tr class="pms-head-1">
                    <th>A</th>
                    <th>HAB</th>
                    <th>C</th>
                    <th>D</th>
                    <th>E</th>
                    <th>F - $</th>
                    <th>G+</th>
                    <th>H – HORA E</th>
                    <th>I+</th>
                    <th>J – HORA S</th>
                    <th>K+</th>
                    <th>L – NOMBRE / ID</th>
                    <th>M+</th>
                    <th>Rº</th>
                    <th>S</th>
                    <th>›</th>
                </tr>

                <tr class="pms-head-2">
                    <th>STATUS</th>
                    <th>N°</th>
                    <th>TIPO</th>
                    <th>PERS</th>
                    <th>PAGO</th>
                    <th>PRECIO</th>
                    <th>+PAGO</th>
                    <th>ENTRA</th>
                    <th>+H.E</th>
                    <th>SALE</th>
                    <th>+H.S</th>
                    <th class="col-nombre">NOMBRE / ESCANEO ID</th>
                    <th>+HUESP</th>
                    <th>OPC</th>
                    <th>TICKET</th>
                    <th></th>
                </tr>

            </thead>

            <tbody id="gridTrabajo"></tbody>

        </table>

    </div>


    <!-- =====================================================
         FOOTER PISOS
    ====================================================== -->
    <div class="work-footer">
        PISO:

        <button onclick="filtrarPiso('PB')" class="btn-floor active">PB</button>

        <button onclick="filtrarPiso(1)" class="btn-floor">1°</button>

        <button onclick="filtrarPiso(2)" class="btn-floor">2°</button>

        <button onclick="filtrarPiso(3)" class="btn-floor">3°</button>
    </div>

</div>




<!-- =====================================================
     MODAL ADMINISTRATIVO
===================================================== -->



<?= view('trabajo/mod_reservacion') ?>
<?= view('trabajo/mod_administrativo') ?>
<?= view('trabajo/modal_acciones') ?>

<!-- =====================================================
     BASE URL GLOBAL
===================================================== -->
<script>
window.base_url = "<?= site_url('/') ?>";
</script>

<!-- ⭐ evitar cache JS -->
<script src="<?= base_url('assets/js/trabajo.js?v='.time()) ?>"></script>
<script src="<?= base_url('assets/js/modal.js') ?>"></script>
<script src="<?= base_url('assets/js/modal_administrativo.js') ?>"></script>
<script src="<?= base_url('assets/js/modal_acciones.js') ?>"></script>

<?= view('layout/footer') ?>