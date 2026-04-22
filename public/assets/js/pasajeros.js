/* ===============================
   DATA GLOBAL PARA FILTROS
================================ */
let PASAJEROS_DATA = [];

/* ===============================
   CARGA PASAJEROS
================================ */
function cargarPasajeros(){

    fetch(base_url + "pasajeros/listado_operativo")
    .then(r => r.json())
    .then(data => {

        PASAJEROS_DATA = data;     // ← guardar global
         actualizarContadores();
        pintarPasajeros(data);

    })
    .catch(e=>{
        console.error("Error cargando pasajeros", e);
    });

}

cargarPasajeros()

/* ===============================
   PINTAR LISTA
================================ */
function pintarPasajeros(lista){

    let html = "";
    let habActual = "";
    let totalTitulares = 0;

    lista.forEach(p => {

        /* ===== divisor habitación ===== */
        if(habActual !== p.numero){
            habActual = p.numero;

            html += `
            <div class="pas-section">
                HABITACIÓN ${p.numero}
            </div>`;
        }

        /* ================= TITULAR ================= */
        if(p.tipo === 'TITULAR'){

            totalTitulares++;

            let badgeVIP = (p.cliente_frecuente == 1)
                ? `<span class="pas-badge vip">VIP</span>` : "";

            let badgeBL = (p.lista_negra == 1)
                ? `<span class="pas-badge bl">LISTA NEGRA</span>` : "";

            html += `
            <div class="pas-card-main">

                <div class="pas-avatar">
                    <i class="fa fa-user"></i>
                </div>

                <div class="pas-main-content">

                    <div class="pas-main-title">
                        ${(p.nombre ?? '').toUpperCase()} ${(p.apellido ?? '').toUpperCase()}
                        ${badgeVIP}
                        ${badgeBL}
                    </div>

                    <div class="pas-main-meta">
                        ${p.tipo_identificacion ?? ''} · 
                        ${p.numero_identificacion ?? ''} · 
                        Hab. ${p.numero} · 
                        ${p.forma_pago ?? ''} · 
                        $${formatoMoneda(p.total)} · 
                        Entrada: ${formatoHora(p.hora_entrada_1)}
                        ${p.hora_salida_1 ? ' · Salida: '+formatoHora(p.hora_salida_1):''}
                        · ${p.num_personas ?? 1} personas
                    </div>

                </div>

                <div class="pas-main-status">
                    ✔ Firma digital<br>
                    ✔ Foto capturada<br>
                    ✔ ID escaneado
                </div>

            </div>`;
        }

        /* ================= ACOMPAÑANTE ================= */
        if(p.tipo === 'ACOMP'){

            let badgeMenor = (p.es_menor == 1)
                ? `<span class="pas-badge men">MENOR</span>` : "";

            html += `
            <div class="pas-card-acomp">

                <div class="pas-avatar">
                    <i class="fa fa-user"></i>
                </div>

                <div class="pas-acomp-content">
                    ${(p.nombre ?? '').toUpperCase()}
                    <span class="pas-badge acomp">ACOMP.</span>
                    ${badgeMenor}

                    <div class="pas-acomp-meta">
                        Acompañante · Hab. ${p.numero}
                        ${p.parentesco ? ' · '+p.parentesco : ''}
                    </div>
                </div>

            </div>`;
        }

    });

    box_pasajeros.innerHTML = html;
    lbl_total_pas.innerText = totalTitulares + " huéspedes";
}
/* ===============================
   HELPERS
================================ */

function formatoHora(f){
    if(!f) return "";
    let d = new Date(f);
    return d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
}

function formatoMoneda(n){
    return parseFloat(n ?? 0).toFixed(2);
}

function agregarAcompanante(){

    let html=`
    <div class="pas-acomp-row">
        <input class="pas-input" placeholder="Nombre">
        <input class="pas-input" placeholder="Parentesco">
        <button class="pas-btn-del">✖</button>
    </div>`;

    box_acomp.insertAdjacentHTML("beforeend",html);
}

function openModalPasajero(){
    document.getElementById('modal_pasajero').style.display='flex';
}

function closeModalPasajero(){
    document.getElementById('modal_pasajero').style.display='none';
}


function actualizarContadores(){

    let titulares = PASAJEROS_DATA.filter(x=>x.tipo==='TITULAR');

    let total = titulares.length;
    let activos = titulares.filter(x=>!x.hora_salida_1).length;
    let vip = titulares.filter(x=>x.cliente_frecuente==1).length;
    let bl = titulares.filter(x=>x.lista_negra==1).length;

    let menores = PASAJEROS_DATA.filter(x=>x.tipo==='ACOMP' && x.es_menor==1)
                    .map(x=>x.registro_id);

    let conMenor = [...new Set(menores)].length;

    document.getElementById('chip_all').innerText = `Todos (${total})`;
    document.getElementById('chip_act').innerText = `Activos (${activos})`;
    document.getElementById('chip_men').innerText = `Con menores (${conMenor})`;
    document.getElementById('chip_bl').innerText = `Lista negra (${bl})`;
    document.getElementById('chip_vip').innerText = `VIP (${vip})`;
}


function filtrar(tipo){

    let registrosFiltrar = [];

    if(tipo==='ALL'){
        pintarPasajeros(PASAJEROS_DATA);
        return;
    }

    if(tipo==='ACT'){
        registrosFiltrar = PASAJEROS_DATA
            .filter(x=>x.tipo==='TITULAR' && !x.hora_salida_1)
            .map(x=>x.registro_id);
    }

    if(tipo==='VIP'){
        registrosFiltrar = PASAJEROS_DATA
            .filter(x=>x.tipo==='TITULAR' && x.cliente_frecuente==1)
            .map(x=>x.registro_id);
    }

    if(tipo==='BL'){
        registrosFiltrar = PASAJEROS_DATA
            .filter(x=>x.tipo==='TITULAR' && x.lista_negra==1)
            .map(x=>x.registro_id);
    }

    if(tipo==='MEN'){
        registrosFiltrar = PASAJEROS_DATA
            .filter(x=>x.tipo==='ACOMP' && x.es_menor==1)
            .map(x=>x.registro_id);
    }

    registrosFiltrar = [...new Set(registrosFiltrar)];

    let nuevaLista = PASAJEROS_DATA
        .filter(x=>registrosFiltrar.includes(x.registro_id));

    pintarPasajeros(nuevaLista);
}

function guardarPasajero(){

    let nombre = document.querySelector('#modal_pasajero input[placeholder="Nombre"]').value;

    if(!nombre){
        alert("Nombre requerido");
        return;
    }

    fetch(base_url + "pasajeros/guardar_huesped",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body: JSON.stringify({
            nombre:nombre
        })
    })
    .then(r=>r.json())
    .then(resp=>{

        closeModalPasajero();
        cargarPasajeros();

    });

}




