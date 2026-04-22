<?= view('layout/header') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/pasajero.css') ?>">

<!-- =====================================================
     PANEL PRINCIPAL CATÁLOGO HUÉSPEDES
===================================================== -->
<div class="pas-panel">

    <!-- ================= TOOLBAR ================= -->
    <div class="pas-toolbar">

        <!-- botón regreso menú -->
        <button class="pas-btn-menu">← Menú</button>

        <!-- título módulo -->
        <div class="pas-title">
            👥 Catálogo de Clientes
            <span id="lbl_total_cat" class="pas-sub"></span>
        </div>

        <div class="pas-spacer"></div>

        <!-- buscador en vivo -->
        <input id="txt_buscar" class="pas-input" style="width:250px" placeholder="Buscar nombre o identificación">

        <!-- botón alta -->
        <button class="pas-btn-new" onclick="openModalCat()">
            ➕ Nuevo cliente
        </button>

    </div>

    <!-- ================= LISTADO CLIENTES ================= -->
    <div class="pas-scroll">
        <div id="box_catalogo"></div>
    </div>

</div>


<!-- =====================================================
     MODAL ALTA / EDICIÓN HUÉSPED
===================================================== -->
<div id="modal_cat" class="pas-modal">

    <div class="pas-modal-content">

        <!-- ================= HEADER ================= -->
        <div class="pas-modal-header">
            👤 Cliente
            <span style="cursor:pointer" onclick="closeModalCat()">✖</span>
        </div>

        <div class="pas-modal-body">

            <!-- id oculto -->
            <input type="hidden" id="cat_id">

            <!-- ================= FOTO PREVIEW ================= -->
            <div style="text-align:center;margin-bottom:15px">

                <img id="cat_foto_preview" src="<?= base_url('assets/img/user.png') ?>" style="
                        width:95px;
                        height:95px;
                        border-radius:50%;
                        object-fit:cover;
                        border:4px solid #e3f2fd;
                        box-shadow:0 3px 10px rgba(0,0,0,.15);
                     ">

                <div style="font-size:11px;color:#777;margin-top:5px">
                    Fotografía del cliente
                </div>

            </div>

            <!-- ================= FORMULARIO ================= -->
            <div class="pas-form-grid">

                <!-- identidad -->
                <input id="cat_nombre" class="pas-input" placeholder="Nombre">
                <input id="cat_apellido" class="pas-input" placeholder="Apellido">

                <select id="cat_tipo_doc" class="pas-input">
                    <option value="" disabled selected>Tipo identificación</option>
                </select>

                <input id="cat_ident" class="pas-input" placeholder="Número identificación">

                <!-- contacto -->
                <input id="cat_tel" class="pas-input" placeholder="Teléfono">
                <input id="cat_mail" class="pas-input" placeholder="Correo">

                <!-- dirección -->
                <input id="cat_dir" class="pas-input col-3" placeholder="Dirección">

                <input id="cat_ciudad" class="pas-input" placeholder="Ciudad">
                <input id="cat_estado" class="pas-input" placeholder="Estado">
                <input id="cat_cp" class="pas-input" placeholder="Código Postal">

                <input id="cat_pais" class="pas-input" placeholder="País">
                <input id="cat_nac" class="pas-input" placeholder="Nacionalidad">

                <!-- personales -->
                <input id="cat_fnac" type="date" class="pas-input">

                <select id="cat_genero" class="pas-input">
                    <option value="">Género</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>

                <!-- comercial -->
                <input id="cat_empresa" class="pas-input" placeholder="Empresa">

                <textarea id="cat_notas" class="pas-input col-3" placeholder="Notas"></textarea>

            </div>

            <hr>

            <!-- ================= ACCIONES BIOMÉTRICAS ================= -->
            <div style="
                display:flex;
                justify-content:center;
                gap:15px;
                margin-top:12px
            ">

                <button class="pas-btn-scan" onclick="scanDocumento()">
                    📄 Escanear INE / Pasaporte
                </button>

                <button class="pas-btn-photo" onclick="capturarFoto()">
                    📷 Tomar fotografía
                </button>

            
        </div>

        <!-- ================= FOOTER ================= -->
        <div class="pas-modal-footer">
            <button class="pas-btn-cancel" onclick="closeModalCat()">Cancelar</button>
            <button class="pas-btn-save" onclick="guardarCat()">Guardar cliente</button>
        </div>

    </div>

</div>


<!-- =====================================================
     MODAL CÁMARA (DOCUMENTO / FOTO)
===================================================== -->
<div id="modal_cam" class="pas-modal">

    <div class="pas-modal-content" style="width:650px">

        <div class="pas-modal-header">
            Cámara
            <span style="cursor:pointer" onclick="cerrarCam()">✖</span>
        </div>

        <div class="pas-modal-body" style="text-align:center">

            <!-- stream cámara -->
            <video id="cam_video" autoplay playsinline style="width:100%;border-radius:10px;background:#000">
            </video>

            <br><br>

            <!-- botón captura -->
            <button class="pas-btn-save" onclick="tomarFoto()">
                📸 Capturar
            </button>

        </div>

    </div>

</div>


<!-- variable global base_url -->
<script>
window.base_url = "<?= base_url() ?>";
</script>

<!-- lógica catálogo -->
<script src="<?= base_url('assets/js/pasajeros_catalogo.js') ?>"></script>

<?= view('layout/footer') ?>