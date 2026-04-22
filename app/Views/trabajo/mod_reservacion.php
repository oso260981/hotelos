<!-- <link rel="stylesheet" href="<?= base_url('assets/css/modal.css') ?>"> -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- <style>
.wizard-progress {
    height: 6px;
    background: #eee;
    border-radius: 10px;
    margin-bottom: 15px;
    overflow: hidden;
}

#wizard-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #22c55e, #16a34a);
    transition: width 0.4s ease;
}

.nav-tab {
    padding: 10px 14px;
    cursor: pointer;
    border-radius: 10px;
    transition: all .25s ease;
}

.nav-tab.active {
    background: #16a34a;
    color: white;
}

.nav-tab.completed {
    background: #dcfce7;
    color: #166534;
}

.nav-tab.disabled {
    opacity: 0.4;
    pointer-events: none;
}

.step-content {
    display: none;
}










 /* Tarjeta de composición de PAX (Dashboard) */
        .pax-visual-container {
            background: white;
            border: 1.5px solid #f1f5f9;
            border-radius: 32px;
            padding: 32px;
            transition: all 0.3s;
        }

        .pax-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .pax-photo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            object-fit: cover;
            border: 3px solid #f1f5f9;
        }
        .pax-name { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; }

        .btn-add-pax {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            border: 2px dashed #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 24px;
            cursor: pointer;
        }

        /* Estructura del Modal basado en diseño de captura de identidad */
        .pms-modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px);
            z-index: 1000; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.3s;
        }
        .pms-modal-overlay.active { opacity: 1; visibility: visible; }
        .pms-modal-content { background: white; width: 95%; max-width: 1000px; border-radius: 32px; overflow: hidden; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); }


</style> -->



<style>
#ocrModal {
    z-index: 9999999 !important;
}

/* opcional */
#ocrModal input[type="file"] {
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* 🔥 LOADER GLOBAL */
.loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 1000002;
    /* 🔥 más alto que cualquier modal */
}

.loader-box {
    background: #1e4fa1;
    padding: 20px 30px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}
</style>




<div class="dashboard-bg"></div>

<div id="master-modal-container">

    <div class="master-modal" id="pms-modal">


        <!-- LEFT NAV -->
        <nav class="nav-sidebar">

            <div class="mb-10 px-4">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-2">Master
                    Folio</p>
                <h2 class="font-black text-xl tracking-tighter" id="display_folio">RES-0000</h2>
            </div>

            <div class="wizard">

                <!-- PROGRESO -->
                <div class="wizard-progress">
                    <div id="wizard-bar"></div>
                </div>

                <!-- STEPS -->
                <div class="wizard-tabs">
                    <div class="nav-tab active" data-step="huesped">👤 Huésped</div>
                    <div class="nav-tab disabled" data-step="habitacion">🏨 Habitación</div>
                    <div class="nav-tab disabled" data-step="logistica">📅 Estancia</div>
                    <div class="nav-tab disabled" data-step="pago">💳 Pago</div>
                </div>

            </div>

            <!--   <div class="nav-tab active" id="tab-link-huesped" onclick="switchTabreserva('huesped')">
                <i class="fas fa-user-shield"></i> Huésped Titular
            </div>
            <div class="nav-tab" id="tab-link-habitacion" onclick="switchTabreserva('habitacion')">
                <i class="fas fa-key"></i> Unidad & Piso
            </div>
            <div class="nav-tab" id="tab-link-logistica" onclick="switchTabreserva('logistica')">
                <i class="fas fa-calendar-check"></i> Estancia & PAX
            </div>
            <div class="nav-tab" id="tab-link-pago" onclick="switchTabreserva('pago')">
                <i class="fas fa-wallet"></i> Garantía & Pago
            </div> -->

            <div class="px-4 mt-10">
                <div class="p-4 bg-slate-100 rounded-xl mb-4 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Estado de Conexión</p>
                    <p class="text-[10px] font-bold text-emerald-500 mt-1"><i class="fas fa-circle text-[6px] mr-1"></i>
                        Sincronizado</p>
                </div>
                <button onclick="toggleMasterModal(false)"
                    class="w-full py-3 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-times-circle mr-2"></i> Cancelar
                </button>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="content-body custom-scroll">

            <!-- TAB 1: HUÉSPED (BUSCADOR REDISEÑADO) -->
            <div id="step-huesped" class="step-content">
                <div class="flex justify-between items-center border-b pb-4">
                    <h3 class="font-black text-lg uppercase tracking-tight">Registro de Identidad</h3>
                    <div id="frequent-badge" class="hidden">
                        <span
                            class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-lg border border-amber-200 uppercase">
                            <i class="fas fa-crown mr-1"></i> Cliente Frecuente
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 flex gap-3">
                        <div class="search-container flex-1">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="guestSearch" onkeyup="handleSearch()"
                                placeholder="Buscar por nombre, apellidos o número de identificación..."
                                class="search-input">
                        </div>
                        <button onclick="openOCRModal()"
                            class="bg-slate-900 text-white px-6 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-fingerprint text-xs"></i> Biometría OCR
                        </button>

                    </div>
                    <div id="searchResults" class="col-span-12 hidden grid grid-cols-2 gap-3 animate-fade-in"></div>

                    <div
                        class="col-span-12 grid grid-cols-12 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <div class="col-span-3 flex flex-col items-center">
                            <div class="camera-box mb-3 shadow-lg">
                                <video id="camera-preview" autoplay playsinline
                                    class="w-full h-full object-cover"></video>
                                <img id="captured-photo" class="hidden w-full h-full object-cover">
                            </div>
                            <div class="flex gap-2">
                                <button onclick="startCamerareserva('camera-preview')"
                                    class="bg-white text-indigo-600 p-2 rounded-xl shadow border hover:bg-indigo-50"><i
                                        class="fas fa-video text-xs"></i></button>
                                <button onclick="takePhotoreserva('camera-preview', 'captured-photo')"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl shadow text-[10px] font-black uppercase">Capturar</button>
                            </div>

                        </div>
                        <div class="col-span-9 grid grid-cols-2 gap-4">
                            <div><label class="pms-label">Nombre(s)</label><input type="text" id="first_name"
                                    oninput="syncSummary();checkHuespedStep()" class="pms-input" placeholder="Nombre"
                                    required></div>
                            <div><label class="pms-label">Apellidos</label><input type="text" id="last_name"
                                    oninput="syncSummary()" class="pms-input" placeholder="Apellidos" required></div>
                            <div>
                                <label class="pms-label">Tipo Identificación</label>
                                <select id="id_type" class="pms-input" required></select>
                            </div>

                            <div><label class="pms-label">No. ID</label><input type="text" id="id_number"
                                    class="pms-input" placeholder="0000000" required></div>
                        </div>
                    </div>

                    <div class="col-span-4"><label class="pms-label">Teléfono</label><input type="tel" id="main_phone"
                            class="pms-input" placeholder="55 5555 5555"
                            oninput="soloNumeros(this);validarDuplicadoDebounce()"></div>

                    <div class="col-span-4"><label class="pms-label">Email Principal</label><input type="email"
                            id="main_email" class="pms-input" placeholder="correo@ejemplo.com" required
                            oninput="validarEmailRealtime();validarDuplicadoDebounce()"></div>

                    <div class="col-span-4"><label class="pms-label">Nacionalidad</label><input type="text"
                            id="main_nationality" value="Mexicana" class="pms-input"></div>

                    <div class="col-span-4"><label class="pms-label">Fecha Nacimiento</label><input type="date"
                            id="main_dob" class="pms-input"></div>
                    <div class="col-span-4"><label class="pms-label">Género</label><select id="main_gender"
                            class="pms-input">
                            <option>Masculino</option>
                            <option>Femenino</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <div class="col-span-4"><label class="pms-label">C.P.</label><input type="text" id="main_zip"
                            class="pms-input" placeholder="00000"></div>

                    <div class="col-span-8"><label class="pms-label">Dirección Fiscal / Residencia</label><input
                            type="text" id="main_address" class="pms-input" placeholder="Calle, Número, Colonia"></div>

                    <!-- INTEGRACIÓN DE PLACAS -->
                    <div class="col-span-4">
                        <label class="pms-label"><i class="fas fa-car mr-1 text-indigo-500"></i> Placas del
                            Vehículo</label>
                        <input type="text" id="car_plates" class="pms-input bg-indigo-50/50 border-indigo-100"
                            placeholder="ABC-123-D" style="text-transform: uppercase;">
                    </div>

                    <div class="col-span-6"><label class="pms-label">Ciudad</label><input type="text" id="main_city"
                            class="pms-input" placeholder="Ciudad"></div>
                    <div class="col-span-6"><label class="pms-label">Estado</label><input type="text" id="main_state"
                            class="pms-input" placeholder="Estado"></div>


                    <input type="text" id="guestSearch" onkeyup="handleSearch()">

                    <!-- <div class="col-span-12"><label class="pms-label">Empresa / Convenio</label><input type="text"
                            id="main_company" class="pms-input" placeholder="Razón Social (Opcional)"></div>
                    <div class="col-span-12"><label class="pms-label">Observaciones de Estancia</label><textarea
                            id="main_notes" class="pms-input h-20 resize-none text-[11px]"
                            placeholder="Notas especiales, alergias o preferencias de piso..."></textarea></div> -->
                </div>
            </div>

            <!-- TAB 2: HABITACIÓN (REDISEÑADO) -->
            <div id="step-habitacion" class="step-content">
                <h3 class="font-black text-lg uppercase tracking-tight border-b pb-4">Asignación de Unidad</h3>

                <div id="grid-controls" class="space-y-6">
                    <div class="flex gap-8 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <div>
                            <label class="pms-label text-indigo-600 mb-3">Nivel / Piso</label>
                            <div id="floorContainer" class="flex gap-2"></div>
                        </div>
                        <div>
                            <label class="pms-label text-indigo-600 mb-3">Categoría de Unidad</label>
                            <div id="typeContainer" class="flex gap-2"></div>
                        </div>
                    </div>
                    <div id="roomContainer" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4"></div>
                </div>

                <div id="selected-room-info"
                    class="hidden bg-indigo-600 p-8 rounded-3xl text-white flex justify-between items-center shadow-2xl">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl"><i
                                class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="text-[10px] font-black opacity-70 uppercase mb-1 tracking-widest">Unidad
                                Confirmada</p>
                            <h4 class="text-3xl font-black" id="selected-room-text">#000</h4>
                        </div>
                    </div>
                    <button onclick="resetRoomSelection()"
                        class="bg-white/20 text-white px-6 py-3 rounded-xl text-[11px] font-black uppercase hover:bg-white/30 transition-all">Cambiar
                        Habitación</button>
                </div>
            </div>

            <!-- TAB 3: LOGÍSTICA -->
            <div id="step-logistica" class="step-content">

                <!-- HEADER -->
                <h3 class="font-black text-lg uppercase tracking-tight border-b pb-4 mb-6">
                    Detalles de Ocupación
                </h3>

                <!-- 🔥 GRID PRINCIPAL -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-end">

                    <!-- Entrada -->
                    <div>
                        <label class="pms-label">Entrada</label>
                        <input type="date" id="checkin_date" onchange="updateTotal()"
                            class="pms-input font-bold text-indigo-600">
                    </div>

                    <!-- Salida -->
                    <div>
                        <label class="pms-label">Salida</label>
                        <input type="date" id="checkout_date" onchange="updateTotal()"
                            class="pms-input font-bold text-indigo-600">
                    </div>

                    <!-- Adultos -->
                    <div class="text-center">
                        <label class="pms-label">Adultos</label>
                        <p id="label_adults" class="text-2xl font-black text-indigo-700 mt-2">
                            1
                        </p>
                    </div>

                    <!-- Niños -->
                    <div class="text-center">
                        <label class="pms-label">Niños</label>
                        <p id="label_kids" class="text-2xl font-black text-indigo-700 mt-2">
                            0
                        </p>
                    </div>

                    <!-- Extra -->
                    <div class="text-center">
                        <label class="pms-label">Extra</label>
                        <p id="label_extra" class="text-2xl font-black text-indigo-700 mt-2">
                            0
                        </p>
                    </div>

                </div>

                <!-- 🔥 TIEMPOS + CAPACIDAD (MISMA FILA) -->
                <div class="mt-6 p-6 bg-slate-50 border rounded-3xl flex flex-col md:flex-row md:items-center gap-6">

                    <!-- 🔹 IZQUIERDA: TIEMPOS -->
                    <div class="flex-1">
                        <label class="pms-label">Tiempos Estadia</label>
                        <select id="main_estadia" class="pms-input mt-1">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <!-- 🔹 DIVISOR -->
                    <div class="hidden md:block w-px h-12 bg-slate-200"></div>

                    <!-- 🔹 DERECHA: CAPACIDAD -->
                    <div class="flex-1">

                        <p class="pms-label flex items-center gap-2">
                            <i class="fas fa-bed text-indigo-500"></i>
                            Capacidad de Unidad
                        </p>

                        <p id="capacidad_info" class="text-sm font-black text-slate-900 mt-1">
                            --
                        </p>

                        <p id="capacidad_extra" class="text-[10px] font-bold text-amber-600 mt-1 uppercase">
                            --
                        </p>

                    </div>

                </div>

                <!-- 🔥 PAX -->
                <div class="pax-visual-container mt-8">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">
                            Acompañantes Vinculados
                        </h3>
                    </div>

                    <div class="flex items-start gap-6 flex-wrap" id="pax-visual-grid">
                        <!-- dinámico -->
                    </div>

                </div>

                <!-- 🔥 WARNING -->
                <div class="mt-6">
                    <div id="capacity-warning"
                        class="hidden p-4 bg-red-50 border border-red-200 text-red-600 text-[10px] font-black uppercase text-center rounded-2xl shadow-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Capacidad máxima excedida para este tipo de habitación.
                    </div>
                </div>

            </div>

            <!-- TAB 4: PAGO (ESTILIZADO) -->
            <div id="step-pago" class="step-content space-y-6">

                <!-- HEADER -->
                <h3 class="font-black text-lg uppercase tracking-tight border-b pb-4">
                    Liquidación o Anticipo de reservación
                </h3>

                <!-- 💰 BLOQUE PRINCIPAL -->
                <div class="bg-slate-900 rounded-3xl px-8 py-6 flex items-center justify-between shadow-lg">

                    <!-- TEXTO -->
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                            Depósito de Liquidación o Anticipo
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Importe
                        </p>
                    </div>

                    <!-- INPUT -->
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 
                         text-emerald-400 font-black text-2xl">$</span>

                        <input type="number" id="anticipo_monto" value="0" min="0" oninput="updateTotal()" class="w-full pl-10 pr-4 py-3 bg-slate-800 border-2 border-slate-700 
                       rounded-xl outline-none font-black text-2xl text-white text-center">
                    </div>

                </div>


                <!-- MÉTODOS DE PAGO -->
                <div class="grid grid-cols-3 gap-4">

                    <!-- EFECTIVO -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_method" value="Efectivo" checked onchange="updateTotal()"
                            class="hidden peer">

                        <div class="text-center p-5 border-2 border-slate-200 rounded-2xl 
                        peer-checked:border-indigo-600 peer-checked:bg-indigo-50 
                        transition-all">

                            <i class="fas fa-money-bill-wave text-2xl mb-2 
                          text-slate-300 peer-checked:text-emerald-500"></i>

                            <p class="text-[10px] font-black uppercase text-slate-400 
                          peer-checked:text-indigo-900">
                                Efectivo
                            </p>
                        </div>
                    </label>

                    <!-- TARJETA -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_method" value="Tarjeta"
                            onchange="cargarFormasPago('tarjeta'); updateTotal()" class="hidden peer">

                        <div class="text-center p-5 border-2 border-slate-200 rounded-2xl 
                        peer-checked:border-indigo-600 peer-checked:bg-indigo-50 
                        transition-all">

                            <i class="fas fa-credit-card text-2xl mb-2 
                          text-slate-300 peer-checked:text-indigo-600"></i>

                            <p class="text-[10px] font-black uppercase text-slate-400 
                          peer-checked:text-indigo-900">
                                Tarjeta
                            </p>
                        </div>
                    </label>

                    <!-- TRANSFERENCIA -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_method" value="Transferencia"
                            onchange="cargarFormasPago('transferencia'); updateTotal()" class="hidden peer">

                        <div class="text-center p-5 border-2 border-slate-200 rounded-2xl 
                        peer-checked:border-indigo-600 peer-checked:bg-indigo-50 
                        transition-all">

                            <i class="fas fa-university text-2xl mb-2 
                          text-slate-300 peer-checked:text-amber-500"></i>

                            <p class="text-[10px] font-black uppercase text-slate-400 
                          peer-checked:text-indigo-900">
                                Transferencia
                            </p>
                        </div>
                    </label>

                </div>

                <!-- FORMA DE PAGO -->
                <div>
                    <label class="pms-label">Forma de Pago</label>
                    <select id="main_formaPago" class="pms-input">
                        <option value="">Cargando...</option>
                    </select>
                </div>

                <!-- DETALLES DINÁMICOS -->
                <div id="payment_details_container" class="grid grid-cols-2 gap-4"></div>


            </div>
        </main>

        <!-- RIGHT AUDIT SIDEBAR (IMPORTES REDUCIDOS) -->
        <aside class="sidebar-ticket">
            <div class="text-center mb-8 border-b border-slate-800 pb-6">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.5em] mb-2 italic">Auditoría
                    Operativa</p>
                <h4 class="text-indigo-400 font-mono text-base font-bold tracking-widest uppercase"
                    id="summary_folio_display">---</h4>
            </div>

            <div class="flex-1 space-y-5 overflow-y-auto sidebar-scroll pr-2">
                <div class="summary-item">
                    <span class="summary-label">Titular</span>
                    <span id="summary_guest" class="summary-value uppercase truncate max-w-[150px]">---</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Unidad</span>
                    <span id="summary_room" class="summary-value text-indigo-300">Sin Asignar</span>
                </div>
                <!-- CAMPO DE NOCHES AÑADIDO -->
                <div class="summary-item">
                    <span class="summary-label">Estancia</span>
                    <span id="summary_nights" class="summary-value text-indigo-300">---</span>
                </div>

                <div class="border-t border-slate-800 pt-5 space-y-3.5">
                    <div class="summary-item">
                        <span class="summary-label">Hospedaje Base</span>
                        <span id="summary_subtotal" class="font-mono text-slate-200 text-sm font-bold">$0.00</span>
                    </div>

                    <div id="row_extra" class="hidden summary-item border-t border-slate-800/50 pt-2">
                        <span class="summary-label text-slate-600">Cargos Extra</span>
                        <span id="summary_extra" class="font-mono text-slate-400 text-sm font-bold">$0.00</span>
                    </div>

                    <div class="summary-item">
                        <span class="summary-label text-slate-600">Subtotal</span>
                        <span id="summary_base" class="font-mono text-slate-400 text-sm font-bold">$0.00</span>
                    </div>

                    <div class="summary-item">
                        <span class="summary-label text-slate-600">I.V.A. (16.0%)</span>
                        <span id="summary_iva" class="font-mono text-slate-400 text-sm font-bold">$0.00</span>
                    </div>
                    <!-- <div class="summary-item">
                        <span class="summary-label text-slate-600">I.S.H. (3.0%)</span>
                        <span id="summary_ish" class="font-mono text-slate-400 text-sm font-bold">$0.00</span>
                    </div>
 -->

                    <div class="summary-item">
                        <span id="label_ish" class="summary-label text-slate-600">
                            I.S.H. (0%)
                        </span>
                        <span id="summary_ish" class="font-mono text-slate-400 text-sm font-bold">
                            $0.00
                        </span>
                    </div>

                    <div class="flex flex-col items-end pt-8 border-t border-slate-700">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-2">Importe
                            Total Bruto</span>
                        <span id="summary_total"
                            class="text-4xl font-black text-white font-mono tracking-tighter leading-none">$0.00</span>
                    </div>

                    <div class="pt-8 space-y-4">
                        <div
                            class="flex justify-between items-center p-3.5 bg-emerald-500/10 rounded-2xl border border-emerald-500/20">
                            <span class="text-[10px] font-black uppercase text-emerald-500">Anticipo</span>
                            <span id="summary_anticipo"
                                class="font-mono font-black text-emerald-400 text-lg">-$0.00</span>
                        </div>
                        <div
                            class="flex flex-col p-3.5 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 text-center">

                            <span class="text-[10px] font-black uppercase text-indigo-300 mb-1 leading-none">
                                Neto por Liquidar
                            </span>

                            <span id="summary_saldo"
                                class="font-mono font-black text-emerald-400 text-lg tracking-tight leading-none">
                                $0.00
                            </span>

                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button onclick="guardarRerservacion()"
                    class="w-full bg-emerald-500 text-white py-5 rounded-[24px] font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 active:scale-95 transition-all">
                    <i class="fas fa-check-double mr-2"></i> Confirmar Folio
                </button>
            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="mt-6 pt-4 border-t border-slate-800 text-center">
                <p class="text-[7px] text-slate-600 font-black uppercase tracking-widest">Hotelos v5.6</p>
            </div>
        </aside>

    </div>

</div>


<!-- FINAL PRINT MODAL -->
<div id="finalModal"
    class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/90 backdrop-blur-xl">
    <div class="modal-content shadow-2xl bg-white w-[540px] rounded-[48px] overflow-hidden p-14 animate-fade-in">
        <div class="flex justify-between items-start mb-12">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tighter leading-none">Folio Generado</h2>
                    <p class="text-[11px] font-black text-indigo-600 uppercase tracking-widest mt-2"
                        id="modal_folio_id">---</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Fecha de Registro</p>
                <p class="text-[10px] font-black" id="modal_date">20/03/2026</p>
            </div>
        </div>

        <div class="space-y-4 border-y border-slate-100 py-10 mb-10">
            <div class="flex justify-between items-center"><span
                    class="text-[11px] font-bold text-slate-400 uppercase">Huésped Titular</span><span id="m_name"
                    class="text-[14px] font-black uppercase text-slate-900">---</span></div>
            <div class="flex justify-between items-center"><span
                    class="text-[11px] font-bold text-slate-400 uppercase">Unidad Confirmada</span><span id="m_room"
                    class="text-[14px] font-black uppercase text-indigo-600 font-bold">---</span></div>
            <div class="flex justify-between items-center"><span
                    class="text-[11px] font-bold text-slate-400 uppercase">Hospedaje Neto</span><span id="m_sub"
                    class="text-[14px] font-black text-slate-900">---</span></div>
            <div class="flex justify-between items-center text-slate-400"><span
                    class="text-[11px] font-bold uppercase">Impuestos Totales</span><span id="m_taxes"
                    class="text-[14px] font-black">---</span></div>
        </div>

        <div class="flex flex-col items-center bg-slate-950 p-12 rounded-[48px] mb-12 shadow-2xl">
            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.5em] mb-2 leading-none">Saldo Neto
                Pendiente</span>
            <span id="m_saldo" class="text-7xl font-black text-emerald-400 tracking-tighter leading-none">$0.00</span>
        </div>

        <div class="flex gap-4">
            <button onclick="closeModal()"
                class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-[24px] font-black uppercase text-[11px] tracking-widest hover:bg-slate-200">Regresar</button>
            <button onclick="guardarHuesped()"
                class="flex-1 py-5 bg-emerald-500 text-white rounded-[24px] font-black uppercase text-[11px] tracking-widest shadow-2xl shadow-emerald-500/30 hover:bg-emerald-600 flex items-center justify-center gap-3">
                <i class="fas fa-print"></i> Guardar e Imprimir
            </button>
        </div>
    </div>
</div>







<!-- MODAL PROFESIONAL DE CAPTURA (BASADO EN IMAGEN) -->
<div id="modal-pax-capture" class="pms-modal-overlay">
    <div class="pms-modal-content">
        <div class="flex h-[750px]">
            <!-- COLUMNA IZQUIERDA: CAPTURA FOTO -->
            <div class="w-[320px] border-r p-8 bg-slate-50/50 flex flex-col gap-6">
                <div class="bg-slate-900 p-4 rounded-[24px] text-center shadow-xl border-t-4 border-indigo-600">
                    <h4 class="pms-label mb-3 text-indigo-400">Captura de Fotografía</h4>

                    <div class="camera-placeholder relative">

                        <!-- VIDEO (NUEVO) -->
                        <video id="modal-pax-video" autoplay playsinline
                            class="w-full h-full object-cover hidden"></video>

                        <!-- IMG -->
                        <img src="" id="modal-pax-preview" class="w-full h-full object-cover hidden">

                        <!-- ICON -->
                        <i id="camera-icon"></i>

                        <!-- BOTÓN CAPTURA (NUEVO) -->
                        <button id="btn-take-photo" onclick="takePhotoPax()"
                            class="hidden absolute bottom-2 right-2 bg-indigo-600 text-white w-8 h-8 rounded-full text-xs">
                            <i class="fas fa-check"></i>
                        </button>

                    </div>

                    <!-- SOLO agregamos onclick -->
                    <button onclick="startCameraPax()"
                        class="w-full py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all">
                        Prender Cámara
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="pms-label">Orden de Llegada</label>
                        <input type="number" id="pax-input-order" class="pms-input w-full" placeholder="1">
                    </div>

                    <div id="pax-excess-alert"
                        class="hidden p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
                        <div>
                            <p class="text-[9px] font-black text-amber-800 uppercase leading-tight">Capacidad Superada
                            </p>
                            <p class="text-[8px] font-bold text-amber-600 uppercase">Cargo de $350 p/n.</p>
                        </div>
                    </div>
                </div>
            </div>




            <!-- COLUMNA DERECHA: DATOS SQL / ESTANCIA -->
            <div class="flex-1 p-10 overflow-y-auto custom-scroll">
                <!-- DATOS DE IDENTIDAD -->
                <div class="mb-10">
                    <h4 class="section-header">Datos de Identidad Acompañante</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="pms-label">Nombre(s)</label>
                            <input type="text" id="pax-input-first" class="pms-input w-full" placeholder="">
                        </div>
                        <div>
                            <label class="pms-label">Apellido(s)</label>
                            <input type="text" id="pax-input-last" class="pms-input w-full" placeholder="">
                        </div>
                        <div>
                            <label class="pms-label">Tipo Identificación</label>
                            <select id="pax-input-id-type" class="pms-input w-full">
                                <option value="Licencia">Licencia</option>
                                <option value="INE">INE / ID</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div>
                            <label class="pms-label">Número de ID</label>
                            <input type="text" id="pax-input-id-num" class="pms-input w-full" placeholder="00006">
                        </div>
                        <div>
                            <label class="pms-label">Parentesco</label>
                            <select id="pax-input-relation" class="pms-input w-full">
                                <option value="Hermano">Hermano</option>
                                <option value="Esposa">Esposa / Cónyuge</option>
                                <option value="Hijo">Hijo</option>
                                <option value="Colega">Colega / Socio</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="pms-label">¿Es Menor?</label>
                                <label class="switch">
                                    <input type="checkbox" id="pax-input-minor">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TIEMPOS & ESTADO -->
                <!-- <div class="mb-10">
                    <h4 class="section-header section-header-green">Tiempos & Estado Estancia</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="pms-label">Hora Entrada</label>
                            <input type="datetime-local" id="pax-input-entry" class="pms-input w-full">
                        </div>
                        <div>
                            <label class="pms-label">Hora Salida</label>
                            <input type="datetime-local" id="pax-input-exit" class="pms-input w-full">
                        </div>
                        <div class="col-span-2">
                            <label class="pms-label">Estado de Estancia</label>
                            <select id="pax-input-status" class="pms-input w-full">
                                <option value="En Casa">Huésped en Casa</option>
                                <option value="Check-out">Check-out Realizado</option>
                                <option value="No Show">No Show</option>
                            </select>
                        </div>
                    </div>
                </div> -->

                <!-- OBSERVACIONES -->
                <div>
                    <h4 class="section-header">Observaciones Técnicas</h4>
                    <textarea id="pax-input-obs" class="pms-input w-full h-32 resize-none"
                        placeholder="Notas adicionales del acompañante..."></textarea>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t flex justify-end gap-3 bg-white">
            <button id="btn-delete" type="button" onclick="deleteGuest()"
                class="hidden px-8 py-4 bg-red-50 text-red-600 rounded-2xl font-black text-[10px] uppercase border border-red-100 hover:bg-red-600 hover:text-white transition-all">Eliminar
                Huésped</button>
            <button onclick="toggleModal('modal-pax-capture', false)"
                class="px-8 py-4 text-[10px] font-black uppercase text-slate-400">Descartar</button>
            <button onclick="savePaxData()"
                class="px-12 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-indigo-200">Guardar
                Acompañante</button>
        </footer>
    </div>
</div>





<div id="ocrModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[2000] hidden">

    <div class="bg-white w-[600px] rounded-3xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Escaneo de Documento</h2>
                <p class="text-xs text-slate-400 tracking-wide">CAPTURA AUTOMÁTICA DE IDENTIFICACIÓN</p>
            </div>

            <button onclick="closeOCRModal()" class="text-slate-400 hover:text-slate-700 text-xl">×</button>
        </div>

        <!-- BODY -->
        <div class="p-6">

            <!-- VISOR -->
            <div class="relative bg-slate-900 rounded-2xl overflow-hidden h-[260px] flex items-center justify-center">

                <video id="video" autoplay class="absolute inset-0 w-full h-full object-cover hidden"></video>
                <canvas id="canvas" class="hidden"></canvas>
                <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden" />

                <!-- guía -->
                <div class="border-2 border-white/40 w-[70%] h-[60%] rounded-xl z-10"></div>

            </div>

            <!-- CONTROLES -->
            <div class="grid grid-cols-3 gap-3 mt-5">

                <button onclick="startCameraocr()"
                    class="bg-slate-100 hover:bg-slate-200 py-2 rounded-xl text-xs font-semibold">
                    Cámara
                </button>

                <button onclick="capturePhoto()"
                    class="bg-slate-100 hover:bg-slate-200 py-2 rounded-xl text-xs font-semibold">
                    Capturar
                </button>

                <label
                    class="bg-slate-100 hover:bg-slate-200 py-2 rounded-xl text-xs font-semibold text-center cursor-pointer">
                    Subir
                    <input type="file" id="fileInput" accept="image/*" class="hidden">
                </label>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="flex justify-between items-center px-6 py-4 border-t">

            <span class="text-xs text-slate-400">
                Escanea INE o pasaporte
            </span>

            <button onclick="processOCR()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-xs font-bold shadow-md">
                Procesar OCR
            </button>

        </div>

    </div>
</div>



<div id="globalLoader" class="loader-overlay" style="display:none;">
    <div class="loader-box">
        <div class="loader-text">Procesando...</div>
    </div>
</div>