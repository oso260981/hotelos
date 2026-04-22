<div class="form-section">

    <div class="form-section-title">
        ⚙️ Configuración del sistema
    </div>

    <table class="g-table" id="tablaConfig">

        <thead>
            <tr>
                <th style="width:25%">NOMBRE</th>
                <th style="width:45%">VALOR</th>
                <th style="width:30%">ACCIÓN</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach($config as $c): ?>

            <tr>
                <td>
                    <strong><?= esc($c['nombre']) ?></strong>
                </td>

                <td>
                    <input 
                        type="text"
                        class="form-control"
                        value="<?= esc($c['valor']) ?>"
                        data-id="<?= $c['id'] ?>"
                    >
                </td>

                <td>
                    <button 
                        class="btn-primary btn-green btnGuardarConfig"
                        data-id="<?= $c['id'] ?>"
                    >
                        💾 Guardar
                    </button>
                </td>
            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>


<script>

document.querySelectorAll('.btnGuardarConfig').forEach(btn=>{

    btn.onclick = function(){

        let id = this.dataset.id
        let input = document.querySelector('input[data-id="'+id+'"]')

        fetch("<?= base_url('configuraciones/guardar') ?>",{
            method:"POST",
            headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
            body:"id="+id+"&valor="+encodeURIComponent(input.value)
        })
        .then(r=>r.json())
        .then(j=>{
            this.innerHTML="✅ Guardado"
            setTimeout(()=>{
                this.innerHTML="💾 Guardar"
            },1500)
        })

    }

})

</script>