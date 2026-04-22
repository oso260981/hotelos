<?= view('layout/header') ?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="flex-1 flex flex-col items-center justify-center bg-[#0f172a] min-h-screen p-6">
    <section id="view-home" class="w-full flex flex-col items-center space-y-12 py-10">
        
        <h2 class="text-2xl font-light text-slate-400 tracking-[0.3em] uppercase">Selecciona una Opción</h2>
        
<?php 
$rol = session()->get('rol'); 
$db = \Config\Database::connect();
$turnoAbierto = $db->table('turnos_operacion')
    ->where('estado', 'ABIERTO')
    ->get()->getRowArray();
$isLocked = !$turnoAbierto;
?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 w-full max-w-7xl">
            
            <?php if ($rol == 1 || $rol == 2): ?>
            <?php if ($isLocked): ?>
            <button onclick="abrirModalTurno()" class="group bg-blue-100 hover:bg-blue-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-blue-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-play-circle text-5xl text-blue-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Empieza Turno</h3>
                <ul class="mt-4 text-sm text-slate-500 group-hover:text-blue-100 space-y-1 font-medium italic">
                    <li>• Termina turno anterior</li>
                    <li>• Baja habitaciones</li>
                    <li>• Reporte automático</li>
                </ul>
            </button>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($rol == 1 || $rol == 3): ?>
            <?php if ($isLocked): ?>
            <button disabled class="group bg-slate-100 p-8 rounded-2xl shadow-xl border-b-[12px] border-slate-300 text-left h-80 opacity-50 cursor-not-allowed">
                <i class="fas fa-file-invoice-dollar text-5xl text-slate-400 mb-6"></i>
                <h3 class="text-2xl font-bold text-slate-400 uppercase">Sacar Reporte</h3>
                <p class="mt-4 text-sm text-slate-400 font-medium italic">Corte de caja, ocupación actual y folios pendientes.</p>
            </button>
            <?php else: ?>
            <button onclick="window.location.href='<?= base_url('reportes') ?>'" class="group bg-green-100 hover:bg-green-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-green-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-file-invoice-dollar text-5xl text-green-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Sacar Reporte</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-green-100 font-medium italic">Corte de caja, ocupación actual y folios pendientes.</p>
            </button>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($rol == 1 || $rol == 2): ?>
            <?php if ($isLocked): ?>
            <button disabled class="group bg-slate-100 p-8 rounded-2xl shadow-xl border-b-[12px] border-slate-300 text-left h-80 opacity-50 cursor-not-allowed">
                <i class="fas fa-users-cog text-5xl text-slate-400 mb-6"></i>
                <h3 class="text-2xl font-bold text-slate-400 uppercase">Lista Pasajeros</h3>
                <p class="mt-4 text-sm text-slate-400 font-medium italic">Búsqueda rápida de huéspedes y registro de salidas.</p>
            </button>
            <?php else: ?>
            <button onclick="window.location.href='<?= base_url('pasajeros') ?>'" class="group bg-pink-100 hover:bg-pink-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-pink-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-users-cog text-5xl text-pink-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Lista Pasajeros</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-pink-100 font-medium italic">Búsqueda rápida de huéspedes y registro de salidas.</p>
            </button>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($rol == 1): ?>
            <?php if ($isLocked): ?>
            <button disabled class="group bg-slate-100 p-8 rounded-2xl shadow-xl border-b-[12px] border-slate-300 text-left h-80 opacity-50 cursor-not-allowed">
                <i class="fas fa-cogs text-5xl text-slate-400 mb-6"></i>
                <h3 class="text-2xl font-bold text-slate-400 uppercase">Editar Sistema</h3>
                <p class="mt-4 text-sm text-slate-400 font-medium italic">Gestión de inventarios, usuarios y configuraciones de red.</p>
            </button>
            <?php else: ?>
            <button onclick="window.location.href='<?= base_url('sistema') ?>'" class="group bg-slate-200 hover:bg-slate-800 p-8 rounded-2xl shadow-xl border-b-[12px] border-slate-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-cogs text-5xl text-slate-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Editar Sistema</h3>
                <p class="mt-4 text-sm text-slate-500 group-hover:text-slate-100 font-medium italic">Gestión de inventarios, usuarios y configuraciones de red.</p>
            </button>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($rol == 1 || $rol == 2): ?>
            <?php if (!$isLocked): ?>
            <button onclick="abrirModalCierre()" class="group bg-red-100 hover:bg-red-600 p-8 rounded-2xl shadow-xl border-b-[12px] border-red-400 transition-all text-left h-80 transform hover:-translate-y-2">
                <i class="fas fa-stop-circle text-5xl text-red-600 group-hover:text-white mb-6"></i>
                <h3 class="text-2xl font-bold group-hover:text-white uppercase">Cerrar Turno</h3>
                <ul class="mt-4 text-sm text-slate-500 group-hover:text-red-100 space-y-1 font-medium italic">
                    <li>• Corte de caja final</li>
                    <li>• Arqueo de efectivo</li>
                    <li>• Imprimir reporte</li>
                </ul>
            </button>
            <?php endif; ?>
            <?php endif; ?>

        </div>
    </section>
</div>

<!-- Modal Abrir Turno -->
<div id="modal-abrir-turno" class="fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl w-[400px] overflow-hidden shadow-2xl">
        <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fas fa-play-circle mr-2"></i> INICIAR TURNO</h3>
            <button onclick="cerrarModalTurno()" class="text-white hover:text-red-200"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Turno a Iniciar</label>
                <select id="turno_id" class="w-full bg-slate-100 border-2 border-slate-200 rounded-xl p-3 font-bold text-slate-700 outline-none focus:border-blue-400">
                    <option value="1">Turno 1 (Mañana)</option>
                    <option value="2">Turno 2 (Tarde)</option>
                    <option value="3">Turno 3 (Noche)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Fondo de Caja Inicial ($)</label>
                <input type="number" id="fondo_inicial" placeholder="0.00" class="w-full bg-slate-100 border-2 border-slate-200 rounded-xl p-3 font-bold text-slate-700 outline-none focus:border-blue-400">
            </div>
            
            <button onclick="procesarAperturaTurno()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest p-4 rounded-xl mt-4 transition-colors">
                ABRIR TURNO AHORA
            </button>
        </div>
    </div>
</div>

<!-- Modal Cerrar Turno -->
<div id="modal-cerrar-turno" class="fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl w-[400px] overflow-hidden shadow-2xl">
        <div class="bg-red-600 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fas fa-stop-circle mr-2"></i> FINALIZAR TURNO</h3>
            <button onclick="cerrarModalCierre()" class="text-white hover:text-red-200"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                <p class="text-xs text-red-600 font-bold uppercase tracking-widest mb-1 text-center">Aviso de Auditoría</p>
                <p class="text-[11px] text-red-500 italic text-center leading-relaxed">
                    Al cerrar el turno, todos los registros en "Check-Out" pasarán a estado "Cerrado" permanentemente.
                </p>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Efectivo Final en Caja ($)</label>
                <input type="number" id="fondo_final" placeholder="0.00" class="w-full bg-slate-100 border-2 border-slate-200 rounded-xl p-3 font-bold text-slate-700 outline-none focus:border-red-400">
            </div>
            
            <button onclick="procesarCierreTurno()" class="w-full bg-red-600 hover:bg-red-700 text-white font-black text-sm uppercase tracking-widest p-4 rounded-xl mt-4 transition-colors">
                REALIZAR CORTE Y CERRAR
            </button>
        </div>
    </div>
</div>

<!-- Modal Ticket Térmico -->
<div id="modal-ticket-reporte" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center hidden">
    <div class="bg-white rounded-lg w-[350px] max-h-[90vh] flex flex-col shadow-2xl">
        <div class="p-4 border-b flex justify-between items-center bg-slate-50">
            <span class="font-bold text-slate-700 uppercase text-xs tracking-widest">Ticket de Turno</span>
            <button onclick="cerrarTicket()" class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="ticket-content" class="flex-1 overflow-y-auto p-6 font-mono text-[11px] leading-tight text-slate-800 bg-white">
            <!-- El contenido se genera dinámicamente -->
        </div>

        <div class="p-4 bg-slate-50 grid grid-cols-2 gap-3">
            <button onclick="imprimirTicket()" class="bg-slate-800 text-white p-3 rounded-lg font-bold text-xs uppercase flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <button onclick="window.location.reload()" class="bg-blue-600 text-white p-3 rounded-lg font-bold text-xs uppercase flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Finalizar
            </button>
            <a id="btn-pdf" href="#" target="_blank" class="bg-red-600 text-white p-3 rounded-lg font-bold text-xs uppercase flex items-center justify-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a id="btn-excel" href="#" target="_blank" class="bg-green-600 text-white p-3 rounded-lg font-bold text-xs uppercase flex items-center justify-center gap-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>
</div>

<script>
function abrirModalTurno() {
    document.getElementById('modal-abrir-turno').classList.remove('hidden');
}
function cerrarModalTurno() {
    document.getElementById('modal-abrir-turno').classList.add('hidden');
}

function abrirModalCierre() {
    document.getElementById('modal-cerrar-turno').classList.remove('hidden');
}
function cerrarModalCierre() {
    document.getElementById('modal-cerrar-turno').classList.add('hidden');
}

function cerrarTicket() {
    document.getElementById('modal-ticket-reporte').classList.add('hidden');
    window.location.reload();
}

async function procesarAperturaTurno() {
    const turno_id = document.getElementById('turno_id').value;
    const fondo = document.getElementById('fondo_inicial').value;
    
    if(!fondo || fondo.trim() === '') {
        Swal.fire('Error', 'Debes ingresar el fondo de caja inicial', 'error');
        return;
    }
    
    try {
        const res = await fetch('<?= base_url("turno/abrir") ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                turno_id: turno_id,
                fondo_caja_inicial: fondo,
                usuario_id: <?= session()->get('id') ?? 0 ?>
            })
        });
        
        const data = await res.json();
        
        if(data.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Turno Abierto',
                text: data.msg,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', data.msg || 'No se pudo abrir el turno', 'error');
        }
        
    } catch(err) {
        console.error(err);
        Swal.fire('Error', 'Problema de conexión', 'error');
    }
}

async function procesarCierreTurno() {
    const fondo = document.getElementById('fondo_final').value;
    
    if(!fondo || fondo.trim() === '') {
        Swal.fire('Error', 'Debes ingresar el efectivo final en caja', 'error');
        return;
    }

    const confirm = await Swal.fire({
        title: '¿Confirmar Cierre?',
        text: "Se procesará el arqueo de caja y se cerrarán los registros pendientes.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'SÍ, CERRAR TURNO',
        cancelButtonText: 'CANCELAR'
    });

    if(!confirm.isConfirmed) return;
    
    try {
        const res = await fetch('<?= base_url("turno/cerrar") ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                fondo_caja_final: fondo
            })
        });
        
        const data = await res.json();
        
        if(data.ok) {
            mostrarTicket(data.reporte);
            cerrarModalCierre();
        } else {
            Swal.fire('Error', data.msg || 'No se pudo cerrar el turno', 'error');
        }
        
    } catch(err) {
        console.error(err);
        Swal.fire('Error', 'Problema de conexión', 'error');
    }
}

function mostrarTicket(rep) {
    const content = document.getElementById('ticket-content');
    const f = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' });

    content.innerHTML = `
        <div class="text-center mb-4">
            <p class="text-sm font-bold">HOTEL OS</p>
            <p>REPORTE DE CIERRE DE TURNO</p>
            <p>--------------------------------</p>
        </div>

        <div class="space-y-1 mb-4">
            <p>TURNO ID: ${rep.turno_info.id}</p>
            <p>TURNO NÚM: ${rep.turno_info.numero}</p>
            <p>USUARIO: ${rep.turno_info.usuario}</p>
            <p>FECHA: ${rep.turno_info.fecha}</p>
            <p>HORA CIERRE: ${rep.turno_info.hora_cierre}</p>
        </div>

        <p class="font-bold border-b border-dashed mb-2 pb-1">OCUPACIÓN</p>
        <div class="grid grid-cols-2 mb-4">
            <span>Check-ins:</span><span class="text-right">${rep.ocupacion.checkins}</span>
            <span>Check-outs:</span><span class="text-right">${rep.ocupacion.checkouts}</span>
            <span>Personas:</span><span class="text-right">${rep.ocupacion.personas}</span>
        </div>

        <p class="font-bold border-b border-dashed mb-2 pb-1">FINANZAS</p>
        <div class="grid grid-cols-2 mb-4">
            <span>Hosp. Efectivo:</span><span class="text-right">${f.format(rep.finanzas.hospedaje_efectivo)}</span>
            <span>Hosp. Tarjeta:</span><span class="text-right">${f.format(rep.finanzas.hospedaje_tarjeta)}</span>
            <span>RS Efectivo:</span><span class="text-right">${f.format(rep.finanzas.room_service_efectivo)}</span>
            <p class="col-span-2">--------------------------------</p>
            <span>TOTAL IVA:</span><span class="text-right">${f.format(rep.finanzas.iva)}</span>
            <span>TOTAL ISH:</span><span class="text-right">${f.format(rep.finanzas.ish)}</span>
        </div>

        <p class="font-bold border-b border-dashed mb-2 pb-1">ARQUEO DE CAJA</p>
        <div class="grid grid-cols-2 mb-6">
            <span>Fondo Inicial:</span><span class="text-right">${f.format(rep.arqueo.fondo_inicial)}</span>
            <span>(+) Ingresos:</span><span class="text-right">${f.format(rep.finanzas.hospedaje_efectivo + rep.finanzas.room_service_efectivo)}</span>
            <p class="col-span-2">--------------------------------</p>
            <span class="font-bold">ESP. CAJA:</span><span class="text-right font-bold">${f.format(rep.arqueo.efectivo_esperado)}</span>
            <span class="font-bold">REAL CAJA:</span><span class="text-right font-bold">${f.format(rep.arqueo.efectivo_real)}</span>
            <p class="col-span-2">--------------------------------</p>
            <span class="font-bold ${rep.arqueo.diferencia < 0 ? 'text-red-600' : 'text-green-600'}">DIFERENCIA:</span>
            <span class="text-right font-bold ${rep.arqueo.diferencia < 0 ? 'text-red-600' : 'text-green-600'}">${f.format(rep.arqueo.diferencia)}</span>
        </div>

        <div class="text-center mt-10">
            <p>__________________________</p>
            <p>FIRMA DEL RECEPTOR</p>
        </div>
    `;

    document.getElementById('btn-pdf').href = `<?= base_url('turno/pdf/') ?>${rep.turno_info.id}`;
    document.getElementById('btn-excel').href = `<?= base_url('turno/excel/') ?>${rep.turno_info.id}`;

    document.getElementById('modal-ticket-reporte').classList.remove('hidden');
}

function imprimirTicket() {
    const printContent = document.getElementById('ticket-content').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = `
        <style>
            @media print {
                body * { visibility: hidden; }
                #print-section, #print-section * { visibility: visible; }
                #print-section { 
                    position: absolute; 
                    left: 0; 
                    top: 0; 
                    width: 58mm; 
                    font-family: monospace;
                    padding: 0;
                    margin: 0;
                }
                @page { margin: 0; size: 58mm auto; }
            }
        </style>
        <div id="print-section">${printContent}</div>
    `;

    window.print();
    window.location.reload();
}
</script>


        </div>
    </section>
</div>

<?= view('layout/footer') ?>