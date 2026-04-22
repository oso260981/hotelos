let CURRENT_GUEST_ID = null;
let searchTimer = null;
let CURRENT_FLOOR = null;
let CURRENT_STREAM = null;

/* =========================================================
   ABRIR MODAL HABITACION
========================================================= */
function abrirHabitacion() {
    const modal = document.getElementById("master-modal-container");
    cargarTiposreservaID();
    cargarPisos();
    cargarTiposHabitacion();
    cargarTiposEstadia();
    cargarHabitacionesPorPiso();
    initDates();
    initWizard();

    if (!modal) {
        console.error("No existe modal");
        return;
    }

    modal.classList.add("show");
}

/* ======================================================
   CARGAR TIPOS DE ESTADIA
====================================================== */
function cargarTiposEstadia() {
    fetch(base_url + "catalogos/tipo-estadia")
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById("main_estadia");
            select.innerHTML = `<option value="">Seleccione</option>`;
            data.forEach(row => {
                select.innerHTML += `<option value="${row.id}">${row.label}</option>`;
            });
        })
        .catch(e => console.error(e));
}

function cargarTiposreservaID() {
    fetch(base_url + "pasajeros/catalogo_tipos_identificacion")
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById("id_type");
            select.innerHTML = `<option value="">Seleccione</option>`;
            data.forEach(t => {
                select.innerHTML += `<option value="${t.id}">${t.nombre}</option>`;
            });
        })
        .catch(e => console.error("Error tipos ID", e));
}

function cargarFormasPago(tipo = 'efectivo') {
    fetch(base_url + "catalogos/formas-pago?tipo=" + tipo)
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById("main_formaPago");
            select.innerHTML = `<option value="">Seleccione</option>`;
            data.forEach(row => {
                select.innerHTML += `<option value="${row.id}">${row.label}</option>`;
            });
        })
        .catch(e => console.error(e));
}

async function handleSearch() {
    const input = document.getElementById('guestSearch').value.trim();
    const resDiv = document.getElementById('searchResults');

    clearTimeout(searchTimer);

    // 🔥 detectar números (para teléfono)
    const soloNumerosStr = input.replace(/\D/g, ''); // FIX: renombrado para no colisionar con función soloNumeros()

    if (input.length < 8 && soloNumerosStr.length < 4) {
        resDiv.innerHTML = '';
        resDiv.classList.add('hidden');
        return;
    }

    searchTimer = setTimeout(() => {
        resDiv.innerHTML = `<div class="p-4 text-slate-400 text-xs font-bold">Buscando huésped...</div>`;
        resDiv.classList.remove('hidden');

        fetch(base_url + "pasajeros/buscar_cliente?q=" + encodeURIComponent(input))
            .then(r => r.json())
            .then(data => {
                resDiv.innerHTML = '';

                if (!data || data.length === 0) {
                    resDiv.innerHTML = `<div class="p-4 text-slate-400 text-xs font-bold">Sin resultados</div>`;
                    return;
                }

                data.forEach(g => {
                    const d = document.createElement('div');
                    d.className = 'p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-600 hover:shadow-lg transition-all flex items-center gap-4';
                    d.innerHTML = `
                        <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-black text-[11px] text-slate-900 uppercase">${g.nombre} ${g.apellido ?? ''}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase">${g.numero_identificacion ?? ''} | ${g.nacionalidad ?? ''}</p>
                        </div>`;
                    d.onclick = () => {
                        populate(g);
                        resDiv.classList.add('hidden');
                    };
                    resDiv.appendChild(d);
                });
            })
            .catch(e => {
                console.error(e);
                resDiv.innerHTML = `<div class="p-4 text-red-400 text-xs font-bold">Error de búsqueda</div>`;
            });
    }, 180);
}

function populate(g) {
    CURRENT_GUEST_ID = g.id;

    document.getElementById('first_name').value       = g.nombre ?? "";
    document.getElementById('last_name').value        = g.apellido ?? "";
    document.getElementById('main_address').value     = g.direccion ?? "";
    document.getElementById('main_city').value        = g.ciudad ?? "";
    document.getElementById('main_state').value       = g.estado ?? "";
    document.getElementById('main_zip').value         = g.codigo_postal ?? "";
    document.getElementById('main_nationality').value = g.nacionalidad ?? "";
    document.getElementById('main_email').value       = g.email ?? "";
    document.getElementById('main_phone').value       = g.telefono ?? "";
    document.getElementById('main_dob').value         = g.fecha_nacimiento ?? "";
    document.getElementById('main_gender').value      = g.genero ?? "M";
    document.getElementById('id_type').value          = g.tipo_identificacion_id ?? "";
    document.getElementById('id_number').value        = g.numero_identificacion ?? "";

    document.getElementById('frequent-badge').classList.remove('hidden');
    syncSummary();
}

// FIX: renombrada a soloNumerosInput para no colisionar con variable local en handleSearch
function soloNumerosInput(input) {
    input.value = input.value.replace(/\D/g, '');
}

// Alias para compatibilidad hacia atrás (si el HTML llama soloNumeros)
function soloNumeros(input) {
    soloNumerosInput(input);
}

function formatoTelefono(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 10);
    if (value.length >= 6) {
        input.value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '$1 $2 $3');
    } else if (value.length >= 2) {
        input.value = value.replace(/(\d{2})(\d{0,4})/, '$1 $2');
    } else {
        input.value = value;
    }
}

function validarTelefono() {
    const tel = document.getElementById("main_phone").value.replace(/\D/g, '');
    if (tel.length !== 10) {
        alert("El teléfono debe tener 10 dígitos");
        return false;
    }
    return true;
}

function esEmailValido(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validarEmailRealtime() {
    const input = document.getElementById("main_email");
    const error = document.getElementById("error_email");
    const email = input.value.trim();

    if (!email) {
        input.classList.remove("border-red-500");
        error.classList.add("hidden");
        return;
    }

    if (!esEmailValido(email)) {
        input.classList.add("border-red-500");
        error.classList.remove("hidden");
    } else {
        input.classList.remove("border-red-500");
        error.classList.add("hidden");
    }
}

function validarEmailFinal() {
    const email = document.getElementById("main_email").value.trim();
    if (!email || !esEmailValido(email)) {
        alert("Correo inválido");
        return false;
    }
    return true;
}

function renderCapacidadUnidad(data) {
    const info  = document.getElementById("capacidad_info");
    const extra = document.getElementById("capacidad_extra");

    if (!data) {
        info.innerText  = "--";
        extra.innerText = "";
        return;
    }

    info.innerText  = `${data.tipo} #${data.numero} - Máx ${data.capacidad} Pers.`;
    extra.innerText = data.extra ? `Cargo extra por exceso: $${data.extra} p/n` : "";
}

/* =========================================================
   GUARDAR TODO (HUÉSPED + REGISTRO)
========================================================= */
// FIX: corregido typo "guardarRerservacion" → "guardarReservacion"
async function guardarReservacion() {
    try {
        if (!selectedRoom) return alert("Error: Debe asignar una habitación antes de confirmar.");
        if (!document.getElementById('first_name').value) return alert("Error: No se ha registrado un titular para el folio.");

        await guardarHuespedAsync();
        await guardarRegistroAsync();

        // FIX: se pasa CURRENT_ROOM_ID (no CURRENT_REGISTRO_ID) porque el endpoint espera habitacion_id
        await marcarHabitacionLimpiaAsync(CURRENT_ROOM_ID);

        await guardarAcompanantesAsync();
        await agregarPago();
        await registrarVehiculo();

        alert("Proceso completado correctamente");
        imprimirTicket();
        await cerrarHabitacion();

    } catch (e) {
        console.error(e);
        alert("Error en el proceso de guardado");
    }
}

// Alias para no romper llamadas existentes con el typo
const guardarRerservacion = guardarReservacion;

/* =========================================================
   GUARDAR HUÉSPED (CREAR / ACTUALIZAR)
========================================================= */
function guardarHuespedAsync() {
    return new Promise((resolve, reject) => {
        const payload = {
            id: CURRENT_GUEST_ID,
            fotografia: document.getElementById("captured-photo")?.dataset.file || null,
            nombre: document.getElementById('first_name').value,
            apellido: document.getElementById('last_name').value,
            tipo_identificacion_id: document.getElementById('id_type').value,
            numero_identificacion: document.getElementById('id_number').value,
            telefono: document.getElementById('main_phone').value,
            email: document.getElementById('main_email').value,
            direccion: document.getElementById('main_address').value,
            ciudad: document.getElementById('main_city').value,
            estado: document.getElementById('main_state').value,
            codigo_postal: document.getElementById('main_zip').value,
            nacionalidad: document.getElementById('main_nationality').value,
            fecha_nacimiento: document.getElementById('main_dob').value,
            genero: document.getElementById('main_gender').value
        };

        fetch(base_url + "pasajeros/guardar_cliente", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.ok) {
                    CURRENT_GUEST_ID = resp.id;
                    resolve(resp);
                } else {
                    reject(resp.msg);
                }
            })
            .catch(e => reject(e));
    });
}

/* =========================================================
   SUBIR FOTO HUÉSPED
========================================================= */
async function subirFoto(base64) {
    try {
        const res = await fetch(base_url + "media/subir-foto", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ foto: base64 })
        });
        const resp = await res.json();
        if (resp.ok) return resp.file;
        alert("Error subiendo foto");
        return null;
    } catch (e) {
        console.error(e);
        return null;
    }
}

async function takePhotoreserva(videoId, imgId) {
    const video = document.getElementById(videoId);
    const img   = document.getElementById(imgId);

    if (!video || !img) {
        console.error("Video o IMG no existen", videoId, imgId);
        return;
    }

    if (video.readyState < 2) {
        await new Promise(resolve => { video.onloadeddata = () => resolve(); });
    }

    const width  = video.videoWidth;
    const height = video.videoHeight;

    if (!width || !height) {
        console.error("No se pudo obtener frame de video");
        return;
    }

    const canvas = document.createElement("canvas");
    canvas.width  = width;
    canvas.height = height;
    canvas.getContext("2d").drawImage(video, 0, 0, width, height);

    const base64 = canvas.toDataURL("image/png");
    img.src = base64;
    img.classList.remove("hidden");
    video.classList.add("hidden");

    // FIX: detener cámara ANTES de limpiar srcObject
    stopCamerareseva(videoId);

    const fileName = await subirFoto(base64);
    if (fileName) img.dataset.file = fileName;
}

/* =========================================================
   CALCULO DE NOCHES
========================================================= */
function calcularNoches() {
    const fechaEntrada = document.getElementById('checkin_date').value;
    const fechaSalida  = document.getElementById('checkout_date').value;
    const inputNoches  = document.getElementById('summary_nights');

    if (!fechaEntrada || !fechaSalida) {
        inputNoches.value = 0;
        return;
    }

    const diffMs     = new Date(fechaSalida) - new Date(fechaEntrada);
    const totalNoches = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
    inputNoches.value = totalNoches > 0 ? totalNoches : 0;
}

/* =========================================================
   GUARDAR REGISTRO (CHECK-IN / ACTUALIZACIÓN)
========================================================= */
let CURRENT_REGISTRO_ID = null;

function guardarRegistroAsync() {
    return new Promise((resolve, reject) => {
        const extra    = parseInt(document.getElementById('qty_extra').value) || 0;
        const anticipo = parseFloat(document.getElementById('anticipo_monto').value) || 0;

        const d1     = new Date(document.getElementById('checkin_date').value);
        const d2     = new Date(document.getElementById('checkout_date').value);
        const nights = Math.max(1, (d2 - d1) / (1000 * 60 * 60 * 24));

        const precio_base = selectedRoom.price;
        const sub   = precio_base * nights;
        const ext   = extra * EXTRA_FEE * nights;
        const base  = sub + ext;
        const iva   = base * 0.16;
        const ish   = base * 0.03;
        const total = base + iva + ish;
        const saldo = total - anticipo;

        // FIX: summary_saldo es un <span> → usar innerText, no .value
        const saldoEl = document.getElementById('summary_saldo');
        if (saldoEl) saldoEl.innerText = "$" + saldo.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const payload = {
            id: CURRENT_REGISTRO_ID,
            huesped_id: CURRENT_GUEST_ID,
            habitacion_id: CURRENT_ROOM_ID,
            hora_entrada: document.getElementById('checkin_date').value,
            hora_salida: document.getElementById('checkout_date').value,
            noches: nights,
            tipo_estadia_id: document.getElementById('main_estadia').value,
            adultos: document.getElementById('qty_adults').value,
            niños: document.getElementById('qty_kids').value,
            forma_pago_id: document.getElementById('main_formaPago').value,
            num_personas_ext: extra,
            precio: base,
            precio_base,
            pago_adicional: ext,
            iva,
            ish,
            total,
        };

        fetch(base_url + "reservacion/guardar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.ok) {
                    CURRENT_REGISTRO_ID = resp.registro_id;
                    resolve(resp);
                } else {
                    reject(resp.msg);
                }
            })
            .catch(e => reject(e));
    });
}

/* ======================================================
   GUARDAR ACOMPAÑANTES
====================================================== */
function guardarAcompanantesAsync() {
    return new Promise((resolve, reject) => {
        const acompanantes = (PAX_STATE.acompanantes || []).map(p => ({
            registro_id: CURRENT_REGISTRO_ID,
            nombre: p.nombre,
            apellido: p.apellido || "",
            parentesco: p.tipo || "",
            es_menor: p.es_menor ? 1 : 0,
            fotografia: p.foto || null,
            estado_estancia: 'Hospedado'
        }));

        if (acompanantes.length === 0) {
            return resolve({ ok: true, msg: "Sin acompañantes para guardar" });
        }

        fetch(base_url + "reservacion/guardar-acompanantes", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ registro_id: CURRENT_REGISTRO_ID, acompanantes })
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.ok) resolve(resp);
                else reject(resp.msg);
            })
            .catch(err => reject(err));
    });
}

/* ======================================================
   MARCAR HABITACIÓN LIMPIA
====================================================== */
function marcarHabitacionLimpiaAsync(habitacionId) {
    return fetch(base_url + "habitaciones/marcar-limpia", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ habitacion_id: habitacionId })
    })
        .then(r => r.json())
        .then(resp => {
            if (!resp.ok) throw resp.msg;
            return resp;
        });
}

/* ======================================================
   AGREGAR PAGO
====================================================== */
async function agregarPago() {
    // FIX: summary_saldo es <span>, leer con innerText y parsear
    const saldoEl       = document.getElementById('summary_saldo');
    const totalEstancia = parseFloat((saldoEl?.innerText || "0").replace(/[^0-9.-]/g, '')) || 0;
    const montoPago     = parseFloat(document.getElementById('anticipo_monto').value) || 0;
    const concepto      = montoPago >= totalEstancia ? "Pago total de estancia" : "Hospedaje";

    const payload = {
        registro_id: CURRENT_REGISTRO_ID,
        forma_pago_id: document.getElementById('main_formaPago').value,
        referencia_pago: document.getElementById('referencia_pago')?.value || "",
        banco: document.getElementById('banco_pago')?.value || "",
        monto: montoPago,
        concepto,
        observaciones: concepto
    };

    try {
        const res  = await fetch(base_url + "reservacion/guardar-pago", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });
        const resp = await res.json();
        if (resp.ok) {
            alert("Pago registrado");
        } else {
            alert(resp.msg);
        }
    } catch (e) {
        console.error(e);
    }
}

/* ======================================================
   REGISTRAR VEHÍCULO
====================================================== */
async function registrarVehiculo() {
    const payload = {
        registro_id: CURRENT_REGISTRO_ID,
        placa: document.getElementById('car_plates').value,
    };

    try {
        const res  = await fetch(base_url + "reservacion/guardar-vehiculo", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });
        const resp = await res.json();
        if (resp.ok) {
            alert("Vehículo registrado");
        } else {
            alert(resp.msg);
        }
    } catch (e) {
        console.error(e);
    }
}

/* =========================================================
   CHECKOUT HABITACIÓN
========================================================= */
function checkoutHabitacion(registroId, habitacionId) {
    fetch(base_url + "reservacion/checkout", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ registro_id: registroId, habitacion_id: habitacionId })
    })
        .then(r => r.json())
        .then(resp => {
            if (resp.ok) alert("Habitación liberada");
        })
        .catch(e => console.error(e));
}

/* =========================================================
   PAGO EXTRA
========================================================= */
function pagoExtra(registroId) {
    const monto = prompt("Monto adicional:");
    if (!monto) return;

    fetch(base_url + "reservacion/pago-extra", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ registro_id: registroId, monto })
    })
        .then(r => r.json())
        .catch(e => console.error(e));
}

/* ======================================================
   PISOS Y TIPOS DE HABITACIÓN
====================================================== */
function cargarPisos() {
    fetch(base_url + "catalogos/listar_pisos")
        .then(r => r.json())
        .then(data => {
            const cont = document.getElementById("floorContainer");
            cont.innerHTML = '';
            data.forEach((p, i) => {
                const btn = document.createElement("button");
                btn.className = "pill-btn floor-btn" + (i === 0 ? " active" : "");
                btn.innerText = p.Piso;
                btn.onclick   = () => switchFloor(p.Piso);
                cont.appendChild(btn);
                if (i === 0) CURRENT_FLOOR = p.Piso;
            });
        })
        .catch(e => console.error("Error pisos", e));
}

function cargarTiposHabitacion() {
    fetch(base_url + "catalogos/listar_tipos_habitacion")
        .then(r => r.json())
        .then(data => {
            const cont = document.getElementById("typeContainer");
            cont.innerHTML = '';

            const btnAll = document.createElement("button");
            btnAll.className = "pill-btn type-btn active";
            btnAll.innerText  = "Todos";
            btnAll.onclick    = () => switchType("Todos");
            cont.appendChild(btnAll);

            data.forEach(t => {
                const btn = document.createElement("button");
                btn.className = "pill-btn type-btn";
                btn.innerText  = t.nombre;
                btn.onclick    = () => switchType(t.nombre);
                cont.appendChild(btn);
            });
        })
        .catch(e => console.error("Error tipos habitacion", e));
}

/* ======================================================
   VARIABLES GLOBALES HABITACIÓN
====================================================== */
const ROOMS = [];
let EXTRA_FEE    = 0;
let selectedRoom = null;
let currentFloor = 'PB';
let currentType  = 'Todos';

window.onload = () => {
    cargarHabitacionesPorPiso();
    renderRooms();
    initDates();
    const f = "AUD-" + Math.random().toString(36).substr(2, 6).toUpperCase();
    document.getElementById('display_folio').innerText         = f;
    document.getElementById('summary_folio_display').innerText = f;
    document.getElementById('modal_date').innerText            = new Date().toLocaleDateString('es-MX');
    updateTotal();
};

function cargarHabitacionesPorPiso() {
    fetch(base_url + "habitaciones/listado_visual")
        .then(r => r.json())
        .then(data => {
            ROOMS.length = 0;
            data.forEach(r => {
                ROOMS.push({
                    id: r.room_number,
                    room_number: r.id,
                    floor: r.floor,
                    type: r.type,
                    price: parseFloat(r.price),
                    capacity: parseInt(r.capacity),
                    extra_price: parseFloat(r.precio_persona_extra),
                    estado: r.estado_id
                });
            });
            renderRooms();
        });
}

function initDates() {
    const cin  = document.getElementById('checkin_date');
    const cout = document.getElementById('checkout_date');
    if (cin && cout) {
        cin.value  = new Date().toISOString().split('T')[0];
        cout.value = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    }
}

function toggleMasterModal() {
    cerrarHabitacion();
}

/* ======================================================
   WIZARD
====================================================== */
const steps = ['huesped', 'habitacion', 'logistica', 'pago'];
let currentStep = 0;

function goToStep(stepName) {
    const nextIndex = steps.indexOf(stepName);
    if (nextIndex > currentStep && !validateStep(steps[currentStep])) return;
    currentStep = nextIndex;
    updateTabs();
    updateProgress();
}

function validateStep(step) {
    renderPaxGrid();
    return true;
}

function updateTabs() {
    document.querySelectorAll('.nav-tab').forEach((tab, index) => {
        tab.classList.remove('active');
        if (index < currentStep) tab.classList.add('completed');
        if (index === currentStep) tab.classList.add('active');
        if (index <= currentStep + 1) tab.classList.remove('disabled');
    });
    showStepContent(steps[currentStep]);
}

function updateProgress() {
    const percent = (currentStep / (steps.length - 1)) * 100;
    document.getElementById('wizard-bar').style.width = percent + '%';
}

function showStepContent(step) {
    document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
    document.getElementById('step-' + step).style.display = 'block';
}

function showToast(msg) {
    console.log(msg);
}

document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', () => goToStep(tab.dataset.step));
});

function checkHuespedStep() {
    const nombre = document.getElementById("first_name").value.trim();
    const tipo   = document.getElementById("id_type").value;
    const tab    = document.querySelector('[data-step="habitacion"]');
    if (nombre && tipo) tab.classList.remove('disabled');
    else tab.classList.add('disabled');
}

function initWizard() {
    currentStep = 0;
    document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
    const first = document.getElementById('step-' + steps[0]);
    if (first) first.style.display = 'block';
    else console.error("❌ No existe step inicial:", steps[0]);
    updateTabs();
    updateProgress();
}

/* ======================================================
   RENDER HABITACIONES
====================================================== */
function renderRooms() {
    const container = document.getElementById('roomContainer');
    if (!container) return;
    container.innerHTML = '';

    const filtered = ROOMS.filter(r =>
        r.floor === currentFloor && (currentType === 'Todos' || r.type === currentType)
    );

    filtered.forEach(room => {
        const isSelected = selectedRoom && selectedRoom.id === room.id;
        const card = document.createElement('div');
        card.className = `room-card animate-fade-in ${isSelected ? 'selected-room' : ''}`;
        card.onclick   = () => selectRoom(room);

        let color = "text-indigo-500", icon = "fa-bed";
        if (room.type === 'Suite')  { color = "text-amber-500";   icon = "fa-crown"; }
        if (room.type === 'Doble')  { color = "text-emerald-500"; icon = "fa-users"; }

        card.innerHTML = `
            <span class="cap-tag">${room.capacity} Pax</span>
            <div class="flex justify-between mb-0.5"><p class="text-[7px] font-black text-slate-400">#${room.id}</p></div>
            <i class="fas ${icon} ${color} text-2xl my-3"></i>
            <p class="text-[9px] font-black text-slate-600 uppercase leading-none">${room.type}</p>
            <p class="text-[13px] font-black mt-2 text-slate-900">$${room.price.toLocaleString()}</p>`;
        container.appendChild(card);
    });
}

/* ======================================================
   VARIABLES GLOBALES PMS
====================================================== */
let CURRENT_ROOM_ID  = null;
let CURRENT_ROOM_OBJ = null;

/* ======================================================
   SELECCIONAR HABITACIÓN
====================================================== */
function selectRoom(room) {
    CURRENT_ROOM_OBJ = room;
    CURRENT_ROOM_ID  = room.room_number;

    selectedRoom = room;
    EXTRA_FEE    = room.extra_price || 0;

    document.getElementById('grid-controls').classList.add('hidden');
    document.getElementById('selected-room-info').classList.remove('hidden');
    document.getElementById('selected-room-text').innerText = `#${room.numero ?? room.id} - ${room.type}`;
    document.getElementById('extraPriceLabel').innerText    = `Cargo: $${room.extra_price.toLocaleString()} / Noche`;

    renderCapacidadUnidad({
        numero: room.id,
        tipo: room.type,
        capacidad: room.capacity,
        extra: room.extra_price
    });

    // FIX: PAX_STATE.room = room directamente (no buscar en ROOMS con selectedRoom que ya es el objeto)
    PAX_STATE.room = room;

    renderPaxGrid();
    updateTotal();

    setTimeout(() => goToStep('logistica'), 400);
}

function resetRoomSelection() {
    selectedRoom = null;
    document.getElementById('grid-controls').classList.remove('hidden');
    document.getElementById('selected-room-info').classList.add('hidden');
    renderRooms();
    updateTotal();
}

function switchFloor(f) {
    currentFloor = f;
    document.querySelectorAll('.floor-btn').forEach(b =>
        b.classList.toggle('active', b.innerText === f)
    );
    renderRooms();
}

function switchType(t) {
    currentType = t;
    document.querySelectorAll('.type-btn').forEach(b =>
        b.classList.toggle('active', b.innerText === t)
    );
    renderRooms();
}

function changeQty(type, step) {
    const el  = document.getElementById(`qty_${type}`);
    let val   = (parseInt(el.value) || 0) + step;
    if (type === 'adults' && val < 1) val = 1;
    if (val < 0) val = 0;
    el.value  = val;
    updateTotal();
}

function updateTotal() {
    const adults   = parseInt(document.getElementById('qty_adults').value) || 1;
    const kids     = parseInt(document.getElementById('qty_kids').value) || 0;
    const extra    = parseInt(document.getElementById('qty_extra').value) || 0;
    const anticipo = parseFloat(document.getElementById('anticipo_monto').value) || 0;
    const method   = document.querySelector('input[name="payment_method"]:checked')?.value || 'Efectivo';

    const warning = document.getElementById('capacity-warning');
    if (selectedRoom && warning) {
        warning.classList.toggle('hidden', (adults + kids) <= selectedRoom.capacity);
    }

    const payCont = document.getElementById('payment_details_container');
    if (method !== 'Efectivo') {
        if (payCont && payCont.children.length === 0) {
            payCont.innerHTML = `
                <div class="col-span-1"><label class="pms-label">No. Referencia / Voucher</label><input id="referencia_pago" type="text" class="pms-input" placeholder="000000"></div>
                <div class="col-span-1"><label class="pms-label">Institución Bancaria</label><input id="banco_pago" type="text" class="pms-input" placeholder="Ej. BBVA"></div>`;
        }
    } else if (payCont) {
        payCont.innerHTML = '';
    }

    if (selectedRoom) {
        const d1     = new Date(document.getElementById('checkin_date').value);
        const d2     = new Date(document.getElementById('checkout_date').value);
        const nights = Math.max(1, (d2 - d1) / (1000 * 60 * 60 * 24));
        const sub    = selectedRoom.price * nights;
        const ext    = extra * EXTRA_FEE * nights;
        const base   = sub + ext;
        const iva    = base * 0.16;
        const ish    = base * 0.03;
        const total  = base + iva + ish;
        const saldo  = total - anticipo;

        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.innerText = val;
        };

        set('summary_subtotal', "$" + sub.toLocaleString());
        set('summary_iva',      "$" + iva.toLocaleString());
        set('summary_ish',      "$" + ish.toLocaleString());
        set('summary_extra',    "$" + ext.toLocaleString());
        set('summary_total',    "$" + total.toLocaleString(undefined, { minimumFractionDigits: 0 }));
        set('summary_anticipo', "-$" + anticipo.toLocaleString());
        set('summary_saldo',    "$" + saldo.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        set('summary_room',     "#" + selectedRoom.id + " (" + selectedRoom.type + ")");
        set('summary_nights',   nights + (nights === 1 ? " Noche" : " Noches"));
        set('m_name',           document.getElementById('summary_guest')?.innerText || "");
        set('m_room',           selectedRoom.id + " (" + selectedRoom.type + ")");
        set('m_sub',            "$" + sub.toLocaleString());
        set('m_taxes',          "$" + (iva + ish).toLocaleString());
        set('m_saldo',          "$" + saldo.toLocaleString());
        set('modal_folio_id',   document.getElementById('display_folio')?.innerText || "");

        const rowExtra = document.getElementById('row_extra');
        if (rowExtra) rowExtra.classList.toggle('hidden', ext === 0);
    }
}

function syncSummary() {
    const n = document.getElementById('first_name').value;
    const a = document.getElementById('last_name').value;
    document.getElementById('summary_guest').innerText = (n + " " + a).trim() || "---";
}

/* ======================================================
   CÁMARA
====================================================== */
async function startCamerareserva(videoId) {
    const video = document.getElementById(videoId);
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false });
        video.srcObject = stream;
        CURRENT_STREAM  = stream; // FIX: guardar stream globalmente para poder detenerlo
        await video.play();
    } catch (e) {
        console.error("Error cámara", e);
        alert("No se pudo abrir la cámara");
    }
}

function stopCamerareseva(videoId) {
    const video = document.getElementById(videoId);

    // FIX: primero detener tracks, luego limpiar srcObject
    if (CURRENT_STREAM) {
        CURRENT_STREAM.getTracks().forEach(track => track.stop());
        CURRENT_STREAM = null;
    }

    if (video) {
        video.pause();
        video.srcObject = null;
    }

    console.log("📷 Cámara detenida");
}

/* ======================================================
   COMPANION LIST (legado — mantenido para compatibilidad)
====================================================== */
function addCompanion() {
    const list = document.getElementById('companionList');
    document.getElementById('noCompanionMsg').classList.add('hidden');
    const id  = Date.now();
    const div = document.createElement('div');
    div.className = 'flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm animate-fade-in group focus-within:border-indigo-600 transition-all';
    div.innerHTML = `
        <div class="relative w-11 h-11 bg-slate-100 rounded-xl border-2 border-slate-50 overflow-hidden shrink-0 flex items-center justify-center">
            <video id="v-comp-${id}" autoplay playsinline class="w-full h-full object-cover hidden"></video>
            <img id="p-comp-${id}" class="comp-photo w-full h-full object-cover hidden">
            <button type="button" onclick="startCamerareserva('v-comp-${id}'); document.getElementById('v-comp-${id}').classList.remove('hidden')" class="text-slate-400 group-hover:text-indigo-600 transition-colors"><i class="fas fa-camera text-xs"></i></button>
            <button type="button" onclick="takePhotoreserva('v-comp-${id}', 'p-comp-${id}')" class="absolute bottom-0 right-0 bg-indigo-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[7px] border-2 border-white"><i class="fas fa-check"></i></button>
        </div>
        <div class="flex-1 grid grid-cols-12 gap-2">
            <input type="text" placeholder="Nombre completo" class="col-span-5 bg-transparent h-10 px-2 outline-none text-[12px] font-bold text-slate-800 placeholder:text-slate-300">
            <select class="col-span-4 bg-transparent h-10 px-1 outline-none text-[11px] font-bold text-slate-700">
                <option>Esposa</option><option>Esposo</option><option>Hijo/a</option>
                <option>Familiar</option><option>Compañero</option><option>Otro</option>
            </select>
            <div class="col-span-3 flex items-center gap-2">
                <label class="switch"><input type="checkbox"><span class="slider"></span></label>
                <span class="text-[9px] font-black text-slate-400 uppercase leading-none">Menor</span>
            </div>
        </div>
        <button onclick="this.parentElement.remove(); if(list.children.length === 1) document.getElementById('noCompanionMsg').classList.remove('hidden');" class="text-red-300 hover:text-red-500 transition-colors p-2"><i class="fas fa-trash-alt text-base"></i></button>`;
    list.appendChild(div);
}

/* ======================================================
   CERRAR MODAL
====================================================== */
function cerrarHabitacion() {
    const modal = document.getElementById("master-modal-container");
    if (!modal) { console.error("No existe modal"); return; }

    modal.classList.remove("show");
    CURRENT_ROOM_ID      = null;
    CURRENT_GUEST_ID     = null;
    CURRENT_REGISTRO_ID  = null;

    const form = modal.querySelector("form");
    if (form) form.reset();

    const list = document.getElementById("companionList");
    if (list) list.innerHTML = "";

    const msg = document.getElementById("noCompanionMsg");
    if (msg) msg.classList.remove("hidden");

    setTimeout(() => location.reload(), 400);
}

function imprimirTicket() {
    if (!CURRENT_REGISTRO_ID) { alert("No hay registro"); return; }
    window.open(base_url + "reservacion/ticket/" + CURRENT_REGISTRO_ID, "_blank", "width=400,height=600");
}

// FIX: openFinalModal usaba "selectedRoomData" (no definida) → corregido a "selectedRoom"
function openFinalModal() {
    if (!selectedRoom) return alert("Por favor, seleccione una habitación primero.");
    if (!document.getElementById('first_name').value) return alert("Faltan datos del titular del registro.");
    document.getElementById('finalModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('finalModal').classList.add('hidden');
}

/* ======================================================
   PAX STATE
====================================================== */
const PAX_STATE = {
    titular: {},
    acompanantes: [],
    editingId: null,
    room: null
};

/* ======================================================
   RENDER PAX GRID
====================================================== */
function renderPaxGrid() {
    const grid = document.getElementById('pax-visual-grid');
    if (!grid) return;

    // FIX: selectedRoom es el objeto plano con .capacity directamente
    if (!selectedRoom) return;

    const nombre    = document.getElementById("first_name")?.value.trim() || "";
    const apellido  = document.getElementById("last_name")?.value.trim() || "";
    const capacidad = selectedRoom.capacity || 0; // FIX: era selectedRoom.room.capacity (incorrecto)

    PAX_STATE.titular = {
        nombre,
        apellido,
        foto: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
    };

    grid.innerHTML = `
        <div class="pax-item" onclick="openPaxModal('titular')">
            <img src="${PAX_STATE.titular.foto}" class="pax-photo" style="border-color:#4f46e5;">
            <span class="pax-name">Titular</span>
        </div>`;

    const acompanantes = PAX_STATE.acompanantes || [];

    acompanantes.forEach((p, index) => {
        const isExtra = (index + 2) > capacidad;
        grid.innerHTML += `
            <div class="pax-item" onclick="openPaxModal(${p.id})">
                <img src="${p.foto}" class="pax-photo" style="${isExtra ? 'border-color:#fbbf24;border-style:dashed;' : ''}">
                <span class="pax-name">${p.nombre}</span>
                ${isExtra ? '<span class="extra-person-badge">Extra</span>' : ''}
            </div>`;
    });

    grid.innerHTML += `<div class="btn-add-pax" onclick="openPaxModal(null)">+</div>`;
}

/* ======================================================
   PAX MODAL
====================================================== */
function openPaxModal(id) {
    PAX_STATE.editingId = id;

    const acompanantes = PAX_STATE.acompanantes || [];
    const max          = PAX_STATE.room?.capacity || 0;
    const currentTotal = 1 + acompanantes.length;
    const alertEl      = document.getElementById('pax-excess-alert');

    ['pax-input-first','pax-input-last','pax-input-id-num','pax-input-obs','pax-input-order']
        .forEach(elId => { const el = document.getElementById(elId); if (el) el.value = ""; });

    const minor   = document.getElementById('pax-input-minor');
    const preview = document.getElementById('modal-pax-preview');
    const cam     = document.getElementById('camera-icon');

    if (minor)   minor.checked = false;
    if (preview) preview.classList.add('hidden');
    if (cam)     cam.classList.remove('hidden');

    if (id === 'titular') {
        if (alertEl) alertEl.classList.add('hidden');
        document.getElementById('pax-input-first').value = PAX_STATE.titular.nombre;
        document.getElementById('pax-input-last').value  = PAX_STATE.titular.apellido;

    } else if (id !== null) {
        const p = acompanantes.find(a => a.id == id);
        if (!p) return;

        document.getElementById('pax-input-first').value    = p.nombre;
        document.getElementById('pax-input-last').value     = p.apellido;
        document.getElementById('pax-input-relation').value = p.tipo;
        if (minor)   minor.checked = p.es_menor;
        if (preview) { preview.src = p.foto; preview.classList.remove('hidden'); }
        if (cam)     cam.classList.add('hidden');
        if (alertEl) alertEl.classList.add('hidden');

    } else {
        if (alertEl) {
            alertEl.classList.toggle('hidden', currentTotal < max);
        }
    }

    toggleModal('modal-pax-capture', true);
}

function savePaxData() {
    const nombre   = document.getElementById('pax-input-first').value.trim();
    const apellido = document.getElementById('pax-input-last').value.trim();
    const relation = document.getElementById('pax-input-relation').value;
    const isMinor  = document.getElementById('pax-input-minor').checked;

    if (!nombre) return;

    let acompanantes = PAX_STATE.acompanantes || [];
    const max        = parseInt(PAX_STATE.room?.capacity) || 0;

    if (PAX_STATE.editingId !== null) {
        const idx = acompanantes.findIndex(a => a.id == PAX_STATE.editingId);
        if (idx !== -1) {
            acompanantes[idx] = { ...acompanantes[idx], nombre, apellido, tipo: relation, es_menor: isMinor };
        }
    } else {
        acompanantes.push({
            id: Date.now(),
            nombre,
            apellido,
            tipo: relation,
            es_menor: isMinor,
            es_extra: false,
            foto: isMinor
                ? "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=150"
                : "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=150"
        });
    }

    if (max > 0) {
        acompanantes = acompanantes.map((p, index) => ({
            ...p,
            es_extra: (index + 2) > max
        }));
    }

    PAX_STATE.acompanantes = acompanantes;
    renderPaxGrid();
    toggleModal('modal-pax-capture', false);
}

function deletePax() {
    if (PAX_STATE.editingId === null || PAX_STATE.editingId === 'titular') return;
    PAX_STATE.acompanantes = (PAX_STATE.acompanantes || []).filter(p => p.id != PAX_STATE.editingId);
    renderPaxGrid();
    toggleModal('modal-pax-capture', false);
}

function guardarPaxBackend() {
    fetch(base_url + "huespedes/guardar", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            titular: PAX_STATE.titular,
            acompanantes: PAX_STATE.acompanantes,
            room_id: PAX_STATE.room?.room_number
        })
    })
        .then(r => r.json())
        .then(res  => console.log("✅ Guardado backend", res))
        .catch(err => console.error("❌ Error backend", err));
}

function toggleModal(id, show) {
    const el = document.getElementById(id);
    if (!el) return;
    if (show) el.classList.add('active');
    else el.classList.remove('active');
}