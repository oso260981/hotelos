/* =====================================================
 VARIABLES GLOBALES
===================================================== */
let HAB_RESERVA = null;


/* =====================================================
 ABRIR MODAL RESERVA
 Se ejecuta al presionar botón 📅 en habitación
===================================================== */
function abrirModalReserva(numeroHab){

    // guardar habitación seleccionada
    HAB_RESERVA = numeroHab;

    // pintar número en el título
    document.getElementById("lblHabReserva").innerText = numeroHab;

    // mostrar modal
    document.getElementById("modalReserva").style.display = "flex";

}


/* =====================================================
 CERRAR MODAL RESERVA
===================================================== */
function cerrarModalReserva(){

    document.getElementById("modalReserva").style.display = "none";

}


/* =====================================================
 GUARDAR RESERVACIÓN
 Aquí después conectaremos API / SQLite
===================================================== */
function guardarReserva(){

    // construir objeto reserva
    let reserva = {

        habitacion : HAB_RESERVA,
        nombre     : document.getElementById("res_nombre").value,
        telefono   : document.getElementById("res_tel").value,
        personas   : document.getElementById("res_personas").value,
        fecha      : document.getElementById("res_fecha").value,
        hora       : document.getElementById("res_hora").value,
        obs        : document.getElementById("res_obs").value,
        estado     : "RESERVA"

    };

    console.log("RESERVA CREADA", reserva);

    // TODO:
    // aquí irá POST a API o guardado en SQLite

    cerrarModalReserva();

}