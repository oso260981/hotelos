<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->get('/sistema','Sistema::index',['filter'=>'auth']);
$routes->post('/guardar-piso','Sistema::guardarPiso',['filter'=>'auth']);
$routes->post('/guardar-habitacion','Sistema::guardarHabitacion',['filter'=>'auth']);

$routes->get('ajax/habitaciones/(:num)','Sistema::habitacionesPorPiso/$1');
$routes->post('ajax/habitacion/estado','Sistema::cambiarEstadoHabitacion');
$routes->post('ajax/habitacion/eliminar','Sistema::eliminarHabitacion');
$routes->post('ajax/habitacion/crear','Sistema::crearHabitacionAjax');

$routes->post('guardar-estado','Sistema::guardarEstado');
$routes->post('ajax/estado/toggle','Sistema::toggleEstado');
$routes->post('ajax/estado/delete','Sistema::eliminarEstado');

$routes->get('empresas/list', 'Empresas::list');
$routes->post('empresas/save', 'Empresas::save');
$routes->post('empresas/delete', 'Empresas::delete');

$routes->post('actualizar-piso','Sistema::actualizarPiso');

/* =====================================================
   RUTAS TICKET CONFIG
=====================================================*/
$routes->get('ticketconfig/list','Ticketconfig::list');
$routes->post('ticketconfig/save','Ticketconfig::save');
$routes->post('ticketconfig/delete','Ticketconfig::delete');
$routes->post('ticketconfig/activate','Ticketconfig::activate');
//$routes->get('ticketconfig/active','Ticketconfig::getActive');
$routes->post('ticketconfig/updateActive','Ticketconfig::updateActive');
$routes->post('ticketconfig/create','Ticketconfig::create');
$routes->get('ticketconfig/detail','Ticketconfig::getDetail');
$routes->get('ticketconfig/active','Ticketconfig::active');

$routes->post('usuarios/list','Usuarios::list');
$routes->post('usuarios/save','Usuarios::save');
$routes->post('usuarios/delete','Usuarios::delete');
$routes->post('usuarios/change-password','Usuarios::changePassword');
$routes->post('usuarios/toggle','Usuarios::toggle');
$routes->post('usuarios/recepcionistas', 'Usuarios::recepcionistas');


$routes->post('roles/list','Roles::list');
$routes->post('roles/save','Roles::save');
$routes->post('roles/delete','Roles::delete');
$routes->get('roles/get/(:num)','Roles::get/$1');


$routes->post('turno/abierto','TurnoOperacion::turnoAbierto');
$routes->post('turno/abrir','TurnoOperacion::abrir');
$routes->post('turno/cerrar','TurnoOperacion::cerrar');
$routes->get('turno/pdf/(:num)', 'TurnoOperacion::pdf/$1');
$routes->get('turno/excel/(:num)', 'TurnoOperacion::excel/$1');
$routes->post('turnos/list','Turnos::list');
$routes->post('turnos/save','Turnos::save');
$routes->post('turnos/delete','Turnos::delete');


$routes->group('configuraciones', function($routes){

    $routes->get('/', 'Configuraciones::index');
    $routes->post('guardar', 'Configuraciones::guardar');

});



$routes->group('', ['filter'=>'auth'], function($routes){

    $routes->get('/dashboard','Dashboard::index');
    $routes->get('/trabajo','Trabajo::index');
    $routes->get('/reportes','Reportes::index');
    $routes->get('/sistema','Sistema::index');
    $routes->get('/pasajeros','Pasajeros::index');

});


$routes->get('habitacionTipos/listTiposHabitacion', 'HabitacionTipos::listTiposHabitacion');
$routes->post('habitacionTipos/saveTipoHabitacion', 'HabitacionTipos::saveTipoHabitacion');
$routes->post('habitacionTipos/delete','HabitacionTipos::delete');

$routes->post('habitacionTipos/recalcular','HabitacionTipos::recalcularImpuestos');

$routes->get('config/impresoras', 'ConfigImpresoras::listarImpresoras');


$routes->get('perifericos/usb', 'ConfigPerifericos::detectarUsb');
$routes->get('perifericos/camaras', 'ConfigPerifericos::detectarCamaras');
$routes->get('perifericos/discos', 'ConfigPerifericos::detectarDiscos');

$routes->post('perifericos/guardar', 'ConfigPerifericos::guardarConfig');


$routes->get('trabajo', 'Trabajo::index');
$routes->get('trabajo/listar', 'Trabajo::listar');
$routes->get('trabajo/obtener/(:num)', 'Trabajo::obtener/$1');


$routes->get('pasajeros', 'Pasajeros::index');
$routes->get('pasajeros/listado_operativo', 'Pasajeros::listado_operativo');

$routes->post('pasajeros/guardar_huesped', 'Pasajeros::guardar_huesped');
$routes->get('pasajeros/obtener_huesped/(:num)', 'Pasajeros::obtener_huesped/$1');

$routes->post('pasajeros/guardar_acompanante', 'Pasajeros::guardar_acompanante');

$routes->get('pasajeros/catalogo','Pasajeros::catalogo');
$routes->get('pasajeros/catalogo_listado','Pasajeros::catalogo_listado');
$routes->post('pasajeros/catalogo_guardar','Pasajeros::catalogo_guardar');
$routes->post('pasajeros/catalogo_baja/(:num)','Pasajeros::catalogo_baja/$1');

$routes->get('pasajeros/catalogo_tipos_identificacion','Pasajeros::catalogo_tipos_identificacion');


$routes->post('pasajeros/guardar_foto','Pasajeros::guardar_foto');
$routes->post('pasajeros/ocr_documento', 'Pasajeros::ocr_documento');



$routes->get('pasajeros/buscar', 'Pasajeros::buscar');
$routes->get('pasajeros/buscar_cliente', 'Pasajeros::buscar_cliente');

$routes->post('pasajeros/guardar_cliente', 'Pasajeros::guardar_cliente');


// CRUD REST pisos
$routes->resource('pisos');

// ⭐ ruta PMS modal
$routes->get('catalogos/listar_pisos','Pisos::listar_pisos');
$routes->get('catalogos/listar_tipos_habitacion','HabitacionTipos::listar_tipos');


$routes->get('habitaciones/listado_visual','Habitaciones::listado_visual');


// =====================================================
// RESERVACIONES / REGISTROS HOTEL
// =====================================================

// Guardar o actualizar registro (check-in)
$routes->post('reservacion/guardar', 'Reservacion::guardarRegistro');

// Registrar salida de habitación (checkout → cambia status a SUCIA)
$routes->post('reservacion/checkout', 'Reservacion::checkout');

// Registrar pago adicional posterior al check-in
$routes->post('reservacion/pago-extra', 'Reservacion::pagoExtra');

// Actualizar estado de habitación (S, X, M, P)
$routes->post('reservacion/actualizar-estado-habitacion', 'Reservacion::actualizarEstadoHabitacion');

// Agregar huésped acompañante al registro activo
$routes->post('reservacion/acompanante', 'Reservacion::agregarAcompanante');

// Obtener registro activo por habitación (uso AJAX tabla trabajo)
$routes->get('reservacion/registro-activo/(:num)', 'Reservacion::registroActivo/$1');

// Obtener detalle completo del registro (modal edición)
$routes->get('reservacion/detalle/(:num)', 'Reservacion::detalle/$1');

// Marcar huésped en lista negra
$routes->post('reservacion/lista-negra', 'Reservacion::marcarListaNegra');

// Marcar huésped como cliente frecuente / VIP
$routes->post('reservacion/vip', 'Reservacion::marcarVip');

// Emitir ticket térmico del registro
$routes->post('reservacion/ticket', 'Reservacion::emitirTicket');

// Cambiar habitación (traslado interno de huésped)
$routes->post('reservacion/cambiar-habitacion', 'Reservacion::cambiarHabitacion');



// =====================================================
// HABITACIONES PMS
// =====================================================
$routes->post('habitaciones/marcar-limpia', 'Habitaciones::marcar_limpia');
$routes->post('habitaciones/actualizar-estado', 'Habitaciones::actualizar_estado');

$routes->get('reservacion/habitaciones-activas', 'Reservacion::getHabitacionesActivas');
$routes->post('reservacion/registrar-salida', 'Reservacion::registrarSalida');
$routes->post('reservacion/registrar-entrada', 'Reservacion::registrarEntrada');
$routes->get('reservacion/get-salidas/(:num)', 'Reservacion::getSalidas/$1');
$routes->get('catalogos/tipo-estadia', 'Reservacion::listar_estadias');

$routes->get('catalogos/formas-pago', 'Reservacion::listar_formas_pago');

$routes->post('reservacion/guardar-acompanantes', 'Reservacion::guardarAcompanantes');

$routes->post('reservacion/guardar-pago', 'Reservacion::guardarPago');
$routes->post('reservacion/guardar-cargo', 'Reservacion::guardarCargo');
$routes->post('reservacion/actualizar-campo', 'Reservacion::actualizarCampoRegistro');

// DATOS FISCALES
$routes->get('reservacion/obtener-fiscal/(:num)', 'Reservacion::obtenerFiscal/$1');
$routes->post('reservacion/guardar-fiscal', 'Reservacion::guardarFiscal');
$routes->get('reservacion/obtenerImpuestos', 'Reservacion::obtenerImpuestos');

$routes->post('reservacion/guardar-vehiculo', 'Reservacion::guardarVehiculo');

$routes->post('media/subir-foto', 'Reservacion::subirFoto');

$routes->get('reservacion/ticket/(:num)','Reservacion::ticket/$1');

$routes->get('pasajeros/acompanantes/(:num)', 'Pasajeros::acompanantes/$1');
$routes->post('pasajeros/guardar_acompanante_update', 'Pasajeros::guardarAcompanante');
$routes->delete('pasajeros/eliminar_acompanante/(:num)', 'Pasajeros::eliminarAcompanante/$1');


$routes->get('registropagos/registros/(:num)/pagos', 'RegistroPagos::porRegistro/$1');
$routes->post('registropagos/pagos', 'RegistroPagos::crear');
$routes->get('registropagos/registros/(:num)/pagos/resumen', 'RegistroPagos::resumen/$1');
$routes->post('registropagos/guardar', 'RegistroPagos::guardarPago');
$routes->post('registropagos/cancelar', 'RegistroPagos::cancelar');




$routes->post('registro-cargos/guardar', 'RegistroCargo::guardar');
$routes->get('registro-cargos/listar/(:num)', 'RegistroCargo::listar/$1');
$routes->get('registro-cargos/total/(:num)', 'RegistroCargo::total/$1');
$routes->post('registro-cargos/cancelar', 'RegistroCargo::cancelar');



$routes->get('roomservice/servicios', 'RoomService::servicios');
$routes->get('roomservice/buscar', 'RoomService::buscar');

$routes->group('roomservice', function($routes) {
    $routes->get('/', 'RoomService::index');
    $routes->get('activos', 'RoomService::activos');
    $routes->get('front', 'RoomService::front');
    $routes->get('(:num)', 'RoomService::show/$1');
    $routes->post('/', 'RoomService::create');
    $routes->put('(:num)', 'RoomService::update/$1');
    $routes->post('finalizar-pedido', 'RoomService::finalizarPedido');
    $routes->delete('(:num)', 'RoomService::delete/$1');
});


// =========================
// 📊 REPORTES
// =========================
$routes->get('reportes', 'Reportes::index');
$routes->post('reportes/guardar', 'Reportes::guardar');


$routes->post('reservacion/cambiar-habitacion', 'Reservacion::cambiarHabitacion');


$routes->get('reportes/registros', 'Reportes::getRegistros');
$routes->get('reportes/trabajo', 'Reportes::getReporteTrabajo');




$routes->get('reportes/turnos','Reportes::getReporteTurnos');
$routes->get('reportes/huespedes', 'Reportes::datosHuespedes');
$routes->get('reportes/reporteDia', 'Reportes::reporteDia');

$routes->get('reportes/reporteFiscal', 'Reportes::reporteFiscal');
$routes->post('reportes/guardarFiscal', 'Reportes::guardarFiscal');

$routes->post('reportes/generar', 'Reportes::generar');



$routes->get('reportes/lista', 'Reportes::lista');
$routes->get('reportes/detalle/(:num)', 'Reportes::detalle/$1');
$routes->delete('reportes/eliminar/(:num)', 'Reportes::eliminar/$1');
$routes->post('reportes/actualizar/(:num)', 'Reportes::actualizar/$1');


$routes->post('ocr/procesar', 'Ocr::procesar');

$routes->post('reservacion/cerrar-folio', 'Reservacion::cerrarFolio');
$routes->post('reservacion/modificar-estadia', 'Reservacion::modificarEstadia');

$routes->post('reservacion/registrar_vehiculos', 'Reservacion::registrar_vehiculos');

$routes->get('reservacion/lista_vehiculos/(:num)', 'Reservacion::lista_vehiculos/$1');
$routes->post('reservacion/eliminar_vehiculo/(:num)', 'Reservacion::eliminar_vehiculo/$1');


$routes->post('reservacion/guardar-perfil', 'Reservacion::guardarPerfil');
$routes->post('reservacion/eliminar-perfil/(:num)', 'Reservacion::eliminarPerfil/$1');

$routes->get('reservacion/perfil/(:num)', 'Reservacion::obtenerPerfil/$1');

$routes->get('factura/pdf/(:num)', 'Facturas::pdf/$1');
$routes->get('factura/test', 'Facturas::test');


$routes->post('reservacion/recalcular-totales/(:num)', 'Reservacion::recalcularTotalesRegistro/$1');



$routes->post('registro/cancelar', 'Reservacion::cancelar');
$routes->post('registro/cancelar-inteligente', 'Reservacion::cancelarInteligente');


$routes->post('reservacion/validar-duplicado', 'Reservacion::validarDuplicado');

$routes->post('reservacion/cerrar-y-limpiar', 'Reservacion::cerrarYLimpia');
$routes->post('reservacion/marcar-limpia', 'Reservacion::marcarLimpia');
$routes->post('reservacion/cambiar-estado', 'Reservacion::cambiarEstado');  

$routes->get('reservacion/obtenerImpuestos', 'Reservacion::obtenerImpuestos');

$routes->get('reservacion/habitaciones', 'Reservacion::getHabitaciones');

// app/Config/Routes.php
$routes->get('reservacion/estados-habitacion', 'Reservacion::estadosHabitacion');

// app/Config/Routes.php
$routes->post('reservacion/checkout', 'Reservacion::checkout');
$routes->post('reservacion/actualizar-campo', 'Reservacion::actualizarCampoRegistro');

