let CURRENT_GUEST_ID = null;
let searchTimer = null;
let CURRENT_FLOOR = null;
let CURRENT_STREAM = null;
let PAX_CAMERA_STREAM = null;

/*************************************************
 * 🔹 ESTADO GLOBAL (SIN CONFLICTOS)
 *************************************************/
const PAX_STATE = {
    titular: {},
    acompanantes: [],
    editingId: null,
    room: {
        numero: null,
        tipo: null,
        capacidad: null,
        extra: null
    }
};

/* =========================================================
   ABRIR MODAL HABITACION
========================================================= */
function abrirHabitacion(room = null) {

    const modal = document.getElementById("master-modal-container");
    cargarTiposreservaID();
    cargarPisos();
    cargarTiposHabitacion();   // ⭐ nuevo
    cargarTiposEstadia();
    cargarHabitacionesPorPiso();
    initDates();
    initWizard();
    cargarFormasPago();
     cargarImpuestos();
   // selectRoom(room);

    if (!modal) {
        console.error("No existe modal");
        return;
    }

    modal.classList.add("show");
}




/*  selectRoom(room); */

   /*  // 🔥 aquí directo
    if (room && typeof selectRoom === 'function') {
        setTimeout(() => selectRoom(room), 300);
    } */


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

                select.innerHTML += `
                <option value="${row.id}">
                    ${row.label}
                </option>
            `;

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
                select.innerHTML += `
                <option value="${t.id}">
                    ${t.nombre}
                </option>`;
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
                select.innerHTML += `
                <option value="${row.id}">
                    ${row.label}
                </option>
            `;
            });

        })
        .catch(e => console.error(e));
}

async function handleSearch() {

    const input = document.getElementById('guestSearch').value.trim();
    const resDiv = document.getElementById('searchResults');

    clearTimeout(searchTimer);

    // 🔥 detectar números (para teléfono)
    const soloNumeros = input.replace(/\D/g, '');

    // 🔥 regla PMS
    if (
        input.length < 8 &&   // mínimo 8 letras
        soloNumeros.length < 4 // o mínimo 4 números
    ) {
        resDiv.innerHTML = '';
        resDiv.classList.add('hidden');
        return;
    }

    searchTimer = setTimeout(() => {

        // ⭐ loader inmediato UX recepción
        resDiv.innerHTML =
            `<div class="p-4 text-slate-400 text-xs font-bold">
            Buscando huésped...
        </div>`;
        resDiv.classList.remove('hidden');

        fetch(base_url + "pasajeros/buscar_cliente?q=" + encodeURIComponent(input))
            .then(r => r.json())
            .then(data => {

                resDiv.innerHTML = '';

                if (!data || data.length === 0) {
                    resDiv.innerHTML =
                        `<div class="p-4 text-slate-400 text-xs font-bold">
                    Sin resultados
                </div>`;
                    return;
                }

                data.forEach(g => {

                    const d = document.createElement('div');

                    d.className =
                        'p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-600 hover:shadow-lg transition-all flex items-center gap-4';

                    d.innerHTML = `
                    <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p class="font-black text-[11px] text-slate-900 uppercase">
                            ${g.nombre} ${g.apellido ?? ''}
                        </p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">
                            ${g.numero_identificacion ?? ''} | ${g.nacionalidad ?? ''}
                        </p>
                    </div>
                `;

                    d.onclick = () => {
                        populate(g);
                        resDiv.classList.add('hidden');
                    };

                    resDiv.appendChild(d);

                });

            })
            .catch(e => {
                console.error(e);
                resDiv.innerHTML =
                    `<div class="p-4 text-red-400 text-xs font-bold">
                Error de búsqueda
            </div>`;
            });

    }, 180);
}

function populate(g) {

    CURRENT_GUEST_ID = g.id;   // 🔥 muy importante para flujo PMS

    document.getElementById('first_name').value =
        g.nombre ?? "";

    document.getElementById('last_name').value =
        g.apellido ?? "";

    document.getElementById('main_address').value =
        g.direccion ?? "";

    document.getElementById('main_city').value =
        g.ciudad ?? "";

    document.getElementById('main_state').value =
        g.estado ?? "";

    document.getElementById('main_zip').value =
        g.codigo_postal ?? "";

    document.getElementById('main_nationality').value =
        g.nacionalidad ?? "";

    document.getElementById('main_email').value =
        g.email ?? "";

    document.getElementById('main_phone').value =
        g.telefono ?? "";

    document.getElementById('main_dob').value =
        g.fecha_nacimiento ?? "";

    document.getElementById('main_gender').value =
        g.genero ?? "M";

    document.getElementById('id_type').value =
        g.tipo_identificacion_id ?? "";

    document.getElementById('id_number').value =
        g.numero_identificacion ?? "";

    document
        .getElementById('frequent-badge')
        .classList.remove('hidden');

    syncSummary();
}

function soloNumeros(input) {
    input.value = input.value.replace(/\D/g, '');
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

    const info = document.getElementById("capacidad_info");
    const extra = document.getElementById("capacidad_extra");

    if (!info || !extra) return;

    // 🔒 validar data
    if (!data) {
        info.innerText = "--";
        extra.innerText = "";
        return;
    }

    const numero = data.numero ?? "--";
    const tipo = data.tipo ?? "--";
    const capacidad = data.capacidad ?? 0;
    const extraPrecio = data.extra ?? 0;

    // 🔥 texto principal
    info.innerText = `${tipo} #${numero} - Máx ${capacidad} Pers.`;

    // 🔥 texto extra
    extra.innerText = extraPrecio > 0
        ? `Cargo extra por exceso: $${extraPrecio} p/n`
        : "";
}


/* =========================================================
   GUARDAR TODO (HUÉSPED + REGISTRO)
   FLUJO PMS RECEPCIÓN
   ========================================================= */
async function guardarRerservacion() {

    try {

        showLoader("Guardando información...");

        if (!selectedRoom) throw new Error("Debe asignar una habitación");

        const nombre = document.getElementById('first_name').value;
        if (!nombre) throw new Error("No se ha registrado un titular");

        // 1️⃣ huésped
        const respHuesped = await guardarHuespedAsync();
        if (!respHuesped?.ok) throw new Error("Error guardando huésped");

        // 2️⃣ registro
        const respRegistro = await guardarRegistroAsync();
        if (!respRegistro?.ok) throw new Error("Error guardando registro");

        // 3️⃣ habitación limpia
        await marcarHabitacionLimpiaAsync(CURRENT_ROOM_ID);

        // 4️⃣ acompañantes
        await guardarAcompanantesAsync();

        // 5️⃣ cargos 🔥 CRÍTICO
        const respCargo = await guardarCargoAsync();
        if (!respCargo) throw new Error("Error guardando cargos");

        // 6️⃣ pago 🔥 SOLO SI HAY CARGOS
        const respPago = await agregarPago();
      //  if (!respPago?.ok) throw new Error("Error registrando pago");

        // 7️⃣ vehículo
        await registrarVehiculo();

        hideLoader();

        showToast("Proceso completado correctamente");

      //  imprimirTicket();

        await cerrarHabitacion();

    } catch (e) {

        console.error("❌ ERROR FLOW:", e);

        hideLoader();

        showToast(e.message || "Error en el proceso");
    }
}

/* =========================================================
   GUARDAR HUÉSPED (CREAR / ACTUALIZAR)
   ========================================================= */
function guardarHuespedAsync() {

    return new Promise((resolve, reject) => {

        const payload = {

            id: CURRENT_GUEST_ID,   // null o 0 si es nuevo


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

                console.log("RESP", resp);

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


async function takePhotoreserva(videoId, imgId) {

    const video = document.getElementById(videoId);
    const img = document.getElementById(imgId);

    if (!video || !img) {
        console.error("Video o IMG no existen", videoId, imgId);
        return;
    }

    // ⭐ esperar a que el video tenga dimensiones reales
    if (video.readyState < 2) {
        await new Promise(resolve => {
            video.onloadeddata = () => resolve();
        });
    }

    const width = video.videoWidth;
    const height = video.videoHeight;

    if (!width || !height) {
        console.error("No se pudo obtener frame de video");
        return;
    }

    // ⭐ canvas dinámico
    const canvas = document.createElement("canvas");
    canvas.width = width;
    canvas.height = height;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, width, height);

    const base64 = canvas.toDataURL("image/png");

    // preview
    img.src = base64;
    img.classList.remove("hidden");
    video.classList.add("hidden");

    // ⭐ subir foto
    const fileName = await subirFoto(base64);

    if (fileName) {
        img.dataset.file = fileName;
    }

    // ⭐ detener cámara
    stopCamerareseva(videoId);

}

/* =========================================================
   CALCULO DE NOCHES 
   ========================================================= */

function calcularNoches() {
    // 1. Obtener los valores de los inputs de fecha
    const fechaEntrada = document.getElementById('checkin_date').value;
    const fechaSalida = document.getElementById('checkout_date').value;
    const inputNoches = document.getElementById('summary_nights');

    // 2. Verificar que ambos campos tengan fecha
    if (!fechaEntrada || !fechaSalida) {
        inputNoches.value = 0;
        return;
    }

    // 3. Convertir a objetos Date
    const inicio = new Date(fechaEntrada);
    const fin = new Date(fechaSalida);

    // 4. Calcular diferencia en milisegundos
    const diffMilisegundos = fin - inicio;

    // 5. Convertir a días (86,400,000 ms en un día)
    // Usamos Math.ceil o Math.round dependiendo de tu política de hotel
    // (Por lo general, si pasan de la hora de check-out, se cuenta la noche)
    let totalNoches = Math.ceil(diffMilisegundos / (1000 * 60 * 60 * 24));

    // 6. Asignar el resultado al input (que luego leerá guardarRegistroAsync)
    if (totalNoches > 0) {
        inputNoches.value = totalNoches;
    } else {
        inputNoches.value = 0; // Evita que se guarden números negativos
    }
}


/* =========================================================
   GUARDAR REGISTRO (CHECK-IN / ACTUALIZACIÓN HABITACIÓN)
   ========================================================= */

let CURRENT_REGISTRO_ID = null;


/* =========================================================
   GUARDAR REGISTRO (CHECK-IN / ACTUALIZACIÓN)
   ========================================================= */
function guardarRegistroAsync() {

    if (!validarPago()) return;

    return new Promise((resolve, reject) => {

        const extra = parseInt(document.getElementById('label_extra').value) || 0;
        const anticipo = parseFloat(document.getElementById('anticipo_monto').value) || 0;


        const d1 = new Date(document.getElementById('checkin_date').value);
        const d2 = new Date(document.getElementById('checkout_date').value);
        const tasaISH = window.TASA_ISH || 0;
        const nights = Math.max(1, (d2 - d1) / (1000 * 60 * 60 * 24));
        const precio_base = selectedRoom.price;
        const sub = selectedRoom.price * nights;
        const ext = (extra * EXTRA_FEE * nights);
        const base = sub + ext;
        const iva = base * 0.16;
        const ish = base * (tasaISH/100);

        
        const total = base + iva + ish;
        const saldo = total - anticipo;

       const lblAdults = document.getElementById('label_adults').textContent;
       const lblKids   = document.getElementById('label_kids').textContent;
       const lblExtra  = document.getElementById('label_extra').textContent;



        const payload = {

            id: CURRENT_REGISTRO_ID,
            huesped_id: CURRENT_GUEST_ID,
            habitacion_id: CURRENT_ROOM_ID,
            hora_entrada: document.getElementById('checkin_date').value,
            hora_salida: document.getElementById('checkout_date').value,
            noches: nights,
            tipo_estadia_id: document.getElementById('main_estadia').value,
            adultos: lblAdults,
            niños: lblKids,
            num_personas_ext: lblExtra,
            forma_pago_id: document.getElementById('main_formaPago').value,
            precio: base,
            precio_base: precio_base,
            pago_adicional: ext,
            iva: iva,
            ish: ish,
            total: total,

        };

        fetch(base_url + "reservacion/guardar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(resp => {

                console.log("RESP", resp);

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
  GUARDAR ACOMPAÑANTES (CHECK-IN / ACTUALIZACIÓN)
====================================================== */

/* function guardarAcompanantesAsync() {
    return new Promise((resolve, reject) => {

        const list = document.getElementById('companionList');
        const rows = list.querySelectorAll('.group'); // Selecciona cada contenedor de acompañante
        const acompanantes = [];

        // 1. Recolectamos los datos de cada fila dinámica
        rows.forEach((row) => {
            const nombreCompleto = row.querySelector('input[type="text"]').value;
            const parentesco = row.querySelector('select').value;
            const esMenor = row.querySelector('input[type="checkbox"]').checked ? 1 : 0;
            const foto = row.querySelector(".comp-photo")?.dataset.file || null;

            // Solo agregamos si tiene nombre para evitar registros vacíos
            if (nombreCompleto.trim() !== "") {
                acompanantes.push({
                    registro_id: CURRENT_REGISTRO_ID, // Usamos tu variable global
                    nombre: nombreCompleto,
                    apellido: "", // Opcional: podrías separar el nombre si lo requieres
                    parentesco: parentesco,
                    es_menor: esMenor,
                    fotografia: foto,
                    estado_estancia: 'Hospedado',
                    // La foto se puede extraer si la necesitas:
                    // foto: row.querySelector('img').src 
                });
            }
        });

        // Si no hay acompañantes, resolvemos de inmediato
        if (acompanantes.length === 0) {
            return resolve({ ok: true, msg: "Sin acompañantes para guardar" });
        }

        const payload = {
            registro_id: CURRENT_REGISTRO_ID,
            acompanantes: acompanantes
        };

        // 2. Enviamos al servidor
        fetch(base_url + "reservacion/guardar-acompanantes", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(resp => {
                console.log("RESP ACOMPAÑANTES", resp);

                if (resp.ok) {
                    resolve(resp);
                } else {
                    reject(resp.msg);
                }
            })
            .catch(e => reject(e));
    });
} */


function guardarAcompanantesAsync() {

    return new Promise((resolve, reject) => {

        const acompanantes = (PAX_STATE.acompanantes || []).map(p => {

            return {
                registro_id: CURRENT_REGISTRO_ID,
                nombre: p.nombre,
                apellido: p.apellido || "",
                parentesco: p.tipo || "",
                es_menor: p.es_menor ? 1 : 0,
                es_extra: p.es_extra ? 1 : 0,
                fotografia: p.foto || null,
                estado_estancia: 'Hospedado'
            };

        });

        // 🔥 SI NO HAY ACOMPAÑANTES
        if (acompanantes.length === 0) {
            return resolve({ ok: true, msg: "Sin acompañantes para guardar" });
        }

        const payload = {
            registro_id: CURRENT_REGISTRO_ID,
            acompanantes: acompanantes
        };

        fetch(base_url + "reservacion/guardar-acompanantes", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(resp => {

                console.log("✅ RESP ACOMPAÑANTES", resp);

                if (resp.ok) resolve(resp);
                else reject(resp.msg);

            })
            .catch(err => reject(err));

    });
}


/* ======================================================
   MARCAR HABITACIÓN LIMPIA (ASYNC)
====================================================== */
function marcarHabitacionLimpiaAsync(habitacionId) {

    return fetch(base_url + "habitaciones/marcar-limpia", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            habitacion_id: habitacionId
        })
    })
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                throw resp.msg;
            }

            return resp;
        });

}

/* ======================================================
   AGREGAR PAGO A REGISTRO
====================================================== */
async function agregarPago() {

    const totalEstancia = parseFloat((document.getElementById('summary_saldo')?.value || "0").replace(/[$,]/g, '')) || 0;
    const montoPago =     parseFloat((document.getElementById('anticipo_monto')?.value || "0").replace(/[$,]/g, '')) || 0;

    let concepto = "Hospedaje";

    // ⭐ si liquida todo
    if (montoPago >= totalEstancia) {
        concepto = "Pago total de estancia";
    }


    const payload = {

        registro_id: CURRENT_REGISTRO_ID,
        forma_pago_id: document.getElementById('main_formaPago').value,
        // ⭐ nuevos     
        referencia_pago : document.getElementById('referencia_pago')?.value.trim() || null,
        banco: document.getElementById('banco_pago')?.value.trim() || null,
        monto: document.getElementById('anticipo_monto').value,
        tipo_movimiento:'Pago',
        estado:'Confirmado',
        sistema:'Recepción',
        concepto: concepto,
        observaciones: concepto
    };

    console.log("PAGO", payload);

    try {

        const res = await fetch(base_url + "reservacion/guardar-pago", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const resp = await res.json();

        if (resp.ok) {

          //  alert("Pago registrado");

            // cargarPagos(); // 🔥 refrescar lista

        } else {
            alert(resp.msg);
        }

    } catch (e) {
        console.error(e);
    }

}


async function guardarCargoAsync() {

    try {

        if (!CURRENT_REGISTRO_ID) throw "No hay registro activo";

        // =========================
        // 🔢 DATOS BASE
        // =========================
        const extra = parseInt(document.getElementById('label_extra').textContent.trim()) || 0;

        const checkinVal  = document.getElementById('checkin_date').value;
        const checkoutVal = document.getElementById('checkout_date').value;

        if (!checkinVal || !checkoutVal) {
            throw "Fechas inválidas";
        }

        const d1 = new Date(checkinVal);
        const d2 = new Date(checkoutVal);

        const nights = Math.max(1, Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24)));

        if (!selectedRoom || !selectedRoom.price) {
            throw "Habitación no válida";
        }

        // =========================
        // 🏨 HOSPEDAJE
        // =========================
        const respHosp = await fetch(base_url + "reservacion/guardar-cargo", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                registro_id: CURRENT_REGISTRO_ID,
                cantidad: nights,
                precio_unitario: selectedRoom.price,
                tipo: "Hospedaje",
                concepto: "Habitación"
            })
        });

        if (!respHosp.ok) {
            throw "Error guardando hospedaje";
        }

        // =========================
        // 👥 PERSONAS EXTRA (UNA POR REGISTRO)
        // =========================
        if (extra > 0) {

            for (let i = 0; i < extra; i++) {

                try {

                    const respExtra = await fetch(base_url + "reservacion/guardar-cargo", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            registro_id: CURRENT_REGISTRO_ID,
                            cantidad: nights,
                            precio_unitario: EXTRA_FEE, // 🔥 por persona
                            tipo: "Persona Extra",
                            concepto: `Persona adicional ${i + 1}`
                        })
                    });

                    if (!respExtra.ok) {
                        console.error(`Error en persona extra ${i + 1}`);
                    }

                } catch (err) {
                    console.error(`Error guardando persona extra ${i + 1}`, err);
                }
            }
        }

        return true;

    } catch (error) {

        console.error("❌ Error en guardarCargoAsync:", error);

        alert(typeof error === "string" ? error : "Error al guardar cargos");

        return false;
    }
}


/* ======================================================
   REGISTRAR VEHICULO
====================================================== */
async function registrarVehiculo() {

    const placa = document.getElementById('car_plates')?.value.trim();

    // 🚫 NO ejecutar si está vacío
    if (!placa) {
        console.log("Sin placa, no se registra vehículo");
        return;
    }

    const payload = {
        registro_id: CURRENT_REGISTRO_ID,
        placa: placa
    };

    console.log("VEHICULO", payload);

    try {

        const res = await fetch(base_url + "reservacion/guardar-vehiculo", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const resp = await res.json();

        if (resp.ok) {
            // cargarVehiculos();
        } else {
            alert(resp.msg);
        }

    } catch (e) {
        console.error(e);
    }
}


/* =========================================================
   CHECKOUT HABITACIÓN (REGISTRA SALIDA AUTOMÁTICA)
   ========================================================= */
function checkoutHabitacion(registroId, habitacionId) {

    const payload = {
        registro_id: registroId,
        habitacion_id: habitacionId
    };

    fetch(base_url + "reservacion/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(resp => {

            if (resp.ok) {
                alert("Habitación liberada");
                // cargarHabitaciones();
            }

        })
        .catch(e => console.error(e));
}

/* =========================================================
   AGREGAR PAGO EXTRA AL REGISTRO
   ========================================================= */
function pagoExtra(registroId) {

    const monto = prompt("Monto adicional:");

    if (!monto) return;

    const payload = {
        registro_id: registroId,
        monto: monto
    };

    fetch(base_url + "reservacion/pago-extra", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(resp => {

            if (resp.ok) {
                //alert("Pago agregado");
                // cargarHabitaciones();
            }

        })
        .catch(e => console.error(e));
}


function cargarPisos() {

    fetch(base_url + "catalogos/listar_pisos")
        .then(r => r.json())
        .then(data => {



            const cont = document.getElementById("floorContainer");
            cont.innerHTML = '';

            data.forEach((p, i) => {

                const btn = document.createElement("button");

                btn.className =
                    "pill-btn floor-btn" + (i === 0 ? " active" : "");

                btn.innerText = p.Piso;

                btn.onclick = () => switchFloor(p.Piso);

                cont.appendChild(btn);

                if (i === 0) {
                    CURRENT_FLOOR = p.Piso;
                }

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

            // ⭐ botón TODOS primero
            const btnAll = document.createElement("button");
            btnAll.className = "pill-btn type-btn active";
            btnAll.innerText = "Todos";
            btnAll.onclick = () => switchType("Todos");
            cont.appendChild(btnAll);

            data.forEach(t => {

                const btn = document.createElement("button");

                btn.className = "pill-btn type-btn";
                btn.innerText = t.nombre;

                btn.onclick = () => switchType(t.nombre);

                cont.appendChild(btn);

            });

        })
        .catch(e => console.error("Error tipos habitacion", e));
}




// --- MASTER PMS MOTOR ---
const ROOMS = [];
const floors = ['PB', '1', '2'];
const types = ['Sencilla', 'Doble', 'Suite'];
const prices = [850, 1300, 2500];
const caps = [2, 4, 6];
let EXTRA_FEE = 0;

/* const GUESTS_DB = [{
        first_name: "ISRAEL",
        last_name: "DE LOS SANTOS",
        id_number: "INE-99228811",
        main_address: "AV. CENTRAL 100",
        main_city: "CDMX",
        main_state: "CDMX",
        main_zip: "06000",
        main_nationality: "Mexicana",
        main_email: "israel@pms.com",
        main_phone: "55 1234 5678",
        main_dob: "1990-01-01",
        main_gender: "Masculino"
    },
    {
        first_name: "MARIO ALBERTO",
        last_name: "DOMINGUEZ",
        id_number: "PAS-77665544",
        main_address: "PASEO DE LA REFORMA 222",
        main_city: "CDMX",
        main_state: "CDMX",
        main_zip: "06600",
        main_nationality: "Mexicana",
        main_email: "mario@pms.com",
        main_phone: "55 8877 6655",
        main_dob: "1985-05-15",
        main_gender: "Masculino"
    }
]; */

let selectedRoom = null;
let currentFloor = 'PB';
let currentType = 'Todos';

window.onload = () => {
    cargarHabitacionesPorPiso();
    renderRooms();
    initDates();
    const f = "AUD-" + Math.random().toString(36).substr(2, 6).toUpperCase();
    document.getElementById('display_folio').innerText = f;
    document.getElementById('summary_folio_display').innerText = f;
    document.getElementById('modal_date').innerText = new Date().toLocaleDateString('es-MX');
    updateTotal();
};

function initRooms() {
    /*  ROOMS.length = 0;
     floors.forEach((f) => {
         for (let i = 1; i <= 15; i++) {
             let typeIdx = i > 12 ? 2 : (i > 8 ? 1 : 0);
             const roomId = f === 'PB' ? (i < 10 ? '0' + i : i) : f + (i < 10 ? '0' + i : i);
             ROOMS.push({
                 id: roomId.toString(),
                 floor: f,
                 type: types[typeIdx],
                 price: prices[typeIdx],
                 capacity: caps[typeIdx]
             });
         }
     }); */
}

function cargarHabitacionesPorPiso() {

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

            renderRooms();

        });
}


function initDates() {
    const cin = document.getElementById('checkin_date');
    const cout = document.getElementById('checkout_date');
    if (cin && cout) {
        const d = new Date().toISOString().split('T')[0];
        const d2 = new Date(Date.now() + 86400000).toISOString().split('T')[0];
        cin.value = d;
        cout.value = d2;
    }
}

function toggleMasterModal(show) {
    /* const m = document.getElementById('master-modal-container');
    const d = document.getElementById('dashboard-view');
    const p = document.getElementById('pms-modal'); */
    /* if (show) {
        m.classList.remove('hidden');
        d.classList.add('hidden');
        setTimeout(() => p.classList.remove('scale-95'), 10);
    } else {
        p.classList.add('scale-95');
        setTimeout(() => {
            m.classList.add('hidden');
            d.classList.remove('hidden');
        }, 200);
    } */
    cerrarHabitacion();
}

/* function switchTabreserva(tabId) {

     const tabActual = getCurrentTab(); // tú defines esto

    // 🔥 validar antes de cambiar
    if (!puedeAvanzar(tabActual, tabDestino)) {
        return; // ❌ bloquea cambio
    }


    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-link-' + tabId).classList.add('active');
    ['huesped', 'habitacion', 'logistica', 'pago'].forEach(id => {
        document.getElementById('content-' + id).classList.add('hidden');
    });
    document.getElementById('content-' + tabId).classList.remove('hidden');
}
 */


function validarHabitacion() {
    if (!DATA.currentRoom) {
        mostrarError("Selecciona una habitación");
        return false;
    }
    return true;
}

function mostrarError(msg) {
    // puedes cambiar esto por toast bonito
    alert(msg);
}



const steps = ['huesped', 'habitacion', 'logistica', 'pago'];
let currentStep = 0;

function goToStep(stepName) {
    const nextIndex = steps.indexOf(stepName);

    // 🔥 Validar antes de avanzar
    if (nextIndex > currentStep && !validateStep(steps[currentStep])) {
        return;
    }

    currentStep = nextIndex;

    updateTabs();
    updateProgress();
}


function validateStep(step) {

    const fields = {
        nombre: document.getElementById("first_name"),
        apellido: document.getElementById("last_name"),
        telefono: document.getElementById("main_phone"),
        estadia: document.getElementById("main_estadia")
    };

    renderPaxGrid();

    // limpiar errores previos
    Object.values(fields).forEach(f => f?.classList.remove('input-error'));

    let errores = [];

    switch (step) {

        case 'huesped':

            if (!fields.nombre.value.trim()) {
                errores.push({ field: fields.nombre, msg: "Falta nombre" });
            }

            if (!fields.apellido.value.trim()) {
                errores.push({ field: fields.apellido, msg: "Falta apellido" });
            }

            if (!fields.telefono.value.trim()) {
                errores.push({ field: fields.telefono, msg: "Falta teléfono" });
            }

            break;

        case 'habitacion':

            if (!CURRENT_ROOM_ID) {
                showToast("Selecciona una habitación");
                return false;
            }

            break;

        case 'logistica':

            if (!fields.estadia.value.trim()) {
                errores.push({ field: fields.estadia, msg: "Selecciona fechas de estadía" });
            }

            break;
    }

    // 🔥 si hay errores
    if (errores.length > 0) {

        // resaltar campos
        errores.forEach(e => e.field.classList.add('input-error'));

        // enfocar el primero
        errores[0].field.focus();

        // mensaje claro
        showToast(errores[0].msg);

        return false;
    }

    return true;
}


function updateTabs() {

    document.querySelectorAll('.nav-tab').forEach((tab, index) => {
        tab.classList.remove('active');

        if (index < currentStep) {
            tab.classList.add('completed');
        }

        if (index === currentStep) {
            tab.classList.add('active');
        }

        if (index <= currentStep + 1) {
            tab.classList.remove('disabled');
        }
    });

    // 👉 Mostrar contenido
    showStepContent(steps[currentStep]);
}


function updateProgress() {
    const percent = ((currentStep) / (steps.length - 1)) * 100;
    document.getElementById('wizard-bar').style.width = percent + '%';
}

/* function showStepContent(step) {
    document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
    document.getElementById('step-' + step).style.display = 'block';
}
 */


function showStepContent(step) {

    document.querySelectorAll('.step-content').forEach(el => {
        el.style.display = 'none';
    });
    const target = document.getElementById('step-' + step);
    if (!target) {
        console.warn("⚠️ step no encontrado:", step);
        return;
    }
    target.style.display = 'block';
}

function showToast(msg) {
    console.log(msg); // aquí puedes meter SweetAlert o toast bonito
}


document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const step = tab.dataset.step;
        goToStep(step);
    });
});


function checkHuespedStep() {
    const nombre = document.getElementById("first_name").value.trim();
    const tipo = document.getElementById("id_type").value;

    const tab = document.querySelector('[data-step="habitacion"]');

    if (nombre && tipo) {
        tab.classList.remove('disabled');
    } else {
        tab.classList.add('disabled');
    }
}

function initWizard() {
    currentStep = 0;

    // 🔥 ocultar todos
    document.querySelectorAll('.step-content')
        .forEach(el => el.style.display = 'none');

    // 🔥 mostrar SOLO el primero
    const first = document.getElementById('step-' + steps[currentStep]);

    if (first) {
        first.style.display = 'block';
    } else {
        console.error("❌ No existe step inicial:", steps[currentStep]);
    }

    updateTabs();
    updateProgress();
}


function renderRooms() {
    const container = document.getElementById('roomContainer');
    if (!container) return;
    container.innerHTML = '';
    const filtered = ROOMS.filter(r => r.floor === currentFloor && (currentType === 'Todos' || r.type === currentType));
    filtered.forEach(room => {
        const isSelected = selectedRoom && selectedRoom.id === room.id;
        const card = document.createElement('div');
        card.className = `room-card animate-fade-in ${isSelected ? 'selected-room' : ''}`;
        card.onclick = () => {
            selectRoom(room);
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
let CURRENT_ROOM_ID = null;
let CURRENT_ROOM_OBJ = null;

/* ======================================================
   SELECCIONAR HABITACIÓN
   GUARDA ID GLOBAL PARA REGISTRO
====================================================== */
function selectRoom(room) {

    // 🔥 guardar objeto completo
    CURRENT_ROOM_OBJ = room;

    // 🔥 guardar SOLO ID para el registro
    CURRENT_ROOM_ID = room.room_number;

    console.log("ROOM SELECTED:", CURRENT_ROOM_ID);

    // lógica UI existente
    selectedRoom = room;
    EXTRA_FEE = room.extra_price || 0;

    document.getElementById('grid-controls').classList.add('hidden');
    document.getElementById('selected-room-info').classList.remove('hidden');

    document.getElementById('selected-room-text').innerText =
        `#${room.numero ?? room.id} - ${room.type}`;

   /*  document.getElementById('extraPriceLabel').innerText =
        `Cargo: $${room.extra_price.toLocaleString()} / Noche`; */


    document.getElementById('capacidad_extra').innerText =
        `Cargo: $${room.extra_price.toLocaleString()} / Noche`;     


    renderCapacidadUnidad({
        numero: room.id,
        tipo: room.type,
        capacidad: room.capacity,
        extra: room.extra_price
    });


    // 🔥 NORMALIZAS AQUÍ
    PAX_STATE.room = {
        numero: room.id,          // o room.room_number (decide uno)
        tipo: room.type,
        capacidad: room.capacity,
        extra: room.extra_price
    };

    renderPaxGrid();

    updateTotal();

    // ⭐ IMPORTANTE PMS
    // aquí después podremos buscar registro activo
    // buscarRegistroActivo(CURRENT_ROOM_ID);

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
    document.querySelectorAll('.floor-btn').forEach(b => b.classList.toggle('active', b.innerText === f || (f ===
        'PB' && b.innerText === 'PB')));
    renderRooms();
}

function switchType(t) {
    currentType = t;
    document.querySelectorAll('.type-btn').forEach(b => b.classList.toggle('active', b.innerText === t));
    renderRooms();
}

function changeQty(type, step) {
    const el = document.getElementById(`qty_${type}`);
    let val = (parseInt(el.value) || 0) + step;
    if (type === 'adults' && val < 1) val = 1;
    if (val < 0) val = 0;
    el.value = val;
    updateTotal();
}

function updateTotal() {
    const acompanantes = PAX_STATE.acompanantes || [];

    let total = 1; // titular
    let kids = 0;

    acompanantes.forEach(p => {
        total++;
        if (p.es_menor) kids++;
    });

    const adults = total - kids;

    const capacidad = PAX_STATE.room?.capacidad || 0;
    const extra = Math.max(0, total - capacidad);


    const anticipo = parseFloat(document.getElementById('anticipo_monto').value) || 0;
    const method = document.querySelector('input[name="payment_method"]:checked').value;

    const warning = document.getElementById('capacity-warning');
    if (selectedRoom) {
        if ((adults + kids) > selectedRoom.capacity) warning.classList.remove('hidden');
        else warning.classList.add('hidden');
    }

   const payCont = document.getElementById('payment_details_container');

if (method !== 'Efectivo') {
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

    if (selectedRoom) {
        const d1 = new Date(document.getElementById('checkin_date').value);
        const d2 = new Date(document.getElementById('checkout_date').value);
        const tasaISH = window.TASA_ISH || 0;
        let nights = Math.max(1, (d2 - d1) / (1000 * 60 * 60 * 24));
        const sub = selectedRoom.price * nights;
        const ext = (extra * EXTRA_FEE * nights);
        const base = sub + ext;
        const iva = base * 0.16;
        const ish = base * (tasaISH / 100);
        const total = base + iva + ish;
        const saldo = total - anticipo;

        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.innerText = val;
        };
        set('summary_subtotal', "$" + sub.toLocaleString());
        set('summary_base', "$" + base.toLocaleString());
        set('summary_iva', "$" + iva.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
        set('summary_ish', "$" + ish.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
        set('summary_extra', "$" + ext.toLocaleString());
        set('summary_total', "$" + total.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2}));
        set('summary_anticipo', "-$" + anticipo.toLocaleString());
        set('summary_saldo', "$" + saldo.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        set('summary_room', "#" + selectedRoom.id + " (" + selectedRoom.type + ")");
        document.getElementById('row_extra').classList.toggle('hidden', ext === 0);

        set('summary_nights', nights + (nights === 1 ? " Noche" : " Noches"));

        set('m_name', document.getElementById('summary_guest').innerText);
        set('m_room', selectedRoom.id + " (" + selectedRoom.type + ")");
        set('m_sub', "$" + sub.toLocaleString());
        set('m_taxes', "$" + (iva + ish).toLocaleString());
        set('m_saldo', "$" + saldo.toLocaleString());
        set('modal_folio_id', document.getElementById('display_folio').innerText);
    }
}

// BUSCADOR MEJORADO
function simulateOCR() {
    populate(GUESTS_DB[0]);
    alert("✓ Escaneo Biométrico Finalizado Exitosamente.");
}

function syncSummary() {
    const n = document.getElementById('first_name').value;
    const a = document.getElementById('last_name').value;
    document.getElementById('summary_guest').innerText = (n + " " + a).trim() || "---";
}

async function startCamerareserva(videoId) {

    const video = document.getElementById(videoId);

    try {

        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" },
            audio: false
        });

        video.srcObject = stream;

        await video.play();

    } catch (e) {
        console.error("Error cámara", e);
        alert("No se pudo abrir la cámara");
    }

}
function stopCamerareseva(videoId) {

    const video = document.getElementById(videoId);

    if (video) {
        video.pause();
        video.srcObject = null;
    }

    if (CURRENT_STREAM) {
        CURRENT_STREAM.getTracks().forEach(track => track.stop());
        CURRENT_STREAM = null;
    }

    console.log("📷 Cámara detenida");
}

/* function addCompanion() {
    const list = document.getElementById('companionList');
    document.getElementById('noCompanionMsg').classList.add('hidden');
    const id = Date.now();
    const div = document.createElement('div');
    div.className =
        'flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm animate-fade-in group focus-within:border-indigo-600 transition-all';
    div.innerHTML =
        `
                <div class="relative w-11 h-11 bg-slate-100 rounded-xl border-2 border-slate-50 overflow-hidden shrink-0 flex items-center justify-center">
                    <video id="v-comp-${id}" autoplay playsinline class="w-full h-full object-cover hidden"></video>   
                    <img id="p-comp-${id}" class="comp-photo w-full h-full object-cover hidden">
                    <button type="button" onclick="startCamerareserva('v-comp-${id}'); document.getElementById('v-comp-${id}').classList.remove('hidden')" class="text-slate-400 group-hover:text-indigo-600 transition-colors"><i class="fas fa-camera text-xs"></i></button>
                    <button type="button" onclick="takePhotoreserva('v-comp-${id}', 'p-comp-${id}')" class="absolute bottom-0 right-0 bg-indigo-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[7px] border-2 border-white"><i class="fas fa-check"></i></button>
                </div>
                <div class="flex-1 grid grid-cols-12 gap-2">
                    <input type="text" placeholder="Nombre completo" class="col-span-5 bg-transparent h-10 px-2 outline-none text-[12px] font-bold text-slate-800 placeholder:text-slate-300">
                    <select class="col-span-4 bg-transparent h-10 px-1 outline-none text-[11px] font-bold text-slate-700">
                        <option>Esposa</option>
                        <option>Esposo</option>
                        <option>Hijo/a</option>
                        <option>Familiar</option>
                        <option>Compañero</option>
                        <option>Otro</option>
                    </select>
                    <div class="col-span-3 flex items-center gap-2">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <span class="text-[9px] font-black text-slate-400 uppercase leading-none">Menor</span>
                    </div>
                </div>
                <button onclick="this.parentElement.remove(); if(list.children.length === 1) document.getElementById('noCompanionMsg').classList.remove('hidden');" class="text-red-300 hover:text-red-500 transition-colors p-2"><i class="fas fa-trash-alt text-base"></i></button>`;
    list.appendChild(div);
} */

/* function openFinalModal() {
    if (!selectedRoom) return alert("Error: Debe asignar una habitación antes de confirmar.");
    if (!document.getElementById('first_name').value) return alert(
        "Error: No se ha registrado un titular para el folio.");
    const m = document.getElementById('finalModal');
    m.classList.remove('hidden');
} */

/* ======================================================
   CERRAR MODAL HABITACIÓN
====================================================== */
function cerrarHabitacion() {

    const modal = document.getElementById("master-modal-container");

    if (!modal) {
        console.error("No existe modal");
        return;
    }

    // 🔵 ocultar modal
    modal.classList.remove("show");

    // 🔥 limpiar variables globales PMS
    CURRENT_ROOM_ID = null;
    CURRENT_GUEST_ID = null;
    CURRENT_REGISTRO_ID = null;

    // 🔥 limpiar formularios (opcional pero recomendado)
    const form = modal.querySelector("form");
    if (form) {
        form.reset();
    }

    // 🔥 limpiar lista acompañantes
    const list = document.getElementById("companionList");
    if (list) {
        list.innerHTML = "";
    }

    // 🔥 mensaje sin acompañantes
    const msg = document.getElementById("noCompanionMsg");
    if (msg) {
        msg.classList.remove("hidden");
    }

    // ⭐ pequeño delay para animación
    setTimeout(() => {
        location.reload();
    }, 400);

}
// FUNCIONALIDAD DE GUARDAR E IMPRIMIR
function saveAndPrint() {
    alert("✓ Folio " + document.getElementById('display_folio').innerText + " guardado exitosamente en base de datos.");
    window.print();
}


function imprimirTicket() {

    if (!CURRENT_REGISTRO_ID) {
        alert("No hay registro");
        return;
    }

    window.open(
        base_url + "reservacion/ticket/" + CURRENT_REGISTRO_ID,
        "_blank",
        "width=400,height=600"
    );

}


function openFinalModal() {
    if (!selectedRoomData) return alert("Por favor, seleccione una habitación primero.");
    if (!document.getElementById('first_name').value) return alert("Faltan datos del titular del registro.");
    document.getElementById('finalModal').classList.remove('hidden');
}

function closeModal() { 
    document.getElementById('finalModal').classList.add('hidden'); 
}





/*************************************************
 * 🔹 SETEAR HABITACIÓN ACTUAL
 * Llamar cuando seleccionas habitación
 *************************************************/


/*************************************************
 * 🔹 RENDER GRID DE HUÉSPEDES
 *************************************************/
function renderPaxGrid() {

    const grid = document.getElementById('pax-visual-grid');
    if (!grid) return;

    // =========================
    // 🔹 HELPER FOTO (USA base_url GLOBAL)
    // =========================
    function getFoto(foto, esMenor = false) {

    if (!foto) {
        return esMenor
            ? "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200"
            : "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=200";
    }

    // base64
    if (foto.startsWith('data:image')) return foto;

    // URL completa
    if (foto.startsWith('http')) return foto;

    // 🔥 AQUÍ EL FIX REAL
    return base_url.replace(/\/$/, '') + "/uploads/fotos/" + foto;
}

    // =========================
    // 🔹 titular desde inputs
    // =========================
    const nombre = document.getElementById("first_name")?.value.trim() || "";
    const apellido = document.getElementById("last_name")?.value.trim() || "";

    const capacidad = PAX_STATE.room?.capacidad || 0;
    const acompanantes = PAX_STATE.acompanantes || [];

    // 🔹 titular
    PAX_STATE.titular = {
        nombre,
        apellido,
        foto: getFoto(null, false)
    };

    // =========================
    // 🔹 limpiar grid
    // =========================
    grid.innerHTML = '';

    // =========================
    // 🔹 pintar titular
    // =========================
    grid.innerHTML += `
        <div class="pax-item" onclick="openPaxModal('titular')">
            <img src="${PAX_STATE.titular.foto}" class="pax-photo" style="border-color:#4f46e5;">
            <span class="pax-name">Titular</span>
        </div>
    `;

    // =========================
    // 🔹 contadores
    // =========================
    let total = 1;
    let menores = 0;

    acompanantes.forEach((p) => {

        total++;

        if (p.es_menor) menores++;

        const isExtra = total > capacidad;

        const fotoFinal = getFoto(p.foto, p.es_menor);

        grid.innerHTML += `
            <div class="pax-item" onclick="openPaxModal('${p.id}')">
                <img src="${fotoFinal}" class="pax-photo"
                style="${isExtra ? 'border-color:#fbbf24;border-style:dashed;' : ''}">
                
                <span class="pax-name">${p.nombre}</span>

                ${isExtra ? '<span class="extra-person-badge">Extra</span>' : ''}
            </div>
        `;
    });

    // =========================
    // 🔹 botón agregar
    // =========================
    grid.innerHTML += `
        <div class="btn-add-pax" onclick="openPaxModal(null)">
            +
        </div>
    `;

    // =========================
    // 🔥 CÁLCULOS
    // =========================
    const extras = Math.max(0, total - capacidad);
    const adultos = total - menores;

    const lblAdults = document.getElementById('label_adults');
    const lblKids = document.getElementById('label_kids');
    const lblExtra = document.getElementById('label_extra');

    if (lblAdults) lblAdults.textContent = adultos;
    if (lblKids) lblKids.textContent = menores;
    if (lblExtra) lblExtra.textContent = extras;

    const warning = document.getElementById('capacity-warning');
    if (warning) {
        if (extras > 0) warning.classList.remove('hidden');
        else warning.classList.add('hidden');
    }

    updateTotal();

    console.log('👥 Total:', total, 'Adultos:', adultos, 'Menores:', menores, 'Extras:', extras);
}
/*************************************************
 * 🔹 ABRIR MODAL
 *************************************************/
function openPaxModal(id) {

    PAX_STATE.editingId = id;

    const acompanantes = PAX_STATE.acompanantes || [];
    const max = PAX_STATE.room?.capacidad || 0;
    const btnDel = document.getElementById('btn-delete');

    const currentTotal = 1 + acompanantes.length;
    const alert = document.getElementById('pax-excess-alert');

    // =========================
    // 🔹 limpiar inputs
    // =========================
    const inputs = [
        'pax-input-first',
        'pax-input-last',
        'pax-input-id-num',
        'pax-input-obs',
        'pax-input-order'
    ];

    inputs.forEach(i => {
        const el = document.getElementById(i);
        if (el) el.value = "";
    });

    const minor = document.getElementById('pax-input-minor');
    if (minor) minor.checked = false;

    const preview = document.getElementById('modal-pax-preview');
    const cam = document.getElementById('camera-icon');

    // =========================
    // 🔹 reset visual
    // =========================
    if (preview) {
        preview.src = "";
        preview.dataset.file = "";
        preview.classList.add('hidden');
    }

    if (cam) cam.classList.remove('hidden');

    // =========================
    // 🔹 TITULAR
    // =========================
    if (id === 'titular') {

        if (alert) alert.classList.add('hidden');

        document.getElementById('pax-input-first').value = PAX_STATE.titular.nombre;
        document.getElementById('pax-input-last').value = PAX_STATE.titular.apellido;

    }
    // =========================
    // 🔹 EDITAR ACOMPAÑANTE
    // =========================
    else if (id !== null) {

        const p = acompanantes.find(a => a.id == id);
        if (!p) return;

        document.getElementById('pax-input-first').value = p.nombre;
        document.getElementById('pax-input-last').value = p.apellido;
        document.getElementById('pax-input-relation').value = p.tipo;

        if (minor) minor.checked = p.es_menor;

        // =========================
        // 📸 CARGAR FOTO (FIX REAL)
        // =========================
        if (preview && p.foto) {

            let fotoUrl = p.foto;

            // base64
            if (!fotoUrl.startsWith('data:image') && !fotoUrl.startsWith('http')) {

                // normalizar Windows path
                fotoUrl = fotoUrl.replace(/\\/g, '/');

                // quitar prefijo si viene completo
                fotoUrl = fotoUrl.replace(/^.*uploads\/fotos\//, '');

                // construir URL final
                fotoUrl = base_url.replace(/\/$/, '') + "/uploads/fotos/" + fotoUrl;
            }

            preview.src = fotoUrl;
            preview.dataset.file = p.foto;

            preview.classList.remove('hidden');
            if (cam) cam.classList.add('hidden');
        }

        if (alert) alert.classList.add('hidden');

    }
    // =========================
    // 🔹 NUEVO
    // =========================
    else {

        if (alert) {
            if (currentTotal >= max) alert.classList.remove('hidden');
            else alert.classList.add('hidden');
        }
    }

    toggleDeleteButton();
    toggleModal('modal-pax-capture', true);
}


/*************************************************
 * 🔹 GUARDAR HUÉSPED
 *************************************************/
function savePaxData() {

    // =========================
    // 📥 INPUTS
    // =========================
    const nombre = document.getElementById('pax-input-first').value.trim();
    const apellido = document.getElementById('pax-input-last').value.trim();
    const relation = document.getElementById('pax-input-relation').value;
    const isMinor = document.getElementById('pax-input-minor').checked;

    // =========================
    // 🚨 VALIDACIONES
    // =========================
    const errores = [];

    if (!nombre) errores.push("Nombre es obligatorio");
    if (!apellido) errores.push("Apellido es obligatorio");
    if (!relation) errores.push("Relación es obligatoria");

    if (isMinor && (!relation || relation === 'otro')) {
        errores.push("Debe especificar parentesco válido para menor");
    }

    if (errores.length > 0) {
        alert("⚠️ Validación:\n\n" + errores.join("\n"));
        return;
    }

    // =========================
    // 📸 FOTO REAL
    // =========================
    const fotoCapturada = document.getElementById('modal-pax-preview')?.dataset?.file || null;

    const fotoFinal = fotoCapturada
        ? fotoCapturada
        : (isMinor
            ? "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=150"
            : "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=150"
        );

    // =========================
    // 🧠 ESTADO
    // =========================
    let acompanantes = PAX_STATE.acompanantes || [];
    const max = parseInt(PAX_STATE.room?.capacidad) || 0;
    const extraPrice = parseFloat(PAX_STATE.room?.extra_price) || 0;

    // =========================
    // ✏️ EDITAR
    // =========================
    if (PAX_STATE.editingId !== null) {

        const idx = acompanantes.findIndex(a => a.id == PAX_STATE.editingId);

        if (idx !== -1) {
            acompanantes[idx] = {
                ...acompanantes[idx],
                nombre,
                apellido,
                tipo: relation,
                es_menor: isMinor,
                foto: fotoFinal // 🔥 ahora sí actualiza foto
            };
        }

    } else {

        // =========================
        // ➕ NUEVO
        // =========================
        const newPax = {
            id: Date.now(),
            nombre,
            apellido,
            tipo: relation,
            es_menor: isMinor,
            es_extra: false,
            foto: fotoFinal // 🔥 foto real o fallback
        };

        acompanantes.push(newPax);
    }

    // =========================
    // 🔥 RECALCULAR EXTRAS
    // =========================
    if (max > 0) {

        acompanantes = acompanantes.map((p, index) => {

            const total = index + 2; // titular + index
            const esExtra = total > max;

            return {
                ...p,
                es_extra: esExtra
            };
        });
    }

    // =========================
    // 💾 GUARDAR ESTADO
    // =========================
    PAX_STATE.acompanantes = acompanantes;

    // =========================
    // 🧹 LIMPIAR FOTO (IMPORTANTE)
    // =========================
    const preview = document.getElementById('modal-pax-preview');
    if (preview) {
        preview.src = "";
        preview.dataset.file = "";
        preview.classList.add('hidden');
    }

    const icon = document.getElementById('camera-icon');
    if (icon) icon.classList.remove('hidden');

    // =========================
    // 🎨 UI
    // =========================
    updateTotal();
    renderPaxGrid();
    toggleModal('modal-pax-capture', false);
}


/*************************************************
 * 🔹 GUARDAR EN BACKEND
 *************************************************/
function guardarPaxBackend() {

    const payload = {
        titular: PAX_STATE.titular,
        acompanantes: PAX_STATE.acompanantes,
        room_id: PAX_STATE.room?.room_number
    };

    fetch(base_url + "huespedes/guardar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(res => {
            console.log("✅ Guardado backend", res);
        })
        .catch(err => {
            console.error("❌ Error backend", err);
        });
}


function deleteGuest() {

    if (PAX_STATE.editingId === null) return;

    if (PAX_STATE.editingId === 'titular') {
        console.warn('No puedes eliminar titular');
        return;
    }

    const antes = PAX_STATE.acompanantes.length;

    // 🔥 eliminar correctamente por ID
    PAX_STATE.acompanantes = (PAX_STATE.acompanantes || []).filter(
        a => String(a.id) !== String(PAX_STATE.editingId)
    );

    const despues = PAX_STATE.acompanantes.length;

    console.log('Antes:', antes, 'Después:', despues);

    if (antes === despues) {
        console.warn('⚠️ No se eliminó nada (ID no coincide)');
        return;
    }

    // reset estado
    PAX_STATE.editingId = null;


    renderPaxGrid();
    // cerrar modal
    toggleModal('modal-pax-capture', false);

    // 🔥 FORZAR REFRESH DESPUÉS DE CERRAR MODAL
    setTimeout(() => {
        renderStepLogistica();
    }, 50);

    console.log('✅ Huésped eliminado correctamente');
}

/**
 * Control de visibilidad del modal.
 */
function toggleModal(id, show) {
    const el = document.getElementById(id);
    if (show) el.classList.add('active');
    else el.classList.remove('active');
}

function toggleDeleteButton() {
    const btn = document.getElementById('btn-delete');

    const nombre = document.getElementById('pax-input-first').value.trim();
    const apellido = document.getElementById('pax-input-last').value.trim();

    const hayNombre = nombre !== '' || apellido !== '';

    const esEdicion = PAX_STATE.editingId !== null;

    const esTitular = PAX_STATE.editingId === 'titular';

    // 🔥 REGLA FINAL
    if (hayNombre && esEdicion && !esTitular) {
        btn.classList.remove('hidden');
    } else {
        btn.classList.add('hidden');
    }
}


function loadGuestData(guest, index) {
    document.getElementById('pax-input-first').value = guest.nombre;
    document.getElementById('pax-input-last').value = guest.apellido;

    PAX_STATE.editingId = index; // o 'titular'

    toggleDeleteButton();
}


function renderGuestsList() {

    const container = document.getElementById('companionList');
    const emptyMsg = document.getElementById('noCompanionMsg');

    if (!container) {
        console.warn('❌ No existe #companionList');
        return;
    }

    const list = PAX_STATE.acompanantes || [];

    // 🔥 LIMPIAR TODO (incluyendo mensaje viejo)
    container.innerHTML = '';

    if (list.length === 0) {
        container.innerHTML = `
            <p class="text-center text-slate-300 text-[11px] font-bold uppercase py-16 border-2 border-dashed rounded-[40px]">
                No hay acompañantes registrados en este folio.
            </p>
        `;
        return;
    }

    list.forEach(p => {

        const row = document.createElement('div');

        row.className = `
            flex justify-between items-center p-4 
            bg-white border rounded-2xl shadow-sm
        `;

        row.innerHTML = `
            <div>
                <p class="text-xs font-black text-slate-900">
                    ${p.nombre} ${p.apellido}
                </p>
                <p class="text-[9px] text-slate-400 font-bold uppercase">
                    ${p.tipo || 'Acompañante'}
                </p>
            </div>

            <button 
                onclick="openPaxModal('${p.id}')"
                class="text-indigo-600 text-[10px] font-black uppercase">
                Editar
            </button>
        `;

        container.appendChild(row);
    });
}



function renderStepLogistica() {
    renderGuestsList();
}


function validarPago() {
    const method = document.querySelector('input[name="payment_method"]:checked')?.value;

    if (method !== 'Efectivo') {
        const ref = document.getElementById('referencia_pago')?.value.trim();
        const banco = document.getElementById('banco_pago')?.value.trim();

        if (!ref || !banco) {
            alert('Completa los datos de pago (referencia y banco)');
            return false;
        }
    }

    return true;
}

// =========================
// LOADER GLOBAL
// =========================
window.Loader = {
    show(text = "Procesando...") {
        const loader = document.getElementById("globalLoader");
        if (!loader) return;

        loader.style.display = "flex";

        const label = loader.querySelector(".loader-text");
        if (label) label.innerText = text;
    },

    hide() {
        const loader = document.getElementById("globalLoader");
        if (!loader) return;

        loader.style.display = "none";
    },

    success(text = "Proceso completado") {
        const loader = document.getElementById("globalLoader");
        if (!loader) return;

        const label = loader.querySelector(".loader-text");
        if (label) label.innerText = text;

        setTimeout(() => {
            loader.style.display = "none";
        }, 1200);
    }
};

 
// =========================
// MODAL OCR
// =========================
function openOCRModal() {
    const modal = document.getElementById('ocrModal');

    // 🔥 evitar problema de z-index
    document.body.appendChild(modal);

    modal.classList.remove('hidden');
}

function closeOCRModal() {
    document.getElementById('ocrModal').classList.add('hidden');
}


// =========================
// CÁMARA
// =========================
let streamRererva;

async function startCameraocr() {

    const video = document.getElementById('video');
    const preview = document.getElementById('preview');

    preview.classList.add('hidden');
    video.classList.remove('hidden');

    try {
        streamRererva = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }
        });
        video.srcObject = streamRererva;

        // 🔥 Control de botones
        document.getElementById('ocr-btn-on').classList.add('hidden');
        document.getElementById('ocr-label-upload').classList.add('hidden');
        document.getElementById('ocr-btn-capture').classList.remove('hidden');
        document.getElementById('ocr-btn-cancel').classList.remove('hidden');
        document.getElementById('ocr-btn-rotate').classList.add('hidden');

        ocrRotation = 0;
        preview.style.transform = 'none';

    } catch (err) {
        console.error(err);
        showToast("No se pudo acceder a la cámara");
    }
}

function stopCameraOCR() {
    if (streamRererva) {
        streamRererva.getTracks().forEach(track => track.stop());
        streamRererva = null;
    }

    document.getElementById('video').classList.add('hidden');
    document.getElementById('preview').classList.add('hidden');

    // 🔥 Restaurar botones
    document.getElementById('ocr-btn-on').classList.remove('hidden');
    document.getElementById('ocr-label-upload').classList.remove('hidden');
    document.getElementById('ocr-btn-capture').classList.add('hidden');
    document.getElementById('ocr-btn-cancel').classList.add('hidden');
    document.getElementById('ocr-btn-rotate').classList.add('hidden');

    window.capturedImage = null;
}


// =========================
// CAPTURA FOTO
// =========================
async function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');

    if (!video.videoWidth) {
        showToast("La cámara aún no está lista");
        return;
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    const image = canvas.toDataURL("image/jpeg", 0.8);
    window.capturedImage = image;

    // 🔥 Mostrar preview
    preview.src = image;
    preview.classList.remove('hidden');
    video.classList.add('hidden');
    document.getElementById('ocr-btn-rotate').classList.remove('hidden');

    // 🔥 Confirmación
    const result = await Swal.fire({
        title: '¿LA IMAGEN ES CORRECTA?',
        text: "Asegúrate de que los datos sean legibles",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'SÍ, PROCESAR',
        cancelButtonText: 'NO, REPETIR',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b'
    });

    if (result.isConfirmed) {
        if (streamRererva) {
            streamRererva.getTracks().forEach(track => track.stop());
            streamRererva = null;
        }
        processOCR();
    } else {
        // Volver a la cámara
        preview.classList.add('hidden');
        video.classList.remove('hidden');
        document.getElementById('ocr-btn-rotate').classList.add('hidden');
    }
}



// =========================
// OCR
// =========================
async function processOCR() {

    try {

        Loader.show("Procesando documento...");

        let imageBase64 = window.capturedImage;

        // fallback: archivo
        if (!imageBase64) {
            const file = document.getElementById('fileInput').files[0];

            if (!file) {
                showToast("Captura o selecciona una imagen");
                Loader.hide();
                return;
            }

            imageBase64 = await toBase64(file);
        }

        // 🔄 Rotar imagen si es necesario
        if (typeof ocrRotation !== 'undefined' && ocrRotation !== 0) {
            imageBase64 = await getRotatedBase64(imageBase64, ocrRotation);
        }

        const response = await fetch(base_url +'/ocr/procesar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: imageBase64 })
        });

        if (!response.ok) throw new Error("Error servidor OCR");

        const data = await response.json();

        console.log("OCR RESPONSE:", data);

        // 🔥 usar data limpia del backend
        fillForm(data.data);

        // 🔥 validación menor
        if (data.es_menor) {
            showToast("⚠️ Menor de edad detectado");
        }

        Loader.success("✔ Datos extraídos");

        // opcional cerrar modal
        closeOCRModal();

    } catch (e) {

        console.error(e);
        Loader.hide();
        showToast(e.message || "Error OCR");

    }
}


// =========================
// FILE → BASE64
// =========================
function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
    });
}





function normalizeOCRData(data) {

    if (!data) return {};

    let nombre = data.nombre || data.name || data.first_name || "";
    let apellidos = data.apellidos || data.last_name || "";

    // 🔥 si viene nombre completo
    if (!nombre && data.nombre_completo) {
        const partes = data.nombre_completo.split(" ");

        nombre = partes.slice(0, -2).join(" ") || partes[0];
        apellidos = partes.slice(-2).join(" ");
    }

    // 🔥 fallback si solo viene un string
    if (!nombre && data.full_name) {
        const partes = data.full_name.split(" ");
        nombre = partes.slice(0, -2).join(" ");
        apellidos = partes.slice(-2).join(" ");
    }

    return {
        nombre: nombre?.trim(),
        apellidos: apellidos?.trim(),
        numero_identificacion: data.numero_identificacion || data.id || data.document_number || "",
        fecha_nacimiento: formatDate(data.fecha_nacimiento || data.dob),
        genero: normalizeGender(data.genero || data.gender),
        nacionalidad: data.nacionalidad || data.nationality || "Mexicana"
    };
}

function formatDate(date) {
    if (!date) return "";

    // ya viene bien
    if (/^\d{4}-\d{2}-\d{2}$/.test(date)) return date;

    // formatos comunes
    const d = new Date(date);
    if (!isNaN(d)) {
        return d.toISOString().split('T')[0];
    }

    return "";
}


function normalizeGender(g) {
    if (!g) return "";

    g = g.toLowerCase();

    if (g.includes("m")) return "Masculino";
    if (g.includes("f")) return "Femenino";

    return "Otro";
}


// =========================
// MAPEAR DATOS OCR → FORM
// =========================


let ocrRotation = 0;

function rotatePhoto() {
    const preview = document.getElementById('preview');
    if (preview.classList.contains('hidden')) return;

    ocrRotation = (ocrRotation + 90) % 360;
    preview.style.transform = `rotate(${ocrRotation}deg)`;
}

function fillForm(data) {

    const clean = normalizeOCRData(data);

    console.log("NORMALIZED:", clean);

    const map = {
        nombre: 'first_name',
        apellidos: 'last_name',
        numero_identificacion: 'id_number',
        fecha_nacimiento: 'main_dob',
        genero: 'main_gender',
        nacionalidad: 'main_nationality'
    };

    Object.keys(map).forEach(key => {
        const value = clean[key];

        if (value) {
            const el = document.getElementById(map[key]);
            if (el) el.value = value;
        }
    });
}

function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');

    if (!video.videoWidth) {
        showToast("La cámara aún no está lista");
        return;
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    const image = canvas.toDataURL("image/jpeg", 0.7);

    window.capturedImage = image;

    // 🔥 mostrar preview
    preview.src = image;
    preview.classList.remove('hidden');
    video.classList.add('hidden');

    // apagar cámara
    if (streamRererva) {
        streamRererva.getTracks().forEach(track => track.stop());
    }
}

document.getElementById('fileInput').addEventListener('change', async function () {

    const file = this.files[0];
    if (!file) return;

    const base64 = await toBase64(file);

    window.capturedImage = base64;

    const preview = document.getElementById('preview');
    preview.src = base64;
    preview.classList.remove('hidden');

    document.getElementById('video').classList.add('hidden');

    // 🔥 Mostrar rotación y cancelar, ocultar otros
    document.getElementById('ocr-btn-rotate').classList.remove('hidden');
    document.getElementById('ocr-btn-cancel').classList.remove('hidden');
    document.getElementById('ocr-btn-on').classList.add('hidden');
    document.getElementById('ocr-label-upload').classList.add('hidden');
}); 



async function startCameraPax() {

    const video = document.getElementById('modal-pax-video');
    const icon = document.getElementById('camera-icon');
    const btn = document.getElementById('btn-take-photo');

    try {

        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "user" },
            audio: false
        });

        PAX_CAMERA_STREAM = stream;

        video.srcObject = stream;
        await video.play();

        video.classList.remove('hidden');
        icon.classList.add('hidden');
        btn.classList.remove('hidden');

    } catch (e) {
        console.error(e);
        alert("No se pudo abrir la cámara");
    }
}


async function takePhotoPax() {

    const video = document.getElementById('modal-pax-video');
    const img = document.getElementById('modal-pax-preview');

    if (video.readyState < 2) {
        await new Promise(r => video.onloadeddata = r);
    }

    const canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0);

    const base64 = canvas.toDataURL("image/png");

    // preview
    img.src = base64;
    img.classList.remove('hidden');
    video.classList.add('hidden');

    // subir foto
    const fileName = await subirFoto(base64);

    if (fileName) {
        img.dataset.file = fileName;
    }

    stopCameraPax();
}


function stopCameraPax() {

    if (PAX_CAMERA_STREAM) {
        PAX_CAMERA_STREAM.getTracks().forEach(t => t.stop());
        PAX_CAMERA_STREAM = null;
    }
}


function getMontoSeguro(id) {

    const el = document.getElementById(id);
    if (!el) return 0;

    let val = el.value || "0";

    // 🔥 limpiar símbolos
    val = val.replace(/[$,]/g, '').trim();

    let num = parseFloat(val);

    if (isNaN(num)) num = 0;

    // 🔥 evitar -0
    if (Object.is(num, -0)) num = 0;

    return num;
}



function getClienteData() {
    return {
        nombre: document.getElementById('first_name').value.trim(),
        apellidos: document.getElementById('last_name').value.trim(),
        id_number: document.getElementById('id_number').value.trim(),
        telefono: document.getElementById('main_phone').value.trim(),
        email: document.getElementById('main_email').value.trim()
    };
}


async function validarDuplicadoCliente() {

    const cliente = {
        numero_identificacion: document.getElementById('id_number').value.trim(),
        telefono: document.getElementById('main_phone').value.trim(),
        email: document.getElementById('main_email').value.trim()
    };

    const resp = await fetch(base_url + "reservacion/validar-duplicado", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cliente)
    });

    const data = await resp.json();

    if (data.duplicados && data.duplicados.length > 0) {

        mostrarDuplicados(data.duplicados);

        return false;
    }

    return true;
}


function mostrarDuplicados(lista) {

    const container = document.getElementById('searchResults');

    container.innerHTML = '';
    container.classList.remove('hidden');

    lista.forEach(c => {

        const div = document.createElement('div');

        div.className = "bg-white border rounded-xl p-3 cursor-pointer hover:bg-slate-50";

        div.innerHTML = `
            <strong>${c.nombre} ${c.apellido}</strong><br>
            <small>${c.numero_identificacion} - ${c.telefono} - ${c.email}</small>
        `;

        div.onclick = () => cargarClienteExistente(c);

        container.appendChild(div);
    });
}

function normalizar(txt) {
    return txt.toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
}


let dupTimer;

function validarDuplicadoDebounce() {
    clearTimeout(dupTimer);
    dupTimer = setTimeout(() => {
        validarDuplicadoCliente();
    }, 500); // espera a que deje de escribir
}



async function cargarImpuestos() {
    const res = await fetch(base_url + 'reservacion/obtenerImpuestos');
    const data = await res.json();

    let ish = data.find(i => i.nombre === 'ISH');
    let iva = data.find(i => i.nombre === 'IVA');

    if (ish) {
        // Actualiza el label
        document.getElementById('label_ish').innerText = 
            `I.S.H. (${parseFloat(ish.tasa).toFixed(1)}%)`;

        // Guarda global si lo necesitas para cálculos
        window.TASA_ISH = parseFloat(ish.tasa);
    }

    if (iva) {
        window.TASA_IVA = parseFloat(iva.tasa);
    }
}


function calcularISH(subtotal) {
    const tasa = window.TASA_ISH || 0;

    const ish = subtotal * (tasa / 100);

    document.getElementById('summary_ish').innerText =
        `$${ish.toFixed(2)}`;

    return ish;
}

async function getRotatedBase64(base64, degrees) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            if (degrees === 90 || degrees === 270) {
                canvas.width = img.height;
                canvas.height = img.width;
            } else {
                canvas.width = img.width;
                canvas.height = img.height;
            }

            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate((degrees * Math.PI) / 180);
            ctx.drawImage(img, -img.width / 2, -img.height / 2);

            resolve(canvas.toDataURL("image/jpeg", 0.8));
        };
        img.src = base64;
    });
}