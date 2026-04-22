<?= view('layout/header') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/pasajero.css') ?>">

<div class="pas-panel">

    <!-- =========================================
         TOOLBAR SUPERIOR
         ========================================= -->
    <div class="pas-toolbar">

        <!-- Botón regresar al menú principal -->
        <button class="pas-btn-menu">← Menú</button>

        <!-- Título principal del módulo -->
        <div class="pas-title">
            📋 Lista de Pasajeros
            <!-- aquí se cargará el total dinámicamente -->
            <span id="lbl_total_pas" class="pas-sub"></span>
        </div>

        <div class="pas-spacer"></div>

        <!-- acciones futuras -->
        <button class="pas-btn-print">🖨 Imprimir</button>
        <button class="pas-btn-export">📄 Exportar</button>

    </div>


    <!-- =========================================
         FILTROS RÁPIDOS
         ========================================= -->
    <div class="pas-filtros">

        <!-- cada chip debe disparar filtro en JS -->
        <span id="chip_all" class="pas-chip active" onclick="filtrar('ALL')">Todos</span>
        <span id="chip_act" class="pas-chip ok" onclick="filtrar('ACT')">Activos</span>
        <span id="chip_men" class="pas-chip warn" onclick="filtrar('MEN')">Con menores</span>
        <span id="chip_bl" class="pas-chip bad" onclick="filtrar('BL')">Lista negra</span>
        <span id="chip_vip" class="pas-chip vip" onclick="filtrar('VIP')">VIP</span>

    </div>


    <!-- =========================================
         LISTADO DINÁMICO
         ========================================= -->
    <div class="pas-scroll">
        <!-- aquí JS pintará las tarjetas de pasajeros -->
        <div id="box_pasajeros"></div>
    </div>

</div>






<!-- =========================================
     VARIABLE GLOBAL BASE URL
     ========================================= -->
<script>
window.base_url = "<?= base_url() ?>";
</script>

<!-- JS DEL MODULO -->
<script src="<?= base_url('assets/js/pasajeros.js') ?>"></script>




<?= view('layout/footer') ?>