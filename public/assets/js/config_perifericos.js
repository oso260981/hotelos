/**
 * Carga impresoras instaladas en el servidor
 * y construye la tabla automáticamente
 */
function cargarImpresorasServidor(){

    fetch(base_url + "config/impresoras")
    .then(r => r.json())
    .then(lista => {

        let html = "";
lista.forEach(imp => {

    let conexion = detectarConexionReal(imp.PortName);

    html += `
        <tr>
            <td>${imp.Name}</td>
            <td>${conexion === "USB" ? "Tickets" : "Reportes"}</td>
            <td>${conexion}</td>
            <td>${imp.PortName}</td>
            <td class="hos-ok">● Conectada</td>
            <td><button class="hos-btn">Probar</button></td>
        </tr>
    `;
});

        document.getElementById("tablaImpresoras").innerHTML = html;

    });

}


/**
 * Detecta tipo impresora simple (heurística)
 */
function detectarTipo(nombre){

    nombre = nombre.toLowerCase();

    if(nombre.includes("pos") || nombre.includes("ticket"))
        return "Tickets";

    return "Reportes";
}

/**
 * Detecta conexión simple
 */
function detectarConexionReal(port){

    port = port.toUpperCase();

    if(port.includes("USB"))
        return "USB";

    if(port.match(/\d+\.\d+\.\d+\.\d+/))
        return "LAN";

    if(port.includes("WSD"))
        return "LAN";

    if(port.includes("FILE") || port.includes("PORTPROMPT"))
        return "VIRTUAL";

    return "OTRO";
}


/**
 * Evento al cargar pantalla
 */
document.addEventListener("DOMContentLoaded", ()=>{
    cargarImpresorasServidor();
});



function detectarPerifericos(){

    // SCANNER Y TOUCH (USB)
    fetch(base_url + "perifericos/usb")
    .then(r=>r.json())
    .then(lista=>{

        let texto = JSON.stringify(lista).toLowerCase();

        if(texto.includes("scanner") || texto.includes("hid")){
            document.getElementById("sw_scanner").checked = true;
        }

        if(texto.includes("wacom") || texto.includes("signature") || texto.includes("tablet")){
            document.getElementById("sw_firma").checked = true;
        }

    });


    // CAMARA
    fetch(base_url + "perifericos/camaras")
    .then(r=>r.json())
    .then(lista=>{

        if(lista && lista.length > 0){
            document.getElementById("sw_camara").checked = true;
        }

    });


    // DISCO EXTERNO
    fetch(base_url + "perifericos/discos")
    .then(r=>r.json())
    .then(lista=>{

        if(lista && lista.length > 0){
            document.getElementById("sw_backup").checked = true;
        }

    });

}

document.addEventListener("DOMContentLoaded", detectarPerifericos);



function guardarConfigPerifericos(){

    let data = [
        {
            clave:"scanner",
            activo: document.getElementById("sw_scanner").checked ? 1:0,
            nombre: document.getElementById("lbl_scanner").innerText
        },
        {
            clave:"firma",
            activo: document.getElementById("sw_firma").checked ? 1:0,
            nombre: document.getElementById("lbl_firma").innerText
        },
        {
            clave:"camara",
            activo: document.getElementById("sw_camara").checked ? 1:0,
            nombre: document.getElementById("lbl_camara").innerText
        },
        {
            clave:"backup",
            activo: document.getElementById("sw_backup").checked ? 1:0,
            nombre: document.getElementById("lbl_backup").innerText
        }
    ];

    fetch(base_url+"perifericos/guardar",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body: JSON.stringify(data)
    })
    .then(r=>r.json())
    .then(resp=>{
        alert("Configuración guardada");
    });

}

