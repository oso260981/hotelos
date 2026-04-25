<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Escanear Documento - HotelOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #0f172a; color: white; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .btn-capture { background: linear-gradient(135deg, #2563eb, #7c3aed); }
        .preview-container { perspective: 1000px; }
        #img-preview { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); max-height: 50vh; object-fit: contain; }
    </style>
</head>
<body class="flex flex-col min-h-screen items-center justify-center p-6 text-center">

    <!-- PASO 1: INICIO -->
    <div id="step-init" class="space-y-6">
        <div class="w-24 h-24 bg-blue-600/20 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
            <i class="fas fa-id-card text-4xl text-blue-400"></i>
        </div>
        <h1 class="text-3xl font-black tracking-tighter uppercase italic">Escaneo de Identidad</h1>
        <p class="text-slate-400 text-sm max-w-xs mx-auto">Toma una foto clara de tu identificación (INE o Pasaporte) para completar tu registro.</p>
        
    <div class="mt-12">
        <input type="file" id="input-file" accept="image/*" capture="environment" class="hidden">
        <div onclick="document.getElementById('input-file').click()" 
             class="btn-capture w-full py-5 px-8 rounded-2xl font-black uppercase tracking-widest shadow-2xl active:scale-95 transition-all cursor-pointer">
            <i class="fas fa-camera mr-2"></i> Abrir Cámara
        </div>
    </div>
    </div>

    <!-- PASO 2: PREVIEW Y ROTACIÓN -->
    <div id="step-preview" class="hidden space-y-6 w-full max-w-sm">
        <h2 class="text-xl font-bold uppercase italic">Revisar Foto</h2>
        <div class="preview-container bg-slate-800 rounded-3xl p-4 overflow-hidden shadow-2xl border border-slate-700">
            <img id="img-preview" src="" class="w-full rounded-xl">
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <button onclick="rotateImage()" class="bg-slate-700 py-4 rounded-2xl font-bold text-sm uppercase">
                <i class="fas fa-sync-alt mr-2 text-blue-400"></i> Rotar
            </button>
            <button onclick="location.reload()" class="bg-slate-800 py-4 rounded-2xl font-bold text-sm uppercase text-slate-400">
                <i class="fas fa-redo mr-2"></i> Repetir
            </button>
        </div>

        <button onclick="uploadProcessedImage()" class="btn-capture w-full py-5 rounded-2xl font-black uppercase tracking-widest shadow-2xl">
            <i class="fas fa-cloud-upload-alt mr-2"></i> Confirmar y Enviar
        </button>
    </div>

    <!-- PASO 3: PROCESANDO -->
    <div id="step-loading" class="hidden space-y-6">
        <div class="w-20 h-20 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
        <h2 class="text-xl font-bold uppercase italic">Procesando Documento</h2>
        <p class="text-slate-400 text-sm">Estamos extrayendo la información mediante IA. No cierres esta ventana.</p>
    </div>

    <!-- PASO 4: ÉXITO -->
    <div id="step-success" class="hidden space-y-6">
        <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-[0_0_50px_rgba(16,185,129,0.3)]">
            <i class="fas fa-check text-4xl text-white"></i>
        </div>
        <h2 class="text-2xl font-black uppercase italic">¡Listo!</h2>
        <p class="text-slate-400 text-sm">Tus datos han sido enviados a la recepción. Ya puedes cerrar esta ventana.</p>
    </div>

    <canvas id="canvas-process" class="hidden"></canvas>

    <script>
        const inputFile = document.getElementById('input-file');
        const imgPreview = document.getElementById('img-preview');
        const token = '<?= $token ?>';
        let currentRotation = 0;
        let originalBase64 = '';

        inputFile.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                originalBase64 = event.target.result;
                imgPreview.src = originalBase64;
                
                document.getElementById('step-init').classList.add('hidden');
                document.getElementById('step-preview').classList.remove('hidden');

                // Limpiar el valor para permitir re-selección
                inputFile.value = '';
            };
            reader.readAsDataURL(file);
        });

        function rotateImage() {
            currentRotation = (currentRotation + 90) % 360;
            imgPreview.style.transform = `rotate(${currentRotation}deg)`;
        }

        async function uploadProcessedImage() {
            document.getElementById('step-preview').classList.add('hidden');
            document.getElementById('step-loading').classList.remove('hidden');

            const rotatedBlob = await getRotatedBlob(originalBase64, currentRotation);
            
            const formData = new FormData();
            formData.append('image', rotatedBlob, 'documento.jpg');

            try {
                const resp = await fetch('<?= base_url("scan/upload") ?>/' + token, {
                    method: 'POST',
                    body: formData
                });

                const res = await resp.json();
                if (res.ok) {
                    document.getElementById('step-loading').classList.add('hidden');
                    document.getElementById('step-success').classList.remove('hidden');
                } else {
                    alert('Error al procesar: ' + res.msg);
                    location.reload();
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
                location.reload();
            }
        }

        function getRotatedBlob(base64, degrees) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.getElementById('canvas-process');
                    const ctx = canvas.getContext('2d');
                    
                    if (degrees === 90 || degrees === 270) {
                        canvas.width = img.height;
                        canvas.height = img.width;
                    } else {
                        canvas.width = img.width;
                        canvas.height = img.height;
                    }
                    
                    ctx.translate(canvas.width / 2, canvas.height / 2);
                    ctx.rotate((degrees * Math.PI) / 180);
                    ctx.drawImage(img, -img.width / 2, -img.height / 2);
                    
                    canvas.toBlob((blob) => {
                        resolve(blob);
                    }, 'image/jpeg', 0.8);
                };
                img.src = base64;
            });
        }
    </script>
</body>
</html>
