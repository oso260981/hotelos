
/* =========================================================
   MODULO TRABAJO - HOTEL OS
   Gestión de habitaciones estilo PMS
========================================================= */


/* =========================================================
   VARIABLES GLOBALES
========================================================= */
let pisoActivo = 'PB';


/* =========================================================
   INICIO PANTALLA
========================================================= */
document.addEventListener("DOMContentLoaded", () => {

    cargarTrabajo(pisoActivo);

});


/* =========================================================
   CARGAR GRID HABITACIONES
========================================================= */

let ROOM_MAP = {}; // 🔥 ahora global

function cargarTrabajo(piso = 'PB') {

    pisoActivo = piso;

    const grid = document.getElementById("gridTrabajo");

    grid.innerHTML = `
        <tr>
            <td colspan="16" style="text-align:center">
                Cargando habitaciones...
            </td>
        </tr>
    `;

    // =========================
    // 🔥 URL DINÁMICA
    // =========================
    let url = base_url + "trabajo/listar?piso=" + piso;

    if (estadoActivo) {
        url += "&estado=" + estadoActivo;
    }

    fetch(url)
        .then(r => {
            if (!r.ok) throw new Error("Error HTTP " + r.status);
            return r.json();
        })
        .then(lista => {

            if (!lista || lista.length === 0) {
                grid.innerHTML = `
                    <tr>
                        <td colspan="16">Sin habitaciones</td>
                    </tr>
                `;
                return;
            }

            let html = "";

            lista.forEach(h => {

                const resId = h.id_reservacion || '';
                const estadoReg = h.estado_registro || '';
                const estadoHab = h.cod_eh || ''; // 🔥 ESTADO HABITACIÓN

                const roomObj = {
                    id: h.Numero_Habitacion,
                    numero: h.Numero_Habitacion,
                    type: h.tip_habitacion ?? '',
                    capacity: h.Total_huespedes ?? 1,
                    extra_price: h.precio_base ?? 0
                };

                ROOM_MAP[h.Numero_Habitacion] = roomObj;

                html += `
                <tr class="trabajo-row" 
                    data-reservacion="${resId}" 
                    data-room-id="${h.Numero_Habitacion}"
                    data-estado-reg="${estadoReg}"
                    data-estado-hab="${estadoHab}">

                    <td>${pintarEstado(h.cod_eh)}</td>
                    <td><strong>${formatRoom(h.Numero_Habitacion)}</strong></td>
                    <td>${h.Cod_Estadia ?? ''}</td>
                    <td>${h.Total_huespedes ?? ''}</td>
                    <td>${pintarFormaPago(h.Cod_Forma_pago)}</td>
                    <td class="td-price">${formatPrecio(h.Sub_total)}</td>

                    <td><button class="btn-mini">+</button></td>
                    <td>${formatearFechaHora(h.hora_entrada)}</td>
                    <td><button class="btn-mini">+</button></td>
                    <td>${formatearFechaHora(h.hora_salida_real)}</td>
                    <td><button class="btn-mini">+</button></td>

                    <td class="col-nombre">${h.Nombre_Huesped ?? ''}</td>

                    <td><button class="btn-mini">+</button></td>
                    <td>⚙</td>
                    <td>🎟</td>
                    <td>›</td>
                </tr>`;
            });

            grid.innerHTML = html;

            // =========================
            // 🔥 EVENTOS (CLICK INTELIGENTE)
            // =========================
            grid.querySelectorAll('.trabajo-row').forEach(row => {

                const id = row.dataset.reservacion;
                const roomId = row.dataset.roomId;
                const estadoReg = row.dataset.estadoReg;
                const estadoHab = row.dataset.estadoHab;

                const room = ROOM_MAP[roomId];

                row.addEventListener('click', () => {

                    // 🔧 MTTO → modal liberar
                    if (estadoHab === 'M') {
                        abrirModalMtto(room);
                        return;
                    }

                    // 🟢 SIN REGISTRO → nueva reserva
                    if (!estadoReg) {
                        abrirHabitacion?.(room);
                        return;
                    }

                    // 🔵 CHECKIN → admin
                    if (estadoReg === 'CHECKIN') {
                        abrirReservacion?.(Number(id));
                        return;
                    }

                    // 🧹 CHECKOUT → limpieza
                    if (estadoReg === 'CHECKOUT') {
                        abrirModalAdmin(room, {
                            estado_registro: estadoReg
                        });
                        return;
                    }

                });

            });

            actualizarFiltroUI();

        })
        .catch(e => {

            grid.innerHTML = `
                <tr>
                    <td colspan="16" style="color:red">
                        Error cargando habitaciones
                    </td>
                </tr>
            `;

            console.error("Error cargarTrabajo:", e);
        });
}
// ======================================================
// FORMATEAR SOLO HORA (HH:MM)
// Recibe datetime SQL o ISO
// ======================================================
function formatearHora(fecha){

   if(!fecha) return '';

   const f = new Date(fecha);

   const hh = String(f.getHours()).padStart(2,'0');
   const mm = String(f.getMinutes()).padStart(2,'0');

   return `${hh}:${mm}`;
}


/* =========================================================
   PINTAR ESTADO PMS
========================================================= */
function pintarEstado(cod) {

    if (!cod) return '';

    cod = cod.toUpperCase();

    if (cod === 'X') {
        return `<span class="st-badge st-clean">X</span>`;
    }

    if (cod === 'S') {
        return `<span class="st-badge st-dirty">◆</span>`;
    }

    if (cod === 'M') {
        return `<span class="st-badge st-manto"></span>`;
    }

    if (cod === 'P') {
        return `<span class="st-badge st-plant">P</span>`;
    }

    if (cod === 'D') {
        return `<span class="st-badge st-disp">✓</span>`;
    }

    return cod;
}

/* =========================================================
   FILTRAR POR PISO
========================================================= */
function filtrarPiso(piso) {

    pisoActivo = piso;

    document
        .querySelectorAll(".btn-floor")
        .forEach(b => b.classList.remove("active"));

    event.target.classList.add("active");

    cargarTrabajo(piso);

}



/* =========================================================
   CERRAR MODAL
========================================================= */
function cerrarHabitacion() {

    document
        .getElementById("modalHabitacion")
        .classList.remove("active");

}

function pintarFormaPago(cod) {

    if (!cod) return '';

    return `<span class="fp-badge fp-${cod}">${cod}</span>`;
}


document.querySelectorAll("#tablaHabitaciones tbody tr")
    .forEach(tr => {
        tr.addEventListener("click", () => {
            let hab = tr.dataset.hab;
            abrirModalHab(hab);
        });
    });

function abrirModalHab(num) {
    document.getElementById("modalHab").style.display = "block";
    document.getElementById("core_numero").innerText = num;

    // aquí luego cargaremos datos reales desde API
}

function cerrarModalHab() {
    document.getElementById("modalHab").style.display = "none";
}




// ======================================================
// FORMATEAR FECHA PARA INPUT datetime-local
// ======================================================
function formatearFechaInput(fecha) {

    if (!fecha) return "";

    const f = new Date(fecha);

    const yyyy = f.getFullYear();
    const mm = String(f.getMonth() + 1).padStart(2, '0');
    const dd = String(f.getDate()).padStart(2, '0');

    const hh = String(f.getHours()).padStart(2, '0');
    const mi = String(f.getMinutes()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
}




// ======================================================
// ABRIR HABITACIÓN - PMS CORE
// ======================================================



function abrirMenuCliente(e){

   e.stopPropagation();

   const menu = document.getElementById("menuCliente");

   menu.style.display =
      menu.style.display === "block" ? "none" : "block";
}

// cerrar si clic fuera
document.addEventListener("click",()=>{
   const menu = document.getElementById("menuCliente");
   if(menu) menu.style.display="none";
});

// ======================================================
// ABRIR MODAL BUSCAR CLIENTE
// ======================================================
function abrirBuscarCliente(){

   console.log("abrir buscador cliente");

   const modal = document.getElementById("modalBuscarCliente");

   if(modal){
      modal.style.display = "flex";
   }

}

function cerrarBuscarCliente(){

   const modal = document.getElementById("modalBuscarCliente");

   if(modal){
      modal.style.display = "none";
   }

}


// ======================================================
// BUSCAR CLIENTES (LIKE GLOBAL)
// ======================================================
async function buscarCliente(){

   const q = document.getElementById("txtBuscarCliente").value;

   const resp = await fetch(base_url + "clientes/buscar?q=" + q);
   const json = await resp.json();

   let html = '';

   json.forEach(c=>{

      html += `
      <div class="fila-cliente"
           onclick="seleccionarCliente(${c.id})">
           ${c.nombre} — ${c.telefono}
      </div>
      `;

   });

   document.getElementById("gridClientes").innerHTML = html;
}

function seleccionarCliente(id){

   console.log("cliente seleccionado", id);

   // después cargaremos:
   // nombre
   // teléfono
   // lista negra
   // cliente frecuente

   cerrarBuscarCliente();
}


// ======================================================
// BUSCAR CLIENTE → RENDER TABLA
// ======================================================
async function buscarCliente(){

   const q = document.getElementById("txtBuscarCliente").value;

   if(q.length < 2){
      document.getElementById("gridBuscarClientes").innerHTML = "";
      return;
   }

   try{

      const resp = await fetch(base_url + "pasajeros/buscar?q=" + encodeURIComponent(q));
      const data = await resp.json();

      let html = "";

      data.forEach((c,i)=>{

         html += `
         <tr onclick="seleccionarCliente(${c.id},'${c.nombre}','${c.telefono}')">
            <td>${i+1}</td>
            <td><b>${c.nombre}</b></td>
            <td>${c.telefono ?? ''}</td>
         </tr>
         `;

      });

      document.getElementById("gridBuscarClientes").innerHTML = html;

   }catch(e){
      console.error("Error buscando cliente", e);
   }

}



function seleccionarCliente(id,nombre,telefono){

   agregarClienteHab({
      id:id,
      nombre:nombre,
      telefono:telefono
   });

   cerrarBuscarCliente();
}


// ======================================================
// CLIENTES ACTUALES EN LA HABITACIÓN
// ======================================================
let CLIENTES_HAB = [];

// ======================================================
// AGREGAR CLIENTE A LA HABITACIÓN
// ======================================================
function agregarClienteHab(cliente){

   // ⭐ evitar duplicados
   const existe = CLIENTES_HAB.find(c => c.id === cliente.id);
   if(existe){
      alert("Este cliente ya está agregado");
      return;
   }

   CLIENTES_HAB.push(cliente);

   pintarClientesHab();
}

// ======================================================
// PINTAR GRID CLIENTES HABITACIÓN
// ======================================================
function pintarClientesHab(){

   let html = "";

   CLIENTES_HAB.forEach((c,i)=>{

      html += `
      <tr>
         <td>${i+1}</td>
         <td>${c.nombre}</td>
         <td>${c.telefono ?? ""}</td>
         <td>
            <button onclick="eliminarClienteHab(${i})">❌</button>
         </td>
      </tr>
      `;

   });

   document.getElementById("gridClientesHab").innerHTML = html;
}

function eliminarClienteHab(index){

   CLIENTES_HAB.splice(index,1);

   pintarClientesHab();
}


/* ======================================================
   MODAL CLIENTE
====================================================== */
function openModalCat(){
    document.getElementById("modal_cat").style.display="flex";
}


function formatearFechaHora(fechaStr) {
    if (!fechaStr) return '—';

    const fecha = new Date(fechaStr);
    if (isNaN(fecha)) return '—';

    const horas = String(fecha.getHours()).padStart(2, '0');
    const minutos = String(fecha.getMinutes()).padStart(2, '0');

    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');

    return `
        <span class="hour-cell">
            <span class="hour-time">${horas}:${minutos}</span>
            <span class="hour-date">${dia}/${mes}</span>
        </span>
    `;
}


function formatRoom(num) {
    return String(num).padStart(3, '0');
}

function formatPrecio(valor) {
    if (!valor) return '';
   // return '$' + Math.round(valor).toLocaleString('es-MX');
     return  Math.round(valor).toLocaleString('es-MX');
}

let estadoActivo = null;

function filtrarEstado(estado) {

    // toggle
    if (estadoActivo === estado) {
        estadoActivo = null;
    } else {
        estadoActivo = estado;
    }

    // 🔥 actualizar UI
    document.querySelectorAll('.legend .badge').forEach(b => {
        b.classList.remove('active');
    });

    if (estadoActivo) {
        const el = document.querySelector(`[onclick="filtrarEstado('${estadoActivo}')"]`);
        el?.classList.add('active');
    }

    // recargar
    cargarTrabajo(pisoActivo);
}


function actualizarFiltroUI() {

    document.querySelectorAll('.legend .badge').forEach(b => {
        b.classList.remove('active');
    });

    if (estadoActivo) {
        const el = document.querySelector(`[onclick="filtrarEstado('${estadoActivo}')"]`);
        el?.classList.add('active');
    }
}

document.querySelectorAll('.legend .badge').forEach(b => {
    b.classList.remove('active');
});

if (estadoActivo) {
    document.querySelector(`.badge[onclick="filtrarEstado('${estadoActivo}')"]`)
        ?.classList.add('active');
}


