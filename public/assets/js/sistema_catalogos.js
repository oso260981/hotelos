/* ==========================================
   GUARDAR CATÁLOGO PRECIOS
========================================== */
function saveCatalogoPrecios(){

    let filas = document.querySelectorAll("#tablaCatalogo tbody tr")

    let data = []

    filas.forEach(tr => {

        let id = tr.dataset.id
        let precio = tr.querySelector(".precioBase").value

        data.push({
            id : id,
            precio_base : precio
        })

    })

    fetch(base_url + "habitacionTipos/saveCatalogo",{
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify(data)
    })
    .then(r=>r.json())
    .then(()=>{
        alert("Catálogo actualizado")
        loadTiposHabGrid()
    })

}

/* ==========================================
   GUARDAR CONFIG IMPUESTOS
========================================== */
function saveConfigImpuestos(){

    let campos = [
        'cfg_iva',
        'cfg_ish',
        'cfg_precio_modo',
        'cfg_ticket_desglose'
    ]

    campos.forEach(id => {

        let el = document.getElementById(id)

        let fd = new FormData()
        fd.append("id", el.dataset.id)
        fd.append("valor", el.value)

        fetch(base_url + "configuraciones/guardar",{
            method:'POST',
            body:fd
        })

    })

    alert("Configuración guardada")

    // ⭐ recalcular catálogo
    loadTiposHabGrid()
}

