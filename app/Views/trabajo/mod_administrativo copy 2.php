<link rel="stylesheet" href="<?= base_url('assets/css/modal_adm.css') ?>">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">




<style>
/* =========================================
           ESTILOS BASE Y TIPOGRAFÍA
           ========================================= */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');



.dashboard-bg {
    position: fixed;
    inset: 0;
    background: radial-gradient(circle at top left, #1e293b, #0f172a);
    z-index: -1;
}

/* MASTER LAYOUT */
.admin-modal {
    width: 98vw;
    height: 95vh;
    max-width: 1500px;
    background: #ffffff;
    border-radius: 28px;
    display: grid;
    grid-template-columns: 280px 1fr 380px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    color: #0f172a;
}

/* SIDEBAR IZQUIERDO */
.admin-sidebar {
    background-color: #f8fafc;
    border-right: 1px solid #e2e8f0;
    padding: 32px 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.nav-tab {
    padding: 14px 18px;
    border-radius: 14px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
    cursor: pointer;
}

.nav-tab.active {
    background: #4f46e5;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* CONTENIDO CENTRAL */
.admin-content {
    padding: 32px;
    overflow-y: auto;
    background: #ffffff;
}

/* SIDEBAR DERECHO */
.admin-audit {
    background-color: #0f172a;
    color: white;
    padding: 24px;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #1e293b;
}

/* COMPONENTES REUTILIZABLES */
.pms-label {
    font-size: 9px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 5px;
    display: block;
    letter-spacing: 0.05em;
}

.pms-badge {
    font-size: 9px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 6px;
    text-transform: uppercase;
}

.badge-success {
    background: #ecfdf5;
    color: #059669;
}

.data-card {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    padding: 16px;
    transition: all 0.2s;
}

.pms-input {
    width: 100%;
    padding: 10px 14px;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
}

/* MODALES SECUNDARIOS */
.modal-overlay-sub {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease;
}

.modal-overlay-sub.active {
    visibility: visible;
    opacity: 1;
}

.modal-container-edit {
    background: white;
    width: 90%;
    max-width: 1000px;
    height: 90vh;
    border-radius: 32px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.5);
    transform: scale(0.9);
    transition: 0.3s;
}

.modal-overlay-sub.active .modal-container-edit {
    transform: scale(1);
}

.custom-scroll::-webkit-scrollbar {
    width: 4px;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.pax-photo {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid white;
}

/* ESTILOS CÁMARA */
#camera-container {
    position: relative;
    width: 100%;
    aspect-ratio: 1/1;
    background: #000;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

#camera-stream {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>


<div class="dashboard-bg"></div>



<!-- EL MODAL PRINCIPAL (MASTER CONTAINER) -->
<div id="admin-modal-container">
    <div class="admin-modal">

        <!-- SIDEBAR DE NAVEGACIÓN -->
        <aside class="admin-sidebar">
            <div class="mb-8 px-2">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-lg">
                        <i class="fas fa-id-card-alt"></i>
                    </div>
                    <div>
                        <p class="pms-label mb-0">Habitación</p>
                        <h2 id="admin_habitacion" class="font-black text-xl tracking-tighter">—</h2>
                    </div>
                </div>
                <span class="pms-badge badge-success"><i class="fas fa-check-circle mr-1"></i> Check-in Activo</span>
            </div>

            <nav class="flex-1 overflow-y-auto custom-scroll pr-1">
                <div class="nav-tab active" id="tab-resumen" onclick="switchSection('resumen')"><i
                        class="fas fa-th-large"></i> Resumen de Folio</div>
                <div class="nav-tab" id="tab-cargos" onclick="switchSection('cargos')"><i
                        class="fas fa-plus-circle"></i> Cargos & Consumos</div>
                <div class="nav-tab" id="tab-pagos" onclick="switchSection('pagos')"><i class="fas fa-wallet"></i> Pagos
                    & Abonos</div>
                <div class="nav-tab" id="tab-room_move" onclick="switchSection('room_move')"><i
                        class="fas fa-exchange-alt"></i> Cambio de Unidad</div>
                <div class="nav-tab" id="tab-room_service" onclick="switchSection('room_service')"><i
                        class="fas fa-utensils"></i> Room Service</div>
                <div class="nav-tab" id="tab-bitacora" onclick="switchSection('bitacora')"><i
                        class="fas fa-history"></i> Bitácora de Cambios</div>
            </nav>

            <div class="mt-auto space-y-3 pt-4 border-t">
                <button
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">
                    <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Procesar Check-out
                </button>
                <button
                    class="w-full py-4 bg-red-50 text-red-500 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-ban mr-2"></i> Cancelar Estadía
                </button>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="admin-content custom-scroll">

            <!-- SECCIÓN: RESUMEN -->
            <div id="sec-resumen" class="space-y-8 fade-in">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 id="admin_nombre" class="text-3xl font-black tracking-tighter">
                            —
                        </h1>

                        <p class="text-slate-400 font-medium">
                            Folio:
                            <span id="admin_folio" class="text-indigo-600 font-bold">—</span>
                            |
                            Registro:
                            <span id="admin_fecha_registro">—</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleEditModal(true)"
                            class="bg-indigo-50 text-indigo-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-indigo-100 transition-all">Editar
                            Perfil</button>
                        <button
                            class="bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-slate-200 transition-all"><i
                                class="fas fa-print"></i></button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="data-card">
                        <p class="pms-label">Estancia Actual</p>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="text-xs font-bold text-slate-400">Entrada</p>
                                <p id="admin_fecha_entrada" class="font-black text-sm">—</p>
                            </div>

                            <div class="text-indigo-200">
                                <i class="fas fa-arrow-right"></i>
                            </div>

                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-400">Salida</p>
                                <p id="admin_fecha_salida" class="font-black text-sm">—</p>
                            </div>
                        </div>
                    </div>


                    <div class="pax-info-card" onclick="toggleCompanionsList(true)">

                        <p class="pms-label">Información de Pax</p>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex -space-x-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 font-black text-[10px]">
                                    AD</div>
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-emerald-600 font-black text-[10px]">
                                    NI</div>
                            </div>
                            <p class="text-xs font-bold">2 Adultos, 1 Niño</p>
                        </div>
                    </div>





                    <div class="data-card">
                        <p class="pms-label">Vehículo & Acceso</p>
                        <div class="mt-2 flex items-center gap-3">
                            <i class="fas fa-car text-slate-300 text-lg"></i>
                            <div>
                                <p class="text-xs font-black">PLACA: ABC-123-X</p>
                                <p class="text-[9px] text-slate-400 uppercase font-bold">Lote Estacionamiento: B-04</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-black text-lg uppercase tracking-tight">Últimos Consumos Registrados</h3>
                        <button onclick="switchSection('cargos')"
                            class="text-indigo-600 font-black text-[10px] uppercase hover:underline">+ Añadir Cargo
                            Manual</button>
                    </div>
                    <div class="border rounded-2xl overflow-hidden shadow-sm">
                        <table id="table-consumos">
                            <thead>
                                <tr>
                                    <th>Fecha / Hora</th>
                                    <th>Concepto / Descripción</th>
                                    <th>Cajero / Usuario</th>
                                    <th>Estado</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dinámico -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: ALTA DE CARGOS -->
            <div id="sec-cargos" class="hidden space-y-8 fade-in">
                <h3 class="font-black text-lg uppercase tracking-tight border-b pb-4">Alta de Cargos Manuales</h3>
                <div class="grid grid-cols-12 gap-6 bg-slate-50 p-8 rounded-2xl border border-slate-100">
                    <div class="col-span-6">
                        <label class="pms-label">Concepto de Cargo</label>
                        <input type="text" id="cargo-concepto" class="pms-input"
                            placeholder="Ej: Lavandería Express, Daños en mobiliario">
                    </div>
                    <div class="col-span-3">
                        <label class="pms-label">Departamento</label>
                        <select id="cargo-depto" class="pms-input">
                            <option>RECEPCIÓN</option>
                            <option>LAVANDERÍA</option>
                            <option>MINIBAR</option>
                            <option>ESTACIONAMIENTO</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="pms-label">Monto ($)</label>
                        <input type="number" id="cargo-monto" class="pms-input" placeholder="0.00">
                    </div>
                    <div class="col-span-12 flex justify-end">
                        <button onclick="altaCargoManual()" class="btn-action btn-primary px-8 py-3">Aplicar Cargo al
                            Folio</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: PAGOS & ABONOS -->
            <div id="sec-pagos" class="hidden space-y-8 fade-in">
                <h3 class="font-black text-lg uppercase tracking-tight border-b pb-4">Registrar Pago o Abono</h3>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 grid grid-cols-3 gap-4">
                        <div class="pay-method selected" id="method-efectivo" onclick="selectPayMethod('Efectivo')">
                            <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                            <p class="text-xs font-black uppercase">Efectivo</p>
                        </div>
                        <div class="pay-method" id="method-tarjeta" onclick="selectPayMethod('Tarjeta')">
                            <i class="fas fa-credit-card text-2xl mb-2"></i>
                            <p class="text-xs font-black uppercase">Tarjeta</p>
                        </div>
                        <div class="pay-method" id="method-transferencia" onclick="selectPayMethod('Transferencia')">
                            <i class="fas fa-university text-2xl mb-2"></i>
                            <p class="text-xs font-black uppercase">Transf.</p>
                        </div>
                    </div>

                    <div class="col-span-8">
                        <label class="pms-label">Referencia / Comprobante</label>
                        <input type="text" id="pago-referencia" class="pms-input" placeholder="Opcional">
                    </div>
                    <div class="col-span-4">
                        <label class="pms-label">Monto del Abono ($)</label>
                        <input type="number" id="pago-monto" class="pms-input" placeholder="0.00">
                    </div>
                    <div class="col-span-12 flex justify-end">
                        <button onclick="registrarAbono()"
                            class="btn-action btn-primary px-10 py-4 bg-emerald-600 hover:bg-emerald-700">Confirmar
                            Abono</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="font-black text-sm uppercase text-slate-400">Historial de Pagos</h3>
                    <div class="border rounded-2xl overflow-hidden shadow-sm">
                        <table id="table-pagos">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Método de Pago</th>
                                    <th>Referencia</th>
                                    <th class="text-right">Abono</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dinámico -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: ROOM SERVICE -->
            <div id="sec-room_service" class="hidden space-y-8 fade-in">
                <div class="flex justify-between items-center border-b pb-4">
                    <h3 class="font-black text-lg uppercase tracking-tight">Menú de Room Service</h3>
                    <div class="flex gap-2">
                        <button class="pill-btn active">Todo</button>
                        <button class="pill-btn">Desayunos</button>
                        <button class="pill-btn">Comidas</button>
                        <button class="pill-btn">Bebidas</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Categoría: Comidas -->
                    <div class="menu-item" onclick="addRoomService('Hamburguesa Elite', 320)">
                        <div class="menu-icon"><i class="fas fa-hamburger"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Hamburguesa Elite</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Res Angus + Papas Trufadas</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$320.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Pizza Margarita Pro', 280)">
                        <div class="menu-icon"><i class="fas fa-pizza-slice"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Pizza Margarita Pro</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Horno de leña, Albahaca fresca</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$280.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Club Sandwich', 190)">
                        <div class="menu-icon"><i class="fas fa-bread-slice"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Club Sandwich</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Clásico 4 niveles</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$190.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Ensalada César', 180)">
                        <div class="menu-icon"><i class="fas fa-leaf"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Ensalada César</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Aderezo de la casa</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$180.00</p>
                    </div>

                    <!-- NUEVAS PETICIONES / BEBIDAS -->
                    <div class="menu-item" onclick="addRoomService('Corte Rib Eye 400g', 550)">
                        <div class="menu-icon"><i class="fas fa-utensils"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Corte Rib Eye 400g</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Al punto con guarnición</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$550.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Vino Tinto Casa', 420)">
                        <div class="menu-icon"><i class="fas fa-wine-bottle"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Vino Tinto Casa</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Copa o botella individual</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$420.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Café Americano Gourmet', 65)">
                        <div class="menu-icon"><i class="fas fa-coffee"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Café Americano Gourmet</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Granos de altura</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$65.00</p>
                    </div>
                    <div class="menu-item" onclick="addRoomService('Refresco 355ml', 45)">
                        <div class="menu-icon"><i class="fas fa-wine-glass-alt"></i></div>
                        <div class="flex-1">
                            <p class="text-sm font-black">Refresco 355ml</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Variedad de sabores</p>
                        </div>
                        <p class="font-black text-indigo-600 text-sm">$45.00</p>
                    </div>
                </div>

                <!-- PETICIONES ESPECIALES -->
                <div class="mt-8 space-y-4">
                    <h3 class="pms-label text-indigo-600">Servicios Adicionales (Peticiones)</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <button class="btn-action btn-secondary py-4"
                            onclick="addRoomService('Kit Toallas Extra', 50)">+ Toallas Extra</button>
                        <button class="btn-action btn-secondary py-4"
                            onclick="addRoomService('Lavandería Express', 250)">+ Lavandería</button>
                        <button class="btn-action btn-secondary py-4"
                            onclick="addRoomService('Cama Extra / Rollaway', 400)">+ Cama Extra</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: CAMBIO DE HABITACIÓN -->
            <div id="sec-room_move" class="hidden space-y-8 fade-in">
                <div class="bg-amber-50 border border-amber-200 p-6 rounded-2xl flex items-center gap-4">
                    <i class="fas fa-info-circle text-amber-500 text-2xl"></i>
                    <div>
                        <h4 class="font-black text-amber-900 text-sm uppercase">Proceso de Room Move</h4>
                        <p class="text-xs text-amber-700">El sistema transferirá automáticamente todos los cargos
                            pendientes de la Suite #204 a la nueva unidad seleccionada.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="pms-label">Nueva Habitación Disponible</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button class="data-card text-center hover:bg-white active:scale-95">
                                <p class="text-xs font-black">#105</p>
                                <p class="text-[8px] text-slate-400 uppercase">Doble</p>
                            </button>
                            <button class="data-card text-center border-indigo-600 bg-indigo-50 shadow-md">
                                <p class="text-xs font-black">#210</p>
                                <p class="text-[8px] text-slate-400 uppercase">Master</p>
                            </button>
                            <button class="data-card text-center hover:bg-white opacity-40 cursor-not-allowed">
                                <p class="text-xs font-black">#301</p>
                                <p class="text-[8px] text-slate-400 uppercase">Ocupada</p>
                            </button>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                        <p class="pms-label mb-4">Motivo del Cambio</p>
                        <select class="pms-input mb-4">
                            <option>Preferencia del Huésped</option>
                            <option>Problema Técnico / Mantenimiento</option>
                            <option>Upgrade de Cortesía</option>
                        </select>
                        <button
                            class="w-full btn-primary py-3 rounded-xl text-[10px] tracking-widest uppercase font-black">Ejecutar
                            Cambio de Unidad</button>
                    </div>
                </div>
            </div>
        </main>

        <!-- SIDEBAR DE AUDITORÍA Y TOTALES (DERECHA) -->
        <aside class="admin-audit">
            <div class="text-center mb-8 border-b border-slate-800 pb-6">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.5em] mb-2 italic">Estado de Cuenta
                    Actual</p>
                <h4 class="text-indigo-400 font-mono text-base font-bold tracking-widest uppercase">Israel de los Santos
                </h4>
            </div>

            <div class="flex-1 space-y-6 overflow-y-auto custom-scroll pr-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-bold uppercase tracking-wider">Hospedaje Acumulado</span>
                    <span class="font-mono" id="audit-hospedaje">$0.00</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-bold uppercase tracking-wider">Consumos Extras</span>
                    <span class="font-mono" id="audit-extras">$0.00</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-bold uppercase tracking-wider">Impuestos (19%)</span>
                    <span class="font-mono" id="audit-taxes">$0.00</span>
                </div>

                <div class="border-t border-slate-700 pt-8 mt-4">
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Importe Total
                            Bruto</span>
                        <span class="text-4xl font-black text-white font-mono tracking-tighter leading-none"
                            id="total_acumulado">$0.00</span>
                    </div>
                </div>

                <div class="pt-8 space-y-4">
                    <div
                        class="flex justify-between items-center p-4 bg-emerald-500/10 rounded-2xl border border-emerald-500/20">
                        <span class="text-[10px] font-black uppercase text-emerald-500">Anticipos/Pagos</span>
                        <span class="font-mono font-black text-emerald-400 text-lg"
                            id="sidebar-total-pagos">-$0.00</span>
                    </div>
                    <div
                        class="flex flex-col p-8 bg-indigo-500/10 rounded-[40px] border-2 border-indigo-500/20 text-center shadow-inner">
                        <span class="text-[10px] font-black uppercase text-indigo-300 mb-2 leading-none">Neto por
                            Liquidar</span>
                        <span class="text-5xl font-black text-emerald-400 font-mono tracking-tighter leading-none"
                            id="saldo_final">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 space-y-2">
                <button onclick="switchSection('pagos')"
                    class="w-full bg-white text-slate-900 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-black/20 hover:bg-indigo-50 transition-all">
                    <i class="fas fa-credit-card mr-2"></i> Registrar Nuevo Pago
                </button>
                <p class="text-[8px] text-center text-slate-500 uppercase font-bold mt-4">PMS Sync: <span
                        id="sync-time">---</span></p>
            </div>
        </aside>

    </div>
</div>





<!-- =========================================
         MODAL DE EDICIÓN CON CÁMARA
         ========================================= -->
<div id="modal-edit-profile" class="modal-overlay-sub" onclick="toggleEditModal(false)">
    <div class="modal-container-edit" onclick="event.stopPropagation()">

        <header class="p-8 border-b bg-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black tracking-tighter text-slate-900 uppercase">Gestión de Perfil e Identidad
                </h3>
               <!--  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sincronización con Tabla:
                    huespedes</p> -->
            </div>
            <button onclick="toggleEditModal(false)"
                class="w-10 h-10 rounded-full hover:bg-red-50 hover:text-red-500 transition-all text-slate-400 flex items-center justify-center">
                <i class="fas fa-times text-xl"></i>
            </button>
        </header>

        <div class="flex-1 overflow-y-auto custom-scroll p-10 bg-white">
            <div class="grid grid-cols-12 gap-10">

                <!-- COLUMNA IZQUIERDA: CÁMARA Y FOTO (Ajustada a col-span-3 para ser más pequeña) -->
                <div class="col-span-3 space-y-6">
                    <div class="bg-slate-900 p-4 rounded-[24px] text-center shadow-xl">
                        <h4 class="pms-label mb-3 text-indigo-400">Captura de Fotografía</h4>

                        <!-- Contenedor de Video/Imagen -->
                        <div id="camera-container" class="relative group">
                            <!-- Stream de Video -->
                            <video id="camera-stream" autoplay playsinline class="hidden"></video>
                            <!-- Vista Previa Estática -->
                            <img src="" id="edit-preview-foto" class="w-full h-full object-cover">

                            <!-- Canvas Oculto para la Captura -->
                            <canvas id="photo-canvas" class="hidden"></canvas>
                        </div>

                        <!-- Botones de Control de Cámara -->
                        <div class="grid grid-cols-1 gap-2">
                            <button id="btn-start-camera" onclick="startCamera()"
                                class="w-full py-2.5 bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-700 transition-all">
                                <i class="fas fa-video mr-1"></i> Prender Cámara
                            </button>
                            <button id="btn-take-photo" onclick="takePhoto()"
                                class="hidden w-full py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-emerald-700 transition-all">
                                <i class="fas fa-camera mr-1"></i> Tomar Foto
                            </button>
                        </div>

                        <p class="text-[7px] text-slate-500 mt-3 uppercase font-bold tracking-widest">SQL: fotografía
                            (Blob/URL)</p>
                    </div>

                    <div>
                        <label class="pms-label border-b pb-1.5 mb-2.5">Notas de Recepción</label>
                        <textarea id="edit-notas" class="pms-input h-24 resize-none text-[11px]"
                            placeholder="Ej: Cliente VIP..."></textarea>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: FORMULARIO SQL (Ampliado a col-span-9) -->
                <div class="col-span-9 grid grid-cols-2 gap-x-6 gap-y-4">

                    <div class="col-span-2 border-b pb-2 mb-2">
                        <h4 class="font-black text-xs text-indigo-600 uppercase">Información Personal</h4>
                    </div>

                    <div><label class="pms-label">Nombre</label><input type="text" id="edit-nombre" class="pms-input">
                    </div>
                    <div><label class="pms-label">Apellido</label><input type="text" id="edit-apellido"
                            class="pms-input"></div>

                    <div>
                        <label class="pms-label">Género</label>
                        <select id="edit-genero" class="pms-input">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div><label class="pms-label">Fecha Nacimiento</label><input type="date" id="edit-fecha_nacimiento"
                            class="pms-input"></div>

                    <!--  <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="edit-tipo_identificacion_id" class="pms-input">
                            <option value="1">INE / Cédula</option>
                            <option value="2">Pasaporte</option>
                        </select>
                    </div> -->

                    <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="edit-tipo_identificacion_id" class="pms-input"></select>
                    </div>

                    <div><label class="pms-label">Número de ID</label><input type="text" id="edit-numero_identificacion"
                            class="pms-input"></div>

                    <div class="col-span-2 border-b pb-2 mt-4 mb-2">
                        <h4 class="font-black text-xs text-emerald-600 uppercase">Contacto & Ubicación</h4>
                    </div>

                    <div><label class="pms-label">Email</label><input type="email" id="edit-email" class="pms-input">
                    </div>
                    <div><label class="pms-label">Teléfono</label><input type="text" id="edit-telefono"
                            class="pms-input"></div>

                    <div class="col-span-2"><label class="pms-label">Dirección</label><input type="text"
                            id="edit-direccion" class="pms-input"></div>

                    <div><label class="pms-label">Ciudad</label><input type="text" id="edit-ciudad" class="pms-input">
                    </div>
                    <div><label class="pms-label">País</label><input type="text" id="edit-pais" class="pms-input"></div>

                    <div class="col-span-2 bg-slate-50 p-4 rounded-2xl flex items-center justify-between mt-4">
                        <div>
                            <p class="text-[10px] font-black uppercase">Perfil Activo</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit-activo" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleEditModal(false)"
                class="px-8 py-4 rounded-2xl font-black text-[10px] uppercase text-slate-400 hover:text-slate-600">Cancelar</button>
            <button onclick="saveProfile()"
                class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl hover:bg-indigo-700 transition-all">Guadar</button>
        </footer>
    </div>
</div>



<!-- =========================================
         MODALES SECUNDARIOS
         ========================================= -->

<!-- MODAL: LISTADO DE ACOMPAÑANTES (NIVEL 1) -->
<div id="modal-companions-list" class="modal-overlay-sub" onclick="toggleCompanionsList(false)">
    <div class="modal-container-edit" style="max-width: 800px; height: 75vh;" onclick="event.stopPropagation()">
        <header class="px-8 py-6 border-b flex justify-between items-center bg-slate-50">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Listado de Acompañantes</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gestión Técnica de
                    registro_acompanantes</p>
            </div>
            <button onclick="toggleCompanionsList(false)"
                class="w-10 h-10 rounded-full hover:bg-slate-200 text-slate-400 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </header>

        <div class="flex-1 overflow-y-auto custom-scroll p-8 bg-white">
            <div id="companions-grid" class="grid grid-cols-1 gap-4">
                <!-- Cards de acompañantes inyectadas por JS -->
            </div>
        </div>

        <footer class="p-8 border-t bg-slate-50 flex justify-between items-center">
            <p class="text-xs font-bold text-slate-400" id="companions-counter-footer">0 Acompañantes registrados</p>
            <button onclick="openCompanionForm(-1)"
                class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-[11px] uppercase shadow-lg">
                <i class="fas fa-user-plus mr-2"></i> Nuevo Acompañante
            </button>
        </footer>
    </div>
</div>

<!-- MODAL: FORMULARIO TÉCNICO COMPLETO (NIVEL 2) -->
<div id="modal-companion-form" class="modal-overlay-sub" style="z-index: 1100;" onclick="toggleCompanionForm(false)">
    <div class="modal-container-edit" onclick="event.stopPropagation()">
        <header class="px-8 py-4 border-b bg-slate-50 flex justify-between items-center">
            <h3 class="text-lg font-black text-slate-900 uppercase" id="comp-form-title">Registro de Acompañante</h3>
            <button onclick="toggleCompanionForm(false)"
                class="w-8 h-8 rounded-full hover:bg-red-50 hover:text-red-500 text-slate-400 flex items-center justify-center"><i
                    class="fas fa-times"></i></button>
        </header>
        <div class="flex-1 overflow-y-auto custom-scroll p-8 bg-white">
            <div class="grid grid-cols-12 gap-8">

                <!-- Lado Izquierdo: Cámara e Identificación Visual -->
                <div class="col-span-4 space-y-6">
                    <div class="bg-slate-900 p-4 rounded-[24px] text-center shadow-xl border-t-4 border-indigo-600">
                        <h4 class="pms-label mb-3 text-indigo-400">Captura de Fotografía</h4>
                        <div class="camera-box-compact">
                            <video id="video-comp" autoplay playsinline class="camera-video hidden"></video>
                            <img src="" id="preview-comp" class="w-full h-full object-cover bg-slate-800">
                            <canvas id="canvas-comp" class="hidden"></canvas>
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            <button id="btn-cam-comp" onclick="startCamera('comp')"
                                class="w-full py-2.5 bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Prender
                                Cámara</button>
                            <button id="btn-snap-comp" onclick="takePhoto('comp')"
                                class="hidden w-full py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Tomar
                                Foto</button>
                        </div>
                    </div>

                    <!-- Orden de llegada (orden_llegada) -->
                    <div>
                        <label class="pms-label">Orden de Llegada</label>
                        <input type="number" id="comp-orden" class="pms-input" placeholder="Ej: 1">
                    </div>
                </div>

                <!-- Lado Derecho: Todos los campos SQL (registro_acompanantes) -->
                <div class="col-span-8 grid grid-cols-2 gap-x-6 gap-y-5">
                    <div class="col-span-2 border-b pb-2">
                        <h4 class="font-black text-xs text-indigo-600 uppercase">Datos de Identidad & SQL</h4>
                    </div>

                    <!-- Nombre y Apellido (nombre / apellido) -->
                    <div><label class="pms-label">Nombre(s)</label><input type="text" id="comp-name" class="pms-input">
                    </div>
                    <div><label class="pms-label">Apellido(s)</label><input type="text" id="comp-lastName"
                            class="pms-input"></div>

                    <!-- Identificación (tipo_identificacion / numero_identificacion) -->
                    <div>
                        <label class="pms-label">Tipo Identificación</label>
                        <select id="comp-tipo-id" class="pms-input">
                            <option value="INE">INE / Cédula</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Licencia">Licencia</option>
                            <option value="Otro">Otro Documento</option>
                            <option value="N/A">Sin Identificación</option>
                        </select>
                    </div>
                    <div><label class="pms-label">Número de ID</label><input type="text" id="comp-id" class="pms-input">
                    </div>

                    <!-- Vínculo y Menor (parentesco / es_menor) -->
                    <div><label class="pms-label">Parentesco</label><input type="text" id="comp-rel" class="pms-input"
                            placeholder="Ej: Esposa, Hijo, Socio"></div>
                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl">
                        <p class="text-[11px] font-black text-slate-900 uppercase">¿Es Menor?</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="comp-minor" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-amber-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>

                    <div class="col-span-2 border-b pb-2 pt-2">
                        <h4 class="font-black text-xs text-emerald-600 uppercase">Tiempos & Estado Estancia</h4>
                    </div>

                    <!-- Horarios (hora_entrada / hora_salida) -->
                    <div><label class="pms-label">Hora Entrada</label><input type="datetime-local" id="comp-entrada"
                            class="pms-input"></div>
                    <div><label class="pms-label">Hora Salida</label><input type="datetime-local" id="comp-salida"
                            class="pms-input"></div>

                    <!-- Estado (estado_estancia) -->
                    <div class="col-span-2">
                        <label class="pms-label">Estado de Estancia</label>
                        <select id="comp-estado" class="pms-input">
                            <option value="Hospedado">Hospedado</option>
                            <option value="Salida">Salida Realizada</option>
                            <option value="Reserva">Reserva Pendiente</option>
                        </select>
                    </div>

                    <!-- Observaciones (observaciones) -->
                    <div class="col-span-2">
                        <label class="pms-label">Observaciones Técnicas</label>
                        <textarea id="comp-obs" class="pms-input h-20 resize-none text-[11px]"
                            placeholder="Notas adicionales del acompañante..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <footer class="p-8 border-t bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleCompanionForm(false)"
                class="px-8 py-3 rounded-xl font-black text-[10px] uppercase text-slate-400 hover:text-slate-600">Descartar</button>
            <button onclick="saveCompanion()"
                class="px-10 py-3 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase shadow-lg">Guadar
                Acompañante</button>
        </footer>
    </div>
</div>