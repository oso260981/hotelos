/* =====================================================
   ================== ESTADOS HABITACIÓN =================
=====================================================*/

/**
 * Abre modal para crear nuevo estado
 */
function abrirModalEstado(){

    // limpiar campos
    document.getElementById('estado_id').value = ''
    document.getElementById('estado_codigo').value = ''
    document.getElementById('estado_nombre').value = ''
    document.getElementById('estado_descripcion').value = ''
    document.getElementById('estado_icono').value = ''

    // mostrar modal
    document.getElementById('modalEstado').style.display = 'flex'
}

/**
 * Cargar datos en modal para editar estado
 */
function editarEstado(id,codigo,nombre,descripcion,icono){

    document.getElementById('estado_id').value = id
    document.getElementById('estado_codigo').value = codigo
    document.getElementById('estado_nombre').value = nombre
    document.getElementById('estado_descripcion').value = descripcion
    document.getElementById('estado_icono').value = icono

    document.getElementById('modalEstado').style.display = 'flex'
}

/**
 * Cerrar modal estado
 */
function cerrarModalEstado(){
    document.getElementById('modalEstado').style.display = 'none'
}

/**
 * Activar / desactivar estado
 */
function toggleEstado(id,el){

    let activo = el.checked ? 1 : 0

    fetch(base_url+"ajax/estado/toggle",{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id=${id}&activo=${activo}`
    })
}

/**
 * Eliminar estado
 */
function eliminarEstado(id){

    if(!confirm('Eliminar estado?')) return

    fetch(base_url+"ajax/estado/delete",{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id=${id}`
    })
    .then(()=>location.reload())
}


/* =====================================================
   ================== TABS CONFIG ======================
=====================================================*/

/**
 * Cambiar pestaña configuración sistema
 */
/* ==========================================
   CONTROL TABS CONFIGURACIÓN SISTEMA
========================================== */
function tabCfg(n,el){

    // ⭐ activar visual tab
    document.querySelectorAll('.rtab')
        .forEach(t=>t.classList.remove('active'))

    el.classList.add('active')

    // ⭐ ocultar paneles
    document.querySelectorAll('.cfg-panel')
        .forEach(p=>p.style.display='none')

    // ⭐ mostrar panel activo
    let panel = document.getElementById('cfg'+n)
    if(panel) panel.style.display='block'

    /* ===============================
       TAB 2 → configuración ticket
    =============================== */
    if(n==2){
        loadActiveTicketConfig()
    }

    /* ===============================
       TAB 3 → usuarios / roles
    =============================== */
    if(n==3){
        loadUsuarios()
        loadRolesGrid()
    }

    /* ===============================
       TAB 4 → timeline turnos
    =============================== */
    if(n==4){
        //setTimeout(renderTimelineFromDB,200)
    }

    /* ===============================
       TAB 5 → tipos habitación
    =============================== */
    if(n==5){
        loadTiposHabGrid()
    }

    // guardar tab activo
    localStorage.setItem("cfg_tab", n)

}



/**
 * Inicializar tab guardada
 */
document.addEventListener("DOMContentLoaded",function(){

    let last = localStorage.getItem("cfg_tab") ?? 1
    let tabs = document.querySelectorAll(".rtab")

    if(tabs[last-1]){
        tabCfg(last,tabs[last-1])
    }

})


/* =====================================================
   ================== EMPRESAS =========================
=====================================================*/

// empresa seleccionada en grid
let empresaSel = null


/**
 * Abrir catálogo razones sociales
 */
function openModalEmpresa(){

    document.getElementById('modalEmpresa').style.display = 'flex'
    loadEmpresas()

}

/**
 * Cerrar catálogo
 */
function closeModalEmpresa(){

    document.getElementById('modalEmpresa').style.display = 'none'

}


/**
 * Cargar listado empresas
 */
function loadEmpresas(){

    fetch(base_url+"empresas/list")
    .then(r=>r.json())
    .then(data=>{

        let html = ''

        data.forEach(e=>{

            html += `
            <tr onclick="selectRowEmpresa(
                this,
                ${e.id},
                '${e.nombre}',
                '${e.rfc}',
                '${e.telefono}',
                '${e.direccion ?? ''}'
            )">
                <td><input type="radio"></td>
                <td>${e.nombre}</td>
                <td>${e.rfc}</td>
                <td>${e.telefono}</td>
            </tr>
            `
        })

        document.getElementById('gridEmpresas').innerHTML = html

        // limpiar selección actual
        empresaSel = null

    })

}


/**
 * Seleccionar fila grid
 */
function selectRowEmpresa(tr,id,nombre,rfc,tel,dir){

    // limpiar highlight
    document.querySelectorAll('#gridEmpresas tr')
        .forEach(r=>r.style.background='')

    // resaltar fila
    tr.style.background = '#dbeafe'

    // guardar selección
    empresaSel = {id,nombre,rfc,tel,dir}

}


/**
 * Enviar empresa seleccionada al panel ticket
 */
function seleccionarEmpresa(){

    if(!empresaSel){
        alert("Selecciona una razón social")
        return
    }

    document.getElementById('tk_rs_id').value = empresaSel.id
    document.getElementById('tk_nombre').value = empresaSel.nombre
    document.getElementById('tk_rfc').value = empresaSel.rfc
    document.getElementById('tk_tel').value = empresaSel.tel

    closeModalEmpresa()

}


/* =====================================================
   ============ CAMBIO VISTA GRID ↔ FORM =================
=====================================================*/

/**
 * Mostrar formulario alta / edición
 */
function showFormEmpresa(){

    document.getElementById('vistaGridEmpresas').style.display = 'none'
    document.getElementById('vistaFormEmpresa').style.display = 'block'

    document.getElementById('footGridEmpresa').style.display = 'none'
    document.getElementById('footFormEmpresa').style.display = 'flex'

    document.getElementById('btnCerrarEmpresa').style.display = 'none'
    document.getElementById('btnBackEmpresa').style.display = 'inline-block'

}

/**
 * Volver al grid catálogo
 */
function backGridEmpresa(){

    document.getElementById('vistaGridEmpresas').style.display = 'block'
    document.getElementById('vistaFormEmpresa').style.display = 'none'

    document.getElementById('footGridEmpresa').style.display = 'flex'
    document.getElementById('footFormEmpresa').style.display = 'none'

    document.getElementById('btnCerrarEmpresa').style.display = 'inline-block'
    document.getElementById('btnBackEmpresa').style.display = 'none'

}


/* =====================================================
   ================== FORM EMPRESA =====================
=====================================================*/

/**
 * Limpiar formulario empresa
 */
function clearFormEmpresa(){

    document.getElementById('emp_id').value = ''
    document.getElementById('emp_nombre').value = ''
    document.getElementById('emp_rfc').value = ''
    document.getElementById('emp_tel').value = ''
    document.getElementById('emp_dir').value = ''

}


/**
 * Alta nueva empresa
 */
function newEmpresa(){

    clearFormEmpresa()
    showFormEmpresa()

}


/**
 * Editar empresa seleccionada
 */
function editEmpresa(){

    if(!empresaSel){
        alert("Selecciona una empresa")
        return
    }

    document.getElementById('emp_id').value = empresaSel.id
    document.getElementById('emp_nombre').value = empresaSel.nombre
    document.getElementById('emp_rfc').value = empresaSel.rfc
    document.getElementById('emp_tel').value = empresaSel.tel
    document.getElementById('emp_dir').value = empresaSel.dir

    showFormEmpresa()

}


/**
 * Guardar empresa (alta o edición)
 * Incluye upload de logo
 */
function saveEmpresa(){

    let fd = new FormData()

    // campos normales
    fd.append("id",document.getElementById('emp_id').value)
    fd.append("nombre",document.getElementById('emp_nombre').value)
    fd.append("rfc",document.getElementById('emp_rfc').value)
    fd.append("telefono",document.getElementById('emp_tel').value)
    fd.append("direccion",document.getElementById('emp_dir').value)

    // ⭐ ARCHIVO LOGO
    let file = document.getElementById('emp_logo').files[0]
    if(file){
        fd.append("logo",file)
    }

    fetch(base_url+"empresas/save",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{

        loadEmpresas()
        backGridEmpresa()

    })

}



/**
 * Eliminar empresa seleccionada
 */
function deleteEmpresa(){

    if(!empresaSel){
        alert("Selecciona empresa")
        return
    }

    if(!confirm("¿Eliminar razón social?")) return

  

    let fd = new FormData()
    fd.append("id",empresaSel.id)

    fetch(base_url+"empresas/delete",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{

        loadEmpresas()

    })

}
