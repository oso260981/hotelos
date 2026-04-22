<?= view('layout/header') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/sistema.css') ?>">


<style>
/* TABLA BASE */
.turnos-table {
    border-collapse: collapse;
    width: 100%;
}

/* HEADER */
.turnos-table th {
    background: #2b6cb0;
    color: white;
    font-size: 11px;
    text-align: left;
    padding: 6px;
}

/* CELDAS */
.turnos-table td {
    padding: 4px 6px;
    font-size: 12px;
}

/* FILAS */
.turnos-table tr:nth-child(even) {
    background: #f5f7fa;
}

/* INPUTS */
.turnos-table input {
    height: 20px;
    font-size: 11px;
    padding: 2px 4px;
    border: 1px solid #cfd6e0;
    border-radius: 3px;
    width: 80px;
}

/* NOMBRE MÁS LARGO */
.turnos-table .nombre {
    width: 140px;
}

/* SELECT RESPONSABLE */
.turnos-table select {
    height: 22px;
    font-size: 11px;
    border: 1px solid #cfd6e0;
    border-radius: 3px;
    padding: 2px 4px;
}

/* SELECT TURNOS ARRIBA */
.select-mini {
    height: 24px;
    font-size: 12px;
    padding: 2px 6px;
    border: 1px solid #cfd6e0;
    border-radius: 4px;
}

/* HOVER SUAVE */
.turnos-table tr:hover {
    background: #eaf2ff;
}

/* SELECT TURNOS (como img2) */
.select-mini {
    height: 32px;
    min-width: 140px;

    font-size: 13px;
    padding: 4px 28px 4px 10px;

    border: 1px solid #cfd6e0;
    border-radius: 6px;

    background: #fff;

    outline: none;
    cursor: pointer;

    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    transition: all 0.15s ease;
}

/* FLECHA */
.select-mini {
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='12' viewBox='0 0 20 20' width='12' xmlns='http://www.w3.org/2000/svg'><path d='M5 7l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

/* HOVER */
.select-mini:hover {
    border-color: #94a3b8;
}

/* 🔥 FOCUS (EL EFECTO DE TU IMG2) */
.select-mini:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
}

</style>



<div class="inner-body">

    <!-- =====================================================
     HEADER INTERNO
     =====================================================-->
    <div class="inner-topbar">
        <button class="btn-back">← Menú</button>

        <div>
            <div class="inner-title">⚙️ Editar Sistema</div>
            <div class="inner-subtitle">Configuración general de HotelOS</div>
        </div>
    </div>

    <!-- =====================================================
     TABS CONFIGURACIÓN
     =====================================================-->
    <div class="rtab-bar">

        <div class="rtab active" onclick="tabCfg(1,this)">
            🏨 Pisos y Habitaciones
        </div>

        <div class="rtab inactive" onclick="tabCfg(2,this)">
            🎫 Tickets
        </div>

        <div class="rtab inactive" onclick="tabCfg(3,this)">
            🔐 Claves
        </div>

        <div class="rtab inactive" onclick="tabCfg(4,this)">
            🕐 Turnos
        </div>

        <div class="rtab inactive" onclick="tabCfg(5,this)">
            💰 Impuestos
        </div>

        <div class="rtab inactive" onclick="tabCfg(6,this)">
            🖨 Periféricos
        </div>

    </div>
    <!-- =====================================================
     TAB PISOS y HABITACIONES
=====================================================-->

    <div id="cfg1" class="cfg-panel scroll-body">
        <div id="t1" class="scroll-body">

            <div class="form-section">
                <div class="form-section-title">🏨 Pisos registrados</div>

                <table class="g-table">
                    <tr>
                        <th>PISO</th>
                        <th>NOMBRE</th>
                        <th>N° HAB</th>
                        <th>ACTIVAS</th>
                        <th>ACCIONES</th>
                    </tr>

                    <?php foreach($pisos as $p): ?>
                    <tr>
                        <td><?= $p['PISO'] ?></td>
                        <td><?= $p['NOMBRE'] ?></td>
                        <td><?= $p['N° HABITACIONES'] ?></td>
                        <td><?= $p['ACTIVAS'] ?></td>
                        <td><button class="btn-primary" onclick="editarPiso('<?= $p['id'] ?>',
                                                                        '<?= $p['PISO'] ?>',
                                                                        '<?= $p['NOMBRE'] ?>')">
                                Editar
                            </button>
                        </td>



                    </tr>
                    <?php endforeach; ?>
                </table>

                <br>

                <button class="btn-primary" onclick="abrirModalPiso()">+ Añadir piso</button>
                <button class="btn-primary btn-orange" onclick="abrirModalHab()">+ Añadir habitación</button>

            </div>

            <div class="form-section">
                <div class="form-section-title">🔧 Estados de habitación disponibles</div>

                <div style="margin-bottom:10px">
                    <button class="btn-primary" onclick="abrirModalEstado()">+ Nuevo estado</button>
                </div>

                <table class="g-table">

                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Icono</th>
                        <th>Activo</th>
                        <th></th>
                    </tr>

                    <?php foreach($estados as $e): ?>
                    <tr>

                        <td><?= $e['codigo'] ?></td>
                        <td><?= $e['nombre'] ?></td>
                        <td><?= $e['descripcion'] ?></td>
                        <td><?= $e['icono'] ?></td>

                        <td>
                            <label class="switch">
                                <input type="checkbox" <?= $e['activo']==1?'checked':'' ?>
                                    onchange="toggleEstado(<?= $e['id'] ?>,this)">
                                <span class="slider"></span>
                            </label>
                        </td>

                        <td>
                            <button class="btn-primary" onclick="editarEstado(
                                        <?= $e['id'] ?>,
                                       '<?= $e['codigo'] ?>',
                                       '<?= $e['nombre'] ?>',
                                       '<?= $e['descripcion'] ?>',
                                       '<?= $e['icono'] ?>'
                                        )">
                                Editar
                            </button>

                            <button class="btn-gray" onclick="eliminarEstado(<?= $e['id'] ?>)">
                                Eliminar
                            </button>
                        </td>

                    </tr>
                    <?php endforeach; ?>

                </table>

            </div>

        </div>

    </div>


    <!-- =====================================================
     TAB TICKETS
=====================================================-->

    <div id="cfg2" class="cfg-panel scroll-body">


        <!-- DATOS EMPRESA -->
        <div class="form-section">
            <div class="form-section-title">🎫 Datos del establecimiento en ticket</div>

            <!-- BOTONES ACCIONES TICKET -->
            <div class="ticket-actions">

                <button class="btn-primary" onclick="openModalEmpresa()">
                    🏢 Seleccionar razón social
                </button>

                <button class="btn-primary" onclick="openModalTicketConfig()">
                    ⚙ Seleccionar configuración
                </button>

            </div>



            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;">

                <input type="hidden" id="tk_rs_id">

                <div>
                    <label>Nombre del establecimiento</label>
                    <input class="input" id="tk_nombre">
                </div>
                <div>
                    <label>RFC</label>
                    <input class="input" id="tk_rfc">
                </div>

                <div>
                    <label>Teléfono</label>
                    <input class="input" id="tk_tel">

                </div>

                <div>
                    <label>Ancho del ticket</label>
                    <select class="input" id="tk_ancho">
                        <option value="58">58 mm</option>
                        <option value="80">80 mm</option>
                    </select>
                </div>

                <div>
                    <label>Logo en ticket</label>
                    <select class="input" id="tk_logo">
                        <option value="1">Sí, centrado</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div>
                    <label>Copias por ticket</label>
                    <select class="input" id=tk_copias>
                        <option selected>1</option>
                        <option>2</option>
                    </select>
                </div>

                <div style="grid-column:1/-1;">
                    <label>Mensaje de pie de ticket</label>
                    <input class="input" id=tk_mensaje>
                </div>

            </div>
        </div>

        <!-- CAMPOS VISIBLES -->
        <div class="form-section">

            <div class="form-section-title">📋 Campos visibles en el ticket</div>

            <div class="ticket-toggle-row">
                <div>Número de habitación</div>
                <label class="switch">
                    <input type="checkbox" id="sw_habitacion">
                    <span></span>
                </label>
            </div>

            <div class="ticket-toggle-row">
                <div>Nombre del huésped</div>
                <label class="switch">
                    <input type="checkbox" id="sw_huesped">
                    <span></span>
                </label>
            </div>

            <div class="ticket-toggle-row">
                <div>Hora y fecha de entrada</div>
                <label class="switch">
                    <input type="checkbox" id="sw_fecha">
                    <span></span>
                </label>
            </div>

            <div class="ticket-toggle-row">
                <div>Desglose SUBTOTAL / IVA / ISH / TOTAL</div>
                <label class="switch">
                    <input type="checkbox" id="sw_desglose">
                    <span></span>
                </label>
            </div>

            <div class="ticket-toggle-row">
                <div>Forma de pago</div>
                <label class="switch">
                    <input type="checkbox" id="sw_pago">
                    <span></span>
                </label>
            </div>

            <div class="ticket-toggle-row last">
                <div>Número de folio consecutivo</div>
                <label class="switch">
                    <input type="checkbox" id="sw_folio">
                    <span></span>
                </label>
            </div>

        </div>

        <button class="btn-primary" onclick="saveActiveTicketConfig()">
            💾 Guardar configuración
        </button>

    </div>

    <!-- =====================================================
     TAB CLAVES
=====================================================-->

    <!-- ==============================
         USUARIOS Y ROLES
    ===============================-->

    <div id="cfg3" class="cfg-panel scroll-body" style="display:none">

        <div class="form-section">

            <div class="form-section-title">
                🔐 Usuarios y niveles de acceso
            </div>

            <table class="g-table">

                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>NOMBRE</th>
                        <th>ROL</th>
                        <th>RECEPCIÓN</th>
                        <th>REPORTES</th>
                        <th>EDITAR SISTEMA</th>
                        <th>ESTATUS USUARIO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>

                <tbody id="gridUsuarios">
                    <!-- dinámico -->
                </tbody>

            </table>

            <div style="margin-top:12px;">

                <button class="btn-primary" onclick="newUsuario()">+ Añadir usuario
                </button>
            </div>

        </div>


        <!-- ==============================
     CAMBIAR CONTRASEÑA Y ROLES
==============================-->
        <div style="display:grid;grid-template-columns: 520px 1fr;gap:24px;align-items:start;">

            <!-- ==================================
         CAMBIAR CONTRASEÑA (IZQUIERDA)
    ===================================-->
            <div class="form-section">

                <div class="form-section-title">
                    🔑 Cambiar contraseña
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr;gap:14px;">

                    <div>
                        <label>Usuario</label>
                        <select class="input" id="pass_user"></select>
                    </div>

                    <div>
                        <label>Contraseña actual</label>
                        <input class="input" type="password" id="pass_actual">
                    </div>

                    <div>
                        <label>Nueva contraseña</label>
                        <input class="input" type="password" id="pass_new">
                    </div>

                    <div>
                        <label>Confirmar nueva contraseña</label>
                        <input class="input" type="password" id="pass_new2">
                    </div>

                </div>

                <div style="margin-top:14px;">
                    <button class="btn-primary" onclick="changePassword()">
                        Cambiar contraseña
                    </button>
                </div>

            </div>


            <!-- ==================================
         TABLA ROLES (DERECHA)
    ===================================-->
            <div class="form-section">

                <div class="form-section-title">
                    🛡 Roles del sistema
                </div>

                <table class="g-table">

                    <thead>
                        <tr>
                            <th>ROL</th>
                            <th>RECEPCIÓN</th>
                            <th>REPORTES</th>
                            <th>EDITAR SISTEMA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>

                    <tbody id="gridRoles">
                        <!-- dinámico -->
                    </tbody>

                </table>

                <div style="margin-top:12px;">
                    <button class="btn-primary" onclick="openModalRol()">
                        + Añadir rol
                    </button>
                </div>

            </div>

        </div>




    </div>


    <!-- =====================================================
     TAB Turnos
=====================================================-->

    <div id="cfg4" class="cfg-panel scroll-body" style="display:none">

       <div class="form-section">

    <div class="form-section-title">🕐 Configuración de turnos del día</div>

    <!-- SELECT SIMPLE -->
    <div style="margin-bottom:10px;">
        <label style="font-size:12px;">Número de turnos por día</label><br>
        <select id="numTurnos" class="select-mini" onchange="renderTurnos()">
            <option value="3" selected>3 turnos</option>
            <option value="2">2 turnos</option>
            <option value="1">1 turno</option>
        </select>
    </div>
    

    <!-- TABLA -->
    <table class="g-table turnos-table">
        <thead>
            <tr>
                <th style="width:60px;">TURNO</th>
                <th>NOMBRE</th>
                <th>HORA INICIO</th>
                <th>HORA FIN</th>
                <th>RESPONSABLE</th>
            </tr>
        </thead>
        <tbody id="tablaTurnos"></tbody>
    </table>

    <button class="btn-primary btn-green" style="margin-top:10px;" onclick="guardarTurnosFront()">
    💾 Guardar turnos
</button>

</div>




       <!--  <div class="turnos-panel">

            <div class="turnos-header">
                Configuración de turnos del día
            </div>

            <div class="turnos-toolbar">
                Número de turnos por día
                <br>
                <select id="numTurnos" class="input" onchange="renderTurnos();renderTimelineFromDB()">
                    <option value="1">1 turno</option>
                    <option value="2">2 turnos</option>
                    <option value="3" selected>3 turnos</option>
                </select>
            </div>

            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:60px">Turno</th>
                        <th>Nombre</th>
                        <th style="width:160px">Hora inicio</th>
                        <th style="width:160px">Hora fin</th>
                        <th style="width:200px">Responsable</th>
                    </tr>
                </thead>
                <tbody id="gridTurnos"></tbody>
            </table>

            <div style="font-weight:600;margin-top:14px;color:#2563eb">
                Vista cobertura del día
            </div>

            <div id="timelineTurnos" class="turnos-timeline"></div>

            <button class="turnos-save" onclick="saveTurnosConfig()">
                💾 Guardar turnos
            </button>

        </div> -->


    </div>



    <!-- =====================================================
     TAB Impuestos
=====================================================-->
    <div id="cfg5" class="cfg-panel scroll-body" style="display:none">
        <!-- ===== IMPUESTOS ===== -->
        <div class="form-section" style="display:block !important;">

            <div class="form-section-title">
                💰 Tasas de impuestos
            </div>

            <div style="display:grid;grid-template-columns: 260px 260px;gap:18px 30px;width:520px;">

                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:12px;color:#607d8b;margin-bottom:4px;">
                        IVA (%)
                    </label>
                    <input type="number" id="cfg_iva" data-id="<?= id_cfg('iva') ?>" type="number"
                        value="<?= cfg('iva') ?>" class="form-control"
                        style="padding:8px 10px;border:1px solid #b0bec5;border-radius:7px;">
                </div>

                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:12px;color:#607d8b;margin-bottom:4px;">
                        ISH — Impuesto sobre Hospedaje (%)
                    </label>
                    <input id="cfg_ish" data-id="<?= id_cfg('ish') ?>" type="number" value="<?= cfg('ish') ?>"
                        class="form-control" style="padding:8px 10px;border:1px solid #b0bec5;border-radius:7px;">
                </div>

                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:12px;color:#607d8b;margin-bottom:4px;">
                        Precio mostrado al cliente
                    </label>

                    <select id="cfg_precio_modo" data-id="<?= id_cfg('precio_modo') ?>"
                        style="padding:8px 10px;border:1px solid #b0bec5;border-radius:7px;">


                        <option value="final" <?= cfg('precio_modo')=='final' ? 'selected':'' ?>>
                            Precio final (con impuestos)
                        </option>

                        <option value="base" <?= cfg('precio_modo')=='base' ? 'selected':'' ?>>
                            Precio base
                        </option>

                    </select>

                </div>

                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:12px;color:#607d8b;margin-bottom:4px;">
                        Desglose en ticket
                    </label>

                    <select id="cfg_ticket_desglose" data-id="<?= id_cfg('ticket_desglose') ?>"
                        style="padding:8px 10px;border:1px solid #b0bec5;border-radius:7px;">

                        <option value="siempre" <?= cfg('ticket_desglose')=='siempre' ? 'selected':'' ?>>
                            Siempre visible
                        </option>

                        <option value="factura" <?= cfg('ticket_desglose')=='factura' ? 'selected':'' ?>>
                            Solo con factura
                        </option>

                    </select>


                </div>

            </div>

        </div>


        <!-- ===== CATÁLOGO ===== -->
        <div class="form-section">

            <div class="form-section-title">
                🏷 Catálogo de precios
            </div>

            <div style="margin-bottom:12px;display:flex;gap:10px;">

                <button class="btn-primary" onclick="openModalTipoHab()">
                    ➕ Agregar tipo de habitación
                </button>

                <button class="btn-gray" onclick="recalcularCatalogoBD()">
                    🔄 Recalcular impuestos
                </button>

            </div>
            <table class="g-table">

                <thead>
                    <tr>
                        <th>TIPO</th>
                        <th>PRECIO BASE</th>
                        <th>IVA</th>
                        <th>ISH</th>
                        <th>TOTAL</th>
                        <th style="width:120px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="gridTiposHab"></tbody>
            </table>

        </div>

        <div style="margin-top:18px">
            <button class="btn-primary btn-green" onclick="saveConfigImpuestos()">
                💾 Guardar configuración
            </button>
        </div>

    </div>

    <!-- =====================================================
     TAB Perfifericos
=====================================================-->
    <div id="cfg6" class="cfg-panel scroll-body" style="display:none">

        <!-- ===== TABLA IMPRESORAS ===== -->
        <div class="hos-card-table mb-3">

            <table class="hos-table">
                <thead>
                    <tr>
                        <th>DISPOSITIVO</th>
                        <th>TIPO</th>
                        <th>CONEXIÓN</th>
                        <th>PUERTO / IP</th>
                        <th>ESTADO</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>

                <tbody id="tablaImpresoras">
                    <!-- JS insertará aquí -->
                </tbody>
            </table>

        </div>


        <!-- ===== PERIFERICOS ===== -->



        <div class="hos-card hos-card-perifericos mb-3">

            <div class="hos-title">🔌 Periféricos</div>

            <!-- SCANNER -->
            <div class="hos-toggle-row">
                <div>
                    <div class="hos-label">🪪 Scanner de ID / Pasaporte</div>
                    <div class="hos-sub" id="lbl_scanner">
                        Detectando dispositivo...
                    </div>
                </div>
                <label class="hos-switch off">
                    <input type="checkbox" id="sw_scanner">
                    <span></span>
                </label>
            </div>

            <!-- FIRMA -->
            <div class="hos-toggle-row">
                <div>
                    <div class="hos-label">✍️ Touch pad de firma digital (exterior)</div>
                    <div class="hos-sub" id="lbl_firma">
                        Detectando dispositivo...
                    </div>
                </div>
                <label class="hos-switch off">
                    <input type="checkbox" id="sw_firma">
                    <span></span>
                </label>
            </div>

            <!-- CAMARA -->
            <div class="hos-toggle-row">
                <div>
                    <div class="hos-label">📷 Cámara discreta de registro</div>
                    <div class="hos-sub" id="lbl_camara">
                        Detectando dispositivo...
                    </div>
                </div>
                <label class="hos-switch off">
                    <input type="checkbox" id="sw_camara">
                    <span></span>
                </label>
            </div>

            <!-- BACKUP -->
            <div class="hos-toggle-row last">
                <div>
                    <div class="hos-label">💾 Disco externo USB — Respaldo mensual</div>
                    <div class="hos-sub" id="lbl_backup">
                        Detectando dispositivo...
                    </div>
                </div>
                <label class="hos-switch off">
                    <input type="checkbox" id="sw_backup">
                    <span></span>
                </label>
            </div>

            <div class="hos-perifericos-footer">
                <button class="hos-btn-save" onclick="guardarConfigPerifericos()">
                    💾 Guardar periféricos
                </button>
            </div>

        </div>
    </div>









</div>





<!-- =====================================
     MODAL USUARIO
======================================-->
<div class="modal-overlay" id="modalUsuario">

    <div class="modal-window" style="width:720px;">

        <div class="modal-head">
            👤 Usuario del sistema
            <span onclick="closeModalUsuario()" style="cursor:pointer">✖</span>
        </div>

        <div class="modal-body">

            <div class="form-section">

                <div class="form-section-title">
                    📋 Datos generales
                </div>

                <div style="
                    display:grid;
                    grid-template-columns: repeat(2,1fr);
                    gap:12px;
                ">

                    <input type="hidden" id="usr_id">

                    <div>
                        <label>Usuario (login)</label>
                        <input class="input" id="usr_username">
                    </div>

                    <div>
                        <label>Password</label>
                        <input class="input" id="usr_password" type="password" placeholder="Solo para crear o cambiar">
                    </div>

                    <div>
                        <label>Nombre</label>
                        <input class="input" id="usr_nombre">
                    </div>

                    <div>
                        <label>Apellido</label>
                        <input class="input" id="usr_apellido">
                    </div>

                    <div>
                        <label>Teléfono</label>
                        <input class="input" id="usr_telefono">
                    </div>

                    <div>
                        <label>Dirección</label>
                        <input class="input" id="usr_direccion">
                    </div>

                    <div>
                        <label>Rol</label>
                        <select class="input" id="usr_rol"></select>
                    </div>

                </div>

            </div>

        </div>

        <div class="modal-foot" style="display:flex;justify-content:space-between;align-items:center;">

            <button class="btn-gray" onclick="closeModalUsuario()">
                ✖ Cancelar
            </button>

            <div style="display:flex;gap:8px;">

                <button class="btn-danger" onclick="deleteUsuario()">
                    🗑 Eliminar
                </button>

                <button class="btn-success" onclick="saveUsuario()">
                    💾 Guardar
                </button>

            </div>

        </div>

    </div>

</div>


<!-- =====================================
     MODAL ROL DEL SISTEMA
======================================-->
<div class="modal-overlay" id="modalRol">

    <div class="modal-window" style="width:540px;">

        <!-- HEADER -->
        <div class="modal-head">
            🛡 Rol del sistema
            <span onclick="closeModalRol()" style="cursor:pointer">✖</span>
        </div>

        <!-- BODY -->
        <div class="modal-body">

            <div class="form-section">

                <div class="form-section-title">
                    Configuración de permisos
                </div>

                <input type="hidden" id="rol_id">

                <!-- NOMBRE -->
                <div style="margin-bottom:14px;">
                    <label>Nombre del rol</label>
                    <input class="input" id="rol_nombre">
                </div>

                <!-- SWITCHES -->
                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr;
                    gap:16px;
                ">

                    <label class="switch">
                        <input type="checkbox" id="rol_recepcion">
                        <span></span>
                        Acceso recepción
                    </label>

                    <label class="switch">
                        <input type="checkbox" id="rol_reportes">
                        <span></span>
                        Acceso reportes
                    </label>

                    <label class="switch">
                        <input type="checkbox" id="rol_sistema">
                        <span></span>
                        Editar sistema
                    </label>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-foot" style="display:flex;justify-content:space-between;align-items:center;">

            <button class="btn-gray" onclick="closeModalRol()">
                Cancelar
            </button>

            <div style="display:flex;gap:8px;">

                <button class="btn-danger" onclick="deleteRol()">
                    🗑 Eliminar
                </button>

                <button class="btn-success" onclick="saveRol()">
                    💾 Guardar
                </button>

            </div>

        </div>

    </div>

</div>



<!-- =====================================================
     MODAL CONFIGURACION SISTEMA Nuevo
=====================================================-->

<div class="modal-overlay" id="modalNewTicketConfig">

    <div class="modal-window" style="width:420px;">

        <div class="modal-head">
            Nueva configuración ticket
            <span onclick="closeModalNewTicketConfig()">✖</span>
        </div>

        <div class="modal-body">

            <label>Nombre configuración</label>
            <input class="input" id="new_tc_nombre">

            <label>Descripción</label>
            <input class="input" id="new_tc_desc">

        </div>

        <div class="modal-foot">

            <button class="btn-gray" onclick="closeModalNewTicketConfig()">Cancelar</button>
            <button class="btn-primary" onclick="saveNewTicketConfig()">Guardar</button>

        </div>

    </div>

</div>

<!-- =====================================================
     MODAL CONFIGURACION SISTEMA
=====================================================-->
<div class="modal-overlay" id="modalTicketConfig">

    <div class="modal-window" style="width:760px;">

        <div class="modal-head">
            Configuraciones de ticket
            <span onclick="closeModalTicketConfig()">✖</span>
        </div>

        <!-- ======== VISTA GRID ======== -->
        <div class="modal-body" id="vistaGridTicketConfig">

            <button class="btn-primary" onclick="showFormTicketConfig()">
                + Nueva configuración
            </button>

            <table class="g-table" style="margin-top:12px;">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Razón social</th>
                        <th>Fecha</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody id="gridTicketConfig"></tbody>
            </table>

        </div>

        <!-- ======== VISTA FORM ======== -->
        <div class="modal-body" id="vistaFormTicketConfig" style="display:none">

            <input type="hidden" id="tc_id">

            <label>Nombre configuración</label>
            <input class="input" id="tc_nombre">

            <label>Descripción</label>
            <input class="input" id="tc_desc">

        </div>

        <!-- =========================
     FOOTER ESTILO PMS
==========================-->
        <div class="modal-foot" style="display:flex;justify-content:space-between;align-items:center;">

            <!-- IZQUIERDA -->
            <div>
                <button class="btn-gray" onclick="closeModalTicketConfig()">
                    ✖ Cerrar
                </button>
            </div>

            <!-- DERECHA -->
            <div style="display:flex;gap:8px;">

                <button class="btn-danger" onclick="deleteTicketConfig()">
                    🗑 Eliminar
                </button>

                <button class="btn-primary" onclick="selectTicketConfig()">
                    ✔ Seleccionar
                </button>

            </div>

        </div>



    </div>

</div>

<!-- =====================================================
     MODAL CATALOGO RAZONES SOCIALES
=====================================================-->

<div class="modal-overlay" id="modalEmpresa">

    <div class="modal-window" style="width:760px;">

        <div class="modal-head">
            Catálogo de razones sociales
            <span onclick="closeModalEmpresa()" style="cursor:pointer">✖</span>
        </div>

        <!-- =========================
             VISTA GRID
        ==========================-->
        <div class="modal-body" id="vistaGridEmpresas">

            <button class="btn-primary" onclick="newEmpresa()">
                + Nueva razón social
            </button>

            <table class="g-table" style="margin-top:12px;">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody id="gridEmpresas"></tbody>
            </table>

        </div>

        <!-- =========================
             VISTA FORM
        ==========================-->
        <div class="modal-body" id="vistaFormEmpresa" style="display:none">

            <div class="form-section">

                <div class="form-section-title">
                    🏢 Datos de razón social
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">

                    <input type="hidden" id="emp_id">

                    <div>
                        <label>Nombre empresa</label>
                        <input class="input" id="emp_nombre">
                    </div>

                    <div>
                        <label>RFC</label>
                        <input class="input" id="emp_rfc">
                    </div>

                    <div>
                        <label>Teléfono</label>
                        <input class="input" id="emp_tel">
                    </div>

                    <div>
                        <label>Dirección</label>
                        <input class="input" id="emp_dir">
                    </div>

                    <div style="grid-column:1/-1;">
                        <label>Logo</label>
                        <input class="input" type="file" id="emp_logo">
                    </div>

                </div>

            </div>

        </div>

        <!-- =========================
             FOOTER
        ==========================-->
        <div class="modal-foot" style="display:flex;justify-content:space-between;align-items:center;">

            <!-- IZQUIERDA -->
            <div>
                <button id="btnCerrarEmpresa" class="btn-gray" onclick="closeModalEmpresa()">
                    ✖ Cerrar
                </button>

                <button id="btnBackEmpresa" class="btn-gray" style="display:none" onclick="backGridEmpresa()">
                    ← Regresar
                </button>
            </div>

            <!-- FOOTER GRID -->
            <div id="footGridEmpresa" style="display:flex;gap:8px;">

                <button class="btn-danger" onclick="deleteEmpresa()">
                    🗑 Eliminar
                </button>

                <button class="btn-primary" onclick="editEmpresa()">
                    ✏ Editar
                </button>

                <button class="btn-primary" onclick="seleccionarEmpresa()">
                    ✔ Seleccionar
                </button>

            </div>

            <!-- FOOTER FORM -->
            <div id="footFormEmpresa" style="display:none;gap:8px;">

                <button class="btn-success" onclick="saveEmpresa()">
                    💾 Guardar
                </button>

            </div>

        </div>

    </div>

</div>

<!-- =====================================================
     MODAL ESTADOS 
=====================================================-->

<div id="modalEstado" class="modal-overlay">

    <div class="modal-window">

        <div class="modal-head">
            Estado habitación
            <span onclick="cerrarModalEstado()" style="cursor:pointer">✖</span>
        </div>

        <form method="post" action="<?= base_url('guardar-estado') ?>">

            <input type="hidden" name="id" id="estado_id">

            <div class="modal-body">

                <label>Código</label>
                <input class="input" name="codigo" id="estado_codigo" required>

                <label>Nombre</label>
                <input class="input" name="nombre" id="estado_nombre" required>

                <label>Descripción</label>
                <input class="input" name="descripcion" id="estado_descripcion">

                <label>Icono</label>
                <input class="input" name="icono" id="estado_icono">

            </div>

            <div class="modal-foot">
                <button type="button" class="btn-gray" onclick="cerrarModalEstado()">Cancelar</button>
                <button class="btn-primary">Guardar</button>
            </div>

        </form>

    </div>
</div>

<!-- =====================================================
     MODAL EDITAR PISO
=====================================================-->
<div id="modalEditPiso" class="modal-overlay">

    <div class="modal-window" style="width:700px">

        <div class="modal-head">
            Editar piso
            <span onclick="closeEditPiso()" style="cursor:pointer">✖</span>
        </div>

        <form method="post" action="<?= base_url('actualizar-piso') ?>">

            <div class="modal-body">

                <input type="hidden" name="id" id="edit_id">

                <label>Código piso</label>
                <input class="input" name="piso" id="edit_piso" required>

                <label>Nombre</label>
                <input class="input" name="nombre" id="edit_nombre" required>

                <hr>

                <div style="font-weight:700;margin-bottom:8px">
                    Habitaciones asignadas
                </div>
                <div style="margin-bottom:8px">

                    <input id="nuevaHab" placeholder="Número habitación"
                        style="padding:6px;border:1px solid #cbd5e1;border-radius:6px">

                    <button type="button" class="btn-primary" onclick="crearHabAjax()">
                        + Agregar
                    </button>

                </div>

                <div style="max-height:220px;overflow:auto;border:1px solid #e5e7eb;border-radius:8px;padding:8px;">

                    <table class="g-table" id="tablaHabitacionesPiso">

                        <tr>
                            <th>Hab</th>
                            <th>Status</th>
                            <th>Activa</th>
                            <th></th>
                        </tr>

                        <!-- se llena dinámicamente -->

                    </table>

                </div>

            </div>

            <div class="modal-foot">
                <button type="button" class="btn-gray" onclick="closeEditPiso()">Cancelar</button>
                <button class="btn-primary">Guardar cambios</button>
            </div>

        </form>

    </div>
</div>

<!-- =====================================================
     MODAL CREAR HABITACIÓN
=====================================================-->
<div id="modalHab" class="modal-overlay">

    <div class="modal-window">

        <div class="modal-head">
            Crear habitación
            <span onclick="closeHab()" style="cursor:pointer">✖</span>
        </div>

        <form method="post" action="<?= base_url('guardar-habitacion') ?>">

            <div class="modal-body">


                <label>Piso</label>
                <select class="input" name="piso_id" required>
                    <option value="">Seleccionar piso</option>

                    <?php foreach($pisosSelect as $p): ?>
                    <option value="<?= $p['id'] ?>">
                        <?= $p['Piso'] ?> - <?= $p['Nombre'] ?>
                    </option>
                    <?php endforeach; ?>

                </select>

                <label>Tipo Habitación</label>
                <select class="input" name="tipo_id" required>
                    <option value="">Seleccionar tipo</option>

                    <?php foreach($tipHabSelect as $t): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= $t['clave'] ?> - <?= $t['nombre'] ?>
                    </option>
                    <?php endforeach; ?>

                </select>



                <label>Número habitación</label>
                <input class="input" name="numero" placeholder="Ej: 101" required>



            </div>

            <div class="modal-foot">
                <button type="button" class="btn-gray" onclick="closeHab()">Cancelar</button>
                <button class="btn-primary">Guardar</button>
            </div>

        </form>

    </div>
</div>

<!-- =====================================================
     MODAL CREAR PISO
=====================================================-->
<div id="modalPiso" class="modal-overlay">

    <div class="modal-window">

        <div class="modal-head">
            Crear piso
            <span onclick="closePiso()" style="cursor:pointer">✖</span>
        </div>

        <form method="post" action="<?= base_url('guardar-piso') ?>">

            <div class="modal-body">

                <label>Código piso</label>
                <input class="input" name="piso" required>

                <label>Nombre</label>
                <input class="input" name="nombre" required>

            </div>

            <div class="modal-foot">
                <button type="button" class="btn-gray" onclick="closePiso()">Cancelar</button>
                <button class="btn-primary">Guardar</button>
            </div>

        </form>

    </div>
</div>


<!-- ==========================================
     MODAL TIPO HABITACIÓN (ESTILO SISTEMA)
========================================== -->

<div id="modalTipoHab" class="modal-overlay">

    <div class="modal-window" style="width:720px">

        <div class="modal-head">
            🛏 Tipo de habitación
            <span class="modal-close-x" onclick="closeModalTipoHab()">✖</span>
        </div>

        <div class="modal-body">

            <div class="form-section-title">
                📋 Datos generales
            </div>

            <input type="hidden" id="th_id">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:12px 18px;
                margin-top:10px;
            ">

                <div>
                    <label>Nombre</label>
                    <input id="th_nombre" class="input">
                </div>

                <div>
                    <label>Clave</label>
                    <input id="th_clave" class="input">
                </div>

                <div>
                    <label>Precio base</label>
                    <input id="th_precio" type="number" class="input">
                </div>

                <div>
                    <label>Precio persona extra</label>
                    <input id="th_extra" type="number" class="input">
                </div>

                <div>
                    <label>Personas máximo</label>
                    <input id="th_personas" type="number" class="input">
                </div>

                <div>
                    <label>Orden visual</label>
                    <input id="th_orden" type="number" class="input">
                </div>

                <div>
                    <label>Color</label>
                    <input id="th_color" class="input">
                </div>

                <div>
                    <label>Icono</label>
                    <input id="th_icono" class="input">
                </div>

            </div>

        </div>

        <div class="modal-foot">

            <button id="btnEliminarTipoHab" class="btn-danger" style="float:left;display:none"
                onclick="deleteTipoHab()">
                🗑 Eliminar
            </button>

            <button class="btn-gray" onclick="closeModalTipoHab()">
                Cancelar
            </button>

            <button class="btn-primary" onclick="saveTipoHab()">
                💾 Guardar
            </button>

        </div>

    </div>
</div>


<script>
window.base_url = "<?= site_url('/') ?>";
</script>


<script>
/* navegación tabs */
function tabCfg(n, el) {
    for (let i = 1; i <= 6; i++) {
        let tab = document.getElementById('t' + i)
        if (tab) tab.style.display = 'none'
    }
    document.getElementById('t' + n).style.display = 'block'

    document.querySelectorAll('.rtab').forEach(t => t.classList.remove('active'))
    el.classList.add('active')
}

/* modal */
function abrirModalPiso() {
    document.getElementById('modalPiso').style.display = 'flex'
}

function closePiso() {
    document.getElementById('modalPiso').style.display = 'none'
}

window.addEventListener('click', function(e) {
    let m = document.getElementById('modalPiso')
    if (e.target === m) {
        m.style.display = 'none'
    }
})
</script>

<script>
/* abrir modal habitación */
function abrirModalHab() {
    document.getElementById('modalHab').style.display = 'flex'
}

/* cerrar modal habitación */
function closeHab() {
    document.getElementById('modalHab').style.display = 'none'
}

window.addEventListener('click', function(e) {
    let m = document.getElementById('modalHab')
    if (e.target === m) {
        m.style.display = 'none'
    }
})
</script>

<script>
function editarPiso(id, piso, nombre) {

    // llenar datos piso
    document.getElementById('edit_id').value = id
    document.getElementById('edit_piso').value = piso
    document.getElementById('edit_nombre').value = nombre

    // referencia tabla
    let tabla = document.getElementById('tablaHabitacionesPiso')

    // loader visual PMS
    tabla.innerHTML = `
    <tr>
        <th>Hab</th>
        <th>Status</th>
        <th>Activa</th>
        <th></th>
    </tr>
    <tr>
        <td colspan="4" style="text-align:center;padding:15px">
        Cargando habitaciones...
        </td>
    </tr>
    `

    // consulta AJAX real
    fetch('<?= base_url('ajax/habitaciones') ?>/' + id)
        .then(r => r.json())
        .then(data => {

            let html = `
        <tr>
            <th>Hab</th>
            <th>Status</th>
            <th>Activa</th>
            <th></th>
        </tr>
        `

            if (data.length === 0) {
                html += `
            <tr>
                <td colspan="4" style="text-align:center;padding:10px">
                Sin habitaciones registradas
                </td>
            </tr>`
            }

            data.forEach(h => {

                html += `
            <tr>
                <td>${h.numero}</td>
                <td>${h.status}</td>

                <td>
                    <input type="checkbox"
                    ${h.activa == 1 ? 'checked' : ''}
                    onchange="toggleHab(${h.id}, this)">
                </td>

                <td>
                    <button class="btn-gray"
                    onclick="eliminarHab(${h.id})">
                    Eliminar
                    </button>
                </td>
            </tr>
            `
            })

            tabla.innerHTML = html
        })

    // abrir modal
    document.getElementById('modalEditPiso').style.display = 'flex'
}

function closeEditPiso() {
    document.getElementById('modalEditPiso').style.display = 'none'
}
</script>

<script>
function editarPisoAjax(id, piso, nombre) {

    document.getElementById('edit_id').value = id
    document.getElementById('edit_piso').value = piso
    document.getElementById('edit_nombre').value = nombre

    fetch('<?= base_url('ajax/habitaciones') ?>/' + id)
        .then(r => r.json())
        .then(data => {

            let html = `
<tr>
<th>Hab</th>
<th>Status</th>
<th>Activa</th>
<th></th>
</tr>
`

            data.forEach(h => {

                html += `
<tr>
<td>${h.numero}</td>
<td>${h.status}</td>

<td>
<input type="checkbox"
${h.activa==1?'checked':''}
onchange="toggleHab(${h.id},this)">
</td>

<td>
<button class="btn-gray"
onclick="eliminarHab(${h.id})">
Eliminar
</button>
</td>
</tr>
`
            })

            document.getElementById('tablaHabitacionesPiso').innerHTML = html

        })

    document.getElementById('modalEditPiso').style.display = 'flex'
}


/* activar desactivar */
function toggleHab(id, el) {

    let activa = el.checked ? 1 : 0

    fetch('<?= base_url('ajax/habitacion/estado') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${id}&activa=${activa}`
    })

}


/* eliminar */
function eliminarHab(id) {

    if (!confirm('Eliminar habitación?')) return

    fetch('<?= base_url('ajax/habitacion/eliminar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}`
        })
        .then(() => {
            document.querySelector(`button[onclick="eliminarHab(${id})"]`)
                .parentElement.parentElement.remove()
        })

}
</script>

<script>
function crearHabAjax() {

    let numero = document.getElementById('nuevaHab').value
    let piso = document.getElementById('edit_id').value

    if (numero == '') {
        alert('Número requerido')
        return
    }

    fetch('<?= base_url('ajax/habitacion/crear') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `numero=${numero}&piso_id=${piso}`
        })
        .then(r => r.json())
        .then(h => {

            let row = `
<tr>
<td>${h.numero}</td>
<td>${h.status}</td>

<td>
<input type="checkbox checked
onchange="toggleHab(${h.id},this)">
</td>

<td>
<button class="btn-gray"
onclick="eliminarHab(${h.id})">
Eliminar
</button>
</td>
</tr>
`

            document.getElementById('tablaHabitacionesPiso')
                .insertAdjacentHTML('beforeend', row)

            document.getElementById('nuevaHab').value = ''

        })

}
</script>



<!-- variable global del PMS -->
<script>
window.base_url = "<?= base_url() ?>";
</script>

<script src="<?= base_url('assets/js/sistema.js') ?>"></script>
<script src="<?= base_url('assets/js/sistema_config.js') ?>"></script>
<script src="<?= base_url('assets/js/sistema_usuario.js') ?>"></script>
<script src="<?= base_url('assets/js/sistema_turnos.js') ?>"></script>
<script src="<?= base_url('assets/js/sistema_tipohabit.js') ?>"></script>
<script src="<?= base_url('assets/js/sistema_catalogos.js') ?>"></script>
<script src="<?= base_url('assets/js/config_perifericos.js') ?>"></script>

<?= view('layout/footer') ?>