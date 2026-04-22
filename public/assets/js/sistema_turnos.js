

let USUARIOS = [];

document.addEventListener("DOMContentLoaded", async () => {

    await cargarUsuarios();
    cargarTurnos();

});

// =========================
// 🔥 USUARIOS
// =========================
function cargarUsuarios() {

    return fetch(base_url + "usuarios/recepcionistas", { method: "POST" })
    .then(r => r.json())
    .then(resp => {
        if (resp.ok) USUARIOS = resp.data;
    });
}

function renderUsuariosOptions(selected = null) {

    let html = `<option value="">Seleccionar</option>`;

    USUARIOS.forEach(u => {
        html += `<option value="${u.id}" ${u.id == selected ? 'selected' : ''}>
                    ${u.nombre} ${u.apellido}
                 </option>`;
    });

    return html;
}

// =========================
// 🔥 CARGAR TURNOS
// =========================
function cargarTurnos() {

    fetch(base_url + "turnos/list", {
        method: "POST"
    })
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert("Error al cargar turnos");
            return;
        }

        const turnos = resp.data || [];

        renderTurnos(turnos);

    })
    .catch(err => console.error(err));
}

// =========================
// 🔥 RENDER
// =========================
function renderTurnos(data) {

    const tbody = document.getElementById("tablaTurnos");

    tbody.innerHTML = "";

    data.forEach((t, i) => {

        const tr = document.createElement("tr");

        tr.dataset.id = t.id;

        tr.innerHTML = `
            <td>${i + 1}</td>

            <td>
                <input class="nombre" value="${t.nombre}">
            </td>

            <td>
                <input type="time" class="inicio" value="${t.hora_inicio}">
            </td>

            <td>
                <input type="time" class="fin" value="${t.hora_fin}">
            </td>

            <td>
                <select class="responsable">
                    ${renderUsuariosOptions(t.usuario_id)}
                </select>
            </td>
        `;

        tbody.appendChild(tr);
    });
}


function guardarTurnosFront() {

    const rows = document.querySelectorAll("#tablaTurnos tr");

    let turnos = [];

    rows.forEach(tr => {

        turnos.push({
            id: tr.dataset.id, // 🔥 SIEMPRE hay id
            nombre: tr.querySelector(".nombre").value,
            hora_inicio: tr.querySelector(".inicio").value,
            hora_fin: tr.querySelector(".fin").value,
            usuario_id: tr.querySelector(".responsable").value
        });

    });

    console.log("ACTUALIZANDO:", turnos);

    fetch(base_url + "turnos/save", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(turnos)
    })
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert(resp.msg || "Error al guardar");
            return;
        }

        alert("Cambios guardados ✔️");

        cargarTurnos();

    })
    .catch(err => console.error(err));
}

function abrirTurno(turno_id, usuario_id) {

    fetch(base_url + "turno/abrir", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            turno_id: turno_id,
            usuario_id: usuario_id
        })
    })
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert(resp.msg);
            return;
        }

        alert("Turno iniciado 🚀");
    });
}


function cerrarTurno() {

    fetch(base_url + "turno/cerrar", {
        method: "POST"
    })
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert(resp.msg);
            return;
        }

        alert("Turno cerrado ✔️");
    });
}