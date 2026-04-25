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
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; overflow: hidden; }
        .gradient-text { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .signature-pad { border: 2px dashed #cbd5e1; background-color: white; border-radius: 1.5rem; cursor: crosshair; touch-action: none; }
        .btn-premium { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .btn-premium:active { transform: scale(0.95); }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- PANTALLA DE ESPERA (BIENVENIDA) -->
    <div id="step-wait" class="text-center space-y-8 animate-in fade-in duration-700">
        <div class="relative inline-block">
            <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-20 rounded-full"></div>
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="HotelOS" class="h-24 mx-auto relative drop-shadow-2xl opacity-10 grayscale">
        </div>
        <div>
            <h1 class="text-4xl font-black gradient-text tracking-tighter uppercase italic">Bienvenidos</h1>
            <p class="text-slate-400 mt-2 font-medium tracking-widest uppercase text-xs">Por favor, espere un momento...</p>
        </div>
        <div class="flex justify-center space-x-2">
            <div class="w-2 h-2 bg-slate-200 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-slate-200 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-slate-200 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
        </div>
    </div>

    <!-- PANTALLA DE FIRMA -->
    <div id="step-sign" class="hidden w-full max-w-2xl glass-card rounded-[3rem] p-8 shadow-2xl space-y-8 animate-in slide-in-from-bottom duration-500">
        <div class="text-center">
            <h2 class="text-sm font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Confirmación de Registro</h2>
            <h3 id="guest-name" class="text-3xl font-black text-slate-800 tracking-tight uppercase italic">---</h3>
            <p class="text-slate-400 text-xs mt-2 font-medium uppercase">Por favor, firme en el cuadro de abajo para finalizar.</p>
        </div>

        <div class="relative">
            <canvas id="signature-canvas" class="signature-pad w-full h-64 shadow-inner"></canvas>
            <button onclick="clearCanvas()" class="absolute bottom-4 right-4 text-slate-400 hover:text-slate-600 text-xs font-bold uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                <i class="fas fa-eraser mr-1"></i> Limpiar
            </button>
        </div>

        <div class="flex flex-col space-y-4">
            <button onclick="saveSignature()" class="btn-premium w-full py-5 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl text-sm">
                <i class="fas fa-check-double mr-2"></i> Finalizar Registro
            </button>
        </div>
    </div>

    <!-- PANTALLA DE ÉXITO -->
    <div id="step-success" class="hidden text-center space-y-6 animate-in zoom-in duration-500">
        <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-[0_0_50px_rgba(16,185,129,0.3)]">
            <i class="fas fa-check text-4xl text-white"></i>
        </div>
        <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter">¡Gracias!</h2>
        <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">Su firma ha sido registrada con éxito.</p>
    </div>

    <script>
        const canvas = document.getElementById('signature-canvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let currentIdOcr = null;
        let pollingInterval = null;

        // Ajustar canvas al tamaño del contenedor
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#0f172a';
        }

        window.addEventListener('resize', resizeCanvas);
        setTimeout(resizeCanvas, 100);

        // Lógica de dibujo
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);

        canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e.touches[0]); });
        canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e.touches[0]); });
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            ctx.beginPath();
            const rect = canvas.getBoundingClientRect();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
        }

        function stopDrawing() { isDrawing = false; }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        // POLLING para verificar estado
        function checkStatus() {
            fetch('<?= base_url('tablet/checkStatus') ?>')
            .then(r => r.json())
            .then(data => {
                if (data.accion === 'mostrar_firma') {
                    clearInterval(pollingInterval);
                    currentIdOcr = data.id_ocr;
                    document.getElementById('guest-name').textContent = data.nombre;
                    
                    document.getElementById('step-wait').classList.add('hidden');
                    document.getElementById('step-sign').classList.remove('hidden');
                    resizeCanvas();
                }
            })
            .catch(e => console.error("Error en polling", e));
        }

        pollingInterval = setInterval(checkStatus, 5000);

        function saveSignature() {
            // Verificar si el canvas está vacío (opcional)
            const signatureData = canvas.toDataURL('image/png');
            
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
                    document.getElementById('step-sign').classList.add('hidden');
                    document.getElementById('step-success').classList.remove('hidden');
                    
                    setTimeout(() => {
                        location.reload(); // Reiniciar para volver a espera
                    }, 3000);
                } else {
                    alert("Error al guardar: " + res.msg);
                }
            });
        }
    </script>
</body>
</html>
