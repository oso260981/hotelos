<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>HotelOS</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        font-family: Roboto;
        background: #e5e7eb;
        height: 100vh;
        overflow: hidden;
    }

    /* TABS */
    .tabs {
        display: flex;
        gap: 4px;
        padding: 8px 10px 0 10px;
        background: #0f172a;
    }

    .tab {
        background: #1f2937;
        color: #9ca3af;
        padding: 8px 14px;
        border-radius: 6px 6px 0 0;
        font-size: 13px;
        cursor: pointer;
    }

    .tab.active {
        background: #f1f5f9;
        color: #2563eb;
        font-weight: 600;
    }

    /* MAIN */
    .mainbox {
        height: calc(100vh - 40px);
        background: #f1f5f9;
        border-top: 3px solid #93c5fd;
        display: flex;
        flex-direction: column;
    }

    /* TOPBAR */
    .topbar {
        background: #1e4fa1;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 15px;
    }

    .top-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .clock {
        background: rgba(0, 0, 0, .4);
        padding: 5px 15px;
        border-radius: 8px;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .date {
        background: rgba(0, 0, 0, .3);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        opacity: 0.9;
    }

    .turnos {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .turno {
        background: white;
        color: #1e4fa1;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        font-weight: 800;
        font-size: 10px;
    }

    .turno.off {
        background: rgba(255, 255, 255, .15);
        color: rgba(255, 255, 255, .5);
    }

    .title {
        text-align: center;
        padding: 6px;
        font-size: 11px;
        letter-spacing: 3px;
        color: #64748b;
        border-bottom: 1px solid #cbd5e1;
        background: #f8fafc;
    }

    .bodybox {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .subtitle {
        font-size: 14px;
        letter-spacing: 3px;
        color: #475569;
        margin-bottom: 25px;
    }

    /* CARDS */
    .cards {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .card {
        width: 270px;
        background: white;
        border-radius: 12px;
        border: 2px solid #94a3b8;
        overflow: hidden;
        cursor: pointer;
        transition: .25s;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, .3);
    }

    .card-head {
        padding: 30px 20px 20px;
        font-size: 15px;
        font-weight: 700;
    }

    .card-body {
        background: #f1f5f9;
        padding: 14px;
        font-size: 12px;
        color: #475569;
        line-height: 1.6;
    }

    .turnoCard .card-head {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }

    .reporteCard .card-head {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #15803d;
    }

    .editarCard .card-head {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #ea580c;
    }

    .pasajerosCard .card-head {
        background: linear-gradient(135deg, #fbcfe8, #f9a8d4);
        color: #be185d;
    }
    </style>

    <style>
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999999;
}


.loader-box {
    background: white;
    padding: 25px 35px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #cbd5f5;
    border-top: 4px solid #2563eb;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: auto;
}

.loader-text {
    margin-top: 10px;
    font-size: 13px;
    color: #334155;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

</style>

</head>

<body>

    <?php 
    $uri = service('uri'); 
    $session = session();
    $rol = $session->get('rol');
    $username = $session->get('username');
    $fullName = $session->get('nombre') . ' ' . $session->get('apellido');

    // Comprobar si el turno está abierto
    $db = \Config\Database::connect();
    $turnoAbierto = $db->table('turnos_operacion')->where('estado', 'ABIERTO')->get()->getRowArray();
    $isLocked = !$turnoAbierto;
    $lockStyle = $isLocked ? 'style="pointer-events: none; opacity: 0.4;"' : '';
    ?>

    <div class="tabs">

        <?php if ($rol == 1): ?>
        <a href="<?= base_url('dashboard') ?>"
            class="tab <?= $uri->getSegment(1)=='' || $uri->getSegment(1)=='dashboard' ? 'active':'' ?>">
            🏠 Inicio
        </a>
        <?php endif; ?>

        <?php if ($rol == 1 || $rol == 2): ?>
        <a <?= $lockStyle ?> href="<?= base_url('trabajo') ?>" class="tab <?= $uri->getSegment(1)=='trabajo' ? 'active':'' ?>">
            📋 Trabajo
        </a>
        <?php endif; ?>

        <?php if ($rol == 1 || $rol == 3): ?>
        <a <?= $lockStyle ?> href="<?= base_url('reportes') ?>" class="tab <?= $uri->getSegment(1)=='reportes' ? 'active':'' ?>">
            📊 Reportes
        </a>
        <?php endif; ?>

        <?php if ($rol == 1): ?>
        <a <?= $lockStyle ?> href="<?= base_url('sistema') ?>" class="tab <?= $uri->getSegment(1)=='sistema' ? 'active':'' ?>">
            ⚙️ Editar Sistema
        </a>
        <?php endif; ?>

        <?php if ($rol == 1 || $rol == 2): ?>
        <!-- LISTA OPERATIVA -->
        <a <?= $lockStyle ?> href="<?= base_url('pasajeros') ?>"
            class="tab <?= $uri->getSegment(1)=='pasajeros' && $uri->getSegment(2)=='' ? 'active':'' ?>">
            🛂 Lista de Pasajeros
        </a>

        <!-- CATALOGO -->
        <a <?= $lockStyle ?> href="<?= base_url('pasajeros/catalogo') ?>"
            class="tab <?= $uri->getSegment(1)=='pasajeros' && $uri->getSegment(2)=='catalogo' ? 'active':'' ?>">
            👥 Catálogo de Clientes
        </a>
        <?php endif; ?>

    </div>

    <div class="mainbox">

        <div class="topbar">
            <div class="top-left">
                <div class="logo">HotelOS</div>
                <div class="clock" id="clock"></div>
                <div class="date" id="date"></div>

                <div style="font-weight:700; margin-left:15px; font-size:15px; opacity:0.9">Turno del día:</div>
                <div class="turnos">
                    <div class="turno off">1</div>
                    <div class="turno off">2</div>
                    <div class="turno">3</div>
                </div>

                <div style="font-weight:500; margin-left:25px; font-size:16px;">
                    Usuario: <span style="font-weight:800;"><?= $fullName ?></span>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:20px;">
                <div style="font-size:8px; text-align:right; opacity:0.7; line-height:1.2; font-weight:bold; letter-spacing:0.5px;">
                    CIMASOL SA de CV<br>
                    CDMX
                </div>
                <a href="<?= base_url('logout') ?>" 
                   onclick="confirmarSalida(event)"
                   style="background:rgba(255,255,255,0.1); color:white; border:1px solid rgba(255,255,255,0.2); padding:6px 12px; border-radius:6px; font-size:10px; font-weight:800; text-decoration:none; display:flex; align-items:center; gap:8px; transition:all 0.2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.2)'" 
                   onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="fas fa-power-off"></i> CERRAR SESIÓN
                </a>
            </div>
        </div>

      


        <div id="globalLoader" class="loader-overlay" style="display:none;">
            <div class="loader-box">
                <div class="spinner"></div>
                <div class="loader-text">Procesando...</div>
            </div>
        </div>







        <script>
        function obtenerTurnoActual() {
            const ahora = new Date();
            const h = ahora.getHours();
            const m = ahora.getMinutes();
            const hora = h + (m / 60);

            if (hora >= 6 && hora < 14) return 1;
            if (hora >= 14 && hora < 22) return 2;
            return 3;
        }

        function pintarTurno() {
            const turno = obtenerTurnoActual();
            const elementos = document.querySelectorAll('.turno');

            elementos.forEach((el, i) => {
                const numero = i + 1;

                if (numero === turno) {
                    el.classList.remove('off');
                } else {
                    el.classList.add('off');
                }
            });
        }

        // 👇 AQUÍ SE USA TODO
        document.addEventListener('DOMContentLoaded', pintarTurno);
        setInterval(pintarTurno, 60000);

        function confirmarSalida(e) {
            e.preventDefault();
            const url = e.currentTarget.href;
            
            Swal.fire({
                title: '¿CERRAR SESIÓN?',
                text: "Estás a punto de salir del sistema HotelOS.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e4fa1',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'SÍ, SALIR',
                cancelButtonText: 'CANCELAR',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        </script>