/* ======================================================
   CLIENTE DESDE MODULO HABITACIÓN
   Versión ligera del catálogo
====================================================== */

let CAM_STREAM = null;
let CAM_MODO = null;

// ======================================================
// ARRAY GLOBAL CLIENTES HABITACIÓN
// ======================================================
let clientesHab = [];


// ======================================================
// ABRIR / CERRAR MODAL
// ======================================================
window.openModalCat = function(){

    document.getElementById("modal_cat").style.display = "flex";

}

window.closeModalCat = function(){

    document.getElementById("modal_cat").style.display = "none";
    limpiarClienteHab();

}


// ======================================================
// LIMPIAR FORM
// ======================================================
function limpiarClienteHab(){

    [
        "cat_id","cat_nombre","cat_apellido","cat_ident",
        "cat_tel","cat_mail","cat_dir","cat_ciudad",
        "cat_estado","cat_cp","cat_pais","cat_nac",
        "cat_fnac","cat_genero","cat_empresa","cat_notas"
    ].forEach(id=>{
        let el = document.getElementById(id);
        if(el) el.value="";
    });

}


// ======================================================
// GUARDAR CLIENTE
// ======================================================
window.guardarCat = async function(){

    // ======================================================
    // VALIDACIÓN BÁSICA
    // ======================================================
    const nombre   = cat_nombre.value.trim();
    const apellido = cat_apellido.value.trim();

    if(!nombre){
        alert("Nombre requerido");
        cat_nombre.focus();
        return;
    }

    // ======================================================
    // PAYLOAD
    // ======================================================
    const payload = {

        id: cat_id.value || null,
        nombre: nombre,
        apellido: apellido,
        tipo_identificacion_id: cat_tipo_doc.value || null,
        numero_identificacion: cat_ident.value.trim(),
        telefono: cat_tel.value.trim(),
        email: cat_mail.value.trim(),
        direccion: cat_dir.value.trim(),
        ciudad: cat_ciudad.value.trim(),
        estado: cat_estado.value.trim(),
        codigo_postal: cat_cp.value.trim(),
        pais: cat_pais.value.trim(),
        nacionalidad: cat_nac.value.trim(),
        fecha_nacimiento: cat_fnac.value || null,
        genero: cat_genero.value || null,
        empresa: cat_empresa.value.trim(),
        notas: cat_notas.value.trim()

    };

    // ======================================================
    // BLOQUEAR BOTÓN (UX PMS)
    // ======================================================
    const btn = document.querySelector(".pas-btn-save");
    if(btn) btn.disabled = true;

    try{

        const r = await fetch(base_url+"pasajeros/catalogo_guardar",{
            method:"POST",
            headers:{ "Content-Type":"application/json" },
            body: JSON.stringify(payload)
        });

        const resp = await r.json();

        if(!resp.ok){
            alert(resp.msg || "Error guardando cliente");
            if(btn) btn.disabled = false;
            return;
        }

        // ======================================================
        // ⭐ AGREGAR CLIENTE A HABITACIÓN
        // ======================================================
        if(window.agregarClienteHab){

            agregarClienteHab({
                id: resp.id,
                nombre: nombre + " " + apellido,
                telefono: payload.telefono || ""
            });

        }

        // ======================================================
        // LIMPIAR FORM
        // ======================================================
        limpiarClienteHab();

        // ======================================================
        // CERRAR MODAL
        // ======================================================
        closeModalCat();

    }catch(e){

        console.error(e);
        alert("Error de comunicación con servidor");

    }finally{

        if(btn) btn.disabled = false;

    }

}

document.addEventListener("DOMContentLoaded",()=>{

    fetch(base_url+"pasajeros/catalogo_tipos_identificacion")
    .then(r=>r.json())
    .then(data=>{

        let html = `<option value="">Tipo identificación</option>`;

        data.forEach(t=>{
            html += `<option value="${t.id}">${t.nombre}</option>`;
        });

        document.getElementById("cat_tipo_doc").innerHTML = html;

    });

});


/* ======================================================
   CAMARA CLIENTE (FOTO / DOCUMENTO)
====================================================== */






function asegurarModalCamTop(){

    const cam = document.getElementById("modal_cam");

    // ⭐ mover al body si no está ya
    if(cam && cam.parentElement !== document.body){
        document.body.appendChild(cam);
    }
}




window.capturarFoto = async function(){

    // ⭐ ocultar modal cliente
    document.getElementById("modal_cat").style.display = "none";

    document.getElementById("modal_cam").style.display = "flex";

    try{

        CAM_STREAM = await navigator.mediaDevices.getUserMedia({
            video:{ facingMode:"environment" },
            audio:false
        });

        cam_video.srcObject = CAM_STREAM;

    }catch(e){
        alert("No se pudo abrir la cámara");
    }

}

window.scanDocumento = async function(){

    document.getElementById("modal_cat").style.display = "none";

    document.getElementById("modal_cam").style.display = "flex";

    try{

        CAM_STREAM = await navigator.mediaDevices.getUserMedia({
            video:{ facingMode:"environment" },
            audio:false
        });

        cam_video.srcObject = CAM_STREAM;

    }catch(e){
        alert("No se pudo abrir la cámara");
    }

}


window.cerrarCam = function(){

    document.getElementById("modal_cam").style.display = "none";

    // ⭐ volver a mostrar cliente
    document.getElementById("modal_cat").style.display = "flex";

    if(CAM_STREAM){
        CAM_STREAM.getTracks().forEach(t=>t.stop());
        CAM_STREAM = null;
    }

}

window.tomarFoto = function(){

    const video = document.getElementById("cam_video");

    const canvas = document.createElement("canvas");

    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext("2d").drawImage(video,0,0);

    const img64 = canvas.toDataURL("image/jpeg");

    document.getElementById("cat_foto_preview").src = img64;

    cerrarCam();

}

// ======================================================
// AGREGAR CLIENTE A TABLA HABITACIÓN
// ======================================================

window.agregarClienteHab = function(cli){

    // ⭐ determinar tipo
    const tipo = clientesHab.length === 0 ? "TITULAR" : "ACOMP";

    clientesHab.push({
        id: cli.id,
        nombre: cli.nombre,
        telefono: cli.telefono,
        tipo: tipo
    });

    pintarClientesHab();

}
function reordenarClientesHab(){

    const filas = document.querySelectorAll("#tblClientesHab tbody tr");

    filas.forEach((tr,i)=>{
        tr.children[0].innerText = i+1;
    });

}

// ======================================================
// AGREGAR CLIENTE
// ======================================================
window.agregarClienteHab = function(cli){

    // ⭐ determinar tipo
    const tipo = clientesHab.length === 0 ? "TITULAR" : "ACOMP";

    clientesHab.push({
        id: cli.id,
        nombre: cli.nombre,
        telefono: cli.telefono,
        tipo: tipo
    });

    pintarClientesHab();

}

function eliminarClienteHab(i){

    clientesHab.splice(i,1);

    // ⭐ si se elimina titular → el primero nuevo será titular
    if(clientesHab.length > 0){
        clientesHab[0].tipo = "TITULAR";
    }

    pintarClientesHab();

}