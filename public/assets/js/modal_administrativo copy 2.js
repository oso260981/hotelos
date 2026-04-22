let HUESPED = null;
let ACOMPANANTES = null;
let streamInstance = null; 

let CONSUMOS = [];


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

function cargarTiposID(selectedId = null){

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
        if(selectedId){
            select.value = selectedId;
        }

    })
    .catch(e => console.error("Error tipos ID", e));

}



/* =========================================================
   FUNCIONES DE CAMARA
========================================================= */

async function takePhoto() {

    try {

        const video   = document.getElementById('camera-stream');
        const canvas  = document.getElementById('photo-canvas');
        const img     = document.getElementById('edit-preview-foto');

        const btnStart = document.getElementById('btn-start-camera');
        const btnTake  = document.getElementById('btn-take-photo');

        if (!video || video.videoWidth === 0) {
            alert("La cámara no está lista");
            return;
        }

        // ===== CAPTURA =====
        canvas.width  = video.videoWidth;
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


function loadFoto(foto){
    const img = document.getElementById("edit-preview-foto");
    if(foto){
        img.dataset.file = foto;
        img.src = base_url + "uploads/fotos/" + foto;
        img.classList.remove("hidden");
    }else{
        img.dataset.file = null;
        img.src = base_url + "assets/img/user-placeholder.png";
    }
}


/* =========================================================
   GUARDAR FOTOS HUÉSPED 
   ========================================================= */
async function subirFoto(base64){

    try{

        const res = await fetch(base_url + "media/subir-foto",{
            method:"POST",
            headers:{ "Content-Type":"application/json" },
            body: JSON.stringify({
                foto: base64
            })
        });

        const resp = await res.json();

        if(resp.ok){
            return resp.file;   // ⭐ nombre archivo
        }else{
            alert("Error subiendo foto");
            return null;
        }

    }catch(e){
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
    document.getElementById('edit-preview-foto').src ='"https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"'
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












function cargarConsumos(registro_id) {

    fetch(base_url + "registropagos/registros/" + registro_id + "/pagos")
        .then(r => r.json())
        .then(data => {

            CONSUMOS = data;  // ✅ guardar data cruda
            console.log("CONSUMOS cargados:", CONSUMOS);
            updateUI(); // 👉 aquí haces transformaciones
        })
        .catch(e => console.error("Error cargar consumos", e));

}


         // --- BASE DE DATOS LOCAL SIMULADA ---
     /*    const CONSUMOS = [
            { fecha: '21/03 - 14:20', concepto: 'Hospedaje - Noche 1', depto: 'SISTEMA', estado: 'Cargado', monto: 2500 },
            { fecha: '21/03 - 18:45', concepto: 'Room Service - Cena', depto: 'RESTAURANTE', estado: 'Pendiente', monto: 450 },
            { fecha: '21/03 - 19:00', concepto: 'Minibar - Refrescos', depto: 'AMA DE LLAVES', estado: 'Cargado', monto: 120 }
        ];  */

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

    // =========================
    // TABLA CONSUMOS (LEDGER)
    // =========================
    const tbodyConsumos = document.querySelector('#table-consumos tbody');
    tbodyConsumos.innerHTML = '';

    let subHospedaje = 0;
    let subExtras = 0;
    let totalPagos = 0;

    CONSUMOS.forEach(item => {

        const tr = document.createElement('tr');

        // ⭐ fecha
        const f = new Date(item.hora_pago);
        const fecha = f.toLocaleDateString('es-MX',{day:'2-digit',month:'2-digit'}) +
                      ' - ' +
                      f.toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'});

        // ⭐ estado
        const estado = item.estado || (
            item.tipo_movimiento === 'PAGO' ? 'Pagado' : 'Cargado'
        );

       /*  // ⭐ badge dinámico
        let badgeClass = 'badge-gray';

        if(item.tipo_movimiento === 'PAGO'){
            badgeClass = 'badge-green';
        } else if(item.tipo_movimiento === 'CARGO'){
            badgeClass = 'badge-red';
        } else if(item.tipo_movimiento === 'AJUSTE'){
            badgeClass = 'badge-yellow';
        } */

        // ⭐ depto
        const depto = item.sistema || 'SISTEMA';

        // ⭐ monto
        const monto = Number(item.monto || 0);

         const badgeClass = item.estado === 'Cargado' ? 'badge-success' : 'badge-warning';

        tr.innerHTML = `
            <td>${fecha}</td>
            <td class="font-bold">${item.concepto}</td>
            <td>${depto}</td>
            <td><span class="pms-badge ${badgeClass}">${estado}</span></td>
            <td class="text-right font-black">$${monto.toLocaleString('es-MX')}</td>
        `;

        tbodyConsumos.appendChild(tr);

        // =========================
        // CÁLCULOS FINANCIEROS
        // =========================

        if(item.tipo_movimiento === 'CARGO'){

            if (item.concepto?.toLowerCase().includes('hospedaje')) {
                subHospedaje += monto;
            } else {
                subExtras += monto;
            }

        } else if(item.tipo_movimiento === 'PAGO'){

            totalPagos += monto;

        } else if(item.tipo_movimiento === 'AJUSTE'){

            subExtras += monto; // puede ser negativo o positivo
        }
    });

    // =========================
    // TABLA PAGOS (OPCIONAL UI)
    // =========================
    const tbodyPagos = document.querySelector('#table-pagos tbody');
    tbodyPagos.innerHTML = '';

    CONSUMOS
        .filter(i => i.tipo_movimiento === 'PAGO')
        .forEach(pago => {

            const f = new Date(pago.hora_pago);

            const fecha = f.toLocaleDateString('es-MX') + ' ' +
                          f.toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'});

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${fecha}</td>
                <td class="font-bold uppercase text-[10px]">${pago.forma_pago || 'PAGO'}</td>
                <td class="text-slate-400 italic">${pago.referencia_pago || ''}</td>
                <td class="text-right font-black text-emerald-600">$${Number(pago.monto).toLocaleString('es-MX')}</td>
            `;

            tbodyPagos.appendChild(tr);
        });

    // =========================
    // TOTALES (PMS REAL)
    // =========================
    const totalCargosSinImpuestos = subHospedaje + subExtras;

    // ⚠️ aquí puedes luego usar IVA real del sistema
    const impuestos = totalCargosSinImpuestos * 0.16;

    const totalBruto = totalCargosSinImpuestos + impuestos;

    const saldoFinal = totalBruto - totalPagos;

    document.getElementById('audit-hospedaje').innerText =
        "$" + subHospedaje.toLocaleString('es-MX');

    document.getElementById('audit-extras').innerText =
        "$" + subExtras.toLocaleString('es-MX');

    document.getElementById('audit-taxes').innerText =
        "$" + impuestos.toLocaleString('es-MX',{minimumFractionDigits:2});

    document.getElementById('total_acumulado').innerText =
        "$" + totalBruto.toLocaleString('es-MX',{minimumFractionDigits:2});

    document.getElementById('sidebar-total-pagos').innerText =
        "-$" + totalPagos.toLocaleString('es-MX');

    document.getElementById('saldo_final').innerText =
        "$" + saldoFinal.toLocaleString('es-MX',{minimumFractionDigits:2});

    updateTime();
}
