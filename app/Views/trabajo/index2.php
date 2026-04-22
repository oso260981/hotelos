<?= view('layout/header') ?>



    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap');
        
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f1f5f9; 
            color: #1e293b; 
            touch-action: manipulation; /* Optimiza respuesta táctil */
        }
        
        /* Estatus de habitación con indicadores más gruesos para visualización rápida */
        .status-limpia { border-left: 14px solid #22c55e; }
        .status-sucia { border-left: 14px solid #f97316; }
        .status-mantenimiento { border-left: 14px solid #eab308; background-color: #fefce8; }
        .status-planta { border-left: 14px solid #6366f1; }
        .status-vap { border-left: 14px solid #ef4444; }

        /* Resaltado de fila para facturación o casos especiales */
        .row-shaded { 
            background-color: #e0f2fe !important; 
            outline: 3px solid #1e40af !important; 
            z-index: 10;
            position: relative;
        }

        /* Grid optimizado para dedos (más alto) */
        .hotel-grid td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            height: 64px;
            vertical-align: middle;
            background-color: white;
            font-size: 14px;
        }
        
        .hotel-grid th {
            background-color: #0c4a6e;
            color: white;
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 12px;
            text-transform: uppercase;
            padding: 14px 10px;
            position: sticky;
            top: 0;
            z-index: 30;
            border: 1px solid #075985;
        }

        /* Dropdown táctil para filtros */
        .filter-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            color: #334155;
            min-width: 200px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border-radius: 1rem;
            z-index: 50;
            border: 1px solid #e2e8f0;
            padding: 15px;
        }
        
        .header-filter:focus-within .filter-dropdown { display: block; }

        /* Estilo para selects táctiles grandes */
        select {
            appearance: none;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d=' Brave 19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 0.5rem center;
            background-size: 1.2em;
            padding-right: 2rem;
            width: 100%;
            cursor: pointer;
            border-radius: 0.5rem;
            background-color: transparent;
        }

        .modal-active { display: flex !important; }
        
        /* Formularios modo Tablet */
        .form-input-group label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            margin-left: 4px;
            margin-bottom: 6px;
            display: block;
        }
        .form-input {
            width: 100%;
            padding: 16px;
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 16px; /* Evita zoom automático en iOS */
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus { border-color: #3b82f6; background-color: white; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }

        .guest-item-card {
            cursor: pointer;
            padding: 1.25rem;
            border-radius: 1.25rem;
            border: 2px solid transparent;
            background: white;
            transition: all 0.2s;
        }
        .guest-item-card.active {
            background-color: #eff6ff;
            border-color: #3b82f6;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        /* Scrollbars más anchos para touch si es necesario */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>


   

    <!-- VISTA TRABAJO -->
    <section id="view-work" class="flex-1 flex flex-col bg-slate-200">
        <div class="bg-slate-900 p-2 flex space-x-4 items-center px-6">
            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-sm">Sucia</span>
            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-sm">X Limpia</span>
            <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-sm">Mantenimiento</span>
            <div class="flex-1"></div>
            <div class="relative">
                <input type="text" id="global-search" oninput="applyGlobalSearch()" placeholder="Búsqueda rápida..." class="bg-slate-800 text-white rounded-xl px-10 py-2 text-xs w-80 border border-slate-700 outline-none">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
            </div>
        </div>

        <div class="flex-1 overflow-auto bg-white">
            <table class="w-full hotel-grid table-fixed min-w-[2100px]">
                <thead>
                    <tr id="header-row">
                        <!-- Generado por JS -->
                    </tr>
                </thead>
                <tbody id="rooms-tbody"></tbody>
            </table>
        </div>

        <footer class="bg-slate-900 text-white p-3 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-[11px] font-black uppercase text-blue-400 tracking-tighter">PISO:</span>
                <div class="flex bg-slate-800 p-1 rounded-xl" id="floor-tabs">
                    <button onclick="changeFloor('PB')" id="btn-floor-PB" class="bg-blue-600 px-6 py-1.5 rounded-lg text-xs font-black shadow-lg">PB</button>
                    <button onclick="changeFloor('1')" id="btn-floor-1" class="px-6 py-1.5 rounded-lg text-xs font-bold text-slate-400">1°</button>
                    <button onclick="changeFloor('2')" id="btn-floor-2" class="px-6 py-1.5 rounded-lg text-xs font-bold text-slate-400">2°</button>
                    <button onclick="changeFloor('3')" id="btn-floor-3" class="px-6 py-1.5 rounded-lg text-xs font-bold text-slate-400">3°</button>
                </div>
            </div>
        </footer>
    </section>

     <!-- MODAL REGISTRO -->
    <div id="modal-register" class="fixed inset-0 bg-slate-900/90 hidden z-[100] items-center justify-center p-6 backdrop-blur-md">
        <div class="bg-white w-full max-w-[1300px] rounded-[3rem] shadow-2xl flex flex-col overflow-hidden max-h-[96vh] border border-white/20">
            
            <!-- HEADER MODAL -->
            <div class="bg-slate-900 p-6 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-6">
                    <div class="bg-blue-600 w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg"><i class="fas fa-user-check text-xl"></i></div>
                    <div>
                        <p class="text-[9px] font-black uppercase opacity-50 tracking-widest mb-1">Módulo de Admisión</p>
                        <h3 class="text-2xl font-black italic uppercase tracking-tighter" id="reg-room-id">Habitación --</h3>
                    </div>
                </div>
                <button onclick="closeModal('modal-register')" class="w-10 h-10 rounded-full flex items-center justify-center bg-white/10 active:scale-90 transition-all"><i class="fas fa-times"></i></button>
            </div>

            <!-- VISTA 1: LISTADO DE HUÉSPEDES -->
            <div id="reg-view-list" class="flex-1 overflow-y-auto p-12 space-y-8">
                <div class="flex justify-between items-center">
                    <h4 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight">Huéspedes Registrados</h4>
                    <button onclick="goToForm(-1)" class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black uppercase text-sm shadow-xl active:scale-95 transition-all"><i class="fas fa-plus-circle mr-3"></i>Nuevo Huésped</button>
                </div>
                
                <div id="guests-list-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cards dinámicas -->
                </div>

                <div class="pt-8 border-t flex justify-end">
                    <button onclick="confirmAllRegistration()" class="bg-emerald-500 text-white px-14 py-6 rounded-[2.5rem] font-black uppercase text-sm shadow-2xl active:scale-95 transition-all">Sincronizar Habitación</button>
                </div>
            </div>

            <!-- VISTA 2: FORMULARIO DE CAPTURA TERMINAL -->
            <div id="reg-view-form" class="hidden flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
                <div class="flex items-center space-x-6 mb-8">
                    <button onclick="backToGuestList()" class="w-12 h-12 rounded-xl bg-white shadow-md flex items-center justify-center text-slate-400 active:text-blue-600 transition-colors"><i class="fas fa-arrow-left text-lg"></i></button>
                    <div>
                        <h4 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter" id="form-title">Expediente de Identidad</h4>
                        <p class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Captura Multimodal</p>
                    </div>
                    <div class="flex-1"></div>
                    <!-- BUSCADOR DE CLIENTES EXISTENTES -->
                    <div class="relative w-96">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 text-sm font-black"><i class="fas fa-search"></i></span>
                        <input type="text" id="client-db-search" oninput="handleClientDBSearch(this.value)" placeholder="BUSCAR CLIENTE HISTÓRICO..." class="w-full bg-white border-2 border-blue-100 rounded-2xl pl-12 pr-6 py-4 text-xs font-black uppercase shadow-sm focus:border-blue-500 outline-none">
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
                                <button onclick="toggleCamera('box-client')" class="btn-action-pro bg-orange-500 text-white shadow-lg">
                                    <i class="fas fa-power-off mr-2"></i>Cámara
                                </button>
                                <button onclick="simulateAction('Capturando rostro...')" class="btn-action-pro bg-slate-900 text-white">
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
                                <button onclick="toggleCamera('box-id')" class="btn-action-pro bg-orange-500 text-white shadow-lg">
                                    <i class="fas fa-power-off mr-1"></i>On
                                </button>
                                <button onclick="simulateAction('Capturando identificación...')" class="btn-action-pro bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fas fa-image mr-1"></i>Capturar
                                </button>
                                <button onclick="runOCR()" class="btn-action-pro bg-blue-600 text-white shadow-lg shadow-blue-100">
                                    <i class="fas fa-expand-arrows-alt mr-1"></i>OCR
                                </button>
                            </div>
                        </div>

                        <!-- 3. Firma -->
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-pen-nib mr-3"></i>Firma Electrónica</div>
                            <div class="signature-container mb-4">
                                <div class="signature-pad-pro">
                                    <span class="text-slate-300 font-black uppercase text-[8px] tracking-[0.2em]">Hardware Externo</span>
                                    <canvas id="sig-canvas" class="absolute inset-0 w-full h-full cursor-crosshair"></canvas>
                                </div>
                            </div>
                            <button onclick="simulateAction('Iniciando terminal Topaz S460...')" class="w-full btn-action-pro bg-emerald-50 text-emerald-700 border border-emerald-100">
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
                                <div class="form-input-group"><label class="form-label">Nombre(s)</label><input type="text" id="f-name" class="form-input" placeholder="Nombre"></div>
                                <div class="form-input-group"><label class="form-label">Apellidos</label><input type="text" id="f-apellidos" class="form-input" placeholder="Apellidos"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div class="form-input-group"><label class="form-label">Teléfono Movil</label><input type="tel" id="f-tel" class="form-input" placeholder="000 000 0000"></div>
                                <div class="form-input-group"><label class="form-label">Correo Electrónico</label><input type="email" id="f-mail" class="form-input" placeholder="ejemplo@mail.com"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-5">
                                <div class="form-input-group"><label class="form-label">Nacionalidad</label><input type="text" id="f-nat" class="form-input" value="MEXICANA"></div>
                                <div class="form-input-group"><label class="form-label">Nacimiento</label><input type="date" id="f-birth" class="form-input"></div>
                                <div class="form-input-group">
                                    <label class="form-label">Género</label>
                                    <select id="f-gender" class="form-input">
                                        <option>Masculino</option><option>Femenino</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Panel: Documento e Identidad -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-file-invoice mr-3"></i>Documentación</div>
                            <div class="grid grid-cols-2 gap-5">
                                <div class="form-input-group">
                                    <label class="form-label">Tipo de Identificación</label>
                                    <select id="f-id-type" class="form-input">
                                        <option>INE</option><option>PASAPORTE</option><option>LICENCIA</option>
                                    </select>
                                </div>
                                <div class="form-input-group">
                                    <label class="form-label">Número de Folio / ID</label>
                                    <input type="text" id="f-id-num" class="form-input font-mono uppercase" placeholder="ABC000000">
                                </div>
                            </div>
                        </div>

                        <!-- Panel: Ubicación y Vehículo -->
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200">
                            <div class="form-section-title"><i class="fas fa-map-marked-alt mr-3"></i>Ubicación & Tránsito</div>
                            <div class="grid grid-cols-4 gap-5 mb-5">
                                <div class="form-input-group col-span-3"><label class="form-label">Dirección Fiscal / Residencia</label><input type="text" id="f-address" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">C.P.</label><input type="text" id="f-cp" class="form-input font-mono"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-5">
                                <div class="form-input-group"><label class="form-label">Ciudad</label><input type="text" id="f-city" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">Estado</label><input type="text" id="f-state" class="form-input"></div>
                                <div class="form-input-group"><label class="form-label">Placas Vehículo</label><input type="text" id="f-placas" class="form-input font-mono uppercase" placeholder="AAA-000"></div>
                            </div>
                        </div>

                        <!-- Panel: Footer de Captura -->
                        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl">
                            <div class="grid grid-cols-2 gap-8 items-center">
                                <div class="form-input-group">
                                    <label class="form-label !text-blue-400">Rol del Huésped</label>
                                    <select id="f-is-titular" class="form-input !bg-slate-800 !border-slate-700 !text-white font-black italic">
                                        <option value="true">TITULAR PRINCIPAL</option>
                                        <option value="false">ACOMPAÑANTE</option>
                                    </select>
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="saveGuestAndReturn()" class="flex-1 bg-blue-600 text-white py-5 rounded-2xl font-black uppercase text-xs shadow-lg active:scale-95 transition-all">
                                        <i class="fas fa-save mr-2"></i>Guardar Expediente
                                    </button>
                                    <button onclick="deleteGuestInForm()" id="btn-delete-guest" class="w-16 h-16 bg-rose-600 text-white rounded-2xl active:bg-rose-700 transition-colors">
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

    <!-- MODAL OPCIONES -->
    <div id="modal-options" class="fixed inset-0 bg-slate-900/90 hidden z-[100] items-center justify-center p-6 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden border border-white/20">
            <div class="bg-slate-900 p-10 text-white flex justify-between items-center">
                <div><p class="text-[10px] font-black uppercase opacity-40 mb-2">Administración</p><h3 class="text-4xl font-black italic uppercase tracking-tighter" id="opt-hab-title">--</h3></div>
                <button onclick="closeModal('modal-options')" class="text-3xl opacity-20"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-12 space-y-8">
                <div class="grid grid-cols-2 gap-8">
                    <button class="flex flex-col items-center p-10 bg-blue-50 rounded-[2.5rem] border-2 border-blue-100 active:bg-blue-600 active:text-white group">
                        <i class="fas fa-exchange-alt text-4xl mb-6 text-blue-600 group-active:text-white"></i><span class="text-xs font-black uppercase">Cambio Hab.</span>
                    </button>
                    <button class="flex flex-col items-center p-10 bg-orange-50 rounded-[2.5rem] border-2 border-orange-100 active:bg-orange-600 active:text-white group">
                        <i class="fas fa-utensils text-4xl mb-6 text-orange-600 group-active:text-white"></i><span class="text-xs font-black uppercase">Room Service</span>
                    </button>
                </div>
                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-400 uppercase ml-4 tracking-widest">Observaciones</label>
                    <textarea id="opt-obs-global" class="w-full h-32 p-8 bg-slate-50 rounded-[2rem] border-2 border-slate-200 outline-none text-lg font-medium focus:border-blue-500" placeholder="..."></textarea>
                </div>
                <button onclick="saveGlobalOptions()" class="w-full bg-slate-900 text-white py-6 rounded-[2rem] font-black uppercase text-sm">Actualizar Cambios</button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="fixed bottom-10 right-10 bg-slate-900 text-white px-10 py-7 rounded-[2.5rem] shadow-2xl translate-y-72 transition-all duration-500 z-[200] border-l-[15px] border-blue-500 flex items-center space-x-10">
        <i id="toast-icon" class="fas fa-info-circle text-blue-400 text-4xl"></i>
        <div>
            <p id="toast-message" class="font-black text-2xl tracking-tight italic uppercase">Mensaje</p>
            <p class="text-[10px] font-bold opacity-40 uppercase tracking-[0.4em] mt-1">Terminal de Respuesta HotelOS</p>
        </div>
    </div>

    <script>
window.base_url = "<?= site_url('/') ?>";
</script>

    <script>
        const rooms = [];
        const basePrice = 500.00;
        let selectedRoomIdx = null;
        let selectedGuestIdx = 0;
        let currentFloor = 'PB';
        let tempGuests = [];
        const activeFilters = {};

        const cols = [
            { id: 'status', label: 'Status', w: '120px', type: 'select', opts: ['Limpia', 'Sucia', 'Mantenimiento', 'Planta'] },
            { id: 'id', label: 'Hab', w: '80px', type: 'text' },
            { id: 'stay', label: 'Estadía', w: '120px', type: 'select', opts: ['S', 'SQ', 'P'] },
            { id: 'days', label: 'Días', w: '75px', type: 'text' },
            { id: 'people', label: 'Pers', w: '75px', type: 'text' },
            { id: 'reg', label: 'Registro', w: '140px', type: 'none' },
            { id: 'pay', label: 'Pago', w: '140px', type: 'select', opts: ['E', 'TC', 'TD'] },
            { id: 'price', label: 'Precio', w: '120px', type: 'text' },
            { id: 'h_ent', label: 'Entrada', w: '120px', type: 'none' },
            { id: 'btn_e', label: 'E', w: '65px', type: 'none' },
            { id: 'h_sal', label: 'Salida', w: '120px', type: 'none' },
            { id: 'btn_s', label: 'S', w: '65px', type: 'none' },
            { id: 'name', label: 'Titular', w: '260px', type: 'text' },
            { id: 'extra', label: 'Extra', w: '85px', type: 'text' },
            { id: 'opt', label: 'Opciones', w: '110px', type: 'none' },
            { id: 'ticket', label: 'Ticket', w: '100px', type: 'none' }
        ];

/**FORMA DE PAGO */

let catalogoFormasPago = [];

async function cargarFormasPago() {

    try {
        const r = await fetch(base_url + "catalogos/formas-pago");

        if (!r.ok) throw new Error("HTTP " + r.status);

        const data = await r.json();

        catalogoFormasPago = data.map(row => {
            const partes = row.label.split('-');

            return {
                codigo: partes[0],
                label: partes.slice(1).join('-')
            };
        });

        console.log("✔ catálogo listo:", catalogoFormasPago);

    } catch (e) {
        console.error("❌ error formas pago:", e);
    }
}

// 🔥 IMPORTANTE: Llama a la función solo cuando el HTML esté listo
document.addEventListener("DOMContentLoaded", () => {
    cargarFormasPago();
});


function renderSelectFormaPago(valorActual, realIdx, isBlocked = false) {

    let html = `<select 
        onchange="rooms[${realIdx}].formaPago=this.value" 
        class="text-[10px] font-bold" 
        ${isBlocked ? 'disabled' : ''}>`;

    html += `<option value="">-</option>`;

    catalogoFormasPago.forEach(fp => {
        html += `
            <option value="${fp.codigo}" ${valorActual === fp.codigo ? 'selected' : ''}>
                ${fp.label}
            </option>
        `;
    });

    html += `</select>`;

    return html;
}

/**FORMA DE PAGO */



  
       async function initData() {

    try {
        const response = await fetch(base_url+'/reservacion/habitaciones');
        const data = await response.json(); // 👈 antes era dataDummy

        rooms.length = 0;

        const statusMap = { 'S': 'sucia', 'X': 'limpia', 'M': 'mantenimiento', 'P': 'planta' };

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
                status: statusMap[item.status] || 'limpia',
                floor: item.piso || 'PB',

                capacidad: item.num_personas || 2, // 🔥 mejora importante
                tipoEstadia: item.tipo_estadia || '',
                dias: item.dias_pagados || 0,

                huespedes: item.huespedes || [],

                formaPago: item.forma_pago || '',
                precio: item.total || 0,

                horaEntrada: h_ent,
                fechaEntrada: f_ent,
                horaSalida: h_sal,
                fechaSalida: f_sal,

                shaded: !!item.sombreado,
                obs: item.observaciones || ''
            });
        });

        
         cargarFormasPago();

        renderHeader();
       
        renderGrid();

    } catch (error) {
        console.error('Error cargando habitaciones:', error);
    }
}

        function renderHeader() {
            const row = document.getElementById('header-row');
            row.innerHTML = '';
            cols.forEach(c => {
                const th = document.createElement('th');
                th.className = 'header-filter';
                th.tabIndex = 0;
                let filterUI = '';
                if (c.type === 'text') {
                    filterUI = `<div class="filter-dropdown"><input type="text" class="w-full p-2 bg-slate-100 rounded text-xs text-black" oninput="applyHeaderFilter('${c.id}', this.value)" placeholder="Buscar..."></div>`;
                } else if (c.type === 'select') {
                    filterUI = `<div class="filter-dropdown"><select class="w-full p-2 bg-slate-100 rounded text-xs text-black" onchange="applyHeaderFilter('${c.id}', this.value)"><option value="">Todos</option>${c.opts.map(o => `<option value="${o.toLowerCase()}">${o}</option>`)}</select></div>`;
                }
                th.innerHTML = `<div>${c.label} <i class="fas fa-filter ml-1 text-[8px] opacity-40"></i></div>${filterUI}`;
                row.appendChild(th);
            });
        }

        function applyHeaderFilter(id, val) { activeFilters[id] = val; renderGrid(); }
        function applyGlobalSearch() { renderGrid(); }

        function renderGrid() {

    const tbody = document.getElementById('rooms-tbody');
    tbody.innerHTML = '';

    const search = document.getElementById('global-search').value.toLowerCase();

    const filtered = rooms.filter(r => {

        if (r.floor !== currentFloor) return false;

        const titular = r.huespedes.find(h => h.isTitular) || { nombre: '', apellidos: '' };
        const fullName = `${titular.nombre} ${titular.apellidos}`.toLowerCase();

        if (search && !r.id.includes(search) && !fullName.includes(search)) return false;
        if (activeFilters.id && !r.id.includes(activeFilters.id)) return false;
        if (activeFilters.status && !r.status.includes(activeFilters.status)) return false;
        if (activeFilters.name && !fullName.includes(activeFilters.name.toLowerCase())) return false;
        if (activeFilters.stay && r.tipoEstadia.toLowerCase() !== activeFilters.stay) return false;
        if (activeFilters.pay && r.formaPago.toLowerCase() !== activeFilters.pay) return false;

        return true;
    });

    filtered.forEach(r => {

        const realIdx = rooms.indexOf(r);

        const titular = r.huespedes.find(h => h.isTitular) || { nombre: '', apellidos: '' };

        const extra = Math.max(0, r.huespedes.length - r.capacidad);

        const isBlocked = (
            r.status === 'mantenimiento' ||
            (r.status === 'sucia' && r.huespedes.length === 0)
        );

        const tr = document.createElement('tr');

        tr.className = `status-${r.status} ${r.shaded ? 'row-shaded' : ''}`;

        tr.innerHTML = `

            <!-- STATUS -->
            <td class="font-bold ${r.status === 'limpia' ? 'status-icon-limpia' : ''}">
                <select onchange="rooms[${realIdx}].status=this.value;renderGrid()">
                    <option value="limpia" ${r.status === 'limpia' ? 'selected' : ''}>X=Limpia</option>
                    <option value="sucia" ${r.status === 'sucia' ? 'selected' : ''}>=Sucia</option>
                    <option value="mantenimiento" ${r.status === 'mantenimiento' ? 'selected' : ''}>M=Mant.</option>
                    <option value="planta" ${r.status === 'planta' ? 'selected' : ''}>P=Planta</option>
                </select>
            </td>

            <!-- NUMERO -->
            <td class="text-center font-black text-blue-900">${r.id}</td>

            <!-- ESTADIA -->
            <td>
                <select onchange="updateStay(${realIdx}, this.value)" ${isBlocked ? 'disabled' : ''}>
                    <option value="">-</option>
                    <option value="S" ${r.tipoEstadia === 'S' ? 'selected' : ''}>S</option>
                    <option value="SQ" ${r.tipoEstadia === 'SQ' ? 'selected' : ''}>SQ</option>
                    <option value="P" ${r.tipoEstadia === 'P' ? 'selected' : ''}>P</option>
                </select>
            </td>

            <!-- DIAS -->
            <td>
                <input type="number" value="${r.dias}" 
                    onchange="updateDays(${realIdx}, this.value)" 
                    class="text-center font-bold no-spinner"
                    ${isBlocked ? 'disabled' : ''}>
            </td>

            <!-- PERSONAS -->
            <td class="text-center font-black">${r.huespedes.length}</td>

            <!-- REGISTRAR -->
            <td class="text-center">
                <button onclick="openRegister(${realIdx})" 
                    class="bg-slate-800 text-white text-[9px] px-3 py-1.5 rounded-lg font-black 
                    ${isBlocked ? 'opacity-20 pointer-events-none' : ''}">
                    REGISTRAR +
                </button>
            </td>

            <!-- 🔥 FORMA DE PAGO (CORREGIDO) -->
            <td class="col-forma-pago">
                ${renderSelectFormaPago(r.formaPago, realIdx, isBlocked)}
            </td>

            <!-- PRECIO -->
            <td class="text-right font-mono font-bold text-blue-800">
                ${r.precio.toFixed(2)}
            </td>

            <!-- ENTRADA -->
            <td class="text-[9px] font-bold leading-none text-slate-400">
                ${r.horaEntrada 
                    ? `<div>${r.horaEntrada}</div><div>${r.fechaEntrada}</div>` 
                    : '-'}
            </td>

            <td class="text-center">
                <button onclick="stampTime(${realIdx},'entrada')" class="text-blue-500">
                    <i class="fas fa-plus-circle"></i>
                </button>
            </td>

            <!-- SALIDA -->
            <td class="text-[9px] font-bold leading-none text-slate-400">
                ${r.horaSalida 
                    ? `<div>${r.horaSalida}</div><div>${r.fechaSalida}</div>` 
                    : '-'}
            </td>

            <td class="text-center">
                <button onclick="stampTime(${realIdx},'salida')" class="text-rose-500">
                    <i class="fas fa-plus-circle"></i>
                </button>
            </td>

            <!-- NOMBRE -->
            <td class="font-black uppercase truncate text-[11px]">
                ${titular.nombre} ${titular.apellidos}
            </td>

            <!-- EXTRAS -->
            <td class="text-center font-bold ${extra > 0 ? 'text-orange-600 bg-orange-50' : 'text-slate-200'}">
                ${extra}
            </td>

            <!-- OPCIONES -->
            <td>
                <button onclick="openOptions(${realIdx})"
                    class="bg-slate-100 px-3 py-1 rounded text-[9px] font-black uppercase 
                    ${r.huespedes.length === 0 ? 'opacity-10 pointer-events-none' : ''}">
                    Opciones
                </button>
            </td>

            <!-- CHECKOUT -->
            <td>
                <button onclick="doCheckout(${realIdx})"
                    class="bg-blue-600 text-white px-3 py-1 rounded text-[9px] font-black uppercase shadow-md 
                    ${r.huespedes.length === 0 ? 'opacity-10 pointer-events-none' : ''}">
                    Ticket
                </button>
            </td>

        `;

        tbody.appendChild(tr);
    });
}

        // --- FUNCIONES OPCIONES ---
        function openOptions(idx) {
            selectedRoomIdx = idx;
            const r = rooms[idx];
            document.getElementById('opt-hab-title').textContent = `Habitación ${r.id}`;
            document.getElementById('opt-obs-global').value = r.obs || '';
            document.getElementById('modal-options').classList.add('modal-active');
        }

        function saveGlobalOptions() {
            rooms[selectedRoomIdx].obs = document.getElementById('opt-obs-global').value;
            showToast("Observaciones actualizadas");
            closeModal('modal-options');
            renderGrid();
        }

        function openChangeUnit() {
            showToast("Cargando unidades disponibles para cambio...", "fa-exchange-alt");
        }

        function updateStay(idx, v) { rooms[idx].tipoEstadia = v; rooms[idx].precio = basePrice * (v === 'SQ' ? 1 : 1); renderGrid(); }
        function updateDays(idx, v) { rooms[idx].dias = v; rooms[idx].precio = basePrice * (parseInt(v) || 1); renderGrid(); }
        function stampTime(idx, type) { 
            const now = new Date(); 
            rooms[idx][`hora${type === 'entrada' ? 'Entrada' : 'Salida'}`] = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); 
            rooms[idx][`fecha${type === 'entrada' ? 'Entrada' : 'Salida'}`] = now.toLocaleDateString(); 
            renderGrid(); 
        }

        function changeFloor(floor) {
            currentFloor = floor;
            const floors = ["PB", "1", "2", "3"];
            floors.forEach(f => {
                const btn = document.getElementById(`btn-floor-${f}`);
                if (btn) btn.className = f === floor ? "bg-blue-600 px-6 py-1.5 rounded-lg text-xs font-black shadow-lg" : "px-6 py-1.5 rounded-lg text-xs font-bold text-slate-400";
            });
            renderGrid();
        }

        function openRegister(idx) {
            selectedRoomIdx = idx; tempGuests = rooms[idx].huespedes.map(g => ({...g}));
            if (tempGuests.length === 0) addNewGuest(); else selectGuest(0);
            document.getElementById('modal-hab-id').textContent = rooms[idx].id;
            document.getElementById('modal-register').classList.add('modal-active');
        }

        function addNewGuest() { tempGuests.push({ nombre: '', apellidos: '', idType: 'INE', idNum: '', telefono: '', email: '', nacionalidad: 'Mexicana', isTitular: tempGuests.length === 0 }); selectGuest(tempGuests.length - 1); }
        function selectGuest(i) { saveCurrentGuestData(false); selectedGuestIdx = i; const g = tempGuests[i]; document.getElementById('f-name').value = g.nombre; document.getElementById('f-apellidos').value = g.apellidos; updateSidebar(); }
        function saveCurrentGuestData(notify = true) { if (tempGuests.length === 0) return; const g = tempGuests[selectedGuestIdx]; g.nombre = document.getElementById('f-name').value.toUpperCase(); g.apellidos = document.getElementById('f-apellidos').value.toUpperCase(); updateSidebar(); if (notify) showToast("Guardado temporal"); }
        function updateSidebar() { const l = document.getElementById('guest-sidebar-list'); l.innerHTML = ''; tempGuests.forEach((g, i) => { const c = document.createElement('div'); c.className = `guest-item-card p-4 rounded-2xl border bg-white shadow-sm flex items-center space-x-3 ${selectedGuestIdx === i ? 'active' : ''}`; c.onclick = () => selectGuest(i); c.innerHTML = `<div class="w-10 h-10 rounded-xl ${g.isTitular ? 'bg-blue-600' : 'bg-slate-200'} flex items-center justify-center text-white text-xs font-black">${g.isTitular ? 'T' : i+1}</div><div class="flex-1 truncate"><p class="text-xs font-black">${g.nombre || '(Sin nombre)'}</p></div>`; l.appendChild(c); }); }
        function confirmRegistration() { saveCurrentGuestData(false); rooms[selectedRoomIdx].huespedes = [...tempGuests]; rooms[selectedRoomIdx].status = 'sucia'; closeModal('modal-register'); renderGrid(); }
        
        function doCheckout(idx) {
            showToast(`Realizando Checkout Hab ${rooms[idx].id}...`, "fa-print");
            setTimeout(() => {
                rooms[idx].huespedes = [];
                rooms[idx].status = 'sucia';
                rooms[idx].tipoEstadia = '';
                rooms[idx].dias = 0;
                rooms[idx].precio = 0;
                renderGrid();
            }, 800);
        }

        function closeModal(id) { document.getElementById(id).classList.remove('modal-active'); }
        function showView(v) { document.getElementById('view-home').classList.add('hidden'); document.getElementById('view-work').classList.add('hidden'); document.getElementById(`view-${v}`).classList.remove('hidden'); }
        function startShift() { showToast("Turno Iniciado"); setTimeout(() => showView('work'), 500); }
        function showToast(m, icon = "fa-info-circle") { 
            const t = document.getElementById('toast'); 
            document.getElementById('toast-message').textContent = m; 
            document.getElementById('toast-icon').className = `fas ${icon} text-blue-400 text-2xl`;
            t.classList.remove('translate-y-48'); 
            setTimeout(() => t.classList.add('translate-y-48'), 3000); 
        }

        window.onload = () => { initData(); setInterval(() => document.getElementById('clock').textContent = new Date().toLocaleTimeString(), 1000); };
    </script>


<?= view('layout/footer') ?>