let HUESPED = null;
let ACOMPANANTES = null;



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
    //  cargarPagos(id);
    //  cargarAcompanantes(id);

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

        // ⭐ ocupantes
        document.getElementById("admin_ocupacion").innerText = fechaCortaHotel(data.admin_ocupacion);



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


function cargarHuesped() {

    fetch(base_url + "pasajeros/obtener_huesped/" + CURRENT_GUEST_ID)
        .then(r => r.json())
        .then(data => {

            HUESPED = data;   // ✅ aquí se llena la variable global
            syncDataToForm();
            console.log("HUESPED cargado:", HUESPED);

        })
        .catch(e => console.error("Error cargar huésped", e));

}




let streamInstance = null; // Variable para controlar el hardware de la cámara

// --- 2. CONTROLADORES DE MODAL ---
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

// Función 2: Tomar Foto
function takePhoto() {
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('photo-canvas');
    const preview = document.getElementById('edit-preview-foto');
    const btnStart = document.getElementById('btn-start-camera');
    const btnTake = document.getElementById('btn-take-photo');

    // Configurar canvas para capturar el frame actual
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convertir frame a Base64 (Data URL)
    const imageData = canvas.toDataURL('image/png');

    // Actualizar vista previa y detener cámara
    preview.src = imageData;
    HUESPED.fotografia = imageData; // Guardar temporalmente en el objeto

    stopCamera(); // Liberar hardware

    // Revertir UI
    video.classList.add('hidden');
    preview.classList.remove('hidden');
    btnStart.classList.remove('hidden');
    btnTake.classList.add('hidden');
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

// --- 4. LÓGICA DE NEGOCIO ---
const safeSet = (id, val, attr = 'innerText') => {
    const el = document.getElementById(id);
    if (el) el[attr] = val;
};

function switchSection(id) {
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
    ['resumen', 'cargos', 'pagos'].forEach(s => {
        const el = document.getElementById('sec-' + s);
        if (el) el.classList.add('hidden');
    });
    const active = document.getElementById('sec-' + id);
    if (active) active.classList.remove('hidden');
}

function syncDataToForm() {
    document.getElementById('edit-nombre').value = HUESPED.nombre;
    document.getElementById('edit-apellido').value = HUESPED.apellido;
    document.getElementById('edit-genero').value = HUESPED.genero;
    document.getElementById('edit-fecha_nacimiento').value = HUESPED.fecha_nacimiento;
    document.getElementById('edit-tipo_identificacion_id').value = HUESPED.tipo_identificacion_id;
    document.getElementById('edit-numero_identificacion').value = HUESPED.numero_identificacion;
    document.getElementById('edit-email').value = HUESPED.email;
    document.getElementById('edit-telefono').value = HUESPED.telefono;
    document.getElementById('edit-direccion').value = HUESPED.direccion;
    document.getElementById('edit-ciudad').value = HUESPED.ciudad;
    document.getElementById('edit-pais').value = HUESPED.pais;
    document.getElementById('edit-notas').value = HUESPED.notas;
    document.getElementById('edit-activo').checked = (HUESPED.activo === 1);

    document.getElementById('edit-preview-foto').src = HUESPED.fotografia;
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
        telefono: document.getElementById('edit-telefono').value,
        notas: document.getElementById('edit-notas').value

    };

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

function updateUI() {
    safeSet('display-nombre-completo', HUESPED.nombre + " " + HUESPED.apellido);
    safeSet('audit-huesped-name', HUESPED.nombre + " " + HUESPED.apellido);
    safeSet('display-empresa-top', HUESPED.empresa);
    safeSet('resumen-foto', HUESPED.fotografia, 'src');
    safeSet('audit-foto-circle', HUESPED.fotografia, 'src');
    safeSet('display-id-num', HUESPED.numero_identificacion);
    safeSet('display-tel', HUESPED.telefono);
    safeSet('display-email', HUESPED.email);
    safeSet('display-ciudad-estado', HUESPED.ciudad + ", " + HUESPED.pais);
    safeSet('sync-time', new Date().toLocaleTimeString());
}

window.onload = updateUI;




/*
       
        // registro_acompanantes: Campos completos
        let ACOMPANANTES = [
            { 
                id: 1, 
                nombre: "María Fernanda", 
                apellido: "Ruiz", 
                parentesco: "Esposa", 
                es_menor: 0, 
                tipo_identificacion: "Pasaporte",
                numero_identificacion: "MEX-001122",
                orden_llegada: 1,
                hora_entrada: "2026-03-21T14:20",
                hora_salida: "",
                estado_estancia: "Hospedado",
                observaciones: "VIP",
                fotografia: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200" 
            }
        ];
*/

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
let stream = null;

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

// --- 3. CÁMARA ---
async function startCamera(type) {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { width: 400, height: 400 } });
        const v = document.getElementById(`video-${type}`);
        const p = document.getElementById(`preview-${type}`);
        v.srcObject = stream;
        v.classList.remove('hidden');
        p.classList.add('hidden');
        document.getElementById(`btn-cam-${type}`).classList.add('hidden');
        document.getElementById(`btn-snap-${type}`).classList.remove('hidden');
    } catch (e) { alert("Cámara no disponible"); }
}

function takePhoto(type) {
    const v = document.getElementById(`video-${type}`);
    const c = document.getElementById(`canvas-${type}`);
    const p = document.getElementById(`preview-${type}`);
    c.width = v.videoWidth; c.height = v.videoHeight;
    c.getContext('2d').drawImage(v, 0, 0);
    const data = c.toDataURL('image/png');
    p.src = data;
    window._temp_photo = data;
    stopCamera();
    v.classList.add('hidden');
    p.classList.remove('hidden');
    document.getElementById(`btn-cam-${type}`).classList.remove('hidden');
    document.getElementById(`btn-snap-${type}`).classList.add('hidden');
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
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

function saveCompanion(){

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

    fetch(base_url + "pasajeros/guardar_acompanante_update",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(resp => {

        console.log("SAVE ACOMP", resp);

        if(resp.ok){

            // ⭐ si backend devuelve id nuevo
            if(resp.id){
                payload.id = resp.id;
            }

            if(currentCompIdx >= 0){
                Object.assign(ACOMPANANTES[currentCompIdx], payload);
            }else{
                ACOMPANANTES.push(payload);
            }

            renderCompanionsList();
         /*    updateUI(); */
            toggleCompanionForm(false);

        }else{
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
function deleteCompanion(idx){

    const comp = ACOMPANANTES[idx];

    if(!comp) return;

    if(!confirm("¿Eliminar acompañante?")) return;

    fetch(base_url + "pasajeros/eliminar_acompanante/" + comp.id,{
        method:"DELETE",
        headers:{ "Content-Type":"application/json" }
    })
    .then(r => r.json())
    .then(resp => {

        console.log("DELETE ACOMP", resp);

        if(resp.ok){

            ACOMPANANTES.splice(idx, 1);

            renderCompanionsList();
            updateUI();

        }else{
            alert(resp.msg || "No se pudo eliminar");
        }

    })
    .catch(e => {

        console.error("Error eliminar acompañante", e);
        alert("Error servidor");

    });

}

// --- 5. RENDERIZADO UI ---
function updateUI() {
    // Titular y Sidebar
    document.getElementById('display-titular-nombre').innerText = TITULAR.nombre;
    document.getElementById('audit-titular-name').innerText = TITULAR.nombre.split(' ')[0];
    document.getElementById('display-titular-id').innerText = "ID: " + TITULAR.id;
    document.getElementById('resumen-foto').src = TITULAR.fotografia;
    document.getElementById('audit-foto-circle').src = TITULAR.fotografia;

    // PAX INFO CARD (Cálculo dinámico de Adultos y Niños)
    const adCount = 1 + ACOMPANANTES.filter(c => !c.es_menor).length;
    const niCount = ACOMPANANTES.filter(c => c.es_menor).length;

    document.getElementById('pax-summary-text').innerText = `${adCount} Adultos, ${niCount} Niño${niCount !== 1 ? 's' : ''}`;

    const circles = document.getElementById('pax-circles-container');
    circles.innerHTML = '';
    // Titular AD
    circles.innerHTML += `<div class="pax-circle circle-ad">AD</div>`;
    // Acompañantes AD / NI
    ACOMPANANTES.forEach(c => {
        const cls = c.es_menor ? 'circle-ni' : 'circle-ad';
        const txt = c.es_menor ? 'NI' : 'AD';
        circles.insertAdjacentHTML('beforeend', `<div class="pax-circle ${cls}">${txt}</div>`);
    });
}

window.onload = updateUI;






        // --- BASE DE DATOS LOCAL SIMULADA ---
        const CONSUMOS = [
            { fecha: '21/03 - 14:20', concepto: 'Hospedaje - Noche 1', depto: 'SISTEMA', estado: 'Cargado', monto: 2500 },
            { fecha: '21/03 - 18:45', concepto: 'Room Service - Cena', depto: 'RESTAURANTE', estado: 'Pendiente', monto: 450 },
            { fecha: '21/03 - 19:00', concepto: 'Minibar - Refrescos', depto: 'AMA DE LLAVES', estado: 'Cargado', monto: 120 }
        ];

        const PAGOS = [
            { fecha: '21/03 - 14:15', metodo: 'Efectivo', referencia: 'Depósito Garantía', monto: 1000 }
        ];

        let selectedMethod = 'Efectivo';

        window.onload = () => {
            updateUI();
            updateTime();
        };

        function updateTime() {
            const now = new Date();
            document.getElementById('sync-time').innerText = now.getHours() + ":" + now.getMinutes().toString().padStart(2, '0') + ":" + now.getSeconds().toString().padStart(2, '0');
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

        // --- LÓGICA DE PAGOS ---

        function selectPayMethod(method) {
            selectedMethod = method;
            document.querySelectorAll('.pay-method').forEach(el => el.classList.remove('selected'));
            document.getElementById('method-' + method.toLowerCase()).classList.add('selected');
        }

        function registrarAbono() {
            const monto = parseFloat(document.getElementById('pago-monto').value);
            const ref = document.getElementById('pago-referencia').value || 'S/R';

            if (isNaN(monto) || monto <= 0) {
                alert("Ingrese un monto válido.");
                return;
            }

            const nuevoPago = {
                fecha: new Date().toLocaleDateString('es-MX', {day: '2-digit', month: '2-digit'}) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
                metodo: selectedMethod,
                referencia: ref,
                monto: monto
            };

            PAGOS.push(nuevoPago);
            updateUI();
            alert("✓ Abono de $" + monto + " registrado correctamente.");
            
            document.getElementById('pago-monto').value = "";
            document.getElementById('pago-referencia').value = "";
            switchSection('resumen');
        }

        // --- LÓGICA DE CARGOS ---
        
        function altaCargoManual() {
            const concepto = document.getElementById('cargo-concepto').value;
            const depto = document.getElementById('cargo-depto').value;
            const monto = parseFloat(document.getElementById('cargo-monto').value);

            if (!concepto || isNaN(monto) || monto <= 0) {
                alert("Por favor complete todos los campos correctamente.");
                return;
            }

            const nuevoConsumo = {
                fecha: new Date().toLocaleDateString('es-MX', {day: '2-digit', month: '2-digit'}) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
                concepto: concepto,
                depto: depto,
                estado: 'Cargado',
                monto: monto
            };

            CONSUMOS.push(nuevoConsumo);
            updateUI();
            alert("Cargo aplicado correctamente al folio.");
            switchSection('resumen');
            
            document.getElementById('cargo-concepto').value = "";
            document.getElementById('cargo-monto').value = "";
        }

        function addRoomService(item, precio) {
            const nuevoConsumo = {
                fecha: new Date().toLocaleDateString('es-MX', {day: '2-digit', month: '2-digit'}) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
                concepto: 'RS: ' + item,
                depto: 'RESTAURANTE',
                estado: 'Cargado',
                monto: precio
            };

            CONSUMOS.push(nuevoConsumo);
            updateUI();
            alert("✓ " + item + " cargado a la habitación.");
        }

        function updateUI() {
            // Tabla de Consumos
            const tbodyConsumos = document.querySelector('#table-consumos tbody');
            tbodyConsumos.innerHTML = '';
            
            let subHospedaje = 0;
            let subExtras = 0;

            CONSUMOS.forEach(item => {
                const tr = document.createElement('tr');
                const badgeClass = item.estado === 'Cargado' ? 'badge-success' : 'badge-warning';
                tr.innerHTML = `
                    <td>${item.fecha}</td>
                    <td class="font-bold">${item.concepto}</td>
                    <td>${item.depto}</td>
                    <td><span class="pms-badge ${badgeClass}">${item.estado}</span></td>
                    <td class="text-right font-black">$${item.monto.toLocaleString()}</td>
                `;
                tbodyConsumos.appendChild(tr);

                if (item.concepto.includes('Hospedaje')) {
                    subHospedaje += item.monto;
                } else {
                    subExtras += item.monto;
                }
            });

            // Tabla de Pagos
            const tbodyPagos = document.querySelector('#table-pagos tbody');
            tbodyPagos.innerHTML = '';
            let totalPagos = 0;

            PAGOS.forEach(pago => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${pago.fecha}</td>
                    <td class="font-bold uppercase text-[10px]">${pago.metodo}</td>
                    <td class="text-slate-400 italic">${pago.referencia}</td>
                    <td class="text-right font-black text-emerald-600">$${pago.monto.toLocaleString()}</td>
                `;
                tbodyPagos.appendChild(tr);
                totalPagos += pago.monto;
            });

            // Totales Sidebar
            const totalCargosSinImpuestos = subHospedaje + subExtras;
            const impuestos = totalCargosSinImpuestos * 0.19;
            const totalBruto = totalCargosSinImpuestos + impuestos;
            const saldoFinal = totalBruto - totalPagos;

            document.getElementById('audit-hospedaje').innerText = "$" + subHospedaje.toLocaleString();
            document.getElementById('audit-extras').innerText = "$" + subExtras.toLocaleString();
            document.getElementById('audit-taxes').innerText = "$" + impuestos.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('total_acumulado').innerText = "$" + totalBruto.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('sidebar-total-pagos').innerText = "-$" + totalPagos.toLocaleString();
            document.getElementById('saldo_final').innerText = "$" + saldoFinal.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            updateTime();
        }
   







/*

// --- BASE DE DATOS LOCAL SIMULADA ---
const CONSUMOS = [{
        fecha: '21/03 - 14:20',
        concepto: 'Hospedaje - Noche 1',
        depto: 'SISTEMA',
        estado: 'Cargado',
        monto: 2500
    },
    {
        fecha: '21/03 - 18:45',
        concepto: 'Room Service - Cena',
        depto: 'RESTAURANTE',
        estado: 'Pendiente',
        monto: 450
    },
    {
        fecha: '21/03 - 19:00',
        concepto: 'Minibar - Refrescos',
        depto: 'AMA DE LLAVES',
        estado: 'Cargado',
        monto: 120
    }
];

const PAGOS = [{
    fecha: '21/03 - 14:15',
    metodo: 'Efectivo',
    referencia: 'Depósito Garantía',
    monto: 1000
}];

let selectedMethod = 'Efectivo';

window.onload = () => {
    updateUI();
    updateTime();
};

function updateTime() {
    const now = new Date();
    document.getElementById('sync-time').innerText = now.getHours() + ":" + now.getMinutes().toString().padStart(2,
        '0') + ":" + now.getSeconds().toString().padStart(2, '0');
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

// --- LÓGICA DE PAGOS ---

function selectPayMethod(method) {
    selectedMethod = method;
    document.querySelectorAll('.pay-method').forEach(el => el.classList.remove('selected'));
    document.getElementById('method-' + method.toLowerCase()).classList.add('selected');
}

function registrarAbono() {
    const monto = parseFloat(document.getElementById('pago-monto').value);
    const ref = document.getElementById('pago-referencia').value || 'S/R';

    if (isNaN(monto) || monto <= 0) {
        alert("Ingrese un monto válido.");
        return;
    }

    const nuevoPago = {
        fecha: new Date().toLocaleDateString('es-MX', {
            day: '2-digit',
            month: '2-digit'
        }) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
        metodo: selectedMethod,
        referencia: ref,
        monto: monto
    };

    PAGOS.push(nuevoPago);
    updateUI();
    alert("✓ Abono de $" + monto + " registrado correctamente.");

    document.getElementById('pago-monto').value = "";
    document.getElementById('pago-referencia').value = "";
    switchSection('resumen');
}

// --- LÓGICA DE CARGOS ---

function altaCargoManual() {
    const concepto = document.getElementById('cargo-concepto').value;
    const depto = document.getElementById('cargo-depto').value;
    const monto = parseFloat(document.getElementById('cargo-monto').value);

    if (!concepto || isNaN(monto) || monto <= 0) {
        alert("Por favor complete todos los campos correctamente.");
        return;
    }

    const nuevoConsumo = {
        fecha: new Date().toLocaleDateString('es-MX', {
            day: '2-digit',
            month: '2-digit'
        }) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
        concepto: concepto,
        depto: depto,
        estado: 'Cargado',
        monto: monto
    };

    CONSUMOS.push(nuevoConsumo);
    updateUI();
    alert("Cargo aplicado correctamente al folio.");
    switchSection('resumen');

    document.getElementById('cargo-concepto').value = "";
    document.getElementById('cargo-monto').value = "";
}

function addRoomService(item, precio) {
    const nuevoConsumo = {
        fecha: new Date().toLocaleDateString('es-MX', {
            day: '2-digit',
            month: '2-digit'
        }) + " - " + new Date().getHours() + ":" + new Date().getMinutes().toString().padStart(2, '0'),
        concepto: 'RS: ' + item,
        depto: 'RESTAURANTE',
        estado: 'Cargado',
        monto: precio
    };

    CONSUMOS.push(nuevoConsumo);
    updateUI();
    alert("✓ " + item + " cargado a la habitación.");
}

function updateUI() {
    // Tabla de Consumos
    const tbodyConsumos = document.querySelector('#table-consumos tbody');
    tbodyConsumos.innerHTML = '';

    let subHospedaje = 0;
    let subExtras = 0;

    CONSUMOS.forEach(item => {
        const tr = document.createElement('tr');
        const badgeClass = item.estado === 'Cargado' ? 'badge-success' : 'badge-warning';
        tr.innerHTML = `
                    <td>${item.fecha}</td>
                    <td class="font-bold">${item.concepto}</td>
                    <td>${item.depto}</td>
                    <td><span class="pms-badge ${badgeClass}">${item.estado}</span></td>
                    <td class="text-right font-black">$${item.monto.toLocaleString()}</td>
                `;
        tbodyConsumos.appendChild(tr);

        if (item.concepto.includes('Hospedaje')) {
            subHospedaje += item.monto;
        } else {
            subExtras += item.monto;
        }
    });

    // Tabla de Pagos
    const tbodyPagos = document.querySelector('#table-pagos tbody');
    tbodyPagos.innerHTML = '';
    let totalPagos = 0;

    PAGOS.forEach(pago => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
                    <td>${pago.fecha}</td>
                    <td class="font-bold uppercase text-[10px]">${pago.metodo}</td>
                    <td class="text-slate-400 italic">${pago.referencia}</td>
                    <td class="text-right font-black text-emerald-600">$${pago.monto.toLocaleString()}</td>
                `;
        tbodyPagos.appendChild(tr);
        totalPagos += pago.monto;
    });

    // Totales Sidebar
    const totalCargosSinImpuestos = subHospedaje + subExtras;
    const impuestos = totalCargosSinImpuestos * 0.19;
    const totalBruto = totalCargosSinImpuestos + impuestos;
    const saldoFinal = totalBruto - totalPagos;

    document.getElementById('audit-hospedaje').innerText = "$" + subHospedaje.toLocaleString();
    document.getElementById('audit-extras').innerText = "$" + subExtras.toLocaleString();
    document.getElementById('audit-taxes').innerText = "$" + impuestos.toLocaleString(undefined, {
        minimumFractionDigits: 2
    });
    document.getElementById('total_acumulado').innerText = "$" + totalBruto.toLocaleString(undefined, {
        minimumFractionDigits: 2
    });
    document.getElementById('sidebar-total-pagos').innerText = "-$" + totalPagos.toLocaleString();
    document.getElementById('saldo_final').innerText = "$" + saldoFinal.toLocaleString(undefined, {
        minimumFractionDigits: 2
    });

    updateTime();
}

        // --- 4.1 ESTRUCTURA DE DATOS (Basada en tabla huespedes SQL) ---
        let GUEST_DATA = { 
            id: 1,
            nombre: "Israel", 
            apellido: "de los Santos",
            tipo_identificacion_id: 1,
            numero_identificacion: "ID-998822",
            fotografia: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200",
            telefono: "+52 555 123 4567",
            email: "israel@pms.com",
            direccion: "Calle Reforma 123",
            ciudad: "CDMX",
            estado: "CDMX",
            pais: "México",
            codigo_postal: "06700",
            nacionalidad: "Mexicana",
            fecha_nacimiento: "1990-05-15",
            genero: "M",
            empresa: "Elite Tech Solutions",
            notas: "Huésped VIP - Preferencia piso alto.",
            activo: 1,
            companions: [
                { name: "María Fernanda Ruiz", rel: "Esposa", id: "PAS-009911" },
                { name: "Israel Jr.", rel: "Hijo", id: "8 Años" }
            ]
        };

        // Historial de cargos inicial simulado
        const CONSUMOS = [{ fecha: '21/03 - 14:20', concepto: 'Hospedaje - Noche 1', depto: 'RECEPCIÓN', monto: 2500 }];

        // --- 4.2 CONTROLADORES DE MODALES ---
        // Abre el sistema de gestión
        function abrirHabitacion() {
            const modal = document.getElementById("master-modal-container");
            if(modal) {
                modal.classList.add("show");
                updateUI();
                renderPaxList();
                syncEditForm(); // Sincroniza datos con el formulario SQL
            }
        }

        // Cierra el sistema de gestión
        function cerrarHabitacion() {
            const modal = document.getElementById("master-modal-container");
            if(modal) modal.classList.remove("show");
        }

        // Control de modales secundarios
        function toggleModalSub(id, show) {
            const el = document.getElementById(id);
            if (show) el.classList.add('active');
            else el.classList.remove('active');
        }

        // --- 4.3 GESTIÓN DE DATOS ---
        // Guarda los cambios del perfil basados en la estructura SQL proporcionada
        function saveGuestProfile() {
            // Capturar datos del formulario
            GUEST_DATA.nombre = document.getElementById('edit-nombre').value;
            GUEST_DATA.apellido = document.getElementById('edit-apellido').value;
            GUEST_DATA.email = document.getElementById('edit-email').value;
            GUEST_DATA.telefono = document.getElementById('edit-telefono').value;
            GUEST_DATA.nacionalidad = document.getElementById('edit-nacionalidad').value;
            GUEST_DATA.empresa = document.getElementById('edit-empresa').value;
            GUEST_DATA.direccion = document.getElementById('edit-direccion').value;
            GUEST_DATA.ciudad = document.getElementById('edit-ciudad').value;
            GUEST_DATA.estado = document.getElementById('edit-estado').value;
            GUEST_DATA.pais = document.getElementById('edit-pais').value;
            GUEST_DATA.codigo_postal = document.getElementById('edit-cp').value;
            GUEST_DATA.genero = document.getElementById('edit-genero').value;
            GUEST_DATA.fecha_nacimiento = document.getElementById('edit-fecha-nac').value;
            GUEST_DATA.numero_identificacion = document.getElementById('edit-num-id').value;
            GUEST_DATA.notas = document.getElementById('edit-notas').value;
            GUEST_DATA.activo = document.getElementById('edit-activo').checked ? 1 : 0;

            if(!GUEST_DATA.nombre || !GUEST_DATA.apellido) return alert("Nombre y Apellido son requeridos.");

            // Actualizar interfaz y cerrar
            updateUI();
            renderPaxList();
            toggleModalSub('modal-edit-profile', false);
            console.log("Registro de Huésped Actualizado en Memoria:", GUEST_DATA);
        }

        // Añade un acompañante a la lista
        function addCompanion() {
            const name = document.getElementById('pax-new-name').value;
            const rel = document.getElementById('pax-new-rel').value;
            const idDoc = document.getElementById('pax-new-id').value;

            if(!name || !rel) return alert("Ingrese nombre y parentesco.");

            GUEST_DATA.companions.push({ name, rel, id: idDoc });
            
            // Limpiar campos internos
            document.getElementById('pax-new-name').value = '';
            document.getElementById('pax-new-rel').value = '';
            document.getElementById('pax-new-id').value = '';

            renderPaxList();
            updateUI();
        }

        // --- 4.4 RENDERIZADO Y UI ---
        // Dibuja la lista de pax y sus previsualizaciones
        function renderPaxList() {
            const container = document.getElementById('pax-list-container');
            const previewContainer = document.getElementById('pax-preview-icons');
            if(!container) return;

            container.innerHTML = '';
            previewContainer.innerHTML = '';

            // Primero el titular (basado en DB)
            const titularHTML = `<div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-indigo-100">
                <img src="${GUEST_DATA.fotografia}" class="w-10 h-10 rounded-xl object-cover">
                <div class="flex-1 text-xs"><b>${GUEST_DATA.nombre} ${GUEST_DATA.apellido}</b><br><span class="text-indigo-600 font-bold">Titular</span></div>
                <span class="pms-badge badge-success">Validado</span>
            </div>`;
            container.insertAdjacentHTML('beforeend', titularHTML);
            previewContainer.insertAdjacentHTML('beforeend', `<img src="${GUEST_DATA.fotografia}" class="pax-photo">`);

            // Luego los acompañantes
            GUEST_DATA.companions.forEach(pax => {
                const itemHTML = `<div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-300"><i class="fas fa-user text-xs"></i></div>
                    <div class="flex-1 text-xs"><b>${pax.name}</b><br><span class="text-slate-400">${pax.rel}</span></div>
                    <div class="text-[10px] font-bold text-slate-400">${pax.id}</div>
                </div>`;
                container.insertAdjacentHTML('beforeend', itemHTML);
                previewContainer.insertAdjacentHTML('beforeend', `<div class="pax-photo bg-slate-100 flex items-center justify-center text-slate-300 border-2 border-white"><i class="fas fa-user text-[10px]"></i></div>`);
            });

            const total = GUEST_DATA.companions.length + 1;
            document.getElementById('pax-count-label').innerText = `${total} PAX Totales`;
        }

        // Cambiar pestañas del menú sidebar
        function switchSection(id) {
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + id).classList.add('active');
            ['resumen', 'cargos', 'pagos', 'room_service'].forEach(s => {
                document.getElementById('sec-' + s).classList.add('hidden');
            });
            document.getElementById('sec-' + id).classList.remove('hidden');
        }

        // Sincroniza los valores del objeto GUEST_DATA con los inputs del formulario
        function syncEditForm() {
            document.getElementById('edit-nombre').value = GUEST_DATA.nombre;
            document.getElementById('edit-apellido').value = GUEST_DATA.apellido;
            document.getElementById('edit-email').value = GUEST_DATA.email;
            document.getElementById('edit-telefono').value = GUEST_DATA.telefono;
            document.getElementById('edit-nacionalidad').value = GUEST_DATA.nacionalidad;
            document.getElementById('edit-empresa').value = GUEST_DATA.empresa;
            document.getElementById('edit-direccion').value = GUEST_DATA.direccion;
            document.getElementById('edit-ciudad').value = GUEST_DATA.ciudad;
            document.getElementById('edit-estado').value = GUEST_DATA.estado;
            document.getElementById('edit-pais').value = GUEST_DATA.pais;
            document.getElementById('edit-cp').value = GUEST_DATA.codigo_postal;
            document.getElementById('edit-genero').value = GUEST_DATA.genero;
            document.getElementById('edit-fecha-nac').value = GUEST_DATA.fecha_nacimiento;
            document.getElementById('edit-num-id').value = GUEST_DATA.numero_identificacion;
            document.getElementById('edit-notas').value = GUEST_DATA.notas;
            document.getElementById('edit-activo').checked = GUEST_DATA.activo === 1;
        }

        // Helper para inyectar texto de forma segura
        const safeSet = (id, val, attr = 'innerText') => {
            const el = document.getElementById(id);
            if (el) el[attr] = val;
        };

        // Actualiza toda la interfaz visual
        function updateUI() {
            safeSet('display-nombre', GUEST_DATA.nombre);
            safeSet('display-apellido', GUEST_DATA.apellido);
            safeSet('welcome-guest-name', GUEST_DATA.nombre + " " + GUEST_DATA.apellido);
            safeSet('audit-guest-name', GUEST_DATA.nombre + " " + GUEST_DATA.apellido);
            safeSet('display-nacionalidad', GUEST_DATA.nacionalidad);
            safeSet('display-telefono', GUEST_DATA.telefono);
            safeSet('display-email', GUEST_DATA.email);
            safeSet('display-empresa', GUEST_DATA.empresa);
            safeSet('display-plates', "PAÍS: " + GUEST_DATA.pais);
            
            const tbody = document.querySelector('#table-consumos tbody');
            if (tbody) {
                tbody.innerHTML = CONSUMOS.map(c => `<tr><td>${c.fecha}</td><td class="font-bold">${c.concepto}</td><td>${c.depto}</td><td class="text-right font-black">$${c.monto.toLocaleString()}</td></tr>`).join('');
                const sub = CONSUMOS.reduce((acc, c) => acc + c.monto, 0);
                const tax = sub * 0.19;
                const total = sub + tax;
                safeSet('audit-subtotal', "$" + sub.toLocaleString(undefined, {minimumFractionDigits: 2}));
                safeSet('audit-taxes', "$" + tax.toLocaleString(undefined, {minimumFractionDigits: 2}));
                safeSet('total_acumulado', "$" + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
                safeSet('saldo_final', "$" + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
            }
        }

        window.onload = () => { updateUI(); };

        // --- 1. MODELO DE DATOS SQL (Huesped Titular) ---
        

        let streamInstance = null; // Variable para controlar el hardware de la cámara

        // --- 2. CONTROLADORES DE MODAL ---
        function toggleEditModal(show) {
            const modal = document.getElementById('modal-edit-profile');
            if (show) {
                syncDataToForm();
                modal.classList.add('active');
            } else {
                stopCamera(); // Importante: Apagar cámara si se cierra el modal
                modal.classList.remove('active');
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

        // Función 2: Tomar Foto
        function takePhoto() {
            const video = document.getElementById('camera-stream');
            const canvas = document.getElementById('photo-canvas');
            const preview = document.getElementById('edit-preview-foto');
            const btnStart = document.getElementById('btn-start-camera');
            const btnTake = document.getElementById('btn-take-photo');

            // Configurar canvas para capturar el frame actual
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convertir frame a Base64 (Data URL)
            const imageData = canvas.toDataURL('image/png');
            
            // Actualizar vista previa y detener cámara
            preview.src = imageData;
            HUESPED.fotografia = imageData; // Guardar temporalmente en el objeto

            stopCamera(); // Liberar hardware
            
            // Revertir UI
            video.classList.add('hidden');
            preview.classList.remove('hidden');
            btnStart.classList.remove('hidden');
            btnTake.classList.add('hidden');
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

        // --- 4. LÓGICA DE NEGOCIO ---
        const safeSet = (id, val, attr = 'innerText') => {
            const el = document.getElementById(id);
            if (el) el[attr] = val;
        };

        function switchSection(id) {
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
            ['resumen', 'cargos', 'pagos'].forEach(s => {
                const el = document.getElementById('sec-' + s);
                if (el) el.classList.add('hidden');
            });
            const active = document.getElementById('sec-' + id);
            if (active) active.classList.remove('hidden');
        }

        function syncDataToForm() {
            document.getElementById('edit-nombre').value = HUESPED.nombre;
            document.getElementById('edit-apellido').value = HUESPED.apellido;
            document.getElementById('edit-genero').value = HUESPED.genero;
            document.getElementById('edit-fecha_nacimiento').value = HUESPED.fecha_nacimiento;
            document.getElementById('edit-tipo_identificacion_id').value = HUESPED.tipo_identificacion_id;
            document.getElementById('edit-numero_identificacion').value = HUESPED.numero_identificacion;
            document.getElementById('edit-email').value = HUESPED.email;
            document.getElementById('edit-telefono').value = HUESPED.telefono;
            document.getElementById('edit-direccion').value = HUESPED.direccion;
            document.getElementById('edit-ciudad').value = HUESPED.ciudad;
            document.getElementById('edit-pais').value = HUESPED.pais;
            document.getElementById('edit-notas').value = HUESPED.notas;
            document.getElementById('edit-activo').checked = (HUESPED.activo === 1);
            
            document.getElementById('edit-preview-foto').src = HUESPED.fotografia;
        }

        function saveProfile() {
            HUESPED.nombre = document.getElementById('edit-nombre').value;
            HUESPED.apellido = document.getElementById('edit-apellido').value;
            HUESPED.email = document.getElementById('edit-email').value;
            HUESPED.telefono = document.getElementById('edit-telefono').value;
            HUESPED.notas = document.getElementById('edit-notas').value;
            // La foto se guarda automáticamente al capturar en takePhoto()

            updateUI();
            toggleEditModal(false);
            alert("✓ Perfil actualizado y foto sincronizada.");
        }

        function updateUI() {
            safeSet('display-nombre-completo', HUESPED.nombre + " " + HUESPED.apellido);
            safeSet('audit-huesped-name', HUESPED.nombre + " " + HUESPED.apellido);
            safeSet('display-empresa-top', HUESPED.empresa);
            safeSet('resumen-foto', HUESPED.fotografia, 'src');
            safeSet('audit-foto-circle', HUESPED.fotografia, 'src');
            safeSet('display-id-num', HUESPED.numero_identificacion);
            safeSet('display-tel', HUESPED.telefono);
            safeSet('display-email', HUESPED.email);
            safeSet('display-ciudad-estado', HUESPED.ciudad + ", " + HUESPED.pais);
            safeSet('sync-time', new Date().toLocaleTimeString());
        }

        window.onload = updateUI;
  */