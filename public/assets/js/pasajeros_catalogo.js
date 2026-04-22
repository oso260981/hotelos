/* ======================================================
   DATA GLOBAL
====================================================== */
let CAT = [];
let CAM_STREAM = null;
let CAM_MODO = null;   // DOC | FOTO


/* ======================================================
   INIT
====================================================== */
document.addEventListener("DOMContentLoaded",()=>{

    window.box_catalogo = document.getElementById("box_catalogo");
    window.lbl_total_cat = document.getElementById("lbl_total_cat");

    cargarCatalogo();
    cargarTiposDoc();

    document.getElementById("txt_buscar")
        .addEventListener("keyup", filtrarCat);

});


/* ======================================================
   CARGA LISTADO
====================================================== */
function cargarCatalogo(){

    fetch(base_url+"pasajeros/catalogo_listado")
    .then(r=>r.json())
    .then(data=>{
        CAT = data || [];
        pintarCatalogo(CAT);
    })
    .catch(e=>{
        console.error("Error cargando catálogo",e);
    });

}

function pintarCatalogo(lista){

    let html="";

    lista.forEach(p=>{

        html += `
        <div class="pas-card-main">

            <div class="pas-avatar">
                <i class="fa fa-user"></i>
            </div>

            <div class="pas-main-content">

                <div class="pas-main-title">
                    ${(p.nombre || '').toUpperCase()} ${(p.apellido || '').toUpperCase()}
                </div>

                <div class="pas-main-meta">
                    ${p.numero_identificacion || ''} ·
                    ${p.telefono || ''} ·
                    ${p.email || ''}
                </div>

            </div>

            <div class="pas-actions">

                <button class="pas-btn-icon edit"
                    onclick="editarCat(${p.id})">✏</button>

                <button class="pas-btn-icon scan"
                    onclick="scanDocumento(${p.id})">📄</button>

                <button class="pas-btn-icon delete"
                    onclick="bajaCat(${p.id})">🗑</button>

            </div>

        </div>`;
    });

    box_catalogo.innerHTML = html;
    lbl_total_cat.innerText = lista.length + " clientes";
}


/* ======================================================
   FILTRO
====================================================== */
function filtrarCat(){

    let t = this.value.toLowerCase();

    let lista = CAT.filter(x=>
        (x.nombre || '').toLowerCase().includes(t) ||
        (x.apellido || '').toLowerCase().includes(t) ||
        (x.numero_identificacion || '').toLowerCase().includes(t)
    );

    pintarCatalogo(lista);
}


/* ======================================================
   MODAL CLIENTE
====================================================== */
function openModalCat(){
    document.getElementById("modal_cat").style.display="flex";
}

function closeModalCat(){
    document.getElementById("modal_cat").style.display="none";
    limpiarCat();
}

function limpiarCat(){

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

function editarCat(id){

    let p = CAT.find(x=>x.id==id);
    if(!p) return;

    openModalCat();

    cat_id.value = p.id;
    cat_nombre.value = p.nombre || "";
    cat_apellido.value = p.apellido || "";
    cat_ident.value = p.numero_identificacion || "";
    cat_tel.value = p.telefono || "";
    cat_mail.value = p.email || "";

    document.getElementById("cat_foto_preview").src =
        p.fotografia
        ? base_url + p.fotografia
        : base_url + "assets/img/user.png";

}


/* ======================================================
   GUARDAR
====================================================== */
function guardarCat(){

    if(!cat_nombre.value.trim()){
        alert("Nombre requerido");
        return;
    }

    let payload = {

        id: cat_id.value || null,
        nombre: cat_nombre.value.trim(),
        apellido: cat_apellido.value.trim(),
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

    fetch(base_url+"pasajeros/catalogo_guardar",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify(payload)
    })
    .then(r=>r.json())
    .then(resp=>{

        if(!resp.ok){
            alert(resp.msg || "Error");
            return;
        }

        closeModalCat();
        cargarCatalogo();

    })
    .catch(e=>{
        console.error("Error guardar",e);
    });

}


/* ======================================================
   BAJA
====================================================== */
function bajaCat(id){

    if(!confirm("¿Dar de baja cliente?")) return;

    fetch(base_url+"pasajeros/catalogo_baja/"+id,{
        method:"POST"
    })
    .then(()=> cargarCatalogo());

}


/* ======================================================
   TIPOS DOCUMENTO
====================================================== */
function cargarTiposDoc(){

    fetch(base_url+"pasajeros/catalogo_tipos_identificacion")
    .then(r=>r.json())
    .then(data=>{

        let html = `<option value="">Tipo identificación</option>`;

        data.forEach(t=>{
            html += `<option value="${t.id}">${t.nombre}</option>`;
        });

        document.getElementById("cat_tipo_doc").innerHTML = html;

    });

}


/* ======================================================
   CÁMARA
====================================================== */
function scanDocumento(id){
    cat_id.value = id;
    CAM_MODO = "DOC";
    abrirCam();
}

function capturarFoto(){
    CAM_MODO = "FOTO";
    abrirCam();
}

function abrirCam(){

    document.getElementById("modal_cam").style.display="flex";

    navigator.mediaDevices.getUserMedia({ video:true })
    .then(stream=>{
        CAM_STREAM = stream;
        document.getElementById("cam_video").srcObject = stream;
    })
    .catch(()=>{
        alert("No se pudo acceder a la cámara");
    });

}

function cerrarCam(){

    if(CAM_STREAM){
        CAM_STREAM.getTracks().forEach(t=>t.stop());
    }

    document.getElementById("modal_cam").style.display="none";
}

function tomarFoto(){

    let video = document.getElementById("cam_video");

    let canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext("2d").drawImage(video,0,0);

    let img = canvas.toDataURL("image/jpeg");

    cerrarCam();

    if(CAM_MODO==="DOC") ejecutarOCR(img);
    if(CAM_MODO==="FOTO") guardarFoto(img);
}


/* ======================================================
   GUARDAR FOTO
====================================================== */
function guardarFoto(img){

    document.getElementById("cat_foto_preview").src = img;

    fetch(base_url+"pasajeros/guardar_foto",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({
            id: cat_id.value,
            imagen: img
        })
    });

}


/* ======================================================
   OCR
====================================================== */
function ejecutarOCR(img){

    fetch(base_url+"pasajeros/ocr_documento",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body: JSON.stringify({ imagen: img })
    })
    .then(r=>r.json())
    .then(resp=>{

        if(!resp.ok){
            alert("OCR no pudo procesar el documento");
            return;
        }

        parseINE(resp.texto);
        parseDireccionINE(resp.texto);

    });

}


/* ======================================================
   PARSER INE
====================================================== */
function parseINE(texto){

    if(!texto) return;

    let t = texto.toUpperCase();

    let curp = t.match(/[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d/);

    if(curp){

        cat_ident.value = curp[0];

        analizarCURP(curp[0]);   // ⭐ ahora sí se ejecuta

        let fecha = curp[0].substring(4,10);

        let anio = parseInt(fecha.substring(0,2));
        let mes  = fecha.substring(2,4);
        let dia  = fecha.substring(4,6);

        anio = anio > 30 ? 1900+anio : 2000+anio;

        cat_fnac.value = `${anio}-${mes}-${dia}`;
    }

}