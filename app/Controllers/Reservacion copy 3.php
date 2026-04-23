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
            'niños'                => $json['niños'] ?? 0,
            

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

            // 🔥 LOG DE ENTRADA (Check-in)
            $db = \Config\Database::connect();
            
            // Obtener nombre del huesped para el log
            $huesped = $db->table('huespedes')->where('id', $data['huesped_id'])->get()->getRow();
            $nombreLog = $huesped ? ($huesped->nombre . ' ' . $huesped->apellido) : 'HUESPED';

            $db->table('salidas_clientes')->insert([
                'registro_id'    => $id,
                'habitacion_id'  => $data['habitacion_id'],
                'nombre_huesped' => $nombreLog,
                'tipo_salida'    => 'ENTRADA',
                'motivo'         => 'CHECKIN REALIZADO',
                'fecha_salida'   => date('Y-m-d H:i:s'),
                'usuario_id'     => session()->get('user_id'),
                'created_at'     => date('Y-m-d H:i:s')
            ]);

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
            $ishRate = 0.03;

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
            "success" => true,
            "msg" => "Registro guardado y Entrada registrada con éxito",
            "registro_id" => $id,
            "datos" => $data
        ]);

    }catch(\Throwable $e){

        return $this->response->setJSON([
            "success" => false,
            "msg" => "Error al guardar registro: " . $e->getMessage()
        ]);
    }
}

public function guardarAcompanantes()
{
    $json = $this->request->getJSON(true);

    $registroId = $json['registro_id'];
    $lista = $json['acompanantes'];

    $db = \Config\Database::connect();

    // 🔥 estrategia PRO PMS
    // primero eliminar existentes
    $db->table('registro_acompanantes')
       ->where('registro_id',$registroId)
       ->delete();

    foreach($lista as $row){

        $db->table('registro_acompanantes')->insert([
            'registro_id' => $registroId,
            'nombre' => $row['nombre'],
            'parentesco' => $row['parentesco'],
            'es_menor' => $row['es_menor'],
            'fotografia' => $row['fotografia'],
            'es_ext' => $row['es_extra'],
            'hora_entrada' => date('Y-m-d H:i:s'),
            'estado_estancia' => 'ACTIVO',
            'created_at' => date('Y-m-d H:i:s')
        ]);

    }

    return $this->response->setJSON(["ok"=>true]);
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

    recalcularTotalesRegistro($registro_id);
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
            "ok" => true,
            "file" => $nombre
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


public function cambiarHabitacion()
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
            ->where('numero', $json['habitacion_id']) // viene 101
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

        if ($diferencia > 0) {

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

    recalcularTotalesRegistro($registro_id);
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

    recalcularTotalesRegistro($registro_id);
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


public function getHabitaciones()
{
    $db = \Config\Database::connect();

    // 🔹 QUERY PRINCIPAL
    try {
        $habitaciones = $db->query("
            SELECT 
                h.id                AS habitacion_id,
                h.numero            AS numero,
                p.nombre            AS piso,
                ht.nombre           AS tipo_habitacion,
                ht.personas_max     AS capacidad_hab,
                ht.precio_persona_extra AS precio_persona_extra,
                h.status            AS status,
                h.estado_id         AS estado_id,
                ht.precio_base      AS precio_hab_base,

                r.id                AS registro_id,
                r.estado_registro   AS estado_registro_val,
                r.noches            AS noches,
                r.adultos           AS adultos,
                r.niños             AS ninos,
                r.num_personas_ext  AS num_personas_ext,
                (r.adultos + r.niños + r.num_personas_ext) AS ocupacion_total,
                r.forma_pago_id     AS forma_pago_id,
                r.precio_base       AS precio_base,
                r.precio            AS precio,
                r.iva               AS iva,
                r.ish               AS ish,
                r.total             AS total,
                r.incluir_en_reporte AS incluir_en_reporte,
                r.observaciones     AS observaciones,
                r.hora_entrada      AS hora_entrada_1,
                r.hora_salida       AS hora_salida_1,
                
                te.nombre           AS tipo_estadia,
                r.tipo_estadia_id   AS tipo_estadia_id,
                fp.descripcion      AS forma_pago,
                CONCAT(hu.nombre, ' ', hu.apellido) AS nombre_huesped,
                r.huesped_id        AS huesped_id

            FROM habitaciones h
            LEFT JOIN pisos p ON h.piso_id = p.id
            LEFT JOIN habitaciones_tipos ht ON h.tipo_habitacion_id = ht.id
            LEFT JOIN registros r 
                ON r.id = (
                    SELECT MAX(r2.id) 
                    FROM registros r2 
                    WHERE r2.habitacion_id = h.id 
                    AND r2.estado_registro IN ('CHECKIN', 'CHECKOUT')
                )
            LEFT JOIN tipo_estadia te ON r.tipo_estadia_id = te.id
            LEFT JOIN formas_pago fp ON r.forma_pago_id = fp.id
            LEFT JOIN huespedes hu ON r.huesped_id = hu.id
            WHERE h.activa = 1
            ORDER BY CAST(h.numero AS UNSIGNED) ASC
        ")->getResultArray();
    } catch (\Throwable $e) {
        log_message('error', '[GET_HABITACIONES_ERROR] ' . $e->getMessage());
        return $this->response->setJSON(['error' => $e->getMessage()]);
    }

    // 🔹 ACOMPAÑANTES
    $acompanantes = $db->query("
        SELECT 
            ra.id              AS acompanante_id,
            ra.registro_id     AS registro_id,
            ra.nombre          AS nombre,
            ra.apellido        AS apellidos,
            ra.numero_identificacion AS idNum
        FROM registro_acompanantes ra
    ")->getResultArray();

    // 🔹 AGRUPAR ACOMPAÑANTES
    $mapAcomp = [];
    foreach ($acompanantes as $a) {
        $mapAcomp[$a['registro_id']][] = [
            'nombre'     => $a['nombre'],
            'apellidos'  => $a['apellidos'],
            'isTitular'  => false,
            'placas'     => '',
            'idNum'      => $a['idNum']
        ];
    }

    // 🔹 ARMAR RESPUESTA FINAL
    $result = [];

    foreach ($habitaciones as $r) {

        $huespedes = [];

        // 🔹 TITULAR
        if (!empty($r['huesped_id'])) {
            $partes = explode(' ', $r['nombre_huesped'], 2);

            $huespedes[] = [
                'nombre'     => $partes[0] ?? '',
                'apellidos'  => $partes[1] ?? '',
                'isTitular'  => true,
                'placas'     => '',
                'idNum'      => ''
            ];
        }

        // 🔹 ACOMPAÑANTES
        if (!empty($mapAcomp[$r['registro_id']])) {
            $huespedes = array_merge($huespedes, $mapAcomp[$r['registro_id']]);
        }

        $result[] = array_merge($r, [
            'huespedes' => $huespedes,
            'sombreado' => 0, // Default if not in DB
            'services'  => [], // Placeholders if not joined yet
            'payments'  => []
        ]);
    }

    return $this->response->setJSON($result);
}



    /* =====================================================
       REGISTRAR SALIDA (TEMPORAL / DEFINITIVA)
       ===================================================== */
    public function registrarSalida()
    {
        try {
            $json = $this->request->getJSON(true);
            $db = \Config\Database::connect();

            if (empty($json['registro_id'])) {
                return $this->response->setJSON(["success" => false, "msg" => "ID de registro requerido"]);
            }

            $nombreHuesped = $json['nombre_huesped'] ?? null;

            if (!$nombreHuesped) {
                $registro = $db->table('registros')->where('id', $json['registro_id'])->get()->getRow();
                if ($registro) {
                    $huesped = $db->table('huespedes')->where('id', $registro->huesped_id)->get()->getRow();
                    $nombreHuesped = $huesped ? ($huesped->nombre . ' ' . $huesped->apellido) : 'HUESPED';
                } else {
                    $nombreHuesped = 'HUESPED';
                }
            }

            $data = [
                'registro_id'    => $json['registro_id'],
                'habitacion_id'  => $json['habitacion_id'] ?? null,
                'nombre_huesped' => $nombreHuesped,
                'tipo_salida'    => $json['tipo'] ?? 'TEMPORAL',
                'motivo'         => $json['motivo'] ?? ($json['tipo'] === 'TEMPORAL' ? 'SALIDA TEMPORAL' : 'CHECKOUT FINALIZADO'),
                'fecha_salida'   => date('Y-m-d H:i:s'),
                'usuario_id'     => session()->get('user_id'),
                'created_at'     => date('Y-m-d H:i:s')
            ];

            $db->table('salidas_clientes')->insert($data);

            return $this->response->setJSON(["success" => true, "msg" => "Salida registrada"]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(["success" => false, "msg" => $e->getMessage()]);
        }
    }

    /* =====================================================
       CHECKOUT DEFINITIVO
       ===================================================== */
    public function checkout()
    {
        try {
            $registro_id = $this->request->getPost('registro_id');
            $db = \Config\Database::connect();

            if (!$registro_id) {
                return $this->response->setJSON(["success" => false, "msg" => "ID de registro requerido"]);
            }

            // 1. Obtener datos del registro
            $registro = $db->table('registros')->where('id', $registro_id)->get()->getRow();
            if (!$registro) {
                return $this->response->setJSON(["success" => false, "msg" => "Registro no encontrado"]);
            }

            $db->transStart();

            // 2. Actualizar estado del registro
            $db->table('registros')->where('id', $registro_id)->update([
                'estado_registro' => 'CHECKOUT',
                'hora_salida_real' => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            ]);

            // 3. Marcar habitación como SUCIA (status = 'S')
            if ($registro->habitacion_id) {
                $db->table('habitaciones')->where('id', $registro->habitacion_id)->update([
                    'status'    => 'S',
                    'estado_id' => 1 // ID para sucia
                ]);
            }

            // 4. Log de salida en salidas_clientes
            $huesped = $db->table('huespedes')->where('id', $registro->huesped_id)->get()->getRow();
            $nombreLog = $huesped ? ($huesped->nombre . ' ' . $huesped->apellido) : 'TITULAR';

            $db->table('salidas_clientes')->insert([
                'registro_id'    => $registro_id,
                'habitacion_id'  => $registro->habitacion_id,
                'nombre_huesped' => $nombreLog,
                'tipo_salida'    => 'DEFINITIVA',
                'motivo'         => 'CHECKOUT FINALIZADO',
                'fecha_salida'   => date('Y-m-d H:i:s'),
                'usuario_id'     => session()->get('user_id'),
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(["success" => false, "msg" => "Error al procesar el checkout"]);
            }

            return $this->response->setJSON(["success" => true, "msg" => "Checkout realizado con éxito"]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(["success" => false, "msg" => $e->getMessage()]);
        }
    }

    /* =====================================================
       HISTORIAL DE MOVIMIENTOS (Salidas/Entradas)
       ===================================================== */
    public function getSalidas($registro_id)
    {
        try {
            $db = \Config\Database::connect();
            $data = $db->table('salidas_clientes')
                       ->where('registro_id', $registro_id)
                       ->orderBy('id', 'DESC')
                       ->get()
                       ->getResultArray();

            return $this->response->setJSON($data);

        } catch (\Throwable $e) {
            return $this->response->setJSON([]);
        }
    }


    /* =====================================================
       CATALOGO ESTADOS DE HABITACION
       ===================================================== */
    public function estadosHabitacion()
    {
        try {
            $db = \Config\Database::connect();
            $data = $db->table('estados_habitacion')
                       ->where('activo', 1)
                       ->get()
                       ->getResultArray();

            return $this->response->setJSON($data);

        } catch (\Throwable $e) {
            return $this->response->setJSON([]);
        }
    }

    /* =====================================================
       ACTUALIZAR CAMPO ESPECIFICO DE REGISTRO
       ===================================================== */
    public function actualizarCampoRegistro()
    {
        try {
            $json = $this->request->getJSON(true);
            $data = $json ?? $this->request->getPost();

            if (empty($data['registro_id'])) {
                return $this->response->setJSON(["success" => false, "msg" => "ID de registro requerido"]);
            }

            $id = $data['registro_id'];
            unset($data['registro_id']); 

            // Manejar formato campo/valor o pares clave-valor
            if (isset($data['campo']) && isset($data['valor'])) {
                $dbData = [$data['campo'] => $data['valor']];
            } else {
                $dbData = $data;
            }

            // 🔥 RECALCULAR TODO DESDE EL TOTAL (El 'precio' recibido es el TOTAL)
            if (isset($dbData['precio']) || isset($dbData['total'])) {
                $totalVal = floatval($dbData['total'] ?? $dbData['precio']);
                $noches   = intval($data['noches'] ?? $data['dias'] ?? 1);
                if ($noches <= 0) $noches = 1;

                $IVA_RATE = 0.16;
                $ISH_RATE = 0.03;
                
                // 1. Subtotal Total = Total / 1.19
                $subtotalTotal = round($totalVal / (1 + $IVA_RATE + $ISH_RATE), 2);
                $ivaTotal      = round($subtotalTotal * $IVA_RATE, 2);
                $ishTotal      = round($subtotalTotal * $ISH_RATE, 2);
                
                // 2. Precio Base (Subtotal por noche)
                $subtotalPorNoche = round($subtotalTotal / $noches, 2);

                // Ajustamos para la BD
                $dbData['precio']      = $subtotalTotal; // Subtotal de todas las noches
                $dbData['precio_base'] = $subtotalPorNoche; // Subtotal por noche
                $dbData['iva']         = $ivaTotal;
                $dbData['ish']         = $ishTotal;
                $dbData['total']       = $totalVal;
            }

            $db = \Config\Database::connect();
            $db->table('registros')->where('id', $id)->update($dbData);

            return $this->response->setJSON(["success" => true, "msg" => "Registro actualizado"]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(["success" => false, "msg" => $e->getMessage()]);
        }
    }
    /* =====================================================
       ACTUALIZAR ESTADO DE HABITACION (S, X, M, P...)
       ===================================================== */
    public function actualizarEstadoHabitacion()
    {
        try {
            $json = $this->request->getJSON(true);
            $db = \Config\Database::connect();

            if (empty($json['habitacion_id']) || empty($json['status'])) {
                return $this->response->setJSON(["success" => false, "msg" => "Datos incompletos"]);
            }

            $habitacionId = $json['habitacion_id'];
            $status = $json['status']; // Código: S, X, M, P...
            $estadoId = $json['estado_id'] ?? null; // ID numérico opcional

            $updateData = ['status' => $status];
            if ($estadoId) {
                $updateData['estado_id'] = $estadoId;
            }

            $db->table('habitaciones')
               ->where('id', $habitacionId)
               ->update($updateData);

            return $this->response->setJSON(["success" => true, "msg" => "Estado de habitación actualizado"]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(["success" => false, "msg" => $e->getMessage()]);
        }
    }

}
