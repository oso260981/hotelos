function sanitize(val, fallback = '-') {
    if (val === null || val === undefined || String(val).trim().toLowerCase() === 'null' || String(val).trim().toLowerCase() === 'undefined' || String(val).trim() === '') return fallback;
    return val;
}

document.addEventListener("input", (e) => {
    

    if (e.target.classList.contains("input-edit")) {

        const input = e.target;
        const original = input.dataset.original;

        if (input.value != original) {

            input.classList.add("editado");
            input.closest("tr").classList.add("fila-editada");

        } else {

            input.classList.remove("editado");

            // si toda la fila ya no tiene edits
            const fila = input.closest("tr");
            if (!fila.querySelector(".editado")) {
                fila.classList.remove("fila-editada");
            }
        }
    }

});

document.addEventListener("DOMContentLoaded", () => {
    cargarReportes();
});




document.addEventListener("DOMContentLoaded", () => {
    cargarFiscal();
});


document.addEventListener("DOMContentLoaded", () => {
    cargarReporteTrabajo();
});

document.addEventListener("DOMContentLoaded", () => {
    cargarReporteTurnos();
});

document.addEventListener("DOMContentLoaded", () => {

    setTimeout(() => {
        cargarReporteHuespedes();
    }, 300);

});

document.addEventListener("DOMContentLoaded", () => {
    cargarReporteDia();
});


document.addEventListener("DOMContentLoaded", () => {

    // 🔥 Inicializa filtros
    window.FILTROS = {
        doc: false,
        firma: false,
        obs: false,
        negra: false
    };

    // 🔥 Eventos de chips
    document.querySelectorAll("#filtrosHuespedes .chip").forEach(chip => {

        chip.addEventListener("click", () => {

            const tipo = chip.dataset.filter;

            FILTROS[tipo] = !FILTROS[tipo];

            chip.classList.toggle("active");

            cargarReporteHuespedes(); // recarga
        });

    });

});







function cargarReporteTrabajo() {

    if (typeof base_url === "undefined") {
        console.error("❌ base_url no está definido");
        return;
    }

    fetch(base_url + "reportes/trabajo")
        .then(r => {
            if (!r.ok) {
                throw new Error("Error HTTP: " + r.status);
            }
            return r.json();
        })
        .then(resp => {

            console.log("✅ RESPUESTA:", resp);

            if (!resp || resp.ok === false) {
                alert(resp?.msg || "Error en la respuesta del servidor");
                return;
            }

            const kpi = resp.kpi || {};
            const data = resp.data || [];

            // =========================
            // 📊 KPIs (SIN romper ejecución)
            // =========================
            try {
                const elOcupadas = document.getElementById('kpi-ocupadas');
                const elTotal = document.getElementById('kpi-total');
                const elEntradas = document.getElementById('kpi-entradas');
                const elSalidas = document.getElementById('kpi-salidas');

                if (elOcupadas) elOcupadas.innerText = kpi.ocupadas ?? 0;
                if (elTotal) elTotal.innerText = "$" + Number(kpi.total || 0).toLocaleString('es-MX');
                if (elEntradas) elEntradas.innerText = kpi.entradas ?? 0;
                if (elSalidas) elSalidas.innerText = kpi.salidas ?? 0;

            } catch (e) {
                console.warn("⚠️ Error pintando KPIs:", e);
            }

            // =========================
            // 📋 TABLA
            // =========================
            const tbody = document.getElementById('tabla-trabajo');

            if (!tbody) {
                console.error("❌ No existe #tabla-trabajo");
                return;
            }

            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="12" style="text-align:center;">
                            Sin registros
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach(row => {

                // 🔥 Validaciones seguras
                const entrada = row.hora_entrada
                    ? new Date(row.hora_entrada).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })
                    : '-';

                const salida = row.hora_salida_real
                    ? new Date(row.hora_salida_real).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })
                    : '-';

                const precio = Number(row.precio_base || 0);
                const iva = Number(row.iva || 0);
                const ish = Number(row.ish || 0);

                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${sanitize(row.Numero_Habitacion)}</td>
                    <td>${sanitize(row.estados_habitacion)}</td>
                    <td>${sanitize(row.cod_tip_habitacion)}</td>
                    <td>${sanitize(row.Total_huespedes, '0')}</td>
                    <td>${sanitize(row.Cod_Forma_pago)}</td>
                    <td>$${precio.toLocaleString('es-MX')}</td>
                    <td>$${iva.toLocaleString('es-MX')}</td>
                    <td>$${ish.toLocaleString('es-MX')}</td>
                    <td><b>$${precio.toLocaleString('es-MX')}</b></td>
                    <td>${entrada}</td>
                    <td>${salida}</td>
                    <td>${sanitize(row.Nombre_Huesped)}</td>
                `;

                tbody.appendChild(tr);
            });

            console.log("✅ Tabla renderizada:", data.length, "filas");

        })
        .catch(e => {
            console.error("❌ Error reporte:", e);
            alert("Error cargando reporte");
        });
}

function animateValue(id, value) {
    const el = document.getElementById(id);
    el.innerText = value;
}

function showRep(n, el) {
    for (let i = 1; i <= 5; i++) {
        const r = document.getElementById('rep' + i);
        if (r) r.style.display = (i === n ? 'block' : 'none');
    }

    document.querySelectorAll('.rtab').forEach(t => {
        t.classList.remove('active');
        t.classList.add('inactive');
    });

    el.classList.remove('inactive');
    el.classList.add('active');
}




function cargarReporteTurnos() {

    fetch(base_url + "reportes/turnos")
    .then(r => r.json())
    .then(resp => {

        if (!resp.ok) {
            alert("Error al cargar reporte");
            return;
        }

        pintarKPIsTurnos(resp.kpi);
        renderReporteTurnos(resp.data);

    })
    .catch(err => console.error(err));
}

function pintarKPIsTurnos(kpi) {

    document.getElementById("t1_total").textContent = formatoMoneda(kpi.t1);
    document.getElementById("t2_total").textContent = formatoMoneda(kpi.t2);
    document.getElementById("t3_total").textContent = formatoMoneda(kpi.t3);
    document.getElementById("total_dia").textContent = formatoMoneda(kpi.total);

}


document.addEventListener("click", (e) => {

    const tr = e.target.closest("#tablaReporteTurnos tr");
    if (!tr) return;

    const checkbox = tr.querySelector(".row-check");
    if (!checkbox) return;

    if (e.target.type !== "checkbox") {
        checkbox.checked = !checkbox.checked;
    }

    tr.classList.toggle("selected", checkbox.checked);

    actualizarSeleccionUI();
});



function renderReporteTurnos(data) {

    const tbody = document.getElementById("tablaReporteTurnos");
    tbody.innerHTML = "";

    let t1 = 0, t2 = 0, t3 = 0;

    data.forEach(row => {

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>
                <input type="checkbox" 
                       class="row-check" 
                       data-id="${row.habitacion_id}">
            </td>

            <td class="hab">${row.habitacion}</td>

            <td class="t1">${row.t1_pago || '-'}</td>
            <td class="t1">${formatoMoneda(row.t1_precio)}</td>
            <td class="t1">${row.t1_nombre || '-'}</td>

            <td class="t2">${row.t2_pago || '-'}</td>
            <td class="t2">${formatoMoneda(row.t2_precio)}</td>
            <td class="t2">${row.t2_nombre || '-'}</td>

            <td class="t3">${row.t3_pago || '-'}</td>
            <td class="t3">${formatoMoneda(row.t3_precio)}</td>
            <td class="t3">${row.t3_nombre || '-'}</td>
        `;

        tbody.appendChild(tr);

        // 🔥 SUMAS
        t1 += Number(row.t1_precio || 0);
        t2 += Number(row.t2_precio || 0);
        t3 += Number(row.t3_precio || 0);
    });

    // 🔥 KPIs
    document.getElementById("t1_total").innerText = formatoMoneda(t1);
    document.getElementById("t2_total").innerText = formatoMoneda(t2);
    document.getElementById("t3_total").innerText = formatoMoneda(t3);
    document.getElementById("total_dia").innerText = formatoMoneda(t1 + t2 + t3);

    // 🔥 FOOTER
    document.getElementById("total_t1").innerText = formatoMoneda(t1);
    document.getElementById("total_t2").innerText = formatoMoneda(t2);
    document.getElementById("total_t3").innerText = formatoMoneda(t3);

    actualizarSeleccionUI();
}

function getSeleccionIds() {
    return Array.from(document.querySelectorAll(".row-check:checked"))
        .map(chk => chk.dataset.id);
}

function enviarSeleccion() {

    const ids = getSeleccionIds();

    if (ids.length === 0) {
        alert("Selecciona al menos una habitación");
        return;
    }

    const nombre = prompt("Nombre del reporte:");

    if (!nombre || nombre.trim() === "") {
        alert("Debes ingresar un nombre");
        return;
    }

    console.log("Enviando:", { ids, nombre });

    fetch(base_url + "reportes/generar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            habitaciones: ids,
            nombre: nombre
        })
    })
    .then(r => r.json())
    .then(resp => {

        console.log("RESPUESTA:", resp);

        if (!resp.ok) {
            alert(resp.msg || "Error al guardar");
            return;
        }

        alert("Reporte generado correctamente");

        // 🔥 📍 AQUÍ VA (DESPUÉS de guardar)
        cargarReporteTurnos();

    })
    .catch(err => {
        console.error("Error:", err);
    });


}

function formatoMoneda(valor) {

    if (!valor) return "-";

    return "$" + parseFloat(valor).toLocaleString("es-MX", {
        minimumFractionDigits: 0
    });
}


function cargarReporteHuespedes() {

    let params = new URLSearchParams();

    if (FILTROS.doc) params.append("doc", 1);
    if (FILTROS.firma) params.append("firma", 1);
    if (FILTROS.obs) params.append("obs", 1);
    if (FILTROS.negra) params.append("negra", 1);

    fetch(base_url + "reportes/huespedes?" + params.toString())
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderTabla(resp.data);

        })
        .catch(err => console.error("Error reporte:", err));
}





function renderTabla(data) {

    const tbody = document.querySelector("#tablaHuespedes tbody");
    tbody.innerHTML = "";

    data.forEach(row => {

        // 🔍 FILTROS
        if (FILTROS.doc && (!row.DOC || row.DOC === '')) return;
        if (FILTROS.firma && !row.firma_path) return;
        if (FILTROS.obs && (!row.OBSERVACIONES || row.OBSERVACIONES === '-')) return;
        if (FILTROS.negra && !row.LISTA.includes('NEGRA')) return;

        // FIRMA
        let firmaHTML = row.firma_path
            ? `<img src="${base_url + row.firma_path}" width="50">`
            : `<span class="badge-pend">⚠ Pend.</span>`;

        // FOTO
        let fotoHTML = row.FOTO === '✔'
            ? `<span class="badge-ok">✔</span>`
            : `<span class="badge-pend">⚠</span>`;

        // LISTA
        let listaHTML = '-';
        if (row.LISTA.includes('NEGRA')) {
            listaHTML = `<span class="badge-danger">⛔ L.NEGRA</span>`;
        } else if (row.LISTA.includes('VIP')) {
            listaHTML = `<span class="badge-vip">⭐ VIP</span>`;
        }

        tbody.innerHTML += `
            <tr>
                <td>${sanitize(row.HAB)}</td>
                <td>${sanitize(row.NOMBRE_COMPLETO)}</td>
                <td>${sanitize(row.DOC)}</td>
                <td>${sanitize(row.NUMERO_DOCUMENTO)}</td>
                <td>${firmaHTML}</td>
                <td>${fotoHTML}</td>
                <td>${sanitize(row.OBSERVACIONES)}</td>
                <td>${listaHTML}</td>
            </tr>
        `;
    });
}

function cargarReporteDia() {

    const fecha = document.getElementById("filtroFecha")?.value || '';

    fetch(base_url + "reportes/reporteDia?fecha=" + fecha)
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderTablaDia(resp.data);
            renderKPI(resp.kpi);

        })
        .catch(err => console.error("Error:", err));
}

function renderKPI(kpi) {

    document.getElementById("kpi_total").innerText = "$" + formatMoney(kpi.total);
    document.getElementById("kpi_efectivo").innerText = "$" + formatMoney(kpi.efectivo);
    document.getElementById("kpi_tarjeta").innerText = "$" + formatMoney(kpi.tarjeta);
    document.getElementById("kpi_facturas").innerText = kpi.facturas || 0;
}


function renderTablaDia(data) {

    const tbody = document.querySelector("#tablaReporteDia tbody");
    tbody.innerHTML = "";

    let turnoActual = "";

    data.forEach(row => {

        // 🔹 HEADER DE TURNO
        if (turnoActual !== row.turno) {
            turnoActual = row.turno;

            tbody.innerHTML += `
                <tr class="turno-header">
                    <td colspan="10"><b>${row.turno}</b></td>
                </tr>
            `;
        }

        tbody.innerHTML += `
            <tr>
                <td>${sanitize(row.turno)}</td>
                <td>${sanitize(row.habitacion)}</td>
                <td>${sanitize(row.nombre)}</td>
                <td>${sanitize(row.pago)}</td>
                <td>$${formatMoney(row.subtotal)}</td>
                <td>$${formatMoney(row.iva)}</td>
                <td>$${formatMoney(row.ish)}</td>
                <td><b>$${formatMoney(row.total)}</b></td>
                <td>${sanitize(row.entrada)}</td>
                <td>${sanitize(row.salida)}</td>
            </tr>
        `;
    });

    agregarTotalDia(data);
}


function agregarTotalDia(data) {

    let subtotal = 0, iva = 0, ish = 0, total = 0;

    data.forEach(r => {
        subtotal += parseFloat(r.subtotal || 0);
        iva += parseFloat(r.iva || 0);
        ish += parseFloat(r.ish || 0);
        total += parseFloat(r.total || 0);
    });

    const tbody = document.querySelector("#tablaReporteDia tbody");

    tbody.innerHTML += `
        <tr class="total-row">
            <td colspan="4">TOTAL DEL DÍA</td>
            <td>$${formatMoney(subtotal)}</td>
            <td>$${formatMoney(iva)}</td>
            <td>$${formatMoney(ish)}</td>
            <td>$${formatMoney(total)}</td>
            <td colspan="2"></td>
        </tr>
    `;
}


function formatMoney(num) {
    return parseFloat(num || 0).toLocaleString('es-MX', {
        minimumFractionDigits: 2
    });
}




function cargarFiscal() {

    const fecha = document.getElementById("filtroFechaFiscal")?.value || '';

    fetch(base_url + "reportes/reporteFiscal?fecha=" + fecha)
        .then(r => r.json())
        .then(resp => {

            if (!resp.ok) {
                alert(resp.msg);
                return;
            }

            renderFiscal(resp.data);

        })
        .catch(err => console.error(err));
}


function renderFiscal(data) {

    const tbody = document.querySelector("#tablaFiscal tbody");
    tbody.innerHTML = "";

    data.forEach(row => {

        tbody.innerHTML += `
            <tr>
                <td>${row.habitacion}</td>
                <td>${row.nombre}</td>
                <td>${row.pago}</td>

                <!-- SUBTOTAL -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="precio"
                        data-original="${row.subtotal}"
                        value="${row.subtotal}">
                </td>

                <!-- IVA -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="iva"
                        data-original="${row.iva}"
                        value="${row.iva}"
                        readonly>
                </td>

                <!-- ISH -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="ish"
                        data-original="${row.ish}"
                        value="${row.ish}"
                        readonly>
                </td>

                <!-- TOTAL -->
                <td class="total-cell">
                    <b>$${parseFloat(row.total).toFixed(2)}</b>
                </td>

                <!-- RFC -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="rfc"
                        data-original="${row.rfc || ''}"
                        value="${row.rfc || ''}"
                        placeholder="RFC...">
                </td>

                <!-- NOTAS -->
                <td>
                    <input 
                        class="input-edit"
                        data-id="${row.id}"
                        data-field="notas"
                        data-original="${row.notas || ''}"
                        value="${row.notas || ''}"
                        placeholder="Nota...">
                </td>
            </tr>
        `;
    });

    activarControlEdicion();
    calcularKPIFiscal();
}



function guardarFiscal() {

  
    const inputs = document.querySelectorAll(".input-edit.editado");

    let data = [];

    inputs.forEach(inp => {

        data.push({
            id: inp.dataset.id,
            campo: inp.dataset.field,
            valor: inp.value
        });

    });

    fetch(base_url + "reportes/guardarFiscal", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ data })
    })
    .then(r => r.json())
    .then(resp => {

        if (resp.ok) {
            alert("✔ Guardado correctamente");
            cargarFiscal(); // 🔥 refresca
        } else {
            alert(resp.msg);
        }

    });

    calcularKPIFiscal();
}



function activarControlEdicion() {

    document.querySelectorAll(".input-edit").forEach(input => {

        input.addEventListener("input", () => {

            const fila = input.closest("tr");
            const original = input.dataset.original;

            // 🔹 marcar cambio
            if (input.value != original) {
                input.classList.add("editado");
                fila.classList.add("fila-editada");
            } else {
                input.classList.remove("editado");

                if (!fila.querySelector(".editado")) {
                    fila.classList.remove("fila-editada");
                }
            }

            // 🔥 SOLO si cambia precio
            if (input.dataset.field === "precio") {
                recalcularFila(fila);
            }

        });

    });

    calcularKPIFiscal();

}


function recalcularFila(fila) {

    const inputPrecio = fila.querySelector('[data-field="precio"]');
    const inputIva    = fila.querySelector('[data-field="iva"]');
    const inputIsh    = fila.querySelector('[data-field="ish"]');

    let subtotal = parseFloat(inputPrecio.value) || 0;

    // 🔥 calcular impuestos
    let iva = subtotal * 0.16;
    let ish = subtotal * 0.03;

    // 🔥 asignar valores recalculados
    inputIva.value = iva.toFixed(2);
    inputIsh.value = ish.toFixed(2);

    // 🔥 marcar como editados automáticamente
    inputIva.classList.add("editado");
    inputIsh.classList.add("editado");

    // 🔥 total
    let total = subtotal + iva + ish;

    fila.querySelector(".total-cell").innerHTML = `<b>$${total.toFixed(2)}</b>`;

    calcularKPIFiscal()
}


function calcularKPIFiscal() {

    let subtotal = 0;
    let iva = 0;
    let ish = 0;
    let total = 0;

    document.querySelectorAll("#tablaFiscal tbody tr").forEach(fila => {

        const p = parseFloat(fila.querySelector('[data-field="precio"]')?.value) || 0;
        const i = parseFloat(fila.querySelector('[data-field="iva"]')?.value) || 0;
        const s = parseFloat(fila.querySelector('[data-field="ish"]')?.value) || 0;

        subtotal += p;
        iva += i;
        ish += s;
        total += (p + i + s);

    });

    // 🔹 pintar
    document.getElementById("kpi_subtotal").innerText = "$" + formatMoney(subtotal);
    document.getElementById("kpi_iva").innerText = "$" + formatMoney(iva);
    document.getElementById("kpi_ish").innerText = "$" + formatMoney(ish);
    document.getElementById("kpi_totalsat").innerText = "$" + formatMoney(total);
}


function cargarReportes() {

    fetch(base_url + "reportes/lista")
        .then(r => r.json())
        .then(resp => {

            console.log("LISTA:", resp); // 🔥 DEBUG

            const cont = document.getElementById("listaReportes");
            cont.innerHTML = "";

            if (!resp.data || resp.data.length === 0) {
                cont.innerHTML = "<tr><td colspan='4'>Sin reportes</td></tr>";
                return;
            }

            resp.data.forEach(r => {

                cont.innerHTML += `
                    <tr>
                        <td>${r.id}</td>
                         <td>${r.nombre || 'Sin nombre'}</td>
                        <td>${r.fecha}</td>
                        <td>${r.usuario}</td>
                        <td>
                            <button onclick="verReporte(${r.id})">👁 Ver</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error("Error lista:", err));
}


function cargarReporte(id) {

    console.log("Cargando reporte:", id);

    fetch(base_url + "reportes/detalle/" + id)
        .then(r => r.json())
        .then(resp => {

            console.log("DETALLE:", resp);

            if (!resp.ok || !resp.data || resp.data.length === 0) {
                alert("Este reporte no tiene datos");
                return;
            }

            // 🔥 ADAPTAR DATA AL FORMATO ORIGINAL
            const data = resp.data.map(r => ({
                habitacion_id: r.habitacion_id,
                habitacion: r.habitacion,

                t1_pago: r.t1_pago,
                t1_precio: r.t1_precio,
                t1_nombre: r.t1_nombre,

                t2_pago: r.t2_pago,
                t2_precio: r.t2_precio,
                t2_nombre: r.t2_nombre,

                t3_pago: r.t3_pago,
                t3_precio: r.t3_precio,
                t3_nombre: r.t3_nombre
            }));

            // 🔥 REUTILIZAS TU TABLA PRINCIPAL
            renderReporteTurnos(data);

            // 🔥 CERRAR MODAL
            cerrarModalReportes();

        })
        .catch(err => {
            console.error("Error cargando reporte:", err);
            alert("Error al cargar reporte");
        });
}



function eliminarReporte(id) {

    if (!confirm("¿Eliminar reporte?")) return;

    fetch(base_url + "reportes/eliminar/" + id, {
        method: "DELETE"
    })
    .then(r => r.json())
    .then(resp => {

        if (resp.ok) {
            alert("Eliminado");
            cargarReportes();
        }
    });
}


function guardarCambios(id) {

    const rows = [];

    document.querySelectorAll("#tablaDetalle tr").forEach(tr => {

        const tds = tr.querySelectorAll("td");

        rows.push({
            id: tr.dataset.id,
            t1_precio: tds[2].innerText,
            t2_precio: tds[5].innerText,
            t3_precio: tds[8].innerText
        });
    });

    fetch(base_url + "reportes/actualizar/" + id, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ rows })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.ok) {
            alert("Actualizado");
        }
    });
}




function actualizarSeleccionUI() {
    const el = document.getElementById("contadorSeleccion");
    if (!el) return; // 🔥 evita el error
    const total = document.querySelectorAll(".row-check:checked").length;
    el.innerText = total;
}


function formatoMoneda(valor) {
    return "$" + Number(valor || 0).toFixed(2);
}


function abrirModalReportes() {
    document.getElementById("modalReportes").style.display = "flex";
    cargarListaModal();
}

function cerrarModalReportes() {
    document.getElementById("modalReportes").style.display = "none";
}


function cargarListaModal() {

    fetch(base_url + "reportes/lista")
        .then(r => r.json())
        .then(resp => {

            const tbody = document.getElementById("tablaModalReportes");
            tbody.innerHTML = "";

            if (!resp.data || resp.data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='4'>Sin reportes</td></tr>";
                return;
            }

            resp.data.forEach(r => {

                tbody.innerHTML += `
                    <tr>
                        <td>${r.id}</td>
                        <td>${r.nombre || 'Sin nombre'}</td>
                        <td>${r.fecha}</td>
                        <td>${r.usuario}</td>
                        <td>
                            <button onclick="cargarReporte(${r.id})">Cargar</button>
                            <button onclick="eliminarReporte(${r.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

        });
}



function nuevoReporte() {

    if (!confirm("¿Iniciar un nuevo reporte?")) return;

    console.log("Reiniciando reporte...");

    cargarReporteTurnos();
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.innerText = value;
}



function showRep(id, el) {

    document.querySelectorAll('[id^="rep"]').forEach(d => {
        d.style.display = 'none';
    });

    document.getElementById('rep' + id).style.display = 'block';

    document.querySelectorAll('.rtab').forEach(t => {
        t.classList.remove('active');
        t.classList.add('inactive'); // 👈 IMPORTANTE
    });

    el.classList.remove('inactive');
    el.classList.add('active');
}