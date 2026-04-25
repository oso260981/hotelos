<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Firma Digital - HotelOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap');
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; overflow: hidden; height: 100vh; width: 100vw; }
        .view { display: none; height: 100%; width: 100%; opacity: 0; transition: opacity 0.5s ease; }
        .view.active { display: flex; opacity: 1; }
        .signature-pad-container { border: 2px dashed #cbd5e1; background-color: white; border-radius: 2rem; cursor: crosshair; touch-action: none; position: relative; }
        canvas { width: 100%; height: 100%; border-radius: 2rem; }
    </style>
</head>
<body class="min-h-screen">

    <!-- VISTA 1: ESPERA (CON BOTÓN MANUAL) -->
    <div id="view-wait" class="view active flex-col items-center justify-center text-center p-8">
        <div class="mb-12 animate-pulse">
            <div class="w-32 h-32 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-2xl shadow-blue-500/50">
                <i class="fas fa-hotel text-5xl text-white"></i>
            </div>
        </div>
        <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tighter">BIENVENIDO</h1>
        <p class="text-xl text-slate-500 font-medium mb-12 max-w-md mx-auto">Por favor, presione el botón de abajo para iniciar su registro y firma digital.</p>
        
        <button onclick="checkStatusManual()" id="btn-check" class="group relative bg-slate-900 text-white px-12 py-6 rounded-3xl font-black text-2xl shadow-2xl hover:scale-105 transition-all active:scale-95">
            <span id="btn-text" class="flex items-center space-x-4">
                <i class="fas fa-fingerprint text-3xl text-blue-400 group-hover:animate-bounce"></i>
                <span>INICIAR REGISTRO</span>
            </span>
            <div id="check-loader" class="hidden absolute inset-0 bg-slate-900 rounded-3xl flex items-center justify-center">
                <div class="w-8 h-8 border-4 border-blue-400 border-t-transparent rounded-full animate-spin"></div>
            </div>
        </button>
        
        <p id="check-msg" class="mt-6 text-sm font-bold text-rose-500 opacity-0 transition-opacity">No hay registros pendientes. Avise a recepción.</p>
    </div>

    <!-- VISTA 2: FIRMA -->
    <div id="view-signature" class="view flex-col p-8 lg:p-12">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 id="guest-name" class="text-4xl lg:text-6xl font-black text-slate-900 tracking-tighter mb-2 uppercase italic">HOLA</h2>
                <p class="text-xl text-slate-500 font-medium">Por favor, firme dentro del recuadro blanco para continuar.</p>
            </div>
            <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center">
                <i class="fas fa-pen-nib text-3xl text-slate-400"></i>
            </div>
        </div>

        <div class="flex-1 signature-pad-container shadow-2xl mb-8">
            <canvas id="sig-canvas"></canvas>
            <button onclick="clearCanvas()" class="absolute top-6 right-6 bg-slate-100 hover:bg-rose-100 text-slate-400 hover:text-rose-600 w-12 h-12 rounded-2xl transition-colors">
                <i class="fas fa-eraser"></i>
            </button>
        </div>

        <button onclick="saveSignature()" id="btn-save" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-8 rounded-[2rem] font-black text-3xl shadow-2xl shadow-blue-600/30 transition-all hover:-translate-y-1 active:scale-[0.98]">
            FINALIZAR REGISTRO
        </button>
    </div>

    <!-- VISTA 3: ÉXITO -->
    <div id="view-success" class="view flex-col items-center justify-center text-center p-8">
        <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-[0_0_50px_rgba(16,185,129,0.3)] mb-8">
            <i class="fas fa-check text-4xl text-white"></i>
        </div>
        <h2 class="text-5xl font-black text-slate-900 uppercase italic tracking-tighter mb-4">¡GRACIAS!</h2>
        <p class="text-xl text-slate-500 font-medium uppercase tracking-widest">Su registro ha sido completado con éxito.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const canvas = document.querySelector("#sig-canvas");
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: '#0f172a',
            minWidth: 2,
            maxWidth: 4
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener("resize", resizeCanvas);
        // Llamar inicialmente pero con un pequeño delay para asegurar que el offsetWidth sea correcto
        setTimeout(resizeCanvas, 200);

        let currentIdOcr = null;

        function checkStatusManual() {
            const btn = document.getElementById('btn-check');
            const loader = document.getElementById('check-loader');
            const msg = document.getElementById('check-msg');

            btn.disabled = true;
            loader.classList.remove('hidden');
            msg.classList.add('opacity-0');

            fetch('<?= base_url('tablet/checkStatus') ?>')
                .then(r => r.json())
                .then(res => {
                    if (res.accion === 'mostrar_firma') {
                        currentIdOcr = res.id_ocr;
                        document.getElementById('guest-name').textContent = "HOLA, " + res.nombre;
                        switchView('view-signature');
                        setTimeout(resizeCanvas, 100);
                    } else {
                        msg.classList.remove('opacity-0');
                        setTimeout(() => msg.classList.add('opacity-0'), 4000);
                    }
                })
                .catch(e => {
                    console.error(e);
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    loader.classList.add('hidden');
                });
        }

        function clearCanvas() {
            signaturePad.clear();
        }

        function saveSignature() {
            if (signaturePad.isEmpty()) {
                return Swal.fire('Firma requerida', 'Por favor, firme antes de finalizar.', 'warning');
            }

            const btn = document.getElementById('btn-save');
            btn.disabled = true;
            btn.textContent = "GUARDANDO...";

            const signatureData = signaturePad.toDataURL('image/png');
            
            fetch('<?= base_url('tablet/guardarFirma') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_ocr: currentIdOcr,
                    firma: signatureData
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.ok) {
                    switchView('view-success');
                    setTimeout(() => {
                        location.reload(); 
                    }, 4000);
                } else {
                    Swal.fire('Error', res.msg, 'error');
                    btn.disabled = false;
                    btn.textContent = "FINALIZAR REGISTRO";
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Error de conexión', 'error');
                btn.disabled = false;
                btn.textContent = "FINALIZAR REGISTRO";
            });
        }

        function switchView(viewId) {
            document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
        }
    </script>
</body>
</html>
