let HUESPED = null;
let ACOMPANANTES = null;
let streamInstance = null;
let CURRENT_FLOOR_ADMIN = null;

let DATA = {
    consumos: [],
    stay : {
        arrival: '',
        departure: '',
        dailyRate: 0
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
     document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
    document.getElementById('tab-' + sectionId).classList.add('active');

    ['resumen', 'cargos', 'pagos', 'room_move', 'room_service', 'bitacora'].forEach(id => {
        const el = document.getElementById('sec-' + id);
        if (el) el.classList.add('hidden');
    });

    const activeSec = document.getElementById('sec-' + sectionId);
    if (activeSec) activeSec.classList.remove('hidden');

    if (sectionId === 'cargos') renderCargosTerminal();
    if (sectionId === 'pagos') renderPaymentsUI();
    if (sectionId === 'room_service') renderRoomServiceMenu();
    if (sectionId === 'room_move') renderAvailableRooms();
}


function cargarPisosadmin(){

    fetch(base_url + "catalogos/listar_pisos")
    .then(r => r.json())
    .then(data => {

      

        const cont = document.getElementById("floorContaineradmin");
        cont.innerHTML = '';

        data.forEach((p,i)=>{

            const btn = document.createElement("button");
            btn.className =
                "pill-btn floor-btn" + (i===0 ? " active" : "");
            btn.innerText = p.Piso;
            btn.onclick = () => switchFlooradmin(p.Piso);
            cont.appendChild(btn);
            if(i===0){
                CURRENT_FLOOR_ADMIN = p.Piso;
            }

        });

    })
    .catch(e => console.error("Error pisos",e));
}

function cargarTiposHabitacionadmin(){

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

function cargarHabitacionesPorPisoadmin(){

    fetch(
        base_url +
        "habitaciones/listado_visual"
    )
    .then(r=>r.json())
    .then(data=>{

        ROOMS.length = 0;

        data.forEach(r=>{

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
    cargarConsumos(id);
    cargarPisosadmin();
    cargarTiposHabitacionadmin();
    cargarHabitacionesPorPisoadmin();
    cargarRoomServiceMenu();
    //  cargarPagos(id);
    //  cargarAcompanantes(id);

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

        // ⭐ nombre huésped
        document.getElementById("admin_nombre").innerText = data.Nombre_Huesped;

        // ⭐ Folio
        document.getElementById("admin_folio").innerText = data.folio;

        // ⭐ admin_fecha_registro
        document.getElementById("admin_fecha_registro").innerText = data.admin_fecha_registro;

        // ⭐ aadmin_fecha_entrada
        document.getElementById("admin_fecha_entrada").innerText = fechaCortaHotel(data.hora_entrada);

        // ⭐ aadmin_fecha_salida
        document.getElementById("admin_fecha_salida").innerText = fechaCortaHotel(data.hora_salida);

        // ⭐ tipo habitación numero habitación
        document.getElementById("admin_habitacion").innerText = data.tip_habitacion + " #" + formatoHabitacion(data.Numero_Habitacion);


        DATA.currentRoom = "#" + formatoHabitacion(data.Numero_Habitacion);

        DATA.stay = {
            arrival: data.hora_entrada?.split(' ')[0], // 👈 ISO limpio
            departure: data.hora_salida?.split(' ')[0],
            dailyRate: 2500
        };

        // ⭐ ocupantes
        // document.getElementById("admin_ocupacion").innerText = fechaCortaHotel(data.admin_ocupacion);

        // ⭐ pintar estado
        //pintarEstadoFinanciero(data.saldo);

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
    document.getElementById('edit-preview-foto').src = '"https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"'
    //base_url +'/uploads/fotos/'+ HUESPED.fotografia;
}


/* =====================================================
GUARDAR PERFIL HUESPED → HOTEL OS
===================================================== */
function saveProfile() {

    const payload = {

        id: CURRENT_GUEST_ID,   // ⭐ MUY IMPORTANTE para que CI haga UPDATE
        nombre: document.getElementById('edit-nombre').value,
        apellido: document.getElementById('edit-apellido').value,
        email: document.getElementById('edit-email').value,
        genero: document.getElementById('edit-genero').value,
        telefono: document.getElementById('edit-telefono').value,
        direccion: document.getElementById('edit-direccion').value,
        ciudad: document.getElementById('edit-ciudad').value,
        tipo_identificacion_id: document.getElementById('edit-tipo_identificacion_id').value,
        numero_identificacion: document.getElementById('edit-numero_identificacion').value,
        notas: document.getElementById('edit-notas').value,
        fotografia: document.getElementById("edit-preview-foto")?.dataset.file || null

    };

    console.log("HUESPED guardado:", payload);

    fetch(base_url + "pasajeros/guardar_huesped", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(resp => {
            console.log("SAVE RESP", resp);
            if (resp.ok) {
                // ⭐ sincronizamos estado frontend
                Object.assign(HUESPED, payload);
                updateUI();
                toggleEditModal(false);
                alert("✓ Perfil actualizado");

            } else {
                alert("No se pudo guardar");
            }
        })
        .catch(e => {
            console.error("Error guardando huésped", e);
            alert("Error servidor");
        });
}



/* =====================================================
GUARDAR PERFIL ACOMPAÑANTE
===================================================== */

function cargarAcompanantes() {

    fetch(base_url + "pasajeros/acompanantes/" + CURRENT_REGISTRO_ID)
        .then(r => r.json())
        .then(data => {

            ACOMPANANTES = data;   // ✅ aquí se llena la variable global
            console.log("HUESPED cargado:", ACOMPANANTES);
            renderCompanionsList();
        })
        .catch(e => console.error("Error cargar huésped", e));
}

// --- 1. DATOS (Mapeado a tablas SQL) ---
let TITULAR = {
    nombre: "Israel de los Santos",
    id: "INE-992210",
    fotografia: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
};

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
         m.classList.add('active'); }
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
    ACOMPANANTES.forEach((c, idx) => {
        grid.insertAdjacentHTML('beforeend', `
                    <div class="bg-white border rounded-2xl p-4 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <img src="${c.fotografia}" class="w-14 h-14 rounded-xl object-cover border">
                            <div>
                                <h4 class="font-black text-slate-900">${c.nombre} ${c.apellido}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${c.parentesco} | ${c.es_menor ? 'Menor' : 'Adulto'}</p>
                                <p class="text-[9px] text-indigo-500 font-bold">Estado: ${c.estado_estancia}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openCompanionForm(${idx})" class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteCompanion(${idx})" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `);
    });
    document.getElementById('companions-counter-footer').innerText = `${ACOMPANANTES.length} Acompañantes en registro_acompanantes`;
}

function openCompanionForm(idx) {
    currentCompIdx = idx;
    const title = document.getElementById('comp-form-title');
    window._temp_photo = null;

    // Reset de todos los campos SQL
    document.getElementById('comp-name').value = '';
    document.getElementById('comp-lastName').value = '';
    document.getElementById('comp-rel').value = '';
    document.getElementById('comp-tipo-id').value = 'INE';
    document.getElementById('comp-id').value = '';
    document.getElementById('comp-minor').checked = false;
    document.getElementById('comp-orden').value = ACOMPANANTES.length + 1;
    document.getElementById('comp-entrada').value = '';
    document.getElementById('comp-salida').value = '';
    document.getElementById('comp-estado').value = 'Hospedado';
    document.getElementById('comp-obs').value = '';
    document.getElementById('preview-comp').src = 'https://via.placeholder.com/200?text=Sin+Foto';

    if (idx >= 0) {
        const c = ACOMPANANTES[idx];
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
        document.getElementById('preview-comp').src = c.fotografia;
        window._temp_photo = c.fotografia;
    } else title.innerText = "Registrar Nuevo Acompañante";

    toggleCompanionForm(true);
}

function saveCompanion() {

    const payload = {

        id: currentCompIdx >= 0
            ? ACOMPANANTES[currentCompIdx].id
            : null,

        registro_id: CURRENT_REGISTRO_ID,

        nombre: document.getElementById('comp-name').value,
        apellido: document.getElementById('comp-lastName').value,
        parentesco: document.getElementById('comp-rel').value,
        tipo_identificacion: document.getElementById('comp-tipo-id').value,
        numero_identificacion: document.getElementById('comp-id').value,
        es_menor: document.getElementById('comp-minor').checked ? 1 : 0,
        orden_llegada: document.getElementById('comp-orden').value,
        hora_entrada: document.getElementById('comp-entrada').value,
        hora_salida: document.getElementById('comp-salida').value,
        estado_estancia: 'ACTIVO',
        observaciones: document.getElementById('comp-obs').value,
        fotografia: window._temp_photo || ''
    };

    console.log("PAYLOAD ACOMP", payload);

    fetch(base_url + "pasajeros/guardar_acompanante_update", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(resp => {

            console.log("SAVE ACOMP", resp);

            if (resp.ok) {

                // ⭐ si backend devuelve id nuevo
                if (resp.id) {
                    payload.id = resp.id;
                }

                if (currentCompIdx >= 0) {
                    Object.assign(ACOMPANANTES[currentCompIdx], payload);
                } else {
                    ACOMPANANTES.push(payload);
                }

                renderCompanionsList();
                /*    updateUI(); */
                toggleCompanionForm(false);

            } else {
                alert(resp.msg || "Error guardando acompañante");
            }

        })
        .catch(e => {

            console.error("Error save acompañante", e);
            alert("Error servidor");

        });

}

/* =====================================================
   ELIMINAR ACOMPAÑANTE (HotelOS)
   ===================================================== */
function deleteCompanion(idx) {

    const comp = ACOMPANANTES[idx];

    if (!comp) return;

    if (!confirm("¿Eliminar acompañante?")) return;

    fetch(base_url + "pasajeros/eliminar_acompanante/" + comp.id, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" }
    })
        .then(r => r.json())
        .then(resp => {

            console.log("DELETE ACOMP", resp);

            if (resp.ok) {

                ACOMPANANTES.splice(idx, 1);

                renderCompanionsList();
                updateUI();

            } else {
                alert(resp.msg || "No se pudo eliminar");
            }

        })
        .catch(e => {

            console.error("Error eliminar acompañante", e);
            alert("Error servidor");

        });

}


function cargarConsumos(registro_id) {

    fetch(base_url + "registropagos/registros/" + registro_id + "/pagos")
        .then(r => r.json())
        .then(data => {
            DATA.consumos = data.map(item => {
                const f = new Date(item.hora_pago);
                return {
                    id: item.id,
                    fecha: f.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit' }) +
                        ' ' +
                        f.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }),
                    concepto: item.concepto,
                    depto: item.sistema || 'SISTEMA',
                    monto: Number(item.monto),
                    qty: item.qty, // ⭐ por ahora fijo (luego puedes manejar cantidades)
                    // tipo: (item.tipo_movimiento || '').toLowerCase(), // 'cargo','pago','ajuste'
                    tipo: item.tipo,
                    estado: item.estado
                };
            });

            console.log("DATA.consumos:", DATA.consumos);
            updateUI();
        })
        .catch(e => console.error("Error cargar consumos", e));
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

            updateUI();

        })
        .catch(e => console.error("Error cargar room service", e));
 
}



// --- 1. MODELO DE DATOS SQL (Estado inicial) ---
DATA = {

   

vehiculos: [],
    titular: {
        nombre: "Israel",
        apellido: "de los Santos",
        id: "INE-992210",
        fotografia: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
    },
    /*  acompanantes: [
         { id: 1, nombre: "María Fernanda", apellido: "Ruiz", parentesco: "Esposa", es_menor: 0, fotografia: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200" },
         { id: 2, nombre: "Israel Jr.", apellido: "de los Santos", parentesco: "Hijo", es_menor: 1, fotografia: "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200" }
     ],
     consumos: [
         { id: 1, fecha: '21/03 14:15', concepto: 'Hospedaje Suite Presidencial', depto: 'SISTEMA', monto: 2500, qty: 1, tipo: 'hospedaje' }
     ], */
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
    ], */
    availableRooms: [
        { id: 1, num: '102', type: 'Estándar', price: 850, status: 'Limpia' },
        { id: 2, num: '105', type: 'Doble Ejecutiva', price: 1250, status: 'Limpia' },
        { id: 3, num: '208', type: 'Suite Ejecutiva', price: 1800, status: 'Limpia' },
        { id: 4, num: '301', type: 'Suite Presidencial', price: 2500, status: 'Limpia' },
        { id: 5, num: '305', type: 'Doble Premium', price: 1450, status: 'Limpia' }
    ],
    currentRoom: '#204',
    selectedMoveRoom: null,
    roomServiceCart: []
};

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
function quickAdd(concepto, depto, monto) { applyCharge(concepto, depto, monto, 1); }

function addManualCharge() {
    const c = document.getElementById('manual-concept');
    const p = document.getElementById('manual-price');
    const q = document.getElementById('manual-qty');
    if (!c.value || isNaN(p.value) || parseFloat(p.value) <= 0) return;
    applyCharge(c.value, 'MANUAL', parseFloat(p.value), parseInt(q.value));
    c.value = ''; p.value = ''; q.value = 1;
}

function filterPOS() {
    const query = document.getElementById('pos-search').value.toLowerCase();
    const cards = document.querySelectorAll('#pos-grid-container .pos-item-card');
    cards.forEach(card => {
        const label = card.querySelector('.label').innerText.toLowerCase();
        if (label.includes(query)) card.style.display = 'flex'; else card.style.display = 'none';
    });
}

function applyCharge(concepto, depto, monto, qty) {
    const now = new Date();
    const fecha = now.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit' }) + " " + now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
    DATA.consumos.push({ id: Date.now(), fecha, concepto, depto, monto, qty, tipo: 'extra' });
    ui.set('last-charge-label', concepto);
    ui.set('last-charge-price', "$" + (monto * qty).toLocaleString());
    updateUI();
    if (!document.getElementById('tab-cargos').classList.contains('hidden')) renderCargosTerminal();
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
    ui.set('move-dest-room', '#'  + formatoHabitacion(num));

    const btn = document.getElementById('btn-confirm-move');
    btn.disabled = false;
    btn.classList.remove('opacity-30', 'cursor-not-allowed');

    renderAvailableRooms();
}

function executeRoomMove() {
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
}

// --- 8. PAGOS & ABONOS ---
function selectPaymentMethod(method, el) {
    DATA.currentPaymentMethod = method;
    document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
}

function processPayment() {
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
}

// --- 9. ELIMINACIÓN CON MOTIVO Y REAJUSTE ---
function deletePayment(id) {
    DATA.pendingDelete = { id, type: 'pago' };
    ui.set('reajuste-hint', "REAJUSTE HACIA ARRIBA: Al eliminar este abono, el saldo por liquidar del huésped aumentará.");
    openMotivoModal();
}

function deleteCharge(id) {
    const idx = DATA.consumos.findIndex(c => c.id === id);
    if (idx !== -1 && DATA.consumos[idx].tipo !== 'hospedaje') {
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

function confirmDeletion() {
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
    ui.set('name-titular', DATA.titular.nombre + " " + DATA.titular.apellido);
    ui.set('id-titular', "ID: " + DATA.titular.id);
    ui.attr('img-titular', 'src', DATA.titular.fotografia);
    ui.attr('audit-avatar', 'src', DATA.titular.fotografia);
    ui.set('audit-name', DATA.titular.nombre);

    /*    const ad = 1 + DATA.acompanantes.filter(c => !c.es_menor).length;
       const ni = DATA.acompanantes.filter(c => c.es_menor).length;
       ui.set('pax-text-summary', `${ad} Adultos, ${ni} Niños`);
       
       const stack = document.getElementById('pax-visual-stack');
       if (stack) {
           stack.innerHTML = '<div class="avatar-circle bg-adult shadow-sm">AD</div>';
           DATA.acompanantes.forEach(c => {
               stack.innerHTML += `<div class="avatar-circle ${c.es_menor ? 'bg-child' : 'bg-adult'} shadow-sm">${c.es_menor ? 'NI' : 'AD'}</div>`;
           });
       } */

    let hosp = 0, extras = 0;
    DATA.consumos.forEach(c => {
        const sub = (c.monto * c.qty);
        if (c.tipo === 'hospedaje') hosp += sub; else extras += sub;
    });

    const totalPagos = DATA.pagos_realizados.reduce((acc, p) => acc + p.monto, 0);
    const taxes = (hosp + extras) * 0.19;
    const total = hosp + extras + taxes;
    const saldo = total - totalPagos;

    ui.set('stat-hospedaje', "$" + hosp.toLocaleString());
    ui.set('stat-extras', "$" + extras.toLocaleString());
    ui.set('stat-taxes', "$" + taxes.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    ui.set('stat-payments', "-$" + totalPagos.toLocaleString());
    ui.set('total-folio', "$" + total.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    ui.set('saldo-neto', "$" + (saldo < 0 ? 0 : saldo).toLocaleString(undefined, { minimumFractionDigits: 2 }));
    ui.set('terminal-session-total', "$" + extras.toLocaleString());

    const percent = total > 0 ? (totalPagos / total * 100) : 0;
    ui.style('payment-bar', 'width', Math.min(percent, 100) + "%");
    ui.set('payment-percent', Math.round(percent) + "%");

    const resTbody = document.getElementById('table-resumen-body');
    if (resTbody) {
        resTbody.innerHTML = '';
        DATA.consumos.slice(-4).reverse().forEach(c => {
            resTbody.innerHTML += `<tr class="fade-in">
                                              <td class="px-6 py-2 text-slate-400 font-mono text-[10px]">${c.fecha}</td>
                                              <td class="py-2 font-bold text-slate-700 text-xs">${c.concepto}</td>
                                              <td class="py-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-lg text-[9px] font-black uppercase">${c.depto}</span></td>
                                               <td class="py-2 font-bold text-slate-700 text-xs">${c.estado}</td>
                                              <td class="text-right px-6 py-2 font-black text-indigo-600 text-xs">$${(c.monto * c.qty).toLocaleString()}</td></tr>`;
        });
    }
    ui.set('sync-time', new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
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
        body.innerHTML += `<div class="history-row fade-in"><span class="text-[10px] font-mono text-slate-400">${c.fecha.split(' ')[1]}</span><div class="history-icon ${info.bg} ${info.color} shadow-sm"><i class="fas ${info.icon}"></i></div><div class="px-4"><p class="font-bold text-xs text-slate-700 leading-none">${c.concepto}</p><p class="text-[9px] text-slate-400 font-bold uppercase mt-1">${c.depto}</p></div><span class="font-black text-[10px] text-center text-slate-400">x${c.qty}</span><span class="text-right font-black text-indigo-600 text-sm">$${(c.monto * c.qty).toLocaleString()}</span><div class="flex justify-end">${c.tipo !== 'hospedaje' ? `<button onclick="deleteCharge(${c.id})" class="w-7 h-7 rounded-lg text-red-300 hover:text-red-500 hover:bg-red-50 transition-all"><i class="fas fa-undo-alt text-xs"></i></button>` : '<i class="fas fa-lock text-slate-200 text-[10px]"></i>'}</div></div>`;
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
            sel.innerHTML = `<option value="${DATA.titular.nombre}">${DATA.titular.nombre} (Titular)</option>`;
            DATA.acompanantes.forEach(c => {
                sel.innerHTML += `<option value="${c.nombre}">${c.nombre} (Acomp.)</option>`;
            });
        }

        function registerVehicle() {
            const plates = document.getElementById('vehicle-plates').value;
            const model = document.getElementById('vehicle-model').value;
            const owner = document.getElementById('vehicle-owner').value;
            if (!plates || !model) return alert("Por favor, ingrese placas y modelo.");
            
            DATA.vehiculos.push({ id: Date.now(), plates: plates.toUpperCase(), model, owner });
            document.getElementById('vehicle-plates').value = '';
            document.getElementById('vehicle-model').value = '';
            renderVehiclesList();
        }

        function renderVehiclesList() {
            const box = document.getElementById('vehicles-list');
            if (DATA.vehiculos.length === 0) {
                box.innerHTML = '<div class="text-center py-10 text-slate-400 italic text-xs">No hay vehículos registrados.</div>';
                return;
            }
            box.innerHTML = '';
            DATA.vehiculos.forEach(v => {
                box.innerHTML += `
                <div class="item-row">
                    <div class="flex items-center gap-4">
                        <div class="plate-badge">${v.plates}</div>
                        <div>
                            <p class="text-xs font-black text-slate-900">${v.model}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Prop: ${v.owner}</p>
                        </div>
                    </div>
                    <button onclick="deleteVehicle(${v.id})" class="text-red-400 hover:text-red-600 transition-colors"><i class="fas fa-trash"></i></button>
                </div>`;
            });
        }

        function deleteVehicle(id) {
            DATA.vehiculos = DATA.vehiculos.filter(v => v.id !== id);
            renderVehiclesList();
        }

        // --- CHARGES & PAYMENTS ---
        function quickAdd(l, d, p) {
            DATA.consumos.push({ id: Date.now(), fecha: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), concepto: l, depto: d, monto: p, qty: 1, tipo: 'extra' });
            updateUI();
        }

        function processPayment() {
            const amt = parseFloat(document.getElementById('payment-amount').value);
            if (isNaN(amt) || amt <= 0) return;
            DATA.pagos_realizados.push({ id: Date.now(), fecha: new Date().toLocaleDateString(), metodo: 'Efectivo', monto: amt });
            document.getElementById('payment-amount').value = '';
            updateUI();
            renderPaymentsUI();
        }

        function renderPaymentsUI() {
            const tbody = document.getElementById('payment-history-table-body');
            tbody.innerHTML = '';
            [...DATA.pagos_realizados].reverse().forEach(p => {
                tbody.innerHTML += `<tr><td class="px-6 py-4 font-mono text-[10px]">${p.fecha}</td><td>${p.metodo}</td><td class="text-right font-black text-emerald-600">$${p.monto.toLocaleString()}</td></tr>`;
            });
        }

        // --- AUDIT & UI REFRESH ---
        function calculateTotals() {
            let h = 0, e = 0;
            DATA.consumos.forEach(c => { if (c.tipo === 'hospedaje') h += (c.monto * c.qty); else e += (c.monto * c.qty); });
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

        function saveStayModification() {
            const newDep = document.getElementById('modify-departure').value;
            const newRate = parseFloat(document.getElementById('modify-rate').value);
            
            if (new Date(newDep) <= new Date(DATA.stay.arrival)) {
                return alert("La fecha de salida debe ser posterior a la de llegada.");
            }

            DATA.stay.departure = newDep;
            DATA.stay.dailyRate = newRate;

            const arr = new Date(DATA.stay.arrival);
            const dep = new Date(DATA.stay.departure);
            const nights = Math.ceil((dep - arr) / (1000 * 60 * 60 * 24));

            const hospIdx = DATA.consumos.findIndex(c => c.tipo === 'hospedaje');
            if (hospIdx !== -1) {
                DATA.consumos[hospIdx].monto = DATA.stay.dailyRate;
                DATA.consumos[hospIdx].qty = nights;
                DATA.consumos[hospIdx].concepto = `Hospedaje (${nights} Noches)`;
            } else {
                DATA.consumos.push({ 
                    id: 'HOSP', fecha: 'SISTEMA', concepto: `Hospedaje (${nights} Noches)`, 
                    depto: 'SISTEMA', monto: DATA.stay.dailyRate, qty: nights, tipo: 'hospedaje' 
                });
            }

            updateUI();
            toggleModal('modal-modify-stay', false);
            alert("✓ Estancia modificada.");
        }




