/* =====================================================
   MÓDULO: CONFIGURACIÓN DE TICKET
   Maneja catálogo de layouts de ticket térmico
   Permite:
   - listar configuraciones
   - seleccionar configuración activa
   - cargar configuración activa
   - guardar cambios en configuración activa
=====================================================*/


/* =====================================================
   VARIABLE GLOBAL SELECCIÓN
   Guarda config seleccionada en modal
=====================================================*/
let ticketConfigSel = null



/* =====================================================
   ABRIR MODAL CONFIGURACIONES TICKET
=====================================================*/
function openModalTicketConfig(){

    document.getElementById('modalTicketConfig').style.display = 'flex'

    loadTicketConfigs()
}


/* =====================================================
   CERRAR MODAL CONFIGURACIONES
=====================================================*/
function closeModalTicketConfig(){

    document.getElementById('modalTicketConfig').style.display = 'none'
}



/* =====================================================
   CARGAR LISTADO CONFIGURACIONES DESDE BD
=====================================================*/
function loadTicketConfigs(){

    fetch(base_url + "/ticketconfig/list")
    .then(r => r.json())
    .then(data => {

        let html = ''

        data.forEach(c => {

            html += `
            <tr onclick="selectRowTicketConfig(
                this,
                ${c.id},
                '${c.ancho_mm}',
                '${c.copias}',
                '${c.mensaje_pie}'
            )">
                <td><input type="radio"></td>
                <td>${c.ancho_mm} mm</td>
                <td>${c.copias}</td>
                <td>${c.mensaje_pie ?? ''}</td>
                <td>${c.activo == 1 ? '✔' : ''}</td>
            </tr>
            `
        })

        document.getElementById('gridTicketConfig').innerHTML = html

    })
}



/* =====================================================
   SELECCIONAR FILA EN MODAL
   Guarda config temporal seleccionada
=====================================================*/
function selectRowTicketConfig(tr,id,ancho,copias,mensaje){

    document.querySelectorAll('#gridTicketConfig tr')
        .forEach(r => r.style.background = '')

    tr.style.background = '#dbeafe'

    ticketConfigSel = {
        id : id,
        ancho : ancho,
        copias : copias,
        mensaje : mensaje
    }
}










/* =====================================================
   GUARDAR CAMBIOS EN CONFIGURACIÓN ACTIVA
   NO crea nuevo registro
   Solo actualiza layout activo
=====================================================*/
function saveActiveTicketConfig(){

    let fd = new FormData()


    fd.append("razon_social_id",document.getElementById('tk_rs_id').value)

    /* ===== básicos ===== */

    fd.append("ancho_mm", document.getElementById('tk_ancho').value)
    fd.append("copias", document.getElementById('tk_copias').value)
    fd.append("logo_visible", document.getElementById('tk_logo').value)
    fd.append("mensaje_pie", document.getElementById('tk_mensaje').value)

    /* ===== switches ===== */

    fd.append("ver_habitacion", document.getElementById('sw_habitacion').checked ? 1 : 0)
    fd.append("ver_huesped", document.getElementById('sw_huesped').checked ? 1 : 0)
    fd.append("ver_fecha", document.getElementById('sw_fecha').checked ? 1 : 0)
    fd.append("ver_desglose", document.getElementById('sw_desglose').checked ? 1 : 0)
    fd.append("ver_pago", document.getElementById('sw_pago').checked ? 1 : 0)
    fd.append("ver_folio", document.getElementById('sw_folio').checked ? 1 : 0)

    fetch(base_url + "ticketconfig/updateActive",{
        method : "POST",
        body : fd
    })
    .then(r => r.json())
    .then(d => {

        if(d.ok){
            alert("Configuración de ticket guardada")
        }else{
            alert("Error al guardar configuración")
        }

    })
}

/* =====================================================
   ABRIR MODAL NUEVA CONFIGURACIÓN TICKET
   Ya NO depende del TAB Tickets
=====================================================*/
function newTicketConfig(){

    document.getElementById('modalNewTicketConfig').style.display = 'flex'

    document.getElementById('new_tc_nombre').value = ''
}

/* =====================================================
   IR A PANTALLA EDITAR SISTEMA → TAB TICKETS
=====================================================*/
function goTicketConfigScreen(){

    // abrir módulo editar sistema (ajusta según tu sistema)
    openEditarSistema()   // ← esta es tu función existente

    setTimeout(()=>{

        let tab = document.getElementById('tabTickets')

        if(tab){
            tabCfg(2, tab)
        }

    },200)
}



function loadTicketConfigs(){

    fetch(base_url+"/ticketconfig/list")
    .then(r=>r.json())
    .then(data=>{

        let html=''

        data.forEach(c=>{

            html+=`
            <tr onclick="selectRowTicketConfig(this,${c.id})">
                <td><input type="radio"></td>
                <td>${c.nombre}</td>
                <td>${c.descripcion ?? ''}</td>
                <td>${c.razon_social ?? ''}</td>
                <td>${c.created_at}</td>
               <td>${c.activo==1 ? '✔' : '✖'}</td>
            </tr>
            `
        })

        gridTicketConfig.innerHTML=html
    })
}

/* =====================================================
   SELECCIONAR FILA CONFIGURACIÓN
=====================================================*/
function selectRowTicketConfig(tr,id){

    document.querySelectorAll('#gridTicketConfig tr')
        .forEach(r=>r.style.background='')

    tr.style.background='#dbeafe'

    ticketConfigSel = {
        id : id
    }
}

/* =====================================================
   CONFIRMAR SELECCIÓN CONFIGURACIÓN
   - Activa registro
   - Obtiene detalle completo
   - Carga layout en pantalla principal
=====================================================*/
function selectTicketConfig(){

    if(!ticketConfigSel){
        alert("Selecciona configuración")
        return
    }

    let fd = new FormData()
    fd.append("id", ticketConfigSel.id)

    fetch(base_url+"ticketconfig/activate",{
        method:'POST',
        body: fd
    })
    .then(r=>r.json())
    .then(d=>{

        if(!d.ok){
            alert("Error al activar configuración")
            return
        }

        // refrescar grid
        loadTicketConfigs()

        // cargar detalle layout
        return fetch(base_url+"ticketconfig/detail?id="+ticketConfigSel.id)

    })
    .then(r=>r.json())
    .then(cfg=>{

        if(!cfg) return

        document.getElementById('tk_ancho').value = cfg.ancho_mm
        document.getElementById('tk_copias').value = cfg.copias
        document.getElementById('tk_mensaje').value = cfg.mensaje_pie ?? ''

        document.getElementById('tk_logo').value =
            cfg.logo_visible == 1 ? "1" : "0"

        document.getElementById('sw_habitacion').checked = cfg.ver_habitacion == 1
        document.getElementById('sw_huesped').checked = cfg.ver_huesped == 1
        document.getElementById('sw_fecha').checked = cfg.ver_fecha == 1
        document.getElementById('sw_desglose').checked = cfg.ver_desglose == 1
        document.getElementById('sw_pago').checked = cfg.ver_pago == 1
        document.getElementById('sw_folio').checked = cfg.ver_folio == 1

        // 🔥 cerrar modal AL FINAL DEL TODO
        closeModalTicketConfig()

    })
    .catch(err=>{
        console.error("Error flujo ticket config", err)
    })
}




function closeModalNewTicketConfig(){
    document.getElementById('modalNewTicketConfig').style.display = 'none'
}

function saveNewTicketConfig(){

    let nombre = document.getElementById('new_tc_nombre').value
    let desc   = document.getElementById('new_tc_desc').value

    if(nombre.trim()===''){
        alert("Escribe nombre de configuración")
        return
    }

    let fd = new FormData()
    fd.append("nombre", nombre)
    fd.append("descripcion", desc)
    fd.append("razon_social_id", empresaSel?.id ?? 0)

    fetch(base_url + "/ticketconfig/create",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(d=>{
        closeModalNewTicketConfig()
        loadTicketConfigs()
    })
}

function showFormTicketConfig(){

    document.getElementById('vistaGridTicketConfig').style.display = 'none'
    document.getElementById('vistaFormTicketConfig').style.display = 'block'

    document.getElementById('footGridTicketConfig').style.display = 'none'
    document.getElementById('footFormTicketConfig').style.display = 'block'

    document.getElementById('tc_id').value = ''
    document.getElementById('tc_nombre').value = ''
    document.getElementById('tc_desc').value = ''
}


function showGridTicketConfig(){

    document.getElementById('vistaGridTicketConfig').style.display = 'block'
    document.getElementById('vistaFormTicketConfig').style.display = 'none'

    document.getElementById('footGridTicketConfig').style.display = 'block'
    document.getElementById('footFormTicketConfig').style.display = 'none'
}

function saveTicketConfig(){

    let fd = new FormData()

    fd.append("id", document.getElementById('tc_id').value)
    fd.append("nombre", document.getElementById('tc_nombre').value)
    fd.append("descripcion", document.getElementById('tc_desc').value)
    fd.append("razon_social_id",empresaSel ? empresaSel.id : '')


    fetch(base_url+"ticketconfig/create",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(d=>{
        showGridTicketConfig()
        loadTicketConfigs()
    })
}


function loadActiveTicketConfig(){

    fetch(base_url+"ticketconfig/active")
    .then(r=>r.json())
    .then(cfg=>{

        if(!cfg){
            console.warn("No hay ticket activo")
            return
        }

        document.getElementById('tk_nombre').value = cfg.rs_nombre ?? ''
        document.getElementById('tk_rfc').value = cfg.rs_rfc ?? ''
        document.getElementById('tk_tel').value = cfg.rs_tel ?? ''
        document.getElementById('tk_rs_id').value = cfg.razon_social_id ?? ''

        document.getElementById('tk_ancho').value = cfg.ancho_mm
        document.getElementById('tk_copias').value = cfg.copias
        document.getElementById('tk_mensaje').value = cfg.mensaje_pie ?? ''

        document.getElementById('tk_logo').value =
            cfg.logo_visible == 1 ? "1" : "0"

        document.getElementById('sw_habitacion').checked = cfg.ver_habitacion == 1
        document.getElementById('sw_huesped').checked = cfg.ver_huesped == 1
        document.getElementById('sw_fecha').checked = cfg.ver_fecha == 1
        document.getElementById('sw_desglose').checked = cfg.ver_desglose == 1
        document.getElementById('sw_pago').checked = cfg.ver_pago == 1
        document.getElementById('sw_folio').checked = cfg.ver_folio == 1

        // ⭐ cargar razón social si quieres
        document.getElementById('tk_rs_id').value = cfg.razon_social_id ?? ''

    })
}

/**
 * Eliminar configuración de ticket seleccionada
 */
function deleteTicketConfig(){

    // validar selección
    if(!ticketConfigSel){
        alert("Selecciona una configuración")
        return
    }

    // confirmar acción
    if(!confirm("¿Eliminar configuración de ticket?")) return

    let fd = new FormData()
    fd.append("id", ticketConfigSel.id)

    fetch(base_url + "ticketconfig/delete", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(resp => {

        // recargar grid
        loadTicketConfigs()

        // limpiar selección
        ticketConfigSel = null

    })

}





