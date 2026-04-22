<?= view('layout/header') ?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="flex-1 flex flex-col items-center justify-center bg-[#0f172a] min-h-screen p-6">
    <section id="view-home" class="w-full flex flex-col items-center space-y-12 py-10">
        
        <h2 class="text-2xl font-light text-slate-400 tracking-[0.3em] uppercase">Selecciona una Opción</h2>
        
        <?php $rol = session()->get('rol'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 w-full max-w-7xl">
            
            <?php if ($rol == 1 || $rol == 2): ?>
            <button onclick="window.location.href='<?= base_url('trabajo') ?>'" class="group bg-blue-100 hover:bg-blue-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-blue-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-play-circle text-5xl text-blue-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Empieza Turno</h3>
                <ul class="mt-4 text-sm text-slate-500 group-hover:text-blue-100 space-y-1 font-medium italic">
                    <li>• Termina turno anterior</li>
                    <li>• Baja habitaciones</li>
                    <li>• Reporte automático</li>
                </ul>
            </button>
            <?php endif; ?>

            <?php if ($rol == 1 || $rol == 3): ?>
            <button onclick="window.location.href='<?= base_url('reportes') ?>'" class="group bg-green-100 hover:bg-green-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-green-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-file-invoice-dollar text-5xl text-green-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Sacar Reporte</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-green-100 font-medium italic">Corte de caja, ocupación actual y folios pendientes.</p>
            </button>
            <?php endif; ?>

            <?php if ($rol == 1 || $rol == 2): ?>
            <button onclick="window.location.href='<?= base_url('pasajeros') ?>'" class="group bg-pink-100 hover:bg-pink-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-pink-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-users-cog text-5xl text-pink-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Lista Pasajeros</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-pink-100 font-medium italic">Búsqueda rápida de huéspedes y registro de salidas.</p>
            </button>
            <?php endif; ?>

            <?php if ($rol == 1): ?>
            <button onclick="window.location.href='<?= base_url('sistema') ?>'" class="group bg-slate-200 hover:bg-slate-800 p-8 rounded-2xl shadow-xl border-b-[12px] border-slate-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-cogs text-5xl text-slate-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Editar Sistema</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-slate-100 font-medium italic">Gestión de inventarios, usuarios y configuraciones de red.</p>
            </button>
            <?php endif; ?>

        </div>
    </section>
</div>

<?= view('layout/footer') ?>