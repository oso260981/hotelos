let usuarioSel = null

function openModalUsuario(){

    limpiarUsuarioForm()
    document.getElementById('modalUsuario').style.display='flex'

}

function closeModalUsuario(){
    document.getElementById('modalUsuario').style.display='none'
}

function limpiarUsuarioForm(){

    document.getElementById('usr_id').value=''
    document.getElementById('usr_username').value=''
    document.getElementById('usr_password').value=''
    document.getElementById('usr_nombre').value=''
    document.getElementById('usr_apellido').value=''
    document.getElementById('usr_telefono').value=''
    document.getElementById('usr_direccion').value=''

}

function saveUsuario(){

    let fd = new FormData()

    fd.append("id",document.getElementById('usr_id').value)
    fd.append("username",document.getElementById('usr_username').value)
    fd.append("password",document.getElementById('usr_password').value)
    fd.append("nombre",document.getElementById('usr_nombre').value)
    fd.append("apellido",document.getElementById('usr_apellido').value)
    fd.append("telefono",document.getElementById('usr_telefono').value)
    fd.append("direccion",document.getElementById('usr_direccion').value)
    fd.append("rol_id",document.getElementById('usr_rol').value)
   

    fetch(base_url+"usuarios/save",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        closeModalUsuario()
        loadUsuarios()
    })

}

/**
 * Cargar usuarios al grid de claves
 * (tabla dinámica dentro del tab)
 */
function loadUsuarios(){

    fetch(base_url + "usuarios/list",{
        method:"POST"
    })
    .then(r => r.json())
    .then(data => {

        let html = ''

        data.forEach(u => {

            html += `
<tr>

    <td>${u.username}</td>

    <td>${u.nombre ?? ''} ${u.apellido ?? ''}</td>

    <td>
        <span class="badge-role">
            ${u.rol ?? ''}
        </span>
    </td>

    <td style="text-align:center">
        ${u.recepcion==1
            ? '<span style="color:#16a34a;font-weight:600">✔</span>'
            : '<span style="color:#dc2626;font-weight:600">✖</span>'
        }
    </td>

    <td style="text-align:center">
        ${u.reportes==1
            ? '<span style="color:#16a34a;font-weight:600">✔</span>'
            : '<span style="color:#dc2626;font-weight:600">✖</span>'
        }
    </td>

    <td style="text-align:center">
        ${u.editar_sistema==1
            ? '<span style="color:#16a34a;font-weight:600">✔</span>'
            : '<span style="color:#dc2626;font-weight:600">✖</span>'
        }
    </td>

    <td style="text-align:center">
        <label class="switch">
            <input type="checkbox"
                ${u.activo==1?'checked':''}
                onchange="toggleUsuario(${u.id},this)">
            <span></span>
        </label>
    </td>

    <td>
        <button class="btn-primary btn-sm"
            onclick="event.stopPropagation(); editUsuarioDirect(
                ${u.id},
                '${u.username}',
                '${u.nombre ?? ''}',
                '${u.apellido ?? ''}',
                '${u.telefono ?? ''}',
                '${u.direccion ?? ''}',
                ${u.rol_id ?? 0}
            )">
            Editar
        </button>
    </td>

</tr>
`


        })

        document.getElementById("gridUsuarios").innerHTML = html

    })

}



function selectUsuarioRow(tr,id,username,nombre,apellido,tel,dir,rol,turno){

    document.querySelectorAll("#gridUsuarios tr")
        .forEach(r => r.style.background='')

    tr.style.background = "#dbeafe"

    usuarioSel = {
        id,username,nombre,apellido,tel,dir,rol,turno
    }

}


function editUsuario(id){

    if(!usuarioSel) return

    document.getElementById('usr_id').value = usuarioSel.id
    document.getElementById('usr_username').value = usuarioSel.username
    document.getElementById('usr_nombre').value = usuarioSel.nombre
    document.getElementById('usr_apellido').value = usuarioSel.apellido
    document.getElementById('usr_telefono').value = usuarioSel.tel
    document.getElementById('usr_direccion').value = usuarioSel.dir
    document.getElementById('usr_rol').value = usuarioSel.rol
    document.getElementById('usr_turno').value = usuarioSel.turno

    document.getElementById('modalUsuario').style.display = 'flex'

}


/**
 * Cargar roles en la tabla del panel claves
 */
function loadRolesGrid(){

    fetch(base_url + "roles/list",{
        method:"POST"
    })
    .then(r => r.json())
    .then(data => {

        let html = ''

        data.forEach(r => {

            html += `
            <tr onclick="selectRolRow(
                    this,
                    ${r.id},
                    '${r.nombre}',
                    ${r.recepcion},
                    ${r.reportes},
                    ${r.editar_sistema}
            )">

                <td>${r.nombre}</td>

                <td>
                    ${r.recepcion == 1
                        ? '<span style="color:#16a34a;font-weight:600">✔ Completo</span>'
                        : '<span style="color:#dc2626;font-weight:600">✖ Sin acceso</span>'
                    }
                </td>

                <td>
                    ${r.reportes == 1
                        ? '<span style="color:#16a34a;font-weight:600">✔ Completo</span>'
                        : '<span style="color:#dc2626;font-weight:600">✖ Sin acceso</span>'
                    }
                </td>

                <td>
                    ${r.editar_sistema == 1
                        ? '<span style="color:#16a34a;font-weight:600">✔ Completo</span>'
                        : '<span style="color:#dc2626;font-weight:600">✖ Sin acceso</span>'
                    }
                </td>

                <td>
                    <button class="btn-primary btn-sm"
    onclick="event.stopPropagation(); editRolDirect(
        ${r.id},
        '${r.nombre}',
        ${r.recepcion},
        ${r.reportes},
        ${r.editar_sistema}
    )">
    Editar
</button>
                </td>

            </tr>
            `
        })

        document.getElementById("gridRoles").innerHTML = html

    })

}

let rolSel = null

function selectRolRow(tr,id,nombre,recepcion,reportes,editar){

    document.querySelectorAll("#gridRoles tr")
        .forEach(r => r.style.background='')

    tr.style.background = "#dbeafe"

    rolSel = {
        id:id,
        nombre:nombre,
        recepcion:recepcion,
        reportes:reportes,
        editar:editar
    }

}



function openModalRol(){

    limpiarRolForm()

    document.getElementById('modalRol').style.display='flex'

}

function closeModalRol(){
    document.getElementById('modalRol').style.display='none'
}

function limpiarRolForm(){

    document.getElementById('rol_id').value=''
    document.getElementById('rol_nombre').value=''
    document.getElementById('rol_recepcion').checked=false
    document.getElementById('rol_reportes').checked=false
    document.getElementById('rol_sistema').checked=false

}

function editRol(){

    if(!rolSel){
        alert("Selecciona un rol")
        return
    }

    document.getElementById('rol_id').value = rolSel.id
    document.getElementById('rol_nombre').value = rolSel.nombre

    document.getElementById('rol_recepcion').checked = rolSel.recepcion == 1
    document.getElementById('rol_reportes').checked = rolSel.reportes == 1
    document.getElementById('rol_sistema').checked = rolSel.editar == 1

    document.getElementById('modalRol').style.display = 'flex'

}


function saveRol(){

    let fd = new FormData()

    fd.append("id",document.getElementById('rol_id').value)
    fd.append("nombre",document.getElementById('rol_nombre').value)
    fd.append("recepcion",document.getElementById('rol_recepcion').checked ? 1:0)
    fd.append("reportes",document.getElementById('rol_reportes').checked ? 1:0)
    fd.append("editar_sistema",document.getElementById('rol_sistema').checked ? 1:0)

    fetch(base_url+"roles/save",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{

        closeModalRol()
        loadRolesGrid()

    })

}

function deleteRol(){

    let id = document.getElementById('rol_id').value

    if(!id){
        alert("Rol no válido")
        return
    }

    if(!confirm("¿Eliminar rol?")) return

    let fd = new FormData()
    fd.append("id", id)

    fetch(base_url+"roles/delete",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        closeModalRol()
        loadRolesGrid()
    })

}


function editRolDirect(id,nombre,recepcion,reportes,editar){

    document.getElementById('rol_id').value = id
    document.getElementById('rol_nombre').value = nombre

    document.getElementById('rol_recepcion').checked = recepcion == 1
    document.getElementById('rol_reportes').checked = reportes == 1
    document.getElementById('rol_sistema').checked = editar == 1

    document.getElementById('modalRol').style.display = 'flex'

}

function deleteRolDirect(id){

    if(!confirm("¿Eliminar rol seleccionado?")) return

    let fd = new FormData()
    fd.append("id", id)

    fetch(base_url + "roles/delete",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        loadRolesGrid()
    })

}


function editUsuarioDirect(id,username,nombre,apellido,tel,dir,rol_id){

    document.getElementById('usr_id').value = id
    document.getElementById('usr_username').value = username
    document.getElementById('usr_nombre').value = nombre
    document.getElementById('usr_apellido').value = apellido
    document.getElementById('usr_telefono').value = tel
    document.getElementById('usr_direccion').value = dir

    // ⭐ cargar roles primero
    fetch(base_url + "roles/list",{
        method:'POST'
    })
    .then(r=>r.json())
    .then(data=>{

        let html = '<option value="">Seleccione rol</option>'

        data.forEach(r=>{
            html += `<option value="${r.id}">${r.nombre}</option>`
        })

        document.getElementById('usr_rol').innerHTML = html

        // ⭐ ahora sí seleccionar rol
        document.getElementById('usr_rol').value = rol_id

    })

    document.getElementById('modalUsuario').style.display='flex'

}


function newUsuario(){

    document.getElementById('usr_id').value=''
    document.getElementById('usr_username').value=''
    document.getElementById('usr_password').value=''
    document.getElementById('usr_nombre').value=''
    document.getElementById('usr_apellido').value=''
    document.getElementById('usr_telefono').value=''
    document.getElementById('usr_direccion').value=''

    loadRoles()
    loadTurnos()

    document.getElementById('modalUsuario').style.display='flex'

}

function deleteUsuarioDirect(id){

    if(!confirm("¿Eliminar usuario?")) return

    let fd = new FormData()
    fd.append("id",id)

    fetch(base_url+"usuarios/delete",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{
        loadUsuarios()
    })

}


/**
 * Cargar roles en combo usuario
 */
function loadRoles(){

    fetch(base_url + "roles/list",{
        method:'POST'
    })
    .then(r=>r.json())
    .then(data=>{

        let html = '<option value="">Seleccione rol</option>'

        data.forEach(r=>{
            html += `<option value="${r.id}">${r.nombre}</option>`
        })

        document.getElementById('usr_rol').innerHTML = html

    })

}


function loadTurnos(){

    fetch(base_url + "turnos/list",{
        method:'POST'
    })
    .then(r=>r.json())
    .then(data=>{

        let html = '<option value="">Seleccione turno</option>'

        data.forEach(t=>{
            html += `<option value="${t.id}">${t.nombre}</option>`
        })

        document.getElementById('usr_turno').innerHTML = html

    })

}


function toggleUsuario(id,el){

    let activo = el.checked ? 1 : 0

    let fd = new FormData()
    fd.append("id",id)
    fd.append("activo",activo)

    fetch(base_url + "usuarios/toggle",{
        method:'POST',
        body:fd
    })
    .then(r=>r.json())
    .then(()=>{

        // efecto visual opcional
        el.closest("tr").style.opacity = activo ? 1 : .4

    })

}









