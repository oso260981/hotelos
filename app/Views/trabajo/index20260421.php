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
    </style>





    <!-- VISTA TRABAJO -->
    <section id="view-work" class="flex-1 flex flex-col bg-slate-200 overflow-hidden">
        <div class="bg-[#0f172a] p-3 flex items-center px-6 border-b border-white/5 overflow-x-auto no-scrollbar">
            <!-- ACCIONES LADO IZQUIERDO -->
            <div class="flex items-center space-x-3 pr-6 border-r border-slate-700/50 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest mr-2">Acciones:</span>
                <button onclick="resetFilters()"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg border border-slate-700">
                    LIMPIAR
                </button>
                <button onclick="openSubModal('modal-cambio')"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg shadow-blue-900/40 border border-blue-400/20">
                    <i class="fas fa-exchange-alt mr-2 text-[8px]"></i>CAMBIO
                </button>
                <button onclick="openSubModal('modal-roomservice')"
                    class="bg-orange-600 hover:bg-orange-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-lg shadow-orange-900/40 border border-orange-400/20">
                    <i class="fas fa-utensils mr-2 text-[8px]"></i>ROOM SERVICE
                </button>
            </div>
            
            <!-- BUSCADOR GLOBAL -->
            <div class="relative w-80 px-6 border-r border-slate-700/50 shrink-0">
                <input type="text" id="global-search" oninput="applyGlobalSearch()"
                    placeholder="Búsqueda Global..."
                    class="bg-slate-800/50 text-white border border-slate-700 rounded-xl px-10 py-2 text-[10px] w-full outline-none focus:border-blue-500 transition-all shadow-inner">
                <i class="fas fa-search absolute left-10 top-1/2 -translate-y-1/2 text-slate-500 text-[9px]"></i>
            </div>

            <!-- PISOS -->
            <div class="flex items-center space-x-4 px-6 border-r border-slate-700/50 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Piso:</span>
                <div id="floor-tabs" class="flex bg-slate-800/30 p-1.5 rounded-xl space-x-1 border border-slate-700/50">
                    <!-- Los pisos se cargarán dinámicamente aquí -->
                </div>
            </div>

            <!-- TIPO HABITACIÓN -->
            <div class="flex items-center space-x-4 px-6 shrink-0">
                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Tipo Habitación:</span>
                <select id="filter-tipo-hab" onchange="applyTipoFilter()" class="bg-slate-800/50 text-white border border-slate-700 rounded-xl px-4 py-2 text-[10px] outline-none focus:border-blue-500 transition-all font-bold">
                    <option value="">Todos</option>
                </select>
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
            <div></div>
            <div class="text-[10px] font-black opacity-30 tracking-widest italic uppercase">Registros activos: <span
                    id="count-active">0</span> | HotelOS Terminal Pro</div>
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
                        <h3 class="text-2xl font-black italic uppercase tracking-tighter" id="reg-room-title">Habitación --</h3>
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
                        <div class="flex items-center space-x-2 bg-slate-100 px-3 py-1 rounded-full border">
                            <i class="fas fa-users text-blue-500 text-[10px]"></i>
                            <span class="text-[10px] font-black text-slate-500 uppercase">Ocupación:</span>
                            <span id="occupancy-counter" class="text-[10px] font-black text-blue-700">0 / 0</span>
                        </div>
                    </div>
                    <button onclick="goToForm(-1)" id="btn-nuevo-huesped"
                        class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black uppercase text-sm shadow-xl active:scale-95 transition-all"><i
                            class="fas fa-plus-circle mr-3"></i>Nuevo Huésped</button>
                </div>

                <div id="guests-list-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cards dinámicas -->
                </div>

                <div class="pt-10 border-t flex flex-col md:flex-row justify-between items-center gap-6">
                    <!-- 🔥 BOTÓN DE CHECKOUT NIVELADO -->
                    <button id="btn-checkout-footer" onclick="hacerCheckout()" 
                        class="hidden bg-rose-600 text-white px-10 py-6 rounded-[2.5rem] font-black uppercase text-xs shadow-xl hover:bg-rose-700 active:scale-95 transition-all flex items-center justify-center space-x-3 flex-1 w-full md:w-auto">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span>Finalizar Estancia (Checkout)</span>
                    </button>

                    <!-- 🔥 BOTÓN DE SINCRONIZAR -->
                    <button onclick="confirmAllRegistration()" id="btn-sincronizar"
                        class="bg-emerald-500 text-white px-10 py-6 rounded-[2.5rem] font-black uppercase text-xs shadow-xl hover:bg-emerald-600 active:scale-95 transition-all flex-1 w-full md:w-auto">
                        Sincronizar Habitación
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
                    <!-- 🔥 CONTADOR DE PERSONAS DINÁMICO -->
                    <div class="bg-white px-6 py-3 rounded-3xl border-2 border-slate-100 shadow-sm flex flex-col items-center min-w-[120px]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Huésped</span>
                        <span id="form-person-counter" class="text-xl font-black text-blue-600 italic tracking-tighter">1 de 2</span>
                    </div>
                    <div class="flex-1"></div>
                    <!-- BUSCADOR DE CLIENTES EXISTENTES -->
                    <div class="relative w-96">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 text-sm font-black"><i
                                class="fas fa-search"></i></span>
                        <input type="text" id="client-db-search" oninput="handleClientDBSearch(this.value)"
                            placeholder="BUSCAR CLIENTE HISTÓRICO..."
                            class="w-full bg-white border-2 border-blue-100 rounded-2xl pl-12 pr-6 py-4 text-xs font-black uppercase shadow-sm focus:border-blue-500 outline-none">
                        <div id="db-search-results" class="search-results-box"></div>
                    </div>
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
                            <div class="form-section-title"><i class="fas fa-user-edit mr-3"></i>Datos Generales</div>
                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div class="form-input-group"><label class="form-label">Nombre(s)</label><input
                                        type="text" id="f-name" class="form-input" placeholder="Nombre"></div>
                                <div class="form-input-group"><label class="form-label">Apellidos</label><input
                                        type="text" id="f-apellidos" class="form-input" placeholder="Apellidos"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div class="form-input-group"><label class="form-label">Teléfono Movil</label><input
                                        type="tel" id="f-tel" class="form-input" placeholder="000 000 0000"></div>
                                <div class="form-input-group"><label class="form-label">Correo Electrónico</label><input
                                        type="email" id="f-mail" class="form-input" placeholder="ejemplo@mail.com">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-5">
                                <div class="form-input-group"><label class="form-label">Nacionalidad</label><input
                                        type="text" id="f-nat" class="form-input" value="MEXICANA"></div>
                                <div class="form-input-group"><label class="form-label">Nacimiento</label><input
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
                                <div class="form-input-group flex flex-col justify-center items-start">
                                    <label class="form-label uppercase tracking-widest text-[10px]">¿Es Menor de Edad?</label>
                                    <label class="relative inline-flex items-center cursor-pointer mt-2">
                                        <input type="checkbox" id="f-is-minor" class="sr-only peer" onchange="toggleMinorFields(this.checked)">
                                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                                <div class="form-input-group flex flex-col justify-center items-start">
                                    <label class="form-label uppercase tracking-widest text-[10px] text-orange-600">¿Es Persona Extra?</label>
                                    <label class="relative inline-flex items-center cursor-pointer mt-2">
                                        <input type="checkbox" id="f-is-extra" class="sr-only peer">
                                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- 🔥 Campo Responsable (Solo Menores) -->
                            <div id="group-responsable" class="form-input-group mt-5 hidden animate-pulse-subtle">
                                <label class="form-label text-blue-600 font-black uppercase tracking-widest text-[10px]">Nombre del Responsable / Tutor</label>
                                <input type="text" id="f-responsable" class="form-input border-2 border-blue-100 bg-blue-50/30" placeholder="Escriba nombre completo del tutor">
                            </div>
                        </div>

                        <!-- Panel: Documento e Identidad -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
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
                                    <button onclick="deleteGuestInForm()" id="btn-delete-guest"
                                        class="w-16 h-16 bg-rose-600 text-white rounded-2xl active:bg-rose-700 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
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

                        <!-- Select agrupado por piso -->
                        <select id="cambio-dest-select"
                            size="10"
                            onchange="selectMoveTargetFromSelect(this.value)"
                            class="flex-1 bg-white border-2 border-slate-200 rounded-2xl p-3 text-sm font-bold outline-none focus:border-emerald-500 transition-all w-full">
                            <option value="" disabled>Cargando habitaciones...</option>
                        </select>

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

    <!-- MODAL ROOM SERVICE -->
    <div id="modal-roomservice" class="fixed inset-0 bg-slate-900/90 hidden z-[200] items-center justify-center p-6 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white w-full max-w-4xl rounded-[4rem] p-14 flex flex-col shadow-2xl relative max-h-[90vh]">
            <button onclick="closeModal('modal-roomservice')" class="absolute right-10 top-10 w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm border border-slate-100 hover:scale-110 active:scale-90"><i class="fas fa-times"></i></button>
            
            <div class="mb-10">
                <h3 class="text-5xl font-black italic uppercase text-orange-600 tracking-tighter leading-none">Room Service</h3>
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Cargos extra a habitación</p>
            </div>
            
            <div class="bg-slate-50 rounded-[2.5rem] p-8 border-2 border-slate-100 mb-8 shadow-inner">
                 <label class="text-[10px] font-black text-orange-600 uppercase tracking-widest block mb-3 ml-2">Habitación a Cargo</label>
                 <select id="rs-room-selector" class="w-full bg-white border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-black text-slate-700 outline-none focus:border-orange-500 transition-all shadow-sm" onchange="updateSelectedRoomFromRS(this.value)">
                    <option value="">Seleccione habitación...</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-8 shrink-0">
                <button onclick="addService('Agua 600ml', 25)" class="p-8 bg-white border-2 border-slate-100 rounded-[2rem] text-center hover:border-orange-500 hover:shadow-xl transition-all active:scale-95 group relative overflow-hidden">
                    <span class="block text-xl font-black uppercase text-slate-800 group-hover:text-orange-600">Agua $25</span>
                    <i class="fas fa-tint absolute -right-4 -bottom-4 text-orange-50 opacity-0 group-hover:opacity-100 transition-all text-6xl rotate-12"></i>
                </button>
                <button onclick="addService('Cerveza Lata', 55)" class="p-8 bg-white border-2 border-slate-100 rounded-[2rem] text-center hover:border-orange-500 hover:shadow-xl transition-all active:scale-95 group relative overflow-hidden">
                    <span class="block text-xl font-black uppercase text-slate-800 group-hover:text-orange-600">Cerveza $55</span>
                    <i class="fas fa-beer absolute -right-4 -bottom-4 text-orange-50 opacity-0 group-hover:opacity-100 transition-all text-6xl rotate-12"></i>
                </button>
                <button onclick="addService('Snack Pro', 45)" class="p-8 bg-white border-2 border-slate-100 rounded-[2rem] text-center hover:border-orange-500 hover:shadow-xl transition-all active:scale-95 group relative overflow-hidden">
                    <span class="block text-xl font-black uppercase text-slate-800 group-hover:text-orange-600">Snack $45</span>
                    <i class="fas fa-hamburger absolute -right-4 -bottom-4 text-orange-50 opacity-0 group-hover:opacity-100 transition-all text-6xl rotate-12"></i>
                </button>
            </div>

            <div id="services-summary" class="flex-1 bg-slate-50/50 rounded-[2.5rem] p-10 overflow-y-auto border-2 border-dashed border-slate-200 min-h-[250px] flex flex-col items-center justify-center">
                <div class="text-center opacity-30">
                    <i class="fas fa-concierge-bell text-6xl mb-4"></i>
                    <p class="text-slate-500 font-black italic uppercase text-lg">Seleccione una habitación para ver consumos</p>
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
window.base_url = "<?= base_url() ?>/";
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
            { id: 'status', label: 'Estatus', w: '120px', filter: 'select' },
            { id: 'registro_id', label: 'ID Reg', w: '0px', filter: 'none', hidden: true },
            { id: 'id', label: 'Hab', w: '80px', filter: 'text' },
            { id: 'stay', label: 'Estadía', w: '100px', filter: 'select' },
            { id: 'days', label: 'Días', w: '70px', filter: 'text' },
            { id: 'people', label: 'Pers', w: '70px', filter: 'text' },
            { id: 'reg', label: 'Registro', w: '120px', filter: 'none' },
            { id: 'payment', label: 'Pago', w: '120px', filter: 'select' },
            { id: 'price', label: 'Precio', w: '110px', filter: 'text' },
            { id: 'h_ent', label: 'Entrada', w: '120px', filter: 'none' },
            { id: 'btn_e', label: '+', w: '60px', filter: 'none' },
            { id: 'h_sal', label: 'Salida', w: '120px', filter: 'none' },
            { id: 'btn_s', label: '+', w: '60px', filter: 'none' },
            { id: 'titular', label: 'Huésped Principal Registrado', w: '250px', filter: 'text' },
            { id: 'extra', label: 'Extra', w: '80px', filter: 'text' },
            { id: 'opt', label: 'Opciones', w: '110px', filter: 'none' },
            { id: 'ticket', label: 'Ticket', w: '100px', filter: 'none' }
        ];

        const activeFilters = {};


        async function initData() {
            console.log("🚀 Iniciando carga de datos...");
            try {
                // 🔥 Cargar Catálogos primero
                const [respHab, respEst] = await Promise.all([
                    fetch(base_url + 'reservacion/habitaciones'),
                    fetch(base_url + 'reservacion/estados-habitacion')
                ]);

                if (respEst.ok) catalogoEstados = await respEst.json();

                if (!respHab.ok) throw new Error("HTTP Status: " + respHab.status);
                const data = await respHab.json();
                console.log("📦 Datos recibidos del servidor:", data);

                rooms.length = 0;
                data.forEach(item => {
                    let h_ent = '', f_ent = '', h_sal = '', f_sal = '';
                    if (item.hora_entrada_1) {
                        const d = new Date(item.hora_entrada_1);
                        h_ent = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        f_ent = d.toLocaleDateString();
                    }
                    if (item.hora_salida_1) {
                        const d = new Date(item.hora_salida_1);
                        h_sal = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        f_sal = d.toLocaleDateString();
                    }
                    rooms.push({
                        id: item.numero.toString().padStart(3, '0'),
                        status: item.status || 'X',
                        floor: item.piso || 'PB',
                        capacidad: parseInt(item.capacidad_hab) || 2,
                        tipoEstadia: item.tipo_estadia || '',
                        dias: parseInt(item.noches) || 0,
                        huespedes: item.huespedes || [],
                        formaPago: item.forma_pago || '',
                        precio: parseFloat(item.total) || parseFloat(item.precio_base) || 0,
                        precio_base: parseFloat(item.precio_base) || 0,
                        horaEntrada: h_ent,
                        fechaEntrada: f_ent,
                        horaSalida: h_sal,
                        fechaSalida: f_sal,
                        shaded: !!item.sombreado,
                        adultos: parseInt(item.adultos) || 0,
                        ninos: parseInt(item.ninos) || 0,
                        ocupacion_total: parseInt(item.ocupacion_total) || 0,
                        extra: parseInt(item.num_personas_ext) || 0,
                        obs: item.observaciones || '',
                        registro_id: item.registro_id || null,
                        id_db: item.habitacion_id || null,
                        tipoHab: item.tipo_habitacion || '',
                        services: item.services || [], // 🔥 Cargado desde el servidor
                        payments: item.payments || [],
                        incluir_en_reporte: !!parseInt(item.incluir_en_reporte)
                    });
                });

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
                th.className = `w-[${col.w}] header-filter text-center font-black uppercase text-[10px] px-4 py-4 ${col.hidden ? 'hidden' : ''}`;
                th.tabIndex = 0;
                let filterHtml = '';
                if (col.filter === 'text') {
                    filterHtml = `<div class="filter-dropdown"><p class="text-[9px] font-black uppercase mb-2 text-blue-600">Buscar en ${col.label}</p><input type="text" class="bg-slate-100 p-2 rounded text-xs w-full font-bold" oninput="updateFilter('${col.id}', this.value)" placeholder="..." onclick="event.stopPropagation()"></div>`;
                } else if (col.filter === 'select') {
                    filterHtml = `<div class="filter-dropdown"><p class="text-[9px] font-black uppercase mb-2 text-blue-600">Categoría</p><select class="bg-slate-100 p-2 rounded text-xs w-full font-bold" onchange="updateFilter('${col.id}', this.value)" onclick="event.stopPropagation()"><option value="">Todos</option>${getOptionsFor(col.id)}</select></div>`;
                }
                th.innerHTML = `<div class="flex items-center justify-center space-x-2"><span>${col.label}</span>${col.filter !== 'none' ? '<i class="fas fa-filter text-[8px] opacity-40"></i>' : ''}</div>${filterHtml}`;
                headerRow.appendChild(th);
            });
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
            const filtered = rooms.filter(r => {
                if (r.floor !== currentFloor) return false;
                const titular = (r.huespedes.find(h => h.isTitular)?.nombre || '') + ' ' + (r.huespedes.find(h => h.isTitular)?.apellido || r.huespedes.find(h => h.isTitular)?.apellidos || '');
                
                // Global Search
                if (search && !r.id.includes(search) && !titular.toLowerCase().includes(search)) return false;

                if (activeFilters.status && r.status !== activeFilters.status) return false;
                if (activeFilters.id && !r.id.includes(activeFilters.id)) return false;
                if (activeFilters.stay && r.tipoEstadia !== activeFilters.stay) return false;
                if (activeFilters.titular && !titular.toLowerCase().includes(activeFilters.titular.toLowerCase())) return false;
                if (activeFilters.payment && r.formaPago !== activeFilters.payment) return false;
                if (activeFilters.days && r.dias.toString() !== activeFilters.days) return false;
                if (activeFilters.people && r.huespedes.length.toString() !== activeFilters.people) return false;
                if (activeFilters.price && !r.precio.toString().includes(activeFilters.price)) return false;
                if (activeFilters.extra && Math.max(0, r.huespedes.length - r.capacidad).toString() !== activeFilters.extra) return false;
                const tipoFiltro = document.getElementById('filter-tipo-hab').value;
                if (tipoFiltro && r.tipoHab !== tipoFiltro) return false;
                return true;
            });
            filtered.forEach(r => {
                const realIdx = rooms.indexOf(r);
                const titular = r.huespedes.find(h => h.isTitular) || { nombre: '', apellido: '', apellidos: '' };
                const isActive = r.huespedes.length > 0;
                const isCheckout = r.status === 'CHECKOUT';
                const tr = document.createElement('tr');
                tr.className = `status-${r.status} transition-all hover:bg-slate-50 ${r.incluir_en_reporte ? 'row-shaded' : ''}`;
                tr.innerHTML = `
                    <td class="font-black p-0">
                        <div class="status-icon-${r.status} flex items-center justify-center h-full w-full min-h-[48px]">
                            ${renderSelectStatus(r.status, realIdx)}
                        </div>
                    </td>
                    <td class="hidden">${r.registro_id || ''}</td>
                    <td class="text-center font-black text-lg text-slate-800">${r.id}</td>
                    <td class="text-center">
                        ${renderSelectEstadia(r.tipoEstadia, realIdx, isCheckout)}
                    </td>
                    <td class="text-center font-black"><input type="number" value="${r.dias}" ${isCheckout ? 'disabled' : ''} onchange="updateDays(${realIdx}, this.value)" class="w-full text-center bg-transparent font-black outline-none"></td>
                    <td class="text-center font-black" ondblclick="editPeople(event, ${realIdx})" title="Doble clic para editar">
                        <span id="pers-val-${realIdx}" class="cursor-pointer hover:text-blue-600 transition-colors">${r.ocupacion_total}</span>
                    </td>
                    <td class="text-center"><button onclick="openRegister(${realIdx})" class="bg-blue-600 text-white text-[10px] px-4 py-2 rounded-xl font-black active:scale-95 transition-all">REGISTRAR</button></td>
                    
                        <td class="text-center">
                        <div class="flex items-center justify-center space-x-4">
                            ${renderSelectFormaPago(r.formaPago, realIdx, isCheckout)}
                            <input type="checkbox" 
                                onchange="toggleReporteYShade(${realIdx}, this.checked)" 
                                ${r.incluir_en_reporte ? 'checked' : ''} 
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" 
                                title="Marcar como Pagado / Incluir en Reporte">
                        </div>
                    </td>
                    <td class="text-right font-black text-blue-700">
                        <input type="number" step="0.01" value="${r.precio.toFixed(2)}" ${isCheckout ? 'disabled' : ''} onchange="updatePrice(${realIdx}, this.value)" class="w-full text-right bg-transparent font-black outline-none text-blue-700">
                    </td>
                    <td class="text-[10px] font-bold leading-tight text-slate-400">
                        ${r.horaEntrada ? `<div>${r.horaEntrada}</div><div class="text-[9px] opacity-60">${r.fechaEntrada}</div>` : '-'}
                    </td>
                    <td class="text-center"><button onclick="stampTime(${realIdx}, 'entrada')" ${isCheckout ? 'disabled' : ''} class="text-blue-500"><i class="fas fa-plus-circle"></i></button></td>
                    <td class="text-[10px] font-bold leading-tight text-slate-400">
                        ${r.horaSalida ? `<div>${r.horaSalida}</div><div class="text-[9px] opacity-60">${r.fechaSalida}</div>` : '-'}
                    </td>
                    <td class="text-center"><button onclick="stampTime(${realIdx}, 'salida')" ${isCheckout ? 'disabled' : ''} class="text-rose-500"><i class="fas fa-plus-circle"></i></button></td>
                    <td class="font-black uppercase truncate text-slate-700 max-w-[200px]">${titular.nombre} ${titular.apellido || titular.apellidos || ''}</td>
                    <td class="text-center font-black text-sm ${r.extra > 0 ? 'text-orange-600' : 'text-slate-300'}">${r.extra}</td>
                    <td class="text-center"><button onclick="openOptionsMenu(${realIdx})" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase ${(!isActive && r.status !== 'CHECKOUT') ? 'opacity-20 pointer-events-none' : ''}">Opciones</button></td>
                    <td class="text-center"><button onclick="openTicketPreview(${realIdx})" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase ${(!isActive && r.status !== 'CHECKOUT') ? 'opacity-20 pointer-events-none' : ''}">Ticket</button></td>
                `; tbody.appendChild(tr);
            });
            document.getElementById('count-active').textContent = rooms.filter(r => r.huespedes.length > 0).length;
        }

        function renderSelectStatus(currentStatus, realIdx) {
            let html = `<select onchange="updateStatus(${realIdx}, this.value)" class="bg-transparent font-black text-[11px] uppercase outline-none cursor-pointer text-center min-w-[70px]">`;
            catalogoEstados.forEach(e => {
                html += `<option value="${e.codigo}" ${currentStatus === e.codigo ? 'selected' : ''}>${e.nombre}</option>`;
            });
            html += `</select>`;
            return html;
        }

        async function updateStatus(idx, val) { 
            rooms[idx].status = val; 
            renderGrid();
            
            // Mapeo inverso de Código -> ID
            const mapId = { 'S': 1, 'X': 2, 'M': 3, 'P': 4, 'VAP': 5, 'R': 6 };
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
            rooms[idx].precio = rooms[idx].dias * rooms[idx].precio_base;
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }
        async function updateDays(idx, val) { 
            rooms[idx].dias = parseInt(val) || 0; 
            rooms[idx].precio = rooms[idx].dias * rooms[idx].precio_base;
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }

        // ✏️ EDICIÓN INLINE DE PERSONAS (PERS)
        function editPeople(e, idx) {
            const r = rooms[idx];
            if (!r.registro_id) return; // Sin registro no se puede editar
            const td = e.currentTarget;
            const span = td.querySelector('span');
            if (!span || td.querySelector('input')) return; // Ya hay un input activo

            const currentVal = r.ocupacion_total || 0;
            span.classList.add('hidden');

            const input = document.createElement('input');
            input.type = 'number';
            input.min = 1;
            input.max = 20;
            input.value = currentVal;
            input.className = 'w-14 text-center bg-blue-50 border-2 border-blue-400 rounded-lg font-black text-slate-800 outline-none text-sm p-1';

            const save = async () => {
                const newVal = parseInt(input.value);
                if (!isNaN(newVal) && newVal >= 0) {
                    await updatePeople(idx, newVal);
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

        async function updatePeople(idx, val) {
            const r = rooms[idx];
            if (!r.registro_id) return;

            rooms[idx].ocupacion_total = val;
            // Actualizar span visualmente sin re-renderizar toda la grid
            const span = document.getElementById(`pers-val-${idx}`);
            if (span) span.textContent = val;

            try {
                const fd = new FormData();
                fd.append('registro_id', r.registro_id);
                fd.append('campo', 'ocupacion_total');
                fd.append('valor', val);
                const resp = await fetch(base_url + 'reservacion/actualizar-campo', { method: 'POST', body: fd });
                const res = await resp.json();
                if (res.success) {
                    showToast('Personas actualizado');
                } else {
                    showToast('Error al guardar');
                }
            } catch (err) {
                console.error(err);
                showToast('Error de conexión');
            }
        }
        async function updatePayment(idx, val) { 
            rooms[idx].formaPago = val; 
            renderGrid(); 
            await sincronizarFilaAsync(idx);
        }
        async function updatePrice(idx, val) {
            rooms[idx].precio = parseFloat(val) || 0;
            renderGrid();
            await sincronizarFilaAsync(idx);
        }

        async function sincronizarFilaAsync(idx) {
            const r = rooms[idx];
            if (!r.registro_id) {
                console.warn(`⚠️ Omitiendo sincronización: La habitación ${r.id} no tiene registro_id.`);
                return;
            }
            try {
                const formData = new FormData();
                formData.append('registro_id', r.registro_id);
                formData.append('noches', r.dias);
                formData.append('precio', r.precio);
                formData.append('tipo_estadia_id', r.tipoEstadia || '');
                formData.append('forma_pago_id', r.formaPago || '');

                const response = await fetch(base_url + "reservacion/actualizar-campo", {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                if (!data.success) throw new Error(data.msg || "Error desconocido");
                showToast("✔ Sincronizado en BD");
            } catch (e) {
                console.error(e);
                showToast("❌ Error al sincronizar");
            }
        }
        function toggleShade(idx, checked) { rooms[idx].shaded = checked; renderGrid(); }
        function stampTime(idx, type) {
            const now = new Date();
            const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            if (type === 'entrada') rooms[idx].horaEntrada = time;
            else rooms[idx].horaSalida = time;
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
            el.classList.add('flex');
            el.classList.add('modal-active');

            if (modalId === 'modal-register-sub') openModalRegistry();
            else if (modalId === 'modal-estado-cuenta') showEstadoCuentaModal();
            else if (modalId === 'modal-roomservice') openModalRoomService();
            else if (modalId === 'modal-pagos') openModalPagos();
            else if (modalId === 'modal-cambio') openModalCambio();
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;

            el.classList.add('hidden');
            el.classList.remove('flex');
            el.classList.remove('modal-active');

            if (optionsMenuCaller && (id !== 'modal-options-menu')) {
                optionsMenuCaller = false;
                if (selectedRoomIdx !== null) openOptionsMenu(selectedRoomIdx);
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

            // Configurar visibilidad de botones según estado
            const isCheckout = r.status === 'CHECKOUT';
            const btnNuevo = document.getElementById('btn-nuevo-huesped');
            const btnSincronizar = document.getElementById('btn-sincronizar');
            
            if (isCheckout) {
                if (btnNuevo) btnNuevo.classList.add('hidden');
                if (btnSincronizar) btnSincronizar.classList.add('hidden');
            } else {
                if (btnNuevo) btnNuevo.classList.remove('hidden');
                if (btnSincronizar) btnSincronizar.classList.remove('hidden');
            }

            setTimeout(initSignaturePad, 500);
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
                elList.className = currentTotal > max ? 'text-[10px] font-black text-rose-600' : 'text-[10px] font-black text-blue-700';
            }

            // 2. Contador en el formulario (Captura Individual)
            const elForm = document.getElementById('form-person-counter');
            if (elForm) {
                let personNum = (editingIdx !== null && editingIdx > -1) ? (editingIdx + 1) : (currentTotal + 1);
                
                if (personNum <= max) {
                    elForm.textContent = `${personNum} de ${max}`;
                    elForm.className = "text-xl font-black text-blue-600 italic tracking-tighter uppercase";
                } else {
                    elForm.textContent = `Extra (+${personNum - max})`;
                    elForm.className = "text-xl font-black text-orange-600 italic tracking-tighter uppercase";
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
            document.getElementById('f-is-minor').checked = false;
            document.getElementById('f-is-extra').checked = false;
            document.getElementById('f-responsable').value = '';
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
                document.getElementById('f-is-minor').checked = g.es_menor == 1;
                document.getElementById('f-is-extra').checked = g.es_extra == 1;
                document.getElementById('f-responsable').value = g.Responsable_menor || '';
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
            if (isMinor) {
                respDiv.classList.remove('hidden');
                addressDiv.classList.add('opacity-30', 'pointer-events-none', 'grayscale');
            } else {
                respDiv.classList.add('hidden');
                addressDiv.classList.remove('opacity-30', 'pointer-events-none', 'grayscale');
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

        function addGuestFromDBByIndex(idx, isTitular, mode) {
            const g = lastSearchResults[idx];
            if (!g) return;
            const guest = {
                ...g,
                isTitular: isTitular,
                fotografia: g.fotografia || null,
                identificacion: g.identificacion || null,
                parentesco: isTitular ? 'TITULAR' : (g.parentesco || 'CONOCIDO')
            };
            if (isTitular) tempGuests.forEach(tg => tg.isTitular = false);
            tempGuests.push(guest);
            const resBoxId = mode === 'header' ? 'db-search-results-header' : 'db-search-results';
            const searchInputId = mode === 'header' ? 'client-db-search-header' : 'client-db-search';
            document.getElementById(resBoxId).style.display = 'none';
            document.getElementById(searchInputId).value = '';
            showToast(isTitular ? "Titular añadido" : "Acompañante añadido");
            backToGuestList();
        }
        /* --- OPERACIONES GLOBALES (ROOM SERVICE / CAMBIO) --- */
        function openModalRoomService() {
            const select = document.getElementById('rs-room-selector');
            select.innerHTML = '<option value="">Seleccione habitación...</option>';
            rooms.forEach((r, idx) => {
                if (r.huespedes && r.huespedes.length > 0) {
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

        function openModalCambio() {
            const originSelect = document.getElementById('cambio-origin-select');
            const destSelect = document.getElementById('cambio-new-select');
            
            originSelect.innerHTML = '<option value="">Seleccione origen...</option>';
            destSelect.innerHTML = '<option value="">Seleccione destino...</option>';
            
            rooms.forEach((r, idx) => {
                const titular = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
                if (r.huespedes.length > 0) {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `Hab ${r.id} - ${titular.nombre} ${titular.apellido || titular.apellidos || ''}`;
                    originSelect.appendChild(opt);
                } else if (r.status === 'X' || r.status === 'Disponible') {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `Hab ${r.id} - ${r.tipoHab} ($${r.precio || 0})`;
                    destSelect.appendChild(opt);
                }
            });

            if (selectedRoomIdx !== null && rooms[selectedRoomIdx].huespedes.length > 0) {
                originSelect.value = selectedRoomIdx;
            }
            
            document.getElementById('modal-cambio').classList.add('modal-active');
        }

        async function executeRoomChange() {
            const originIdx = document.getElementById('cambio-origin-select').value;
            const newIdx = document.getElementById('cambio-new-select').value;
            const motivo = document.getElementById('cambio-motivo').value;

            if (!originIdx) return showToast("SELECCIONE HABITACIÓN DE ORIGEN");
            if (!newIdx) return showToast("SELECCIONE HABITACIÓN DE DESTINO");
            
            const oldRoom = rooms[originIdx];
            const newRoom = rooms[newIdx];
            
            try {
                showToast("Procesando cambio...");
                // TODO: Llamar al servidor para persistir el cambio
                
                newRoom.huespedes = [...oldRoom.huespedes];
                newRoom.services = [...(oldRoom.services || [])];
                newRoom.payments = [...(oldRoom.payments || [])];
                newRoom.status = 'S'; // Pasa a sucia/ocupada
                newRoom.tipoEstadia = oldRoom.tipoEstadia;
                
                oldRoom.huespedes = [];
                oldRoom.services = [];
                oldRoom.payments = [];
                oldRoom.status = 'X'; // Pasa a limpia/vacia
                oldRoom.tipoEstadia = '';
                
                closeModal('modal-cambio');
                showToast(`CAMBIO EXITOSO: HAB ${oldRoom.id} -> ${newRoom.id}`);
                renderGrid();
            } catch (e) {
                console.error(e);
                showToast("Error al procesar el cambio");
            }
        }

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
                es_menor: document.getElementById('f-is-minor').checked ? 1 : 0,
                es_extra: document.getElementById('f-is-extra').checked ? 1 : 0,
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
                // Si es acompañante, solo lo guardamos localmente por ahora
                if (selectedGuestEditIdx === -1) tempGuests.push(d); else tempGuests[selectedGuestEditIdx] = d;
                backToGuestList();
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
                        Responsable_menor: a.Responsable_menor || null,
                        es_extra: a.es_extra || 0,
                        fotografia: a.fotografia || null,
                        identificacion: a.identificacion || null,
                        firma_path: a.firma_path || null
                    }))
                })
            });
            const resp = await response.json();
            if (!resp.ok) throw new Error(resp.msg || "Error guardando acompañantes");
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
                r.status = 'S';
                
                // 4. Actualizar estado de habitación a OCUPADA
                await actualizarEstadoHabitacionAsync(r.id_db || r.id, 1);

                closeModal('modal-register');
                renderGrid();
                showToast("Registro sincronizado correctamente");
            } catch (err) {
                console.error(err);
                showToast("Error al sincronizar: " + err.message);
            }
        }

        async function sincronizarHabitacionDB(idx) {
            const r = rooms[idx];
            if (!r.registro_id) {
                console.warn(`⚠️ Omitiendo sincronización: La habitación ${r.id} no tiene registro_id activo.`);
                return null; // Ya no se crean registros nuevos desde aquí
            }

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
            const subtotal = parseFloat(r.precio) || 0;
            const iva = subtotal * 0.16;
            const ish = subtotal * 0.03;
            const total = subtotal + iva + ish;

            const payload = {
                id: r.registro_id, // 🔥 Clave Primaria Estricta
                huesped_id: huespedId,
                habitacion_id: r.id_db || r.id, 
                hora_entrada: r.fechaEntrada ? `${r.fechaEntrada.split('/').reverse().join('-')} ${r.horaEntrada || '12:00:00'}` : new Date().toISOString().slice(0, 19).replace('T', ' '),
                hora_salida: r.dias ? new Date(Date.now() + r.dias * 86400000).toISOString().slice(0, 10) : new Date().toISOString().slice(0, 10),
                noches: nights,
                tipo_estadia_id: r.tipoEstadia === 'S' ? 1 : (r.tipoEstadia === 'SQ' ? 2 : 3),
                forma_pago_id: r.formaPago || 1,
                precio: subtotal,
                precio_base: basePrice,
                iva: iva,
                ish: ish,
                total: total,
                adultos: r.huespedes.filter(g => g.es_menor != 1 && g.es_extra != 1).length,
                niños: r.huespedes.filter(g => g.es_menor == 1).length,
                num_personas_ext: r.huespedes.filter(g => g.es_extra == 1).length
            };

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
            return resp;
        }

        async function actualizarEstadoHabitacionAsync(habitacionId, estadoId) {
            return fetch(base_url + "habitaciones/actualizar-estado", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ habitacion_id: habitacionId, estado_id: estadoId })
            }).then(r => r.json());
        }

        async function marcarHabitacionLimpiaAsync(habitacionId) {
            const resp = await actualizarEstadoHabitacionAsync(habitacionId, 1);
            if (!resp.ok) throw new Error(resp.msg || "Error al marcar como limpia");
            return resp;
        }

        function renderGuestList() {
            const c = document.getElementById('guests-list-container');
            const r = rooms[selectedRoomIdx];
            c.innerHTML = tempGuests.map((g, i) => {
                const badgeExtra = g.es_extra == 1 ? '<span class="ml-3 bg-orange-500 text-white text-[9px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm border border-orange-600">Persona Extra</span>' : '';
                return `<div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border shadow-sm"><div class="flex items-center space-x-6"><div class="w-12 h-12 rounded-xl ${g.isTitular ? 'bg-blue-600' : (g.es_extra == 1 ? 'bg-orange-500' : 'bg-slate-200')} text-white flex items-center justify-center font-black">${i + 1}</div><div class="flex flex-col"><p class="font-black text-slate-800 uppercase flex items-center">${g.nombre} ${g.apellido || g.apellidos || ''} ${badgeExtra}</p></div></div><button onclick="goToForm(${i})" class="text-blue-500 p-2"><i class="fas fa-pen"></i></button></div>`;
            }).join('');
            if (!tempGuests.length) c.innerHTML = `<p class="col-span-2 text-center text-slate-400 font-bold p-10 uppercase text-xs">Sin registros</p>`;

            // 🔥 RE-EVALUAR BOTÓN CHECKOUT
            const btnCheckout = document.getElementById('btn-checkout-footer');
            const rRoom = rooms[selectedRoomIdx];
            const hasTitular = tempGuests.some(g => g.isTitular);
            if (btnCheckout && rRoom) {
                if (rRoom.registro_id && hasTitular) {
                    btnCheckout.classList.remove('hidden');
                } else {
                    btnCheckout.classList.add('hidden');
                }
            }
        }

        function deleteGuestInForm() { if (selectedGuestEditIdx > -1) { tempGuests.splice(selectedGuestEditIdx, 1); backToGuestList(); } }

        /* --- MÓDULO MOVER HUÉSPEDES (TRASLADOS) --- */
        function openSubModal(id) {
            // Mostrar modal PRIMERO, luego inicializar contenido
            document.getElementById(id).classList.add('modal-active');
            if (id === 'modal-cambio') setTimeout(prepareMoveModal, 50);
            if (id === 'modal-roomservice') setTimeout(renderServicesSummary, 50);
            if (id === 'modal-pagos') setTimeout(renderPaymentsHistory, 50);
        }

        function prepareMoveModal() {
            const sourceSelect = document.getElementById('cambio-source-room');
            if (!sourceSelect) return;
            sourceSelect.innerHTML = '<option value="">Seleccionar Origen...</option>';
            
            // 1. Cargar Orígenes (Habitaciones con Check-in activo)
            rooms.forEach((r, idx) => {
                if (r.registro_id && r.huespedes.length > 0) {
                    const titular = r.huespedes.find(h => h.isTitular);
                    const name = titular ? (titular.nombre + ' ' + (titular.apellido || titular.apellidos || '')) : 'Huésped';
                    sourceSelect.innerHTML += `<option value="${idx}">Hab. ${r.id} - ${name}</option>`;
                }
            });

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
            validateMoveForm();
        }

        // Almacena la lista de rooms válidos para destino (para el buscador)
        let _destRooms = [];

        function renderMoveTargetRooms() {
            const sel = document.getElementById('cambio-dest-select');
            if (!sel) return;

            sel.innerHTML = '';
            _destRooms = [];

            // Agrupar por piso
            const pisos = {};
            rooms.forEach((r, idx) => {
                const s = (r.status || '').toUpperCase();
                const isClean = s === 'X';
                const hasNoGuests = !r.huespedes || r.huespedes.length === 0;
                const isSource = selectedMoveSourceIdx !== null && selectedMoveSourceIdx == idx;

                if (isClean && hasNoGuests && !isSource) {
                    const piso = r.floor || 'PB';
                    if (!pisos[piso]) pisos[piso] = [];
                    pisos[piso].push({ idx, r });
                    _destRooms.push({ idx, r, piso });
                }
            });

            if (_destRooms.length === 0) {
                sel.innerHTML = '<option disabled value="">No hay habitaciones limpias disponibles</option>';
                return;
            }

            // Crear optgroups por piso
            Object.keys(pisos).sort().forEach(piso => {
                const group = document.createElement('optgroup');
                group.label = `Piso ${piso}`;
                pisos[piso].forEach(({ idx, r }) => {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `${r.id}  —  ${r.tipoHab}`;
                    group.appendChild(opt);
                });
                sel.appendChild(group);
            });

            console.log('✅ [TRASLADO] Destinos disponibles:', _destRooms.length);
        }

        function filterDestSelect(query) {
            const sel = document.getElementById('cambio-dest-select');
            if (!sel) return;

            const q = query.trim().toLowerCase();
            sel.innerHTML = '';

            const filtrados = q ? _destRooms.filter(({ r }) =>
                r.id.toLowerCase().includes(q) ||
                (r.tipoHab || '').toLowerCase().includes(q) ||
                (r.floor || '').toLowerCase().includes(q)
            ) : _destRooms;

            if (filtrados.length === 0) {
                sel.innerHTML = '<option disabled value="">Sin resultados</option>';
                return;
            }

            // Reagrupar por piso
            const pisos = {};
            filtrados.forEach(item => {
                const p = item.piso || 'PB';
                if (!pisos[p]) pisos[p] = [];
                pisos[p].push(item);
            });

            Object.keys(pisos).sort().forEach(piso => {
                const group = document.createElement('optgroup');
                group.label = `Piso ${piso}`;
                pisos[piso].forEach(({ idx, r }) => {
                    const opt = document.createElement('option');
                    opt.value = idx;
                    opt.textContent = `${r.id}  —  ${r.tipoHab}`;
                    group.appendChild(opt);
                });
                sel.appendChild(group);
            });
        }

        function selectMoveTargetFromSelect(value) {
            if (value === '' || value === null) return;
            selectedMoveTargetIdx = parseInt(value);
            const r = rooms[selectedMoveTargetIdx];
            const label = document.getElementById('move-dest-label');
            if (label && r) label.textContent = `HAB. ${r.id} — ${r.tipoHab} (Piso ${r.floor})`;
            validateMoveForm();
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
            if (selectedMoveSourceIdx === null || selectedMoveTargetIdx === null) return;

            const source = rooms[selectedMoveSourceIdx];
            const target = rooms[selectedMoveTargetIdx];
            const motivo = document.getElementById('move-motivo').value;

            try {
                const resp = await fetch(base_url + "reservacion/cambiar-habitacion", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        registro_id: source.registro_id,
                        habitacion_id: target.id_db,
                        motivo: motivo,
                        confirmar_upgrade: confirmado
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

        /* --- IMPRIMIR REGISTRO --- */
        function openModalRegistry() {
            activePreviewMode = 'registry'; const r = rooms[selectedRoomIdx]; const t = r.huespedes.find(h => h.isTitular) || r.huespedes[0];
            if (!t) return showToast("No hay huéspedes registrados");
            document.getElementById('btn-final-action').textContent = "Confirmar Impresión";
            document.getElementById('preview-content').innerHTML = `
                <div class="registry-paper">
                    <div class="flex justify-between items-start border-b-4 border-blue-600 pb-8 mb-10">
                        <div><h1 class="text-5xl font-black italic text-blue-600 tracking-tighter">HOTELOS<span class="text-slate-900 not-italic ml-1">V2</span></h1><p class="text-[11px] font-black uppercase text-slate-400 mt-2">Folio Oficial de Registro</p></div>
                        <div class="text-right"><p class="font-black text-2xl">HAB: ${r.id}</p><p class="text-slate-500 font-bold uppercase text-xs">${r.fechaEntrada}</p></div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-12 gap-y-6 mb-12">
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Huésped</label><p class="text-lg font-black uppercase">${t.nombre} ${t.apellidos}</p></div>
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Teléfono</label><p class="text-lg font-bold">${t.telefono || '---'}</p></div>
                        <div class="col-span-2 border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Dirección</label><p class="text-lg font-bold uppercase">${t.direccion || '---'}, ${t.ciudad || ''}</p></div>
                        <div class="border-b pb-2"><label class="text-[9px] font-black text-blue-600 uppercase block mb-1">Placas</label><p class="text-lg font-mono font-bold uppercase">${t.placas || 'N/A'}</p></div>
                    </div>
                    <div class="bg-slate-900 text-white p-8 rounded-3xl grid grid-cols-4 text-center mb-20 shadow-xl border-8 border-white">
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Unidad</p><p class="text-3xl font-black">${r.id}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Pernoctas</p><p class="text-3xl font-black">${r.dias}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Pers.</p><p class="text-3xl font-black">${r.huespedes.length}</p></div>
                        <div><p class="text-[10px] font-black opacity-40 uppercase">Tarifa</p><p class="text-3xl font-black">$${basePrice}</p></div>
                    </div>
                    <div class="flex justify-between items-center space-x-20">
                        <div class="flex-1 border-t-2 border-slate-900 pt-4 text-center"><p class="font-black text-[10px] uppercase">Firma Huésped</p></div>
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
        function executeFinalAction() { if (activePreviewMode === 'ticket') { const r = rooms[selectedRoomIdx]; r.huespedes = []; r.services = []; r.payments = []; r.status = 'sucia'; r.precio = 0; closeModal('modal-preview'); renderGrid(); showToast("Checkout procesado"); } else { closeModal('modal-preview'); } }

        /* --- CARGOS Y PAGOS --- */
        function addService(name, price) { rooms[selectedRoomIdx].services.push({ id: Date.now(), name, price, t: new Date().toLocaleTimeString(), date: new Date().toLocaleDateString() }); updateTotalPrice(); renderServicesSummary(); showToast("Cargado: " + name); }
        function removeService(sId) { const idx = rooms[selectedRoomIdx].services.findIndex(s => s.id === sId); if (idx > -1) { rooms[selectedRoomIdx].services.splice(idx, 1); updateTotalPrice(); renderServicesSummary(); showToast("Cargo eliminado"); } }
        function updateTotalPrice() { const r = rooms[selectedRoomIdx]; r.precio = (r.dias * basePrice) + r.services.reduce((a, b) => a + b.price, 0); renderGrid(); }
        function renderServicesSummary() { const container = document.getElementById('services-summary'); container.innerHTML = (rooms[selectedRoomIdx].services || []).map(s => `<div class="flex justify-between items-center bg-white p-4 rounded-2xl border shadow-sm group"><div><p class="font-black text-xs uppercase">${s.name}</p><p class="text-[9px] text-slate-400 font-bold">${s.t}</p></div><div class="flex items-center space-x-6"><span class="font-black text-orange-600 text-sm">$${s.price.toFixed(2)}</span><button onclick="removeService(${s.id})" class="text-rose-500 opacity-20 group-hover:opacity-100 transition-all"><i class="fas fa-trash"></i></button></div></div>`).join(''); }
        function registerPayment() { const amt = parseFloat(document.getElementById('pay-amount').value); if (amt > 0) { rooms[selectedRoomIdx].payments.push({ amount: amt, method: document.getElementById('pay-method').value, time: new Date().toLocaleTimeString(), date: new Date().toLocaleDateString() }); document.getElementById('pay-amount').value = ''; renderPaymentsHistory(); showToast("Abono registrado"); } }
        function renderPaymentsHistory() { document.getElementById('payments-history').innerHTML = rooms[selectedRoomIdx].payments.map(p => `<div class="flex justify-between items-center bg-white p-4 rounded-2xl border mb-2"><div><p class="font-black text-xs uppercase">${p.method}</p><p class="text-[9px] text-slate-400">${p.date} ${p.time}</p></div><span class="font-black text-emerald-600">$${p.amount.toFixed(2)}</span></div>`).join(''); }

        /* --- AUXILIARES --- */
        function openOptionsMenu(idx) { selectedRoomIdx = idx; document.getElementById('menu-opt-hab').textContent = `HABITACIÓN ${rooms[idx].id}`; document.getElementById('opt-obs-input').value = rooms[idx].obs || ''; document.getElementById('modal-options-menu').classList.add('modal-active'); }
        function updateRoomObs(v) { rooms[selectedRoomIdx].obs = v; }
        function showView(v) { 
            const viewWork = document.getElementById('view-work');
            if (viewWork) viewWork.classList.toggle('hidden', v === 'home');
            const regList = document.getElementById('reg-view-list');
            if (regList) regList.classList.toggle('hidden', v !== 'reg-list');
            const regForm = document.getElementById('reg-view-form');
            if (regForm) regForm.classList.toggle('hidden', v !== 'reg-form');
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
                
                data.forEach((p, i) => {
                    const btn = document.createElement("button");
                    btn.dataset.floor = p.Piso;
                    
                    let btnText = p.Piso;
                    if (btnText === 'PB' || btnText.toLowerCase().includes('piso')) {
                        btn.innerText = btnText;
                    } else {
                        btn.innerText = "PISO " + btnText;
                    }

                    if (i === 0) {
                        btn.className = "bg-blue-600 px-5 py-1.5 rounded-lg text-xs font-black shadow-lg";
                        currentFloor = p.Piso;
                    } else {
                        btn.className = "px-5 py-1.5 rounded-lg text-xs font-bold text-slate-400";
                    }
                    
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
                cargarTiposHabitacion()
            ]);
            initData();
            setInterval(() => document.getElementById('clock').textContent = new Date().toLocaleTimeString(), 1000);
        };

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
                    const formData = new FormData();
                    formData.set('registro_id', r.registro_id);
                    formData.set('incluir_en_reporte', status ? 1 : 0);

                    const resp = await fetch(base_url + 'reservacion/actualizar-campo', {
                        method: 'POST',
                        body: formData
                    });
                    
                    if (!resp.ok) throw new Error("Error en servidor");
                    const res = await resp.json();
                    
                    if (res.success) {
                        showToast(status ? "Pagado / Reporte" : "Pendiente");
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

        /* =====================================================
           📦 LÓGICA DE TRASLADO (MOVER HUÉSPEDES)
        ===================================================== */
        function prepareMoveModal() {
            const sourceSelect = document.getElementById('cambio-source-room');
            sourceSelect.innerHTML = '<option value="">Seleccionar Origen...</option>';
            
            // 1. Cargar Orígenes (Habitaciones con Check-in)
            rooms.forEach((r, idx) => {
                if (r.registro_id && r.huespedes.length > 0) {
                    const titular = r.huespedes.find(h => h.isTitular);
                    const name = titular ? (titular.nombre + ' ' + (titular.apellido || titular.apellidos || '')) : 'Huésped';
                    sourceSelect.innerHTML += `<option value="${idx}">Hab. ${r.id} - ${name}</option>`;
                }
            });

            // 2. Cargar Filtros de Destino
            const floorSelect = document.getElementById('cambio-filter-floor');
            const typeSelect = document.getElementById('cambio-filter-type');
            
            const floors = [...new Set(rooms.map(r => r.floor))].sort();
            const types = [...new Set(rooms.map(r => r.tipoHab))].sort();

            floorSelect.innerHTML = '<option value="">Todos los Pisos</option>';
            floors.forEach(f => floorSelect.innerHTML += `<option value="${f}">Piso ${f}</option>`);

            typeSelect.innerHTML = '<option value="">Todos los Tipos</option>';
            types.forEach(t => typeSelect.innerHTML += `<option value="${t}">${t}</option>`);

            // Limpiar estado
            selectedMoveSourceIdx = null;
            selectedMoveTargetIdx = null;
            document.getElementById('move-motivo').value = '';
            document.getElementById('move-dest-label').textContent = '---';
            document.getElementById('btn-confirm-move').disabled = true;
            document.getElementById('btn-confirm-move').classList.add('opacity-30', 'cursor-not-allowed');
            
            renderMoveTargetRooms();
        }

        function updateMoveSource() {
            selectedMoveSourceIdx = document.getElementById('cambio-source-room').value;
            validateMoveForm();
        }

        function renderMoveTargetRooms() {
            const grid = document.getElementById('cambio-grid');
            const floorF = document.getElementById('cambio-filter-floor').value;
            const typeF = document.getElementById('cambio-filter-type').value;
            
            grid.innerHTML = '';

            rooms.forEach((r, idx) => {
                // REGLA: Debe estar LIMPIA (no 'S', no 'M') y SIN CLIENTE
                const isClean = r.status === 'X' || r.status === 'DISPONIBLE' || r.status === 'L';
                const hasNoClient = !r.registro_id && r.huespedes.length === 0;

                if (isClean && hasNoClient) {
                    if (floorF && r.floor != floorF) return;
                    if (typeF && r.tipoHab != typeF) return;

                    const isSelected = selectedMoveTargetIdx == idx;
                    const card = document.createElement('div');
                    card.onclick = () => selectMoveTarget(idx);
                    card.className = `cursor-pointer p-4 rounded-2xl border-2 transition-all text-center flex flex-col items-center justify-center space-y-1 ${isSelected ? 'border-emerald-500 bg-emerald-50 shadow-lg scale-105' : 'border-slate-100 bg-white hover:border-blue-300'}`;
                    card.innerHTML = `
                        <span class="text-[10px] font-black text-slate-400 uppercase">${r.tipoHab}</span>
                        <span class="text-xl font-black text-slate-800">${r.id}</span>
                        <span class="text-[9px] font-bold text-emerald-600 uppercase">Limpia</span>
                    `;
                    grid.appendChild(card);
                }
            });
        }

        function selectMoveTarget(idx) {
            selectedMoveTargetIdx = idx;
            const r = rooms[idx];
            document.getElementById('move-dest-label').textContent = `HABITACIÓN ${r.id} (${r.tipoHab})`;
            renderMoveTargetRooms();
            validateMoveForm();
        }

        function validateMoveForm() {
            const motivo = document.getElementById('move-motivo').value.trim();
            const btn = document.getElementById('btn-confirm-move');
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
            if (selectedMoveSourceIdx === null || selectedMoveTargetIdx === null) return;

            const source = rooms[selectedMoveSourceIdx];
            const target = rooms[selectedMoveTargetIdx];
            const motivo = document.getElementById('move-motivo').value;

            try {
                const resp = await fetch(base_url + "reservacion/cambiar-habitacion", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        registro_id: source.registro_id,
                        habitacion_id: target.id_db,
                        motivo: motivo,
                        confirmar_upgrade: confirmado
                    })
                });

                const res = await resp.json();

                if (res.requiere_confirmacion) {
                    const ok = await Swal.fire({
                        title: 'Confirmar Upgrade',
                        html: `La nueva habitación es más cara.<br>Diferencia: <b>$${Number(res.diferencia).toLocaleString('es-MX')}</b><br><br>¿Proceder con el cargo?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, Cambiar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (ok.isConfirmed) executeRoomMove(true);
                    return;
                }

                if (res.success || res.ok) {
                    await Swal.fire('Éxito', `Traslado exitoso a la unidad ${target.id}`, 'success');
                    closeModal('modal-cambio');
                    initData();
                } else {
                    Swal.fire('Error', res.msg || "Error al cambiar habitación", 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Fallo de conexión', 'error');
            }
        }
    </script>
</body>

<?= view('layout/footer') ?>