let HUESPED = null;
//let ACOMPANANTES = null;
let streamInstance = null;
let CURRENT_FLOOR_ADMIN = null;
let CAMERA_STREAM_EDIT = null;

let DATA = {
    titular: {
        nombre: '',
        apellido: '',
        id: '',
        id_Huesped: '',
        fotografia: '',
    },
    personas_max: 0,
    vehiculos: [],
    estado_registro: '',
    acompanantes: [],
    consumos: [],
    pagos_realizados: [],
    pagos: [],
    pendingDelete: null,
    stay: {
        arrival: '',
        departure: '',
        dailyRate: 0,
        room: '',
        roomType: ''

    }   // ⭐ SIEMPRE array, nunca undefined
};




/* =========================================================
   FORMATOS
========================================================= */

function fechaCortaHotel(f) {

    const d = new Date(f);
    return d.toLocaleDateString("es-MX", {
        day: "2-digit",
        month: "2-digit",
        year: "2-digit"
    });
}

function formatoHabitacion(n) {
    return String(n).padStart(3, "0");
}

function cargarTiposID(selectedId = null) {

    fetch(base_url + "pasajeros/catalogo_tipos_identificacion")
        .then(r => r.json())
        .then(data => {

            const select = document.getElementById("edit-tipo_identificacion_id");

            select.innerHTML = `<option value="">Seleccione</option>`;

            data.forEach(t => {
                select.innerHTML += `
                <option value="${t.id}">
                    ${t.nombre}
                </option>`;
            });

            // ⭐ seleccionar después de llenar
            if (selectedId) {
                select.value = selectedId;
            }

        })
        .catch(e => console.error("Error tipos ID", e));

}

function switchSection(sectionId) {
    document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
    document.getElementById('tab-' + sectionId).classList.add('active');

    ['resumen', 'cargos', 'pagos', 'room_move', 'room_service', 'bitacora'].forEach(id => {
        const el = document.getElementById('sec-' + id);
        if (el) el.classList.add('hidden');
    });

    const activeSec = document.getElementById('sec-' + sectionId);
    if (activeSec) activeSec.classList.remove('hidden');


}

// --- 2. CONTROL DE VISTAS (Tabs) ---
function switchTab(sectionId, el) {

    // 🔒 bloquear navegación si ya está checkout
    if (DATA.estado_registro === 'CHECKOUT' && sectionId !== 'bitacora') {
        return;
    }

    document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));

    const tab = document.getElementById('tab-' + sectionId);
    if (tab) tab.classList.add('active');

    ['resumen', 'cargos', 'pagos', 'room_move', 'room_service', 'bitacora'].forEach(id => {
        const el = document.getElementById('sec-' + id);
        if (el) el.classList.add('hidden');
    });

    const activeSec = document.getElementById('sec-' + sectionId);
    if (activeSec) activeSec.classList.remove('hidden');

    // =========================
    // 🎯 RENDER
    // =========================
    if (sectionId === 'cargos') renderCargosTerminal();
    if (sectionId === 'pagos') renderPaymentsUI();
    if (sectionId === 'room_service') renderRoomServiceMenu();

    if (sectionId === 'room_move') {
        cargarTiposHabitacionadmin();
        cargarHabitacionesPorPisoadmin();
        cargarRoomServiceMenu();
        renderAvailableRooms();
    }

    if (sectionId === 'bitacora') updateFolioReportUI();
}


function cargarPisosadmin() {

    fetch(base_url + "catalogos/listar_pisos")
        .then(r => r.json())
        .then(data => {



            const cont = document.getElementById("floorContaineradmin");
            cont.innerHTML = '';

            data.forEach((p, i) => {

                const btn = document.createElement("button");
                btn.className =
                    "pill-btn floor-btn" + (i === 0 ? " active" : "");
                btn.innerText = p.Piso;
                btn.onclick = () => switchFlooradmin(p.Piso);
                cont.appendChild(btn);
                if (i === 0) {
                    CURRENT_FLOOR_ADMIN = p.Piso;
                }

            });

        })
        .catch(e => console.error("Error pisos", e));
}

function cargarTiposHabitacionadmin() {

    fetch(base_url + "catalogos/listar_tipos_habitacion")
        .then(r => r.json())
        .then(data => {

            const cont = document.getElementById("typeContaineradmin");
            cont.innerHTML = '';

            // ⭐ botón TODOS primero
            const btnAll = document.createElement("button");
            btnAll.className = "pill-btn type-btn active";
            btnAll.innerText = "Todos";
            btnAll.onclick = () => switchTypeadmin("Todos");
            cont.appendChild(btnAll);

            data.forEach(t => {

                const btn = document.createElement("button");

                btn.className = "pill-btn type-btn";
                btn.innerText = t.nombre;

                btn.onclick = () => switchTypeadmin(t.nombre);

                cont.appendChild(btn);

            });

        })
        .catch(e => console.error("Error tipos habitacion", e));
}

function cargarHabitacionesPorPisoadmin() {

    fetch(
        base_url +
        "habitaciones/listado_visual"
    )
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
                    extra_price: parseFloat(r.precio_persona_extra),   // ⭐ IMPORTANTE
                    estado: r.estado_id
                });

            });

            renderRoomsadmin();

        });
}




/* =========================================================
   FUNCIONES DE CAMARA
========================================================= */

async function takePhoto() {

    try {

        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('photo-canvas');
        const img = document.getElementById('edit-preview-foto');

        const btnStart = document.getElementById('btn-start-camera');
        const btnTake = document.getElementById('btn-take-photo');

        if (!video || video.videoWidth === 0) {
            alert("La cámara no está lista");
            return;
        }

        // ===== CAPTURA =====
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const base64 = canvas.toDataURL('image/jpeg', 0.85);

        // ⭐ preview LOCAL inmediato (no esperar upload)
        img.src = base64;
        img.classList.remove('hidden');

        // ===== UI =====
        stopCamera();
        video.classList.add('hidden');
        btnStart.classList.remove('hidden');
        btnTake.classList.add('hidden');

        // ===== SUBIDA =====
        const fileName = await subirFoto(base64);

        if (fileName) {

            // ⭐ guardar filename REAL
            HUESPED.fotografia = fileName;

            // ⭐ guardar dataset (CLAVE para save)
            img.dataset.file = fileName;

            // ⭐ cambiar preview a URL real del servidor
            img.src = base_url + "uploads/fotos/" + fileName;

            console.log("✅ Foto subida:", fileName);

        } else {

            // ⭐ fallback → se queda preview base64
            img.dataset.file = null;

            console.warn("⚠️ Foto no subida, solo preview local");

        }

    } catch (err) {

        console.error("Error takePhoto:", err);
        alert("Error al capturar la foto");

    }
}

// --- 3. LÓGICA DE CÁMARA (Hardware) ---

// Función 1: Prender Cámara
async function startCamera() {
    try {
        // Solicitar acceso a la cámara
        streamInstance = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: 400, height: 400 }
        });

        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('edit-preview-foto');
        const btnStart = document.getElementById('btn-start-camera');
        const btnTake = document.getElementById('btn-take-photo');

        // Cambiar visibilidad de elementos
        video.srcObject = streamInstance;
        video.classList.remove('hidden');
        preview.classList.add('hidden');
        btnStart.classList.add('hidden');
        btnTake.classList.remove('hidden');

    } catch (err) {
        console.error("Error al acceder a la cámara:", err);
        alert("No se pudo acceder a la cámara. Verifique los permisos.");
    }
}


// Función Auxiliar: Apagar Hardware
function stopCamera() {
    if (streamInstance) {
        streamInstance.getTracks().forEach(track => track.stop());
        streamInstance = null;
    }
    const video = document.getElementById('camera-stream');
    if (video) video.classList.add('hidden');
}


function loadFoto(foto) {
    const img = document.getElementById("edit-preview-foto");
    if (foto) {
        img.dataset.file = foto;
        img.src = base_url + "uploads/fotos/" + foto;
        img.classList.remove("hidden");
    } else {
        img.dataset.file = null;
        img.src = base_url + "assets/img/user-placeholder.png";
    }
}


/* =========================================================
   GUARDAR FOTOS HUÉSPED 
   ========================================================= */
async function subirFoto(base64) {

    try {

        const res = await fetch(base_url + "media/subir-foto", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                foto: base64
            })
        });

        const resp = await res.json();

        if (resp.ok) {
            return resp.file;   // ⭐ nombre archivo
        } else {
            alert("Error subiendo foto");
            return null;
        }

    } catch (e) {
        console.error(e);
        return null;
    }

}


/* =========================================================
   ABRIR MODAL ADMINISTRATIVO
========================================================= */
function abrirReservacion(id) {

    CURRENT_REGISTRO_ID = id;

    const modal = document.getElementById("admin-modal-container");

    if (!modal) {
        console.error("No existe modal");
        return;
    }

    modal.classList.add("show");


    cargarResumenAdministrativo(id);

    cargarPisosadmin();
    /*   cargarTiposHabitacionadmin(); */
    /* cargarHabitacionesPorPisoadmin();*/
    cargarRoomServiceMenu();
    cargarAcompanantes();
    cargarServiciosPOS();
    //cargarFormasPagoadmin();
    cargarVehiculos();

    handlePaymentMethod('Efectivo', document.querySelector('.payment-card.active'));

    cargarConsumos(id);
    cargarPagos(id);
    updateUI();

    cargarPerfilFiscal();
    updateFacturacionStatus();


}


/* =========================================================
   ABRIR MODAL EDITAR PERFIL
========================================================= */

function toggleEditModal(show) {
    const modal = document.getElementById('modal-edit-profile');
    if (show) {
        cargarHuesped();

        modal.classList.add('active');
    } else {
        stopCamera(); // Importante: Apagar cámara si se cierra el modal
        modal.classList.remove('active');
    }
}



/* ======================================================
   CARGAR RESUMEN ADMINISTRATIVO
====================================================== */
async function cargarResumenAdministrativo(id) {

    try {

        const res = await fetch(base_url + "reservacion/resumen/" + id);
        const data = await res.json();

        console.log("RESUMEN", data);

        CURRENT_GUEST_ID = data.huesped_id;

        document.getElementById("admin_nombre").innerText = data.Nombre_Huesped;
        document.getElementById("admin_folio").innerText = data.folio;

        document.getElementById("admin_fecha_registro").innerText = data.admin_fecha_registro;
        document.getElementById("admin_fecha_entrada").innerText = fechaCortaHotel(data.hora_entrada);
        document.getElementById("admin_fecha_salida").innerText = fechaCortaHotel(data.hora_salida);
        document.getElementById("admin_habitacion").innerText = data.tip_habitacion + " #" + formatoHabitacion(data.Numero_Habitacion);

        document.getElementById("folio-number").innerText = formatoHabitacion(data.Numero_Habitacion) + ' ' + data.folio;

        DATA.currentRoom = "#" + formatoHabitacion(data.Numero_Habitacion);

        DATA.stay = {
            arrival: data.hora_entrada?.split(' ')[0],
            departure: data.hora_salida?.split(' ')[0],
            dailyRate: data.precio_base,
            room: "#" + formatoHabitacion(data.Numero_Habitacion),
            roomType: data.tip_habitacion
        };


        DATA.personas_max = data.personas_max || 0;

        DATA.titular = {
            nombre: (data.nombre || "").trim(),
            apellido: (data.apellido || "").trim(),
            id: `${data.tipo_identificacion || ""}-${data.numero_identificacion || ""}`,
            id_Huesped: data.id,
            fotografia: data.fotografia
                ? base_url + "uploads/fotos/" + data.fotografia
                : "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
        };

        // 🔥 AQUÍ GUARDAS EL ESTADO
        DATA.estado_registro = data.estado_registro;

        DATA.tarifa_extra = data.precio_persona_extra  // precio por noche
        DATA.noches = data.noches         // noches de la estadía




        // 🔥🔥🔥 CLAVE
        updateUI();
        cargarConsumos(id);
        // 🔥 AQUÍ APLICAS REGLAS DE UI
        applyRegistroState();
        cargarVehiculos();


        // 🔥 AQUÍ EL TOAST
        if (DATA.estado_registro === 'CHECKOUT') {
            showToast("Folio cerrado - modo solo lectura");
        }

    } catch (e) {
        console.error(e);
    }
}


/* =========================================================
   HOTEL OS
   Cargar datos huésped para modal edición perfil
   ========================================================= */


function cargarHuesped() {
    fetch(base_url + "pasajeros/obtener_huesped/" + CURRENT_GUEST_ID)
        .then(r => r.json())
        .then(data => {
            HUESPED = data;   // ✅ aquí se llena la variable global
            syncDataToForm();
            // cargarTiposID()
            console.log("HUESPED cargado:", HUESPED);
        })
        .catch(e => console.error("Error cargar huésped", e));
}


function syncDataToForm() {
    document.getElementById('edit-nombre').value = HUESPED.nombre;
    document.getElementById('edit-apellido').value = HUESPED.apellido;
    document.getElementById('edit-genero').value = HUESPED.genero;
    document.getElementById('edit-fecha_nacimiento').value = HUESPED.fecha_nacimiento;
    document.getElementById('edit-tipo_identificacion_id').value = cargarTiposID(HUESPED.tipo_identificacion_id);
    document.getElementById('edit-numero_identificacion').value = HUESPED.numero_identificacion;
    document.getElementById('edit-email').value = HUESPED.email;
    document.getElementById('edit-telefono').value = HUESPED.telefono;
    document.getElementById('edit-direccion').value = HUESPED.direccion;
    document.getElementById('edit-ciudad').value = HUESPED.ciudad;
    document.getElementById('edit-pais').value = HUESPED.pais;
    document.getElementById('edit-notas').value = HUESPED.notas;
    document.getElementById('edit-activo').checked = (HUESPED.activo === 1);
    console.log(base_url + 'uploads/fotos/' + HUESPED.fotografia);
    document.getElementById('edit-preview-foto').src = DATA.titular.fotografia

}


/* =====================================================
GUARDAR PERFIL HUESPED → HOTEL OS
===================================================== */
async function saveProfile() {

    try {

        const btn = document.getElementById('btn-save-profile');
        if (btn) btn.disabled = true;

        const fotoEl = document.getElementById("edit-preview-foto");
        let foto = fotoEl?.dataset?.file || null;

        // 🔴 VALIDACIÓN CRÍTICA
        if (foto && foto.startsWith("data:image")) {
            alert("La foto aún no se ha subido. Toma la foto nuevamente.");
            if (btn) btn.disabled = false;
            return;
        }

        const payload = {
            id: CURRENT_GUEST_ID,
            nombre: document.getElementById('edit-nombre').value.trim(),
            apellido: document.getElementById('edit-apellido').value.trim(),
            email: document.getElementById('edit-email').value.trim(),
            genero: document.getElementById('edit-genero').value,
            telefono: document.getElementById('edit-telefono').value.trim(),
            direccion: document.getElementById('edit-direccion').value.trim(),
            ciudad: document.getElementById('edit-ciudad').value.trim(),
            tipo_identificacion_id: document.getElementById('edit-tipo_identificacion_id').value,
            numero_identificacion: document.getElementById('edit-numero_identificacion').value.trim(),
            notas: document.getElementById('edit-notas').value.trim(),
            fotografia: foto
        };

        // 🔥 VALIDACIÓN BÁSICA
        if (!payload.nombre) {
            alert("El nombre es obligatorio");
            if (btn) btn.disabled = false;
            return;
        }

        console.log("HUESPED guardado:", payload);

        const response = await fetch(base_url + "pasajeros/guardar_huesped", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error("Error HTTP: " + response.status);
        }

        const resp = await response.json();

        console.log("SAVE RESP", resp);

        if (resp.ok) {

            // 🔥 sincronizar frontend
            Object.assign(HUESPED, payload);

            // 🔥 actualizar foto en UI correctamente
            if (payload.fotografia) {
                const preview = document.getElementById("edit-preview-foto");
                preview.src = base_url + "uploads/fotos/" + payload.fotografia;
            }

            updateUI();
            toggleEditModal(false);

            alert("✓ Perfil actualizado");

        } else {
            alert(resp.msg || "No se pudo guardar");
        }

    } catch (e) {

        console.error("❌ Error guardando huésped:", e);
        alert("Error de servidor");

    } finally {

        const btn = document.getElementById('btn-save-profile');
        if (btn) btn.disabled = false;
    }

    cargarHuesped();
}



/* =====================================================
GUARDAR PERFIL ACOMPAÑANTE
===================================================== */

function cargarAcompanantes() {

    fetch(base_url + "pasajeros/acompanantes/" + CURRENT_REGISTRO_ID)
        .then(r => r.json())
        .then(data => {

            DATA.acompanantes = data;

            console.log("HUESPED cargado:", DATA.acompanantes);

            renderCompanionsList();

            // 🔥 NUEVO
            renderPaxSummary();

        })
        .catch(e => console.error("Error cargar huésped", e));
}
// --- 1. DATOS (Mapeado a tablas SQL) ---
/* let TITULAR = {
    nombre: "Israel de los Santos",
    id: "INE-992210",
    fotografia: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
}; */

let currentCompIdx = -1;


// --- 2. CONTROL DE MODALES ---

function toggleModal(id, show) {
    const el = document.getElementById(id);
    if (show) {
        el.classList.add('active');
        if (id === 'modal-modify-stay') initModifyModal();
        if (id === 'modal-vehicles') {
            renderVehicleOwners();
            renderVehiclesList();
        }
    } else {
        el.classList.remove('active');
    }
}




// --- 2. CONTROL DE MODALES ---
function toggleCompanionsList(show) {
    const m = document.getElementById('modal-companions-list');
    if (show) {

        cargarAcompanantes();
        m.classList.add('active');
    }
    else m.classList.remove('active');
}





function toggleCompanionForm(show) {
    const m = document.getElementById('modal-companion-form');
    if (show) m.classList.add('active');
    else { stopCamera(); m.classList.remove('active'); }
}

function toggleTitularModal(show) {
    const m = document.getElementById('modal-titular');
    if (show && m) m.classList.add('active');
    else if (m) m.classList.remove('active');
}

// --- 4. GESTIÓN DE ACOMPAÑANTES (CRUD) ---
function renderCompanionsList() {

    const grid = document.getElementById('companions-grid');
    grid.innerHTML = '';

    // =========================
    // 🔹 HELPER FOTO
    // =========================
    function getFoto(foto, esMenor = false) {

        if (!foto) {
            return esMenor
                ? "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200"
                : "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=200";
        }

        if (foto.startsWith('data:image')) return foto;
        if (foto.startsWith('http')) return foto;

        foto = foto.replace(/\\/g, '/');
        foto = foto.replace(/^.*uploads\/fotos\//, '');

        return base_url.replace(/\/$/, '') + "/uploads/fotos/" + foto;
    }

    // =========================
    // 🔹 CONFIG
    // =========================
    const limite = DATA.personas_max || 0;
    const titular = DATA.titular ? 1 : 0;

    let totalAcompanantes = 0;
    let extras = 0;

    // =========================
    // 🔹 RENDER
    // =========================
    DATA.acompanantes.forEach((c, idx) => {

        totalAcompanantes++;

        const esMenor = parseInt(c.es_menor) === 1;
        const esExtra = parseInt(c.es_ext) === 1;

        if (esExtra) extras++;

        const fotoFinal = getFoto(c.fotografia, esMenor);

        grid.insertAdjacentHTML('beforeend', `
            <div class="border rounded-2xl p-4 flex items-center justify-between 
                        shadow-sm hover:shadow-md transition-all duration-200
                        ${esExtra ? 'border-red-300 bg-red-50' : 'bg-white'}">

                <!-- IZQUIERDA -->
                <div class="flex items-center gap-4">

                    <!-- FOTO (LIMPIA) -->
                    <div class="relative">
                        <img src="${fotoFinal}" 
                             class="w-14 h-14 rounded-xl object-cover border"
                             onerror="this.src='https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=200'">

                        ${esExtra ? `
                            <span class="absolute -bottom-1 -right-1 text-[8px] px-1.5 py-0.5 rounded bg-red-500 text-white font-bold">
                                EXTRA
                            </span>
                        ` : ''}
                    </div>

                    <!-- INFO -->
                    <div>
                        <h4 class="font-black text-slate-900">
                            ${c.nombre} ${c.apellido}
                        </h4>

                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            ${c.parentesco || 'Sin parentesco'} · 
                            <span class="${esMenor ? 'text-yellow-600' : ''}">
                                ${esMenor ? 'Menor' : 'Adulto'}
                            </span>
                        </p>

                        <p class="text-[10px] font-semibold ${esExtra ? 'text-red-500' : 'text-indigo-500'}">
                            ${c.estado_estancia || (esExtra ? 'Persona extra' : '—')}
                        </p>
                    </div>

                </div>

                <!-- DERECHA -->
                <div class="flex gap-2">

                    <button onclick="openCompanionForm(${idx})"
                        class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center 
                               hover:bg-indigo-600 hover:text-white transition-all">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button onclick="deleteCompanion(${idx})"
                        class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center 
                               hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-trash"></i>
                    </button>

                </div>

            </div>
        `);
    });

    // =========================
    // 🔥 CONTADOR FINAL
    // =========================
    const total = totalAcompanantes + titular;

    const counter = document.getElementById('companions-counter-footer');

    counter.innerHTML = `
        <span class="${total > limite ? 'text-red-500' : 'text-slate-800'} font-black">
            ${total}
        </span>
        / ${limite} personas

        ${extras > 0
            ? `<span class="text-[10px] text-red-500 ml-2 font-bold">(${extras} extra)</span>`
            : ''}
    `;
}

function openCompanionForm(idx) {

    currentCompIdx = idx;
    const title = document.getElementById('comp-form-title');
    window._temp_photo = null;

    // =========================
    // 🔹 HELPER FOTO
    // =========================
    function getFoto(foto, esMenor = false) {

        if (!foto) {
            return esMenor
                ? "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200"
                : "https://via.placeholder.com/200?text=Sin+Foto";
        }

        if (foto.startsWith('data:image')) return foto;
        if (foto.startsWith('http')) return foto;

        // normalizar path Windows
        foto = foto.replace(/\\/g, '/');

        // quitar prefijo si viene completo
        foto = foto.replace(/^.*uploads\/fotos\//, '');

        // construir URL final
        return base_url.replace(/\/$/, '') + "/uploads/fotos/" + foto;
    }

    // =========================
    // 🔹 RESET CAMPOS
    // =========================
    document.getElementById('comp-name').value = '';
    document.getElementById('comp-lastName').value = '';
    document.getElementById('comp-rel').value = '';
    document.getElementById('comp-tipo-id').value = 'INE';
    document.getElementById('comp-id').value = '';
    document.getElementById('comp-minor').checked = false;
    document.getElementById('comp-orden').value = DATA.acompanantes.length + 1;
    document.getElementById('comp-entrada').value = '';
    document.getElementById('comp-salida').value = '';
    document.getElementById('comp-estado').value = 'Hospedado';
    document.getElementById('comp-obs').value = '';

    const preview = document.getElementById('preview-comp');
    preview.src = 'https://via.placeholder.com/200?text=Sin+Foto';

    // =========================
    // 🔹 EDITAR
    // =========================
    if (idx >= 0) {

        const c = DATA.acompanantes[idx];
        title.innerText = "Editar Acompañante";

        document.getElementById('comp-name').value = c.nombre;
        document.getElementById('comp-lastName').value = c.apellido;
        document.getElementById('comp-rel').value = c.parentesco;
        document.getElementById('comp-tipo-id').value = c.tipo_identificacion;
        document.getElementById('comp-id').value = c.numero_identificacion;
        document.getElementById('comp-minor').checked = c.es_menor === 1;
        document.getElementById('comp-orden').value = c.orden_llegada;
        document.getElementById('comp-entrada').value = c.hora_entrada;
        document.getElementById('comp-salida').value = c.hora_salida;
        document.getElementById('comp-estado').value = c.estado_estancia;
        document.getElementById('comp-obs').value = c.observaciones;

        // =========================
        // 📸 FIX FOTO
        // =========================
        if (c.fotografia) {

            const fotoFinal = getFoto(c.fotografia, c.es_menor === 1);

            preview.src = fotoFinal;

            // 🔥 mantener valor original (NO URL transformada)
            window._temp_photo = c.fotografia;

        } else {
            window._temp_photo = null;
        }

    }
    // =========================
    // 🔹 NUEVO
    // =========================
    else {
        title.innerText = "Registrar Nuevo Acompañante";
        window._temp_photo = null;
    }

    toggleCompanionForm(true);
}

async function saveCompanion() {

    try {

        const btn = document.getElementById('btn-save-comp');
        if (btn) btn.disabled = true;

        // =========================
        // 📸 FOTO
        // =========================
        const fotoEl = document.getElementById('preview-comp');
        let foto = fotoEl?.dataset?.file || null;

        if (foto && foto.startsWith("data:image")) {
            alert("La foto del acompañante aún no se ha subido");
            if (btn) btn.disabled = false;
            return;
        }

        // =========================
        // 📅 CALCULAR NOCHES
        // =========================
        function calcNoches(arrival, departure) {
            if (!arrival || !departure) return 1;

            const f1 = new Date(arrival);
            const f2 = new Date(departure);

            const diff = (f2 - f1) / (1000 * 60 * 60 * 24);
            return diff > 0 ? diff : 1;
        }

        const noches = calcNoches(DATA.stay.arrival, DATA.stay.departure);

        // =========================
        // 📦 PAYLOAD
        // =========================
        const payload = {

            id: currentCompIdx >= 0
                ? DATA.acompanantes[currentCompIdx].id
                : null,

            registro_id: CURRENT_REGISTRO_ID,

            nombre: document.getElementById('comp-name').value.trim(),
            apellido: document.getElementById('comp-lastName').value.trim(),
            parentesco: document.getElementById('comp-rel').value,
            tipo_identificacion: document.getElementById('comp-tipo-id').value,
            numero_identificacion: document.getElementById('comp-id').value.trim(),
            es_menor: document.getElementById('comp-minor').checked ? 1 : 0,
            orden_llegada: document.getElementById('comp-orden').value,
            hora_entrada: document.getElementById('comp-entrada').value,
            hora_salida: document.getElementById('comp-salida').value,
            estado_estancia: 'ACTIVO',
            observaciones: document.getElementById('comp-obs').value.trim(),

            fotografia: foto,
            es_ext: 0
        };

        if (!payload.nombre) {
            alert("El nombre del acompañante es obligatorio");
            if (btn) btn.disabled = false;
            return;
        }

        // =========================
        // 🔥 DETECTAR EXTRA
        // =========================
        let esExtra = 0;
        let totalExtra = 0;

        if (currentCompIdx < 0) {

            const titular = 1; // 🔥 SIEMPRE hay titular
            const actuales = DATA.acompanantes.length + titular;
            const limite = DATA.personas_max || 0;

            if (actuales >= limite) {

                esExtra = 1;

                const tarifa = Number(DATA.stay.dailyRate) || 0;

                totalExtra = tarifa * noches;

                const confirmar = confirm(
                    `⚠️ Persona adicional detectada\n\n` +
                    `Tarifa por noche: $${tarifa.toLocaleString()}\n` +
                    `Noches: ${noches}\n\n` +
                    `Total a cobrar: $${totalExtra.toLocaleString()}\n\n` +
                    `¿Deseas continuar?`
                );

                if (!confirmar) {
                    if (btn) btn.disabled = false;
                    return;
                }
            }
        }

        payload.es_ext = esExtra;

        console.log("📤 PAYLOAD ACOMP", payload);

        // =========================
        // 🚀 GUARDAR
        // =========================
        const response = await fetch(base_url + "pasajeros/guardar_acompanante_update", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const text = await response.text();

        let resp;
        try {
            resp = JSON.parse(text);
        } catch (e) {
            alert("Respuesta inválida del servidor:\n\n" + text);
            return;
        }

        if (!response.ok) {
            alert(`Error HTTP ${response.status}\n\n${text}`);
            return;
        }

        if (resp.ok) {

            if (resp.id) {
                payload.id = resp.id;
            }

            if (currentCompIdx >= 0) {
                Object.assign(DATA.acompanantes[currentCompIdx], payload);
            } else {
                DATA.acompanantes.push(payload);
            }

            // =========================
            // 💳 GENERAR CARGO
            // =========================
            if (esExtra) {
                await applyCharge(
                    "Persona adicional",
                    "Persona Extra",
                    totalExtra,
                    1
                );
            }

            // =========================
            // 🔄 UI
            // =========================
            if (payload.fotografia) {
                fotoEl.src = base_url + "uploads/fotos/" + payload.fotografia;
            }

            renderCompanionsList();
            toggleCompanionForm(false);

        } else {

            alert(`
Error backend:

${resp.msg}

${JSON.stringify(resp.errors || {}, null, 2)}
            `);
        }

    } catch (e) {

        console.error("❌ Error save acompañante", e);
        alert("Error servidor:\n" + e.message);

    } finally {

        const btn = document.getElementById('btn-save-comp');
        if (btn) btn.disabled = false;
    }
}


/* =====================================================
   ELIMINAR ACOMPAÑANTE (HotelOS)
   ===================================================== */
async function deleteCompanion(idx) {

    const comp = DATA.acompanantes[idx];
    if (!comp) return;

    if (!confirm("¿Eliminar acompañante?")) return;

    try {

        // =========================
        // 🔥 CANCELAR CARGO SI ERA EXTRA
        // =========================
        if (parseInt(comp.es_ext) === 1) {

            const tarifa = Number(DATA.stay.dailyRate) || 0;

            const noches = (() => {
                if (!DATA.stay.arrival || !DATA.stay.departure) return 1;

                const f1 = new Date(DATA.stay.arrival);
                const f2 = new Date(DATA.stay.departure);

                const diff = (f2 - f1) / (1000 * 60 * 60 * 24);
                return diff > 0 ? diff : 1;
            })();

            const total = tarifa * noches;

            // 🔴 movimiento inverso
            await applyCharge(
                "Cancelación persona adicional",
                "SISTEMA",
                total * -1,
                1
            );
        }

        // =========================
        // 🗑 ELIMINAR ACOMPAÑANTE
        // =========================
        const resp = await fetch(base_url + "pasajeros/eliminar_acompanante/" + comp.id, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" }
        });

        const data = await resp.json();

        console.log("DELETE ACOMP", data);

        if (!data.ok) {
            throw new Error(data.msg || "No se pudo eliminar");
        }

        // =========================
        // 🔄 UI
        // =========================
        DATA.acompanantes.splice(idx, 1);

        renderCompanionsList();
        updateUI();

    } catch (e) {

        console.error("Error eliminar acompañante", e);
        alert(e.message || "Error servidor");

    }
}


function cargarConsumos(registro_id) {

    if (!registro_id) {
        console.warn("⚠️ registro_id requerido");
        return;
    }

    fetch(base_url + "registro-cargos/listar/" + registro_id)
        .then(r => r.json())
        .then(resp => {

            console.log("📥 RAW CONSUMOS:", resp);

            const data = resp.data || [];

            DATA.consumos = data.map(item => {

                const f = new Date(item.created_at);

                const fecha =
                    f.toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: '2-digit'
                    }) +
                    ' ' +
                    f.toLocaleTimeString('es-MX', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                return {
                    id: item.id,
                    fecha: fecha,
                    concepto: item.concepto || '',
                    depto: (item.departamento || 'SISTEMA').toUpperCase(),
                    monto: Number(item.precio_unitario) || 0,
                    //  monto: Number(item.subtotal) || 0,
                    qty: item.cantidad || 1,
                    tipo: (item.tipo || ''),
                    estado: item.estado || 'ACTIVO'
                };
            });

            console.log("✅ DATA.consumos:", DATA.consumos);

            updateUI();


        })
        .catch(e => {
            console.error("❌ Error cargar consumos", e);
            DATA.consumos = [];
            updateUI();
        });
}


function cargarPagos(registro_id) {

    if (!registro_id) {
        console.warn("⚠️ registro_id requerido");
        return;
    }

    fetch(base_url + "registropagos/registros/" + registro_id + "/pagos")
        .then(r => r.json())
        .then(resp => {

            const data = Array.isArray(resp) ? resp : resp.data || [];

            // =========================
            // 🧠 DATA.pagos (general)
            // =========================
            DATA.pagos = data.map(item => {

                const f = new Date(item.hora_pago);

                const fecha =
                    f.toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: '2-digit'
                    }) +
                    ' ' +
                    f.toLocaleTimeString('es-MX', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                return {
                    id: item.id,
                    fecha: fecha,
                    concepto: item.concepto || '',
                    depto: item.sistema || 'SISTEMA',
                    monto: Number(item.monto) || 0,
                    qty: item.qty || 1,
                    tipo: item.tipo || 'PAGO',
                    estado: item.estado || 'APLICADO'
                };
            });

            // =========================
            // 💳 DATA.pagos_realizados (UI)
            // =========================
            DATA.pagos_realizados = data.map(item => {

                const f = new Date(item.hora_pago);

                const fecha =
                    f.toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: '2-digit'
                    }) +
                    ' ' +
                    f.toLocaleTimeString('es-MX', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                return {
                    id: item.id,
                    fecha: fecha,

                    // 🔥 código EF, TC, etc.
                    metodo: item.forma_pago_codigo || 'EF',

                    // 🔥 referencia bancaria
                    referencia: item.referencia_pago || '',

                    monto: Number(item.monto) || 0
                };
            });

            console.log("✅ DATA.pagos:", DATA.pagos);
            console.log("✅ DATA.pagos_realizados:", DATA.pagos_realizados);

            // 🔥 actualiza ambas vistas
            updateUI();
            renderPaymentsUI();

        })
        .catch(e => {
            console.error("❌ Error cargar pagos", e);

            DATA.pagos = [];
            DATA.pagos_realizados = [];

            updateUI();
            renderPaymentsUI();
        });
}

function cargarRoomServiceMenu() {

    fetch(base_url + "roomservice/front")
        .then(r => r.json())
        .then(data => {

            DATA.roomServiceMenu = data.map(item => {

                return {
                    id: Number(item.id), // opcional
                    cat: item.cat,
                    label: item.label,
                    price: Number(item.price),
                    icon: item.icon
                };

            });

            console.log("DATA.roomServiceMenu:", DATA.roomServiceMenu);

            // updateUI();

        })
        .catch(e => console.error("Error cargar room service", e));

}


/*
// --- 1. MODELO DE DATOS SQL (Estado inicial) ---
DATA = {

vehiculos: [],
   /* titular: {
        nombre: "Israel",
        apellido: "de los Santos",
        id: "INE-992210",
        fotografia: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
    },
     acompanantes: [
         { id: 1, nombre: "María Fernanda", apellido: "Ruiz", parentesco: "Esposa", es_menor: 0, fotografia: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200" },
         { id: 2, nombre: "Israel Jr.", apellido: "de los Santos", parentesco: "Hijo", es_menor: 1, fotografia: "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200" }
     ],
     consumos: [
         { id: 1, fecha: '21/03 14:15', concepto: 'Hospedaje Suite Presidencial', depto: 'SISTEMA', monto: 2500, qty: 1, tipo: 'hospedaje' }
     ], 

    pagos: [
                { id: 101, fecha: '21/03', concepto: 'Garantía Inicial', monto: 1000 }
            ],  
    pagos_realizados: [],
    audit_logs: [], // Historial de auditoría interna
    currentPaymentMethod: 'Efectivo',
    pendingDelete: null,
    /*  roomServiceMenu: [
        { id: 101, cat: 'DESAYUNO', label: 'Desayuno Continental', price: 180, icon: 'fa-coffee' },
        { id: 102, cat: 'DESAYUNO', label: 'Huevos al Gusto', price: 155, icon: 'fa-egg' },
        { id: 201, cat: 'COMIDA', label: 'Hamburguesa de la Casa', price: 320, icon: 'fa-hamburger' },
        { id: 202, cat: 'COMIDA', label: 'Club Sandwich', price: 210, icon: 'fa-bread-slice' },
        { id: 203, cat: 'COMIDA', label: 'Pizza Personal', price: 285, icon: 'fa-pizza-slice' },
        { id: 301, cat: 'BEBIDAS', label: 'Vino Tinto Copa', price: 160, icon: 'fa-wine-glass' },
        { id: 302, cat: 'BEBIDAS', label: 'Cerveza Nacional', price: 85, icon: 'fa-beer' },
        { id: 303, cat: 'BEBIDAS', label: 'Refresco de Lata', price: 55, icon: 'fa-glass-whiskey' }
    ], 
    availableRooms: [
        { id: 1, num: '102', type: 'Estándar', price: 850, status: 'Limpia' },
        { id: 2, num: '105', type: 'Doble Ejecutiva', price: 1250, status: 'Limpia' },
        { id: 3, num: '208', type: 'Suite Ejecutiva', price: 1800, status: 'Limpia' },
        { id: 4, num: '301', type: 'Suite Presidencial', price: 2500, status: 'Limpia' },
        { id: 5, num: '305', type: 'Doble Premium', price: 1450, status: 'Limpia' }
    ],
   // currentRoom: '#204',
    selectedMoveRoom: null,
    roomServiceCart: []
};*/

let stream = null;

const ui = {
    set: (id, text) => { const el = document.getElementById(id); if (el) el.innerText = text; },
    html: (id, html) => { const el = document.getElementById(id); if (el) el.innerHTML = html; },
    attr: (id, attr, val) => { const el = document.getElementById(id); if (el) el[attr] = val; },
    style: (id, prop, val) => { const el = document.getElementById(id); if (el && el.style) el.style[prop] = val; }
};



// --- 3. GESTIÓN DE MODALES ---
/* function openTitularEdit() {
    ui.attr('edit-nombre', 'value', DATA.titular.nombre);
    ui.attr('edit-apellido', 'value', DATA.titular.apellido);
    ui.attr('edit-id', 'value', DATA.titular.id);
    ui.attr('prev-titular', 'src', DATA.titular.fotografia);
    document.getElementById('modal-titular').classList.add('active');
} */

/* function openCompanionsList() {
    const box = document.getElementById('companions-list-box');
    if (box) {
        box.innerHTML = '';
        DATA.acompanantes.forEach(c => {
            box.innerHTML += `
                <div class="bg-white border p-4 rounded-2xl flex items-center justify-between group fade-in">
                    <div class="flex items-center gap-4">
                        <img src="${c.fotografia}" class="w-12 h-12 rounded-xl object-cover border">
                        <div>
                            <h4 class="font-black text-slate-800 text-xs">${c.nombre} ${c.apellido}</h4>
                            <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest">${c.parentesco} | ${c.es_menor ? 'Menor' : 'Adulto'}</p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    ui.set('comp-count-label', DATA.acompanantes.length + " Registros en SQL");
    document.getElementById('modal-companions').classList.add('active');
} */

/*  function closeModal(id) { 
     if(id === 'modal-titular') stopCamera(); 
     document.getElementById(id).classList.remove('active'); 
 }
*/
// --- 4. CÁMARA ---
/*    async function startCamera(type) {
       try {
           stream = await navigator.mediaDevices.getUserMedia({ video: { width: 400, height: 400 } });
           const v = document.getElementById(`video-${type}`);
           const p = document.getElementById(`prev-${type}`);
           if (v && p) {
               v.srcObject = stream; v.classList.remove('hidden'); p.classList.add('hidden');
               ui.style('btn-cam-start', 'display', 'none'); ui.style('btn-cam-snap', 'display', 'block');
           }
       } catch (e) { console.error("Error cámara"); }
   } */

/*         function takePhoto(type) {
            const v = document.getElementById(`video-${type}`);
            const c = document.createElement('canvas');
            const p = document.getElementById(`prev-${type}`);
            if (v && p) {
                c.width = v.videoWidth; c.height = v.videoHeight;
                c.getContext('2d').drawImage(v, 0, 0);
                const data = c.toDataURL('image/png');
                p.src = data; DATA.titular.fotografia = data;
                stopCamera(); v.classList.add('hidden'); p.classList.remove('hidden');
                ui.style('btn-cam-start', 'display', 'block'); ui.style('btn-cam-snap', 'display', 'none');
                updateUI();
            }
        }

        function stopCamera() { if(stream) { stream.getTracks().forEach(t => t.stop()); stream = null; } } */

// --- 5. CARGOS Y POS ---
function quickAdd(concepto, depto, monto) {
    applyCharge(concepto, depto, monto, 1);
}

/* function addManualCharge() {
    const c = document.getElementById('manual-concept');
    const p = document.getElementById('manual-price');
    const q = document.getElementById('manual-qty');
    if (!c.value || isNaN(p.value) || parseFloat(p.value) <= 0) return;
    applyCharge(c.value, 'MANUAL', parseFloat(p.value), parseInt(q.value));
    c.value = ''; p.value = ''; q.value = 1;
}
 */

async function addManualCharge() {

    try {

        const c = document.getElementById('manual-concept');
        const p = document.getElementById('manual-price');
        const q = document.getElementById('manual-qty');

        const concepto = c.value.trim();
        const precio = parseFloat(p.value);
        const cantidad = parseInt(q.value) || 1;

        // =========================
        // 🔒 VALIDACIONES
        // =========================
        if (!concepto) throw new Error("Captura concepto");

        if (!precio || precio <= 0) {
            throw new Error("Precio inválido");
        }

        if (!CURRENT_REGISTRO_ID) {
            throw new Error("No hay registro activo");
        }

        // =========================
        // 📦 PAYLOAD CORRECTO
        // =========================
        const payload = {
            registro_id: CURRENT_REGISTRO_ID,
            concepto: concepto,
            cantidad: cantidad,
            precio_unitario: precio,
            tipo: 'Servicio',
            departamento: 'Manual'
        };


        console.log("📤 ENVIANDO CARGO:", payload);

        // =========================
        // 🚀 REQUEST
        // =========================
        const resp = await fetch(base_url + "registro-cargos/guardar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const data = await resp.json();

        console.log("✅ RESP CARGO:", data);

        if (!data.ok) {
            throw new Error(data.msg || "Error al guardar cargo");
        }

        // =========================
        // 🔄 RECARGAR
        // =========================
        await cargarConsumos(CURRENT_REGISTRO_ID);

        // =========================
        // 🧹 LIMPIAR
        // =========================
        c.value = '';
        p.value = '';
        q.value = 1;

        showToast("Cargo agregado correctamente");

    } catch (e) {

        console.error("❌ Error guardar cargo", e);
        showToast(e.message || "Error de conexión");

    }
}




function filterPOS() {
    const query = document.getElementById('pos-search').value.toLowerCase();
    const cards = document.querySelectorAll('#pos-grid-container .pos-item-card');
    cards.forEach(card => {
        const label = card.querySelector('.label').innerText.toLowerCase();
        if (label.includes(query)) card.style.display = 'flex'; else card.style.display = 'none';
    });
}

/* function applyCharge(concepto, depto, monto, qty) {
    const now = new Date();
    const fecha = now.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit' }) + " " + now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
    DATA.consumos.push({ id: Date.now(), fecha, concepto, depto, monto, qty, tipo: 'extra' });
    ui.set('last-charge-label', concepto);
    ui.set('last-charge-price', "$" + (monto * qty).toLocaleString());
    updateUI();
    if (!document.getElementById('tab-cargos').classList.contains('hidden')) renderCargosTerminal();
} */

async function applyCharge(concepto, depto, monto, qty) {

    try {

        const cantidad = Number(qty) || 1;
        let precio = Number(monto) || 0;

        // 🔥 limpiar si viene con formato tipo "1,000"
        if (typeof monto === 'string') {
            precio = parseFloat(monto.replace(/,/g, ''));
        }

        // =========================
        // 🔒 VALIDACIONES
        // =========================
        if (!concepto) throw new Error("Concepto requerido");

        if (isNaN(precio) || precio <= 0) {
            throw new Error("Monto inválido");
        }

        if (cantidad <= 0) {
            throw new Error("Cantidad inválida");
        }

        if (!CURRENT_REGISTRO_ID) {
            throw new Error("No hay registro activo");
        }

        // =========================
        // 📦 PAYLOAD CORRECTO
        // =========================
        const payload = {
            registro_id: CURRENT_REGISTRO_ID,
            concepto: concepto,
            cantidad: cantidad,
            precio_unitario: precio,
            tipo: 'Servicio',
            departamento: depto || 'Sistema'
        };

        console.log("📤 ENVIANDO CONSUMO:", payload);

        // =========================
        // 🚀 REQUEST
        // =========================
        const resp = await fetch(base_url + "registro-cargos/guardar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const data = await resp.json();

        console.log("✅ RESP CONSUMO:", data);

        if (!data.ok) {
            throw new Error(data.msg || "Error al guardar consumo");
        }

        // =========================
        // 🔄 RECARGAR
        // =========================
        await cargarConsumos(CURRENT_REGISTRO_ID);

        // =========================
        // 🧠 UI
        // =========================
        const total = precio * cantidad;

        ui.set('last-charge-label', concepto);
        ui.set(
            'last-charge-price',
            "$" + total.toLocaleString('es-MX', {
                minimumFractionDigits: 2
            })
        );

        updateUI();

        if (!document.getElementById('tab-cargos')?.classList.contains('hidden')) {
            renderCargosTerminal();
        }

    } catch (e) {

        console.error("❌ Error guardar consumo", e);
        showToast(e.message || "Error de conexión");

    }
}


// --- 6. SERVICIO A HABITACIÓN LÓGICA ---
function filterMenu(cat, el) {
    document.querySelectorAll('#room_service_categories .category-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    renderRoomServiceMenu(cat);
}

function renderRoomServiceMenu(filter = 'TODOS') {
    const container = document.getElementById('menu-container');
    if (!container) return;
    container.innerHTML = '';

    const filtered = filter === 'TODOS' ? DATA.roomServiceMenu : DATA.roomServiceMenu.filter(m => m.cat === filter);

    filtered.forEach(m => {
        container.innerHTML += `
                    <div class="menu-item-card fade-in" onclick="addToCart(${m.id})">
                        <div class="menu-item-img flex items-center justify-center bg-indigo-50 text-indigo-400">
                            <i class="fas ${m.icon} text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <span class="pms-label text-slate-400 text-[8px] mb-1">${m.cat}</span>
                            <h4 class="text-xs font-black text-slate-800 leading-tight">${m.label}</h4>
                            <p class="text-indigo-600 font-black text-sm mt-2">$${m.price.toLocaleString()}</p>
                        </div>
                        <div class="absolute right-4 bottom-4 w-6 h-6 bg-slate-900 text-white rounded-lg flex items-center justify-center text-[10px]">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                `;
    });
}

function addToCart(id) {
    const item = DATA.roomServiceMenu.find(m => m.id === id);
    const inCart = DATA.roomServiceCart.find(c => c.id === id);
    if (inCart) inCart.qty++;
    else DATA.roomServiceCart.push({ ...item, qty: 1 });
    renderCart();
}

function removeFromCart(id) {
    const idx = DATA.roomServiceCart.findIndex(c => c.id === id);
    if (idx !== -1) {
        if (DATA.roomServiceCart[idx].qty > 1) DATA.roomServiceCart[idx].qty--;
        else DATA.roomServiceCart.splice(idx, 1);
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    if (!container) return;

    if (DATA.roomServiceCart.length === 0) {
        container.innerHTML = '<p class="text-center text-slate-300 italic text-xs py-10">Agrega artículos para iniciar pedido</p>';
        ui.set('cart-subtotal', "$0.00");
        ui.set('cart-service', "$0.00");
        ui.set('cart-total', "$0.00");
        document.getElementById('btn-order').disabled = true;
        document.getElementById('btn-order').classList.add('opacity-30', 'cursor-not-allowed');
        return;
    }

    container.innerHTML = '';
    let subtotal = 0;
    DATA.roomServiceCart.forEach(c => {
        const totalItem = c.price * c.qty;
        subtotal += totalItem;
        container.innerHTML += `
                    <div class="flex items-center justify-between group">
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-800 leading-none">${c.label}</p>
                        
                            <p class="text-[9px] text-slate-400 font-bold mt-1">$${(c.price || 0).toLocaleString()} x ${c.qty || 0}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="removeFromCart(${c.id})" class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-all">
                                <i class="fas fa-minus text-[8px]"></i>
                            </button>
                            <span class="text-xs font-black text-slate-900 w-4 text-center">${c.qty}</span>
                            <button onclick="addToCart(${c.id})" class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-500 transition-all">
                                <i class="fas fa-plus text-[8px]"></i>
                            </button>
                        </div>
                    </div>
                `;
    });

    const service = subtotal * 0.10;
    const total = subtotal + service;

    ui.set('cart-subtotal', "$" + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    ui.set('cart-service', "$" + service.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    ui.set('cart-total', "$" + total.toLocaleString(undefined, { minimumFractionDigits: 2 }));

    document.getElementById('btn-order').disabled = false;
    document.getElementById('btn-order').classList.remove('opacity-30', 'cursor-not-allowed');
}

function placeRoomServiceOrder() {
    if (DATA.roomServiceCart.length === 0) return;

    const totalComanda = DATA.roomServiceCart.reduce((acc, c) => acc + (c.price * c.qty), 0) * 1.1;
    const resumen = DATA.roomServiceCart.map(c => `${c.qty}x ${c.label}`).join(", ");

    applyCharge("Servicio a habitación: " + resumen, "REST", totalComanda / 1.1, 1.1); // 1.1 qty simula el service charge

    DATA.roomServiceCart = [];
    renderCart();
    alert("✓ Pedido de servicio a habitación cargado al folio exitosamente.");
    switchTab('resumen', document.querySelector('.nav-link:nth-child(1)'));
}

function searchMenu() {
    const query = document.getElementById('menu-search').value.toLowerCase();
    const cards = document.querySelectorAll('#menu-container .menu-item-card');
    cards.forEach(card => {
        const label = card.querySelector('h4').innerText.toLowerCase();
        if (label.includes(query)) card.style.display = 'flex'; else card.style.display = 'none';
    });
}


function renderRoomsadmin() {
    const container = document.getElementById('roomContaineradmin');
    if (!container) return;
    container.innerHTML = '';
    const filtered = ROOMS.filter(r => r.floor === currentFloor && (currentType === 'Todos' || r.type === currentType));
    filtered.forEach(room => {
        const isSelected = selectedRoom && selectedRoom.id === room.id;
        const card = document.createElement('div');
        card.className = `room-card animate-fade-in ${isSelected ? 'selected-room' : ''} `;
        card.onclick = () => {
            selectRoomForMove(room.id);
        };
        let color = "text-indigo-500";
        let icon = "fa-bed";
        if (room.type === 'Suite') {
            color = "text-amber-500";
            icon = "fa-crown";
        }
        if (room.type === 'Doble') {
            color = "text-emerald-500";
            icon = "fa-users";
        }
        card.innerHTML = `
                    <span class="cap-tag">${room.capacity} Pax</span>
                    <div class="flex justify-between mb-0.5"><p class="text-[12px] font-black text-slate-400">#${room.id}</p></div>
                    <i class="fas ${icon} ${color} text-2xl my-3"></i>
                    <p class="text-[9px] font-black text-slate-600 uppercase leading-none">${room.type}</p>
                    <p class="text-[13px] font-black mt-2 text-slate-900">$${room.price.toLocaleString()}</p>`;
        container.appendChild(card);
    });
    ui.set('move-origin-room', DATA.currentRoom);
}

function switchFlooradmin(f) {
    currentFloor = f;
    document.querySelectorAll('.floor-btn').forEach(b => b.classList.toggle('active', b.innerText === f || (f ===
        'PB' && b.innerText === 'PB')));
    renderRoomsadmin();
}

function switchTypeadmin(t) {
    currentType = t;
    document.querySelectorAll('.type-btn').forEach(b => b.classList.toggle('active', b.innerText === t));
    renderRoomsadmin();
}





// --- 7. CAMBIO DE UNIDAD LÓGICA ---
function renderAvailableRooms() {
    const grid = document.getElementById('available-rooms-grid');
    if (!grid) return;
    grid.innerHTML = '';

    DATA.availableRooms.forEach(room => {
        const isSelected = DATA.selectedMoveRoom === room.num;
        grid.innerHTML += `
                    <div class="room-card ${isSelected ? 'selected' : ''} fade-in" onclick="selectRoomForMove('${room.num}')">
                        <div class="room-number">#${room.num}</div>
                        <div class="room-type">${room.type}</div>
                        <div class="room-price">$${room.price.toLocaleString()} / Noche</div>
                        <span class="room-status status-ready">${room.status}</span>
                    </div>
                `;
    });
    ui.set('move-origin-room', DATA.currentRoom);
}

function selectRoomForMove(num) {
    DATA.selectedMoveRoom = num;
    ui.set('move-dest-room', '#' + formatoHabitacion(num));

    const btn = document.getElementById('btn-confirm-move');
    btn.disabled = false;
    btn.classList.remove('opacity-30', 'cursor-not-allowed');

    renderAvailableRooms();
}

/* function executeRoomMove() {
    if (!DATA.selectedMoveRoom) return;

    const confirmMsg = `¿Confirmar traslado de la habitación ${DATA.currentRoom} a la #${DATA.selectedMoveRoom}?`;
    if (confirm(confirmMsg)) {
        DATA.currentRoom = '#' + DATA.selectedMoveRoom;

        // Actualizar UI Global
        ui.set('sidebar-room-number', DATA.currentRoom);
        ui.set('header-room-number', DATA.currentRoom);

        // Limpiar selección
        DATA.selectedMoveRoom = null;
        ui.set('move-dest-room', '---');
        document.getElementById('btn-confirm-move').disabled = true;
        document.getElementById('btn-confirm-move').classList.add('opacity-30', 'cursor-not-allowed');

        alert(`✓ Traslado exitoso a la unidad ${DATA.currentRoom}. Folio actualizado.`);
        switchTab('resumen', document.querySelector('.nav-link:nth-child(1)'));
    }
} */

function executeRoomMove(confirmado = false) {

    if (!DATA.selectedMoveRoom) return;

    const motivo = document.getElementById('move-motivo')?.value;

    if (!motivo) {
        alert("Selecciona un motivo");
        return;
    }

    const nuevaHabitacion = DATA.selectedMoveRoom;

    // =========================
    // 🚀 REQUEST
    // =========================
    fetch(base_url + "reservacion/cambiar-habitacion", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            registro_id: CURRENT_REGISTRO_ID,
            habitacion_id: nuevaHabitacion,
            motivo: motivo,
            confirmar_upgrade: confirmado // 🔥 nuevo
        })
    })
        .then(r => r.json())
        .then(resp => {

            // =========================
            // 💰 SI HAY UPGRADE
            // =========================
            if (resp.requiere_confirmacion) {

                const ok = confirm(
                    `La nueva habitación es más cara.\n` +
                    `Diferencia: $${Number(resp.diferencia).toLocaleString('es-MX')}\n\n` +
                    `¿Deseas continuar?`
                );

                if (ok) {
                    // 🔥 vuelve a ejecutar confirmando
                    executeRoomMove(true);
                }

                return;
            }

            // =========================
            // ❌ ERROR
            // =========================
            if (!resp.ok) {
                alert(resp.msg || "Error al cambiar habitación");
                return;
            }

            // =========================
            // ✅ ACTUALIZAR FRONT
            // =========================
            DATA.currentRoom = '#' + nuevaHabitacion;

            ui.set('sidebar-room-number', DATA.currentRoom);
            ui.set('header-room-number', DATA.currentRoom);

            // limpiar selección
            DATA.selectedMoveRoom = null;
            ui.set('move-dest-room', '---');

            const btn = document.getElementById('btn-confirm-move');
            btn.disabled = true;
            btn.classList.add('opacity-30', 'cursor-not-allowed');

            document.getElementById('move-motivo').value = '';

            alert(`✓ Traslado exitoso a la unidad ${DATA.currentRoom}`);

            switchTab('resumen', document.querySelector('.nav-link:nth-child(1)'));

        })
        .catch(e => {
            console.error("❌ Error mover habitación", e);
            alert("Error de conexión");
        });
}





// --- 8. PAGOS & ABONOS ---
function selectPaymentMethod(method, el) {

    DATA.currentPaymentMethod = method;
    document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
}

/* function processPayment() {
    const refInput = document.getElementById('payment-ref');
    const amountInput = document.getElementById('payment-amount');
    const amount = parseFloat(amountInput.value);
    if (!amount || amount <= 0) return;

    const now = new Date();
    const fechaFull = now.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit', year: '2-digit' }) + " " + now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });

    DATA.pagos_realizados.push({
        id: Date.now(),
        metodo: DATA.currentPaymentMethod,
        referencia: refInput.value || 'Sin ref.',
        monto: amount,
        fecha: fechaFull
    });

    refInput.value = '';
    amountInput.value = '';
    updateUI();
    renderPaymentsUI();
} */

function processPayment() {

    const amountInput = document.getElementById('payment-amount');
    const conceptoSelect = document.getElementById('payment-concept');
    const formaPagoSelect = document.getElementById('main_formaPagoadmin');

    const referenciaInput = document.getElementById('referencia_pago');
    const bancoInput = document.getElementById('banco_pago');

    const amount = parseFloat(amountInput.value);

    // =========================
    // 🔒 VALIDACIONES
    // =========================
    if (!amount || amount <= 0) {
        alert("Ingresa un monto válido");
        return;
    }

    if (!formaPagoSelect.value) {
        alert("Selecciona forma de pago");
        return;
    }

    // 🔥 validar referencia/banco si aplica
    if (['Tarjeta', 'Transferencia'].includes(DATA.currentPaymentMethod)) {

        const ref = referenciaInput?.value;
        const banco = bancoInput?.value;

        if (!ref || !banco) {
            alert("Referencia y banco son requeridos");
            return;
        }
    }

    // =========================
    // 📦 PAYLOAD
    // =========================
    const payload = {

        registro_id: CURRENT_REGISTRO_ID,

        forma_pago_id: formaPagoSelect.value,

        monto: amount,

        concepto: conceptoSelect.value || 'Pago',

        tipo: DATA.currentPaymentMethod,

        tipo_movimiento: 'PAGO',

        referencia_pago: referenciaInput?.value || null,

        banco: bancoInput?.value || null,

        estado: 'APLICADO',
        sistema: 'PMS'
    };

    console.log("📤 ENVIANDO PAGO:", payload);

    // =========================
    // 🚀 REQUEST
    // =========================
    fetch(base_url + "registropagos/guardar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(resp => {

            console.log("✅ RESP:", resp);

            if (!resp.ok) {
                alert(resp.msg || "Error al guardar");
                return;
            }

            // =========================
            // 🔄 RECARGAR DATOS
            // =========================
            cargarPagos(CURRENT_REGISTRO_ID);
            updateUI();
            renderCargosTerminal();
            renderPaymentsUI();

            // =========================
            // 🧹 LIMPIAR FORM
            // =========================
            amountInput.value = '';
            if (referenciaInput) referenciaInput.value = '';
            if (bancoInput) bancoInput.value = '';

        })
        .catch(e => {
            console.error("❌ Error guardar pago", e);
            alert("Error de conexión");
        });
}

// --- 9. ELIMINACIÓN CON MOTIVO Y REAJUSTE ---
function deletePayment(id) {
    DATA.pendingDelete = { id, type: 'pago' };
    ui.set('reajuste-hint', "REAJUSTE HACIA ARRIBA: Al eliminar este abono, el saldo por liquidar del huésped aumentará.");
    openMotivoModal();
}

function deleteCharge(id) {
    const idx = DATA.consumos.findIndex(c => String(c.id) === String(id));
    if (idx !== -1 && DATA.consumos[idx].tipo !== 'Hospedaje') {
        DATA.pendingDelete = { id, type: 'cargo' };
        ui.set('reajuste-hint', "REAJUSTE HACIA ABAJO: Al eliminar este cargo, el total acumulado y el saldo pendiente disminuirán.");
        openMotivoModal();
    }
}

function openMotivoModal() {
    document.getElementById('motivo-select').value = '';
    document.getElementById('motivo-input').value = '';
    document.getElementById('otro-motivo-container').classList.add('hidden');
    document.getElementById('modal-motivo').classList.add('active');
}

function toggleOtroMotivo() {
    const select = document.getElementById('motivo-select');
    const container = document.getElementById('otro-motivo-container');
    if (select.value === 'OTRO') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

/* function confirmDeletion() {
    const selectValue = document.getElementById('motivo-select').value;
    const inputValue = document.getElementById('motivo-input').value;

    let motivoFinal = selectValue;
    if (selectValue === 'OTRO') motivoFinal = inputValue;

    if (!motivoFinal || motivoFinal.trim().length < 5) {
        alert("Por favor, seleccione o escriba un motivo válido para la auditoría.");
        return;
    }

    // Guardar en LOG de auditoría
    DATA.audit_logs.push({
        fecha: new Date().toISOString(),
        tipo: DATA.pendingDelete.type,
        id_referencia: DATA.pendingDelete.id,
        motivo: motivoFinal
    });

    if (DATA.pendingDelete.type === 'cargo') {
        const idx = DATA.consumos.findIndex(c => c.id === DATA.pendingDelete.id);
        if (idx !== -1) DATA.consumos.splice(idx, 1);
    } else if (DATA.pendingDelete.type === 'pago') {
        const idx = DATA.pagos_realizados.findIndex(p => p.id === DATA.pendingDelete.id);
        if (idx !== -1) DATA.pagos_realizados.splice(idx, 1);
    }

    DATA.pendingDelete = null;
    closeModal('modal-motivo');

    // Reajuste automático mediante updateUI
    updateUI();
    renderCargosTerminal();
    renderPaymentsUI();

    console.log("LOG DE AUDITORÍA:", DATA.audit_logs);
} */

function confirmDeletion() {

    try {

        // =========================
        // 🔹 MOTIVO
        // =========================
        const selectValue = document.getElementById('motivo-select').value;
        const inputValue = document.getElementById('motivo-input').value;

        let motivoFinal = selectValue;

        if (selectValue === 'OTRO') {
            motivoFinal = inputValue;
        }

        if (!motivoFinal || motivoFinal.trim().length < 5) {
            alert("Por favor, capture un motivo válido (mínimo 5 caracteres).");
            return;
        }

        // =========================
        // 🔹 VALIDAR CONTEXTO
        // =========================
        if (!DATA.pendingDelete) {
            console.warn("⚠️ No hay acción pendiente");
            return;
        }

        const now = new Date().toISOString();

        const id = Number(DATA.pendingDelete.id);
        const tipo = DATA.pendingDelete.type;

        console.log("🧠 Acción:", tipo, "ID:", id);

        // =========================
        // 🧾 LOG AUDITORÍA
        // =========================
        const log = {
            fecha: now,
            tipo: tipo,
            id_referencia: id,
            motivo: motivoFinal
        };

        DATA.audit_logs.push(log);

        console.log("🧾 LOG:", log);

        // =========================
        // 🔥 CANCELACIÓN LOCAL
        // =========================
        if (tipo === 'cargo') {

            const item = DATA.consumos.find(c => Number(c.id) === id);

            if (item) {
                item.estado = 'CANCELADO';
            } else {
                console.warn("⚠️ Cargo no encontrado:", id);
            }

        } else if (tipo === 'pago') {

            const item = DATA.pagos_realizados.find(p => Number(p.id) === id);
            if (item) item.estado = 'CANCELADO';

            const item2 = DATA.pagos.find(p => Number(p.id) === id);
            if (item2) item2.estado = 'CANCELADO';

        } else if (tipo === 'estadia') {

            // 🔥 marcar todo
            DATA.estado_registro = 'CANCELADO';

            if (Array.isArray(DATA.consumos)) {
                DATA.consumos.forEach(c => c.estado = 'CANCELADO');
            }

            if (Array.isArray(DATA.pagos_realizados)) {
                DATA.pagos_realizados.forEach(p => p.estado = 'CANCELADO');
            }

            if (Array.isArray(DATA.pagos)) {
                DATA.pagos.forEach(p => p.estado = 'CANCELADO');
            }
        }

        // =========================
        // 🚀 BACKEND
        // =========================
        let endpoint = '';

        if (tipo === 'cargo') {
            endpoint = "registro-cargos/cancelar";
        } else if (tipo === 'pago') {
            endpoint = "registropagos/cancelar";
        } else if (tipo === 'estadia') {
            endpoint = "registro/cancelar";
        }

        if (!endpoint) {
            throw new Error("Tipo de operación no válido");
        }

        fetch(base_url + endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id,
                motivo: motivoFinal
            })
        })
            .then(r => r.json())
            .then(resp => {

                console.log("✅ Backend OK:", resp);

                if (!resp.ok) {
                    throw new Error(resp.msg || "Error en backend");
                }

                // =========================
                // 🔄 RECARGAR DATA
                // =========================
                if (tipo === 'estadia') {

                    showToast("Estadía cancelada correctamente");

                    // opcional: redirigir
                    setTimeout(() => {
                        location.reload();
                    }, 800);

                } else {

                    cargarConsumos(CURRENT_REGISTRO_ID);
                    cargarPagos(CURRENT_REGISTRO_ID);

                    updateUI();
                    renderCargosTerminal();
                    renderPaymentsUI();

                    showToast("Reajuste aplicado correctamente");
                }

            })
            .catch(e => {

                console.error("❌ Error backend:", e);
                alert(e.message || "Error en operación");

            });

        // =========================
        // 🧹 LIMPIEZA UI
        // =========================
        DATA.pendingDelete = null;

        closeModal('modal-motivo');

    } catch (e) {

        console.error("❌ Error confirmDeletion:", e);
        alert(e.message || "Error inesperado");

    }
}

function renderPaymentsUI() {
    const tbody = document.getElementById('payment-history-table-body');
    if (!tbody) return;
    tbody.innerHTML = '';
    if (DATA.pagos_realizados.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-10 text-slate-300 italic text-xs">No hay pagos registrados</td></tr>';
        return;
    }
    [...DATA.pagos_realizados].reverse().forEach(p => {
        let icon = p.metodo === 'Tarjeta' ? 'fa-credit-card' : p.metodo === 'Transferencia' ? 'fa-university' : 'fa-money-bill-wave';
        tbody.innerHTML += `
                    <tr class="fade-in hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-mono text-[10px]">${p.fecha}</td>
                        <td class="py-4 text-xs font-bold text-slate-700 uppercase"><i class="fas ${icon} mr-2"></i>${p.metodo}</td>
                        <td class="py-4 text-xs text-slate-500 font-medium">${p.referencia}</td>
                        <td class="text-right py-4 font-black text-emerald-600 text-sm">$${p.monto.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                        <td class="text-center px-6 py-4">
                            <button onclick="deletePayment(${p.id})" class="text-red-300 hover:text-red-500 transition-all p-2"><i class="fas fa-trash-alt text-[10px]"></i></button>
                        </td>
                    </tr>
                `;
    });
    const totalPagado = DATA.pagos_realizados.reduce((acc, p) => acc + p.monto, 0);
    ui.set('total-payments-label', "$" + totalPagado.toLocaleString(undefined, { minimumFractionDigits: 2 }));
}

function updateUI() {

    /*   // 🔒 VALIDACIÓN GLOBAL
      if (!window.DATA || typeof DATA !== "object") {
          console.warn("updateUI: DATA no disponible");
          return;
      }
   */
    // 🔒 defaults seguros
    const titular = DATA.titular || {};
    const acompanantes = DATA.acompanantes || [];
    const consumos = DATA.consumos || [];
    const pagos = DATA.pagos_realizados || [];

    // =========================
    // TITULAR
    // =========================
    ui.set('name-titular', (titular.nombre || "") + " " + (titular.apellido || ""));
    ui.set('id-titular', titular.id ? "ID: " + titular.id : "");

    if (titular.fotografia) {
        ui.attr('img-titular', 'src', titular.fotografia);
        ui.attr('audit-avatar', 'src', titular.fotografia);
    }

    ui.set('audit-name', titular.nombre || "");

    // =========================
    // CONSUMOS
    // =========================
    let hosp = 0, extras = 0;

    consumos.forEach(c => {

        const sub = (c.monto || 0) * (c.qty || 1);

        if (c.tipo === 'Hospedaje' || c.tipo === 'Persona Extra') {
            hosp += sub;
        } else {
            extras += sub;
        }

    });
    // =========================
    // PAGOS
    // =========================
    const totalPagos = pagos.reduce((acc, p) => acc + (p.monto || 0), 0);
    const taxes = (hosp + extras) * 0.16;
    const ish = (hosp) * 0.03;
    const total = hosp + extras + taxes + ish;
    const saldo = total - totalPagos;

    // =========================
    // UI NUMÉRICA
    // =========================
    ui.set('stat-hospedaje', "$" + hosp.toLocaleString());
    ui.set('stat-extras', "$" + extras.toLocaleString());
    ui.set('stat-sub', "$" + (hosp + extras).toLocaleString());
    ui.set('stat-taxes', "$" + taxes.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('stat-ish', "$" + ish.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('stat-payments', "-$" + totalPagos.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('total-folio', "$" + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('saldo-neto', "$" + (saldo < 0 ? 0 : saldo).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('terminal-session-total', "$" + extras.toLocaleString());

    // =========================
    // PROGRESS BAR
    // =========================
    const percent = total > 0 ? (totalPagos / total * 100) : 0;

    ui.style('payment-bar', 'width', Math.min(percent, 100) + "%");
    ui.set('payment-percent', Math.round(percent) + "%");

    // =========================
    // TABLA RESUMEN
    // =========================
    const resTbody = document.getElementById('table-resumen-body');

    if (resTbody) {
        resTbody.innerHTML = '';

        consumos.slice(-4).reverse().forEach(c => {

            const monto = (c.monto || 0) * (c.qty || 1);

            resTbody.innerHTML += `
                <tr class="fade-in">
                    <td class="px-6 py-2 text-slate-400 font-mono text-[10px]">${c.fecha || ""}</td>
                    <td class="py-2 font-bold text-slate-700 text-xs">${c.concepto || ""}</td>
                    <td class="py-2">
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg text-[9px] font-black uppercase">
                            ${c.depto || ""}
                        </span>
                    </td>
                    <td class="py-2 font-bold text-slate-700 text-xs">${c.estado || ""}</td>
                    <td class="text-right px-6 py-2 font-black text-indigo-600 text-xs">
                        $${monto.toLocaleString()}
                    </td>
                </tr>
            `;
        });
    }

    // =========================
    // TIME
    // =========================
    ui.set('sync-time', new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    }));

    console.log("✔ updateUI ejecutado correctamente");
}

function renderCargosTerminal() {
    const body = document.getElementById('timeline-charges-container');
    if (!body) return;
    body.innerHTML = '';
    const deptIcons = {
        'REST': { icon: 'fa-utensils', bg: 'bg-orange-50', color: 'text-orange-600' },
        'MINI': { icon: 'fa-tint', bg: 'bg-blue-50', color: 'text-blue-600' },
        'AMA': { icon: 'fa-tshirt', bg: 'bg-emerald-50', color: 'text-emerald-600' },
        'REC': { icon: 'fa-car', bg: 'bg-purple-50', color: 'text-purple-600' },
        'SPA': { icon: 'fa-spa', bg: 'bg-pink-50', color: 'text-pink-600' },
        'MANUAL': { icon: 'fa-edit', bg: 'bg-slate-50', color: 'text-slate-600' },
        'SISTEMA': { icon: 'fa-server', bg: 'bg-slate-900', color: 'text-white' }
    };
    [...DATA.consumos].reverse().forEach(c => {
        const info = deptIcons[c.depto] || { icon: 'fa-receipt', bg: 'bg-slate-50', color: 'text-slate-500' };
        body.innerHTML += `<div class="history-row fade-in"><span class="text-[10px] font-mono text-slate-400">${c.fecha.split(' ')[1]}</span><div class="history-icon ${info.bg} ${info.color} shadow-sm"><i class="fas ${info.icon}"></i></div><div class="px-4"><p class="font-bold text-xs text-slate-700 leading-none">${c.concepto}</p><p class="text-[9px] text-slate-400 font-bold uppercase mt-1">${c.depto}</p></div><span class="font-black text-[10px] text-center text-slate-400">x${c.qty}</span><span class="text-right font-black text-indigo-600 text-sm">$${(c.monto * c.qty).toLocaleString()}</span><div class="flex justify-end">${c.tipo !== 'Hospedaje' ? `<button onclick="deleteCharge(${c.id})" class="w-7 h-7 rounded-lg text-red-300 hover:text-red-500 hover:bg-red-50 transition-all"><i class="fas fa-undo-alt text-xs"></i></button>` : '<i class="fas fa-lock text-slate-200 text-[10px]"></i>'}</div></div>`;
    });
}

function saveTitular() {
    DATA.titular.nombre = document.getElementById('edit-nombre').value;
    DATA.titular.apellido = document.getElementById('edit-apellido').value;
    DATA.titular.id = document.getElementById('edit-id').value;
    updateUI(); closeModal('modal-titular');
}

window.onload = updateUI;


// --- MODAL WRAPPERS (Fixing the ReferenceErrors) ---
// --- MODAL WRAPPERS (Fixing the ReferenceErrors) ---


// --- VEHICLES ---
function renderVehicleOwners() {
    const sel = document.getElementById('vehicle-owner');
    sel.innerHTML = `<option value="${DATA.titular.id_Huesped}">${DATA.titular.nombre} (Titular)</option>`;
    DATA.acompanantes.forEach(c => {
        sel.innerHTML += `<option value="${c.id}">${c.nombre} (Acomp.)</option>`;
    });
}

async function registerVehicle() {

    try {

        // =========================
        // 📥 INPUTS
        // =========================
        const platesInput = document.getElementById('vehicle-plates');
        const modelInput = document.getElementById('vehicle-model');
        const ownerInput = document.getElementById('vehicle-owner');

        const texto = ownerInput.options[ownerInput.selectedIndex].text;
        const tipo = texto.includes('(Titular)') ? 'TITULAR' : 'ACOMPANANTE';





        const plates = platesInput.value.trim().toUpperCase();
        const model = modelInput.value.trim();
        const owner = ownerInput.value;

        // =========================
        // 🚨 VALIDACIONES
        // =========================
        const errores = [];

        if (!plates) errores.push("Placas son obligatorias");
        if (!model) errores.push("Modelo es obligatorio");

        // formato básico placas (MX flexible)
        if (plates && plates.length < 5) {
            errores.push("Formato de placas inválido");
        }

        if (errores.length > 0) {
            alert("⚠️ Validación:\n\n" + errores.join("\n"));
            return;
        }

        // =========================
        // 🧠 PARSEO MODELO / COLOR
        // =========================
        let modelo = model;
        let color = '';

        const partes = model.split(' ');
        if (partes.length > 1) {
            color = partes.pop(); // último valor
            modelo = partes.join(' ');
        }

        // =========================
        // 📦 PAYLOAD
        // =========================
        const payload = {
            registro_id: CURRENT_REGISTRO_ID,
            tipo: tipo,
            placa: plates,
            modelo: modelo,
            color: color,
            registro_acompanante_id: owner || null,
            tipo_vehiculo: "AUTO",
            numero_cajon: null,
            cargo: 0,
            hora_entrada: new Date().toISOString().slice(0, 19).replace('T', ' ')
        };

        console.log("🚗 Enviando vehículo:", payload);

        // =========================
        // 🌐 API
        // =========================
        const res = await fetch(base_url + "reservacion/registrar_vehiculos", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const resp = await res.json();

        if (!resp.ok) {
            alert(resp.msg || "Error al registrar vehículo");
            return;
        }

        // =========================
        // 🔄 UI
        // =========================
        platesInput.value = '';
        modelInput.value = '';
        ownerInput.value = '';

        renderVehiclesList?.(); // 🔥 recargar desde BD

        alert("Vehículo registrado correctamente");

    } catch (e) {
        console.error("❌ Error:", e);
        alert("Error inesperado");
    }

    renderVehiclesList();
}

function renderVehiclesList() {

    const box = document.getElementById('vehicles-list');

    if (!DATA.vehiculos || DATA.vehiculos.length === 0) {
        box.innerHTML = `
            <div class="text-center py-12 text-slate-400 italic text-xs">
                No hay vehículos registrados
            </div>`;
        return;
    }

    box.innerHTML = '';

    DATA.vehiculos.forEach(v => {

        const owner = v.propietario || 'Sin propietario';

        // 🔥 TIPO VISUAL
        const isTitular = v.tipo === 'TITULAR';

        const badgeTipo = isTitular
            ? `<span class="px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[8px] font-black uppercase">👤 Titular</span>`
            : `<span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[8px] font-black uppercase">👥 Acomp.</span>`;

        box.innerHTML += `
        <div class="group bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between hover:shadow-md transition-all">

            <!-- IZQUIERDA -->
            <div class="flex items-center gap-4">

                <!-- ICONO -->
                <div class="w-12 h-12 rounded-2xl ${isTitular ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600'} flex items-center justify-center">
                    <i class="fas fa-car-side text-lg"></i>
                </div>

                <!-- INFO -->
                <div class="flex flex-col">

                    <!-- PLACA -->
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-lg bg-slate-900 text-white font-black text-[10px] tracking-widest">
                            ${v.placa}
                        </span>

                        ${v.tipo_vehiculo ? `
                            <span class="text-[8px] font-bold text-slate-400 uppercase">
                                ${v.tipo_vehiculo}
                            </span>` : ''}

                        ${badgeTipo}
                    </div>

                    <!-- MODELO -->
                    <p class="text-xs font-black text-slate-900 mt-1">
                        ${v.modelo} ${v.color ? `<span class="text-slate-400">• ${v.color}</span>` : ''}
                    </p>

                    <!-- OWNER -->
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                        Propietario: 
                        <span class="${isTitular ? 'text-indigo-600' : 'text-amber-600'} font-black">
                            ${owner}
                        </span>
                    </p>

                </div>

            </div>

            <!-- DERECHA -->
            <div class="flex items-center gap-2">

                <!-- STATUS -->
                <div class="hidden md:flex items-center gap-1 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    ACTIVO
                </div>

                <!-- DELETE -->
                <button onclick="deleteVehicle(${v.id})"
                    class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-trash text-sm"></i>
                </button>

            </div>

        </div>`;
    });
}


async function deleteVehicle(id) {

    try {

        if (!confirm("¿Eliminar vehículo del registro?")) return;

        const res = await fetch(base_url + "reservacion/eliminar_vehiculo/" + id, {
            method: "POST"
        });

        const resp = await res.json();

        if (!resp.ok) {
            alert(resp.msg || "Error al eliminar vehículo");
            return;
        }

        // 🔥 refrescar desde BD (NO manipular local)
        cargarVehiculos();
        renderVehicleCard();

    } catch (e) {
        console.error("❌ Error eliminar vehículo:", e);
        alert("Error inesperado");
    }
}

// --- CHARGES & PAYMENTS ---
/*  function quickAdd(l, d, p) {
     DATA.consumos.push({ id: Date.now(), fecha: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), concepto: l, depto: d, monto: p, qty: 1, tipo: 'extra' });
     updateUI();
 } */

/*     function processPayment() {
        const amt = parseFloat(document.getElementById('payment-amount').value);
        if (isNaN(amt) || amt <= 0) return;
        DATA.pagos_realizados.push({ id: Date.now(), fecha: new Date().toLocaleDateString(), metodo: 'Efectivo', monto: amt });
        document.getElementById('payment-amount').value = '';
        updateUI();
        renderPaymentsUI();
    } */

/*  function renderPaymentsUI() {
     const tbody = document.getElementById('payment-history-table-body');
     tbody.innerHTML = '';
     [...DATA.pagos_realizados].reverse().forEach(p => {
         tbody.innerHTML += `<tr><td class="px-6 py-4 font-mono text-[10px]">${p.fecha}</td><td>${p.metodo}</td><td class="text-right font-black text-emerald-600">$${p.monto.toLocaleString()}</td></tr>`;
     });
 }
*/
// --- AUDIT & UI REFRESH ---
function calculateTotals() {
    let h = 0, e = 0;
    DATA.consumos.forEach(c => { if (c.tipo === 'Hospedaje') h += (c.monto * c.qty); else e += (c.monto * c.qty); });
    const p = DATA.pagos_realizados.reduce((acc, pay) => acc + pay.monto, 0);
    const taxes = (h + e) * 0.19;
    const bruto = h + e + taxes;
    return { bruto, saldo: bruto - p, taxes, h, e, p };
}


// --- MODIFY STAY ---
function initModifyModal() {
    document.getElementById('modify-arrival').value = DATA.stay.arrival;
    document.getElementById('modify-departure').value = DATA.stay.departure;
    document.getElementById('modify-rate').value = DATA.stay.dailyRate;
    updateNightsCalc();

    document.getElementById('modify-departure').onchange = updateNightsCalc;
    document.getElementById('modify-rate').onkeyup = updateNightsCalc;
}

function updateNightsCalc() {
    const arr = new Date(document.getElementById('modify-arrival').value);
    const dep = new Date(document.getElementById('modify-departure').value);
    const diff = Math.ceil((dep - arr) / (1000 * 60 * 60 * 24));
    ui.set('modify-nights-calc', Math.max(1, diff));
}

async function saveStayModification() {

    try {

        const registroId = CURRENT_REGISTRO_ID; // asegúrate que este exista en tu flujo

        const fechaEntrada = document.getElementById('modify-arrival').value;
        const fechaSalida = document.getElementById('modify-departure').value;

        if (!fechaSalida) {
            alert("La fecha de salida es obligatoria");
            return;
        }

        if (!registroId) {
            alert("No se encontró el ID del registro");
            return;
        }

        // =========================
        // 🔥 PAYLOAD MÍNIMO
        // =========================
        const payload = {
            registro_id: registroId,
            fecha_entrada: fechaEntrada, // opcional si decides usarlo
            fecha_salida: fechaSalida
        };

        console.log("📤 Enviando modificación:", payload);

        const res = await fetch(base_url + "reservacion/modificar-estadia", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const resp = await res.json();

        console.log("📥 Respuesta:", resp);

        if (resp.ok) {

            alert("Estadía actualizada correctamente");

            // 🔥 refrescar UI

            renderPaxGrid?.();

            toggleModal('modal-modify-stay', false);

        } else {
            alert(resp.msg || "Error al actualizar estadía");
        }

    } catch (e) {
        console.error("❌ Error modificando estadía:", e);
        alert("Error inesperado");
    }
}




// --- REPORTE DE FOLIO (PDF) ---
function updateFolioReportUI() {
    const t = calculateTotals();
    ui.set('folio-guest-name', DATA.titular.nombre + " " + DATA.titular.apellido);
    ui.set('folio-guest-id', "ID: " + DATA.titular.id);
    ui.set('folio-dates-range', formatDateMX(DATA.stay.arrival) + " - " + formatDateMX(DATA.stay.departure));
    ui.set('folio-room-type', `${DATA.stay.roomType} (${DATA.stay.room})`);
    ui.set('folio-print-date', new Date().toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }));

    const menores = DATA.acompanantes.filter(c => c.esMenor).length;
    const adultos = DATA.acompanantes.filter(c => !c.esMenor).length;

    const totalPax = 1 + DATA.acompanantes.length;

    ui.set('folio-occupancy-summary', `${totalPax} PAX (${1 + adultos} AD, ${menores} NI)`);

    const cBody = document.getElementById('folio-charges-body');
    cBody.innerHTML = '';
    DATA.consumos.forEach(c => {
        cBody.innerHTML += `<tr><td class="py-4 px-6 text-slate-400 font-mono text-[10px]">${c.fecha}</td><td class="py-4 px-6 font-bold text-slate-700">${c.concepto}</td><td class="py-4 px-6 uppercase text-[9px] font-black text-slate-400">${c.depto}</td><td class="py-4 px-6 text-right font-black">$${(c.monto * c.qty).toLocaleString()}</td></tr>`;
    });

    const pBody = document.getElementById('folio-payments-body');
    pBody.innerHTML = '';
    DATA.pagos.forEach(p => {
        pBody.innerHTML += `<tr><td class="py-4 px-6 text-slate-400 font-mono text-[10px]">${p.fecha}</td><td class="py-4 px-6 font-bold text-slate-700">${p.concepto}</td><td class="py-4 px-6 text-right font-black text-emerald-600">-$${p.monto.toLocaleString()}</td></tr>`;
    });

    // ui.set('folio-subtotal', "$" + t.sub.toLocaleString(undefined, {minimumFractionDigits: 2}));

    ui.set('folio-subtotal', "$" + (Number(t?.subtotal) || 0).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('folio-taxes', "$" + t.taxes.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('folio-ish', "$" + t.taxes_ish.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('folio-total', "$" + t.total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('folio-paid', "-$" + t.p.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    ui.set('folio-balance', "$" + Math.max(0, t.saldo).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

    document.getElementById('paid-stamp-visual').style.display = (t.saldo <= 1) ? 'block' : 'none';
}


/**
         * Realiza los cálculos financieros del folio incluyendo IVA e ISH.
         */
function calculateTotals() {
    let h = 0, e = 0;
    // Separar cargos de hospedaje de cargos extras (Consumos)
    DATA.consumos.forEach(c => {
        if (c.tipo === 'Hospedaje') h += (c.monto * c.qty);
        else e += (c.monto * c.qty);
    });
    const p = DATA.pagos.reduce((acc, pay) => acc + pay.monto, 0);
    const subtotal = (h + e);
    const taxes = subtotal * 0.16; // IVA 16% + ISH 3%
    const taxes_ish = h * 0.03; // IVA 16% + ISH 3%
    const total = subtotal + taxes + taxes_ish;

    return { subtotal, h, e, taxes, taxes_ish, total, p, saldo: total - p };
}


function formatDateMX(dateStr) {
    const date = new Date(dateStr);

    const formatted = date.toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    // capitalizar mes
    return formatted.replace(/(\d{2}) (\w+)/, (match, d, m) => {
        return `${d} ${m.charAt(0).toUpperCase() + m.slice(1)}`;
    });
}


function cargarFormasPagoadmin(tipo = 'Efectivo') {

    fetch(base_url + "catalogos/formas-pago?tipo=" + tipo)
        .then(r => r.json())
        .then(data => {

            const select = document.getElementById("main_formaPagoadmin");

            select.innerHTML = `<option value="">Seleccione</option>`;

            data.forEach(row => {
                select.innerHTML += `
                <option value="${row.id}">
                    ${row.label}
                </option>
            `;
            });

        })
        .catch(e => console.error(e));


    const payCont = document.getElementById('payment_details_containeradmin');

    if (tipo !== 'Efectivo') {
        if (payCont.children.length === 0) {
            payCont.innerHTML = `
            <div class="col-span-1">
                <label class="pms-label">No. Referencia / Voucher</label>
                <input id="referencia_pago" type="text" class="pms-input" placeholder="000000" required>
            </div>
            <div class="col-span-1">
                <label class="pms-label">Institución Bancaria</label>
                <input id="banco_pago" type="text" class="pms-input" placeholder="Ej. BBVA" required>
            </div>
        `;
        }
    } else {
        payCont.innerHTML = '';
    }


}


function handlePaymentMethod(method, el) {
    selectPaymentMethod(method, el);
    cargarFormasPagoadmin(method);
}

function closeModal(id) {
    if (id === 'modal-titular') stopCamera();
    document.getElementById(id).classList.remove('active');
}



function cargarServiciosPOS() {

    fetch(base_url + "roomservice/servicios")
        .then(r => r.json())
        .then(resp => {

            const data = resp.data || [];

            const cont = document.getElementById("pos-servicios");
            if (!cont) return;

            cont.innerHTML = '';

            data.forEach(item => {

                cont.innerHTML += `
                    <div class="pos-item-card"
                        onclick="quickAdd('${item.nombre}', '${item.categoria}', ${item.precio})">
                        <i class="fas ${item.icono || 'fa-box'}"></i>
                        <div>
                            <span class="label">${item.nombre}</span>
                            <span class="price">$${Number(item.precio).toLocaleString('es-MX')}</span>
                        </div>
                    </div>

                `;
            });

        })
        .catch(e => console.error("❌ Error cargar servicios", e));
}



function buscarProductosPOS(q) {

    // 🔥 SI ESTÁ VACÍO → volver a modo POS inicial
    if (!q || q.trim().length === 0) {
        cargarServiciosPOS();
        return;
    }

    fetch(base_url + "roomservice/buscar?q=" + encodeURIComponent(q))
        .then(r => r.json())
        .then(resp => {

            const cont = document.getElementById("pos-servicios");
            cont.innerHTML = '';

            (resp.data || []).forEach(item => {

                cont.innerHTML += `
                    <div class="pos-item-card"
                        onclick="quickAdd('${item.nombre}', '${item.categoria}', ${item.precio})">

                        <i class="fas ${item.icono || 'fa-box'}"></i>
                        <span class="label">${item.nombre}</span>
                        <span class="price">$${Number(item.precio).toLocaleString('es-MX')}</span>

                    </div>
                `;
            });

        });
}


// --- 8. FLUJO DE CHECK-OUT (Cierre de folio) ---
async function handleCheckOutFlow() {

    try {



        const t = calculateTotals() || {};

        // =========================
        // 🔢 NORMALIZAR VALORES
        // =========================
        const bruto = Number(t.bruto ?? 0);
        const saldo = Number(t.saldo ?? 0);

        // =========================
        // 🚨 VALIDAR SALDO
        // =========================
        if (saldo > 0.01) {
            alert(`IMPOSIBLE CERRAR FOLIO: Saldo pendiente de $${saldo.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`);
            switchTab('pagos', document.querySelector('.nav-link:nth-child(5)'));
            return false;
        }

        showLoader("Guardando información...");

        // =========================
        // 🔄 BACKEND
        // =========================
        const resp = await fetch(base_url + "reservacion/cerrar-folio", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                registro_id: CURRENT_REGISTRO_ID
            })
        });

        // 🔥 VALIDAR HTTP
        if (!resp.ok) {
            const text = await resp.text();
            console.error("Respuesta servidor:", text);
            throw new Error(`Error HTTP ${resp.status}`);
        }

        // 🔥 VALIDAR JSON
        let data;
        try {
            data = await resp.json();
        } catch {
            throw new Error("Respuesta inválida del servidor");
        }

        if (!data || data.ok !== true) {
            throw new Error(data?.msg || "Error al cerrar folio");
        }

        // =========================
        // 🧠 UI (ANTES DE LIMPIAR)
        // =========================
        ui.set('co-total-bruto', '$' + bruto.toLocaleString('es-MX', { minimumFractionDigits: 2 }));
        ui.set('co-saldo-pendiente', '$0.00');

        // =========================
        // 🔔 MENSAJE FINAL
        // =========================
        showToast("Check-out cerrado correctamente");

        // =========================
        // ❌ CERRAR MODALES
        // =========================
        const modalCheckout = document.getElementById('modal-checkout');
        const modalPrincipal = document.getElementById('master-modal-container');

        if (modalCheckout) modalCheckout.classList.remove('active');
        if (modalPrincipal) modalPrincipal.classList.remove('show');

        // =========================
        // 🔄 REFRESH UI
        // =========================
        if (typeof cargarTrabajo === 'function') {
            cargarTrabajo();
        }

        if (typeof updateUI === 'function') {
            updateUI();
        }

        // =========================
        // 🧹 LIMPIEZA DE ESTADO
        // =========================
        CURRENT_REGISTRO_ID = null;
        selectedRoom = null;

        hideLoader();

        closeModalAdmin();

        return true;

    } catch (e) {

        console.error("❌ Error en check-out", e);

        showToast(e.message || "Error al cerrar folio");

        return false;
    }
}

function applyRegistroState() {

    const estado = DATA.estado_registro;

    const btnCheckout = document.getElementById('btn-checkout');
    const btnCancelar = document.getElementById('btn-cancelar');

    if (estado === 'CHECKOUT') {

        console.log("🔒 MODO CHECKOUT ACTIVADO");

        // =========================
        // 🔒 BLOQUEAR BOTONES
        // =========================
        [btnCheckout, btnCancelar].forEach(btn => {
            if (!btn) return;

            btn.disabled = true;

            // 🔥 bloquear click real
            btn.style.pointerEvents = 'none';

            // 🔥 visual
            btn.classList.add('opacity-40', 'cursor-not-allowed');

            // 🔥 quitar onclick (blindaje total)
            btn.onclick = null;

        });




        switchTab('bitacora', this);
        updateUI();
        updateFolioReportUI();


    } else {

        [btnCheckout, btnCancelar].forEach(btn => {
            if (!btn) return;

            btn.disabled = false;
            btn.style.pointerEvents = '';
            btn.classList.remove('opacity-40', 'cursor-not-allowed');
        });
    }
}


function closeModalAdmin() {

    const modal = document.getElementById("admin-modal-container");

    if (!modal) return;

    modal.classList.remove("show");

    // 🔥 limpiar scroll
    document.body.classList.remove('overflow-hidden');

    console.log("✅ Modal cerrado correctamente");
}




/* async function startCameraEdit() {

    const video = document.getElementById('camera-stream-edit');
    const preview = document.getElementById('edit-preview-foto');
    const btnTake = document.getElementById('btn-take-photo-edit');

    try {

        // 🔥 obtener lista de dispositivos primero
        const devices = await navigator.mediaDevices.enumerateDevices();

        const cameras = devices.filter(d => d.kind === 'videoinput');

        if (!cameras.length) {
            alert("No hay cámaras disponibles");
            return;
        }

        console.log("📷 Cámaras detectadas:", cameras);

        // 🔥 usar la primera cámara disponible (SIN facingMode)
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                deviceId: cameras[0].deviceId
            },
            audio: false
        });

        CAMERA_STREAM_EDIT = stream;

        video.srcObject = stream;
        await video.play();

        video.classList.remove('hidden');
        preview.classList.add('hidden');
        btnTake.classList.remove('hidden');

    } catch (e) {

        console.error("❌ Error cámara FULL:", e.name, e.message, e);

        alert(`
Error cámara:
${e.name}

Posibles causas:
- Cámara en uso (Zoom, Teams)
- Electron sin permisos
- Dispositivo bloqueado
        `);
    }
}

async function takePhotoEdit() {

    const video = document.getElementById('camera-stream-edit');
    const preview = document.getElementById('edit-preview-foto');
    const canvas = document.getElementById('photo-canvas-edit');

    if (video.readyState < 2) {
        await new Promise(r => video.onloadeddata = r);
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0);

    const base64 = canvas.toDataURL("image/png");

    // preview inmediato
    preview.src = base64;
    preview.classList.remove('hidden');
    video.classList.add('hidden');

    // 🔥 subir foto (usa tu función existente)
    const fileName = await subirFoto(base64);

    if (fileName) {
        preview.dataset.file = fileName;
    } else {
        // fallback
        preview.dataset.file = base64;
    }

    stopCameraEdit();
}

function stopCameraEdit() {

    if (CAMERA_STREAM_EDIT) {
        CAMERA_STREAM_EDIT.getTracks().forEach(t => t.stop());
        CAMERA_STREAM_EDIT = null;
    }

    const video = document.getElementById('camera-stream-edit');
    if (video) {
        video.srcObject = null;
        video.classList.add('hidden');
    }

    console.log("📷 Cámara edit detenida");
} */


function renderPaxSummary() {

    const data = DATA.acompanantes || [];

    let adultos = 0;
    let ninos = 0;

    data.forEach(p => {
        if (parseInt(p.es_menor) === 1) ninos++;
        else adultos++;
    });

    // =========================
    // 🔹 BADGES
    // =========================
    const badges = document.getElementById('pax-badges');
    if (badges) {

        let html = '';

        if (adultos > 0) {
            html += `
                <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 font-black text-[10px]">
                    AD
                </div>
            `;
        }

        if (ninos > 0) {
            html += `
                <div class="w-8 h-8 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-emerald-600 font-black text-[10px]">
                    NI
                </div>
            `;
        }

        badges.innerHTML = html;
    }

    // =========================
    // 🔹 TEXTO
    // =========================
    const summary = document.getElementById('pax-summary');

    if (summary) {

        const txtAdultos = adultos + (adultos === 1 ? " Adulto" : " Adultos");
        const txtNinos = ninos + (ninos === 1 ? " Niño" : " Niños");

        let texto = '';

        if (adultos > 0 && ninos > 0) {
            texto = `${txtAdultos}, ${txtNinos}`;
        } else if (adultos > 0) {
            texto = txtAdultos;
        } else if (ninos > 0) {
            texto = txtNinos;
        } else {
            texto = "Sin acompañantes";
        }

        summary.textContent = texto;
    }

    console.log("👥 Adultos:", adultos, "Niños:", ninos);
}


function cargarVehiculos() {

    fetch(base_url + "reservacion/lista_vehiculos/" + CURRENT_REGISTRO_ID)
        .then(r => r.json())
        .then(data => {

            DATA.vehiculos = data;

            console.log("🚗 Vehículos cargados:", DATA.vehiculos);

            renderVehiclesList();
            renderVehicleCard();

        })
        .catch(e => console.error("❌ Error cargar vehículos", e));
}



async function savePerfilFiscal() {

    const payload = {
        registro_id: CURRENT_REGISTRO_ID,
        rfc: document.getElementById('fact-rfc').value,
        razon_social: document.getElementById('fact-razon').value,
        regimen_fiscal: document.getElementById('fact-regimen').value,
        codigo_postal_fiscal: document.getElementById('fact-cp').value,
        uso_cfdi: document.getElementById('fact-uso').value,
        email_facturacion: document.getElementById('fact-email').value,
        es_extranjero: document.getElementById('fact-extranjero').checked ? 1 : 0,
        QR: document.getElementById('fact-qr').value
    };

    if (!payload.rfc || !payload.razon_social) {
        return Swal.fire("Error", "RFC y Razón Social son obligatorios", "error");
    }

    try {
        const res = await fetch(base_url + "reservacion/guardar-perfil", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const resp = await res.json();

        if (resp.ok) {
            Swal.fire("Éxito", "Perfil fiscal guardado y vinculado al registro", "success");
            toggleModal('modal-facturacion', false);
            
            // Actualizar el estado global
            await cargarPerfilFiscal();
            updateFacturacionStatus();
            
        } else {
            Swal.fire("Error", resp.msg || "No se pudo guardar el perfil", "error");
        }
    } catch (error) {
        console.error("Error saving fiscal profile:", error);
        Swal.fire("Error", "Error de conexión al servidor", "error");
    }
}


async function openFacturaModal() {

    // 🔥 cargar desde BD
    await cargarPerfilFiscal();
    updateFacturacionStatus();

    // limpiar
    document.getElementById('fact-rfc').value = '';
    document.getElementById('fact-razon').value = '';
    document.getElementById('fact-regimen').value = '';
    document.getElementById('fact-cp').value = '';
    document.getElementById('fact-uso').value = '';
    document.getElementById('fact-email').value = '';
    document.getElementById('fact-extranjero').checked = false;
    document.getElementById('fact-qr').value = '';

    // 🔥 cargar datos si existen
    if (DATA.perfilFiscal) {

        const p = DATA.perfilFiscal;

        document.getElementById('fact-rfc').value = p.rfc || '';
        document.getElementById('fact-razon').value = p.razon_social || '';
        document.getElementById('fact-regimen').value = p.regimen_fiscal || '';
        document.getElementById('fact-cp').value = p.codigo_postal_fiscal || '';
        document.getElementById('fact-uso').value = p.uso_cfdi || '';
        document.getElementById('fact-email').value = p.email_facturacion || '';
        document.getElementById('fact-extranjero').checked = p.es_extranjero == 1;
        document.getElementById('fact-qr').value = p.QR || '';
    }

    toggleModal('modal-facturacion', true);
}

// savePerfilFiscal has been moved up and unified with guardarPerfilFiscal logic.
// The mod_administrativo.php calls savePerfilFiscal().



async function deletePerfilFiscal() {

    if (!confirm("¿Eliminar perfil fiscal del registro?")) return;

    const res = await fetch(base_url + "reservacion/eliminar-perfil/" + CURRENT_REGISTRO_ID, {
        method: "POST"
    });

    const resp = await res.json();

    if (!resp.ok) return alert(resp.msg);

    alert("Perfil eliminado");
    updateFacturacionStatus();

    toggleModal('modal-facturacion', false);
}


async function cargarPerfilFiscal() {

    try {

        const res = await fetch(base_url + "reservacion/perfil/" + CURRENT_REGISTRO_ID);
        const data = await res.json();

        if (!data.ok) {
            DATA.perfilFiscal = null;
            return;
        }

        DATA.perfilFiscal = data.data;
        updateFacturacionStatus();

        console.log("📄 Perfil fiscal:", DATA.perfilFiscal);

    } catch (e) {
        console.error("Error cargar perfil fiscal", e);
    }
}

function updateFacturacionStatus() {

    const el = document.getElementById('menu-facturacion');
    if (!el) return;

    const hasData = DATA.perfilFiscal && DATA.perfilFiscal.id_perfil;

    if (hasData) {

        el.classList.remove('bg-red-50', 'text-red-600');
        el.classList.add('bg-emerald-50', 'text-emerald-600');

        el.innerHTML = `
            <i class="fas fa-file-invoice-dollar"></i>
            FACTURACIÓN
            <span class="ml-auto text-[9px] font-black bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-lg">
                OK
            </span>
        `;

    } else {

        el.classList.remove('bg-emerald-50', 'text-emerald-600');
        el.classList.add('bg-red-50', 'text-red-600');

        el.innerHTML = `
            <i class="fas fa-file-invoice-dollar"></i>
            FACTURACIÓN
            <span class="ml-auto text-[9px] font-black bg-red-100 text-red-600 px-2 py-0.5 rounded-lg">
                FALTA
            </span>
        `;
    }
}



function generarPDF() {

    // 🔹 aquí debes tener el ID del folio activo
    const folioId = CURRENT_REGISTRO_ID; // o donde lo estés guardando

    if (!folioId) {
        alert('No hay folio seleccionado');
        return;
    }

    window.open(`${base_url}factura/pdf/${folioId}`, '_blank');
}








let CAMERA_STREAM = null;

async function startCamera(type = 'main') {

    const ids = {
        main: {
            video: 'camera-stream',
            preview: 'edit-preview-foto',
            btn: 'btn-take-photo'
        },
        comp: {
            video: 'video-comp',
            preview: 'preview-comp',
            btn: 'btn-snap-comp'
        }
    };

    const config = ids[type] || ids.main;

    const video = document.getElementById(config.video);
    const preview = document.getElementById(config.preview);
    const btnTake = document.getElementById(config.btn);

    if (!video || !preview || !btnTake) {
        console.error("❌ Elementos no encontrados:", config);
        alert("Error interno: elementos de cámara no encontrados");
        return;
    }

    try {

        // 🔥 detener cámara previa
        if (CAMERA_STREAM) {
            CAMERA_STREAM.getTracks().forEach(t => t.stop());
        }

        const stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        });

        CAMERA_STREAM = stream;

        video.srcObject = stream;

        // 🔥 esperar a que cargue el video
        await new Promise(resolve => {
            video.onloadedmetadata = () => {
                video.play();
                resolve();
            };
        });

        // UI
        video.classList.remove('hidden');
        preview.classList.add('hidden');

        btnTake.classList.remove('hidden');
        btnTake.style.display = 'block';

        console.log("📷 Cámara iniciada:", type);

    } catch (e) {
        console.error("❌ Error cámara:", e);

        alert(`
No se pudo iniciar la cámara.

Posibles causas:
- Cámara en uso (Zoom, Teams)
- Permisos bloqueados
- Navegador no compatible
        `);
    }
}

// 🔥 TOMAR FOTO
async function takePhoto(type = 'main') {

    const ids = {
        main: {
            video: 'camera-stream',
            preview: 'edit-preview-foto',
            canvas: 'photo-canvas'
        },
        comp: {
            video: 'video-comp',
            preview: 'preview-comp',
            canvas: 'canvas-comp'
        }
    };

    const config = ids[type] || ids.main;

    const video = document.getElementById(config.video);
    const preview = document.getElementById(config.preview);
    const canvas = document.getElementById(config.canvas);

    if (!video || !preview || !canvas) {
        console.error("❌ Elementos no encontrados:", config);
        alert("Error interno de cámara");
        return;
    }

    try {

        // 🔥 esperar a que el video esté listo SI ES NECESARIO
        if (!video.videoWidth || !video.videoHeight) {
            await new Promise(resolve => {
                video.onloadedmetadata = () => resolve();
            });
        }

        if (!video.videoWidth) {
            alert("La cámara no está lista aún");
            return;
        }

        // 🔥 dimensiones seguras
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext("2d");

        if (!ctx) {
            throw new Error("No se pudo obtener contexto del canvas");
        }

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // 🔥 compresión ligera (MEJOR QUE PNG)
        const base64 = canvas.toDataURL("image/jpeg", 0.85);

        // preview inmediato
        preview.src = base64;
        preview.classList.remove('hidden');
        video.classList.add('hidden');

        // 🔥 subir al servidor (IMPORTANTE)
        let fileName = null;

        try {
            fileName = await subirFotoReserva(base64);
        } catch (uploadError) {
            console.warn("⚠️ Error subiendo foto:", uploadError);
        }

        // guardar resultado
        if (fileName) {
            preview.dataset.file = fileName;
        } else {
            // fallback si falla upload
            preview.dataset.file = base64;
        }

        // 🔥 detener cámara
        stopCamera();

        console.log("📸 Foto capturada:", type);

    } catch (e) {

        console.error("❌ Error tomando foto:", e);

        alert(`
Error al capturar imagen.

Posibles causas:
- Cámara no lista
- Permisos bloqueados
- Canvas error
        `);
    }
}

// 🔥 DETENER
function stopCamera() {

    if (CAMERA_STREAM) {
        CAMERA_STREAM.getTracks().forEach(t => t.stop());
        CAMERA_STREAM = null;
    }

    document.querySelectorAll('video').forEach(v => {
        v.srcObject = null;
        v.classList.add('hidden');
    });
}



async function subirFotoReserva(base64) {

    try {

        const res = await fetch(base_url + "media/subir-foto", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                foto: base64
            })
        });

        const resp = await res.json();

        if (resp.ok) {
            return resp.file;   // ⭐ nombre archivo
        } else {
            alert("Error subiendo foto");
            return null;
        }

    } catch (e) {
        console.error(e);
        return null;
    }

}


function renderVehicleCard() {

    const box = document.getElementById('card-vehiculos');

    if (!box) return;

    const vehiculos = DATA.vehiculos || [];

    // 🔥 solo titular
    const titularVehiculos = vehiculos.filter(v => v.tipo === 'TITULAR');

    const total = vehiculos.length;

    let placasHTML = '';

    if (titularVehiculos.length > 0) {

        placasHTML = titularVehiculos.map(v => `
            <p class="text-xs font-black">PLACA: ${v.placa}</p>
        `).join('');

    } else {

        placasHTML = `
            <p class="text-xs text-slate-400 italic">
                Sin vehículos del titular
            </p>
        `;
    }

    box.innerHTML = `
        <p class="pms-label">Vehículo & Acceso</p>

        <div class="mt-2 flex items-center gap-3">

            <i class="fas fa-car text-slate-300 text-lg"></i>

            <div>

                ${placasHTML}

                <p class="text-[9px] text-slate-400 uppercase font-bold">
                    Total vehículos: ${total}
                </p>

            </div>

        </div>
    `;
}


async function cancelarEstadia(registroId) {

    if (!confirm("¿Cancelar estadía? Esta acción no se puede deshacer")) return;

    try {

        const resp = await fetch(base_url + "registro/cancelar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: registroId })
        });

        const data = await resp.json();

        if (!data.ok) throw new Error(data.msg);

        showToast("Estadía cancelada");

        // 🔄 refrescar UI
        location.reload();

    } catch (e) {
        alert(e.message);
    }
}


function cancelarEstadia() {

    DATA.pendingDelete = {
        id: CURRENT_REGISTRO_ID,
        type: 'estadia'
    };

    ui.set(
        'reajuste-hint',
        "CANCELACIÓN TOTAL: Se revertirán todos los cargos y la habitación será liberada. Este movimiento quedará registrado en auditoría."
    );

    openMotivoModal();
}



function updateCancelPreview() {

    const tipo = document.getElementById('cancel-type').value;
    const nochesUsadas = Number(document.getElementById('noches-usadas').value || 0);
    const penal = Number(document.getElementById('penalizacion').value || 0);

    // 🔥 detectar noches
    const f1 = new Date(DATA.stay.arrival);
    const f2 = new Date(DATA.stay.departure);

    const nochesTotales = Math.max(1, (f2 - f1) / (1000 * 60 * 60 * 24));

    let reverso = 0;

    DATA.consumos.forEach(c => {

        if (c.tipo !== 'Hospedaje' && c.tipo !== 'Persona Extra') return;

        const monto = Number(c.monto || 0);

        if (tipo === 'NO_SHOW') {
            reverso += monto;
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

        const resp = await fetch(base_url + "registro/cancelar-inteligente", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: CURRENT_REGISTRO_ID,
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

        // 🔄 refresh
        location.reload();

    } catch (e) {
        alert(e.message);
    }
}


function openCancelacionModal() {

    openModal('modal-cancelacion');

    const modal = document.getElementById('modal-cancelacion');

    // 🔥 noches totales
    if (DATA?.stay?.arrival && DATA?.stay?.departure) {

        const f1 = new Date(DATA.stay.arrival);
        const f2 = new Date(DATA.stay.departure);

        const noches = Math.max(1, (f2 - f1) / (1000 * 60 * 60 * 24));

        modal.querySelector('#noches-totales').innerText = noches;
        modal.querySelector('#noches-usadas').value = noches;
    }

    // 🔥 detectar pagos
    if (DATA?.pagos_realizados?.length > 0) {
        modal.querySelector('#alert-pagos').classList.remove('hidden');
    }

    setTimeout(updateCancelPreview, 80);
}


function openModal(id) {

    if (!id) {
        console.warn("openModal sin id");
        return;
    }

    toggleModal(id, true);
}

function closeModal(id) {
    toggleModal('modal-cancelacion', false);
}

function formatMoney(value) {

    const num = Number(value || 0);

    return num.toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2
    });
}


async function cargarEstadosHabitacion(selectId = 'estado_habitacion', selected = null) {
    try {
        const select = document.getElementById(selectId);
        if (!select) return;

        // loading
        select.innerHTML = `<option value="">Cargando...</option>`;

        const response = await fetch(base_url + 'reservacion/estados-habitacion');
        const data = await response.json();

        if (!data || data.length === 0) {
            select.innerHTML = `<option value="">Sin datos</option>`;
            return;
        }

        let html = `<option value="">Seleccione</option>`;

        data.forEach(e => {
            html += `
                <option value="${e.codigo}" 
                    ${selected == e.codigo ? 'selected' : ''}>
                    ${e.nombre}
                </option>
            `;
        });

        select.innerHTML = html;

    } catch (error) {
        console.error("Error cargando estados:", error);
    }
}

async function hacerCheckout(registroId) {
    if (!registroId) return;

    if (!confirm("¿Confirmar checkout?")) return;

    try {
        const formData = new FormData();
        formData.append('registro_id', registroId);

        const response = await fetch(base_url + 'reservacion/checkout', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            alert(data.msg || "Error en checkout");
            return;
        }

        console.log("✅ Checkout OK");

        // 🔥 Refrescar solo el renglón específico en la tabla
        if (typeof rooms !== 'undefined' && typeof renderGrid === 'function') {
            const idx = rooms.findIndex(r => r.registro_id == registroId);
            if (idx > -1) {
                rooms[idx].status = 'S'; // Pasa a sucia
                rooms[idx].registro_id = data.nuevo_registro_id || null; // 🔥 Se actualiza con el nuevo folio vacío
                rooms[idx].huespedes = [];
                rooms[idx].services = [];
                rooms[idx].payments = [];
                rooms[idx].precio = 0;
                rooms[idx].dias = 0;
                rooms[idx].horaEntrada = '';
                rooms[idx].fechaEntrada = '';
                
                renderGrid(); // Refrescar DOM
            }
        }
        
        if (typeof closeModal === 'function') {
            closeModal('modal-register');
        }

    } catch (e) {
        console.error("❌ Error:", e);
        alert("Error de conexión");
    }
}



