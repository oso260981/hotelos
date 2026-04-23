<?php

namespace App\Controllers;

use App\Models\RegistroModel;

class Reservacion extends BaseController
{
    protected $registroModel;

    public function __construct()
    {
        $this->registroModel = new RegistroModel();
    }

    /* ======================================================
   LISTAR TIPOS DE ESTADIA
====================================================== */
public function listar_estadias()
{
    $db = \Config\Database::connect();

    $data = $db->table('tipo_estadia')
        ->select('id, CONCAT(codigo,"-",nombre) as label')
        ->orderBy('id','ASC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON($data);
}

/* ======================================================
   LISTAR FORMAS DE PAGO
====================================================== */
public function listar_formas_pago()
{
    //$tipo = $this->request->getGet('tipo'); // tarjeta o efectivo

    $db = \Config\Database::connect();

    $builder = $db->table('formas_pago')
        ->select('id, CONCAT(codigo,"-",descripcion) as label')
        ->orderBy('id','ASC');


    //$tipo = mb_strtolower($tipo, 'UTF-8');    

    // ⭐ variante PMS
    //if($tipo === 'tarjeta'){
    //    $builder->where('id >=', 3)
    //            ->where('id <=', 6);
    //}else{
    //     $builder->where('id >=', 1)
    //            ->where('id <=', 2);
    //}

    $data = $builder->get()->getResultArray();

    return $this->response->setJSON($data);
}





    /* =====================================================
       GUARDAR REGISTRO (CHECK-IN NUEVO / UPDATE)
       ===================================================== */
    public function guardarRegistro()
{
    try{
        date_default_timezone_set('America/Mexico_City');
        $json = $this->request->getJSON(true);

        $id = $json['id'] ?? null;


      
        $horaSalida = ($json['hora_salida'] ?? date('Y-m-d')) . ' 13:00:00';
       


        $data = [

            // 🔑 RELACIONES
            'habitacion_id'    => $json['habitacion_id'] ?? null,
            'huesped_id'       => $json['huesped_id'],
            'tipo_estadia_id'  => $json['tipo_estadia_id'] ?? null,
            'turno_id'         => $this->obtenerTurnoActual(),
            'forma_pago_id'    => $json['forma_pago_id'] ?? null,
            'usuario_id'       => session()->get('user_id'),

            // 🏨 CICLO ESTANCIA
            'hora_entrada'     => $json['hora_entrada'],
            'hora_salida'      => $horaSalida,
            'fecha_estadia'    => date('Y-m-d'),
            'noches'           =>  $json['noches'],
            'estado_registro'  => 'CHECKIN',

            // 👥 OCUPACIÓN
            'num_personas_ext'     => $json['num_personas_ext'] ?? 0,
            'adultos'              => $json['adultos'] ?? 0,
            'ninos'                => $json['ninos'] ?? $json['niños'] ?? 0,
            

            // 💰 FINANCIERO
            'precio'           => $json['precio'] ?? 0,
            'precio_base'      => $json['precio_base'] ?? 0,
            'pago_adicional'   => $json['pago_adicional'] ?? 0,
            'iva'              => $json['iva'] ?? 0,
            'ish'              => $json['ish'] ?? 0,
            'total'            => $json['total'] ?? null,
            'observaciones'    => $json['observaciones'] ?? null,

        ];

        // ======================================
        // NUEVO CHECK-IN
        // ======================================
        if(!$id){

            $data['hora_entrada'] = date('Y-m-d H:i:s');

            $this->registroModel->insert($data);
            $id = $this->registroModel->insertID();

        }else{

            // ======================================
            // UPDATE REGISTRO
            // ======================================
            $this->registroModel->update($id, $data);
        }

        // ======================================
        // GUARDAR PRECIO SI VIENE
        // ======================================
        if(!empty($json['total'])){

            $total = floatval($json['total']);
            $ivaRate = 0.16;
            $ishRate = 0.035;

            $base = $total / (1 + $ivaRate + $ishRate);
            $iva  = round($base * $ivaRate, 2);
            $ish  = round($base * $ishRate, 2);
            $precioBase = round($base, 2);
            $precio = $json['precio'] ?? $precioBase;

            $this->registroModel->update($id, [
                'precio'       => $precio,
               // 'precio_base'  => $precioBase,
                'iva'          => $iva,
                'ish'          => $ish,
                'total'        => $total
            ]);
        }

        return $this->response->setJSON([
            "ok" => true,
            "success" => true,
            "registro_id" => $id,
            "datos" => $data
        ]);

    }catch(\Throwable $e){

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}

public function guardarAcompanantes()
{
    try {
        $json = $this->request->getJSON(true);

        $registroId = $json['registro_id'] ?? null;
        $lista = $json['acompanantes'] ?? [];

        if (!$registroId) {
            throw new \Exception("ID de registro no proporcionado");
        }

        $db = \Config\Database::connect();

        // Limpiar previos para evitar duplicados en actualizaciones
        $db->table('registro_acompanantes')
           ->where('registro_id', $registroId)
           ->delete();

        foreach ($lista as $row) {
            $db->table('registro_acompanantes')->insert([
                'registro_id'   => $registroId,
                'nombre'        => $row['nombre'] ?? 'S/N',
                'apellido'      => $row['apellido'] ?? '',
                'parentesco'    => $row['parentesco'] ?? null,
                'es_menor'      => $row['es_menor'] ?? 0,
                'fotografia'    => $row['fotografia'] ?? null,
                'identificacion' => $row['identificacion'] ?? null,
                'firma_path'    => $row['firma_path'] ?? null,
                'es_ext'        => $row['es_extra'] ?? 0,
                'Responsable_menor' => $row['Responsable_menor'] ?? null,
                'hora_entrada'  => date('Y-m-d H:i:s'),
                'estado_estancia' => 'ACTIVO',
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(["ok" => true, "count" => count($lista)]);

    } catch (\Throwable $e) {
        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en BD: " . $e->getMessage()
        ]);
    }
}


/* =====================================================
   GUARDAR PAGO
===================================================== */
public function guardarPago()
{
    $db = \Config\Database::connect();

    try {

        $json = $this->request->getJSON(true);

        if (empty($json['registro_id'])) {
            throw new \Exception("registro_id requerido");
        }

        $monto = (float)$json['monto'];

        if ($monto <= 0) {
            throw new \Exception("Monto inválido");
        }

        $registro_id = $json['registro_id'];

        // =========================
        // 🔥 INICIAR TRANSACCIÓN
        // =========================
       // $db->transStart();

        // 🔒 BLOQUEAR REGISTRO (CLAVE)
        $db->query("SELECT id FROM registros WHERE id = ? FOR UPDATE", [$registro_id]);


           

        // =========================
        // 📊 CALCULAR SALDO DENTRO DE TX
        // =========================
        $totalCargos = (float)$db->query("
            SELECT IFNULL(SUM(total),0) total 
            FROM registro_cargos 
            WHERE registro_id = ?
        ", [$registro_id])->getRow()->total;

        $totalPagos = (float)$db->query("
            SELECT IFNULL(SUM(monto),0) total 
            FROM registro_pagos 
            WHERE registro_id = ? 
            AND tipo_movimiento = 'PAGO'
            AND estado = 'APLICADO'
        ", [$registro_id])->getRow()->total;

        $saldo = round($totalCargos - $totalPagos, 2);

        if ($monto > $saldo) {
            throw new \Exception("El pago excede el saldo pendiente");
        }

        // =========================
        // 💾 INSERTAR PAGO
        // =========================
        $db->table('registro_pagos')->insert([

            'registro_id'     => $registro_id,
            'forma_pago_id'   => $json['forma_pago_id'] ?? null,
            'monto'           => $monto,
            'concepto'        => $json['concepto'] ?? 'Pago hospedaje',
            'tipo'            => 'Hospedaje',
            'tipo_movimiento' => 'PAGO',
            'hora_pago'       => date('Y-m-d H:i:s'),
            'estado'          => 'APLICADO',
            'sistema'         => 'PMS',
            'qty'             => 1,
            'referencia_pago' => $json['referencia_pago'] ?? null,
            'banco'           => $json['banco'] ?? null,
            'usuario_id'      => session()->get('user_id') ?? 1,
            'observaciones'   => $json['observaciones'] ?? null,
            'created_at'      => date('Y-m-d H:i:s')

        ]);

     

        // =========================
        // 📌 MARCAR PAGADO
        // =========================
       /*  if (round($saldo - $monto, 2) <= 0) {
            $db->table('registros')
                ->where('id', $registro_id)
                ->update(['estado_pago' => 'PAGADO']);
        } */

       // $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception("Error en transacción");
        }

        $this->recalcularTotalesRegistro($registro_id);

        return $this->response->setJSON([
            "ok" => true,
            "saldo_restante" => round($saldo - $monto, 2)
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }
}

public function guardarCargo()
{
    try {

        $json = $this->request->getJSON(true);
        $db = \Config\Database::connect();

        // =========================
        // 🔒 VALIDACIONES
        // =========================
        if (empty($json['registro_id'])) {
            throw new \Exception("registro_id requerido");
        }

        $registro_id = (int)$json['registro_id'];
        $cantidad = (int)($json['cantidad'] ?? 1);
        $precio_unitario = (float)($json['precio_unitario'] ?? 0);

        if ($cantidad <= 0) {
            throw new \Exception("Cantidad inválida");
        }

        if ($precio_unitario <= 0) {
            throw new \Exception("Precio unitario inválido");
        }

        // =========================
        // 🧠 BASE
        // =========================
        $subtotal = round($cantidad * $precio_unitario, 2);

        // =========================
        // ⚙️ IMPUESTOS
        // =========================
        $IVA_RATE = 0.16;
        $ISH_RATE = 0.03;

        $tipo = $json['tipo'] ?? 'Extra';

        $aplicaIVA = 1;
        $aplicaISH = in_array($tipo, ['Hospedaje', 'Persona Extra']) ? 1 : 0;

        $iva = $aplicaIVA ? round($subtotal * $IVA_RATE, 2) : 0;
        $ish = $aplicaISH ? round($subtotal * $ISH_RATE, 2) : 0;

        // =========================
        // 💰 TOTAL
        // =========================
        $total = round($subtotal + $iva + $ish, 2);

        // =========================
        // 🚀 TRANSACCIÓN
        // =========================
        $db->transStart();

        $insert = $db->table('registro_cargos')->insert([
            'registro_id'     => $registro_id,
            'concepto'        => $json['concepto'] ?? 'Cargo',
            'tipo'            => $tipo,

            'cantidad'        => $cantidad,
            'precio_unitario' => $precio_unitario,

            'subtotal'        => $subtotal,
            'iva'             => $iva,
            'ish'             => $ish,
            'total'           => $total,

            'aplica_iva'      => $aplicaIVA,
            'aplica_ish'      => $aplicaISH,

            'estado'          => 'ACTIVO',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        if (!$insert) {
            throw new \Exception("Error insertando cargo");
        }

        $db->transComplete();

        return $this->response->setJSON([
            "ok" => true,
            "data" => [
                "subtotal" => $subtotal,
                "iva"      => $iva,
                "ish"      => $ish,
                "total"    => $total
            ]
        ]);

    } catch (\Throwable $e) {

        log_message('error', 'ERROR guardarCargo: ' . $e->getMessage());

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }

    $this->recalcularTotalesRegistro($registro_id);
}

/* =====================================================
   RESUMEN ADMINISTRATIVO REGISTRO
===================================================== */
public function resumen($id)
{
    $db = \Config\Database::connect();

    $registro = $db->table('vw_trabajo')
    ->where('id_reservacion', $id)
    ->get()
    ->getRowArray();


    return $this->response->setJSON($registro);
}

/* =====================================================
   GUARDAR VEHICULO ESTACIONAMIENTO
===================================================== */
public function guardarVehiculo()
{
    try{

        $json = $this->request->getJSON(true);

        $db = \Config\Database::connect();

        $db->table('registro_estacionamiento')->insert([

            'registro_id'   => $json['registro_id'],
          //  'numero_cajon'  => $json['numero_cajon'] ?? null,
            'placa'         => $json['placa'] ?? null,
          //  'modelo'        => $json['modelo'] ?? null,
          //  'color'         => $json['color'] ?? null,
          //  'tipo_vehiculo' => $json['tipo_vehiculo'] ?? 'AUTO',
          //  'cargo'         => $json['cargo'] ?? 0,

            'hora_entrada'  => date('Y-m-d H:i:s'),
            'estado'        => 'ACTIVO',
            'created_at'    => date('Y-m-d H:i:s')

        ]);

        return $this->response->setJSON([
            "ok" => true
        ]);

    }catch(\Throwable $e){

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);

    }
}



public function subirFoto()
{
    try {

        $json = $this->request->getJSON(true);

        if (!$json || !isset($json['foto'])) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "No se recibió imagen"
            ]);
        }

        $base64 = $json['foto'];

        // 🔥 detectar tipo de imagen (png/jpg)
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $extension = strtolower($type[1]); // jpg, png, jpeg

            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                return $this->response->setJSON([
                    "ok" => false,
                    "msg" => "Formato no permitido"
                ]);
            }

            // limpiar base64
            $base64 = substr($base64, strpos($base64, ',') + 1);

        } else {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Formato base64 inválido"
            ]);
        }

        $base64 = str_replace(' ', '+', $base64);

        $data = base64_decode($base64);

        if ($data === false) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Error al decodificar imagen"
            ]);
        }

        // 🔥 RUTA
        $path = FCPATH . 'uploads/fotos/';

        // 🔥 crear carpeta si no existe
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // 🔥 nombre único
        $nombre = 'guest_' . date('Ymd_His') . '_' . rand(1000,9999) . '.' . $extension;

        $fullPath = $path . $nombre;

        // 🔥 guardar archivo
        if (!file_put_contents($fullPath, $data)) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "No se pudo guardar el archivo",
                "path" => $fullPath
            ]);
        }

        return $this->response->setJSON([
            "ok"   => true,
            "file" => $nombre,
            "foto" => $nombre, // Para compatibilidad con ambos
            "url"  => base_url('uploads/fotos/' . $nombre)
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage(),
            "line" => $e->getLine()
        ]);
    }
}

public function ticket($id)
{
    $db = \Config\Database::connect();

    $r = $db->table('vw_trabajo')
        ->where('id_reservacion',$id)
        ->get()
        ->getRow();

    $pagado = $db->table('registro_pagos')
        ->selectSum('monto')
        ->where('registro_id',$id)
        ->get()->getRow()->monto ?? 0;

    $data = [
        "folio" => "ADM-".str_pad($r->id_reservacion,6,"0",STR_PAD_LEFT),
        "habitacion" => str_pad($r->numero,3,"0",STR_PAD_LEFT),
        "huesped" => $r->Nombre_Huesped,
        "entrada" => date("d/m/Y",strtotime($r->hora_entrada)),
        "salida" => date("d/m/Y",strtotime($r->hora_salida)),
        "total" => $r->total,
        "pagado" => $pagado,
        "saldo" => $r->total - $pagado
    ];

    print_r($data );

   // return view('trabajo/ticket_checkin',$data);
}


/* public function cambiarHabitacion()
{
    try {

        $json = $this->request->getJSON(true);

        if (empty($json['registro_id']) || empty($json['habitacion_id'])) {
            throw new \Exception("Datos incompletos");
        }


        $registro_id=$json['registro_id'];

        $db = \Config\Database::connect();

        // =========================
        // 🔎 REGISTRO ACTUAL
        // =========================
        $registro = $db->table('registros')
            ->where('id', $json['registro_id'])
            ->get()
            ->getRowArray();

        $habActual = $db->table('habitaciones')
            ->where('id', $registro['habitacion_id'])
            ->get()
            ->getRowArray();

        // =========================
        // 🔎 HABITACIÓN NUEVA
        // =========================
        $habNueva = $db->table('habitaciones')
            ->where('id', $json['habitacion_id']) // viene 101
            ->get()
            ->getRowArray();

        if (!$habNueva) {
            throw new \Exception("Habitación nueva no encontrada");
        }

        // =========================
        // 💰 OBTENER PRECIOS
        // =========================
        $tipoActual = $db->table('habitaciones_tipos')
            ->where('id', $habActual['tipo_habitacion_id'])
            ->get()
            ->getRowArray();

        $tipoNueva = $db->table('habitaciones_tipos')
            ->where('id', $habNueva['tipo_habitacion_id'])
            ->get()
            ->getRowArray();

        $precioActual = (float)$tipoActual['precio_base'];
        $precioNuevo  = (float)$tipoNueva['precio_base'];

        // =========================
        // 🧠 VALIDACIÓN
        // =========================
        $diferencia = $precioNuevo - $precioActual;

        if ($diferencia > 0 && !($json['confirmar_upgrade'] ?? false)) {

            // 🔥 MÁS CARA → cobrar diferencia
            return $this->response->setJSON([
                "ok" => false,
                "requiere_confirmacion" => true,
                "tipo" => "UPGRADE",
                "diferencia" => $diferencia,
                "msg" => "La nueva habitación es más cara. Se requiere confirmar cargo adicional."
            ]);
        }

        // =========================
        // 🚀 SI TODO OK → MOVER
        // =========================
        $db->transStart();

        // liberar anterior
        $db->table('habitaciones')
            ->where('id', $habActual['id'])
            ->update(['estado_id' => 1]);

        // ocupar nueva
        $db->table('habitaciones')
            ->where('id', $habNueva['id'])
            ->update(['estado_id' => 1]);

        // actualizar registro
        $db->table('registros')
            ->where('id', $json['registro_id'])
            ->update([
                'habitacion_id' => $habNueva['id']
            ]);

        // log
        $db->table('registro_movimientos')->insert([
            'registro_id' => $json['registro_id'],
            'habitacion_anterior' => $habActual['id'],
            'habitacion_nueva' => $habNueva['id'],
            'motivo' => $json['motivo'] ?? null,
            'fecha' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            "ok" => true
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }

    $this->recalcularTotalesRegistro($registro_id);
} */

public function cambiarHabitacion()
{
    try {
        $json = $this->request->getJSON(true);
        $db = \Config\Database::connect();

        if (empty($json['registro_id']) || empty($json['habitacion_id'])) {
            throw new \Exception("Datos incompletos");
        }

        // 1. Obtener Registro Actual (el que se mueve)
        $registro = $db->table('registros')->where('id', $json['registro_id'])->get()->getRowArray();
        if (!$registro) throw new \Exception("Registro no encontrado");

        // 2. Obtener Info Habitaciones
        $habActual = $db->table('habitaciones')->where('id', $registro['habitacion_id'])->get()->getRowArray();
        $habNueva  = $db->table('habitaciones')->where('id', $json['habitacion_id'])->get()->getRowArray();
        if (!$habNueva) throw new \Exception("Habitación destino no encontrada");

        // 3. Validar Upgrade de Precio
        $tipoActual = $db->table('habitaciones_tipos')->where('id', $habActual['tipo_habitacion_id'])->get()->getRowArray();
        $tipoNueva  = $db->table('habitaciones_tipos')->where('id', $habNueva['tipo_habitacion_id'])->get()->getRowArray();

        $diferencia = (float)$tipoNueva['precio_base'] - (float)$tipoActual['precio_base'];

        if ($diferencia > 0 && !($json['confirmar_upgrade'] ?? false)) {
            return $this->response->setJSON([
                "ok" => false,
                "requiere_confirmacion" => true,
                "tipo" => "UPGRADE",
                "diferencia" => $diferencia
            ]);
        }

        // 4. TRANSACCIÓN
        $db->transStart();

        // A. Buscar el registro 'DISPONIBLE' de la Habitación Nueva
        $regDisponibleNueva = $db->table('registros')
            ->where('habitacion_id', $habNueva['id'])
            ->where('estado_registro', 'DISPONIBLE')
            ->orderBy('id', 'DESC')->get()->getRowArray();

        if (!$regDisponibleNueva) throw new \Exception("No hay un folio DISPONIBLE en la habitación destino");

        // B. INTERCAMBIO DE HABITACIONES
        // El registro activo pasa a la habitación nueva
        $updatePayload = [
            'habitacion_id' => $habNueva['id'],
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        // Si se confirmó upgrade, actualizamos el precio base y el total
        if ($json['confirmar_upgrade'] ?? false) {
            $noches = (int)($registro['noches'] ?? 1);
            if ($noches <= 0) $noches = 1;
            
            $ivaRate = 0.16;
            $ishRate = 0.035;
            
            // Calculamos el nuevo total si aplicamos el precio de la nueva habitación
            $nuevoPrecioBase = (float)$tipoNueva['precio_base'];
            $nuevoSubtotalTotal = $nuevoPrecioBase * $noches;
            $nuevoIva = round($nuevoSubtotalTotal * $ivaRate, 2);
            $nuevoIsh = round($nuevoSubtotalTotal * $ishRate, 2);
            $nuevoTotal = $nuevoSubtotalTotal + $nuevoIva + $nuevoIsh;

            $updatePayload['precio_base'] = $nuevoPrecioBase;
            $updatePayload['precio']      = $nuevoSubtotalTotal;
            $updatePayload['iva']         = $nuevoIva;
            $updatePayload['ish']         = $nuevoIsh;
            $updatePayload['total']       = $nuevoTotal;
        }

        $db->table('registros')->where('id', $registro['id'])->update($updatePayload);

        // El registro disponible pasa a la habitación vieja (Reciclaje)
        // 🔥 Importante: Este nuevo folio en la habitación vieja debe nacer como SUCIO (1)
        $db->table('registros')->where('id', $regDisponibleNueva['id'])->update([
            'habitacion_id' => $habActual['id'],
            'estado_id'     => 1, // 'S' - SUCIA
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        // C. ACTUALIZAR ESTADOS FÍSICOS (Redundancia en tabla habitaciones)
        // Habitación vieja queda SUCIA (1)
        $db->table('habitaciones')->where('id', $habActual['id'])->update(['estado_id' => 1]);
        // Habitación nueva queda OCUPADA/LIMPIA (2)
        $db->table('habitaciones')->where('id', $habNueva['id'])->update(['estado_id' => 2]);

        // D. LOG DE MOVIMIENTO
        $db->table('registro_movimientos')->insert([
            'registro_id'         => $registro['id'],
            'habitacion_anterior' => $habActual['id'],
            'habitacion_nueva'    => $habNueva['id'],
            'motivo'              => $json['motivo'] ?? 'REASIGNACION',
            'fecha'               => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) throw new \Exception("Error al procesar el cambio en base de datos");

        // 5. RECALCULAR SALDOS (Para asegurar que todo esté al día)
        $this->recalcularTotalesRegistro($registro['id']);

        return $this->response->setJSON(["ok" => true, "msg" => "Cambio realizado exitosamente"]);

    } catch (\Throwable $e) {
        return $this->response->setJSON(["ok" => false, "msg" => $e->getMessage()]);
    }
}



private function obtenerTurnoActual()
{
    $db = \Config\Database::connect();

    $horaActual = date('H:i:s');

    $turnos = $db->table('turnos')
        ->where('activo', 1)
        ->get()
        ->getResult();

    foreach ($turnos as $turno) {

        $inicio = $turno->hora_inicio;
        $fin    = $turno->hora_fin;

        // Caso normal (no cruza medianoche)
        if ($inicio < $fin) {
            if ($horaActual >= $inicio && $horaActual < $fin) {
                return $turno->id;
            }
        } 
        // Caso turno nocturno (cruza medianoche)
        else {
            if ($horaActual >= $inicio || $horaActual < $fin) {
                return $turno->id;
            }
        }
    }

    return null; // fallback
}

public function cerrarFolio()
{
    try {

        $json = $this->request->getJSON(true);
        $registro_id = (int)$json['registro_id'];

        $db = \Config\Database::connect();

        // 🔥 validar saldo real
        $totalCargos = (float)$db->query("
            SELECT IFNULL(SUM(total),0) total 
            FROM registro_cargos 
            WHERE registro_id = ? AND estado = 'ACTIVO'
        ", [$registro_id])->getRow()->total;

        $totalPagos = (float)$db->query("
            SELECT IFNULL(SUM(monto),0) total 
            FROM registro_pagos 
            WHERE registro_id = ? 
            AND tipo_movimiento = 'PAGO'
            AND estado = 'APLICADO'
        ", [$registro_id])->getRow()->total;

        $saldo = round($totalCargos - $totalPagos, 2);

        if ($saldo > 0.01) {
            throw new \Exception("No se puede cerrar, saldo pendiente");
        }

        // 🔥 cerrar folio
        $db->table('registros')
            ->where('id', $registro_id)
            ->update([
                'estado_registro' => 'CHECKOUT',
               // 'estado_servicio' => 'PAGADO',
                'hora_salida_real' => date('Y-m-d H:i:s')
            ]);


         $this->recalcularTotalesRegistro($registro_id);   

        return $this->response->setJSON([
            "ok" => true
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }

    
}


public function modificarEstadia()
{
    try {

        $input = $this->request->getJSON(true);

        $registro_id   = $input['registro_id'] ?? null;
        $fecha_salida  = $input['fecha_salida'] ?? null;
        $fecha_entrada = $input['fecha_entrada'] ?? null;

        if (!$registro_id || !$fecha_salida || !$fecha_entrada) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Datos incompletos"
            ]);
        }

        $db = \Config\Database::connect();

        // =========================
        // 📅 NOCHES
        // =========================
        $entrada = new \DateTime($fecha_entrada);
        $salida  = new \DateTime($fecha_salida);

        $noches = $entrada->diff($salida)->days;
        if ($noches <= 0) $noches = 1;

        // =========================
        // 🔍 REGISTRO
        // =========================
        $registro = $db->table('registros')
            ->where('id', $registro_id)
            ->get()
            ->getRow();

        if (!$registro) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Registro no encontrado"
            ]);
        }

        // =========================
        // 💰 TARIFA BASE
        // =========================
        $tarifa = $registro->tarifa_base ?? $registro->precio_base ?? 0;

        $IVA_RATE = 0.16;
        $ISH_RATE = 0.03;

        // =========================
        // 🔄 UPDATE REGISTRO
        // =========================
        $subtotal = $noches * $tarifa;
        $iva = $subtotal * $IVA_RATE;
        $ish = $subtotal * $ISH_RATE;
        $total = $subtotal + $iva + $ish;

        $db->table('registros')
            ->where('id', $registro_id)
            ->update([
                'hora_salida' => $fecha_salida,
                'noches'      => $noches,
                'precio' => $subtotal,
                'iva'         => $iva,
                'ish'         => $ish,
                'total'       => $total,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

        // =====================================================
        // 🔥 ACTUALIZAR CARGOS (FORMA CORRECTA)
        // =====================================================
        $cargos = $db->table('registro_cargos')
            ->where('registro_id', $registro_id)
            ->whereIn('tipo', ['Hospedaje', 'Persona Extra'])
            ->where('estado', 'ACTIVO')
            ->get()
            ->getResult();

        foreach ($cargos as $cargo) {

            $precio = $cargo->precio_unitario;

            // 🔥 clave: cantidad = noches
            $cantidad = $noches;

            $sub = $cantidad * $precio;
            $iva_c = $sub * ($cargo->aplica_iva ? $IVA_RATE : 0);
            $ish_c = $sub * ($cargo->aplica_ish ? $ISH_RATE : 0);
            $tot = $sub + $iva_c + $ish_c;

            $db->table('registro_cargos')
                ->where('id', $cargo->id)
                ->update([
                    'cantidad'   => $cantidad,
                    'subtotal'   => $sub,
                    'iva'        => $iva_c,
                    'ish'        => $ish_c,
                    'total'      => $tot,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Estadía actualizada correctamente",
            "data" => [
                "noches" => $noches,
                "total"  => $total
            ]
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en servidor",
            "error" => $e->getMessage()
        ]);
    }

    $this->recalcularTotalesRegistro($registro_id);
}


public function registrar_vehiculos()
{
    try {

        $input = $this->request->getJSON(true);

        // =========================
        // VALIDACIÓN
        // =========================
        if (empty($input['registro_id']) || empty($input['placa'])) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Datos incompletos"
            ]);
        }

        $db = \Config\Database::connect();

        // =========================
        // INSERT
        // =========================
        $db->table('registro_estacionamiento')->insert([
            'registro_id'               => $input['registro_id'],
            'tipo'                   => $input['tipo'] ?? '',
            'placa'                    => strtoupper($input['placa']),
            'modelo'                   => $input['modelo'] ?? '',
            'color'                    => $input['color'] ?? '',
            'registro_acompanante_id'  => $input['registro_acompanante_id'] ?? null,
            'tipo_vehiculo'            => $input['tipo_vehiculo'] ?? 'AUTO',
            'numero_cajon'             => $input['numero_cajon'] ?? null,
            'cargo'                    => $input['cargo'] ?? 0,
            'hora_entrada'             => $input['hora_entrada'] ?? date('Y-m-d H:i:s'),
            'estado'                   => 'ACTIVO',
            'created_at'               => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Vehículo registrado"
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en servidor",
            "error" => $e->getMessage()
        ]);
    }
}

public function lista_vehiculos($registro_id)
{
    try {

        $db = \Config\Database::connect();

 
   $data = $db->table('registro_estacionamiento v')
    ->select("
        v.id,
        v.placa,
        v.modelo,
        v.color,
        v.registro_acompanante_id,
        v.tipo,

        CASE 
            WHEN v.tipo = 'TITULAR' 
                THEN CONCAT(h.nombre, ' ', IFNULL(h.apellido, ''))
            WHEN v.tipo = 'ACOMPANANTE' 
                THEN CONCAT(a.nombre, ' ', IFNULL(a.apellido, ''))
            ELSE 'N/A'
        END as propietario
    ")
    ->join(
        'registro_acompanantes a',
        "a.id = v.registro_acompanante_id AND v.tipo = 'ACOMPANANTE'",
        'left'
    )
    ->join(
        'huespedes h',
        "h.id = v.registro_acompanante_id AND v.tipo = 'TITULAR'",
        'left'
    )
    ->where('v.registro_id', $registro_id)
    ->where('v.estado', 'ACTIVO')
    ->orderBy('v.tipo', 'DESC')
    ->get()
    ->getResult();

        return $this->response->setJSON($data);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error al cargar vehículos",
            "error" => $e->getMessage()
        ]);
    }
}

public function eliminar_vehiculo($id)
{
    try {

        $db = \Config\Database::connect();

        // validar existencia
        $vehiculo = $db->table('registro_estacionamiento')
            ->where('id', $id)
            ->get()
            ->getRow();

        if (!$vehiculo) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Vehículo no encontrado"
            ]);
        }

        // =========================
        // 🔥 UPDATE (NO DELETE)
        // =========================
        $db->table('registro_estacionamiento')
            ->where('id', $id)
            ->update([
                'estado' => 'INACTIVO',
                'hora_salida' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Vehículo eliminado correctamente"
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en servidor",
            "error" => $e->getMessage()
        ]);
    }
}


public function guardarPerfil()
{
    try {

        $input = $this->request->getJSON(true);

        // =========================
        // 📥 INPUTS
        // =========================
        $registro_id = $input['registro_id'] ?? null;
        $rfc         = strtoupper(trim($input['rfc'] ?? ''));
        $razon       = trim($input['razon_social'] ?? '');
        $regimen     = $input['regimen_fiscal'] ?? null;
        $cp          = $input['codigo_postal_fiscal'] ?? null;
        $uso_cfdi    = $input['uso_cfdi'] ?? null;
        $email       = $input['email_facturacion'] ?? null;
        $extranjero  = $input['es_extranjero'] ?? 0;

        // =========================
        // 🚨 VALIDACIÓN
        // =========================
        if (!$registro_id || !$rfc || !$razon) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Datos fiscales incompletos"
            ]);
        }

        $db = \Config\Database::connect();

        // =========================
        // 🔒 TRANSACCIÓN
        // =========================
        $db->transStart();

        // =========================
        // 🔍 VALIDAR SI YA EXISTE RFC
        // =========================
        $perfilExistente = $db->table('Perfiles_Fiscales')
            ->where('rfc', $rfc)
            ->get()
            ->getRow();

        if ($perfilExistente) {

            $id_perfil = $perfilExistente->id_perfil;

        } else {

            // =========================
            // 💾 INSERT PERFIL
            // =========================
            $db->table('Perfiles_Fiscales')->insert([
                'rfc'                    => $rfc,
                'razon_social'           => $razon,
                'regimen_fiscal'         => $regimen,
                'codigo_postal_fiscal'   => $cp,
                'uso_cfdi'               => $uso_cfdi,
                'email_facturacion'      => $email,
                'es_extranjero'          => $extranjero,
                'fecha_registro'         => date('Y-m-d H:i:s')
            ]);

            $id_perfil = $db->insertID();
        }

        // =========================
        // 🔗 ACTUALIZAR REGISTRO
        // =========================
        $db->table('registros')
            ->where('id', $registro_id)
            ->update([
                'id_perfil' => $id_perfil,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $db->transComplete();

        // =========================
        // ✅ RESULTADO
        // =========================
        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Error al guardar perfil fiscal"
            ]);
        }

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Perfil fiscal asignado correctamente",
            "id_perfil" => $id_perfil
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en servidor",
            "error" => $e->getMessage()
        ]);
    }
}

public function eliminarPerfil($registro_id)
{
    $db = \Config\Database::connect();

    $db->table('registros')
        ->where('id', $registro_id)
        ->update([
            'id_perfil' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    return $this->response->setJSON([
        "ok" => true
    ]);
}

public function obtenerPerfil($registro_id)
{
    try {

        $db = \Config\Database::connect();

        $perfil = $db->table('registros r')
            ->select('p.*')
            ->join('Perfiles_Fiscales p', 'p.id_perfil = r.id_perfil', 'left')
            ->where('r.id', $registro_id)
            ->get()
            ->getRow();

        if (!$perfil) {
            return $this->response->setJSON([
                "ok" => false
            ]);
        }

        return $this->response->setJSON([
            "ok" => true,
            "data" => $perfil
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => "Error en servidor",
            "error" => $e->getMessage()
        ]);
    }
}



public function cancelar()
{
    try {

        $json = $this->request->getJSON(true);

        if (empty($json['id'])) {
            throw new \Exception("ID requerido");
        }

        $registroId = $json['id'];
        $motivo     = $json['motivo'] ?? 'Cancelación sin motivo';

        $db = \Config\Database::connect();
        $db->transStart();

        // =========================
        // 🔎 OBTENER REGISTRO
        // =========================
        $registro = $db->table('registro')
            ->where('id', $registroId)
            ->get()
            ->getRowArray();

        if (!$registro) {
            throw new \Exception("Registro no encontrado");
        }

        if ($registro['estado'] === 'CANCELADO') {
            throw new \Exception("El registro ya está cancelado");
        }

        // =========================
        // 💰 OBTENER CARGOS A CANCELAR
        // =========================
        $cargos = $db->table('registro_cargos')
            ->where('registro_id', $registroId)
            ->groupStart()
                ->where('tipo', 'Hospedaje')
                ->orWhere('tipo', 'Persona Extra')
            ->groupEnd()
            ->get()
            ->getResultArray();

        $totalReverso = 0;

        foreach ($cargos as $c) {
            $totalReverso += floatval($c['total']);
        }

        // =========================
        // 🔁 REVERSO CONTABLE
        // =========================
        if ($totalReverso > 0) {

            $db->table('registro_cargos')->insert([
                'registro_id'     => $registroId,
                'concepto'        => 'Cancelación de estadía',
                'cantidad'        => 1,
                'precio_unitario' => -$totalReverso,
                'total'           => -$totalReverso,
                'tipo'            => 'Ajuste',
                'departamento'    => 'SISTEMA',
                'created_at'      => date('Y-m-d H:i:s'),
                'observaciones'   => $motivo
            ]);
        }

        // =========================
        // 🏨 LIBERAR HABITACIÓN
        // =========================
        if (!empty($registro['habitacion_id'])) {

            $db->table('habitaciones')
                ->where('id', $registro['habitacion_id'])
                ->update([
                    'estado' => 'DISPONIBLE'
                ]);
        }

        // =========================
        // 📌 ACTUALIZAR REGISTRO
        // =========================
        $db->table('registro')
            ->where('id', $registroId)
            ->update([
                'estado'             => 'CANCELADO',
                'fecha_cancelacion'  => date('Y-m-d H:i:s'),
                'motivo_cancelacion' => $motivo
            ]);

        // =========================
        // 🧾 LOG (OPCIONAL PERO PRO)
        // =========================
        $db->table('registro_logs')->insert([
            'registro_id' => $registroId,
            'tipo'        => 'CANCELACION',
            'descripcion' => $motivo,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Estadía cancelada correctamente',
            'reverso' => $totalReverso
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }
}


public function cancelarInteligente()
{
    try {

        $json = $this->request->getJSON(true);

        if (empty($json['id'])) {
            throw new \Exception("ID requerido");
        }

        $registroId = $json['id'];
        $tipo       = $json['tipo_cancelacion'] ?? 'TOTAL';
        $nochesUsadas = intval($json['noches_usadas'] ?? 0);
        $penalizacion = floatval($json['penalizacion'] ?? 0);
        $motivo     = $json['motivo'] ?? 'Cancelación';

        

        $db = \Config\Database::connect();
        $db->transStart();

        // =========================
        // 🔎 REGISTRO
        // =========================
        $registro = $db->table('registros')
            ->where('id', $registroId)
            ->get()->getRowArray();

        if (!$registro) throw new \Exception("Registro no encontrado");

        // =========================
        // 🛏️ NOCHES TOTALES
        // =========================
        $entrada = new \DateTime($registro['hora_entrada']);
        $salida  = new \DateTime($registro['hora_salida'] ?? date('Y-m-d H:i:s'));

        $nochesTotales = max(1, $entrada->diff($salida)->days);

        // =========================
        // 💰 CARGOS
        // =========================
        $cargos = $db->table('registro_cargos')
            ->where('registro_id', $registroId)
            ->groupStart()
                ->where('tipo', 'Hospedaje')
                ->orWhere('tipo', 'Persona Extra')
            ->groupEnd()
            ->get()->getResultArray();

        $reversoTotal = 0;

        foreach ($cargos as $c) {

            $monto = floatval($c['total']);

            // =========================
            // 🔥 LÓGICA INTELIGENTE
            // =========================
            if ($tipo === 'NO_SHOW') {

                $reverso = $monto;

            } elseif ($tipo === 'EARLY') {

                $nochesNoUsadas = max(0, $nochesTotales - $nochesUsadas);

                $porNoche = $monto / $nochesTotales;

                $reverso = $porNoche * $nochesNoUsadas;

            } else { // TOTAL

                $reverso = $monto;
            }

            $reversoTotal += $reverso;
        }

        // =========================
        // 🔁 REVERSO
        // =========================
        if ($reversoTotal > 0) {

           $insert = $db->table('registro_cargos')->insert([
    'registro_id'     => $registroId,
    'concepto'        => 'Reverso cancelación (' . $tipo . ')',
    'cantidad'        => 1,
    'precio_unitario' => -$reversoTotal,
    'Subtotal'        => -$reversoTotal,
    'total'           => -$reversoTotal,
    'tipo'            => 'Ajuste',
    'departamento'    => 'SISTEMA',
    'created_at'      => date('Y-m-d H:i:s'),
    'observaciones'   => $motivo
]);

if (!$insert) {
    $error = $db->error();
    dd($error); // 🔥 ESTO TE VA A DECIR TODO
}
        }

        // =========================
        // 💸 PENALIZACIÓN
        // =========================
        if ($penalizacion > 0) {

            $db->table('registro_cargos')->insert([
                'registro_id'     => $registroId,
                'concepto'        => 'Penalización cancelación',
                'cantidad'        => 1,
                'precio_unitario' => $penalizacion,
                'Subtotal'        => $penalizacion,
                'total'           => $penalizacion,
                'tipo'            => 'Penalización',
                'departamento'    => 'SISTEMA',
                'created_at'      => date('Y-m-d H:i:s'),
                'observaciones'   => $motivo
            ]);
        }

        // =========================
        // 🏨 LIBERAR HABITACIÓN
        // =========================
       /*  if (!empty($registro['habitacion_id'])) {
            $db->table('habitaciones')
                ->where('id', $registro['habitacion_id'])
                ->update(['estado' => 'DISPONIBLE']);
        } */

        // =========================
        // 📌 ESTADO
        // =========================
        $db->table('registro')
            ->where('id', $registroId)
            ->update([
                'estado_registro' => 'CHECKOUT',
                'estado_servicio' => 'CANCELADO',                  
                'hora_salida_real' => date('Y-m-d H:i:s'),
                'motivo_cancelacion' => $motivo,
                'tipo_cancelacion' => $tipo
            ]);

        $db->transComplete();

        $this->recalcularTotalesRegistro($registroId);

        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Cancelación procesada',
            'reverso' => $reversoTotal,
            'penalizacion' => $penalizacion
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }
}







public function recalcularTotalesRegistro($registro_id)
{
    $db = \Config\Database::connect();

    // 🔥 SUMAR CARGOS ACTIVOS
    $row = $db->table('registro_cargos')
        ->select("
            SUM(subtotal) as subtotal,
            SUM(iva) as iva,
            SUM(ish) as ish,
            SUM(total) as total
        ")
        ->where('registro_id', $registro_id)
        ->where('estado', 'ACTIVO')
        ->get()
        ->getRow();

    // 🔥 valores seguros
    $subtotal = $row->subtotal ?? 0;
    $iva      = $row->iva ?? 0;
    $ish      = $row->ish ?? 0;
    $total    = $row->total ?? 0;

    // 🔥 UPDATE REGISTRO
    $db->table('registros')
        ->where('id', $registro_id)
        ->update([
            'precio' => $subtotal,
            'iva'    => $iva,
            'ish'    => $ish,
            'total'  => $total,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    return [
        'ok' => true,
        'registro_id' => $registro_id,
        'totales' => [
            'subtotal' => $subtotal,
            'iva' => $iva,
            'ish' => $ish,
            'total' => $total
        ]
    ];
}


public function validarDuplicado()
{
    try {

        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->response->setJSON([
                'ok' => false,
                'msg' => 'Sin datos'
            ]);
        }

        $db = \Config\Database::connect();

        $builder = $db->table('huespedes');

        $builder->select("
            id,
            nombre,
            apellido,
            numero_identificacion,
            telefono,
            email
        ");

        // =========================
        // 🔥 FILTRO DUPLICADOS
        // =========================
        $builder->groupStart();

        if (!empty($json['numero_identificacion'])) {
            $builder->orWhere('numero_identificacion', $json['numero_identificacion']);
        }

        if (!empty($json['telefono'])) {
            $builder->orWhere('telefono', $json['telefono']);
        }

        if (!empty($json['email'])) {
            $builder->orWhere('email', $json['email']);
        }

        $builder->groupEnd();

        // solo activos
        $builder->where('activo', 1);

        $result = $builder->get()->getResult();

        return $this->response->setJSON([
            'ok' => true,
            'duplicados' => $result
        ]);

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }
}


public function cerrarYLimpia()
{
    $json = $this->request->getJSON(true);
    $db = \Config\Database::connect();

    // 🔥 buscar registro activo
    $registro = $db->table('registros')
        ->where('habitacion_id', $json['habitacion_id'])
        ->where('cerrado', 0)
        ->get()->getRow();

    if (!$registro) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'No hay registro activo'
        ]);
    }

    // 🔥 cerrar registro (forzado)
    $db->table('registros')
        ->where('id', $registro->id)
        ->update([
            'cerrado' => 1,
            'hora_salida_1' => date('Y-m-d H:i:s')
        ]);

    // 🔥 marcar SUCIA (NO limpia)
    $db->table('habitaciones')
        ->where('numero', $json['habitacion_id'])
        ->update([
            'status' => 'S'
        ]);

    return $this->response->setJSON([
        'ok' => true,
        'msg' => 'Registro cerrado y habitación marcada como sucia'
    ]);
}




public function marcarLimpia()
{
    $json = $this->request->getJSON(true);

    // =========================
    // 🔥 VALIDAR INPUT
    // =========================
    if (empty($json['habitacion_id'])) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Habitación requerida'
        ]);
    }

    $db = \Config\Database::connect();

    // =========================
    // 🔥 OBTENER ID REAL DESDE NUMERO (ROOM)
    // =========================
    $habitacion = $db->table('habitaciones')
        ->select('id, numero')
        ->where('numero', $json['habitacion_id'])
        ->get()
        ->getRow();

    if (!$habitacion) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Habitación no encontrada'
        ]);
    }

    $habitacionId = $habitacion->id;

    // =========================
    // 🔥 VALIDAR SI ESTÁ OCUPADA (CHECKIN)
    // =========================
    $ocupada = $db->table('registros')
        ->where('habitacion_id', $habitacionId)
        ->where('estado_registro', 'CHECKIN')
        ->where('estado_servicio', 'ACTIVO')
        ->get()
        ->getRow();

    if ($ocupada) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'No puedes limpiar: habitación ocupada'
        ]);
    }

    // =========================
    // 🔥 CERRAR SERVICIO
    // =========================
    $registro = $db->table('registros')
        ->where('habitacion_id', $habitacionId)
        ->where('estado_registro', 'CHECKOUT')
        ->where('estado_servicio', 'ACTIVO')
        ->orderBy('id', 'DESC')
        ->get()
        ->getRow();

    if ($registro) {
        $db->table('registros')
            ->where('id', $registro->id)
            ->update([
                'estado_servicio' => 'CERRADO'
            ]);
    }

    // =========================
    // 🔥 ACTUALIZAR HABITACIÓN → LIMPIA
    // =========================
    $db->table('habitaciones')
        ->where('id', $habitacionId)
        ->update([
            'estado_id' => '2'
        ]);

    if ($db->affectedRows() == 0) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'No se actualizó la habitación'
        ]);
    }

    return $this->response->setJSON([
        'ok' => true,
        'msg' => 'Habitación marcada como limpia'
    ]);
}


/* =====================================
   CAMBIAR ESTADO HABITACIÓN (MTTO / LIMPIA)
===================================== */
public function cambiarEstado()
{
    $json = $this->request->getJSON(true);

    if (empty($json['habitacion_id']) || empty($json['estado'])) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Datos incompletos'
        ]);
    }

    $db = \Config\Database::connect();

    // =========================
    // 🔥 MAPEO
    // =========================
    $mapEstados = [
        'X' => 2, // limpia
        'M' => 3  // mantenimiento
    ];

    if (!isset($mapEstados[$json['estado']])) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Estado inválido'
        ]);
    }

    $estadoId = $mapEstados[$json['estado']];

    // =========================
    // 🔥 OBTENER HABITACIÓN
    // =========================
    $habitacion = $db->table('habitaciones')
        ->select('id, numero')
        ->where('numero', $json['habitacion_id'])
        ->get()
        ->getRow();

    if (!$habitacion) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Habitación no encontrada'
        ]);
    }

    $habitacionId = $habitacion->id;

    // =========================
    // 🔥 VALIDAR CHECKIN
    // =========================
    $checkin = $db->table('registros')
        ->where('habitacion_id', $habitacionId)
        ->where('estado_registro', 'CHECKIN')
        ->where('estado_servicio', 'ACTIVO')
        ->get()
        ->getRow();

    if ($checkin) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'No puedes enviar a mantenimiento: habitación ocupada'
        ]);
    }

    // =========================
    // 🔥 CERRAR SERVICIO ACTIVO
    // =========================
    $registro = $db->table('registros')
        ->where('habitacion_id', $habitacionId)
        ->where('estado_servicio', 'ACTIVO')
        ->orderBy('id', 'DESC')
        ->get()
        ->getRow();

    if ($registro) {
        $db->table('registros')
            ->where('id', $registro->id)
            ->update([
                'estado_servicio' => 'CERRADO'
            ]);
    }

    // =========================
    // 🔥 ACTUALIZAR HABITACIÓN
    // =========================
    $db->table('habitaciones')
        ->where('id', $habitacionId)
        ->update([
            'estado_id' => $estadoId
        ]);

    if ($db->affectedRows() == 0) {
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'No se actualizó la habitación'
        ]);
    }

    return $this->response->setJSON([
        'ok' => true,
        'msg' => 'Estado actualizado correctamente'
    ]);
}

public function obtenerImpuestos()
{
    $db = \Config\Database::connect();

    $data = $db->table('impuestos')
        ->where('activo', 1)
        ->get()
        ->getResult();

    return $this->response->setJSON($data);
}


/**
 * 🔥 Función especializada para el modal de Traslados
 * Retorna solo las habitaciones que tienen un huésped con CHECKIN activo
 */
public function getHabitacionesActivas()
{
    $db = \Config\Database::connect();
    $sql = "SELECT 
                r.id AS registro_id,
                h.numero AS habitacion_numero,
                h.id AS habitacion_id_db,
                CONCAT(IFNULL(hu.nombre,''), ' ', IFNULL(hu.apellido,'')) AS nombre_huesped
            FROM registros r
            INNER JOIN habitaciones h ON h.id = r.habitacion_id
            LEFT JOIN huespedes hu ON hu.id = r.huesped_id
            WHERE r.estado_registro = 'CHECKIN'
            AND h.activa = 1
            ORDER BY h.numero ASC";
            
    $data = $db->query($sql)->getResultArray();
    return $this->response->setJSON($data);
}


public function getHabitaciones()
{
    $db = \Config\Database::connect();

    // 🔹 QUERY PRINCIPAL (Trae todas las habitaciones y sus registros activos si existen)
    $habitaciones = $db->query("
        SELECT 
            h.id                AS habitacion_id,
            h.numero            AS numero,
            p.piso              AS piso,
            eh.codigo           AS status,
            r.estado_registro   AS estado_registro,
            r.id                AS registro_id,
            r.tipo_estadia_id   AS tipo_estadia,
            r.noches            AS noches,
            r.adultos           AS adultos,
            r.niños             AS ninos,
            r.num_personas_ext  AS num_personas_ext,
            r.incluir_en_reporte AS incluir_en_reporte,
            (IFNULL(r.adultos,0) + IFNULL(r.niños,0) + IFNULL(r.num_personas_ext,0)) AS ocupacion_total,

            r.forma_pago_id     AS forma_pago,
            r.precio            AS precio,
            r.iva               AS iva,
            r.ish               AS ish,
            r.total             AS total,
            r.created_at        AS fecha_registro,

            r.hora_entrada      AS hora_entrada_1,
            r.hora_salida       AS hora_salida_1,

            (SELECT MAX(fecha_salida) FROM salidas_clientes sc WHERE sc.registro_id = r.id AND sc.tipo_salida = 'entrada') AS ultima_entrada,
            (SELECT MAX(fecha_salida) FROM salidas_clientes sc WHERE sc.registro_id = r.id AND sc.tipo_salida != 'entrada') AS ultima_salida,

            CONCAT(IFNULL(hu.nombre,''), ' ', IFNULL(hu.apellido,'')) AS nombre_huesped,
            COALESCE(r.estado_registro, 'DISPONIBLE') AS estado_registro_val,
            r.observaciones     AS observaciones,

            hu.id               AS huesped_id,
            hu.fotografia       AS fotografia,
            hu.identificacion   AS identificacion,
            hu.firma_path       AS firma_path,

            h.estado_id         AS estado_id,

            hp.nombre           AS tipo_habitacion,
            hp.precio_base,
            hp.precio_persona_extra,
            hp.personas_max     AS capacidad_hab,
			CASE WHEN r.estado_registro in ('CHECKIN','CHECKOUT') then 'Con registro' else 'Sin registro' END registro 

        FROM habitaciones h
        LEFT JOIN registros r 
            ON r.habitacion_id = h.id 
            AND r.estado_registro IN ('CHECKIN','CHECKOUT', 'DISPONIBLE')
        INNER JOIN habitaciones_tipos hp 
            ON hp.id = h.tipo_habitacion_id
        INNER JOIN pisos p 
            ON p.id = h.piso_id
        LEFT JOIN estados_habitacion eh 
            ON eh.id = r.estado_id
        LEFT JOIN huespedes hu 
            ON hu.id = r.huesped_id
        WHERE h.activa = 1
        ORDER BY p.piso ASC, h.numero ASC
    ")->getResultArray();


    // 🔹 ACOMPAÑANTES
    $acompanantes = $db->query("
        SELECT 
            ra.id              AS acompanante_id,
            ra.registro_id     AS registro_id,
            ra.nombre          AS nombre,
            ra.apellido        AS apellidos,
            ra.parentesco      AS parentesco,
            ra.es_menor        AS es_menor,
            ra.Responsable_menor AS Responsable_menor,
            ra.numero_identificacion AS idNum,
            ra.fotografia      AS fotografia,
            ra.identificacion  AS identificacion,
            ra.es_ext          AS es_extra
        FROM registro_acompanantes ra
    ")->getResultArray();


    // 🔹 CARGOS / SERVICIOS (🔥 NUEVO)
    $cargos = $db->query("
        SELECT 
            id,
            registro_id,
            concepto   AS name,
            total      AS price,
            created_at AS date
        FROM registro_cargos
        WHERE estado = 'ACTIVO'
    ")->getResultArray();

    // 🔹 MAPA DE ACOMPAÑANTES
    $mapAcomp = [];
    foreach ($acompanantes as $a) {
        $mapAcomp[$a['registro_id']][] = [
            'nombre'         => $a['nombre'],
            'apellidos'      => $a['apellidos'],
            'parentesco'     => $a['parentesco'],
            'es_menor'       => $a['es_menor'],
            'Responsable_menor' => $a['Responsable_menor'],
            'isTitular'      => false,
            'placas'         => '',
            'idNum'          => $a['idNum'],
            'fotografia'     => $a['fotografia'],
            'identificacion' => $a['identificacion'],
            'es_extra'       => $a['es_extra'] ?? 0
        ];
    }

    // 🔹 MAPA DE CARGOS (🔥 NUEVO)
    $mapCargos = [];
    foreach ($cargos as $c) {
        $mapCargos[$c['registro_id']][] = [
            'id'    => $c['id'],
            'name'  => $c['name'],
            'price' => (float)$c['price'],
            'date'  => $c['date']
        ];
    }


    // 🔹 RESPUESTA FINAL
    $result = [];

    foreach ($habitaciones as $r) {

        $huespedes = [];

        // 🔹 TITULAR
        if (!empty($r['huesped_id'])) {
            $partes = explode(' ', trim($r['nombre_huesped']), 2);

            $huespedes[] = [
                'nombre'         => $partes[0] ?? '',
                'apellidos'      => $partes[1] ?? '',
                'isTitular'      => true,
                'placas'         => '',
                'idNum'          => '',
                'fotografia'     => $r['fotografia'],
                'identificacion' => $r['identificacion'],
                'firma_path'     => $r['firma_path'],
                'es_extra'       => 0
            ];
        }

        // 🔹 ACOMPAÑANTES
        if (!empty($mapAcomp[$r['registro_id']])) {
            $huespedes = array_merge($huespedes, $mapAcomp[$r['registro_id']]);
        }

        $result[] = [
            // 🏨 INFO HABITACIÓN
            'numero'        => $r['numero'],
            'piso'          => $r['piso'],
            'status'        => $r['status'],
            'estado_id'     => $r['estado_id'],
            'habitacion_id' => $r['habitacion_id'],

            // 🛏️ CONFIG HABITACIÓN
            'tipo_habitacion'        => $r['tipo_habitacion'],
            'precio_base'            => $r['precio_base'],
            'precio_persona_extra'   => $r['precio_persona_extra'],
            'capacidad_hab'          => $r['capacidad_hab'],

            // 📊 REGISTRO
            'tipo_estadia'     => $r['tipo_estadia'],
            'noches'           => $r['noches'],
            'adultos'          => (int)$r['adultos'],
            'ninos'            => (int)$r['ninos'],
            'ocupacion_total'  => (int)$r['ocupacion_total'],
            'num_personas_ext' => (int)$r['num_personas_ext'],
            'incluir_en_reporte' => (int)$r['incluir_en_reporte'],
             'registro' => $r['registro'],

            

            'forma_pago'    => $r['forma_pago'],
            'total'         => $r['total'],

            // ⏰ HORAS
            'hora_entrada_1'=> $r['hora_entrada_1'],
            'hora_salida_1' => $r['hora_salida_1'],

            // 👤 HUESPEDES
            'huespedes'     => $huespedes,
            'nombre_huesped'=> trim($r['nombre_huesped']),
            'estado_registro_val'=> trim($r['estado_registro_val']),
            'observaciones' => $r['observaciones'],
            'sombreado'     => 0,

            'services'      => $mapCargos[$r['registro_id']] ?? [], // 🔥 CARGOS

            // 🔧 DEBUG / FRONT
            'habitacion_id' => $r['habitacion_id'],
            'registro_id'   => $r['registro_id']
        ];
    }

    return $this->response->setJSON($result);
}

public function estadosHabitacion()
    {
        $db = \Config\Database::connect();

        $data = $db->query("
            SELECT codigo, nombre 
            FROM estados_habitacion
            ORDER BY nombre
        ")->getResultArray();

        return $this->response->setJSON($data);
    }

    public function checkout()
{
    $db = \Config\Database::connect();
    $request = service('request');

    $registroId = $request->getPost('registro_id');

    if (!$registroId) {
        return $this->response->setJSON([
            'success' => false,
            'msg' => 'registro_id requerido'
        ]);
    }

    // 🔹 Obtener registro actual
    $registro = $db->table('registros')
        ->where('id', $registroId)
        ->get()
        ->getRowArray();

    if (!$registro) {
        return $this->response->setJSON([
            'success' => false,
            'msg' => 'Registro no encontrado'
        ]);
    }

    // 🔥 Evitar doble checkout
    if ($registro['estado_registro'] === 'CHECKOUT') {
        return $this->response->setJSON([
            'success' => false,
            'msg' => 'El registro ya está en checkout'
        ]);
    }

    $db->transStart();

    // 🔹 1. Marcar como checkout
    $db->table('registros')
        ->where('id', $registroId)
        ->update([
            'estado_registro' => 'CHECKOUT',
            'hora_salida_real'     => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

    // 🔹 2. Marcar habitación como SUCIA (ID 1)
    $db->table('habitaciones')
        ->where('id', $registro['habitacion_id'])
        ->update([
            'estado_id' => 1 // 'S' - SUCIA
        ]);

    // 🔹 3. Registrar salida definitiva en la nueva tabla
    $db->table('salidas_clientes')->insert([
        'registro_id'   => $registroId,
        'habitacion_id' => $registro['habitacion_id'],
        'nombre_huesped'=> $registro['nombre_huesped'] ?? 'TITULAR',
        'tipo_salida'   => 'DEFINITIVA',
        'motivo'        => 'CHECKOUT FINALIZADO',
        'fecha_salida'  => date('Y-m-d H:i:s'),
        'usuario_id'    => session()->get('user_id'),
        'created_at'    => date('Y-m-d H:i:s')
    ]);

    // 🔹 4. Generar el nuevo folio DISPONIBLE para la habitación (Sucia)
    $db->table('registros')->insert([
        'habitacion_id'   => $registro['habitacion_id'],
        'estado_registro' => 'DISPONIBLE',
        'estado_id'       => 1, // 'S' - SUCIA
        'huesped_id'      => 0,
        'estado_servicio' => 'ACTIVO',
        'turno_id'        => $registro['turno_id'] ?? null,
        'usuario_id'      => session()->get('user_id'),
        'created_at'      => date('Y-m-d H:i:s'),
        'updated_at'      => date('Y-m-d H:i:s')
    ]);

    $db->transComplete();

    // 🔥 Validar transacción
    if ($db->transStatus() === false) {
        return $this->response->setJSON([
            'success' => false,
            'msg' => 'Error al realizar checkout'
        ]);
    }

    return $this->response->setJSON([
        'success' => true,
        'msg' => 'Checkout realizado correctamente. La habitación ahora está SUCIA.'
    ]);
}

    public function actualizarCampoRegistro() {
        try {
            // 🚀 Búsqueda agresiva de datos
            $post = (array)$this->request->getPost();
            $json = (array)$this->request->getJSON(true);
            $get  = (array)$this->request->getGet();
            
            $datos = array_merge($post, $json, $get);

            error_log('📊 [DEBUG SYNC] Datos procesados: ' . json_encode($datos));

            $registroId = $datos['registro_id'] ?? $this->request->getVar('registro_id');

            if (!$registroId) {
                return $this->response->setJSON([
                    "success" => false, 
                    "msg" => "Faltan registro_id.",
                    "debug_received" => $datos
                ]);
            }

            $model = new \App\Models\RegistroModel();
            $updateData = [];
            
            // Lógica de Impuestos Atómica
            if (isset($datos['precio']) && $datos['precio'] !== '') {
                $total = floatval($datos['precio']);
                $noches = intval($datos['noches'] ?? 1);
                if ($noches <= 0) $noches = 1;

                $ivaRate = 0.16;
                $ishRate = 0.035;
                $baseTotal = $total / (1 + $ivaRate + $ishRate);
                
                $updateData['precio']      = round($baseTotal, 2);           // Subtotal total
                $updateData['precio_base'] = round($baseTotal / $noches, 2);  // Precio base por noche
                $updateData['iva']         = round($baseTotal * $ivaRate, 2);
                $updateData['ish']         = round($baseTotal * $ishRate, 2);
                $updateData['total']       = $total;
            }

            // Mapeo de campos permitidos
            if (isset($datos['noches']) && $datos['noches'] !== '') {
                $updateData['noches'] = $datos['noches'];
            }
            
            if (isset($datos['tipo_estadia_id'])) {
                $val = $datos['tipo_estadia_id'];
                $updateData['tipo_estadia_id'] = ($val === 'null' || $val === '') ? null : $val;
            }
            
            if (isset($datos['forma_pago_id'])) {
                $val = $datos['forma_pago_id'];
                $updateData['forma_pago_id'] = ($val === 'null' || $val === '') ? null : $val;
            }

            // 🔥 NUEVO: Soporte para Incluir en Reporte
            if (isset($datos['incluir_en_reporte'])) {
                $updateData['incluir_en_reporte'] = intval($datos['incluir_en_reporte']);
            }

            // 🔥 NUEVO: Soporte para Estado (Estatus)
            if (isset($datos['estado_id'])) {
                $updateData['estado_id'] = intval($datos['estado_id']);
            }
            
            // 🔥 NUEVO: Soporte para conteo de personas
            if (isset($datos['adultos'])) {
                $updateData['adultos'] = intval($datos['adultos']);
            }
            if (isset($datos['ninos'])) {
                $updateData['niños'] = intval($datos['ninos']);
            }

            // Soporte para formato campo/valor
            if (isset($datos['campo']) && isset($datos['valor'])) {
                $updateData[$datos['campo']] = $datos['valor'];
            }

            if (empty($updateData)) {
                return $this->response->setJSON(["success" => false, "msg" => "No hay campos para actualizar"]);
            }

            // 🛠 DEBUG: Loguear lo que vamos a guardar
            file_put_contents(WRITEPATH . 'db_errors.log', "Updating ID: $registroId Data: " . json_encode($updateData) . PHP_EOL, FILE_APPEND);

            $ok = false;
            try {
                $ok = $model->update($registroId, $updateData);
                if (!$ok) {
                    file_put_contents(WRITEPATH . 'db_errors.log', "❌ DB Errors: " . json_encode($model->errors()) . PHP_EOL, FILE_APPEND);
                }
            } catch (\Exception $e) {
                file_put_contents(WRITEPATH . 'db_errors.log', "🔥 Exception: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }

            return $this->response->setJSON([
                "success" => $ok, 
                "msg" => $ok ? "Actualizado correctamente" : "Error al guardar en base de datos",
                "data" => $updateData
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                "success" => false,
                "msg" => "Error interno: " . $e->getMessage()
            ]);
        }
    }

    public function registrarSalida() {
        try {
            date_default_timezone_set('America/Mexico_City');
            $json = $this->request->getJSON(true);
            $registroId = $json['registro_id'] ?? null;
            $tipo = $json['tipo'] ?? 'TEMPORAL';

            if (!$registroId) {
                return $this->response->setJSON(['success' => false, 'msg' => 'registro_id requerido']);
            }

            $db = \Config\Database::connect();
            $registro = $db->table('registros')->where('id', $registroId)->get()->getRowArray();

            if (!$registro) {
                return $this->response->setJSON(['success' => false, 'msg' => 'Registro no encontrado']);
            }

            $data = [
                'registro_id'   => $registroId,
                'habitacion_id' => $registro['habitacion_id'],
                'nombre_huesped'=> $json['nombre_huesped'] ?? 'HUÉSPED',
                'tipo_salida'   => $tipo,
                'motivo'        => $json['motivo'] ?? ($tipo === 'TEMPORAL' ? 'SALIDA TEMPORAL' : 'CHECKOUT'),
                'fecha_salida'  => date('Y-m-d H:i:s'),
                'usuario_id'    => session()->get('user_id'),
                'created_at'    => date('Y-m-d H:i:s')
            ];
            $db->table('salidas_clientes')->insert($data);
            
            file_put_contents(WRITEPATH . 'db_errors.log', "SALIDA PERSISTED: " . json_encode($data) . PHP_EOL, FILE_APPEND);

            return $this->response->setJSON(['success' => true, 'msg' => 'Salida registrada correctamente']);
        } catch (\Exception $e) {
            file_put_contents(WRITEPATH . 'db_errors.log', "SALIDA ERROR: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function registrarEntrada() {
        try {
            date_default_timezone_set('America/Mexico_City');
            $json = $this->request->getJSON(true);
            
            file_put_contents(WRITEPATH . 'db_errors.log', "ENTRADA REQUEST RECEIVED: " . json_encode($json) . PHP_EOL, FILE_APPEND);

            $registroId = $json['registro_id'] ?? null;

            if (!$registroId) {
                file_put_contents(WRITEPATH . 'db_errors.log', "ENTRADA ERROR: registro_id missing" . PHP_EOL, FILE_APPEND);
                return $this->response->setJSON(['success' => false, 'msg' => 'registro_id requerido']);
            }

            $db = \Config\Database::connect();
            $registro = $db->table('registros')->where('id', $registroId)->get()->getRowArray();

            if (!$registro) {
                file_put_contents(WRITEPATH . 'db_errors.log', "ENTRADA ERROR: Registro $registroId not found" . PHP_EOL, FILE_APPEND);
                return $this->response->setJSON(['success' => false, 'msg' => 'Registro no encontrado']);
            }

            $data = [
                'registro_id'   => $registroId,
                'habitacion_id' => $registro['habitacion_id'],
                'nombre_huesped'=> $json['nombre_huesped'] ?? 'HUÉSPED',
                'tipo_salida'   => 'entrada',
                'motivo'        => 'REGRESO DE HUESPED',
                'fecha_salida'  => date('Y-m-d H:i:s'),
                'usuario_id'    => session()->get('get_id') ?? session()->get('user_id'),
                'created_at'    => date('Y-m-d H:i:s')
            ];
            $db->table('salidas_clientes')->insert($data);
            
            file_put_contents(WRITEPATH . 'db_errors.log', "ENTRADA (AS SALIDA) PERSISTED: " . json_encode($data) . PHP_EOL, FILE_APPEND);

            return $this->response->setJSON(['success' => true, 'msg' => 'Entrada registrada correctamente']);
        } catch (\Exception $e) {
            file_put_contents(WRITEPATH . 'db_errors.log', "ENTRADA FATAL ERROR: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return $this->response->setJSON(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getSalidas($registroId) {
        $db = \Config\Database::connect();
        $data = $db->table('salidas_clientes')
            ->where('registro_id', $registroId)
            ->orderBy('fecha_salida', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($data);
    }

}
