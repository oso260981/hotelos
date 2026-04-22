/* =====================================================
   MODAL TIPO HABITACIÓN
===================================================== */
function openModalTipoHab(){

    // ⭐ limpiar campos
    document.getElementById('th_id').value = ''
    document.getElementById('th_nombre').value = ''
    document.getElementById('th_clave').value = ''
    document.getElementById('th_precio').value = ''
    document.getElementById('th_extra').value = ''
    document.getElementById('th_personas').value = ''
    document.getElementById('th_color').value = ''
    document.getElementById('th_icono').value = ''
    document.getElementById('th_orden').value = ''

    // ⭐ ocultar botón eliminar
    document.getElementById('btnEliminarTipoHab').style.display = 'none'

    // ⭐ abrir modal centrado
    document.getElementById('modalTipoHab').style.display = 'flex'
}

/* cerrar modal */
function closeModalTipoHab(){
    document.getElementById('modalTipoHab').style.display = 'none'
}

/* =====================================================
   GUARDAR TIPO HABITACIÓN
===================================================== */
function saveTipoHab(){

    let fd = new FormData()

    fd.append("id", document.getElementById('th_id').value)
    fd.append("nombre", document.getElementById('th_nombre').value)
    fd.append("clave", document.getElementById('th_clave').value)
    fd.append("precio_base", document.getElementById('th_precio').value)
    fd.append("precio_persona_extra", document.getElementById('th_extra').value)
    fd.append("personas_max", document.getElementById('th_personas').value)
    fd.append("color", document.getElementById('th_color').value)
    fd.append("icono", document.getElementById('th_icono').value)
    fd.append("orden", document.getElementById('th_orden').value)

    fetch(base_url + "habitacionTipos/saveTipoHabitacion",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        closeModalTipoHab()
        loadTiposHabGrid()
    })
}

/* =====================================================
   CARGAR GRID TIPOS HABITACIÓN
===================================================== */
function loadTiposHabGrid(){

    fetch(base_url + "habitacionTipos/listTiposHabitacion",{
        method:'GET'
    })
    .then(r=>r.json())
    .then(data=>{

        let html = ''

        data.forEach(t => {

            let obj = encodeURIComponent(JSON.stringify(t))

            html += `
<tr>
    <td>${t.nombre}</td>
    <td>$${parseFloat(t.precio_base).toFixed(2)}</td>
    <td>$${parseFloat(t.iva).toFixed(2)}</td>
    <td>$${parseFloat(t.ish).toFixed(2)}</td>
    <td style="font-weight:600">$${parseFloat(t.precio_total).toFixed(2)}</td>
    <td>
        <button class="btn-primary btn-sm"
            onclick="editTipoHabDirect('${obj}')">
            Editar
        </button>
    </td>
</tr>`
        })

        document.getElementById("gridTiposHab").innerHTML = html
    })
}

/* =====================================================
   EDITAR TIPO HABITACIÓN
===================================================== */
function editTipoHabDirect(obj){

    let t = JSON.parse(decodeURIComponent(obj))

    document.getElementById('th_id').value = t.id
    document.getElementById('th_nombre').value = t.nombre
    document.getElementById('th_clave').value = t.clave ?? ''
    document.getElementById('th_precio').value = t.precio_base
    document.getElementById('th_extra').value = t.precio_persona_extra
    document.getElementById('th_personas').value = t.personas_max
    document.getElementById('th_color').value = t.color ?? ''
    document.getElementById('th_icono').value = t.icono ?? ''
    document.getElementById('th_orden').value = t.orden ?? ''

    // ⭐ mostrar eliminar
    document.getElementById('btnEliminarTipoHab').style.display = 'inline-block'

    // ⭐ abrir modal centrado
    document.getElementById('modalTipoHab').style.display = 'flex'
}

/* =====================================================
   ELIMINAR
===================================================== */
function deleteTipoHab(){

    let id = document.getElementById('th_id').value
    if(!id) return

    if(!confirm("¿Eliminar tipo de habitación?")) return

    let fd = new FormData()
    fd.append("id", id)

    fetch(base_url + "habitacionTipos/delete",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        closeModalTipoHab()
        loadTiposHabGrid()
    })
}


/* ==========================================
   RECALCULAR CATÁLOGO EN BD
========================================== */
function recalcularCatalogoBD(){

    if(!confirm("Se recalcularán todos los impuestos del catálogo ¿continuar?"))
        return

    fetch(base_url + "habitacionTipos/recalcular",{
        method:'POST'
    })
    .then(r=>r.json())
    .then(resp=>{

        if(resp.ok){

            // ⭐ volver a cargar tabla
            loadTiposHabGrid()

        }else{
            alert("Error al recalcular")
        }

    })
}