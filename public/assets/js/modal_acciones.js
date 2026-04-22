/* =====================================
   ABRIR MODAL ADMINISTRATIVO
===================================== */
function abrirModalAdmin(room, h) {

    // permitir en:
    // CHECKOUT (sucia)
    // o sin registro (libre / mtto)
    if (h.estado_registro === 'CHECKIN') {
        return; // ❌ ocupada → no permitir
    }

    window.currentRoomAdmin = room.numero;

    const modal = document.getElementById('modal-limpieza');
    const label = document.getElementById('clean-room-label');

    if (!modal) return;

    label.innerText = `Habitación ${room.numero}`;

    modal.classList.remove('hidden');
}


/* =====================================
   CERRAR MODAL
===================================== */
function cerrarModalClean() {

    const modal = document.getElementById('modal-limpieza');

    if (!modal) return;

    modal.classList.add('hidden');
}


/* =====================================
   ACCIÓN: MARCAR COMO LIMPIA
===================================== */
async function accionMarcarLimpia(){

    if (!window.currentRoomAdmin) return;

    // confirmación usuario
    if (!confirm("¿Marcar habitación como limpia?")) return;

    try {

        const resp = await fetch(base_url + "reservacion/marcar-limpia", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                habitacion_id: window.currentRoomAdmin
            })
        });

        const data = await resp.json();

        if (!data.ok) {
            alert(data.msg || "Error al limpiar");
            return;
        }

        // cerrar modal
        cerrarModalClean();

        // refrescar tabla
        cargarTrabajo(pisoActivo);

    } catch (e) {
        console.error(e);
        alert("Error en limpieza");
    }
}

/* =====================================
   ACCIÓN: MANDAR A MANTENIMIENTO
===================================== */
async function accionMandarMtto(){

    if (!window.currentRoomAdmin) return;

    if (!confirm("¿Enviar habitación a mantenimiento?")) return;

    try {

        const resp = await fetch(base_url + "reservacion/cambiar-estado", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                habitacion_id: window.currentRoomAdmin,
                estado: 'M' // 🔥 mantenimiento
            })
        });

        const data = await resp.json();

        if (!data.ok) {
            alert(data.msg || "Error");
            return;
        }

        cerrarModalClean();
        cargarTrabajo(pisoActivo);

    } catch (e) {
        console.error(e);
        alert("Error en mantenimiento");
    }
}