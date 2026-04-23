<?= view('layout/header') ?>


    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            touch-action: manipulation;
        }

        /* Estatus de habitación - Colores Demo19 */
        .status-X { border-left: 10px solid #22c55e; }
        .status-S { border-left: 10px solid #f97316; }
        .status-M { border-left: 10px solid #eab308; background-color: #fef9c3; }
        .status-P { border-left: 10px solid #6366f1; }
        .status-VAP { border-left: 10px solid #ef4444; }
        .status-R { border-left: 10px solid #0ea5e9; background-color: #f0f9ff; }

        .row-shaded { background-color: #e0f2fe !important; outline: 2px solid #0369a1; z-index: 5; }

        /* Iconos de Estatus - Estilo Refinado */
        .status-icon-X::after { 
            content: 'X'; 
            display: inline-flex; align-items: center; justify-content: center;
            width: 22px; height: 22px; border-radius: 50%;
            background-color: #dcfce7; color: #166534; 
            font-weight: 900; font-size: 10px; margin-left: 12px;
            box-shadow: inset 0 0 2px rgba(0,0,0,0.1);
        }
        .status-icon-S::before { 
            content: '\f06a'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
            color: #f97316; font-size: 16px; margin-right: 12px; 
        }
        .status-icon-M::before { 
            content: '\f0ad'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
            color: #eab308; font-size: 16px; margin-right: 12px; 
        }
        .status-icon-P::before { 
            content: '\f015'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
            color: #6366f1; font-size: 16px; margin-right: 12px; 
        }
        .status-icon-VAP::before { 
            content: '\f06d'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
            color: #ef4444; font-size: 16px; margin-right: 12px; 
        }
        .status-icon-R::before { 
            content: '\f073'; font-family: 'Font Awesome 6 Free'; font-weight: 900; 
            color: #0ea5e9; font-size: 16px; margin-right: 12px; 
        }


        /* Estilo Tabla Compacta Demo19 */
        .hotel-grid td {
            border: 1px solid #cbd5e1;
            padding: 2px 8px;
            height: 48px;
            vertical-align: middle;
            background-color: white;
            font-size: 13px;
        }

        .hotel-grid th {
            background-color: #0c4a6e;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px 8px;
            position: sticky;
            top: 0;
            z-index: 20;
            border: 1px solid #075985;
        }

        /* Header Filters */
        .header-filter {
            cursor: pointer;
            position: relative;
        }

        .header-filter:hover {
            background-color: #075985;
        }

        .filter-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            color: #334155;
            min-width: 180px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            border-radius: 0.5rem;
            z-index: 50;
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-transform: none;
            font-weight: normal;
        }

        .header-filter:focus-within .filter-dropdown {
            display: block;
        }

        /* Modales */
        .modal-active {
            display: flex !important;
        }

        /* Inputs Estilo Terminal Premium */
        .form-section-title {
            font-size: 11px;
            font-weight: 900;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .form-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
            margin-left: 15px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background-color: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .guest-card-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .guest-card-item:active {
            transform: scale(0.98);
            background-color: #f8fafc;
        }

        /* Módulos de Captura Media */
        .media-box {
            background: #f1f5f9;
            border: 2px dashed #cbd5e1;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .box-photo-client {
            aspect-ratio: 1/1.2;
            width: 100%;
        }

        .box-id-card {
            aspect-ratio: 1.6/1;
            width: 100%;
        }

        .camera-on {
            background: #0f172a !important;
            border-style: solid !important;
            border-color: #3b82f6 !important;
        }

        .scan-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background: #3b82f6;
            box-shadow: 0 0 15px #3b82f6;
            top: 0;
            left: 0;
            animation: scanning 3s infinite linear;
            display: none;
            z-index: 10;
        }

        @keyframes scanning {
            0% {
                top: 0%;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0%;
            }
        }

        /* Pad de Firma Profesional */
        .signature-container {
            width: 100%;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 10px;
        }

        .signature-pad-pro {
            width: 100%;
            height: 180px;
            background: #fafafa;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px inset #eee;
        }

        .btn-action-pro {
            @apply flex items-center justify-center space-x-2 font-black uppercase text-[10px] py-3 px-2 rounded-xl transition-all active:scale-95;
        }

        /* Buscador de Clientes UI */
        .search-results-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            z-index: 100;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            max-height: 250px;
            overflow-y: auto;
            display: none;
        }

        .search-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
        }

        .search-item:hover {
            background-color: #eff6ff;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* --- ESTILOS PARA TICKET TÉRMICO (80mm / 88mm) --- */
        .ticket-paper {
            width: 350px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            font-family: 'Courier New', Courier, monospace;
            color: black;
            line-height: 1.2;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .ticket-title {
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .ticket-info {
            font-size: 12px;
            margin-bottom: 15px;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .ticket-divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .ticket-total {
            font-size: 18px;
            font-weight: 900;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }

        .ticket-footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
            font-style: italic;
        }

        @media print {
            body * { visibility: hidden; }
            #modal-preview, #modal-preview * { visibility: visible; }
            #modal-preview { 
                position: fixed; 
                left: 0; 
                top: 0; 
                width: 100%; 
                height: 100%; 
                background: white !important;
                display: flex !important;
                justify-content: center;
                align-items: flex-start;
                z-index: 9999;
            }
            .ticket-paper { 
                width: 80mm !important; 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 !important;
                padding: 5mm !important;
            }
            .no-print, button, #btn-final-action { display: none !important; }
        }
    </style>





    <!-- VISTA TRABAJO -->
    <section id="view-work" class="flex-1 flex flex-col bg-slate-200 overflow-hidden">
        <div class="bg-[#0f172a] p-3 flex items-center px-6 border-b border-white/5 overflow-x-auto no-scrollbar">
            <!-- 1. BOTONES DE ACCIÓN (CAMBIO/ROOM SERVICE) -->
            <div class="flex items-center space-x-3 pr-6 border-r border-slate-700/50 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest mr-2">Acciones:</span>
                <button onclick="openSubModal('modal-cambio')"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg shadow-blue-900/40 border border-blue-400/20">
                    <i class="fas fa-exchange-alt mr-2 text-[8px]"></i>CAMBIO
                </button>
                <button onclick="openSubModal('modal-roomservice')"
                    class="bg-orange-600 hover:bg-orange-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg shadow-orange-900/40 border border-orange-400/20">
                    <i class="fas fa-utensils mr-2 text-[8px]"></i>ROOM SERVICE
                </button>
            </div>

            <!-- 2. PISOS -->
            <div class="flex items-center space-x-4 px-6 border-r border-slate-700/50 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Piso:</span>
                <div id="floor-tabs" class="flex bg-slate-800/30 p-1.5 rounded-xl space-x-1 border border-slate-700/50">
                    <!-- Los pisos se cargarán dinámicamente aquí -->
                </div>
            </div>

            <!-- 3. TIPO HABITACIÓN -->
            <div class="flex items-center space-x-4 px-6 border-r border-slate-700/50 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Tipo:</span>
                <select id="filter-tipo-hab" onchange="applyTipoFilter()" class="bg-slate-800/50 text-white border border-slate-700 rounded-xl px-4 py-2 text-[10px] outline-none focus:border-blue-500 transition-all font-bold">
                    <option value="">Todos</option>
                </select>
            </div>

            <!-- 4. BUSCADOR GLOBAL -->
            <div class="relative w-80 px-6 border-r border-slate-700/50 shrink-0">
                <input type="text" id="global-search" oninput="applyGlobalSearch()"
                    placeholder="Búsqueda Global..."
                    class="bg-slate-800/50 text-white border border-slate-700 rounded-xl px-10 py-2 text-[10px] w-full outline-none focus:border-blue-500 transition-all shadow-inner">
                <i class="fas fa-search absolute left-10 top-1/2 -translate-y-1/2 text-slate-500 text-[9px]"></i>
            </div>
            
            <!-- 5. BOTÓN RESET (LIMPIAR) -->
            <div class="px-6 shrink-0">
                <button onclick="resetFilters()"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg border border-slate-700">
                    LIMPIAR FILTROS
                </button>
            </div>
            
            <div class="flex-1 min-w-[50px]"></div>
            <div class="text-[9px] font-black text-slate-500 uppercase italic shrink-0 opacity-50">HotelOS Operational Grid v2.0</div>
        </div>

        <div class="flex-1 overflow-auto bg-white">
            <table class="w-full hotel-grid table-fixed min-w-[1900px]">
                <thead>
                    <tr id="grid-header-row">
                        <!-- Generado por JS para incluir filtros en todos -->
                    </tr>
                </thead>
                <tbody id="rooms-tbody"></tbody>
            </table>
        </div>

        <footer class="bg-slate-900 text-white p-3 flex items-center justify-between border-t border-slate-700">
            <div class="text-[10px] font-black tracking-widest italic uppercase text-white">
                Mostrando: <span id="count-filtered">0</span> de <span id="count-total">0</span> | HotelOS Terminal Pro
            </div>
            <div></div>
        </footer>
    </section>

    <!-- MODAL DE REGISTRO -->
    <div id="modal-register"
        class="fixed inset-0 bg-slate-900/90 hidden z-[100] items-center justify-center p-6 backdrop-blur-md">
        <div
            class="bg-white w-full max-w-[1300px] rounded-[3rem] shadow-2xl flex flex-col overflow-hidden max-h-[96vh] border border-white/20">

            <!-- HEADER MODAL -->
            <div class="bg-slate-900 p-6 text-white flex justify-between items-center shrink-0 relative overflow-visible">
                <div class="flex items-center space-x-6">
                    <div class="bg-blue-600 w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg"><i
                            class="fas fa-user-check text-xl"></i></div>
                    <div>
                        <p class="text-[9px] font-black uppercase opacity-50 tracking-widest mb-1">Módulo de Admisión
                        </p>
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-black italic uppercase tracking-tighter" id="reg-room-title">Habitación --</h3>
                            
                            <!-- 🔥 CONTADORES SUBIDOS AL HEADER -->
                            <div id="reg-header-counters" class="flex items-center space-x-4 border-l border-white/10 pl-6 ml-2">
                                <!-- Ocupación (Lista) -->
                                <div id="header-occ-container" class="flex items-center space-x-3 bg-white/10 px-5 py-2 rounded-full border border-white/20">
                                    <i class="fas fa-users text-blue-400 text-xs"></i>
                                    <span class="text-[11px] font-black text-slate-300 uppercase tracking-wider">Ocupación:</span>
                                    <span id="occupancy-counter" class="text-2xl font-black text-white italic">0 / 0</span>
                                </div>
                                <!-- Registrando (Formulario) -->
                                <div id="header-person-container" class="hidden flex items-center space-x-3 bg-white/10 px-5 py-2 rounded-full border border-white/20">
                                    <i class="fas fa-user-edit text-blue-400 text-xs"></i>
                                    <span class="text-[11px] font-black text-slate-300 uppercase tracking-wider">Huésped:</span>
                                    <span id="form-person-counter" class="text-2xl font-black text-white italic">1 de 2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BUSCADOR GLOBAL EN HEADER (SIEMPRE VISIBLE) -->
                <div class="flex-1 flex items-center justify-center px-12 overflow-visible">
                    <div class="relative w-full max-w-md">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-400 text-sm font-black"><i
                                class="fas fa-search"></i></span>
                        <input type="text" id="client-db-search-header" oninput="handleClientDBSearch(this.value, 'header')"
                            placeholder="BUSCAR CLIENTE HISTÓRICO..."
                            class="w-full bg-white/10 border-2 border-white/10 rounded-2xl pl-12 pr-6 py-3 text-xs font-black uppercase text-white shadow-inner focus:bg-white focus:text-slate-900 focus:border-blue-500 outline-none transition-all">
                        <div id="db-search-results-header" class="search-results-box text-slate-900 shadow-2xl border border-slate-200"></div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="closeModal('modal-register')"
                        class="w-10 h-10 rounded-full flex items-center justify-center bg-white/10 active:scale-90 transition-all"><i
                            class="fas fa-times text-white"></i></button>
                </div>
            </div>

            <!-- VISTA 1: LISTADO DE HUÉSPEDES -->
            <div id="reg-view-list" class="flex-1 overflow-y-auto p-12 space-y-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center space-x-6">
                        <h4 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight">Huéspedes Registrados</h4>
                    </div>
                    <button onclick="goToForm(-1)" id="btn-nuevo-huesped"
                        class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black uppercase text-sm shadow-xl active:scale-95 transition-all"><i
                            class="fas fa-plus-circle mr-3"></i>Nuevo Huésped</button>
                </div>

                <div id="guests-list-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cards dinámicas -->
                </div>

                <div class="pt-10 border-t flex flex-col md:flex-row justify-between items-center gap-6">
                    <!-- 🔥 BOTÓN DE CHECKIN (ANTERIOR SINCRONIZAR) -->
                    <button onclick="confirmAllRegistration()" id="btn-sincronizar"
                        class="bg-emerald-500 text-white px-10 py-6 rounded-[2.5rem] font-black uppercase text-xs shadow-xl hover:bg-emerald-600 active:scale-95 transition-all flex-1 w-full md:w-auto flex items-center justify-center space-x-3">
                        <i class="fas fa-check-circle text-lg"></i>
                        <span>Checkin</span>
                    </button>

                    <!-- 🔥 BOTÓN DE CANCELAR ESTADÍA -->
                    <button id="btn-cancelar-registro" onclick="cancelarRegistroActual()"
                        class="bg-rose-50 text-rose-700 px-10 py-6 rounded-[2.5rem] font-black uppercase text-xs shadow-sm hover:bg-rose-100 active:scale-95 transition-all flex items-center justify-center space-x-3 flex-1 w-full md:w-auto border border-rose-100">
                        <i class="fas fa-ban text-lg"></i>
                        <span>Cancelar Estadía / Reservación</span>
                    </button>

                    <!-- 🔥 BOTÓN DE CHECKOUT NIVELADO -->
                    <button id="btn-checkout-footer" onclick="hacerCheckout()" 
                        class="hidden bg-rose-600 text-white px-10 py-6 rounded-[2.5rem] font-black uppercase text-xs shadow-xl hover:bg-rose-700 active:scale-95 transition-all flex items-center justify-center space-x-3 flex-1 w-full md:w-auto">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span>Finalizar Estancia (Checkout)</span>
                    </button>
                </div>
            </div>

            <!-- VISTA 2: FORMULARIO DE CAPTURA TERMINAL -->
            <div id="reg-view-form" class="hidden flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
                <div class="flex items-center space-x-6 mb-8">
                    <button onclick="backToGuestList()"
                        class="w-12 h-12 rounded-xl bg-white shadow-md flex items-center justify-center text-slate-400 active:text-blue-600 transition-colors"><i
                            class="fas fa-arrow-left text-lg"></i></button>
                    <div>
                        <h4 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter"
                            id="form-title">Expediente de Identidad</h4>
                        <p class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Captura Multimodal</p>
                    </div>
                    <div class="flex-1"></div>
                </div>

                <div class="grid grid-cols-12 gap-8 max-w-[1400px] mx-auto">

                    <!-- Columna Media: Fotos y Firma -->
                    <div class="col-span-12 lg:col-span-4 space-y-6">

                        <!-- 1. Foto Cliente -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-user-circle mr-3"></i>Foto Cliente</div>
                            <div id="box-client" class="media-box box-photo-client mb-4">
                                <i class="fas fa-user text-6xl opacity-10"></i>
                                <span class="text-[9px] font-black uppercase text-slate-400 mt-4">Video de Rostro</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="toggleCamera('box-client')"
                                    class="btn-action-pro bg-orange-500 text-white shadow-lg">
                                    <i class="fas fa-power-off mr-2"></i>Cámara
                                </button>
                                <button onclick="capturePhoto('box-client')"
                                    class="btn-action-pro bg-slate-900 text-white">
                                    <i class="fas fa-camera mr-2"></i>Capturar
                                </button>
                            </div>
                        </div>

                        <!-- 2. Foto Identificación + OCR -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-id-card mr-3"></i>Identificación</div>
                            <div id="box-id" class="media-box box-id-card mb-4">
                                <div id="scan-line" class="scan-line"></div>
                                <i class="fas fa-id-badge text-7xl opacity-10"></i>
                                <span class="text-[9px] font-black uppercase text-slate-400 mt-4">Scan Documento</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <button onclick="toggleCamera('box-id')"
                                    class="btn-action-pro bg-orange-500 text-white shadow-lg">
                                    <i class="fas fa-power-off mr-1"></i>On
                                </button>
                                <button onclick="capturePhoto('box-id')"
                                    class="btn-action-pro bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fas fa-image mr-1"></i>Capturar
                                </button>
                                <button onclick="runOCR()"
                                    class="btn-action-pro bg-blue-600 text-white shadow-lg shadow-blue-100">
                                    <i class="fas fa-expand-arrows-alt mr-1"></i>OCR
                                </button>
                                <!-- 🔥 File input para OCR -->
                                <input type="file" id="f-ocr-file" class="hidden" accept="image/*" onchange="handleOCRFile(this)">
                                <button onclick="document.getElementById('f-ocr-file').click()"
                                    class="btn-action-pro bg-slate-800 text-white">
                                    <i class="fas fa-upload mr-1"></i>Subir
                                </button>
                            </div>
                        </div>

                        <!-- 3. Firma -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-pen-nib mr-3"></i>Firma Electrónica</div>
                            <div class="signature-container mb-4">
                                <div class="signature-pad-pro">
                                    <span
                                        class="text-slate-300 font-black uppercase text-[8px] tracking-[0.2em]">Hardware
                                        Externo</span>
                                    <canvas id="sig-canvas"
                                        class="absolute inset-0 w-full h-full cursor-crosshair"></canvas>
                                </div>
                            </div>
                            <button onclick="initSignaturePad()"
                                class="w-full btn-action-pro bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i class="fas fa-tablet-alt mr-2"></i>Activar Pad
                            </button>
                        </div>
                    </div>

                    <!-- Columna Datos: Texto -->
                    <div class="col-span-12 lg:col-span-8 space-y-6">

                        <!-- Panel: Información Personal -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title flex justify-between items-center">
                                <span><i class="fas fa-user-edit mr-3"></i>Datos Generales</span>
                                <div class="flex items-center space-x-3 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                                    <label class="form-label !mb-0 uppercase tracking-widest text-[10px] font-black text-slate-500">¿Es Menor de Edad?</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="f-is-minor" class="sr-only peer" onchange="toggleMinorFields(this.checked)">
                                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div class="form-input-group"><label class="form-label">Nombre(s)</label><input
                                        type="text" id="f-name" class="form-input" placeholder="Nombre" oninput="this.value = this.value.toUpperCase()"></div>
                                <div class="form-input-group"><label class="form-label">Apellidos</label><input
                                        type="text" id="f-apellidos" class="form-input" placeholder="Apellidos" oninput="this.value = this.value.toUpperCase()"></div>
                            </div>
                            <div id="group-minor-hidden-1" class="grid grid-cols-2 gap-5 mb-5">
                                <div class="form-input-group"><label class="form-label">Teléfono Movil</label><input
                                        type="tel" id="f-tel" class="form-input" placeholder="Min 10 - Max 12 dígitos" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')"></div>
                                <div class="form-input-group"><label class="form-label">Correo Electrónico</label><input
                                        type="email" id="f-mail" class="form-input" placeholder="ejemplo@mail.com">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-5">
                                <div id="group-minor-hidden-2" class="form-input-group"><label class="form-label">Nacionalidad</label><input
                                        type="text" id="f-nat" class="form-input" value="MEXICANA"></div>
                                <div id="group-minor-hidden-3" class="form-input-group"><label class="form-label">Nacimiento</label><input
                                        type="date" id="f-birth" class="form-input"></div>
                                <div class="form-input-group">
                                    <label class="form-label">Género</label>
                                    <select id="f-gender" class="form-input">
                                        <option>Masculino</option>
                                        <option>Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <!-- 🔥 Nuevos campos solicitados -->
                            <div class="grid grid-cols-2 gap-5 mt-5">
                                <div class="form-input-group">
                                    <label class="form-label uppercase tracking-widest text-[10px]">Parentesco (con titular)</label>
                                    <select id="f-parentesco" class="form-input font-bold">
                                        <option value="Huésped Principal">Huésped Principal</option>
                                        <option value="Hermano">Hermano</option>
                                        <option value="Esposa / Cónyuge">Esposa / Cónyuge</option>
                                        <option value="Hijo">Hijo</option>
                                        <option value="Colega / Socio">Colega / Socio</option>
                                        <option value="Amigo">Amigo</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div class="form-input-group">
                                    <label class="form-label uppercase tracking-widest text-[10px]">Empresa</label>
                                    <input type="text" id="f-empresa" class="form-input" placeholder="Nombre de la empresa">
                                </div>
                            </div>
                            
                            <!-- 🔥 Campo Responsable (Solo Menores) -->
                            <div id="group-responsable" class="form-input-group mt-5 hidden animate-pulse-subtle">
                                <label class="form-label text-blue-600 font-black uppercase tracking-widest text-[10px]">Nombre del Responsable / Tutor</label>
                                <input type="text" id="f-responsable" class="form-input border-2 border-blue-100 bg-blue-50/30" placeholder="Escriba nombre completo del tutor">
                            </div>
                        </div>

                        <!-- Panel: Documento e Identidad -->
                        <div id="panel-documentacion" class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-file-invoice mr-3"></i>Documentación</div>
                            <div class="grid grid-cols-2 gap-5">
                                <div class="form-input-group">
                                    <label class="form-label">Tipo de Identificación</label>
                                    <select id="f-id-type" class="form-input">
                                        <option value="">Cargando...</option>
                                    </select>
                                </div>
                                <div class="form-input-group">
                                    <label class="form-label">Número de Folio / ID</label>
                                    <input type="text" id="f-id-num" class="form-input font-mono uppercase"
                                        placeholder="ABC000000">
                                </div>
                            </div>
                        </div>

                        <!-- Panel: Ubicación y Vehículo -->
                        <div id="panel-ubicacion" class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 transition-all duration-300">
                            <div class="form-section-title"><i class="fas fa-map-marked-alt mr-3"></i>Ubicación &
                                Tránsito</div>
                            <div class="grid grid-cols-4 gap-5 mb-5">
                                <div class="form-input-group col-span-3"><label class="form-label">Dirección Fiscal /
                                        Residencia</label><input type="text" id="f-address" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">C.P.</label><input type="text"
                                        id="f-cp" class="form-input font-mono"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-5">
                                <div class="form-input-group"><label class="form-label">Ciudad</label><input type="text"
                                        id="f-city" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">Estado</label><input type="text"
                                        id="f-state" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">Placas Vehículo</label><input
                                        type="text" id="f-placas" class="form-input font-mono uppercase"
                                        placeholder="AAA-000"></div>
                            </div>
                        </div>

                        <!-- Panel: Footer de Captura -->
                        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl">
                            <div class="grid grid-cols-2 gap-8 items-center">
                                <div class="form-input-group">
                                    <label class="form-label !text-blue-400">Rol del Huésped</label>
                                    <select id="f-is-titular"
                                        class="form-input !bg-slate-800 !border-slate-700 !text-white font-black italic">
                                        <option value="true">TITULAR PRINCIPAL</option>
                                        <option value="false">ACOMPAÑANTE</option>
                                    </select>
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="saveGuestAndReturn()"
                                        class="flex-1 bg-blue-600 text-white py-5 rounded-2xl font-black uppercase text-xs shadow-lg active:scale-95 transition-all">
                                        <i class="fas fa-save mr-2"></i>Guardar Expediente
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL OPCIONES (GESTIÓN) -->
    <div id="modal-options-menu"
        class="fixed inset-0 bg-slate-900/90 hidden z-[110] items-center justify-center p-6 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-[3.5rem] shadow-2xl p-12 overflow-hidden">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase mb-2">Panel de Gestión</p>
                    <h3 class="text-4xl font-black italic uppercase" id="menu-opt-hab">HABITACIÓN --</h3>
                </div>
                <button onclick="closeModal('modal-options-menu')"
                    class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="grid grid-cols-1 gap-4">
                
                <button onclick="openSubModal('modal-pagos')" class="btn-menu-opt">
                    <i class="fas fa-cash-register text-2xl text-emerald-500"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Control de Pagos</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Registrar abonos al
                            folio</p>
                    </div>
                </button>
                <button onclick="openSubModal('modal-estado-cuenta')" class="btn-menu-opt">
                    <i class="fas fa-file-invoice-dollar text-2xl text-blue-700"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Estado de Cuenta</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Ver movimientos
                            detallados</p>
                    </div>
                </button>
                <button onclick="openSubModal('modal-register-sub')" class="btn-menu-opt">
                    <i class="fas fa-print text-2xl text-slate-500"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Imprimir Registro</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Generar formato
                            oficial A4</p>
                    </div>
                </button>
                <button onclick="verSalidas()" class="btn-menu-opt">
                    <i class="fas fa-door-open text-2xl text-orange-500"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Historial de Salidas</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Ver bitácora de entradas y salidas</p>
                    </div>
                </button>

                <button onclick="abrirModalFiscal()" class="btn-menu-opt">
                    <i class="fas fa-file-invoice text-2xl text-purple-600"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Datos Fiscales</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Administrar RFC y facturación</p>
                    </div>
                </button>

                <button onclick="openRegistrationTicket(selectedRoomIdx)" class="btn-menu-opt">
                    <i class="fas fa-file-invoice-dollar text-2xl text-emerald-600"></i>
                    <div class="text-left">
                        <p class="font-black text-slate-800 uppercase">Comprobante</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Ver ticket de admisión</p>
                    </div>
                </button>
            </div>
            <div class="mt-8 pt-8 border-t">
                <label class="form-label">Bitácora de Observaciones</label>
                <textarea id="opt-obs-input"
                    class="w-full h-24 p-5 bg-slate-50 rounded-2xl border-2 outline-none font-bold focus:border-blue-500 transition-all"
                    oninput="updateRoomObs(this.value)"></textarea>
            </div>
        </div>
    </div>

    <!-- MODAL CAMBIO DE HABITACIÓN -->
    <div id="modal-cambio"
        class="fixed inset-0 bg-slate-900/95 hidden z-[120] items-center justify-center p-6 backdrop-blur-md">
        <div
            class="bg-white w-full max-w-5xl rounded-[3.5rem] shadow-2xl p-10 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-4xl font-black italic uppercase text-blue-600">Mover Huéspedes</h3>
                <button onclick="closeModal('modal-cambio')"
                    class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="grid grid-cols-12 gap-8 flex-1 overflow-hidden">
                <!-- COLUMNA IZQUIERDA: ORIGEN Y MOTIVO -->
                <div class="col-span-12 lg:col-span-4 space-y-6 flex flex-col">
                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                        <label class="form-label text-blue-600">1. Seleccionar Habitación Origen</label>
                        <select id="cambio-source-room" onchange="updateMoveSource()" class="form-input !bg-white border-2 border-blue-100 font-black italic">
                            <option value="">Seleccionar...</option>
                        </select>
                        <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Solo habitaciones con Check-in activo</p>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 flex-1">
                        <label class="form-label">2. Motivo del Traslado</label>
                        <textarea id="move-motivo" class="w-full h-32 p-4 bg-slate-50 rounded-2xl border-2 outline-none font-bold focus:border-blue-500 transition-all resize-none" placeholder="Ej: Falla en aire acondicionado, Upgrade solicitado, etc."></textarea>
                    </div>

                    <div class="bg-slate-900 p-6 rounded-[2.5rem] shadow-xl">
                        <button id="btn-confirm-move" onclick="executeRoomMove()" disabled
                            class="w-full bg-blue-600 text-white py-5 rounded-2xl font-black uppercase text-xs shadow-lg active:scale-95 transition-all opacity-30 cursor-not-allowed">
                            <i class="fas fa-exchange-alt mr-2"></i>Confirmar Traslado
                        </button>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: DESTINO -->
                <div class="col-span-12 lg:col-span-8 flex flex-col overflow-hidden">
                    <div class="bg-slate-50 p-8 rounded-[3rem] border border-slate-200 flex flex-col h-full">
                        <label class="form-label text-emerald-600 mb-4">3. Seleccionar Habitación Destino</label>

                        <!-- Buscador integrado -->
                        <div class="relative mb-4">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <input type="text" id="cambio-dest-search"
                                oninput="filterDestSelect(this.value)"
                                class="w-full pl-10 pr-4 py-3 bg-white border-2 border-slate-200 rounded-2xl text-sm font-bold outline-none focus:border-emerald-500 transition-all"
                                placeholder="Buscar por número de habitación...">
                        </div>

                        <!-- Rejilla de habitaciones destino (Optimizado para Tablet) -->
                        <div id="cambio-grid" class="flex-1 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-2 min-h-[300px] content-start">
                            <div class="col-span-full flex flex-col items-center justify-center py-20 opacity-30">
                                <i class="fas fa-bed text-5xl mb-4"></i>
                                <p class="font-black uppercase tracking-tighter">Cargando habitaciones...</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase italic">Destino seleccionado: <span id="move-dest-label" class="text-emerald-600 font-black">---</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ESTADO DE CUENTA -->
    <div id="modal-estado-cuenta"
        class="fixed inset-0 bg-slate-900/95 hidden z-[120] items-center justify-center p-6 backdrop-blur-md">
        <div class="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="bg-blue-700 p-6 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-file-invoice-dollar text-2xl"></i>
                    <div>
                        <h3 class="text-xl font-black italic uppercase tracking-tighter">Estado de Cuenta Interactivo
                        </h3>
                        <p class="text-[10px] opacity-70 uppercase font-bold" id="account-room-label">HABITACIÓN --</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-estado-cuenta')"
                    class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 transition-all"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto p-8">
                <table class="w-full text-left">
                    <thead class="bg-slate-100 border-b">
                        <tr class="text-[10px] uppercase font-black text-slate-500">
                            <th class="p-4">Fecha/Hora</th>
                            <th class="p-4">Concepto</th>
                            <th class="p-4 text-right">Cargo</th>
                            <th class="p-4 text-right">Abono</th>
                            <th class="p-4 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="movements-tbody" class="text-sm font-bold text-slate-700"></tbody>
                </table>
            </div>
            <div class="p-6 bg-slate-50 border-t flex justify-end space-x-4">
                <button onclick="closeModal('modal-estado-cuenta')"
                    class="px-8 py-3 rounded-xl bg-slate-200 text-slate-600 font-black uppercase text-xs">Cerrar</button>
                <button onclick="openTicketPreviewFromStatement()"
                    class="px-8 py-3 rounded-xl bg-blue-600 text-white font-black uppercase text-xs shadow-lg">Ver
                    Ticket</button>
            </div>
        </div>
    </div>

    <!-- MODAL PREVIEW (TICKET / REGISTRO A4) -->
    <div id="modal-preview"
        class="fixed inset-0 bg-black/95 hidden z-[200] items-center justify-center p-12 backdrop-blur-md overflow-y-auto">
        <div class="flex flex-col items-center space-y-8 max-h-full">
            <div id="preview-content" class="shrink-0"></div>
            <div class="flex space-x-6 shrink-0 pb-10">
                <button onclick="executeFinalAction()"
                    class="bg-emerald-500 text-white px-16 py-6 rounded-3xl font-black uppercase shadow-2xl active:scale-95 transition-all"
                    id="btn-final-action">Confirmar Acción</button>
                <button onclick="simulateAction('Generando Impresión PDF...')"
                    class="bg-blue-600 text-white px-16 py-6 rounded-3xl font-black uppercase shadow-2xl active:scale-95 transition-all">Imprimir</button>
                <button onclick="closeModal('modal-preview')"
                    class="bg-white text-slate-900 px-16 py-6 rounded-3xl font-black uppercase shadow-2xl">Cerrar /
                    Volver</button>
            </div>
        </div>
    </div>

    <!-- MODAL PAGOS -->
    <div id="modal-pagos" class="fixed inset-0 bg-slate-900/95 hidden z-[120] items-center justify-center p-6">
        <div class="bg-white w-full max-w-3xl rounded-[3.5rem] p-10 flex flex-col max-h-[85vh] shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black uppercase text-emerald-600 italic">Abonar al Folio</h3><button
                    onclick="closeModal('modal-pagos')"
                    class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="form-input-group">
                    <label class="form-label">Monto</label>
                    <input type="number" id="pay-amount" class="form-input text-2xl" placeholder="0.00">
                </div>
                <div class="form-input-group">
                    <label class="form-label">Método</label>
                    <select id="pay-method" class="form-input">
                        <option>Efectivo</option>
                        <option>Tarjeta</option>
                    </select>
                </div>
            </div>
            <button onclick="registerPayment()"
                class="w-full py-5 bg-emerald-600 text-white rounded-2xl font-black uppercase shadow-lg mb-8">Registrar
                Abono</button>
            <div id="payments-history" class="mt-6 flex-1 overflow-y-auto space-y-2 bg-slate-50 p-6 rounded-3xl border">
            </div>
        </div>
    </div>

    <!-- MODAL HISTORIAL DE SALIDAS -->
    <div id="modal-salidas"
        class="fixed inset-0 bg-slate-900/95 hidden z-[130] items-center justify-center p-6 backdrop-blur-md">
        <div class="bg-white w-full max-w-4xl rounded-[3.5rem] shadow-2xl p-10 overflow-hidden flex flex-col max-h-[85vh]">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-4xl font-black italic uppercase text-orange-600">Bitácora de Salidas</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase">Movimientos Registrados</p>
                </div>
                <button onclick="closeModal('modal-salidas')"
                    class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center"><i
                        class="fas fa-times"></i></button>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-100">
                            <th class="py-4 text-[10px] font-black text-slate-400 uppercase">Fecha / Hora</th>
                            <th class="py-4 text-[10px] font-black text-slate-400 uppercase">Huésped</th>
                            <th class="py-4 text-[10px] font-black text-slate-400 uppercase">Tipo</th>
                            <th class="py-4 text-[10px] font-black text-slate-400 uppercase">Motivo / Obs</th>
                        </tr>
                    </thead>
                    <tbody id="lista-salidas-body">
                        <!-- Filas dinámicas -->
                    </tbody>
                </table>
                <div id="no-salidas-msg" class="hidden py-20 text-center">
                    <i class="fas fa-history text-5xl text-slate-100 mb-4"></i>
                    <p class="text-slate-400 font-black uppercase text-xs">Sin movimientos registrados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DATOS FISCALES -->
    <div id="modal-fiscal" class="fixed inset-0 bg-slate-900/95 hidden z-[140] items-center justify-center p-6 backdrop-blur-md">
        <div class="bg-white w-full max-w-2xl rounded-[3.5rem] shadow-2xl p-10 flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center mb-8 border-b pb-6">
                <div>
                    <h3 class="text-4xl font-black italic uppercase text-purple-600">Datos Fiscales</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase" id="fiscal-hab-label">HABITACIÓN --</p>
                </div>
                <button onclick="closeModal('modal-fiscal')" class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="flex-1 overflow-y-auto space-y-6 px-2">
                <div class="grid grid-cols-2 gap-5">
                    <div class="form-input-group">
                        <label class="form-label">RFC</label>
                        <input type="text" id="fiscal-rfc" class="form-input uppercase font-mono" placeholder="XAXX010101000">
                    </div>
                    <div class="form-input-group">
                        <label class="form-label">Régimen Fiscal</label>
                        <input type="text" id="fiscal-regimen" class="form-input" placeholder="601">
                    </div>
                </div>

                <div class="form-input-group">
                    <label class="form-label">Razón Social</label>
                    <input type="text" id="fiscal-razon" class="form-input" placeholder="Nombre completo o Razón Social">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="form-input-group">
                        <label class="form-label">Código Postal</label>
                        <input type="text" id="fiscal-cp" class="form-input">
                    </div>
                    <div class="form-input-group">
                        <label class="form-label">Uso CFDI</label>
                        <input type="text" id="fiscal-uso" class="form-input" placeholder="G03">
                    </div>
                </div>

                <div class="form-input-group">
                    <label class="form-label">Email Facturación</label>
                    <input type="email" id="fiscal-email" class="form-input">
                </div>

                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="fiscal-extranjero" class="w-5 h-5 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                            <label class="text-xs font-bold text-slate-500 uppercase">Cliente Extranjero</label>
                        </div>
                        <div class="flex-1">
                            <label class="form-label">QR (Opcional)</label>
                            <input id="fiscal-qr" class="form-input w-full" placeholder="Datos del QR">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t flex space-x-4">
                <button onclick="closeModal('modal-fiscal')" class="flex-1 px-8 py-4 rounded-2xl bg-slate-100 text-slate-500 font-black uppercase text-xs">Cancelar</button>
                <button onclick="guardarDatosFiscales()" class="flex-[2] px-8 py-4 rounded-2xl bg-purple-600 text-white font-black uppercase text-xs shadow-xl shadow-purple-100 active:scale-95 transition-all">
                    <i class="fas fa-save mr-2"></i>Guardar Información Fiscal
                </button>
            </div>
        </div>
    </div>

    <div id="modal-roomservice" class="fixed inset-0 bg-slate-900/90 hidden z-[200] items-center justify-center p-6 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white w-full max-w-6xl rounded-[3rem] p-10 flex flex-col shadow-2xl relative max-h-[95vh]">
            <button onclick="closeModal('modal-roomservice')" class="absolute right-8 top-8 w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm border border-slate-100"><i class="fas fa-times"></i></button>
            
            <div class="flex items-start justify-between mb-8 border-b border-slate-100 pb-6">
                <div>
                    <h3 class="text-4xl font-black italic uppercase text-orange-600 tracking-tighter leading-none">Punto de Venta / Room Service</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Venta Directa o Cargo a Habitación</p>
                </div>
                <div class="flex space-x-4">
                    <div class="bg-slate-900 text-white px-8 py-4 rounded-2xl flex flex-col items-center justify-center">
                        <span class="text-[9px] font-black uppercase text-slate-400">Total Pedido</span>
                        <span id="rs-total-display" class="text-2xl font-black italic text-orange-400">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-8 flex-1 overflow-hidden">
                <!-- Columna Izquierda: Búsqueda y Entrada Manual -->
                <div class="col-span-12 lg:col-span-7 flex flex-col space-y-6 overflow-y-auto pr-4">
                    
                    <!-- Selector de Habitación -->
                    <div class="bg-orange-50 p-6 rounded-[2rem] border-2 border-orange-100">
                        <label class="text-[10px] font-black text-orange-600 uppercase tracking-widest block mb-3 ml-2">Asignar a Habitación (Opcional)</label>
                        <select id="rs-room-selector" class="w-full bg-white border-2 border-orange-200 rounded-2xl px-6 py-4 text-sm font-black text-slate-700 outline-none focus:border-orange-500 transition-all shadow-sm">
                            <option value="">VENTA INDEPENDIENTE (MOSTRADOR)</option>
                        </select>
                    </div>

                    <!-- Búsqueda de Productos -->
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-orange-500 text-xl"><i class="fas fa-search"></i></span>
                        <input type="text" id="rs-product-search" oninput="searchRSProducts(this.value)" placeholder="BUSCAR PRODUCTO O SERVICIO..." class="w-full bg-white border-2 border-slate-100 rounded-[1.5rem] pl-16 pr-8 py-5 text-sm font-black uppercase shadow-sm focus:border-orange-500 outline-none transition-all">
                        <div id="rs-search-results" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto hidden"></div>
                    </div>

                    <!-- Entrada Manual -->
                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest italic">Entrada Manual</h5>
                            <i class="fas fa-keyboard text-slate-300"></i>
                        </div>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-7">
                                <input type="text" id="rs-manual-concept" placeholder="CONCEPTO (Ej. CENA ESPECIAL)" class="w-full bg-white border-2 border-slate-100 rounded-xl px-4 py-3 text-xs font-black uppercase outline-none focus:border-orange-500">
                            </div>
                            <div class="col-span-3">
                                <input type="number" id="rs-manual-price" placeholder="PRECIO" class="w-full bg-white border-2 border-slate-100 rounded-xl px-4 py-3 text-xs font-black outline-none focus:border-orange-500">
                            </div>
                            <div class="col-span-2">
                                <button onclick="addManualToCart()" class="w-full h-full bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-orange-600 transition-all active:scale-95">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Accesos Rápidos -->
                    <div class="grid grid-cols-3 gap-4" id="rs-quick-access">
                        <!-- Se llena dinámicamente -->
                    </div>
                </div>

                <!-- Columna Derecha: Carrito / Resumen -->
                <div class="col-span-12 lg:col-span-5 flex flex-col bg-slate-50 rounded-[3rem] border border-slate-200 p-8 overflow-hidden">
                    <div class="flex items-center justify-between mb-6 px-2">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Resumen de Orden</h4>
                        <span id="rs-item-count" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-[9px] font-black">0 ITEMS</span>
                    </div>

                    <div id="rs-cart-container" class="flex-1 overflow-y-auto space-y-3 mb-6 pr-2 custom-scrollbar">
                        <div class="flex flex-col items-center justify-center h-full opacity-20 py-10">
                            <i class="fas fa-shopping-basket text-6xl mb-4"></i>
                            <p class="text-xs font-black uppercase italic">El carrito está vacío</p>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-slate-200">
                        <div class="flex justify-between items-center px-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total a Pagar</span>
                            <span id="rs-total-footer" class="text-3xl font-black text-slate-800 italic tracking-tighter">$0.00</span>
                        </div>
                        <button onclick="finalizarPedidoRS()" class="w-full bg-orange-600 text-white py-6 rounded-2xl font-black uppercase text-xs shadow-xl shadow-orange-100 hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center space-x-3">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span>Cerrar Pedido e Imprimir Ticket</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE CANCELACIÓN INTELIGENTE -->
    <div id="modal-cancelacion"
        class="fixed inset-0 bg-slate-900/90 hidden z-[200] items-center justify-center p-6 backdrop-blur-md">
        <div
            class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl flex flex-col overflow-hidden border border-white/20">
            <div class="bg-rose-600 p-10 text-white relative">
                <h3 class="text-4xl font-black italic uppercase tracking-tighter">Cancelar Estadía</h3>
                <p class="text-[10px] font-black uppercase opacity-60 tracking-widest mt-2">Gestión de Reverso y Penalizaciones</p>
                <button onclick="closeModal('modal-cancelacion')" class="absolute right-8 top-8 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-all"><i class="fas fa-times"></i></button>
            </div>

            <div class="p-10 space-y-8 overflow-y-auto custom-scrollbar">
                <!-- Alerta de Pagos -->
                <div id="alert-pagos" class="hidden bg-amber-50 border-2 border-amber-100 p-6 rounded-2xl flex items-start space-x-4">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-2xl mt-1"></i>
                    <div>
                        <p class="text-xs font-black text-amber-800 uppercase italic">Atención: Pagos Detectados</p>
                        <p class="text-[10px] font-bold text-amber-600 uppercase">Existen abonos registrados. El sistema solo reversará los cargos de hospedaje. Deberá gestionar el reembolso manual de los pagos si aplica.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="form-input-group">
                        <label class="form-label">Tipo de Cancelación</label>
                        <select id="cancel-type" onchange="updateCancelPreview()" class="form-input font-black">
                            <option value="FULL">CANCELACIÓN TOTAL (REVERSO 100%)</option>
                            <option value="NO_SHOW">NO SHOW (COBRO TOTAL SIN REVERSO)</option>
                            <option value="EARLY">SALIDA ANTICIPADA (PRORRATEO)</option>
                        </select>
                    </div>

                    <div id="noches-container" class="form-input-group hidden">
                        <label class="form-label">Noches Utilizadas (de <span id="noches-totales">0</span>)</label>
                        <input type="number" id="noches-usadas" oninput="updateCancelPreview()" class="form-input font-black text-center" min="0" value="0">
                    </div>

                    <div class="form-input-group">
                        <label class="form-label">Penalización Extra ($)</label>
                        <input type="number" id="penalizacion" oninput="updateCancelPreview()" class="form-input font-black text-center" value="0">
                    </div>
                </div>

                <!-- Resumen de Reajuste -->
                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center text-[11px] font-black uppercase text-slate-400">
                        <span>Reverso de Hospedaje:</span>
                        <span id="preview-reverso" class="text-rose-500 font-black">-$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-black uppercase text-slate-400">
                        <span>Penalización:</span>
                        <span id="preview-penal" class="text-slate-800 font-black">$0.00</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-xs font-black uppercase text-slate-800 italic">Total Reajuste Final:</span>
                        <span id="preview-total" class="text-3xl font-black text-rose-600 italic tracking-tighter">$0.00</span>
                    </div>
                </div>

                <div id="reajuste-hint" class="text-[9px] font-bold text-slate-400 uppercase text-center italic">
                    Este movimiento quedará registrado en auditoría.
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button onclick="closeModal('modal-cancelacion')" class="bg-slate-100 text-slate-500 py-5 rounded-2xl font-black uppercase text-xs hover:bg-slate-200 transition-all">Regresar</button>
                    <button onclick="confirmCancelacionInteligente()" class="bg-rose-600 text-white py-5 rounded-2xl font-black uppercase text-xs shadow-xl shadow-rose-100 hover:bg-rose-700 active:scale-95 transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Aplicar Cancelación</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast"
        class="fixed bottom-10 right-10 bg-slate-900 text-white px-10 py-7 rounded-[2.5rem] shadow-2xl translate-y-72 transition-all duration-500 z-[300] border-l-[15px] border-blue-500 flex items-center space-x-10">
        <i class="fas fa-info-circle text-blue-400 text-4xl"></i>
        <p id="toast-message" class="font-black text-2xl italic uppercase">Mensaje</p>
    </div>

    <script>
        window.base_url = "<?= rtrim(base_url(), '/') ?>/";
        window.userRole = <?= session()->get('rol') ?? 0 ?>;
    </script>
   
 <script>
        const rooms = [];
        const basePrice = 500.00;
        let selectedRoomIdx = null;
        let selectedGuestEditIdx = -1;
        let selectedMoveSourceIdx = null;
        let selectedMoveTargetIdx = null;
        let currentFloor = 'PB';
        let tempGuests = [];
        let activePreviewMode = '';
        let optionsMenuCaller = false;
        let catalogoFormasPago = [];
        let catalogoTiposEstadia = [];
        let catalogoTiposID = [];
        let catalogoEstados = []; // 🔥 Catálogo dinámico de estados

        const gridCols = [
            { id: 'status', label: 'Estatus', w: '120px', filter: 'select', multi: true },
            { id: 'registro_id', label: 'ID Reg', w: '0px', filter: 'none', hidden: true },
            { id: 'id', label: 'Hab', w: '80px', filter: 'select', multi: true },
            { id: 'stay', label: 'Estadía', w: '100px', filter: 'select', multi: true },
            { id: 'days', label: 'Días', w: '70px', filter: 'range' },
            { id: 'people', label: 'Personas', w: '130px', filter: 'text' },
            { id: 'payment', label: 'Pago', w: '120px', filter: 'select', multi: true },
            { id: 'price', label: 'Precio', w: '110px', filter: 'range' },
            { id: 'reg', label: 'Registro', w: '120px', filter: 'select', multi: true },
            { id: 'titular', label: 'Huesped principal', w: '250px', filter: 'select', multi: true },
            { id: 'extra', label: 'Extra', w: '80px', filter: 'select', multi: true },
            { id: 'btn_e', label: '+', w: '60px', filter: 'none' },
            { id: 'h_ent', label: 'Entrada', w: '120px', filter: 'none' },
            { id: 'h_sal', label: 'Salida', w: '120px', filter: 'none' },
            { id: 'btn_s', label: '+', w: '60px', filter: 'none' },
            { id: 'acciones', label: 'CHECKOUT', w: '110px', filter: 'none' },
            { id: 'opt', label: 'Opciones', w: '110px', filter: 'none' }
        ];

        const activeFilters = {};
        let currentSort = { col: 'id', order: 'asc' };

        function toggleSort(colId) {
            // No ordenar columnas de acciones
            if (['btn_e', 'btn_s', 'opt'].includes(colId)) return;

            if (currentSort.col === colId) {
                currentSort.order = currentSort.order === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.col = colId;
                currentSort.order = 'asc';
            }
            renderGrid();
            updateSortUI();
        }

        function updateSortUI() {
            const ths = document.querySelectorAll('#grid-header-row th');
            gridCols.forEach((col, i) => {
                const th = ths[i];
                if (!th) return;
                const iconCont = th.querySelector('.sort-icon-container');
                if (iconCont) {
                    const isSortable = !['btn_e', 'btn_s', 'opt', 'acciones'].includes(col.id);
                    if (isSortable) {
                        if (currentSort.col === col.id) {
                            iconCont.innerHTML = currentSort.order === 'asc' ? '<i class="fas fa-sort-up text-blue-500"></i>' : '<i class="fas fa-sort-down text-blue-500"></i>';
                            th.classList.add('bg-blue-50/30');
                        } else {
                            iconCont.innerHTML = '<i class="fas fa-sort opacity-10"></i>';
                            th.classList.remove('bg-blue-50/30');
                        }
                    } else {
                        iconCont.innerHTML = '';
                        th.classList.remove('bg-blue-50/30');
                    }
                }
            });
        }
        function formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        }

        async function initData() {
            console.log("🚀 Iniciando carga de datos... URL:", base_url);
            try {
                // 🔥 Cargar Catálogos primero
                const [respHab, respEst] = await Promise.all([
                    fetch(base_url + 'reservacion/habitaciones'),
                    fetch(base_url + 'reservacion/estados-habitacion')
                ]).catch(err => {
                    console.error("🔥 Error de RED detectado:", err);
                    throw err;
                });

                if (respEst.ok) {
                    catalogoEstados = await respEst.json();
                } else {
                    console.error("❌ Error en estados-habitacion:", respEst.status);
                }

                if (!respHab.ok) {
                    console.error("❌ Error en habitaciones:", respHab.status);
                    throw new Error("HTTP Status: " + respHab.status);
                }
                const data = await respHab.json();
                console.log("📦 [DEBUG DATA] Datos crudos del servidor (primera hab):", data[0]);
                console.log("📦 Datos recibidos del servidor:", data);

                rooms.length = 0;
                data.forEach(item => {
                    let h_ent = '', f_ent = '', h_sal = '', f_sal = '';
                    
                    // Priorizar última entrada persistida
                    const rawEnt = item.ultima_entrada || item.hora_entrada_1;
                    if (rawEnt) {
                        const d = new Date(rawEnt);
                        h_ent = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                        f_ent = formatDate(d);
                    }

                    // Priorizar última salida persistida
                    const rawSal = item.ultima_salida || item.hora_salida_1;
                    if (rawSal) {
                        const d = new Date(rawSal);
                        h_sal = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                        f_sal = formatDate(d);
                    }

                    rooms.push({
                        id: parseInt(item.numero).toString().padStart(3, '0'),
                        status: item.status || 'X',
                        floor: item.piso || 'PB',
                        capacidad: parseInt(item.capacidad_hab) || 2,
                        precio_extra: parseFloat(item.precio_persona_extra) || 0,
                        tipoEstadia: item.tipo_estadia || '',
                        tipoEstadiaId: item.tipo_estadia || 1,
                        dias: parseInt(item.noches) || 0,
                        huespedes: item.huespedes || [],
                        formaPago: item.forma_pago || '',
                        precio: parseFloat(item.total) || parseFloat(item.precio_base) || 0,
                        precio_base: parseFloat(item.precio_base) || 0,
                        horaEntrada: h_ent,
                        fechaEntrada: f_ent,
                        horaSalida: h_sal,
                        fechaSalida: f_sal,
                        rawEntrada: item.hora_entrada_1 ? new Date(item.hora_entrada_1).getTime() : 0,
                        rawSalida: item.hora_salida_1 ? new Date(item.hora_salida_1).getTime() : 0,
                        shaded: !!item.sombreado,
                        adultos: parseInt(item.adultos) || 0,
                        ninos: parseInt(item.ninos) || 0,
                        ocupacion_total: parseInt(item.ocupacion_total) || 0,
                        extra: parseInt(item.num_personas_ext) || 0,
                        obs: item.observaciones || '',
                        registro_id: item.registro_id || null,
                        id_db: item.habitacion_id || null,
                        tipoHab: item.tipo_habitacion || '',
                        estado_registro: item.estado_registro_val || '',
                        services: item.services || [],
                        payments: item.payments || [],
                        incluir_en_reporte: !!parseInt(item.incluir_en_reporte),
                        
                        // 🔥 CAMPOS FISCALES
                        precio_reg: parseFloat(item.precio) || 0,
                        iva_reg: parseFloat(item.iva) || 0,
                        ish_reg: parseFloat(item.ish) || 0,
                        fecha_registro: item.fecha_registro || '---',
                        titular_full: item.nombre_huesped || '',
                        registro: item.registro || ''
                    });
                });
                console.log("📊 [DEBUG DATA] Total registros recibidos:", rooms.length);
                if (rooms.length > 0) {
                    console.log("📊 [DEBUG DATA] Primer registro:", rooms[0]);
                    console.log("📊 [DEBUG DATA] Valores únicos de 'registro':", [...new Set(data.map(i => i.registro))]);
                }

                buildHeader();
                renderGrid();
                console.log("✅ Grid actualizado");
            } catch (error) {
                console.error('❌ Error en initData:', error);
                showToast("Error al conectar con el servidor");
            }
        }


        function buildHeader() {
            const headerRow = document.getElementById('grid-header-row');
            headerRow.innerHTML = '';
            gridCols.forEach(col => {
                const th = document.createElement('th');
                th.className = `w-[${col.w}] header-filter text-center font-black uppercase text-[10px] px-4 py-4 transition-colors hover:bg-slate-50 ${col.hidden ? 'hidden' : ''}`;
                th.tabIndex = 0;
                
                let filterHtml = '';
                if (col.filter === 'text') {
                    filterHtml = `<div class="filter-dropdown"><p class="text-[9px] font-black uppercase mb-2 text-blue-600">Buscar en ${col.label}</p><input type="text" class="bg-slate-100 p-2 rounded text-xs w-full font-bold" oninput="updateFilter('${col.id}', this.value)" placeholder="..." onclick="event.stopPropagation()"></div>`;
                } else if (col.filter === 'select') {
                    if (col.multi) {
                        filterHtml = `
                            <div class="filter-dropdown p-4 min-w-[180px]">
                                <p class="text-[9px] font-black uppercase mb-3 text-blue-600 border-b border-blue-100 pb-2">Selección Múltiple</p>
                                <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                    ${getMultiOptionsFor(col.id)}
                                </div>
                            </div>
                        `;
                    } else {
                        filterHtml = `<div class="filter-dropdown"><p class="text-[9px] font-black uppercase mb-2 text-blue-600">Categoría</p><select class="bg-slate-100 p-2 rounded text-xs w-full font-bold" onchange="updateFilter('${col.id}', this.value)" onclick="event.stopPropagation()"><option value="">Todos</option>${getOptionsFor(col.id)}</select></div>`;
                    }
                } else if (col.filter === 'range') {
                    const current = activeFilters[col.id] || { min: '', max: '' };
                    filterHtml = `
                        <div class="filter-dropdown p-4 min-w-[200px]">
                            <p class="text-[9px] font-black uppercase mb-3 text-indigo-600 border-b border-indigo-50 pb-2">Rango de ${col.label}</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[8px] font-bold text-slate-400 mb-1 uppercase">Mínimo</p>
                                    <input type="number" 
                                        value="${current.min}"
                                        placeholder="0" 
                                        oninput="updateRangeFilter('${col.id}', 'min', this.value)"
                                        onclick="event.stopPropagation()"
                                        class="w-full bg-slate-50 border-none rounded-lg py-1.5 px-3 text-[10px] font-black text-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                                <div>
                                    <p class="text-[8px] font-bold text-slate-400 mb-1 uppercase">Máximo</p>
                                    <input type="number" 
                                        value="${current.max}"
                                        placeholder="999+" 
                                        oninput="updateRangeFilter('${col.id}', 'max', this.value)"
                                        onclick="event.stopPropagation()"
                                        class="w-full bg-slate-50 border-none rounded-lg py-1.5 px-3 text-[10px] font-black text-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Header content with Sort icon
                const isSortable = !['btn_e', 'btn_s', 'opt', 'acciones'].includes(col.id);
                th.innerHTML = `
                    <div class="flex items-center justify-center space-x-2">
                        <div class="flex items-center space-x-2 ${isSortable ? 'cursor-pointer hover:text-blue-500 transition-colors' : ''}" 
                             ${isSortable ? `onclick="event.stopPropagation(); toggleSort('${col.id}')"` : ''}>
                            <span class="sort-icon-container">
                                ${isSortable ? '<i class="fas fa-sort opacity-10"></i>' : ''}
                            </span>
                            <span>${col.label}</span>
                        </div>
                        ${col.filter !== 'none' ? '<i class="fas fa-filter text-[8px] opacity-40 ml-1"></i>' : ''}
                    </div>
                    ${filterHtml}
                `;
                headerRow.appendChild(th);
            });
            updateSortUI();
        }

        function getMultiOptionsFor(colId) {
            let html = `
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-2 border-b border-slate-50 pb-2 px-1">
                        <button onclick="selectAllMultiFilter('${colId}', true)" class="text-[8px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 transition-all">Todos</button>
                        <button onclick="selectAllMultiFilter('${colId}', false)" class="text-[8px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-all">Resetear</button>
                    </div>
                    <div class="relative px-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-300"></i>
                        <input type="text" 
                            placeholder="Buscar..." 
                            oninput="filterMultiOptions('${colId}', this.value)"
                            onclick="event.stopPropagation()"
                            class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-8 pr-3 text-[10px] font-black text-slate-700 focus:ring-2 focus:ring-indigo-100 placeholder-slate-300 transition-all">
                    </div>
                </div>
            `;
            const currentVals = activeFilters[colId] || [];

            if (colId === 'status') {
                catalogoEstados.forEach(e => {
                    const isChecked = currentVals.includes(e.codigo);
                    html += renderMultiOption(colId, e.codigo, e.nombre, isChecked);
                });
                return html;
            }

            if (colId === 'id') {
                // Obtener números únicos de habitación y ordenar
                const roomNums = [...new Set(rooms.map(r => r.id))].sort((a, b) => a.localeCompare(b));
                roomNums.forEach(num => {
                    const isChecked = currentVals.includes(num);
                    html += renderMultiOption(colId, num, num, isChecked);
                });
                return html;
            }

            if (colId === 'stay') {
                catalogoTiposEstadia.forEach(te => {
                    const isChecked = currentVals.includes(te.id.toString());
                    html += renderMultiOption(colId, te.id, `${te.codigo} (${te.nombre})`, isChecked);
                });
                return html;
            }

            if (colId === 'payment') {
                catalogoFormasPago.forEach(fp => {
                    const isChecked = currentVals.includes(fp.id.toString());
                    html += renderMultiOption(colId, fp.id, `${fp.codigo} (${fp.descripcion})`, isChecked);
                });
                return html;
            }

            if (colId === 'titular') {
                const names = [...new Set(rooms.map(r => {
                    const tit = r.huespedes.find(h => h.isTitular);
                    return tit ? `${tit.nombre} ${tit.apellido || tit.apellidos || ''}`.trim() : '';
                }))].filter(n => n !== '').sort();
                names.forEach(name => {
                    const isChecked = currentVals.includes(name);
                    html += renderMultiOption(colId, name, name, isChecked);
                });
                return html;
            }

            if (colId === 'extra') {
                const extras = [...new Set(rooms.map(r => Math.max(0, r.huespedes.length - r.capacidad).toString()))].sort((a,b) => a-b);
                extras.forEach(ex => {
                    const isChecked = currentVals.includes(ex);
                    html += renderMultiOption(colId, ex, ex, isChecked);
                });
                return html;
            }
            if (colId === 'reg') {
                const options = ['Con registro', 'Sin registro'];
                options.forEach(opt => {
                    const isChecked = currentVals.includes(opt);
                    html += renderMultiOption(colId, opt, opt, isChecked);
                });
                return html;
            }
            return '';
        }

        function renderMultiOption(colId, val, label, isChecked) {
            return `
                <label class="multi-opt-${colId} flex items-center space-x-2.5 cursor-pointer group py-1.5 px-2 rounded-lg transition-all ${isChecked ? 'bg-indigo-50/50' : 'hover:bg-slate-50'}" 
                    data-val="${val.toString().toLowerCase()}"
                    data-label="${label.toString().toLowerCase()}"
                    onclick="event.stopPropagation()">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" ${isChecked ? 'checked' : ''} 
                            onchange="toggleMultiFilter('${colId}', '${val}', this.checked)"
                            class="w-3.5 h-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-all">
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-tight ${isChecked ? 'text-indigo-600' : 'text-slate-500'} group-hover:text-indigo-600 transition-colors">${label}</span>
                </label>
            `;
        }

        function filterMultiOptions(colId, query) {
            const q = query.toLowerCase();
            const options = document.querySelectorAll(`.multi-opt-${colId}`);
            options.forEach(opt => {
                const label = opt.getAttribute('data-label') || '';
                const val = opt.getAttribute('data-val') || '';
                if (label.includes(q) || val.includes(q)) {
                    opt.classList.remove('hidden');
                } else {
                    opt.classList.add('hidden');
                }
            });
        }

        function updateRangeFilter(colId, type, val) {
            if (!activeFilters[colId]) activeFilters[colId] = { min: '', max: '' };
            activeFilters[colId][type] = val;
            renderGrid();
        }

        function selectAllMultiFilter(colId, select) {
            if (select) {
                if (colId === 'status') activeFilters[colId] = catalogoEstados.map(e => e.codigo);
                if (colId === 'id') activeFilters[colId] = [...new Set(rooms.map(r => r.id))];
                if (colId === 'stay') activeFilters[colId] = catalogoTiposEstadia.map(te => te.id.toString());
                if (colId === 'payment') activeFilters[colId] = catalogoFormasPago.map(fp => fp.id.toString());
                if (colId === 'titular') {
                    activeFilters[colId] = [...new Set(rooms.map(r => {
                        const tit = r.huespedes.find(h => h.isTitular);
                        return tit ? `${tit.nombre} ${tit.apellido || tit.apellidos || ''}`.trim() : '';
                    }))].filter(n => n !== '');
                }
                if (colId === 'extra') {
                    activeFilters[colId] = [...new Set(rooms.map(r => Math.max(0, r.huespedes.length - r.capacidad).toString()))];
                }
            } else {
                activeFilters[colId] = [];
            }
            buildHeader();
            renderGrid();
        }

        function toggleMultiFilter(colId, val, checked) {
            if (!activeFilters[colId]) activeFilters[colId] = [];
            if (checked) {
                if (!activeFilters[colId].includes(val)) activeFilters[colId].push(val);
            } else {
                activeFilters[colId] = activeFilters[colId].filter(v => v !== val);
            }
            renderGrid();
        }

        function getOptionsFor(colId) {
            if (colId === 'status') {
                let html = '';
                catalogoEstados.forEach(e => {
                    html += `<option value="${e.codigo}">${e.nombre}</option>`;
                });
                return html;
            }
            if (colId === 'stay') return `<option value="S">S (Hoy)</option><option value="SQ">SQ (Se queda)</option><option value="P">P (Pasajero)</option>`;
            if (colId === 'payment') return `<option value="E">Efectivo</option><option value="TC">Tarjeta</option>`;
            return '';
        }

        // 🔥 Función solicitada para cargar estados en selects específicos
        async function cargarEstadosHabitacion(selectId = 'estado_habitacion', selected = null) {
            try {
                const select = document.getElementById(selectId);
                if (!select) return;
                select.innerHTML = `<option value="">Cargando...</option>`;

                const response = await fetch(base_url + 'reservacion/estados-habitacion');
                const data = await response.json();

                if (!data || data.length === 0) {
                    select.innerHTML = `<option value="">Sin datos</option>`;
                    return;
                }

                let html = `<option value="">Seleccione</option>`;
                data.forEach(e => {
                    html += `<option value="${e.codigo}" ${selected == e.codigo ? 'selected' : ''}>${e.nombre}</option>`;
                });
                select.innerHTML = html;
                catalogoEstados = data; // Sincronizar catálogo global
            } catch (error) {
                console.error("Error cargando estados:", error);
            }
        }

        function updateFilter(colId, val) {
            activeFilters[colId] = val;
            renderGrid();
        }

        function applyGlobalSearch() { renderGrid(); }
        function applyTipoFilter() { renderGrid(); }

        function resetFilters() {
            Object.keys(activeFilters).forEach(k => delete activeFilters[k]);
            document.getElementById('global-search').value = '';
            document.getElementById('filter-tipo-hab').value = '';
            buildHeader();
            renderGrid();
            showToast("Filtros limpiados");
        }

        function renderGrid() {
            const tbody = document.getElementById('rooms-tbody'); tbody.innerHTML = '';
            const search = document.getElementById('global-search').value.toLowerCase();
            
            // 🔥 Total de habitaciones en la vista actual (piso o todos)
            const totalOnView = currentFloor === 'ALL' 
                ? rooms.length 
                : rooms.filter(r => r.floor === currentFloor).length;

            const filtered = rooms.filter(r => {
                // Si no estamos en "TODOS", filtrar por piso
                if (currentFloor !== 'ALL' && r.floor !== currentFloor) return false;

                const titular = (r.huespedes.find(h => h.isTitular)?.nombre || '') + ' ' + (r.huespedes.find(h => h.isTitular)?.apellido || r.huespedes.find(h => h.isTitular)?.apellidos || '');
                
                // Global Search
                if (search && !r.id.includes(search) && !titular.toLowerCase().includes(search)) return false;

                // Multi-select Status Filter
                if (Array.isArray(activeFilters.status) && activeFilters.status.length > 0) {
                    if (!activeFilters.status.includes(r.status)) return false;
                } else if (typeof activeFilters.status === 'string' && activeFilters.status !== '' && r.status !== activeFilters.status) {
                    return false;
                }

                // Multi-select Hab Filter
                if (Array.isArray(activeFilters.id) && activeFilters.id.length > 0) {
                    if (!activeFilters.id.includes(r.id)) return false;
                } else if (typeof activeFilters.id === 'string' && activeFilters.id !== '' && !r.id.includes(activeFilters.id)) {
                    return false;
                }

                // Multi-select Estadía Filter
                if (Array.isArray(activeFilters.stay) && activeFilters.stay.length > 0) {
                    if (!activeFilters.stay.includes(r.tipoEstadia.toString())) return false;
                } else if (typeof activeFilters.stay === 'string' && activeFilters.stay !== '' && r.tipoEstadia.toString() !== activeFilters.stay) {
                    return false;
                }
                if (activeFilters.titular) {
                    if (Array.isArray(activeFilters.titular) && activeFilters.titular.length > 0) {
                        if (!activeFilters.titular.includes(titular)) return false;
                    } else if (typeof activeFilters.titular === 'string' && activeFilters.titular !== '' && !titular.toLowerCase().includes(activeFilters.titular.toLowerCase())) {
                        return false;
                    }
                }

                if (activeFilters.payment) {
                    if (Array.isArray(activeFilters.payment) && activeFilters.payment.length > 0) {
                        if (!activeFilters.payment.includes(r.formaPago.toString())) return false;
                    } else if (typeof activeFilters.payment === 'string' && activeFilters.payment !== '' && r.formaPago !== activeFilters.payment) {
                        return false;
                    }
                }

                if (activeFilters.extra) {
                    const extraCount = Math.max(0, r.huespedes.length - r.capacidad).toString();
                    if (Array.isArray(activeFilters.extra) && activeFilters.extra.length > 0) {
                        if (!activeFilters.extra.includes(extraCount)) return false;
                    } else if (typeof activeFilters.extra === 'string' && activeFilters.extra !== '' && extraCount !== activeFilters.extra) {
                        return false;
                    }
                }

                // Range Filters for Days and Price
                if (activeFilters.days) {
                    const val = parseInt(r.dias) || 0;
                    const { min, max } = activeFilters.days;
                    if (min !== '' && val < parseInt(min)) return false;
                    if (max !== '' && val > parseInt(max)) return false;
                }

                if (activeFilters.price) {
                    const val = parseFloat(r.precio) || 0;
                    const { min, max } = activeFilters.price;
                    if (min !== '' && val < parseFloat(min)) return false;
                    if (max !== '' && val > parseFloat(max)) return false;
                }
                
                // 🔥 Filtro por Registro (Con registro / Sin registro)
                if (Array.isArray(activeFilters.reg) && activeFilters.reg.length > 0) {
                    const rStatus = (r.registro || '').trim().toLowerCase();
                    const match = activeFilters.reg.some(f => f.trim().toLowerCase() === rStatus);
                    if (!match) return false;
                } else if (typeof activeFilters.reg === 'string' && activeFilters.reg !== '') {
                    const rStatus = (r.registro || '').trim().toLowerCase();
                    if (rStatus !== activeFilters.reg.trim().toLowerCase()) return false;
                }

                const tipoFiltro = document.getElementById('filter-tipo-hab').value;
                if (tipoFiltro && r.tipoHab !== tipoFiltro) return false;
                return true;
            });

            // 🔥 Aplicar Ordenamiento
            filtered.sort((a, b) => {
                let valA, valB;
                
                switch(currentSort.col) {
                    case 'id': valA = a.id; valB = b.id; break;
                    case 'status': valA = a.status; valB = b.status; break;
                    case 'stay': valA = a.tipoEstadia; valB = b.tipoEstadia; break;
                    case 'days': valA = a.dias; valB = b.dias; break;
                    case 'people': valA = a.ocupacion_total; valB = b.ocupacion_total; break;
                    case 'reg': 
                        valA = a.registro;
                        valB = b.registro;
                        break;
                    case 'payment': valA = a.formaPago; valB = b.formaPago; break;
                    case 'price': valA = a.precio; valB = b.precio; break;
                    case 'h_ent': valA = a.rawEntrada; valB = b.rawEntrada; break;
                    case 'h_sal': valA = a.rawSalida; valB = b.rawSalida; break;
                    case 'titular': 
                        const titA = a.huespedes.find(h => h.isTitular) || { nombre: '', apellido: '', apellidos: '' };
                        valA = (titA.nombre + ' ' + (titA.apellido || titA.apellidos || '')).trim();
                        const titB = b.huespedes.find(h => h.isTitular) || { nombre: '', apellido: '', apellidos: '' };
                        valB = (titB.nombre + ' ' + (titB.apellido || titB.apellidos || '')).trim();
                        break;
                    case 'extra': valA = a.extra; valB = b.extra; break;
                    default: valA = a[currentSort.col]; valB = b[currentSort.col];
                }

                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return currentSort.order === 'asc' ? -1 : 1;
                if (valA > valB) return currentSort.order === 'asc' ? 1 : -1;
                return 0;
            });

            filtered.forEach(r => {
                const realIdx = rooms.indexOf(r);
                const titular = r.huespedes.find(h => h.isTitular) || { nombre: '', apellido: '', apellidos: '' };
                const isActive = r.huespedes.length > 0;
                const isCheckoutReg = (r.estado_registro || '').toUpperCase().trim() === 'CHECKOUT';
                const isCheckinReg = ['CHECKIN', 'CHECK-IN'].includes((r.estado_registro || '').toUpperCase().trim());
                
                if (realIdx === 0 || isCheckoutReg) {
                    console.log(`🎨 [COLOR DEBUG] Hab: ${r.id} | Estado: "${r.estado_registro}" | isCheckout: ${isCheckoutReg} | isCheckin: ${isCheckinReg}`);
                }

                // 🔥 Bloqueo para Mantenimiento (M) o Planta (P) o Sucia (S) o VAP
                const isSuciaOrVap = r.status === 'S' || r.status === 'VAP';
                const isBlocked = r.status === 'M' || r.status === 'P' || isSuciaOrVap;
                
                // Si es ADMIN (rol 1), el Checkout NO lo deshabilita. Para otros roles sí.
                const isDisabledByStatus = (isCheckoutReg && window.userRole !== 1) || isBlocked;
                
                const disabledAttr = isDisabledByStatus ? 'disabled' : '';
                const pointerClass = isDisabledByStatus ? 'opacity-20 pointer-events-none' : '';

                // 🔥 Bloqueo específico para Registro (No permite registrar en S o VAP si no hay registro previo)
                const isRegDisabled = isSuciaOrVap && !isCheckinReg && !isCheckoutReg;
                const regDisabledAttr = (isDisabledByStatus || isRegDisabled) ? 'disabled' : '';
                const regPointerClass = (isDisabledByStatus || isRegDisabled) ? 'opacity-20 pointer-events-none' : '';

                // 🔥 Restricción para botones de Entrada/Salida (Solo en CHECKIN)
                const isNotCheckin = !isCheckinReg || isBlocked;
                const stampDisabledAttr = isNotCheckin ? 'disabled' : '';
                const stampPointerClass = isNotCheckin ? 'opacity-20 pointer-events-none' : 'cursor-pointer hover:scale-110';

                const tr = document.createElement('tr');
                tr.className = `status-${r.status} transition-all hover:bg-slate-50 ${r.incluir_en_reporte ? 'row-shaded' : ''}`;
                tr.innerHTML = `
                    <td class="font-black p-0">
                        <div class="status-icon-${r.status} flex items-center justify-center h-full w-full min-h-[48px]">
                            ${renderSelectStatus(r, realIdx)}
                        </div>
                    </td>
                    <td class="hidden">${r.registro_id || ''}</td>
                    <td class="text-center font-black text-lg text-slate-800">${r.id}</td>
                    <td class="text-center">
                        ${renderSelectEstadia(r.tipoEstadia, realIdx, isCheckoutReg || isBlocked)}
                    </td>
                    <td class="text-center font-black">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="changeDays(${realIdx}, -1)" ${disabledAttr} class="w-6 h-6 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 active:scale-90 transition-all">
                                <i class="fas fa-minus text-[8px]"></i>
                            </button>
                            <span class="w-6 text-center font-black text-xs text-slate-700">${r.dias}</span>
                            <button onclick="changeDays(${realIdx}, 1)" ${disabledAttr} class="w-6 h-6 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 active:scale-90 transition-all">
                                <i class="fas fa-plus text-[8px]"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-center font-black">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="changePeople(${realIdx}, -1)" ${disabledAttr} class="w-6 h-6 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 active:scale-90 transition-all">
                                <i class="fas fa-minus text-[8px]"></i>
                            </button>
                            <div id="pers-val-${realIdx}" class="flex items-center justify-center space-x-1 min-w-[50px]">
                                ${r.adultos > 0 ? `<span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black shadow-sm" title="Adultos">${r.adultos}A</span>` : ''}
                                ${r.ninos > 0 ? `<span class="bg-orange-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-black shadow-sm" title="Niños">${r.ninos}N</span>` : ''}
                                ${(r.adultos === 0 && r.ninos === 0) ? `<span class="text-slate-300 font-black text-[10px]">0</span>` : ''}
                            </div>
                            <button onclick="changePeople(${realIdx}, 1)" ${disabledAttr} class="w-6 h-6 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 active:scale-90 transition-all">
                                <i class="fas fa-plus text-[8px]"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center space-x-4">
                            ${renderSelectFormaPago(r.formaPago, realIdx, isCheckoutReg || isBlocked)}
                            <input type="checkbox" 
                                onchange="toggleReporteYShade(${realIdx}, this.checked)" 
                                ${r.incluir_en_reporte ? 'checked' : ''} 
                                ${disabledAttr}
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 ${isBlocked ? 'opacity-20 cursor-not-allowed' : 'cursor-pointer'}" 
                                title="Marcar como Pagado / Incluir en Reporte">
                        </div>
                    </td>
                    <td class="text-right font-black text-blue-700" ${isBlocked ? '' : `ondblclick="editPrice(event, ${realIdx})"`} title="${isBlocked ? '' : 'Doble clic para editar'}">
                        <span id="price-val-${realIdx}" class="${isBlocked ? 'text-slate-300' : 'cursor-pointer hover:text-blue-600'} transition-colors">
                            ${isCheckinReg ? r.precio.toFixed(2) : '0.00'}
                        </span>
                    </td>
                    <td class="text-center">
                        <button onclick="openRegister(${realIdx})" ${regDisabledAttr} 
                            class="${isCheckoutReg ? 'bg-rose-500 shadow-rose-200' : (isCheckinReg ? 'bg-emerald-500 shadow-emerald-200' : 'bg-blue-600 shadow-blue-200')} text-white text-[10px] px-4 py-2 rounded-xl font-black shadow-md active:scale-95 transition-all ${regPointerClass}">
                            ${isCheckoutReg ? 'CHECKOUT' : (isCheckinReg ? 'VER REGISTRO' : 'REGISTRAR')}
                        </button>
                    </td>
                    <td class="font-black uppercase truncate text-slate-700 max-w-[200px]">${titular.nombre} ${titular.apellido || titular.apellidos || ''}</td>
                    <td class="text-center font-black text-sm ${r.extra > 0 ? 'text-orange-600' : 'text-slate-300'}">${r.extra}</td>
                    <td class="text-center">
                        <button onclick="stampTime(${realIdx}, 'entrada')" ${stampDisabledAttr} class="text-blue-500 ${stampPointerClass} transition-all"><i class="fas fa-plus-circle"></i></button>
                    </td>
                    <td class="text-[10px] font-bold leading-tight text-slate-400">
                        ${r.horaEntrada ? `<div class="font-black text-slate-700">${r.horaEntrada} ${r.fechaEntrada}</div>` : '-'}
                    </td>
                    <td class="text-[10px] font-bold leading-tight text-slate-400">
                        ${r.horaSalida ? `<div class="font-black text-slate-700">${r.horaSalida} ${r.fechaSalida}</div>` : '-'}
                    </td>
                    <td class="text-center">
                        <button onclick="stampTime(${realIdx}, 'salida')" ${stampDisabledAttr} class="text-rose-500 ${stampPointerClass} transition-all"><i class="fas fa-plus-circle"></i></button>
                    </td>
                    <td class="text-center">
                        ${r.estado_registro === 'CHECKIN' ? `
                            <button onclick="hacerCheckout(${r.registro_id})" 
                                class="bg-rose-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-rose-100 active:scale-95 transition-all">
                                Checkout
                            </button>
                        ` : '-'}
                    </td>
                    <td class="text-center">
                        <button onclick="openOptionsMenu(${realIdx})" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase ${((!isActive && r.status !== 'CHECKOUT') || isBlocked) ? 'opacity-20 pointer-events-none' : ''}">Opciones</button>
                    </td>
                `; tbody.appendChild(tr);
            });
            
            // 🔥 Actualizar contadores en el footer
            document.getElementById('count-filtered').textContent = filtered.length;
            document.getElementById('count-total').textContent = totalOnView;
        }

        function renderSelectStatus(r, realIdx) {
            const hasTitular = r.huespedes && r.huespedes.some(h => h.isTitular);
            const isSucia = r.status === 'S';
            const shouldBlock = isSucia && hasTitular;

            let html = `<select onchange="updateStatus(${realIdx}, this.value)" ${shouldBlock ? 'disabled' : ''} class="bg-transparent font-black text-[11px] uppercase outline-none cursor-pointer text-center min-w-[70px] ${shouldBlock ? 'opacity-50 cursor-not-allowed' : ''}">`;
            catalogoEstados.forEach(e => {
                html += `<option value="${e.codigo}" ${r.status === e.codigo ? 'selected' : ''}>${e.nombre}</option>`;
            });
            html += `</select>`;
            return html;
        }

        async function updateStatus(idx, val) { 
            rooms[idx].status = val; 
            renderGrid();
            
            // Mapeo inverso de Código -> ID (Sincronizado con BD)
            const mapId = { 'S': 1, 'X': 2, 'M': 3, 'P': 4, 'VAP': 5, 'R': 9 };
            const estadoId = mapId[val] || 1;
            
            try {
                await actualizarEstadoHabitacionAsync(rooms[idx].id, estadoId);
                showToast("Estado actualizado en BD");
            } catch (err) {
                console.error(err);
                showToast("Error al actualizar estado");
            }
        }
        async function updateStay(idx, val) { 
            rooms[idx].tipoEstadia = val; 
            if(val !== '' && rooms[idx].dias === 0) rooms[idx].dias = 1;
            await recalculateRoomPrice(idx);
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }
        async function updateDays(idx, val) { 
            rooms[idx].dias = parseInt(val) || 0; 
            await recalculateRoomPrice(idx);
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }

        async function changeDays(idx, delta) {
            const r = rooms[idx];
            if (!r) return;
            r.dias = Math.max(0, r.dias + delta);
            await recalculateRoomPrice(idx);
            renderGrid();
            await sincronizarFilaAsync(idx);
        }

        async function changePeople(idx, delta) {
            const r = rooms[idx];
            if (!r) return;
            
            // Incrementamos el total
            const newTotal = Math.max(1, (r.ocupacion_total || 1) + delta);
            r.ocupacion_total = newTotal;

            // Ajustamos adultos (es el campo que se sincroniza principalmente)
            // adultos = total - niños - extras
            r.adultos = Math.max(1, r.ocupacion_total - (r.ninos || 0) - (r.extra || 0));
            
            await recalculateRoomPrice(idx);
            renderGrid();
            await sincronizarFilaAsync(idx);
        }

        async function updatePeople(idx, val) {
            const r = rooms[idx];
            if (!r.registro_id) return;

            rooms[idx].ocupacion_total = val;
            const span = document.getElementById(`pers-val-${idx}`);
            if (span) span.textContent = val;

            try {
                const fd = new FormData();
                fd.append('registro_id', r.registro_id);
                fd.append('campo', 'ocupacion_total');
                fd.append('valor', val);
                const resp = await fetch(base_url + 'reservacion/actualizar-campo', { method: 'POST', body: fd });
                const res = await resp.json();
                if (res.success) showToast('Personas actualizado'); else showToast('Error al guardar');
            } catch (err) { console.error(err); showToast('Error de conexión'); }
        }

        // ✏️ EDICIÓN INLINE DE PRECIO
        function editPrice(e, idx) {
            const r = rooms[idx];
            if (!r.registro_id) return;
            const td = e.currentTarget;
            const span = td.querySelector('span');
            if (!span || td.querySelector('input')) return;

            const currentVal = r.precio || 0;
            span.classList.add('hidden');

            const input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.value = currentVal.toFixed(2);
            input.className = 'w-24 text-right bg-blue-50 border-2 border-blue-400 rounded-lg font-black text-blue-700 outline-none text-sm p-1';

            const save = async () => {
                const newVal = parseFloat(input.value);
                if (!isNaN(newVal)) {
                    await updatePrice(idx, newVal);
                }
                input.remove();
                span.classList.remove('hidden');
            };

            input.addEventListener('blur', save);
            input.addEventListener('keydown', (ev) => {
                if (ev.key === 'Enter' || ev.key === 'Tab') { ev.preventDefault(); save(); }
                if (ev.key === 'Escape') { input.remove(); span.classList.remove('hidden'); }
            });

            td.appendChild(input);
            input.focus();
            input.select();
        }

        async function updatePayment(idx, val) { 
            rooms[idx].formaPago = val; 
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }
        async function updatePrice(idx, val) {
            rooms[idx].precio = parseFloat(val) || 0;
            const span = document.getElementById(`price-val-${idx}`);
            if (span) span.textContent = rooms[idx].precio.toFixed(2);
            await sincronizarFilaAsync(idx);
        }

        async function sincronizarFilaAsync(idx) {
            const r = rooms[idx];
            if (!r.registro_id) {
                console.warn(`⚠️ Omitiendo sincronización: La habitación ${r.id} no tiene registro_id.`);
                return;
            }
            try {
                const payload = {
                    registro_id: r.registro_id,
                    noches: r.dias,
                    precio: r.precio,
                    tipo_estadia_id: r.tipoEstadia || '',
                    forma_pago_id: r.formaPago || '',
                    adultos: r.adultos,
                    ninos: r.ninos
                };

                console.log("📤 [DEBUG SYNC] Enviando JSON:", payload);

                const response = await fetch(base_url + "reservacion/actualizar-campo", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (!data.success) throw new Error(data.msg || "Error desconocido");

                // 🔥 Sincronizar cálculos del servidor con el objeto local para que el ticket sea correcto
                if (data.data) {
                    if (data.data.precio !== undefined)      r.precio_reg  = data.data.precio;
                    if (data.data.iva !== undefined)         r.iva_reg     = data.data.iva;
                    if (data.data.ish !== undefined)         r.ish_reg     = data.data.ish;
                    if (data.data.total !== undefined)       r.precio      = data.data.total;
                    if (data.data.precio_base !== undefined) r.precio_base = data.data.precio_base;
                }

                showToast("✔ Sincronizado en BD");
            } catch (e) {
                console.error(e);
                showToast("❌ Error al sincronizar");
            }
        }
        function toggleShade(idx, checked) { rooms[idx].shaded = checked; renderGrid(); }
        async function stampTime(idx, type) {
            const now = new Date();
            const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            const date = formatDate(now);
            
            if (type === 'entrada') {
                rooms[idx].horaEntrada = time;
                rooms[idx].fechaEntrada = date;
                showToast("✔ Entrada registrada: " + time);
                const payload = {
                    registro_id: rooms[idx].registro_id,
                    nombre_huesped: rooms[idx].titular_full,
                    tipo: 'TEMPORAL'
                };
                console.log("🚀 [DEBUG ENTRADA] Payload:", payload);
                try {
                    await fetch(base_url + "reservacion/registrar-entrada", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(payload)
                    });
                } catch (e) {
                    console.error("Error al persistir entrada:", e);
                }
            } else {
                rooms[idx].horaSalida = time;
                rooms[idx].fechaSalida = date;
                showToast("✔ Salida registrada: " + time);
                const payload = {
                    registro_id: rooms[idx].registro_id,
                    nombre_huesped: rooms[idx].titular_full,
                    tipo: 'TEMPORAL'
                };
                console.log("🚀 [DEBUG SALIDA] Payload:", payload);
                // 🔥 Registrar salida temporal en BD
                try {
                    await fetch(base_url + "reservacion/registrar-salida", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(payload)
                    });
                } catch (e) {
                    console.error("Error al registrar salida temporal:", e);
                }
            }
            renderGrid();
        }

        /* --- NAVEGACIÓN MODAL INTELIGENTE --- */
        function openSubModal(modalId) {
            const el = document.getElementById(modalId);
            if (!el) return;

            const isIndependent = ['modal-cambio', 'modal-roomservice'].includes(modalId);
            if (selectedRoomIdx === null && !isIndependent) {
                showToast("SELECCIONE UNA HABITACIÓN PRIMERO");
                return;
            }

            optionsMenuCaller = true;
            const menu = document.getElementById('modal-options-menu');
            if (menu) menu.classList.remove('modal-active');
            
            // Soporte para ambos sistemas de visibilidad
            el.classList.remove('hidden');
            el.classList.add('flex', 'modal-active');

            if (modalId === 'modal-register-sub') openModalRegistry();
            else if (modalId === 'modal-estado-cuenta') showEstadoCuentaModal();
            else if (modalId === 'modal-roomservice') openModalRoomService();
            else if (modalId === 'modal-pagos') openModalPagos();
            else if (modalId === 'modal-cambio') prepareMoveModal();
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;

            el.classList.add('hidden');
            el.classList.remove('flex');
            el.classList.remove('modal-active');

            if (optionsMenuCaller && (id !== 'modal-options-menu')) {
                const noReopen = ['modal-cambio', 'modal-roomservice'].includes(id);
                optionsMenuCaller = false;
                if (selectedRoomIdx !== null && !noReopen) openOptionsMenu(selectedRoomIdx);
            }
            renderGrid();
        }

        /* --- MÓDULO DE EXPEDIENTE --- */
        function openRegister(idx) {
            selectedRoomIdx = idx;
            const r = rooms[idx];
            tempGuests = [...r.huespedes];
            document.getElementById('reg-room-title').textContent = `HABITACIÓN ${r.id} (${r.tipoHab})`;
            updateOccupancyCounter();
            renderGuestList();
            showView('reg-list');
            document.getElementById('modal-register').classList.add('modal-active');
            
            // Configurar botón Checkout Footer (Solo si hay titular y registro)
            const btnCheckout = document.getElementById('btn-checkout-footer');
            const hasTitular = tempGuests.some(g => g.isTitular);
            if (r.registro_id && hasTitular) {
                btnCheckout.classList.remove('hidden');
                window.currentRegistroId = r.registro_id; 
            } else {
                btnCheckout.classList.add('hidden');
            }

            const isCheckout = r.status === 'CHECKOUT';
            const btnNuevo = document.getElementById('btn-nuevo-huesped');
            const btnSincronizar = document.getElementById('btn-sincronizar');
            
            console.log("🧐 [DEBUG] Habitación:", r.id, "Estado Registro:", r.estado_registro);

            if (isCheckout) {
                if (btnNuevo) btnNuevo.classList.add('hidden');
                if (btnSincronizar) btnSincronizar.classList.add('hidden');
            } else {
                if (btnNuevo) btnNuevo.classList.remove('hidden');
                if (btnSincronizar) {
                    btnSincronizar.classList.remove('hidden');
                    
                    // 🔥 Bloqueo Robusto (Insensible a mayúsculas/espacios)
                    const statusReg = (r.estado_registro || '').toUpperCase().trim();
                    
                    if (statusReg === 'CHECKIN' || statusReg === 'CHECK-IN') {
                        btnSincronizar.disabled = true;
                        btnSincronizar.classList.add('opacity-50', 'cursor-not-allowed');
                        btnSincronizar.innerHTML = '<i class="fas fa-check-double text-lg"></i><span>Checkin Realizado</span>';
                        console.log("🚫 Botón Checkin BLOQUEADO para hab", r.id);
                    } else {
                        btnSincronizar.disabled = false;
                        btnSincronizar.classList.remove('opacity-50', 'cursor-not-allowed');
                        btnSincronizar.innerHTML = '<i class="fas fa-check-circle text-lg"></i><span>Checkin</span>';
                        console.log("✅ Botón Checkin HABILITADO para hab", r.id);
                    }
                }
            }

            setTimeout(initSignaturePad, 500);

            // 🔥 El botón cancelar solo se ve si no es Checkout y hay registro
            const btnCancelReg = document.getElementById('btn-cancelar-registro');
            if (btnCancelReg) {
                if (!isCheckout && r.registro_id) btnCancelReg.classList.remove('hidden');
                else btnCancelReg.classList.add('hidden');
            }
        }

        async function cancelarRegistroActual() {
            cancelarEstadia();
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);
        }

        const ui = {
            set: (id, val) => {
                const el = document.getElementById(id);
                if (el) {
                    if (el.tagName === 'INPUT' || el.tagName === 'SELECT') el.value = val;
                    else el.textContent = val;
                }
            }
        };

        function cancelarEstadia() {
            if (selectedRoomIdx === null) return;
            const r = rooms[selectedRoomIdx];
            
            // 🔥 Mapear datos para que las funciones del usuario funcionen
            window.DATA = {
                stay: {
                    arrival: r.rawEntrada, // Timestamp
                    departure: r.rawSalida // Timestamp
                },
                consumos: r.services || [],
                pagos_realizados: r.payments || []
            };

            ui.set(
                'reajuste-hint',
                "CANCELACIÓN TOTAL: Se revertirán todos los cargos y la habitación será liberada. Este movimiento quedará registrado en auditoría."
            );

            openCancelacionModal();
        }

        function updateCancelPreview() {
            const tipo = document.getElementById('cancel-type').value;
            const nochesUsadas = Number(document.getElementById('noches-usadas').value || 0);
            const penal = Number(document.getElementById('penalizacion').value || 0);

            // 🔥 detectar noches
            const f1 = new Date(DATA.stay.arrival);
            const f2 = new Date(DATA.stay.departure);

            const nochesTotales = Math.max(1, Math.round((f2 - f1) / (1000 * 60 * 60 * 24)));

            let reverso = 0;

            DATA.consumos.forEach(c => {
                if (c.tipo !== 'Hospedaje' && c.tipo !== 'Persona Extra') return;
                const monto = Number(c.monto || 0);

                if (tipo === 'NO_SHOW') {
                    // No hay reverso
                }
                else if (tipo === 'EARLY') {
                    const noUsadas = Math.max(0, nochesTotales - nochesUsadas);
                    const porNoche = monto / nochesTotales;
                    reverso += porNoche * noUsadas;
                }
                else {
                    reverso += monto;
                }
            });

            const total = (-reverso) + penal;

            // UI
            ui.set('preview-reverso', formatMoney(-reverso));
            ui.set('preview-penal', formatMoney(penal));
            ui.set('preview-total', formatMoney(total));

            // mostrar noches si aplica
            document.getElementById('noches-container').classList.toggle(
                'hidden',
                tipo !== 'EARLY'
            );
        }

        async function confirmCancelacionInteligente() {
            try {
                const tipo = document.getElementById('cancel-type').value;
                const noches = Number(document.getElementById('noches-usadas').value || 0);
                const penal = Number(document.getElementById('penalizacion').value || 0);
                const r = rooms[selectedRoomIdx];

                const resp = await fetch(base_url + "registro/cancelar-inteligente", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: r.registro_id,
                        tipo_cancelacion: tipo,
                        noches_usadas: noches,
                        penalizacion: penal,
                        motivo: "Cancelación desde UI"
                    })
                });

                const data = await resp.json();
                if (!data.ok) throw new Error(data.msg);

                showToast("Cancelación aplicada correctamente");
                closeModal('modal-cancelacion');
                closeModal('modal-register');
                initData(); // 🔄 refresh

            } catch (e) {
                alert(e.message);
            }
        }

        function openCancelacionModal() {
            const modal = document.getElementById('modal-cancelacion');
            modal.classList.add('modal-active');

            // 🔥 noches totales
            if (DATA?.stay?.arrival && DATA?.stay?.departure) {
                const f1 = new Date(DATA.stay.arrival);
                const f2 = new Date(DATA.stay.departure);
                const noches = Math.max(1, Math.round((f2 - f1) / (1000 * 60 * 60 * 24)));

                ui.set('noches-totales', noches);
                ui.set('noches-usadas', Array.from(modal.querySelectorAll('#noches-usadas')).length > 0 ? noches : noches);
                
                // Asegurar que el input de noches usadas se setee
                const nInput = document.getElementById('noches-usadas');
                if(nInput) nInput.value = noches;
            }

            // 🔥 detectar pagos
            if (DATA?.pagos_realizados?.length > 0) {
                document.getElementById('alert-pagos').classList.remove('hidden');
            } else {
                document.getElementById('alert-pagos').classList.add('hidden');
            }

            setTimeout(updateCancelPreview, 80);
        }

        function updateOccupancyCounter(editingIdx = null) {
            if (selectedRoomIdx === null) return;
            const r = rooms[selectedRoomIdx];
            const currentTotal = tempGuests.length;
            const max = parseInt(r.capacidad) || 2;
            
            // 1. Contador en la lista (Vista General)
            const elList = document.getElementById('occupancy-counter');
            if (elList) {
                elList.textContent = `${currentTotal} / ${max}`;
                elList.className = currentTotal > max ? 'text-2xl font-black text-rose-400 italic' : 'text-2xl font-black text-white italic';
            }

            // 2. Contador en el formulario (Captura Individual)
            const elForm = document.getElementById('form-person-counter');
            if (elForm) {
                let personNum = (editingIdx !== null && editingIdx > -1) ? (editingIdx + 1) : (currentTotal + 1);
                
                if (personNum <= max) {
                    elForm.textContent = `${personNum} de ${max}`;
                    elForm.className = "text-2xl font-black text-white italic tracking-tighter uppercase";
                } else {
                    elForm.textContent = `Extra (+${personNum - max})`;
                    elForm.className = "text-2xl font-black text-orange-400 italic tracking-tighter uppercase";
                }
            }
        }

        function backToGuestList() {
            updateOccupancyCounter();
            renderGuestList();
            showView('reg-list');
        }

        function goToForm(idx = -1) {
            selectedGuestEditIdx = idx;
            updateOccupancyCounter(idx); // 🔥 Actualizar contador específico
            sigDirty = false;
            
            // Reset de campos básicos
            const inputs = document.querySelectorAll('#reg-view-form input, #reg-view-form select, #reg-view-form textarea');
            inputs.forEach(i => i.value = '');
            
            document.getElementById('f-gender').value = 'Masculino';
            document.getElementById('f-id-type').value = '1';
            document.getElementById('f-parentesco').value = 'Huésped Principal';
            document.getElementById('f-empresa').value = '';
            document.getElementById('f-is-minor').checked = false;
            document.getElementById('f-responsable').value = '';
            
            // 🔥 Rol por default: Primero es Titular, los demás Acompañantes
            const isFirst = tempGuests.length === 0;
            document.getElementById('f-is-titular').value = isFirst ? 'true' : 'false';
            document.getElementById('f-parentesco').value = isFirst ? 'Huésped Principal' : 'Otro';

            toggleMinorFields(false);

            // Reset de Media
            document.getElementById('box-client').innerHTML = '<i class="fas fa-user text-6xl opacity-10"></i><span class="text-[9px] font-black uppercase text-slate-400 mt-4">Video de Rostro</span>';
            document.getElementById('box-id').innerHTML = '<div id="scan-line" class="scan-line" style="display:none"></div><i class="fas fa-id-badge text-7xl opacity-10"></i><span class="text-[9px] font-black uppercase text-slate-400 mt-4">Scan Documento</span>';
            delete document.getElementById('box-client').dataset.blob;
            delete document.getElementById('box-id').dataset.blob;

            if (idx > -1) {
                const g = tempGuests[idx];
                document.getElementById('f-name').value = g.nombre || '';
                document.getElementById('f-apellidos').value = g.apellido || g.apellidos || '';
                document.getElementById('f-tel').value = g.telefono || '';
                document.getElementById('f-mail').value = g.email || '';
                document.getElementById('f-id-type').value = g.tipo_identificacion_id || '1';
                document.getElementById('f-id-num').value = g.numero_identificacion || '';
                document.getElementById('f-address').value = g.direccion || '';
                document.getElementById('f-city').value = g.ciudad || '';
                document.getElementById('f-state').value = g.estado || '';
                document.getElementById('f-cp').value = g.codigo_postal || '';
                document.getElementById('f-birth').value = g.fecha_nacimiento || '';
                document.getElementById('f-gender').value = g.genero === 'F' ? 'Femenino' : 'Masculino';
                document.getElementById('f-parentesco').value = g.parentesco || 'Otro';
                document.getElementById('f-empresa').value = g.empresa || '';
                document.getElementById('f-is-minor').checked = g.es_menor == 1;
                document.getElementById('f-responsable').value = g.Responsable_menor || '';
                document.getElementById('f-is-titular').value = g.isTitular ? 'true' : 'false';
                toggleMinorFields(g.es_menor == 1);

                document.getElementById('f-nat').value = g.nacionalidad || 'MEXICANA';
                document.getElementById('f-placas').value = g.placas || '';
                if (g.fotografia) document.getElementById('box-client').innerHTML = `<img src="${g.fotografia.startsWith('http') ? g.fotografia : base_url + 'uploads/fotos/' + g.fotografia}" class="w-full h-full object-cover rounded-2xl shadow-inner">`;
                if (g.identificacion) document.getElementById('box-id').innerHTML = `<img src="${g.identificacion.startsWith('http') ? g.identificacion : base_url + 'uploads/fotos/' + g.identificacion}" class="w-full h-full object-cover rounded-2xl shadow-inner">`;
            }
            showView('reg-form');
        }

        function toggleMinorFields(isMinor) {
            const respDiv = document.getElementById('group-responsable');
            const addressDiv = document.getElementById('panel-ubicacion');
            const contactDiv = document.getElementById('group-minor-hidden-1');
            const natDiv = document.getElementById('group-minor-hidden-2');
            const birthDiv = document.getElementById('group-minor-hidden-3');
            const docPanel = document.getElementById('panel-documentacion');
            const respInput = document.getElementById('f-responsable');
            const empresaGroup = document.getElementById('f-empresa')?.closest('.form-input-group');

            if (isMinor) {
                respDiv.classList.remove('hidden');
                addressDiv.classList.add('opacity-30', 'pointer-events-none', 'grayscale');
                if (contactDiv) contactDiv.classList.add('hidden');
                if (natDiv) natDiv.classList.add('hidden');
                if (birthDiv) birthDiv.classList.add('hidden');
                if (docPanel) docPanel.classList.add('hidden');
                if (empresaGroup) empresaGroup.classList.add('hidden');
                if (respInput) respInput.setAttribute('required', 'true');
                
                // 🔥 Los menores no pueden ser titulares
                const titularSelect = document.getElementById('f-is-titular');
                if (titularSelect) {
                    titularSelect.value = 'false';
                    titularSelect.options[0].disabled = true;
                }
            } else {
                respDiv.classList.add('hidden');
                addressDiv.classList.remove('opacity-30', 'pointer-events-none', 'grayscale');
                if (contactDiv) contactDiv.classList.remove('hidden');
                if (natDiv) natDiv.classList.remove('hidden');
                if (birthDiv) birthDiv.classList.remove('hidden');
                if (docPanel) docPanel.classList.remove('hidden');
                if (empresaGroup) empresaGroup.classList.remove('hidden');
                if (respInput) respInput.removeAttribute('required');

                const titularSelect = document.getElementById('f-is-titular');
                if (titularSelect) titularSelect.options[0].disabled = false;
            }
        }

       let lastSearchResults = [];
let searchTimeout = null;
let currentFetchId = 0;

window.handleClientDBSearch = function(q, mode = 'form') {

    const resBoxId = mode === 'header' ? 'db-search-results-header' : 'db-search-results';
    const resBox = document.getElementById(resBoxId);

    if (!resBox) return;

    // 🔹 limpiar debounce anterior
    if (searchTimeout) clearTimeout(searchTimeout);

    // 🔹 mínimo caracteres
    if (!q || q.length < 3) {
        resBox.style.display = 'none';
        resBox.innerHTML = '';
        return;
    }

    // 🔹 debounce (300ms)
    searchTimeout = setTimeout(async () => {

        const fetchId = ++currentFetchId;

        resBox.innerHTML = `
            <div class="p-4 text-center text-blue-600 text-[10px] font-black uppercase animate-pulse italic">
                <i class="fas fa-spinner fa-spin mr-2"></i>Buscando...
            </div>
        `;
        resBox.style.display = 'block';

        try {
            const response = await fetch(base_url + "pasajeros/buscar_cliente?q=" + encodeURIComponent(q));
            const data = await response.json();

            // 🔹 evita que respuestas viejas sobreescriban nuevas
            if (fetchId !== currentFetchId) return;

            lastSearchResults = data;

            if (!data || data.length === 0) {
                resBox.innerHTML = `
                    <div class="p-4 text-center text-slate-400 text-xs italic">
                        Sin resultados para "${q}"
                    </div>
                `;
                return;
            }

            resBox.innerHTML = data.map((g, idx) => `
                <div class="p-3 border-b hover:bg-blue-50 transition-colors flex justify-between items-center">
                    <div>
                        <p class="font-black text-xs uppercase text-slate-800">
                            ${g.nombre || ''} ${g.apellido || ''}
                        </p>
                        <p class="text-[9px] font-bold text-slate-400">
                            ID: ${g.numero_identificacion || '---'}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick='addGuestFromDBByIndex(${idx}, true, "${mode}")'
                            class="px-2 py-1 bg-blue-600 text-white text-[9px] font-black rounded">
                            Titular
                        </button>
                        <button onclick='addGuestFromDBByIndex(${idx}, false, "${mode}")'
                            class="px-2 py-1 bg-slate-200 text-slate-700 text-[9px] font-black rounded">
                            Acomp.
                        </button>
                    </div>
                </div>
            `).join('');

        } catch (e) {
            console.error("❌ Error en búsqueda:", e);
            resBox.innerHTML = `
                <div class="p-4 text-center text-red-500 text-xs">
                    Error al buscar
                </div>
            `;
        }

    }, 300);
};

        async function addGuestFromDBByIndex(idx, isTitular, mode) {
            const g = lastSearchResults[idx];
            if (!g) return;
            const r = rooms[selectedRoomIdx];
            
            const guest = {
                ...g,
                isTitular: isTitular,
                fotografia: g.fotografia || null,
                identificacion: g.identificacion || null,
                parentesco: isTitular ? 'TITULAR' : (g.parentesco || 'CONOCIDO')
            };

            const totalHuespedesActuales = tempGuests.length + 1;

            if (!isTitular && totalHuespedesActuales > r.capacidad) {
                const result = await Swal.fire({
                    title: 'Capacidad Excedida',
                    text: `La habitación tiene capacidad para ${r.capacidad} personas. ¿Registrar como PERSONA EXTRA con cargo de $${r.precio_extra}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, agregar con cargo',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                });

                if (result.isConfirmed) {
                    guest.es_extra = 1;
                } else {
                    return;
                }
            }

            if (isTitular) tempGuests.forEach(tg => tg.isTitular = false);
            tempGuests.push(guest);
            
            const resBoxId = mode === 'header' ? 'db-search-results-header' : 'db-search-results';
            const searchInputId = mode === 'header' ? 'client-db-search-header' : 'client-db-search';
            document.getElementById(resBoxId).style.display = 'none';
            document.getElementById(searchInputId).value = '';
            
            showToast("Actualizando registro...");
            try {
                syncLocalRoom(selectedRoomIdx); // 🔥 Actualizar datos locales
                if (r.registro_id) {
                    await guardarAcompanantesAsync(r.registro_id, tempGuests.filter(g => !g.isTitular));
                    await sincronizarHabitacionDB(selectedRoomIdx);
                }
                renderGuestList();
                renderGrid(); // 🔥 Refrescar grid
            } catch (err) {
                console.error(err);
            }
        }
        /* --- ROOM SERVICE / POS --- */
        let rsCart = [];
        let rsSearchResults = [];

        function openModalRoomService() {
            rsCart = [];
            renderCart();
            const selector = document.getElementById('rs-room-selector');
            if (!selector) return;
            selector.innerHTML = '<option value="0">VENTA INDEPENDIENTE (MOSTRADOR)</option>';
            rooms.forEach(r => {
                const isCheckin = ['CHECKIN', 'CHECK-IN'].includes((r.estado_registro || '').toUpperCase().trim());
                if (r.registro_id && isCheckin) {
                    const nombre = r.titular_full || 'REGISTRADO';
                    selector.innerHTML += `<option value="${r.registro_id}">HAB ${r.id} - ${nombre}</option>`;
                }
            });

            // Pre-seleccionar si venimos de una habitación específica
            if (selectedRoomIdx !== null && rooms[selectedRoomIdx].registro_id) {
                selector.value = rooms[selectedRoomIdx].registro_id;
            } else {
                selector.value = "0";
            }
            cargarAccesosRapidosRS();
            document.getElementById('modal-roomservice').classList.remove('hidden');
            document.getElementById('modal-roomservice').classList.add('flex');
        }

        async function cargarAccesosRapidosRS() {
            try {
                const resp = await fetch(base_url + "roomservice/servicios");
                const json = await resp.json();
                const container = document.getElementById('rs-quick-access');
                if (!container) return;
                container.innerHTML = '';
                if (json.ok) {
                    json.data.forEach(p => {
                        container.innerHTML += `
                            <button onclick="addToCart({id: ${p.id}, nombre: '${p.nombre}', precio: ${p.precio}})" class="p-6 bg-white border-2 border-slate-100 rounded-3xl text-center hover:border-orange-500 hover:shadow-xl transition-all active:scale-95 group overflow-hidden relative">
                                <span class="block text-xs font-black uppercase text-slate-800">${p.nombre}</span>
                                <span class="block text-lg font-black text-orange-600">$${p.precio}</span>
                            </button>
                        `;
                    });
                }
            } catch (e) { console.error(e); }
        }

        async function searchRSProducts(q) {
            const resBox = document.getElementById('rs-search-results');
            if (q.length < 2) { resBox.classList.add('hidden'); return; }
            try {
                const resp = await fetch(base_url + "roomservice/buscar?q=" + q);
                const json = await resp.json();
                rsSearchResults = json.data;
                resBox.innerHTML = '';
                if (rsSearchResults.length > 0) {
                    rsSearchResults.forEach((p, idx) => {
                        resBox.innerHTML += `
                            <div onclick="addSearchResultToCart(${idx})" class="p-4 border-b border-slate-50 hover:bg-orange-50 cursor-pointer flex justify-between items-center transition-colors">
                                <span class="text-xs font-black uppercase text-slate-700">${p.nombre}</span>
                                <span class="text-xs font-black text-orange-600">$${p.precio}</span>
                            </div>
                        `;
                    });
                    resBox.classList.remove('hidden');
                } else {
                    resBox.innerHTML = '<div class="p-4 text-xs font-bold text-slate-400 uppercase">No se encontraron productos</div>';
                    resBox.classList.remove('hidden');
                }
            } catch (e) { console.error(e); }
        }

        function addSearchResultToCart(idx) {
            const p = rsSearchResults[idx];
            addToCart({ id: p.id, nombre: p.nombre, precio: p.precio });
            document.getElementById('rs-search-results').classList.add('hidden');
            document.getElementById('rs-product-search').value = '';
        }

        function addManualToCart() {
            const concept = document.getElementById('rs-manual-concept').value.trim();
            const price = parseFloat(document.getElementById('rs-manual-price').value);
            if (!concept || isNaN(price)) { showToast("Ingrese concepto y precio válido"); return; }
            addToCart({ id: null, nombre: concept, precio: price });
            document.getElementById('rs-manual-concept').value = '';
            document.getElementById('rs-manual-price').value = '';
        }

        function addToCart(p) {
            const existing = rsCart.find(item => item.id === p.id && item.id !== null && item.nombre === p.nombre);
            if (existing) { existing.cantidad++; } 
            else { rsCart.push({ ...p, cantidad: 1, observaciones: '' }); }
            renderCart();
            showToast("Añadido al carrito");
        }

        function removeFromCart(idx) {
            rsCart.splice(idx, 1);
            renderCart();
        }

        function updateCartQty(idx, qty) {
            if (qty < 1) return;
            rsCart[idx].cantidad = parseInt(qty) || 1;
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('rs-cart-container');
            const totalDisplay = document.getElementById('rs-total-display');
            const totalFooter = document.getElementById('rs-total-footer');
            const countDisplay = document.getElementById('rs-item-count');
            if (!container) return;
            let total = 0; let itemsCount = 0;
            if (rsCart.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center h-full opacity-20 py-10"><i class="fas fa-shopping-basket text-6xl mb-4"></i><p class="text-xs font-black uppercase italic">El carrito está vacío</p></div>`;
            } else {
                container.innerHTML = rsCart.map((p, i) => {
                    const sub = p.precio * p.cantidad;
                    total += sub; itemsCount += p.cantidad;
                    return `<div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between"><div class="flex-1"><p class="text-[10px] font-black text-slate-800 uppercase tracking-tighter">${p.nombre}</p><p class="text-[9px] font-bold text-orange-500">$${p.precio} c/u</p></div><div class="flex items-center space-x-4"><div class="flex items-center bg-slate-100 rounded-xl px-2 py-1"><button onclick="updateCartQty(${i}, ${p.cantidad - 1})" class="w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-900"><i class="fas fa-minus text-[8px]"></i></button><span class="w-8 text-center text-xs font-black">${p.cantidad}</span><button onclick="updateCartQty(${i}, ${p.cantidad + 1})" class="w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-900"><i class="fas fa-plus text-[8px]"></i></button></div><div class="w-20 text-right"><p class="text-xs font-black text-slate-800">$${sub.toFixed(2)}</p></div><button onclick="removeFromCart(${i})" class="text-rose-400 hover:text-rose-600 transition-colors ml-2"><i class="fas fa-trash-alt"></i></button></div></div>`;
                }).join('');
            }
            if (totalDisplay) totalDisplay.textContent = `$${total.toFixed(2)}`;
            if (totalFooter) totalFooter.textContent = `$${total.toFixed(2)}`;
            if (countDisplay) countDisplay.textContent = `${itemsCount} ITEMS`;
        }

        async function finalizarPedidoRS() {
            if (rsCart.length === 0) { showToast("El carrito está vacío"); return; }
            
            // 1. Registro ID (Asegurar 0 si es Mostrador)
            const registroId = parseInt(document.getElementById('rs-room-selector').value) || 0;
            
            const result = await Swal.fire({ 
                title: 'Finalizar Pedido', 
                text: "¿Confirma que desea cerrar el pedido e imprimir el ticket?", 
                icon: 'question', 
                showCancelButton: true, 
                confirmButtonColor: '#ea580c', 
                confirmButtonText: 'Sí, finalizar', 
                cancelButtonText: 'Cancelar' 
            });

            if (result.isConfirmed) {
                showToast("Procesando pedido...");
                
                // 2. Agrupar pedido en un solo concepto
                const totalOrder = rsCart.reduce((acc, p) => acc + (p.precio * p.cantidad), 0);
                const aggregatedText = rsCart.map(p => `${p.nombre} (${p.cantidad})`).join(' + ');
                const jsonDetail = JSON.stringify(rsCart);

                try {
                    const resp = await fetch(base_url + "roomservice/finalizar-pedido", { 
                        method: "POST", 
                        headers: { "Content-Type": "application/json" }, 
                        body: JSON.stringify({ 
                            registro_id: registroId, 
                            // Enviamos el pedido agrupado en un solo item según lo solicitado
                            items: [{
                                nombre: aggregatedText,
                                precio: totalOrder,
                                cantidad: 1,
                                observaciones: jsonDetail,
                                registro_id: registroId // 🔥 Añadido aquí también para evitar auto-consecutivos
                            }],
                            observaciones: jsonDetail,
                            usuario_id: null 
                        }) 
                    });
                    
                    const json = await resp.json();
                    if (json.ok) { 
                        const printConfirm = await Swal.fire({
                            title: '¡Éxito!',
                            text: 'Pedido registrado correctamente. ¿Desea imprimir el ticket?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'SÍ, IMPRIMIR',
                            cancelButtonText: 'NO, SOLO CERRAR',
                            confirmButtonColor: '#ea580c'
                        });

                        if (printConfirm.isConfirmed) {
                            imprimirTicketRS(rsCart, registroId, json.total || totalOrder); 
                        }

                        closeModal('modal-roomservice'); 
                        initData(); 
                    } else { 
                        throw new Error(json.msg); 
                    }
                } catch (e) { 
                    console.error(e); 
                    Swal.fire('Error', e.message, 'error'); 
                }
            }
        }

        function imprimirTicketRS(cart, registroId, total) {
            const ticketWindow = window.open('', 'PRINT', 'height=600,width=400');
            const roomLabel = (registroId && registroId != 0) ? `REGISTRO: ${registroId}` : 'VENTA MOSTRADOR';
            ticketWindow.document.write(`<html><head><style>body { font-family: 'Courier New'; width: 80mm; padding: 5mm; } .text-center { text-align: center; } .header { border-bottom: 1px dashed #000; padding-bottom: 5mm; margin-bottom: 5mm; } table { width: 100%; border-collapse: collapse; } th { text-align: left; font-size: 10px; } td { font-size: 10px; padding: 2mm 0; } .total { border-top: 2px solid #000; margin-top: 5mm; padding-top: 2mm; font-weight: bold; font-size: 14px; }</style></head><body><div class="text-center header"><h3>HOTEL OS</h3><p>TICKET ROOM SERVICE</p><p>${new Date().toLocaleString()}</p><p><strong>${roomLabel}</strong></p></div><table><thead><tr><th>CANT</th><th>PRODUCTO</th><th style="text-align:right">TOTAL</th></tr></thead><tbody>${cart.map(p => `<tr><td>${p.cantidad}</td><td>${p.nombre}</td><td style="text-align:right">$${(p.cantidad * p.precio).toFixed(2)}</td></tr>`).join('')}</tbody></table><div class="total"><span>TOTAL:</span><span style="float:right">$${total.toFixed(2)}</span></div><div class="text-center" style="margin-top:10mm; font-size:10px;"><p>¡GRACIAS POR SU COMPRA!</p></div></body></html>`);
            ticketWindow.document.close(); ticketWindow.focus(); ticketWindow.print(); ticketWindow.close();
        }

        function openModalRoomService_OLD() {
            const select = document.getElementById('rs-room-selector');
            select.innerHTML = '<option value="">Seleccione habitación...</option>';
            rooms.forEach((r, idx) => {
                const isCheckin = ['CHECKIN', 'CHECK-IN'].includes((r.estado_registro || '').toUpperCase().trim());
                if (r.registro_id && isCheckin && r.huespedes && r.huespedes.length > 0) {
                    const titular = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `Hab ${r.id} - ${titular.nombre} ${titular.apellido || titular.apellidos || ''}`;
                    select.appendChild(opt);
                }
            });
            if (selectedRoomIdx !== null && rooms[selectedRoomIdx].huespedes.length > 0) {
                select.value = selectedRoomIdx;
            }
            renderServicesSummary();
            document.getElementById('modal-roomservice').classList.add('modal-active');
        }

        function updateSelectedRoomFromRS(idx) {
            selectedRoomIdx = idx === "" ? null : parseInt(idx);
            renderServicesSummary();
        }

        async function addService(name, price) {
            if (selectedRoomIdx === null) return showToast("SELECCIONE UNA HABITACIÓN");
            const r = rooms[selectedRoomIdx];
            
            if (!r.registro_id) return showToast("LA HABITACIÓN NO TIENE UN REGISTRO ACTIVO");

            const payload = {
                registro_id: r.registro_id,
                concepto: name,
                precio_unitario: price,
                cantidad: 1,
                tipo: 'Extra',
                departamento: 'Room Service'
            };

            try {
                showToast("Registrando cargo...");
                const response = await fetch(base_url + "registro-cargos/guardar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const res = await response.json();
                
                if (res.ok) {
                    showToast(`AGREGADO: ${name} ($${price})`);
                    if (!r.services) r.services = [];
                    r.services.push({ id: res.data.id, name, price, date: new Date().toLocaleString() });
                    renderServicesSummary();
                    initData(); // Recargar para actualizar totales globales
                } else {
                    showToast("Error: " + res.msg);
                }
            } catch (e) {
                console.error(e);
                showToast("Error de conexión al registrar cargo");
            }
        }

        async function removeService(serviceId, idxInArray) {
            if (!confirm("¿Desea cancelar este cargo?")) return;
            
            try {
                const response = await fetch(base_url + "registro-cargos/cancelar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: serviceId, motivo: "Cancelado por usuario en Room Service" })
                });
                const res = await response.json();
                
                if (res.ok) {
                    showToast("Cargo cancelado correctamente");
                    rooms[selectedRoomIdx].services.splice(idxInArray, 1);
                    renderServicesSummary();
                    initData();
                } else {
                    showToast("Error al cancelar: " + res.msg);
                }
            } catch (e) {
                showToast("Error de conexión");
            }
        }

        function renderServicesSummary() {
            const container = document.getElementById('services-summary');
            if (selectedRoomIdx === null) {
                container.innerHTML = `
                    <div class="text-center opacity-30">
                        <i class="fas fa-concierge-bell text-6xl mb-4 text-slate-300"></i>
                        <p class="text-slate-500 font-black italic uppercase text-lg">Seleccione una habitación para ver consumos</p>
                    </div>`;
                return;
            }
            const r = rooms[selectedRoomIdx];
            if (!r.services || r.services.length === 0) {
                container.innerHTML = '<div class="text-center p-10 font-black text-slate-300 italic uppercase">Sin consumos registrados</div>';
                return;
            }
            let total = 0;
            container.innerHTML = r.services.map((s, idx) => {
                total += parseFloat(s.price);
                return `
                    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border-2 border-slate-100 shadow-sm mb-3">
                        <div>
                            <p class="font-black text-sm uppercase text-slate-700">${s.name}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${s.date}</p>
                        </div>
                        <div class="flex items-center space-x-6">
                            <span class="text-xl font-black text-orange-600 italic">$${parseFloat(s.price).toFixed(2)}</span>
                            <button onclick="removeService(${s.id}, ${idx})" class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `;
            }).join('');
            container.innerHTML += `
                <div class="pt-8 border-t-2 border-dashed border-slate-200 mt-8 flex justify-between items-center px-4">
                    <span class="text-2xl font-black uppercase italic text-slate-400">Total Consumos</span>
                    <span class="text-5xl font-black text-orange-600 italic tracking-tighter">$${total}</span>
                </div>
            `;
        }

        /* Se maneja arriba asíncronamente */


        async function saveGuestAndReturn() {
            const isTitular = document.getElementById('f-is-titular').value === 'true';
            const d = {
                id: (selectedGuestEditIdx > -1 && tempGuests[selectedGuestEditIdx]) ? tempGuests[selectedGuestEditIdx].id : null,
                nombre: document.getElementById('f-name').value.toUpperCase(),
                apellido: document.getElementById('f-apellidos').value.toUpperCase(),
                telefono: document.getElementById('f-tel').value,
                email: document.getElementById('f-mail').value,
                nacionalidad: document.getElementById('f-nat').value.toUpperCase(),
                fecha_nacimiento: document.getElementById('f-birth').value,
                genero: document.getElementById('f-gender').value === 'Masculino' ? 'M' : 'F',
                tipo_identificacion_id: document.getElementById('f-id-type').value,
                numero_identificacion: document.getElementById('f-id-num').value.toUpperCase(),
                direccion: document.getElementById('f-address').value.toUpperCase(),
                ciudad: document.getElementById('f-city').value,
                estado: document.getElementById('f-state').value,
                codigo_postal: document.getElementById('f-cp').value,
                placas: document.getElementById('f-placas').value.toUpperCase(),
                isTitular: isTitular,
                parentesco: document.getElementById('f-parentesco').value,
                empresa: document.getElementById('f-empresa').value.toUpperCase(),
                es_menor: document.getElementById('f-is-minor').checked ? 1 : 0,
                es_extra: (selectedGuestEditIdx > -1 && tempGuests[selectedGuestEditIdx]) ? (tempGuests[selectedGuestEditIdx].es_extra || 0) : 0,
                Responsable_menor: document.getElementById('f-responsable').value.toUpperCase(),
                fotografia: document.getElementById('box-client').dataset.blob || null,
                identificacion: document.getElementById('box-id').dataset.blob || null,
                firma_path: null // Se llenará abajo si hay firma
            };

            // 🔥 Si hay firma nueva, subirla
            if (sigDirty) {
                const canvas = document.getElementById('sig-canvas');
                const sigBase64 = canvas.toDataURL("image/png");
                try {
                    const sigFileName = await subirFoto(sigBase64);
                    d.firma_path = sigFileName;
                } catch (e) { console.error("Error subiendo firma", e); }
            } else if (selectedGuestEditIdx > -1 && tempGuests[selectedGuestEditIdx]) {
                d.firma_path = tempGuests[selectedGuestEditIdx].firma_path;
            }

            if (!d.nombre) return showToast("Nombre requerido");
            if (!d.es_menor && d.telefono.length < 10) return showToast("El teléfono debe tener al menos 10 dígitos");
            if (d.es_menor && !d.Responsable_menor) return showToast("Nombre del responsable requerido");

            console.log("💾 Guardando huésped local:", d);

            if (isTitular) {
                // Si es titular, lo mandamos a la DB de una vez para asegurar que tenga ID
                showToast("Guardando titular...");
                guardarHuespedAsync(d)
                    .then(resp => {
                        if (!resp.id) throw new Error("No se recibió ID del titular");
                        d.id = resp.id;
                        // Desmarcar otros titulares locales
                        tempGuests.forEach(g => g.isTitular = false);
                        if (selectedGuestEditIdx === -1) tempGuests.push(d); else tempGuests[selectedGuestEditIdx] = d;
                        showToast("Titular guardado");
                        backToGuestList();
                    })
                    .catch(err => {
                        console.error(err);
                        showToast("Error: " + (err.message || err));
                    });
            } else {
                // Si es acompañante, lo guardamos y sincronizamos de una vez
                const r = rooms[selectedRoomIdx];
                const totalHuespedesActuales = tempGuests.length + (selectedGuestEditIdx === -1 ? 1 : 0);
                
                console.log(`🧐 [DEBUG CAPACIDAD] Habitación: ${r.id}, Capacidad: ${r.capacidad}, Actuales + Nuevo: ${totalHuespedesActuales}`);

                if (totalHuespedesActuales > r.capacidad && d.es_extra != 1) {
                    const result = await Swal.fire({
                        title: 'Persona Extra',
                        text: `La capacidad máxima es de ${r.capacidad} personas. ¿Aplicar cargo extra de $${r.precio_extra}?`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, aplicar cargo',
                        cancelButtonText: 'No, cancelar registro',
                        confirmButtonColor: '#f59e0b'
                    });

                    if (result.isConfirmed) {
                        d.es_extra = 1;
                    } else {
                        return; // Cancela el guardado
                    }
                }

                if (selectedGuestEditIdx === -1) tempGuests.push(d); else tempGuests[selectedGuestEditIdx] = d;
                
                showToast("Sincronizando acompañante...");
                try {
                    syncLocalRoom(selectedRoomIdx); // 🔥 Actualizar datos locales
                    if (r.registro_id) {
                        await guardarAcompanantesAsync(r.registro_id, tempGuests.filter(g => !g.isTitular));
                        await sincronizarHabitacionDB(selectedRoomIdx);
                    }
                    backToGuestList();
                    renderGrid(); // 🔥 Refrescar grid
                } catch (err) {
                    console.error(err);
                    showToast("Error al sincronizar: " + err.message);
                }
            }
        }

        async function guardarHuespedAsync(d) {
            const response = await fetch(base_url + "pasajeros/guardar_cliente", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(d)
            });
            const resp = await response.json();
            if (!resp.ok) throw new Error(resp.msg || "Error desconocido");
            return resp;
        }

        async function guardarAcompanantesAsync(registro_id, acompanantes) {
            const response = await fetch(base_url + "reservacion/guardar-acompanantes", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    registro_id: registro_id,
                    acompanantes: acompanantes.map(a => ({
                        nombre: a.nombre,
                        apellido: a.apellido || '',
                        parentesco: a.parentesco || '',
                        es_menor: a.es_menor || 0,
                        es_extra: a.es_extra || 0,
                        Responsable_menor: a.Responsable_menor || null,
                        fotografia: a.fotografia || null,
                        identificacion: a.identificacion || null
                    }))
                })
            });
            const resp = await response.json();
            if (!resp.ok) throw new Error(resp.msg || "Error al guardar acompañantes");
            return resp;
        }

        async function confirmAllRegistration() {
            const r = rooms[selectedRoomIdx];
            const acompañantes = tempGuests.filter(g => !g.isTitular);

            try {
                showToast("Sincronizando...");
                
                // 🔥 IMPORTANTE: Actualizar huéspedes locales primero para que sincronizarHabitacionDB use los datos nuevos (incluyendo fotos)
                r.huespedes = [...tempGuests];

                // 1. Sincronizar registro principal primero para obtener el registro_id
                const respReg = await sincronizarHabitacionDB(selectedRoomIdx);
                const newRegistroId = respReg ? respReg.registro_id : r.registro_id;
                
                if (newRegistroId) {
                    r.registro_id = newRegistroId; // Guardar en el objeto local
                } else {
                    console.warn("No hay registro_id válido para sincronizar.");
                }

                // 2. Guardar acompañantes usando el nuevo registro_id
                if (acompañantes.length > 0 && r.registro_id) {
                    await guardarAcompanantesAsync(r.registro_id, acompañantes);
                }

                // 3. Actualizar estado local y visual inmediatamente para que el grid refleje los cambios
                r.huespedes = [...tempGuests];
                r.adultos = tempGuests.filter(g => g.es_menor != 1 && g.es_extra != 1).length;
                r.ninos = tempGuests.filter(g => g.es_menor == 1).length;
                r.extra = tempGuests.filter(g => g.es_extra == 1).length;
                r.ocupacion_total = r.adultos + r.ninos + r.extra;
                r.status = 'X';
                
                // 4. Actualizar estado de habitación a OCUPADA
                await actualizarEstadoHabitacionAsync(r.id_db || r.id, 2);

                closeModal('modal-register');
                renderGrid();
                showToast("Registro sincronizado correctamente");
                
                // 🔥 NUEVO: Mostrar ticket de registro con impuestos
                setTimeout(() => openRegistrationTicket(selectedRoomIdx), 500);
            } catch (err) {
                console.error(err);
                showToast("Error al sincronizar: " + err.message);
            }
        }

        async function sincronizarHabitacionDB(idx) {
            const r = rooms[idx];
            // Si no hay registro_id, se enviará undefined al server y éste creará uno nuevo
            console.log("🔄 Iniciando sincronización para hab", r.id, r.registro_id ? "Registro ID: " + r.registro_id : "NUEVO REGISTRO");

            const titular = (r.huespedes || []).find(g => g.isTitular) || (r.huespedes || [])[0];
            let huespedId = null;

            if (titular) {
                // Asegurar que el titular esté guardado en la BD y obtener su ID
                const respHuesped = await guardarHuespedAsync(titular);
                huespedId = respHuesped.id;
                console.log("✅ Titular persistido para registro:", titular.nombre, "ID:", huespedId);
            }

            // Cálculos financieros
            const nights = r.dias || 1;
            const extraCount = (r.huespedes || []).filter(g => g.es_extra == 1).length;
            const extraChargePerNight = extraCount * (r.precio_extra || 0);
            
            // Subtotal es (Base + Extras) * Noches
            const subtotal = (parseFloat(r.precio_base) + extraChargePerNight) * nights;
            
            const ivaRate = 0.16;
            const ishRate = 0.035; // Usamos 3.5% que es lo estándar en MX para ISH en muchos estados, o lo que diga el server
            
            const iva = subtotal * ivaRate;
            const ish = subtotal * ishRate;
            const total = subtotal + iva + ish;

            const payload = {
                id: r.registro_id, // 🔥 Clave Primaria Estricta
                huesped_id: huespedId,
                habitacion_id: r.id_db || r.id, 
                hora_entrada: r.fechaEntrada ? `${r.fechaEntrada.split('/').reverse().join('-')} ${r.horaEntrada || '12:00:00'}` : new Date().toLocaleString('sv-SE').replace('T', ' '),
                hora_salida: r.dias ? new Date(Date.now() + r.dias * 86400000).toISOString().slice(0, 10) : new Date().toISOString().slice(0, 10),
                noches: nights,
                tipo_estadia_id: r.tipoEstadiaId || 1,
                forma_pago_id: r.formaPago || 1,
                precio: subtotal,
                precio_base: r.precio_base || 0,
                iva: iva,
                ish: ish,
                total: total,
                adultos: (r.huespedes || []).filter(g => g.es_menor != 1 && g.es_extra != 1).length,
                niños: (r.huespedes || []).filter(g => g.es_menor == 1).length,
                num_personas_ext: (r.huespedes || []).filter(g => g.es_extra == 1).length,
                pago_adicional: ((r.huespedes || []).filter(g => g.es_extra == 1).length * r.precio_extra)
            };

            // 🔥 Actualizar datos locales para que el grid refleje el nuevo precio/total
            r.precio = total; 
            r.total = total;
            r.iva_reg = iva;
            r.ish_reg = ish;

            return guardarRegistroAsync(payload);
        }

        async function guardarRegistroAsync(payload) {
            const response = await fetch(base_url + "reservacion/guardar", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });
            const resp = await response.json();
            if (!resp.ok) throw new Error(resp.msg || "Error al guardar registro");
            return { ...resp, ok: resp.ok };
        }

        async function verSalidas() {
            const r = rooms[selectedRoomIdx];
            if (!r || !r.registro_id) return;

            openSubModal('modal-salidas');
            const body = document.getElementById('lista-salidas-body');
            const msg = document.getElementById('no-salidas-msg');
            body.innerHTML = '<tr><td colspan="4" class="py-10 text-center font-black text-slate-400">CARGANDO...</td></tr>';

            try {
                const response = await fetch(base_url + "reservacion/get-salidas/" + r.registro_id);
                const data = await response.json();
                
                body.innerHTML = '';
                if (data.length === 0) {
                    msg.classList.remove('hidden');
                } else {
                    msg.classList.add('hidden');
                    data.forEach(s => {
                        const date = new Date(s.fecha_salida).toLocaleString('es-MX', { 
                            day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' 
                        });
                        let badgeClass = 'bg-slate-100 text-slate-600';
                        if (s.tipo_salida === 'entrada') {
                            badgeClass = 'bg-indigo-100 text-indigo-600';
                        } else if (s.tipo_salida === 'TEMPORAL') {
                            badgeClass = 'bg-orange-100 text-orange-600';
                        } else if (s.tipo_salida === 'DEFINITIVA') {
                            badgeClass = 'bg-rose-100 text-rose-600';
                        }
                        
                        body.innerHTML += `
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 text-xs font-black text-slate-600">${date}</td>
                                <td class="py-4">
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-tighter">${s.nombre_huesped || 'S/N'}</p>
                                </td>
                                <td class="py-4">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase ${badgeClass}">${s.tipo_salida}</span>
                                </td>
                                <td class="py-4 text-[10px] font-bold text-slate-400 uppercase leading-tight">${s.motivo || '-'}</td>
                            </tr>
                        `;
                    });
                }
            } catch (e) {
                body.innerHTML = '<tr><td colspan="4" class="py-10 text-center font-black text-rose-500 uppercase">Error al cargar datos</td></tr>';
            }
        }

        async function actualizarEstadoHabitacionAsync(habitacionId, estadoId) {
            // 🔥 Buscamos el registro_id de la habitación en el array local
            const room = rooms.find(r => r.id_db == habitacionId || r.id == habitacionId);
            const registroId = room ? room.registro_id : null;

            if (!registroId) {
                console.error("No se encontró registro_id para la habitación", habitacionId);
                return { ok: false, msg: "No hay un registro activo" };
            }

            return fetch(base_url + "reservacion/actualizar-campo", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    registro_id: registroId, 
                    estado_id: estadoId 
                })
            }).then(r => r.json()).then(res => {
                // Mapeamos success a ok para compatibilidad
                return { ...res, ok: res.success };
            });
        }

        async function marcarHabitacionLimpiaAsync(habitacionId) {
            const resp = await actualizarEstadoHabitacionAsync(habitacionId, 2);
            if (!resp.ok) throw new Error(resp.msg || "Error al marcar como limpia");
            return resp;
        }

        function renderGuestList() {
            const c = document.getElementById('guests-list-container');
            const r = rooms[selectedRoomIdx];
            c.innerHTML = tempGuests.map((g, i) => {
                const badgeExtra = g.es_extra == 1 ? '<span class="ml-3 bg-orange-500 text-white text-[9px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm border border-orange-600">Persona Extra</span>' : '';
                return `
                <div class="flex items-center justify-between p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center space-x-6">
                        <div class="w-12 h-12 rounded-xl ${g.isTitular ? 'bg-blue-600' : (g.es_extra == 1 ? 'bg-orange-500' : 'bg-slate-100 text-slate-400')} text-white flex items-center justify-center font-black shadow-inner">
                            ${i + 1}
                        </div>
                        <div class="flex flex-col">
                            <p class="font-black text-slate-800 uppercase flex items-center tracking-tighter">
                                ${g.nombre} ${g.apellido || g.apellidos || ''} 
                                ${badgeExtra}
                            </p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                ${g.isTitular ? 'Huésped Responsable' : (g.es_extra == 1 ? 'Invitado Adicional' : 'Acompañante')}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="goToForm(${i})" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                    </div>
                </div>`;
            }).join('');
            if (!tempGuests.length) c.innerHTML = `<p class="col-span-2 text-center text-slate-400 font-bold p-10 uppercase text-xs">Sin registros</p>`;
            
            updateOccupancyCounter();
            
            // 🔥 RE-EVALUAR BOTÓN CHECKOUT
            const btnCheckout = document.getElementById('btn-checkout-footer');
            const hasTitular = tempGuests.some(g => g.isTitular);
            if (btnCheckout && r) {
                if (r.registro_id && hasTitular) {
                    btnCheckout.classList.remove('hidden');
                } else {
                    btnCheckout.classList.add('hidden');
                }
            }
        }

        async function removeGuest(i) {
            const result = await Swal.fire({
                title: '¿Eliminar Huésped?',
                text: "Esta acción quitará al huésped del registro actual.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                tempGuests.splice(i, 1);
                const r = rooms[selectedRoomIdx];
                
                showToast("Actualizando registro...");
                try {
                    syncLocalRoom(selectedRoomIdx); // 🔥 Actualizar datos locales

                    if (r.registro_id) {
                        await guardarAcompanantesAsync(r.registro_id, tempGuests.filter(g => !g.isTitular));
                        await sincronizarHabitacionDB(selectedRoomIdx);
                    }
                    
                    renderGuestList();
                    renderGrid(); // 🔥 Refrescar grid principal
                    showToast("Huésped eliminado");
                } catch (e) {
                    console.error(e);
                    showToast("Error al eliminar");
                }
            }
        }

        function syncLocalRoom(idx) {
            const r = rooms[idx];
            if (!r) return;
            r.huespedes = [...tempGuests];
            r.adultos = tempGuests.filter(g => g.es_menor != 1 && g.es_extra != 1).length;
            r.ninos = tempGuests.filter(g => g.es_menor == 1).length;
            r.extra = tempGuests.filter(g => g.es_extra == 1).length;
            r.ocupacion_total = r.adultos + r.ninos + r.extra;
        }

        async function prepareMoveModal() {
            const sourceSelect = document.getElementById('cambio-source-room');
            if (!sourceSelect) return;
            
            sourceSelect.innerHTML = '<option value="">Cargando orígenes...</option>';
            sourceSelect.disabled = true;

            try {
                const resp = await fetch(base_url + 'reservacion/habitaciones-activas');
                const activas = await resp.json();
                
                sourceSelect.innerHTML = '<option value="">Seleccionar Origen...</option>';
                activas.forEach(r => {
                    sourceSelect.innerHTML += `<option value="${r.registro_id}" 
                        data-hab-id="${r.habitacion_numero}" 
                        data-hab-id-db="${r.habitacion_id_db}">
                        Hab. ${r.habitacion_numero} - ${r.nombre_huesped}
                    </option>`;
                });
            } catch (error) {
                console.error("Error cargando habitaciones activas:", error);
                sourceSelect.innerHTML = '<option value="">Error al cargar</option>';
            } finally {
                sourceSelect.disabled = false;
            }

            // Limpiar estado
            selectedMoveSourceIdx = null;
            selectedMoveTargetIdx = null;
            if (document.getElementById('move-motivo')) document.getElementById('move-motivo').value = '';
            if (document.getElementById('move-dest-label')) document.getElementById('move-dest-label').textContent = '---';
            if (document.getElementById('cambio-dest-search')) document.getElementById('cambio-dest-search').value = '';
            
            const btn = document.getElementById('btn-confirm-move');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-30', 'cursor-not-allowed');
            }
            
            renderMoveTargetRooms();
        }

        function updateMoveSource() {
            selectedMoveSourceIdx = document.getElementById('cambio-source-room').value;
            renderMoveTargetRooms(); // Re-renderizar para excluir el nuevo origen
            validateMoveForm();
        }

        // Almacena la lista de rooms válidos para destino (para el buscador)
        let _destRooms = [];

        function renderMoveTargetRooms() {
            const grid = document.getElementById('cambio-grid');
            if (!grid) return;

            grid.innerHTML = '';
            _destRooms = [];

            const searchQuery = (document.getElementById('cambio-dest-search')?.value || '').toLowerCase();

            rooms.forEach((r, idx) => {
                const isSource = selectedMoveSourceIdx !== null && selectedMoveSourceIdx == idx;
                const matchesSearch = !searchQuery || r.id.toLowerCase().includes(searchQuery) || r.tipoHab.toLowerCase().includes(searchQuery);
                
                // El estado de disponibilidad ahora viene del registro
                const estadoReg = (r.estado_registro || '').toUpperCase().trim();
                const isAvailable = estadoReg === 'DISPONIBLE';
                
                // 🔥 Una habitación solo es destino válido si está LIMPIA (X)
                const isCleanStatus = r.status === 'X';

                // CRÍTICO: Una habitación está ocupada SOLO si tiene huéspedes activos
                const isOcupied = r.huespedes && Array.isArray(r.huespedes) && r.huespedes.length > 0;

                // Solo mostrar si cumple el filtro de disponible (registro), vacía y LIMPIA (X)
                if (!isSource && matchesSearch && isAvailable && !isOcupied && isCleanStatus) {
                    _destRooms.push({ idx, r });

                    const isSelected = selectedMoveTargetIdx == idx;
                    const card = document.createElement('div');
                    card.onclick = () => selectMoveTarget(idx);
                    
                    let statusClasses = isSelected 
                        ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100 shadow-xl scale-105 z-10' 
                        : 'border-slate-100 bg-white hover:border-blue-300 hover:shadow-md';
                    
                    card.className = `cursor-pointer p-4 rounded-[2rem] border-2 transition-all flex flex-col items-center justify-center space-y-1 relative ${statusClasses}`;

                    card.innerHTML = `
                        <div class="absolute top-4 left-4 text-[8px] font-black text-slate-300">ID: ${r.id_db}</div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${r.tipoHab}</span>
                        <span class="text-2xl font-black text-slate-800 italic">${r.id}</span>
                        <div class="flex items-center space-x-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[9px] font-black uppercase text-emerald-600">Disponible</span>
                        </div>
                        ${isSelected ? '<div class="absolute -top-2 -right-2 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-lg animate-bounce"><i class="fas fa-check text-[10px]"></i></div>' : ''}
                    `;
                    grid.appendChild(card);
                }
            });

            if (_destRooms.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-20 opacity-20">
                        <i class="fas fa-search text-4xl mb-4"></i>
                        <p class="font-black uppercase italic">Sin habitaciones disponibles</p>
                    </div>
                `;
            }

            console.log('✅ [TRASLADO] Grid actualizado:', _destRooms.length);
        }


        function filterDestSelect(query) {
            renderMoveTargetRooms();
        }

        function selectMoveTarget(idx) {
            selectedMoveTargetIdx = idx;
            const r = rooms[idx];
            const label = document.getElementById('move-dest-label');
            if (label) label.textContent = `HABITACIÓN ${r.id} (${r.tipoHab})`;
            renderMoveTargetRooms();
            validateMoveForm();
        }

        function validateMoveForm() {
            const motivo = document.getElementById('move-motivo')?.value.trim() || '';
            const btn = document.getElementById('btn-confirm-move');
            if (!btn) return;

            const isValid = selectedMoveSourceIdx !== null && selectedMoveSourceIdx !== "" && selectedMoveTargetIdx !== null && motivo.length > 2;

            if (isValid) {
                btn.disabled = false;
                btn.classList.remove('opacity-30', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-30', 'cursor-not-allowed');
            }
        }

        async function executeRoomMove(confirmado = false) {
            if (!selectedMoveSourceIdx || selectedMoveTargetIdx === null) return;

            const target = rooms[selectedMoveTargetIdx];
            const motivo = document.getElementById('move-motivo').value;

            // Obtenemos info del origen desde el select
            const select = document.getElementById('cambio-source-room');
            const opt = select.options[select.selectedIndex];
            const sourceHab = opt.getAttribute('data-hab-id');

            console.log("📤 [DEBUG TRASLADO] Intentando mover:", {
                origen_hab: sourceHab,
                origen_registro_id: selectedMoveSourceIdx,
                destino_id: target.id,
                destino_id_db: target.id_db,
                motivo: motivo
            });

            try {
                const resp = await fetch(base_url + "reservacion/cambiar-habitacion", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        registro_id: selectedMoveSourceIdx,
                        habitacion_id: target.id_db, // 🔥 ID de base de datos
                        confirmar_upgrade: confirmado,
                        motivo: motivo
                    })
                });

                const res = await resp.json();

                if (res.requiere_confirmacion) {
                    const ok = await Swal.fire({
                        title: 'CONFIRMAR UPGRADE',
                        html: `La habitación destino es más cara.<br>Diferencia: <b>$${Number(res.diferencia).toLocaleString('es-MX')}</b><br><br>¿Deseas realizar el traslado con este cargo extra?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, PROCEDER',
                        cancelButtonText: 'CANCELAR'
                    });

                    if (ok.isConfirmed) executeRoomMove(true);
                    return;
                }

                if (res.success || res.ok) {
                    await Swal.fire('TRASLADO EXITOSO', `Huéspedes movidos a la Habitación ${target.id}`, 'success');
                    closeModal('modal-cambio');
                    initData(); // Recargar grid completo
                } else {
                    Swal.fire('ERROR', res.msg || "Error al cambiar habitación", 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('ERROR', 'Error de conexión con el servidor', 'error');
            }
        }

        // Listener para validar motivo en tiempo real
        document.addEventListener('DOMContentLoaded', () => {
            document.body.addEventListener('input', (e) => {
                if (e.target.id === 'move-motivo') validateMoveForm();
            });
        });

        /* --- ESTADO DE CUENTA --- */
        function showEstadoCuentaModal() {
            const r = rooms[selectedRoomIdx]; document.getElementById('account-room-label').textContent = `HABITACIÓN ${r.id} (${r.type})`;
            const tbody = document.getElementById('movements-tbody'); tbody.innerHTML = '';
            let totalC = 0, totalA = 0, saldo = r.dias * basePrice;
            tbody.innerHTML += `<tr class="border-b"><td class="p-4 opacity-40 uppercase text-[10px] font-bold">${r.fechaEntrada}</td><td class="p-4 uppercase">HOSPEDAJE (${r.dias} D)</td><td class="p-4 text-right">$${(r.dias * basePrice).toFixed(2)}</td><td class="p-4 text-right">---</td><td class="p-4 text-right font-black">$${saldo.toFixed(2)}</td></tr>`;
            const movs = [...r.services.map(s => ({ ...s, t: 'cargo' })), ...r.payments.map(p => ({ name: `PAGO ${p.method}`, price: p.amount, t: 'abono', date: p.date, time: p.time }))];
            movs.forEach(m => {
                if (m.t === 'cargo') saldo += m.price; else saldo -= m.price;
                tbody.innerHTML += `<tr class="border-b ${m.t === 'abono' ? 'bg-emerald-50/50' : ''}"> <td class="p-4 opacity-40 uppercase text-[10px] font-bold">${m.date || '---'}</td><td class="p-4 uppercase">${m.name}</td><td class="p-4 text-right">${m.t === 'cargo' ? '$' + m.price.toFixed(2) : '---'}</td><td class="p-4 text-right text-emerald-600">${m.t === 'abono' ? '$' + m.price.toFixed(2) : '---'}</td><td class="p-4 text-right font-black">$${saldo.toFixed(2)}</td> </tr>`;
            });
            document.getElementById('modal-estado-cuenta').classList.add('modal-active');
        }

        function openRegistrationTicket(idx) {
            selectedRoomIdx = idx; 
            activePreviewMode = 'ticket-registro'; 
            const r = rooms[idx]; 
            const t = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
            
            // Nombre completo (priorizar titular_full del servidor)
            const nombreCompleto = r.titular_full || (t ? t.nombre + ' ' + (t.apellidos || '') : 'SIN NOMBRE');

            // 🔥 Lógica de Impuestos Alineada con Backend
            // Si tenemos precio_reg (subtotal) lo usamos, si no calculamos desde el total actual (r.precio)
            const subtotal = r.precio_reg || (r.precio / 1.195) || (parseFloat(r.precio_base) * (r.dias || 1)) || 0;
            const iva = r.iva_reg || (subtotal * 0.16);
            const ish = r.ish_reg || (subtotal * 0.035);
            const total = r.precio || (subtotal + iva + ish);

            document.getElementById('btn-final-action').textContent = "Imprimir Ticket";
            document.getElementById('preview-content').innerHTML = `
                <div class="ticket-paper shadow-2xl rounded-sm border">
                    <div class="ticket-header">
                        <p class="ticket-title">HotelOS V2</p>
                        <p class="text-[10px] font-bold">COMPROBANTE DE ADMISIÓN</p>
                        <p class="text-[9px] uppercase">Registro: ${r.fecha_registro}</p>
                    </div>
                    
                    <div class="ticket-info">
                        <div class="ticket-row font-black">
                            <span>HABITACIÓN:</span>
                            <span>${r.id}</span>
                        </div>
                        <div class="ticket-row">
                            <span>TIPO:</span>
                            <span class="uppercase">${r.tipoHab || 'ESTÁNDAR'}</span>
                        </div>
                        <div class="ticket-divider"></div>
                        <div class="ticket-row font-bold">
                            <span>HUÉSPED:</span>
                        </div>
                        <div class="text-[11px] uppercase mb-2">
                            ${nombreCompleto}
                        </div>
                        <div class="ticket-row">
                            <span>PERSONAS:</span>
                            <span>${r.ocupacion_total}</span>
                        </div>
                        <div class="ticket-row">
                            <span>ESTADÍA:</span>
                            <span>${r.dias} NOCHE(S)</span>
                        </div>
                    </div>

                    <div class="ticket-divider"></div>

                    <div class="space-y-1 text-[11px]">
                        <div class="ticket-row">
                            <span>SUBTOTAL:</span>
                            <span>$${subtotal.toFixed(2)}</span>
                        </div>
                        <div class="ticket-row">
                            <span>IVA (16%):</span>
                            <span>$${iva.toFixed(2)}</span>
                        </div>
                        <div class="ticket-row">
                            <span>ISH (3.5%):</span>
                            <span>$${ish.toFixed(2)}</span>
                        </div>
                    </div>

                    <div class="ticket-total ticket-row">
                        <span>TOTAL:</span>
                        <span>$${total.toFixed(2)}</span>
                    </div>

                    <div class="ticket-footer">
                        <p>*** GRACIAS POR SU PREFERENCIA ***</p>
                        <p>Este no es un comprobante fiscal</p>
                        <p class="mt-4 opacity-30 text-[8px]">Software by Antigravity AI</p>
                        <p class="text-[8px] uppercase">${new Date().toLocaleString()}</p>
                    </div>
                </div>`;
            document.getElementById('modal-preview').classList.add('modal-active');
        }

        /* --- IMPRIMIR REGISTRO --- */
        function s(val, fallback = '---') {
            if (val === null || val === undefined || val === 'null' || val === 'undefined' || String(val).trim() === '') return fallback;
            return val;
        }

        function openModalRegistry() {
            activePreviewMode = 'registry'; const r = rooms[selectedRoomIdx]; const t = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
            if (!t) return showToast("No hay huéspedes registrados");
            document.getElementById('btn-final-action').textContent = "Confirmar Impresión";
            
            const nombre = s(t.nombre, '') + ' ' + s(t.apellido || t.apellidos, '');
            
            document.getElementById('preview-content').innerHTML = `
                <div class="registry-paper">
                    <div class="flex justify-between items-start border-b-4 border-blue-600 pb-8 mb-10">
                        <div><h1 class="text-5xl font-black italic text-blue-600 tracking-tighter">HOTELOS<span class="text-slate-900 not-italic ml-1">V2</span></h1><p class="text-[11px] font-black uppercase text-slate-400 mt-2">Folio Oficial de Registro</p></div>
                        <div class="text-right"><p class="font-black text-2xl">HAB: ${r.id}</p><p class="text-slate-500 font-bold uppercase text-xs">${r.fechaEntrada}</p></div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-12 gap-y-6 mb-12">
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Huésped</label><p class="text-lg font-black uppercase">${nombre}</p></div>
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Teléfono</label><p class="text-lg font-bold">${s(t.telefono)}</p></div>
                        <div class="col-span-2 border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Dirección</label><p class="text-lg font-bold uppercase">${s(t.direccion)}, ${s(t.ciudad, '')}</p></div>
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Placas</label><p class="text-lg font-mono font-bold uppercase">${s(t.placas, 'N/A')}</p></div>
                    </div>
                    <div class="bg-slate-900 text-white p-8 rounded-3xl grid grid-cols-4 text-center mb-20 shadow-xl border-8 border-white">
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Unidad</p><p class="text-3xl font-black">${r.id}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Pernoctas</p><p class="text-3xl font-black">${r.dias}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Pers.</p><p class="text-3xl font-black">${r.ocupacion_total}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Tarifa</p><p class="text-3xl font-black">$${r.precio_base}</p></div>
                    </div>
                    <div class="flex justify-between items-center space-x-20">
                        <div class="flex-1 border-t-2 border-slate-900 pt-4 text-center">
                            ${t.firma_path ? `<img src="${base_url}uploads/fotos/${t.firma_path}" class="h-16 mx-auto mb-2">` : ''}
                            <p class="font-black text-[10px] uppercase">Firma Huésped</p>
                        </div>
                        <div class="flex-1 border-t-2 border-slate-900 pt-4 text-center"><p class="font-black text-[10px] uppercase">Sello Recepción</p></div>
                    </div>
                </div>`;
            document.getElementById('modal-preview').classList.add('modal-active');
        }

        /* --- TICKET SALIDA --- */
        function openTicketPreview(idx) {
            selectedRoomIdx = idx; activePreviewMode = 'ticket'; const r = rooms[idx]; const t = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
            const totalS = r.services.reduce((a, b) => a + b.price, 0); const totalP = r.payments.reduce((a, b) => a + b.amount, 0);
            const balance = (r.dias * basePrice) + totalS - totalP;
            document.getElementById('btn-final-action').textContent = "Confirmar Checkout";
            document.getElementById('preview-content').innerHTML = `
                <div class="ticket-paper">
                    <p class="text-center font-black text-2xl mb-6 italic tracking-tighter uppercase">HotelOS V2</p>
                    <div class="space-y-1 mb-4 text-[11px] font-bold"><p class="flex justify-between"><span>HABITACIÓN:</span> <span>${r.id}</span></p><p class="flex justify-between"><span>TITULAR:</span> <span class="uppercase">${t ? t.nombre : '---'}</span></p></div>
                    <div class="border-t-2 border-slate-900 pt-4"><div class="flex justify-between text-xs font-bold opacity-60"><span>SUBTOTAL:</span><span>$${((r.dias * basePrice) + totalS).toFixed(2)}</span></div><div class="flex justify-between text-xs font-bold opacity-60"><span>PAGOS:</span><span>-$${totalP.toFixed(2)}</span></div><div class="flex justify-between font-black text-xl mt-4 border-t pt-2 ${balance > 0 ? 'text-rose-600' : 'text-emerald-700'}"><span>SALDO TOTAL:</span><span>$${balance.toFixed(2)}</span></div></div>
                </div>`;
            document.getElementById('modal-preview').classList.add('modal-active');
        }
        function openTicketPreviewFromStatement() { closeModal('modal-estado-cuenta'); openTicketPreview(selectedRoomIdx); }
        function executeFinalAction() { 
            if (activePreviewMode === 'ticket') { 
                const r = rooms[selectedRoomIdx]; 
                r.huespedes = []; r.services = []; r.payments = []; r.status = 'sucia'; r.precio = 0; 
                closeModal('modal-preview'); 
                renderGrid(); 
                showToast("Checkout procesado"); 
            } else if (activePreviewMode === 'ticket-registro') {
                window.print();
                closeModal('modal-preview');
            } else { 
                closeModal('modal-preview'); 
            } 
        }


        /* --- AUXILIARES --- */
        function openOptionsMenu(idx) { selectedRoomIdx = idx; document.getElementById('menu-opt-hab').textContent = `HABITACIÓN ${rooms[idx].id}`; document.getElementById('opt-obs-input').value = rooms[idx].obs || ''; document.getElementById('modal-options-menu').classList.add('modal-active'); }
        
        /* --- DATOS FISCALES --- */
        async function abrirModalFiscal() {
            closeModal('modal-options-menu');
            const r = rooms[selectedRoomIdx];
            if (!r || !r.registro_id) return showToast("SE REQUIERE UN REGISTRO ACTIVO");

            document.getElementById('fiscal-hab-label').textContent = `HABITACIÓN ${r.id}`;
            
            // Limpiar campos
            const fields = ['rfc', 'razon', 'regimen', 'cp', 'uso', 'email', 'qr'];
            fields.forEach(f => {
                const el = document.getElementById('fiscal-' + f);
                if (el) el.value = '';
            });
            document.getElementById('fiscal-extranjero').checked = false;

            try {
                // Usar el endpoint correcto para obtener el perfil fiscal vinculado
                const response = await fetch(base_url + "reservacion/perfil/" + r.registro_id);
                const res = await response.json();
                if (res.ok && res.data) {
                    const d = res.data;
                    document.getElementById('fiscal-rfc').value = d.rfc || '';
                    document.getElementById('fiscal-razon').value = d.razon_social || '';
                    document.getElementById('fiscal-regimen').value = d.regimen_fiscal || '';
                    document.getElementById('fiscal-cp').value = d.codigo_postal_fiscal || '';
                    document.getElementById('fiscal-uso').value = d.uso_cfdi || '';
                    document.getElementById('fiscal-email').value = d.email_facturacion || '';
                    document.getElementById('fiscal-extranjero').checked = (d.es_extranjero == 1);
                    document.getElementById('fiscal-qr').value = d.QR || '';
                }
            } catch (e) { console.error("Error cargando perfil fiscal", e); }

            document.getElementById('modal-fiscal').classList.remove('hidden');
            document.getElementById('modal-fiscal').classList.add('flex');
        }

        async function guardarDatosFiscales() {
            const r = rooms[selectedRoomIdx];
            const payload = {
                registro_id: r.registro_id,
                rfc: document.getElementById('fiscal-rfc').value.toUpperCase(),
                razon_social: document.getElementById('fiscal-razon').value,
                regimen_fiscal: document.getElementById('fiscal-regimen').value,
                codigo_postal_fiscal: document.getElementById('fiscal-cp').value,
                uso_cfdi: document.getElementById('fiscal-uso').value,
                email_facturacion: document.getElementById('fiscal-email').value,
                es_extranjero: document.getElementById('fiscal-extranjero').checked ? 1 : 0,
                QR: document.getElementById('fiscal-qr').value
            };

            if (!payload.rfc || !payload.razon_social) {
                return Swal.fire("Error", "RFC y Razón Social son requeridos", "error");
            }

            try {
                showToast("Guardando perfil fiscal...");
                const response = await fetch(base_url + "reservacion/guardar-perfil", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const res = await response.json();
                if (res.ok) {
                    showToast("DATOS FISCALES GUARDADOS");
                    closeModal('modal-fiscal');
                    initData();
                } else {
                    showToast("Error: " + res.msg);
                }
            } catch (e) {
                console.error(e);
                showToast("Error de conexión");
            }
        }

        function updateRoomObs(v) { rooms[selectedRoomIdx].obs = v; }
        function showView(v) { 
            const viewWork = document.getElementById('view-work');
            if (viewWork) viewWork.classList.toggle('hidden', v === 'home');
            
            const regList = document.getElementById('reg-view-list');
            if (regList) regList.classList.toggle('hidden', v !== 'reg-list');
            
            const regForm = document.getElementById('reg-view-form');
            if (regForm) regForm.classList.toggle('hidden', v !== 'reg-form');

            // 🔥 Toggle visibility of header counters
            const headerOcc = document.getElementById('header-occ-container');
            const headerPerson = document.getElementById('header-person-container');
            if (headerOcc) headerOcc.classList.toggle('hidden', v !== 'reg-list');
            if (headerPerson) headerPerson.classList.toggle('hidden', v !== 'reg-form');
        }
        function startShift() { showToast("Sincronizando sesión..."); setTimeout(() => showView('work'), 800); }
        function changeFloor(f) {
            currentFloor = f;
            const container = document.getElementById("floor-tabs");
            if (container) {
                const buttons = container.querySelectorAll("button");
                buttons.forEach(btn => {
                    if (btn.dataset.floor === String(f)) {
                        btn.className = 'bg-blue-600 px-5 py-1.5 rounded-lg text-xs font-black shadow-lg';
                    } else {
                        btn.className = 'px-5 py-1.5 rounded-lg text-xs font-bold text-slate-400';
                    }
                });
            }
            renderGrid();
        }

        async function cargarPisos() {
            try {
                const response = await fetch(base_url + "catalogos/listar_pisos");
                const data = await response.json();
                const cont = document.getElementById("floor-tabs");
                if (!cont) return;
                
                cont.innerHTML = '';

                // 🔥 Añadir pestaña TODOS
                const btnAll = document.createElement("button");
                btnAll.dataset.floor = 'ALL';
                btnAll.innerText = "TODOS";
                btnAll.className = "bg-blue-600 px-5 py-1.5 rounded-lg text-xs font-black shadow-lg";
                btnAll.onclick = () => changeFloor('ALL');
                cont.appendChild(btnAll);
                
                currentFloor = 'ALL'; // Por defecto ver todos
                
                data.forEach((p, i) => {
                    const btn = document.createElement("button");
                    btn.dataset.floor = p.Piso;
                    
                    let btnText = p.Piso;
                    if (btnText === 'PB' || btnText.toLowerCase().includes('piso')) {
                        btn.innerText = btnText;
                    } else {
                        btn.innerText = "PISO " + btnText;
                    }

                    btn.className = "px-5 py-1.5 rounded-lg text-xs font-bold text-slate-400";
                    btn.onclick = () => changeFloor(p.Piso);
                    cont.appendChild(btn);
                });
            } catch (e) {
                console.error("Error cargando pisos:", e);
            }
        }
        function showToast(m) { const t = document.getElementById('toast'); t.querySelector('p').textContent = m; t.classList.remove('translate-y-72'); setTimeout(() => t.classList.add('translate-y-72'), 3000); }
        /* --- MÓDULO MULTIMEDIA (CÁMARA, OCR, FIRMA) --- */
        let activeStream = null;
        let activeBoxId = null;

        async function toggleCamera(boxId) {
            const box = document.getElementById(boxId);
            if (activeStream && activeBoxId === boxId) {
                stopCamera();
                return;
            }
            if (activeStream) stopCamera();

            try {
                activeStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false });
                activeBoxId = boxId;
                box.innerHTML = `<video id="video-preview" autoplay playsinline class="w-full h-full object-cover rounded-2xl"></video>`;
                document.getElementById('video-preview').srcObject = activeStream;
                showToast("Cámara encendida");
            } catch (err) {
                console.error(err);
                showToast("Error al acceder a la cámara");
            }
        }

        function stopCamera() {
            if (activeStream) {
                activeStream.getTracks().forEach(track => track.stop());
                const box = document.getElementById(activeBoxId);
                if (box) {
                    const icon = activeBoxId === 'box-client' ? 'fa-user' : 'fa-id-badge';
                    const label = activeBoxId === 'box-client' ? 'Video de Rostro' : 'Scan Documento';
                    box.innerHTML = `<i class="fas ${icon} text-6xl opacity-10"></i><span class="text-[9px] font-black uppercase text-slate-400 mt-4">${label}</span>`;
                    if (activeBoxId === 'box-id') box.innerHTML += `<div id="scan-line" class="scan-line" style="display:none"></div>`;
                }
                activeStream = null; activeBoxId = null;
            }
        }

        async function capturePhoto(boxId) {
            if (!activeStream || activeBoxId !== boxId) return showToast("Enciende la cámara primero");
            const video = document.getElementById('video-preview');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth; canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            const box = document.getElementById(boxId);
            
            // 🔥 Preview local inmediato (permanece mientras sube)
            box.innerHTML = `<img src="${dataUrl}" class="w-full h-full object-cover rounded-2xl shadow-inner opacity-50">
                             <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i>
                             </div>`;
            
            stopCamera();
            console.log("📸 Captura tomada, subiendo...");

            try {
                const fileName = await subirFoto(dataUrl);
                if (fileName) {
                    box.dataset.blob = fileName;
                    // Actualizar a opacidad completa al terminar
                    box.innerHTML = `<img src="${dataUrl}" class="w-full h-full object-cover rounded-2xl shadow-inner">`;
                    showToast("Foto lista");
                }
            } catch (err) {
                console.error(err);
                showToast("Error al subir foto");
                // Revertir a icono si falló
                box.innerHTML = `<i class="fas fa-user text-6xl opacity-10"></i>`;
            }
        }

        async function subirFoto(base64) {
            const response = await fetch(base_url + "media/subir-foto", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ foto: base64 })
            });
            const resp = await response.json();
            if (!resp.ok) throw new Error(resp.msg);
            return resp.foto;
        }

        async function runOCR(imageBase64 = null) {
            const boxId = 'box-id';
            const box = document.getElementById(boxId);
            const scanLine = document.getElementById('scan-line');
            
            // 1. Obtener imagen si no se pasó una (desde la cámara/preview)
            if (!imageBase64) {
                const img = box.querySelector('img');
                if (!img) return showToast("Captura la identificación o sube un archivo");
                imageBase64 = img.src;
            }

            if (scanLine) scanLine.style.display = 'block';
            showToast("Procesando OCR con IA...");

            try {
                const response = await fetch(base_url + "ocr/procesar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ image: imageBase64 })
                });
                const resp = await response.json();
                
                if (scanLine) scanLine.style.display = 'none';

                if (resp.ok && resp.data) {
                    fillFormFromOCR(resp.data);
                    
                    // 🔥 Guardar el nombre del archivo para que se salve en la BD al confirmar
                    const boxIdEl = document.getElementById('box-id');
                    if (resp.file) boxIdEl.dataset.blob = resp.file;

                    if (resp.es_menor) showToast("⚠️ Atención: Menor de edad detectado");
                    showToast("✔ Datos extraídos correctamente");
                } else {
                    throw new Error(resp.error || "Error al procesar documento");
                }
            } catch (err) {
                console.error(err);
                if (scanLine) scanLine.style.display = 'none';
                showToast("Error OCR: " + err.message);
            }
        }

        function fillFormFromOCR(data) {
            console.log("📝 Mapeando datos OCR:", data);
            if (data.nombre) document.getElementById('f-name').value = data.nombre.toUpperCase();
            if (data.apellidos) document.getElementById('f-apellidos').value = data.apellidos.toUpperCase();
            if (data.numero_identificacion) document.getElementById('f-id-num').value = data.numero_identificacion.toUpperCase();
            if (data.fecha_nacimiento) document.getElementById('f-birth').value = data.fecha_nacimiento;
            if (data.genero) {
                const gen = data.genero.toLowerCase();
                document.getElementById('f-gender').value = (gen.startsWith('m') || gen.includes('masc')) ? 'Masculino' : 'Femenino';
            }
            if (data.nacionalidad) document.getElementById('f-nat').value = data.nacionalidad.toUpperCase();
            if (data.direccion) document.getElementById('f-address').value = data.direccion.toUpperCase();
        }

        function handleOCRFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64 = e.target.result;
                    // Mostrar preview en el box de ID
                    const box = document.getElementById('box-id');
                    box.innerHTML = `<img src="${base64}" class="w-full h-full object-cover rounded-2xl shadow-inner">
                                     <div id="scan-line" class="scan-line"></div>`;
                    runOCR(base64);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        /* FIRMA ELECTRÓNICA */
        let sigPad = null;
        let sigDirty = false;
        function initSignaturePad() {
            const canvas = document.getElementById('sig-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let drawing = false;

            // Ajustar tamaño al contenedor
            const resize = () => {
                const rect = canvas.parentNode.getBoundingClientRect();
                canvas.width = rect.width; canvas.height = rect.height;
            };
            window.addEventListener('resize', resize); resize();

            const start = (e) => { drawing = true; sigDirty = true; ctx.beginPath(); move(e); };
            const stop = () => { drawing = false; };
            const move = (e) => {
                if (!drawing) return;
                const rect = canvas.getBoundingClientRect();
                const x = (e.clientX || e.touches[0].clientX) - rect.left;
                const y = (e.clientY || e.touches[0].clientY) - rect.top;
                ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.strokeStyle = '#0f172a';
                ctx.lineTo(x, y); ctx.stroke();
            };

            canvas.onmousedown = start; canvas.onmousemove = move; window.onmouseup = stop;
            canvas.ontouchstart = (e) => { e.preventDefault(); start(e); };
            canvas.ontouchmove = (e) => { e.preventDefault(); move(e); };
            canvas.ontouchend = stop;
        }

        function clearSignature() {
            const canvas = document.getElementById('sig-canvas');
            if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        }
        function applyGlobalSearch() { renderGrid(); }
        function handleClientDBSearch(v) { /* Búsqueda simulada */ }

       /*FUNCIONES DE BASE DE DATOS*/

       async function cargarFormasPago() {

    try {
        const r = await fetch(base_url + "catalogos/formas-pago");

        if (!r.ok) throw new Error("HTTP " + r.status);

        const data = await r.json();

        catalogoFormasPago = data.map(row => {
            const partes = row.label.split('-');
            return {
                id: row.id,
                codigo: partes[0],
                descripcion: partes.slice(1).join('-')
            };
        });

        console.log("✔ catálogo listo:", catalogoFormasPago);

    } catch (e) {
        console.error("❌ error formas pago:", e);
    }
}

function renderSelectFormaPago(valorActual, realIdx, isBlocked = false) {
    let html = `<select 
        onchange="updatePayment(${realIdx}, this.value)" 
        class="bg-transparent font-bold text-xs outline-none" 
        ${isBlocked ? 'disabled' : ''}>`;

    html += `<option value="">-</option>`;

    catalogoFormasPago.forEach(fp => {
        html += `
            <option value="${fp.id}" ${valorActual == fp.id ? 'selected' : ''}>
                ${fp.codigo}
            </option>
        `;
    });

    html += `</select>`;
    return html;
}

async function cargarTiposEstadia() {
    try {
        const r = await fetch(base_url + "catalogos/tipo-estadia");
        if (!r.ok) throw new Error("HTTP " + r.status);
        const data = await r.json();
        catalogoTiposEstadia = data.map(row => {
            const partes = row.label.split('-');
            return {
                id: row.id,
                codigo: partes[0],
                nombre: partes.slice(1).join('-')
            };
        });
        console.log("✔ tipos estadía listos:", catalogoTiposEstadia);
    } catch (e) {
        console.error("❌ error tipos estadía:", e);
    }
}

async function cargarTiposID() {
    try {
        const r = await fetch(base_url + "pasajeros/catalogo_tipos_identificacion");
        if (!r.ok) throw new Error("HTTP " + r.status);
        const data = await r.json();
        catalogoTiposID = data;
        const select = document.getElementById("f-id-type");
        if (select) {
            select.innerHTML = `<option value="">Seleccione</option>`;
            data.forEach(t => {
                select.innerHTML += `<option value="${t.id}">${t.nombre}</option>`;
            });
        }
        console.log("✔ tipos ID listos:", catalogoTiposID);
    } catch (e) {
        console.error("❌ error tipos ID:", e);
    }
}

function renderSelectEstadia(valorActual, realIdx, isBlocked = false) {
    let html = `<select 
        onchange="updateStay(${realIdx}, this.value)" 
        class="bg-transparent font-bold text-xs outline-none" 
        ${isBlocked ? 'disabled' : ''}>`;

    html += `<option value="">-</option>`;

    catalogoTiposEstadia.forEach(te => {
        html += `
            <option value="${te.id}" ${valorActual == te.id ? 'selected' : ''}>
                ${te.codigo}
            </option>
        `;
    });

    html += `</select>`;
    return html;
}





        window.onload = async () => {
            // ⚡ Carga en paralelo todos los catálogos al mismo tiempo
            await Promise.all([
                cargarFormasPago(),
                cargarTiposEstadia(),
                cargarTiposID(),
                cargarPisos(),
                cargarTiposHabitacion(),
                cargarImpuestos()
            ]);
            initData();
            setInterval(() => document.getElementById('clock').textContent = new Date().toLocaleTimeString(), 1000);
        };

        async function cargarImpuestos() {
            try {
                const res = await fetch(base_url + 'reservacion/obtenerImpuestos');
                const data = await res.json();

                window.TASA_IVA = 16; // Default
                window.TASA_ISH = 3;  // Default

                let iva = data.find(i => i.nombre === 'IVA');
                if (iva) window.TASA_IVA = parseFloat(iva.tasa);

                let ish = data.find(i => i.nombre === 'ISH');
                if (ish) window.TASA_ISH = parseFloat(ish.tasa);
                
                console.log("💎 Tasas cargadas:", window.TASA_IVA, window.TASA_ISH);
            } catch (e) {
                console.error("Error cargando impuestos:", e);
                window.TASA_IVA = 16;
                window.TASA_ISH = 3;
            }
        }

        async function cargarTiposHabitacion() {
            try {
                const response = await fetch(base_url + "catalogos/listar_tipos_habitacion");
                const data = await response.json();
                const select = document.getElementById('filter-tipo-hab');
                if (!select) return;
                select.innerHTML = '<option value="">Todos</option>';
                data.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.nombre;
                    opt.textContent = t.nombre;
                    select.appendChild(opt);
                });
            } catch (e) {
                console.error('Error cargando tipos habitación:', e);
            }
        }
        async function hacerCheckout(overrideId = null) {
            const registroId = overrideId || window.currentRegistroId;
            if (!registroId) return;

            const confirm = await Swal.fire({
                title: '¿FINALIZAR ESTANCIA?',
                text: "Se marcará la salida del huésped y la habitación quedará DISPONIBLE.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'SÍ, FINALIZAR',
                cancelButtonText: 'CANCELAR'
            });
if (confirm.isConfirmed) {
    try {

        const formData = new FormData();
        formData.append('registro_id', registroId);

        const resp = await fetch(base_url + 'reservacion/checkout', {
            method: 'POST',
            body: formData
        });

        if (!resp.ok) {
            throw new Error("Error HTTP: " + resp.status);
        }

        const res = await resp.json();

        if (res.success) {
            showToast("Checkout realizado con éxito");
            closeModal('modal-register');
            initData(); // Recargar grid
        } else {
            throw new Error(res.msg || "Error al procesar checkout");
        }

    } catch (err) {
        console.error(err);
        showToast("Error: " + err.message);
    }
}
        }
        async function toggleReporteYShade(idx, status) {
            const r = rooms[idx];
            if (!r) return;

            // 1. Efecto Visual Inmediato (siempre funciona local)
            r.incluir_en_reporte = status;
            renderGrid();

            // 2. Persistencia en Servidor (solo si hay un registro activo)
            if (r.registro_id) {
                try {
                    const resp = await fetch(base_url + 'reservacion/actualizar-campo', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            registro_id: r.registro_id, 
                            incluir_en_reporte: status ? 1 : 0 
                        })
                    });
                    
                    if (!resp.ok) throw new Error("Error en servidor");
                    const res = await resp.json();
                    
                    if (res.success) {
                        showToast(status ? "Agregado a reporte" : "Removido de reporte");
                    } else {
                        console.error("Respuesta error:", res);
                        // No revertimos el visual para no molestar al usuario, 
                        // pero avisamos que hubo un problema de persistencia
                    }
                } catch (err) {
                    console.error("Error Fetch:", err);
                    showToast("Error de conexión");
                }
            }
        }

    </script>

</body>

<?= view('layout/footer') ?>