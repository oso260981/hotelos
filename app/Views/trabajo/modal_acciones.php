<style>
/* =========================
   MODAL FONDO (OVERLAY)
========================= */
/* =========================
   MODAL BOX PRO
========================= */
.modal-box{
    background: #ffffff;
    padding: 26px;
    border-radius: 18px;
    width: 340px;
    text-align: center;

    box-shadow: 0 25px 60px rgba(0,0,0,0.35);
    animation: fadeIn .18s ease;
}

/* título */
.modal-box h2{
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 6px;
    color: #0f172a;
}

/* subtítulo */
.modal-box p{
    font-size: 13px;
    color: #64748b;
    margin-bottom: 18px;
}

/* =========================
   BOTONES PRO
========================= */
.actions button{
    display: block;
    width: 100%;
    margin-top: 10px;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all .15s ease;
}

/* LIMPIA (verde PMS) */
.btn-success{
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
}

.btn-success:hover{
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(34,197,94,.3);
}

/* MTTO (naranja PMS) */
.btn-warning{
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
}

.btn-warning:hover{
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(245,158,11,.3);
}

/* CANCELAR */
.actions .btn{
    background: #f1f5f9;
    color: #334155;
}

.actions .btn:hover{
    background: #e2e8f0;
}

/* animación */
@keyframes fadeIn{
    from{
        opacity: 0;
        transform: scale(.95);
    }
    to{
        opacity: 1;
        transform: scale(1);
    }
}
</style>



<!-- =========================
     MODAL ADMINISTRAR HABITACIÓN
========================= -->
<div id="modal-limpieza" class="modal-clean hidden">

    <div class="modal-box">

        <!-- 🔹 TÍTULO -->
        <h2>⚙ Administrar Habitación</h2>

        <!-- 🔹 NÚMERO HABITACIÓN -->
        <p id="clean-room-label">Habitación</p>

        <!-- 🔹 ACCIONES -->
        <div class="actions">

            <!-- LIMPIAR -->
            <button onclick="accionMarcarLimpia()" class="btn-success">
                🧹 Marcar como limpia
            </button>

            <!-- MANTENIMIENTO -->
            <button onclick="accionMandarMtto()" class="btn-warning">
                🔧 Enviar a mantenimiento
            </button>

            <!-- CANCELAR -->
            <button onclick="cerrarModalClean()" class="btn">
                Cancelar
            </button>

        </div>

    </div>

</div>

