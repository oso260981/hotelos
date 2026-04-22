<!-- <link rel="stylesheet" href="<?= base_url('assets/css/modal_adm.css') ?>"> -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<!-- 
<style>
/* =========================================
           ESTILOS BASE Y TIPOGRAFÍA
           ========================================= */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');



.dashboard-bg {
    position: fixed;
    inset: 0;
    background: radial-gradient(circle at top left, #1e293b, #0f172a);
    z-index: -1;
}

/* MASTER LAYOUT */
.admin-modal {
    width: 98vw;
    height: 95vh;
    max-width: 1500px;
    background: #ffffff;
    border-radius: 28px;
    display: grid;
    grid-template-columns: 280px 1fr 380px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    color: #0f172a;
}

/* SIDEBAR IZQUIERDO */
.admin-sidebar {
    background-color: #f8fafc;
    border-right: 1px solid #e2e8f0;
    padding: 32px 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.nav-tab {
    padding: 14px 18px;
    border-radius: 14px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
    cursor: pointer;
}

.nav-tab.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* CONTENIDO CENTRAL */
.admin-content {
    padding: 32px;
    overflow-y: auto;
    background: #ffffff;
}

/* SIDEBAR DERECHO */
.admin-audit {
    background-color: #0f172a;
    color: white;
    padding: 24px;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #1e293b;
}

/* COMPONENTES REUTILIZABLES */
.pms-label {
    font-size: 9px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 5px;
    display: block;
    letter-spacing: 0.05em;
}

.pms-badge {
    font-size: 9px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 6px;
    text-transform: uppercase;
}

.badge-success {
    background: #ecfdf5;
    color: #059669;
}

.data-card {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    padding: 16px;
    transition: all 0.2s;
}

.pms-input {
    width: 100%;
    padding: 10px 14px;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
}

/* MODALES SECUNDARIOS */
.modal-overlay-sub {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease;
}

.modal-overlay-sub.active {
    visibility: visible;
    opacity: 1;
}

.modal-container-edit {
    background: white;
    width: 90%;
    max-width: 1000px;
    height: 90vh;
    border-radius: 32px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5);
    transform: scale(0.9);
    transition: 0.3s;
}

.modal-overlay-sub.active .modal-container-edit {
    transform: scale(1);
}

.custom-scroll::-webkit-scrollbar {
    width: 4px;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.pax-photo {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid white;
}

/* ESTILOS CÁMARA */
#camera-container {
    position: relative;
    width: 100%;
    aspect-ratio: 1/1;
    background: #000;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

#camera-stream {
    width: 100%;
    height: 100%;
    object-fit: cover;
}







:root {
    --pms-primary: #4f46e5;
    --pms-secondary: #0f172a;
    --pms-accent: #10b981;
    --pms-bg: #f8fafc;
}



/* Estructura Principal Pro */
.glass-container {
    width: 99vw;
    height: 97vh;
    max-width: 1700px;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 32px;
    display: grid;
    grid-template-columns: 240px 1fr 360px;
    overflow: hidden;
    box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4);
}

/* Sidebar Navegación */
.sidebar-nav {
    background: #ffffff;
    border-right: 1px solid #f1f5f9;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
}

.nav-link {
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
    cursor: pointer;
    margin-bottom: 4px;
}

.nav-link.active {
    background: var(--pms-primary);
    color: #ffffff;
    box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.3);
}

.nav-link i {
    font-size: 14px;
    width: 20px;
    text-align: center;
}

/* Área de Contenido Principal */
.main-stage {
    padding: 24px 32px;
    overflow-y: auto;
    background: #ffffff;
}

/* Sidebar de Auditoría (Derecha) */
.audit-panel {
    background: var(--pms-secondary);
    color: #ffffff;
    padding: 32px 24px;
    display: flex;
    flex-direction: column;
    border-left: 1px solid rgba(255, 255, 255, 0.05);
}

/* Componentes de PAX */
.pax-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.2s;
}

.pax-card:hover {
    border-color: var(--pms-primary);
    background: white;
}

.avatar-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 10px;
    margin-right: -10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.bg-adult {
    background: #e0e7ff;
    color: #4338ca;
}

.bg-child {
    background: #dcfce7;
    color: #166534;
}

/* TERMINAL POS */
.pos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(115px, 1fr));
    gap: 10px;
}

.pos-item-card {
    background: #ffffff;
    border: 1.5px solid #f1f5f9;
    border-radius: 18px;
    padding: 12px 10px;
    text-align: left;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 85px;
}

.pos-item-card:hover {
    border-color: var(--pms-primary);
    background: #f5f3ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.08);
}

.pos-item-card i {
    font-size: 12px;
    color: #4f46e5;
    background: #f1f5f9;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin-bottom: 8px;
}

.pos-item-card:hover i {
    background: var(--pms-primary);
    color: white;
}

.pos-item-card .label {
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    color: #1e293b;
    line-height: 1.1;
}

.pos-item-card .price {
    font-size: 12px;
    font-weight: 800;
    color: #6366f1;
    margin-top: 2px;
}

/* Barras de Registro */
.quick-charge-bar {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.pms-input {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 600;
    outline: none;
    transition: all 0.2s;
}

.pms-input:focus {
    border-color: var(--pms-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.pms-label {
    font-size: 9px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 4px;
    display: block;
    letter-spacing: 0.5px;
}

/* Historial Estilo Timeline / Tabla */
.history-row {
    display: grid;
    grid-template-columns: 80px 40px 1fr 80px 100px 50px;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f8fafc;
    transition: all 0.2s;
}

.history-row:hover {
    background: #f8fafc;
}

.history-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.custom-scroll::-webkit-scrollbar {
    width: 4px;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}

.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Buscador POS */
.pos-search-container {
    position: relative;
    margin-bottom: 12px;
}

.pos-search-container i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 12px;
}

.pos-search-input {
    width: 100%;
    padding: 10px 16px 10px 38px;
    font-size: 12px;
}

/* PAGOS STYLES */
.payment-card {
    border: 1.5px solid #f1f5f9;
    border-radius: 20px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.payment-card:hover {
    border-color: #10b981;
    background: #f0fdf4;
    transform: translateY(-2px);
}

.payment-card.active {
    border-color: #10b981;
    background: #10b981;
    color: white;
}

.payment-card i {
    font-size: 20px;
    margin-bottom: 8px;
    display: block;
}

.payment-card span {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    font-size: 9px;
    color: #94a3b8;
    font-weight: 800;
    text-transform: uppercase;
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
}

td {
    padding: 12px 16px;
    font-size: 12px;
    border-bottom: 1px solid #f8fafc;
}

/* ROOM SERVICE MENU */
.menu-item-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    padding: 16px;
    display: flex;
    gap: 16px;
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}

.menu-item-card:hover {
    border-color: var(--pms-primary);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);
}

.menu-item-img {
    width: 80px;
    height: 80px;
    border-radius: 18px;
    object-fit: cover;
    background: #f8fafc;
}

.category-chip {
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s;
    background: #f1f5f9;
    color: #64748b;
}

.category-chip.active {
    background: var(--pms-primary);
    color: white;
}

/* ROOM MOVE STYLES */
.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
}

.room-card {
    background: #ffffff;
    border: 2px solid #f1f5f9;
    border-radius: 24px;
    padding: 20px;
    transition: all 0.2s;
    cursor: pointer;
    text-align: center;
}

.room-card:hover {
    border-color: var(--pms-primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.room-card.selected {
    border-color: var(--pms-primary);
    background: #f5f3ff;
}

.room-card .room-number {
    font-size: 24px;
    font-weight: 900;
    color: #1e293b;
}

.room-card .room-type {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    margin-top: 4px;
}

.room-card .room-price {
    font-size: 14px;
    font-weight: 800;
    color: #4f46e5;
    margin-top: 8px;
}

.room-card .room-status {
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-block;
    margin-top: 12px;
}

.status-ready {
    background: #dcfce7;
    color: #166534;
}

/* Modales */
.pms-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
}

.pms-modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.pms-modal-content {
    background: #ffffff;
    width: 90%;
    max-width: 900px;
    border-radius: 32px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
</style>
 -->


<style>
#pos-servicios {
    flex-wrap: nowrap;
}

/* SELLO DE PAGADO */
.paid-stamp {
    position: absolute;
    top: 220px;
    right: 80px;
    border: 5px solid #10b981;
    color: #10b981;
    font-size: 38px;
    font-weight: 900;
    padding: 12px 36px;
    text-transform: uppercase;
    transform: rotate(-15deg);
    border-radius: 12px;
    opacity: 0.3;
    z-index: 50;
    display: none;
    letter-spacing: 4px;
}

/* ESTILOS DE IMPRESIÓN OPTIMIZADOS */
@media print {
    /* 1. Ocultar absolutamente todo lo que tenga la clase no-print */
    .no-print, 
    .admin-sidebar, 
    .audit-panel, 
    nav, 
    button {
        display: none !important;
    }

@page {
          size: A4;
                margin: 1cm;
}

body { 
                background: white !important; 
                height: auto !important;
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .glass-container { 
                display: block !important; 
                width: 100% !important; 
                height: auto !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                background: white !important;
            }
            .no-print, .sidebar-nav, .audit-panel, .pms-modal-overlay, button, .pms-header-nav { 
                display: none !important; 
            }

.main-stage { 
                padding: 0 !important;
                overflow: visible !important;
                width: 100% !important;
                display: block !important;
            }
           /*  #sec-bitacora {
                display: block !important;
                visibility: visible !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            } */

            #sec-bitacora {
        display: block !important;
         visibility: visible !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }

            #folio-printable-area {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                min-height: 100vh !important;
            }
            
            .paid-stamp {
                display: block !important;
                opacity: 0.2 !important;
            }

             /* Estructura de Factura */
            .folio-header {
                border-bottom: 2px solid #000 !important;
                margin-bottom: 20px !important;
                padding-bottom: 20px !important;
            }

            .folio-body {
                flex-grow: 1 !important;
            }

            .folio-footer {
                page-break-inside: avoid !important;
                margin-top: auto !important;
                padding-top: 20px !important;
                border-top: 1px solid #eee !important;
            }

            table { 
                width: 100% !important;
                border: 1px solid #eee !important;
            }
            th {
                background-color: #f9fafb !important;
                border-bottom: 1px solid #000 !important;
                color: #000 !important;
            }
            td {
                border-bottom: 1px solid #eee !important;
                color: #000 !important;
            }
            
            p, h1, h2, h3, h4, span {
                color: #000 !important;
            }
            
            .bg-slate-900 {
                background-color: transparent !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }
            .text-white { color: #000 !important; }
        


    /* 2. Asegurar que el contenedor principal no tenga márgenes o scrolls raros */
   /*  body, html {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    } */

    /* 3. Forzar que la sección de bitácora se muestre, aunque esté 'hidden' en la web */
    

    /* 4. Eliminar sombras y bordes pesados para ahorrar tinta y verse profesional */

    /* 5. Ajuste de escala (opcional) */
   /*  @page {
        size: auto;
        margin: 10mm;
    } */
}
</style>



<div class="dashboard-bg"></div>



<!-- EL MODAL PRINCIPAL (MASTER CONTAINER) -->
<div id="admin-modal-container">
    <div class="admin-modal">

        <!-- SIDEBAR DE NAVEGACIÓN -->
        <aside class="admin-sidebar no-print">
            <div class="mb-8 px-2">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-id-card-alt"></i>
                    </div>
                    <div>
                        <p class="pms-label mb-0">Habitación</p>
                        <h2 id="admin_habitacion" class="font-black text-xl tracking-tighter">—</h2>
                    </div>
                </div>
                <span class="pms-badge badge-success"><i class="fas fa-check-circle mr-1"></i> Check-in Activo</span>
            </div>

            <nav class="flex-1 overflow-y-auto custom-scroll pr-1">
                <div class="nav-tab active" id="tab-resumen" onclick="switchTab('resumen', this)"><i
                        class="fas fa-th-large"></i> Resumen de Folio</div>
                <div class="nav-tab" id="tab-cargos" onclick="switchTab('cargos', this)"><i
                        class="fas fa-plus-circle"></i> Cargos & Consumos</div>
                <div class="nav-tab" id="tab-pagos" onclick="switchTab('pagos')"><i class="fas fa-wallet"></i> Pagos
                    & Abonos</div>
                <div class="nav-tab" id="tab-room_move" onclick="switchTab('room_move', this)"><i
                        class="fas fa-exchange-alt"></i> Cambio de Unidad</div>
                <div class="nav-tab" id="tab-room_service" onclick="switchTab('room_service', this)"><i
                        class="fas fa-utensils"></i> Room Service</div>
                <div class="nav-tab" id="tab-bitacora" onclick="switchTab('bitacora', this)"><i
                        class="fas fa-history"></i>Generar Folio</div>
            </nav>

            <!-- Status de sincronización y botón de cierre de folio -->
            <div class="mt-auto space-y-2">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-4">
                    <p class="pms-label">Estado Sistema</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <span class="text-[10px] font-bold text-slate-600 uppercase">Sincronizado</span>
                    </div>
                </div>
                <button id="btn-checkout" onclick="handleCheckOutFlow()"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all shadow-xl">
                    Finalizar Check-Out
                </button>

                <button id="btn-cancelar" onclick="openCancelacionModal()"
                    class="w-full py-4 bg-red-50 text-red-500 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-ban mr-2"></i> Cancelar Estadía
                </button>

                <button onclick="closeModalAdmin()"
                    class="w-full py-3 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-times-circle mr-2"></i> Regresar a Reservaciones
                </button>
            </div>

            <!--  <div class="mt-auto space-y-3 pt-4 border-t">
                <button
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">
                    <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Procesar Check-out
                </button>
                <button onclick="closeModalAdmin()"
                    class="w-full py-4 bg-red-50 text-red-500 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-ban mr-2"></i> Cancelar Estadía
                </button>
            </div> -->
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="admin-content custom-scroll">

            <!-- SECCIÓN: RESUMEN -->
            <div id="sec-resumen" class="space-y-8 fade-in no-print">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 id="admin_nombre" class="text-3xl font-black tracking-tighter">
                            —
                        </h1>

                        <p class="text-slate-400 font-medium">
                            Folio:
                            <span id="admin_folio" class="text-indigo-600 font-bold">—</span>
                            |
                            Registro:
                            <span id="admin_fecha_registro">—</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleEditModal(true)"
                            class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-indigo-100 transition-all">Editar
                            Perfil</button>
                        <button
                            class="bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-slate-200 transition-all"><i
                                class="fas fa-print"></i></button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="pax-info-card" onclick="toggleModal('modal-modify-stay', true)">
                        <p class="pms-label">Estancia Actual</p>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="text-xs font-bold text-slate-400">Entrada</p>
                                <p id="admin_fecha_entrada" class="font-black text-sm">—</p>
                            </div>

                            <div class="text-indigo-200">
                                <i class="fas fa-arrow-right"></i>
                            </div>

                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-400">Salida</p>
                                <p id="admin_fecha_salida" class="font-black text-sm">—</p>
                            </div>
                        </div>
                    </div>


                    <div class="pax-info-card" onclick="toggleCompanionsList(true)">

                        <p class="pms-label">Información de acompañantes</p>
                        <div class="flex items-center gap-4 mt-2">

                            <div class="flex items-center gap-4 mt-2">

                                <div id="pax-badges" class="flex -space-x-2"></div>

                                <p id="pax-summary" class="text-xs font-bold"></p>

                            </div>



                            <!--  <div class="flex -space-x-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 font-black text-[10px]">
                                    AD</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-emerald-600 font-black text-[10px]">
                                    NI</div>
                            </div>
                            <p class="text-xs font-bold">2 Adultos, 1 Niño</p> -->
                        </div>

                    </div>

<!-- 
                    <div class="pax-info-card" onclick="toggleModal('modal-vehicles', true)">
                        <p class="pms-label">Vehículo & Acceso</p>
                        <div class="mt-2 flex items-center gap-3">
                            <i class="fas fa-car text-slate-300 text-lg"></i>
                            <div>
                                <p class="text-xs font-black">PLACA: ABC-123-X</p>
                                <p class="text-[9px] text-slate-400 uppercase font-bold">Lote Estacionamiento: B-04</p>
                            </div>
                        </div>
                    </div> -->

<div id="card-vehiculos" class="pax-info-card" onclick="toggleModal('modal-vehicles', true)">
</div>

                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-black text-lg uppercase tracking-tight">Últimos Consumos Registrados</h3>
                        <button onclick="switchSection('cargos')"
                            class="text-indigo-600 font-black text-[10px] uppercase hover:underline">+ Añadir Cargo
                            Manual</button>
                    </div>
                    <div class="border rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3">Fecha</th>
                                    <th class="py-3">Concepto</th>
                                    <th class="py-3">Departamento</th>
                                    <th>Estado</th>
                                    <th class="text-right px-6 py-3">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="table-resumen-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: ALTA DE CARGOS -->
            <div id="sec-cargos" class="hidden space-y-6 fade-in no-print">

                <!-- Terminal Header -->
                <div class="flex justify-between items-end border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-slate-900">Terminal de Cargos</h2>
                        <p class="text-slate-400 font-medium text-xs mt-1">Gestión rápida de consumos para la unidad</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="category-tab active">General</div>
                        <div class="category-tab">Wellness</div>
                    </div>
                </div>

                <!-- Registro Manual Horizontal -->
                <div class="quick-charge-bar shadow-sm">
                    <div class="flex-1">
                        <label class="pms-label">Descripción de Cargo Manual</label>
                        <input type="text" id="manual-concept" class="pms-input w-full"
                            placeholder="Ej: Late Check-out 2h extra">
                    </div>
                    <div class="w-28">
                        <label class="pms-label">Monto ($)</label>
                        <input type="number" id="manual-price" class="pms-input w-full" placeholder="0.00">
                    </div>
                    <div class="w-16">
                        <label class="pms-label">Cant.</label>
                        <input type="number" id="manual-qty" class="pms-input w-full" value="1">
                    </div>
                    <button onclick="addManualCharge()"
                        class="h-[38px] mt-4 px-6 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-indigo-700 transition-all">
                        Aplicar
                    </button>
                </div>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-8 space-y-4">
                        <div class="flex items-center justify-between px-1">
                            <h3 class="pms-label text-slate-900 mb-0">Artículos de Venta Rápida</h3>
                            <div class="w-48 pos-search-container">
                                <i class="fas fa-search"></i>
                                <!--  <input type="text" id="pos-search" class="pms-input pos-search-input"
                                    placeholder="Buscar..." onkeyup="filterPOS()"> -->
                                <input type="text" placeholder="Buscar..." onkeyup="buscarProductosPOS(this.value)"
                                    class="pms-input pos-search-input">

                            </div>
                        </div>
                        <div class="pos-grid" id="pos-servicios">






                            <!-- <div class="pos-item-card" onclick="quickAdd('Botella Agua 1L', 'MINI', 45)">
                                <i class="fas fa-tint"></i>
                                <div><span class="label">Agua 1L</span><span class="price">$45.00</span></div>
                            </div>
                            <div class="pos-item-card" onclick="quickAdd('Servicio Lavandería', 'AMA', 280)">
                                <i class="fas fa-tshirt"></i>
                                <div><span class="label">Lavandería</span><span class="price">$280.00</span></div>
                            </div>
                            <div class="pos-item-card" onclick="quickAdd('Estacionamiento', 'REC', 150)">
                                <i class="fas fa-car"></i>
                                <div><span class="label">Parking</span><span class="price">$150.00</span></div>
                            </div>
                            <div class="pos-item-card" onclick="quickAdd('Masaje 50 min', 'SPA', 1200)">
                                <i class="fas fa-spa"></i>
                                <div><span class="label">Spa Masaje</span><span class="price">$1,200.00</span></div>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-span-4 space-y-4">
                        <div class="bg-slate-900 rounded-3xl p-5 text-white shadow-xl">
                            <p class="pms-label text-slate-500 mb-4">Registro Sesión</p>
                            <div class="flex items-center gap-3 border-b border-slate-800 pb-4 mb-4">
                                <div
                                    class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center text-white">
                                    <i class="fas fa-receipt text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[8px] font-black text-indigo-400 uppercase">Último Movimiento</p>
                                    <h4 class="text-[10px] font-bold truncate" id="last-charge-label">Sin movimientos
                                    </h4>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-white" id="last-charge-price">$0</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="pms-label text-slate-500 mb-0">Total Hoy</p>
                                    <h4 class="text-2xl font-black text-white" id="terminal-session-total">$0</h4>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-[7px] font-black uppercase text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded">Activo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t">
                    <h3 class="font-black text-base text-slate-900 uppercase">Historial de Folio</h3>
                    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
                        <div
                            class="bg-slate-50 grid grid-cols-[80px_40px_1fr_80px_100px_50px] items-center px-4 py-2 border-b">
                            <span class="pms-label mb-0">Hora</span>
                            <span></span>
                            <span class="pms-label mb-0">Concepto</span>
                            <span class="pms-label mb-0">Cant.</span>
                            <span class="pms-label mb-0 text-right">Total</span>
                            <span></span>
                        </div>
                        <div id="timeline-charges-container" class="max-h-[250px] overflow-y-auto custom-scroll"></div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: PAGOS & ABONOS -->
            <div id="sec-pagos" class="hidden space-y-6 fade-in no-print">
                <div class="flex justify-between items-end border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-slate-900">Pagos & Abonos</h2>
                        <p class="text-slate-400 font-medium text-xs mt-1">Registra cobros y pagos adelantados al folio
                        </p>
                    </div>
                    <div class="bg-emerald-50 px-4 py-2 rounded-2xl flex items-center gap-2 border border-emerald-100">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                        <p class="text-[9px] font-black text-emerald-900 uppercase">Caja Abierta</p>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-6">



                    <div class="col-span-12 space-y-5">

                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">

                            <!-- 🔥 FILA 1 -->
                            <div class="grid grid-cols-12 gap-4 items-end">

                                <!-- MÉTODOS (IZQUIERDA) -->
                                <div class="col-span-12 md:col-span-7 grid grid-cols-3 gap-3">

                                    <button
                                        class="payment-card active h-[60px] rounded-2xl bg-emerald-500 text-white flex flex-col justify-center items-center text-xs font-bold shadow-sm"
                                        onclick="handlePaymentMethod('Efectivo', this)">
                                        <i class="fas fa-money-bill-wave text-lg mb-1"></i>
                                        EFECTIVO
                                    </button>

                                    <button
                                        class="payment-card h-[60px] rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-center items-center text-xs font-bold"
                                        onclick="handlePaymentMethod('Tarjeta', this)">
                                        <i class="fas fa-credit-card text-lg mb-1"></i>
                                        TARJETA
                                    </button>

                                    <button
                                        class="payment-card h-[60px] rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-center items-center text-xs font-bold"
                                        onclick="handlePaymentMethod('Transferencia', this)">
                                        <i class="fas fa-university text-lg mb-1"></i>
                                        TRANSFERENCIA
                                    </button>

                                </div>

                                <!-- IMPORTE (DERECHA) -->
                                <div class="col-span-12 md:col-span-5">
                                    <label class="pms-label">Importe ($)</label>
                                    <input type="number" id="payment-amount"
                                        class="w-full h-[60px] text-3xl font-black text-right px-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-400 outline-none"
                                        placeholder="0.00">
                                </div>

                            </div>

                            <!-- 🔥 FILA 2 -->
                            <div class="grid grid-cols-12 gap-4 items-end">

                                <!-- FORMA DE PAGO -->
                                <div class="col-span-12 md:col-span-5">
                                    <label class="pms-label">Forma de Pago</label>
                                    <select id="main_formaPagoadmin" class="pms-input w-full">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>

                                <!-- CONCEPTO -->
                                <div class="col-span-12 md:col-span-5">
                                    <label class="pms-label">Concepto</label>
                                    <select id="payment-concept" class="pms-input w-full">
                                        <option value="">Seleccione</option>
                                        <option>Hospedaje</option>
                                        <option>Anticipo</option>
                                        <option>Pago parcial</option>
                                        <option>Consumo</option>
                                    </select>
                                </div>



                                <!-- BOTÓN -->
                                <div class="col-span-12 md:col-span-2 flex justify-end">
                                    <button onclick="processPayment()"
                                        class="w-full h-[48px] bg-emerald-600 text-white rounded-xl font-bold text-xs uppercase shadow-md hover:bg-emerald-700 transition-all">
                                        COBRAR
                                    </button>
                                </div>

                            </div>

                            <!-- DETALLES DINÁMICOS -->
                            <div id="payment_details_containeradmin" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            </div>


                            <div id="menu-facturacion" class="menu-item" onclick="openFacturaModal()">
                                <i class="fas fa-file-invoice-dollar"></i>
                                FACTURACIÓN
                            </div>




                        </div>

                    </div>
                </div>
                <div class="space-y-4 pt-4">
                    <div class="flex justify-between items-center px-1">
                        <h3 class="font-black text-base text-slate-900 uppercase">Historial de Transacciones</h3>
                        <div
                            class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                            Total Recaudado: <span id="total-payments-label">$0.00</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3">Fecha y Hora</th>
                                    <th class="py-3">Método</th>
                                    <th class="py-3">Referencia</th>
                                    <th class="text-right py-3">Monto</th>
                                    <th class="text-center px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="payment-history-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- SECCIÓN: ROOM SERVICE -->
            <div id="sec-room_service" class="hidden space-y-6 fade-in no-print">
                <div class="flex justify-between items-end border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-slate-900">Room Service</h2>
                        <p class="text-slate-400 font-medium text-xs mt-1">Gestión de pedidos gastronómicos a la unidad
                        </p>
                    </div>
                    <div class="flex gap-2" id="room_service_categories">
                        <div class="category-chip active" onclick="filterMenu('TODOS', this)">Todos</div>
                        <div class="category-chip" onclick="filterMenu('DESAYUNO', this)">Desayunos</div>
                        <div class="category-chip" onclick="filterMenu('COMIDA', this)">Comidas</div>
                        <div class="category-chip" onclick="filterMenu('BEBIDAS', this)">Bebidas</div>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-8">
                    <!-- Grid de Menú -->
                    <div class="col-span-8 space-y-4">
                        <div class="pos-search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" id="menu-search" class="pms-input pos-search-input"
                                placeholder="Buscar platillo o bebida..." onkeyup="searchMenu()">
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="menu-container">
                            <!-- Inyectado por JS -->
                        </div>
                    </div>

                    <!-- Carrito de Pedido -->
                    <div class="col-span-4">
                        <div class="bg-white border-2 border-slate-50 rounded-[32px] p-6 sticky top-0 shadow-sm">
                            <h3 class="pms-label mb-6 text-slate-900">Comanda en Preparación</h3>
                            <div id="cart-items"
                                class="space-y-4 mb-8 min-h-[100px] max-h-[300px] overflow-y-auto custom-scroll">
                                <p class="text-center text-slate-300 italic text-xs py-10">Agrega artículos para iniciar
                                    pedido</p>
                            </div>

                            <div class="space-y-2 border-t pt-4">
                                <div class="flex justify-between items-center text-xs font-bold text-slate-400">
                                    <span>Subtotal</span>
                                    <span id="cart-subtotal">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-bold text-slate-400">
                                    <span>Cargo Servicio (10%)</span>
                                    <span id="cart-service">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-sm font-black text-slate-900">Total Comanda</span>
                                    <span class="text-xl font-black text-indigo-600" id="cart-total">$0.00</span>
                                </div>
                            </div>

                            <button onclick="placeRoomServiceOrder()" id="btn-order" disabled
                                class="w-full mt-6 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest opacity-30 cursor-not-allowed shadow-lg shadow-indigo-100 transition-all">
                                Cargar al Folio
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- SECCIÓN: CAMBIO DE HABITACIÓN -->
            <div id="sec-room_move" class="hidden space-y-6 fade-in no-print">

                <div class="flex justify-between items-end border-b pb-4">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-slate-900">Cambio de Unidad</h2>
                        <p class="text-slate-400 font-medium text-xs mt-1">
                            Transfiere los cargos a una nueva habitación disponible
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-8">

                    <!-- IZQUIERDA -->

                    <div class="col-span-12 lg:col-span-12 space-y-12">

                        <!-- CONTROLES + HABITACIONES -->
                        <div id="grid-controls" class="space-y-4">

                            <!-- FILTROS COMPACTOS EN UNA SOLA FILA -->
                            <div
                                class="flex flex-wrap items-center gap-6 bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">

                                <!-- NIVEL -->
                                <div class="flex items-center gap-3">
                                    <label class="pms-label text-indigo-600 mb-0 whitespace-nowrap">
                                        Nivel / Piso
                                    </label>
                                    <div id="floorContaineradmin" class="flex gap-2"></div>
                                </div>

                                <!-- CATEGORIA -->
                                <div class="flex items-center gap-3">
                                    <label class="pms-label text-indigo-600 mb-0 whitespace-nowrap">
                                        Categoría de Unidad
                                    </label>
                                    <div id="typeContaineradmin" class="flex gap-2"></div>
                                </div>

                            </div>



                        </div>

                    </div>

                    <div class="col-span-12 lg:col-span-8 space-y-6">



                        <!-- HABITACIONES -->
                        <div id="roomContaineradmin" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        </div>




                    </div>

                    <!-- DERECHA -->
                    <div class="col-span-12 lg:col-span-4">
                        <div
                            class="bg-indigo-50 border-2 border-indigo-100 rounded-[32px] p-8 text-center sticky top-24">

                            <div
                                class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-indigo-600 mx-auto mb-6 shadow-sm">
                                <i class="fas fa-exchange-alt text-2xl"></i>
                            </div>

                            <h3 class="text-xl font-black text-slate-900 mb-2">
                                Resumen de Cambio
                            </h3>

                            <p class="text-xs font-bold text-slate-500 mb-8">
                                Todos los cargos y pagos serán migrados íntegramente a la nueva unidad seleccionada.
                            </p>

                            <p class="pms-label mb-4">Motivo del Cambio</p>
                            <select id="move-motivo" class="pms-input mb-4">
                                <option value="">Seleccione motivo</option>
                                <option value="Preferencia del Huésped">Preferencia del Huésped</option>
                                <option value="Problema Técnico / Mantenimiento">Problema Técnico / Mantenimiento
                                </option>
                                <option value="Upgrade de Cortesía">Upgrade de Cortesía</option>
                            </select>

                            <div class="space-y-4 text-left bg-white/50 p-6 rounded-2xl border border-white mb-8">



                                <div class="flex justify-between items-center border-b border-indigo-100 pb-2">
                                    <span class="pms-label mb-0">Origen</span>
                                    <span class="font-black text-slate-900" id="move-origin-room">#204</span>
                                </div>

                                <div class="flex justify-between items-center pt-2">
                                    <span class="pms-label mb-0">Destino</span>
                                    <span class="font-black text-indigo-600" id="move-dest-room">---</span>
                                </div>

                            </div>

                            <button onclick="executeRoomMove()" id="btn-confirm-move" disabled
                                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest opacity-30 cursor-not-allowed shadow-lg transition-all">
                                Confirmar Cambio
                            </button>

                        </div>
                    </div>

                </div>
            </div>



            <!-- VISTA: REPORTE DE FOLIO (OPTIMIZADO PARA PDF) -->
            <div id="sec-bitacora" class="hidden space-y-6 fade-in">
                <div class="no-print flex justify-between items-center border-b pb-6 px-4 pms-header-nav">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-slate-900">Estado de Cuenta Oficial</h2>
                        <p class="text-xs text-slate-400 font-medium">Documento legal y administrativo para el huésped.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <!-- <button onclick="switchTab('resumen', null)"
                            class="px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black uppercase text-[10px] tracking-widest">Regresar</button> -->
                       <!--  <button onclick="window.print()"
                            class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl hover:bg-indigo-700">
                            <i class="fas fa-file-pdf mr-2"></i> Imprimir / Guardar PDF
                        </button> -->
                        <button onclick="generarPDF()"
                 class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl hover:bg-indigo-700">
                <i class="fas fa-file-pdf mr-2"></i> Generar PDF
                             </button>
                    </div>
                </div>

                <!-- ÁREA DE IMPRESIÓN ESTRUCTURADA -->
                <div class="bg-white p-12 border border-slate-100 rounded-[32px] shadow-2xl max-w-5xl mx-auto relative flex flex-col"
                    id="folio-printable-area">

                    <!-- ENCABEZADO -->
                    <div class="folio-header">
                        <div id="paid-stamp-visual" class="paid-stamp">PAGADO</div>

                        <div class="flex justify-between items-start">
                            <div class="flex gap-6">
                                <svg width="80" height="80" viewBox="0 0 100 100" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="100" height="100" rx="20" fill="#0f172a" />
                                    <path d="M50 20L80 45V80H20V45L50 20Z" fill="white" fill-opacity="0.15" />
                                    <path d="M50 30L65 42V70H35V42L50 30Z" fill="white" />
                                    <rect x="47" y="55" width="6" height="15" fill="#0f172a" />
                                </svg>
                                <div>
                                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Hotel
                                        Elite
                                        Suites <span class="text-indigo-600">Eos</span></h1>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">
                                        International Resorts & Residences</p>
                                    <div
                                        class="grid grid-cols-1 text-[9px] font-bold text-slate-400 uppercase leading-tight gap-1">
                                        <p>Av. Paseo de la Reforma 405, CDMX, 11000</p>
                                        <p>RFC: ESL-120515-HOT | ID Fiscal: 9988221</p>
                                        <p>TEL: +52 (55) 9988-7766 | elite-suites.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div
                                    class="inline-block border-2 border-slate-900 p-6 rounded-2xl text-center min-w-[200px]">
                                    <h2 class="text-[10px] font-black text-slate-400 uppercase mb-1">Estado de Cuenta
                                    </h2>
                                    <p class="text-2xl font-black text-slate-900 mb-2" id="folio-number">#204-A99</p>
                                    <div class="border-t pt-2 mt-2">
                                        <p class="text-[8px] font-black text-indigo-500 uppercase">Fecha Emisión</p>
                                        <p class="text-xs font-black text-slate-800" id="folio-print-date">---</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info del Huésped en Grid -->
                        <div class="grid grid-cols-4 gap-6 mt-12 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="col-span-2">
                                <p class="pms-label text-indigo-600 mb-1">Huésped Titular</p>
                                <h3 class="text-lg font-black text-slate-900" id="folio-guest-name">---</h3>
                                <p class="text-[10px] font-bold text-slate-400" id="folio-guest-id">ID: ---</p>
                            </div>
                            <div>
                                <p class="pms-label text-indigo-600 mb-1">Periodo</p>
                                <p class="text-xs font-black text-slate-800" id="folio-dates-range">---</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase" id="folio-occupancy-summary">
                                    ---</p>
                            </div>
                            <div class="text-right">
                                <p class="pms-label text-indigo-600 mb-1">Habitación</p>
                                <p class="text-base font-black text-slate-900" id="folio-room-type">---</p>
                            </div>
                        </div>
                    </div>

                    <!-- CUERPO (TABLAS) -->
                    <div class="folio-body space-y-10">
                        <!-- Sección Cargos -->
                        <div class="border border-slate-100 rounded-2xl overflow-hidden">
                            <div
                                class="bg-slate-50 px-6 py-3 border-b border-slate-100 flex justify-between items-center">
                                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Desglose de
                                    Cargos y Servicios</h4>
                                <span class="text-[8px] font-bold text-slate-400 italic">Moneda: MXN</span>
                            </div>
                            <table class="w-full text-[11px]">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="py-4 px-6 border-b-2 border-slate-900">Fecha</th>
                                        <th class="py-4 px-6 border-b-2 border-slate-900">Descripción del Servicio</th>
                                        <th class="py-4 px-6 border-b-2 border-slate-900">Departamento</th>
                                        <th class="py-4 px-6 border-b-2 border-slate-900 text-right">Monto Bruto</th>
                                    </tr>
                                </thead>
                                <tbody id="folio-charges-body" class="divide-y divide-slate-100">
                                    <!-- Dinámico -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Sección Abonos -->
                        <div class="border border-emerald-50 rounded-2xl overflow-hidden">
                            <div class="bg-emerald-50 px-6 py-3 border-b border-emerald-100">
                                <h4 class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Historial
                                    de Pagos y Abonos</h4>
                            </div>
                            <table class="w-full text-[11px]">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="py-4 px-6 border-b border-emerald-200">Fecha</th>
                                        <th class="py-4 px-6 border-b border-emerald-200">Método de Pago / Concepto</th>
                                        <th class="py-4 px-6 border-b border-emerald-200 text-right">Abono Aplicado</th>
                                    </tr>
                                </thead>
                                <tbody id="folio-payments-body" class="divide-y divide-emerald-50">
                                    <!-- Dinámico -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PIE DE PÁGINA -->
                    <div class="folio-footer mt-12">
                        <div class="flex justify-between items-start gap-12">
                            <div class="flex-1 space-y-6">
                                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="pms-label mb-2">Términos y Condiciones</p>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase leading-relaxed">
                                        * Este documento no constituye una factura electrónica CFDI de acuerdo con las
                                        leyes vigentes.<br>
                                        * Para solicitar su factura fiscal, favor de contactar a recepción antes de su
                                        salida.<br>
                                        * Elite Suites Luxury se reserva el derecho de cobro posterior por daños o
                                        consumos no reportados.
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-8 pt-6">
                                    <div class="text-center">
                                        <div class="h-px bg-slate-200 w-full mb-3"></div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Firma
                                            de Conformidad Huésped</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="h-px bg-slate-200 w-full mb-3"></div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                                            Autorización Front Desk</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloque de Totales -->
                            <div class="w-80 bg-slate-900 p-8 rounded-[32px] text-white shadow-xl">
                                <div class="space-y-3 mb-6 border-b border-white/10 pb-6">
                                    <div
                                        class="flex justify-between text-[10px] font-bold opacity-60 uppercase tracking-widest">
                                        <span>Subtotal</span><span id="folio-subtotal">$0.00</span>
                                    </div>
                                    <div
                                        class="flex justify-between text-[10px] font-bold opacity-60 uppercase tracking-widest">
                                        <span>Impuestos (16%)</span><span id="folio-taxes">$0.00</span>
                                    </div>
                                    <div
                                        class="flex justify-between text-[10px] font-bold opacity-60 uppercase tracking-widest">
                                        <span>ISH (3%)</span><span id="folio-ish">$0.00</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-black pt-2"><span>TOTAL
                                            ACUMULADO</span><span id="folio-total">$0.00</span></div>
                                    <div class="flex justify-between text-xs font-black text-emerald-400"><span>PAGOS
                                            RECIBIDOS</span><span id="folio-paid">-$0.00</span></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="pms-label text-indigo-400 mb-1">Saldo Final de Cuenta</p>
                                        <h5 class="text-4xl font-black tracking-tighter" id="folio-balance">$0.00</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 text-center text-[8px] font-black text-slate-300 uppercase tracking-[0.5em]">
                            Luxury Experience Delivered by Elite PMS
                        </div>
                    </div>
                </div>
            </div>



        </main>

        <!-- SIDEBAR DE AUDITORÍA Y TOTALES (DERECHA) -->
        <!-- COLUMNA 3: AUDITORÍA -->
        <aside class="audit-panel no-print">
            <div class="flex flex-col items-center mb-8 pb-6 border-b border-slate-800">
                <div class="relative mb-4">
                    <img src="" id="audit-avatar"
                        class="w-20 h-20 rounded-full object-cover border-2 border-indigo-500/50 p-1 bg-slate-800 shadow-xl">
                    <div
                        class="absolute bottom-0 right-0 w-5 h-5 bg-emerald-500 rounded-full border-2 border-slate-900 flex items-center justify-center text-[8px]">
                        <i class="fas fa-sync"></i>
                    </div>
                </div>
                <p class="pms-label text-indigo-400 tracking-[0.3em] mb-1">Folio Auditoría</p>
                <h4 class="text-lg font-bold text-white truncate w-full text-center" id="audit-name">---</h4>
            </div>

            <div class="flex-1 space-y-6 overflow-y-auto custom-scroll pr-2">
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold uppercase tracking-wider">Hospedaje</span>
                        <span class="font-mono text-slate-300" id="stat-hospedaje">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold uppercase tracking-wider">Cargos Extras</span>
                        <span class="font-mono text-slate-300" id="stat-extras">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold uppercase tracking-wider">Sub Total</span>
                        <span class="font-mono text-slate-300" id="stat-sub">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold uppercase tracking-wider">Impuestos (16%)</span>
                        <span class="font-mono text-slate-300" id="stat-taxes">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold uppercase tracking-wider">ish (3%)</span>
                        <span class="font-mono text-slate-300" id="stat-ish">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] pt-2 border-t border-slate-800">
                        <span class="text-emerald-500 font-bold uppercase tracking-wider">Abonos Realizados</span>
                        <span class="font-mono text-emerald-400" id="stat-payments">-$0.00</span>
                    </div>
                </div>

                <div class="pt-6 mt-2 border-t border-slate-800">
                    <p class="pms-label text-slate-500 mb-1">Monto Bruto Acumulado</p>
                    <div class="text-5xl font-black text-white tracking-tighter" id="total-folio">$0.00</div>
                </div>

                <div class="pt-6">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-[9px] font-black uppercase text-indigo-400">Progreso de Pago</span>
                        <span class="text-[10px] font-bold text-slate-400" id="payment-percent">0%</span>
                    </div>
                    <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                        <div id="payment-bar" class="h-full bg-indigo-500 transition-all duration-700"
                            style="width: 0%"></div>
                    </div>
                </div>

                <div
                    class="bg-indigo-600/10 rounded-[35px] border-2 border-indigo-500/20 p-8 text-center mt-6 shadow-inner">
                    <p class="text-[10px] font-black uppercase text-indigo-300 mb-2 tracking-widest">Saldo por Liquidar
                    </p>
                    <p class="text-5xl font-black text-emerald-400 font-mono tracking-tighter" id="saldo-neto">$0.00</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 text-center">
                <p class="text-[8px] font-black text-slate-700 uppercase tracking-widest">Sync: <span
                        id="sync-time">---</span></p>
            </div>
        </aside>

    </div>
</div>





<!-- =========================================
         MODAL DE EDICIÓN CON CÁMARA
         ========================================= -->
<div id="modal-edit-profile" class="modal-overlay-sub" onclick="toggleEditModal(false)">
    <div class="modal-container-edit" onclick="event.stopPropagation()">

        <header class="p-8 border-b bg-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black tracking-tighter text-slate-900 uppercase">Gestión de Perfil e Identidad
                </h3>
                <!--  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sincronización con Tabla:
                    huespedes</p> -->
            </div>
            <button onclick="toggleEditModal(false)"
                class="w-10 h-10 rounded-full hover:bg-red-50 hover:text-red-500 transition-all text-slate-400 flex items-center justify-center">
                <i class="fas fa-times text-xl"></i>
            </button>
        </header>

        <div class="flex-1 overflow-y-auto custom-scroll p-10 bg-white">
            <div class="grid grid-cols-12 gap-10">

                <!-- COLUMNA IZQUIERDA: CÁMARA Y FOTO (Ajustada a col-span-3 para ser más pequeña) -->
                <div class="col-span-3 space-y-6">
                    <div class="bg-slate-900 p-4 rounded-[24px] text-center shadow-xl">
                        <h4 class="pms-label mb-3 text-indigo-400">Captura de Fotografía</h4>

                        <!-- Contenedor de Video/Imagen -->
                        <div id="camera-container" class="relative group">
                            <!-- Stream de Video -->
                           <video id="camera-stream" autoplay playsinline class="hidden"></video>
                            <!-- Vista Previa Estática -->
                            <img src="" id="edit-preview-foto" class="w-full h-full object-cover">
                             <!-- Canvas Oculto para la Captura -->
                            <canvas id="photo-canvas" class="hidden"></canvas>
                        </div>

                        <!-- Botones de Control de Cámara -->
                        <div class="grid grid-cols-1 gap-2">
                            <button id="btn-start-camera" onclick="startCamera('main')"
                                class="w-full py-2.5 bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-700 transition-all">
                                <i class="fas fa-video mr-1"></i> Prender Cámara
                            </button>
                            <button id="btn-take-photo" onclick="takePhoto('main')"
                                class=" w-full py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Tomar
                                Foto</button>
                        </div>                        
                    </div>

                    <div>
                        <label class="pms-label border-b pb-1.5 mb-2.5">Notas de Recepción</label>
                        <textarea id="edit-notas" class="pms-input h-24 resize-none text-[11px]"
                            placeholder="Ej: Cliente VIP..."></textarea>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: FORMULARIO SQL (Ampliado a col-span-9) -->
                <div class="col-span-9 grid grid-cols-2 gap-x-6 gap-y-4">

                    <div class="col-span-2 border-b pb-2 mb-2">
                        <h4 class="font-black text-xs text-indigo-600 uppercase">Información Personal</h4>
                    </div>

                    <div><label class="pms-label">Nombre</label><input type="text" id="edit-nombre" class="pms-input">
                    </div>
                    <div><label class="pms-label">Apellido</label><input type="text" id="edit-apellido"
                            class="pms-input"></div>

                    <div>
                        <label class="pms-label">Género</label>
                        <select id="edit-genero" class="pms-input">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div><label class="pms-label">Fecha Nacimiento</label><input type="date" id="edit-fecha_nacimiento"
                            class="pms-input"></div>

                    <!--  <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="edit-tipo_identificacion_id" class="pms-input">
                            <option value="1">INE / Cédula</option>
                            <option value="2">Pasaporte</option>
                        </select>
                    </div> -->

                    <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="edit-tipo_identificacion_id" class="pms-input"></select>
                    </div>

                    <div><label class="pms-label">Número de ID</label><input type="text" id="edit-numero_identificacion"
                            class="pms-input"></div>

                    <div class="col-span-2 border-b pb-2 mt-4 mb-2">
                        <h4 class="font-black text-xs text-emerald-600 uppercase">Contacto & Ubicación</h4>
                    </div>

                    <div><label class="pms-label">Email</label><input type="email" id="edit-email" class="pms-input">
                    </div>
                    <div><label class="pms-label">Teléfono</label><input type="text" id="edit-telefono"
                            class="pms-input"></div>

                    <div class="col-span-2"><label class="pms-label">Dirección</label><input type="text"
                            id="edit-direccion" class="pms-input"></div>

                    <div><label class="pms-label">Ciudad</label><input type="text" id="edit-ciudad" class="pms-input">
                    </div>
                    <div><label class="pms-label">País</label><input type="text" id="edit-pais" class="pms-input"></div>

                    <div class="col-span-2 bg-slate-50 p-4 rounded-2xl flex items-center justify-between mt-4">
                        <div>
                            <p class="text-[10px] font-black uppercase">Perfil Activo</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit-activo" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleEditModal(false)"
                class="px-8 py-4 rounded-2xl font-black text-[10px] uppercase text-slate-400 hover:text-slate-600">Cancelar</button>
            <button onclick="saveProfile()"
                class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl hover:bg-indigo-700 transition-all">Guadar</button>
        </footer>
    </div>
</div>



<!-- =========================================
         MODALES SECUNDARIOS
         ========================================= -->

<!-- MODAL: LISTADO DE ACOMPAÑANTES (NIVEL 1) -->
<div id="modal-companions-list"
     class="modal-overlay-sub hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50"
     onclick="toggleCompanionsList(false)">

    <div class="modal-container-edit w-full max-w-3xl h-[80vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden"
         onclick="event.stopPropagation()">

        <!-- HEADER -->
        <header class="px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-indigo-500 text-white">

            <div>
                <h3 class="text-lg font-extrabold uppercase tracking-wide">
                    Acompañantes
                </h3>
                <p class="text-[10px] opacity-80 uppercase tracking-widest">
                    Gestión de habitación
                </p>
            </div>

            <button onclick="toggleCompanionsList(false)"
                class="w-9 h-9 rounded-full hover:bg-white/20 flex items-center justify-center transition">
                ✕
            </button>
        </header>

        <!-- BODY -->
        <div class="flex-1 overflow-y-auto custom-scroll p-6 bg-slate-100">

            <div id="companions-grid" class="grid gap-3">

                <!-- CARD TEMPLATE -->
                <!-- JS inyecta aquí -->

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="px-6 py-4 border-t bg-white flex justify-between items-center">

            <!-- CONTADOR -->
            <div class="text-sm font-semibold text-slate-600">
                <span id="companions-counter-footer" class="font-bold text-slate-800">
                    0 / 0
                </span>
                <span class="text-slate-400 text-xs ml-1">personas</span>
            </div>

            <!-- CTA -->
            <button onclick="openCompanionForm(-1)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-xs font-bold shadow-md flex items-center gap-2 transition">
                <i class="fas fa-user-plus"></i>
                Nuevo
            </button>

        </footer>
    </div>
</div>

<!-- MODAL: FORMULARIO TÉCNICO COMPLETO (NIVEL 2) -->
<div id="modal-companion-form" class="modal-overlay-sub" style="z-index: 1100;" onclick="toggleCompanionForm(false)">
    <div class="modal-container-edit" onclick="event.stopPropagation()">
        <header class="px-8 py-4 border-b bg-slate-50 flex justify-between items-center">
            <h3 class="text-lg font-black text-slate-900 uppercase" id="comp-form-title">Registro de Acompañante</h3>
            <button onclick="toggleCompanionForm(false)"
                class="w-8 h-8 rounded-full hover:bg-red-50 hover:text-red-500 text-slate-400 flex items-center justify-center"><i
                    class="fas fa-times"></i></button>
        </header>
        <div class="flex-1 overflow-y-auto custom-scroll p-8 bg-white">
            <div class="grid grid-cols-12 gap-8">

                <!-- Lado Izquierdo: Cámara e Identificación Visual -->
                <div class="col-span-4 space-y-6">
                    <div class="bg-slate-900 p-4 rounded-[24px] text-center shadow-xl border-t-4 border-indigo-600">
                        <h4 class="pms-label mb-3 text-indigo-400">Captura de Fotografía</h4>

                        <div class="camera-box-compact">
                            <video id="video-comp" autoplay playsinline class="hidden"></video>
                            <img src="" id="preview-comp" class="w-full h-full object-cover bg-slate-800">
                            <canvas id="canvas-comp" class="hidden"></canvas>
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            <button id="btn-cam-comp" onclick="startCamera('comp')"
                                class="w-full py-2.5 bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Prender
                                Cámara</button>
                            <button id="btn-snap-comp" onclick="takePhoto('comp')"
                                class=" w-full py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Tomar
                                Foto</button>
                        </div>
                    </div>

                    <!-- Orden de llegada (orden_llegada) -->
                    <div>
                        <label class="pms-label">Orden de Llegada</label>
                        <input type="number" id="comp-orden" class="pms-input" placeholder="Ej: 1">
                    </div>
                </div>

                <!-- Lado Derecho: Todos los campos SQL (registro_acompanantes) -->
                <div class="col-span-8 grid grid-cols-2 gap-x-6 gap-y-5">
                    <div class="col-span-2 border-b pb-2">
                        <h4 class="font-black text-xs text-indigo-600 uppercase">Datos de Identidad Acompañante</h4>
                    </div>

                    <!-- Nombre y Apellido (nombre / apellido) -->
                    <div><label class="pms-label">Nombre(s)</label><input type="text" id="comp-name" class="pms-input">
                    </div>
                    <div><label class="pms-label">Apellido(s)</label><input type="text" id="comp-lastName"
                            class="pms-input"></div>

                    <!-- Identificación (tipo_identificacion / numero_identificacion) -->
                    <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="comp-tipo-id" class="pms-input">
                            <option value="INE">INE / Cédula</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Licencia">Licencia</option>
                            <option value="Otro">Otro Documento</option>
                            <option value="N/A">Sin Identificación</option>
                        </select>
                    </div>
                    <div><label class="pms-label">Número de ID</label><input type="text" id="comp-id" class="pms-input">
                    </div>

                    <!-- Vínculo y Menor (parentesco / es_menor) -->
                    <div><label class="pms-label">Parentesco</label><input type="text" id="comp-rel" class="pms-input"
                            placeholder="Ej: Esposa, Hijo, Socio"></div>
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl">
                        <p class="text-[11px] font-black text-slate-900 uppercase">¿Es Menor?</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="comp-minor" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-amber-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>

                    <div class="col-span-2 border-b pb-2 pt-2">
                        <h4 class="font-black text-xs text-emerald-600 uppercase">Tiempos & Estado Estancia</h4>
                    </div>

                    <!-- Horarios (hora_entrada / hora_salida) -->
                    <div><label class="pms-label">Hora Entrada</label><input type="datetime-local" id="comp-entrada"
                            class="pms-input"></div>
                    <div><label class="pms-label">Hora Salida</label><input type="datetime-local" id="comp-salida"
                            class="pms-input"></div>

                    <!-- Estado (estado_estancia) -->
                    <div class="col-span-2">
                        <label class="pms-label">Estado de Estancia</label>
                        <select id="comp-estado" class="pms-input">
                            <option value="Hospedado">Hospedado</option>
                            <option value="Salida">Salida Realizada</option>
                            <option value="Reserva">Reserva Pendiente</option>
                        </select>
                    </div>

                    <!-- Observaciones (observaciones) -->
                    <div class="col-span-2">
                        <label class="pms-label">Observaciones Técnicas</label>
                        <textarea id="comp-obs" class="pms-input h-20 resize-none text-[11px]"
                            placeholder="Notas adicionales del acompañante..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <footer class="p-8 border-t bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleCompanionForm(false)"
                class="px-8 py-3 rounded-xl font-black text-[10px] uppercase text-slate-400 hover:text-slate-600">Descartar</button>
            <button onclick="saveCompanion()"
                class="px-10 py-3 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase shadow-lg">Guadar
                Acompañante</button>
        </footer>
    </div>
</div>




<!-- MODAL: MODIFY STAY -->
<div id="modal-modify-stay" class="pms-modal-overlay">
    <div class="pms-modal-content">
        <header class="p-8 border-b bg-white flex justify-between items-center">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                        Modificar Instancia
                    </h3>

                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Ajuste de fechas y tarifas
                    </p>
                </div>

            </div>

            <button onclick="toggleModal('modal-modify-stay', false)"
                class="text-slate-400 hover:text-slate-900 transition-colors">
                <i class="fas fa-times"></i>
            </button>

        </header>

        <div class="p-8 grid grid-cols-2 gap-8 bg-slate-50/50">
            <div class="space-y-4">
                <div>
                    <label class="pms-label">Fecha de Llegada (Check-in)</label>
                    <input type="date" id="modify-arrival" class="pms-input w-full bg-slate-100 cursor-not-allowed"
                        readonly>
                    <p class="text-[8px] text-slate-400 mt-1 font-bold uppercase">La llegada no puede modificarse
                        post-ingreso</p>
                </div>
                <div>
                    <label class="pms-label">Fecha de Salida (Check-out)</label>
                    <input type="date" id="modify-departure" class="pms-input w-full">
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="pms-label">Tarifa Diaria Base ($)</label>
                    <input type="number" id="modify-rate" class="pms-input w-full" placeholder="0.00">
                </div>
                <div class="p-6 bg-white border border-slate-200 rounded-3xl text-center">
                    <p class="pms-label">Recálculo Estimado</p>
                    <div class="flex justify-center items-end gap-2">
                        <span class="text-3xl font-black text-slate-900" id="modify-nights-calc">0</span>
                        <span class="text-xs font-bold text-slate-400 mb-1 uppercase">Noches</span>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t bg-white flex justify-between items-center">

            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest max-w-[300px]">
                Al guardar, el cargo por hospedaje se actualizará en el historial del folio.
            </p>

            <div class="flex gap-3">

                <button onclick="toggleModal('modal-modify-stay', false)"
                    class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">
                    Cancelar
                </button>

                <button onclick="saveStayModification()"
                    class="px-8 py-3 rounded-xl bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                    Guardar Cambios
                </button>

            </div>

        </footer>
    </div>
</div>


<!-- MODAL: VEHICLES -->
<!-- MODAL: VEHICLES -->
<div id="modal-vehicles" class="pms-modal-overlay">

    <div class="pms-modal-content" style="max-width: 800px;">

        <!-- HEADER -->
        <header class="p-8 border-b bg-white flex justify-between items-center">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-car-side text-xl"></i>
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                        Inventario de Vehículos
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Control de acceso a estacionamiento
                    </p>
                </div>

            </div>

            <button onclick="toggleModal('modal-vehicles', false)"
                class="text-slate-400 hover:text-slate-900 transition-colors">
                <i class="fas fa-times"></i>
            </button>

        </header>

        <!-- BODY -->
        <div class="p-8 space-y-6 bg-slate-50/50">

            <!-- CARD FORM -->
            <div class="bg-white p-6 rounded-[28px] border border-slate-200">

                <h4 class="pms-label text-indigo-600 mb-4">
                    Registrar Nuevo Vehículo
                </h4>

                <div class="grid grid-cols-12 gap-4">

                    <div class="col-span-12 md:col-span-5">
                        <label class="pms-label">Propietario</label>
                        <select id="vehicle-owner" class="pms-input w-full"></select>
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label class="pms-label">Placas</label>
                        <input type="text" id="vehicle-plates" class="pms-input w-full" placeholder="Ej: ABC-1234">
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="pms-label">Modelo / Color</label>
                        <input type="text" id="vehicle-model" class="pms-input w-full" placeholder="Mazda 3 Rojo">
                    </div>

                    <div class="col-span-12 mt-2">
                        <button onclick="registerVehicle()"
                            class="w-full py-3 rounded-xl bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                            Guardar Vehículo
                        </button>
                    </div>

                </div>
            </div>

            <!-- LIST -->
            <div id="vehicles-list"
                class="bg-white rounded-[28px] border border-slate-200 p-4 space-y-2 max-h-[300px] overflow-y-auto custom-scroll">

                <!-- Dynamic List -->

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="p-6 border-t bg-white flex justify-end">

            <button onclick="toggleModal('modal-vehicles', false)"
                class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">
                Cerrar
            </button>

        </footer>

    </div>
</div>


<!-- MODAL: MOTIVO DE CANCELACIÓN (ACTUALIZADO CON SELECTOR) -->
<div id="modal-motivo" class="pms-modal-overlay" onclick="closeModal('modal-motivo')">
    <div class="pms-modal-content" style="max-width: 500px;" onclick="event.stopPropagation()">
        <header class="p-6 border-b bg-red-50 flex justify-between items-center">
            <div class="flex items-center gap-3 text-red-600">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-widest">Auditoría de Folio</h3>
                    <p class="text-[9px] font-bold opacity-70">REAJUSTE FINANCIERO REQUERIDO</p>
                </div>
            </div>
            <button onclick="closeModal('modal-motivo')" class="text-slate-400 hover:text-red-500"><i
                    class="fas fa-times"></i></button>
        </header>
        <div class="p-8 space-y-5">
            <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100 mb-2">
                <p class="text-[10px] font-bold text-red-700 leading-relaxed italic" id="reajuste-hint">Al eliminar este
                    registro, el sistema realizará un reajuste automático en el saldo por liquidar.</p>
            </div>

            <div>
                <label class="pms-label">Motivo de Eliminación</label>
                <select id="motivo-select" class="pms-input w-full cursor-pointer" onchange="toggleOtroMotivo()">
                    <option value="">Seleccione una razón...</option>
                    <option value="Error de captura">Error de captura / digitación</option>
                    <option value="Cancelación de servicio">Cancelación por parte del huésped</option>
                    <option value="Cortesía administrativa">Cortesía o ajuste administrativo</option>
                    <option value="Cargo duplicado">Cargo duplicado en sistema</option>
                    <option value="OTRO">Otro (especificar abajo)...</option>
                </select>
            </div>

            <div id="otro-motivo-container" class="hidden">
                <label class="pms-label">Especifique detalle</label>
                <textarea id="motivo-input" class="pms-input w-full h-24 resize-none"
                    placeholder="Escriba el motivo técnico o administrativo..."></textarea>
            </div>
        </div>
        <footer class="p-6 border-t flex justify-end gap-3 bg-slate-50">
            <button onclick="closeModal('modal-motivo')"
                class="px-6 py-3 bg-white border border-slate-200 text-slate-400 rounded-xl font-black text-[10px] uppercase">Cancelar</button>
            <button onclick="confirmDeletion()"
                class="px-6 py-3 bg-red-600 text-white rounded-xl font-black text-[10px] uppercase shadow-lg shadow-red-100">Confirmar
                Reajuste</button>
        </footer>
    </div>
</div>


<div id="modal-facturacion" class="pms-modal-overlay">

    <div class="pms-modal-content" style="max-width: 700px;">

        <!-- HEADER -->
        <header class="p-8 border-b bg-white flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                        Datos de Facturación
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Perfil Fiscal del Huésped
                    </p>
                </div>
            </div>

            <button onclick="toggleModal('modal-facturacion', false)" class="text-slate-400 hover:text-slate-900">
                <i class="fas fa-times"></i>
            </button>
        </header>

        <!-- BODY -->
        <div class="p-8 space-y-6 bg-slate-50/50">

            <div class="bg-white p-6 rounded-[28px] border border-slate-200 grid grid-cols-2 gap-4">

                <div class="col-span-2">
                    <label class="pms-label">RFC</label>
                    <input id="fact-rfc" class="pms-input w-full" placeholder="XAXX010101000">
                </div>

                <div class="col-span-2">
                    <label class="pms-label">Razón Social</label>
                    <input id="fact-razon" class="pms-input w-full">
                </div>

                <div>
                    <label class="pms-label">Régimen Fiscal</label>
                    <input id="fact-regimen" class="pms-input w-full" placeholder="601">
                </div>

                <div>
                    <label class="pms-label">Código Postal</label>
                    <input id="fact-cp" class="pms-input w-full">
                </div>

                <div>
                    <label class="pms-label">Uso CFDI</label>
                    <input id="fact-uso" class="pms-input w-full" placeholder="G03">
                </div>

                <div>
                    <label class="pms-label">Email Facturación</label>
                    <input id="fact-email" class="pms-input w-full">
                </div>

                <div class="col-span-2 flex items-center gap-2 mt-2">
                    <input type="checkbox" id="fact-extranjero">
                    <label class="text-xs font-bold text-slate-500">Cliente Extranjero</label>
                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="p-6 border-t bg-white flex justify-between items-center">

            <!-- ELIMINAR -->
            <button onclick="deletePerfilFiscal()"
                class="px-6 py-3 rounded-xl bg-red-50 text-red-600 font-black text-[10px] uppercase hover:bg-red-500 hover:text-white transition-all">
                Eliminar Perfil
            </button>

            <div class="flex gap-3">
                <button onclick="toggleModal('modal-facturacion', false)" class="btn-action btn-slate px-6 py-3">
                    Cancelar
                </button>

                <button onclick="savePerfilFiscal()"
                    class="btn-action btn-indigo px-8 py-3 shadow-lg shadow-indigo-100">
                    Guardar
                </button>
            </div>

        </footer>

    </div>
</div>



<div id="modal-cancelacion" class="pms-modal-overlay hidden" onclick="closeModal('modal-cancelacion')">
    <div class="pms-modal-content" style="max-width: 600px;" onclick="event.stopPropagation()">

        <!-- HEADER -->
        <header class="p-6 border-b bg-indigo-50 flex justify-between items-center">
            <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-indigo-700">
                    Cancelación Inteligente
                </h3>
                <p class="text-[10px] text-slate-400">
                    Simulación de impacto financiero
                </p>
            </div>
            <button onclick="closeModal('modal-cancelacion')">
                <i class="fas fa-times"></i>
            </button>
        </header>

        <div class="p-6 space-y-5">

            <!-- ALERTA PAGOS -->
            <div id="alert-pagos" class="hidden bg-yellow-50 border border-yellow-200 p-3 rounded-xl text-xs text-yellow-700 font-bold">
                ⚠️ Este huésped ya tiene pagos registrados. La cancelación puede generar saldo a favor.
            </div>

            <!-- INFO ESTANCIA -->
            <div class="bg-slate-50 border rounded-xl p-3 text-xs text-slate-500">
                <div class="flex justify-between">
                    <span>Noches totales</span>
                    <span id="noches-totales">0</span>
                </div>
            </div>

            <!-- TIPO -->
            <div>
                <label class="pms-label">Tipo de cancelación</label>
                <select id="cancel-type" class="pms-input w-full" onchange="updateCancelPreview()">
                    <option value="TOTAL">Cancelación total</option>
                    <option value="NO_SHOW">No Show (no llegó)</option>
                    <option value="EARLY">Salida anticipada</option>
                </select>
            </div>

            <!-- NOCHES -->
            <div id="noches-container" class="hidden">
                <label class="pms-label">Noches utilizadas</label>
                <input type="number" id="noches-usadas" class="pms-input w-full"
                    min="0" value="1" onchange="updateCancelPreview()">
            </div>

            <!-- PENALIZACIÓN -->
            <div>
                <label class="pms-label">Penalización (opcional)</label>
                <input type="number" id="penalizacion" class="pms-input w-full"
                    value="0" onchange="updateCancelPreview()">
            </div>

            <!-- PREVIEW -->
            <div class="bg-slate-50 border rounded-xl p-4 space-y-2">

                <div class="flex justify-between text-sm">
                    <span>Reverso estimado</span>
                    <span id="preview-reverso" class="font-bold text-red-500">$0.00</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Penalización</span>
                    <span id="preview-penal">$0.00</span>
                </div>

                <div class="border-t pt-2 flex justify-between font-black text-lg">
                    <span>Impacto total</span>
                    <span id="preview-total">$0.00</span>
                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="p-6 border-t flex justify-end gap-3 bg-slate-50">
            <button onclick="closeModal('modal-cancelacion')" class="btn-secondary">
                Cancelar
            </button>

            <button onclick="confirmCancelacionInteligente()" class="btn-danger">
                Confirmar Cancelación
            </button>
        </footer>

    </div>
</div>