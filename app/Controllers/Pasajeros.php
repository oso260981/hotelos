<?php

namespace App\Controllers;

use App\Models\HuespedModel;
use App\Models\EstanciaModel;
use App\Models\AcompananteModel;
use App\Models\RegistroAcompanantesModel;


class Pasajeros extends BaseController
{

    public function index()
    {
        return view('pasajeros/index');
    }

 
   /* ===== LISTADO OPERATIVO (pantalla tarjetas) ===== */
public function listado_operativo()
{
    $db = db_connect();

    /* ==== TITULARES ==== */
    $sqlTit = "
        SELECT
            r.id AS registro_id,
            'TITULAR' AS tipo,

            h.id AS huesped_id,
            h.nombre,
            h.apellido,
            h.numero_identificacion,
            ht.nombre AS tipo_identificacion,

            hab.numero,
            r.total,
            fp.codigo forma_pago,
			r.adultos + r.niños as  num_personas,

            r.lista_negra,
            r.cliente_frecuente,
            r.hora_entrada,
            r.hora_salida,

            0 AS es_menor,
            '' AS parentesco

        FROM registros r
        JOIN huespedes h 
            ON h.id = r.huesped_id
        LEFT JOIN huesped_identificaciones_tipos ht
            ON ht.id = h.tipo_identificacion_id
        JOIN habitaciones hab 
            ON hab.id = r.habitacion_id
         INNER JOIN formas_pago fp on r.forma_pago_id=fp.id   

        WHERE r.estado_registro ='CHECKIN'
    ";

    /* ==== ACOMPAÑANTES ==== */
    $sqlAcomp = "
        SELECT
            ra.registro_id,
            'ACOMP' AS tipo,

            0 AS huesped_id,
            ra.nombre,
            '' AS apellido,
            ra.numero_identificacion,
            ra.tipo_identificacion,

            hab.numero,
            0 AS total,
            '' AS forma_pago,
            0 AS num_personas,

            0 AS lista_negra,
            0 AS cliente_frecuente,
            NULL AS hora_entrada,
            NULL AS hora_salida,

            ra.es_menor,
            ra.parentesco

        FROM registro_acompanantes ra
        JOIN registros r 
            ON r.id = ra.registro_id
        JOIN habitaciones hab 
            ON hab.id = r.habitacion_id

       WHERE r.estado_registro ='CHECKIN'
    ";

    $titulares = $db->query($sqlTit)->getResultArray();
    $acomps    = $db->query($sqlAcomp)->getResultArray();

    /* ==== MERGE ==== */
    $lista = array_merge($titulares, $acomps);

    /* ==== ORDENAR POR HAB Y TIPO ==== */
    usort($lista, function($a,$b){

    if($a['numero'] == $b['numero']){

        /* TITULAR primero */
        if($a['tipo'] == $b['tipo']) return 0;
        if($a['tipo'] == 'TITULAR') return -1;
        return 1;
    }

    return $a['numero'] <=> $b['numero'];
});

    return $this->response->setJSON($lista);
}

    /* ===== GUARDAR HUESPED ===== */
   public function guardar_huesped()
{
    try {

        $model = new HuespedModel();
        $data = $this->request->getJSON(true);

        // 🔥 DEBUG EN LOG
        log_message('debug', 'DATA HUESPED: ' . json_encode($data));

        // 🔥 VALIDACIÓN
        if (!$data) {
            return $this->response->setJSON([
                'ok' => false,
                'msg' => 'No se recibieron datos'
            ]);
        }

        // 🔥 SAVE
        if (!$model->save($data)) {

            return $this->response->setJSON([
                'ok' => false,
                'msg' => 'Error al guardar',
                'errors' => $model->errors() // 🔥 AQUÍ ESTÁ LA CLAVE
            ]);
        }

        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Guardado correctamente'
        ]);

    } catch (\Throwable $e) {

        // 🔥 ERROR REAL
        log_message('error', $e->getMessage());

        return $this->response->setJSON([
            'ok' => false,
            'msg' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
}
  



    /* ===== OBTENER HUESPED ===== */
    public function obtener_huesped($id)
    {
        $model = new HuespedModel();
        return $this->response->setJSON(
            $model->find($id)
        );
    }

    public function acompanantes($registro_id)
{
    $model = new RegistroAcompanantesModel();

    $data = $model
        ->where('registro_id', $registro_id)       
        ->findAll();

    return $this->response->setJSON($data);
}

    /* ===== AGREGAR ACOMPAÑANTE ===== */
    public function guardar_acompanante()
    {
        $model = new AcompananteModel();
        $data = $this->request->getJSON(true);
        $model->insert($data);
        return $this->response->setJSON(['ok'=>true]);
    }

    public function catalogo()
{
    return view('pasajeros/pasajeros_catalogo');
}

public function catalogo_listado()
{
    $db = db_connect();

    $sql = "
        SELECT
            id,
            nombre,
            apellido,
            numero_identificacion,
            telefono,
            email,
            activo,
            fecha_alta
        FROM huespedes
        ORDER BY nombre, apellido
    ";

    return $this->response->setJSON(
        $db->query($sql)->getResultArray()
    );
}

public function catalogo_guardar()
{
    $db = db_connect();
    $data = $this->request->getJSON(true);

    if(empty($data['nombre'])){
        return $this->response->setJSON([
            'ok'=>false,
            'msg'=>'Nombre requerido'
        ]);
    }

    /* ===== NORMALIZAR ===== */
    $payload = [

        'nombre' => $data['nombre'] ?? null,
        'apellido' => $data['apellido'] ?? null,

        'tipo_identificacion_id' => $data['tipo_identificacion_id'] ?? null,
        'numero_identificacion' => $data['numero_identificacion'] ?? null,

        'telefono' => $data['telefono'] ?? null,
        'email' => $data['email'] ?? null,

        'direccion' => $data['direccion'] ?? null,
        'ciudad' => $data['ciudad'] ?? null,
        'estado' => $data['estado'] ?? null,
        'codigo_postal' => $data['codigo_postal'] ?? null,

        'pais' => $data['pais'] ?? null,
        'nacionalidad' => $data['nacionalidad'] ?? null,
        'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
        'genero' => $data['genero'] ?? null,

        'empresa' => $data['empresa'] ?? null,
        'notas' => $data['notas'] ?? null

    ];

    $db->transStart();

    /* ===== UPDATE ===== */
    if(!empty($data['id'])){

        $db->table('huespedes')
            ->where('id',$data['id'])
            ->update($payload);

        $db->transComplete();

        return $this->response->setJSON([
            'ok'=>true,
            'modo'=>'update'
        ]);
    }

    /* ===== VALIDAR DUPLICADO IDENTIFICACION ===== */
    if(!empty($payload['numero_identificacion'])){

        $dup = $db->table('huespedes')
            ->where('numero_identificacion',$payload['numero_identificacion'])
            ->where('activo',1)
            ->countAllResults();

        if($dup > 0){
            return $this->response->setJSON([
                'ok'=>false,
                'msg'=>'Ya existe un huésped con esa identificación'
            ]);
        }
    }

    /* ===== INSERT ===== */
    $payload['activo'] = 1;
    $payload['fecha_alta'] = date('Y-m-d H:i:s');

    $db->table('huespedes')->insert($payload);

    $db->transComplete();

    return $this->response->setJSON([
        'ok'=>true,
        'modo'=>'insert'
    ]);
}


public function catalogo_baja($id)
{
    $db = db_connect();

    $db->table('huespedes')
        ->where('id',$id)
        ->update(['activo'=>0]);

    return $this->response->setJSON(['ok'=>true]);
}

public function catalogo_tipos_identificacion()
{
    $db = db_connect();

    $sql = "
        SELECT id, nombre
        FROM huesped_identificaciones_tipos
        WHERE activo = 1
        ORDER BY nombre
    ";

    return $this->response->setJSON(
        $db->query($sql)->getResultArray()
    );
}

public function guardar_foto()
{
    $data = $this->request->getJSON(true);

    if(empty($data['id']) || empty($data['imagen'])){
        return $this->response->setJSON([
            'ok'=>false,
            'msg'=>'Datos incompletos'
        ]);
    }

    /* ===== limpiar base64 ===== */
    $img = $data['imagen'];
    $img = str_replace('data:image/jpeg;base64,','',$img);
    $img = base64_decode($img);

    /* ===== nombre archivo ===== */
    $nombre = 'huesped_'.$data['id'].'.jpg';
    $ruta = FCPATH.'uploads/huespedes/'.$nombre;

    file_put_contents($ruta,$img);

    /* ===== guardar en BD ===== */
    $db = db_connect();

    $db->table('huespedes')
        ->where('id',$data['id'])
        ->update([
            'fotografia'=>'uploads/huespedes/'.$nombre
        ]);

    return $this->response->setJSON([
        'ok'=>true,
        'ruta'=>'uploads/huespedes/'.$nombre
    ]);
}

public function ocr_documento()
{
    $data = $this->request->getJSON(true);

    $img = str_replace('data:image/jpeg;base64,','',$data['imagen']);
    $img = base64_decode($img);

    $tmp = FCPATH.'uploads/tmp_ocr.jpg';
    file_put_contents($tmp,$img);

    $cmd = '"C:\\Program Files\\Tesseract-OCR\\tesseract" "'.$tmp.'" stdout -l spa';

    $texto = shell_exec($cmd);

    return $this->response->setJSON([
        'ok'=>true,
        'texto'=>$texto
    ]);
}

 public function buscar()
{
    $q = $this->request->getGet('q');

    $db = \Config\Database::connect();

   $res = $db->table('huespedes')
    ->select("id, CONCAT(nombre,' ',apellido) AS nombre, telefono")
    ->groupStart()
        ->where("CONCAT(nombre,' ',apellido) LIKE", "%{$q}%")
        ->orWhere("telefono LIKE", "%{$q}%")
    ->groupEnd()
    ->limit(20)
    ->get()
    ->getResultArray();



    return $this->response->setJSON($res);
}

public function buscar_cliente()
{
    $q = $this->request->getGet('q');

    $db = \Config\Database::connect();

    $res = $db->table('huespedes')
        ->select("
            id,
            nombre,
            apellido,
            numero_identificacion,
            telefono,
            nacionalidad,
            direccion,
            ciudad,
            estado,
            codigo_postal,
            email,
            fecha_nacimiento,
            genero,
            tipo_identificacion_id,
            empresa,
            fotografia,
            identificacion,
            firma_path
        ")
        ->where('activo', 1)
        ->groupStart()
            ->like('nombre', $q)
            ->orLike('apellido', $q)
            ->orLike('numero_identificacion', $q)
            ->orLike('telefono', $q)
            ->orLike("CONCAT(nombre, ' ', apellido)", $q)
        ->groupEnd()
        ->limit(20)
        ->get()
        ->getResultArray();

    return $this->response->setJSON($res);
}

public function guardar_cliente()
{
    $data = $this->request->getJSON(true);

    if(empty($data)){
        return $this->response->setJSON([
            "ok" => false,
            "msg" => "No llegaron datos"
        ]);
    }

    $db = \Config\Database::connect();
    $builder = $db->table('huespedes');

    // 🔥 construir payload seguro
    $payload = [
        "nombre" => $data["nombre"] ?? null,
        "apellido" => $data["apellido"] ?? null,
        "fotografia" => $data["fotografia"] ?? null,
        "identificacion" => $data["identificacion"] ?? null,
        "firma_path" => $data["firma_path"] ?? null,

        "tipo_identificacion_id" =>
            !empty($data["tipo_identificacion_id"])
            ? $data["tipo_identificacion_id"]
            : null,

        "numero_identificacion" =>
            !empty($data["numero_identificacion"])
            ? $data["numero_identificacion"]
            : null,

        "telefono" =>
            !empty($data["telefono"])
            ? $data["telefono"]
            : null,

        "email" =>
            !empty($data["email"])
            ? $data["email"]
            : null,

        "direccion" =>
            !empty($data["direccion"])
            ? $data["direccion"]
            : null,

        "ciudad" =>
            !empty($data["ciudad"])
            ? $data["ciudad"]
            : null,

        "estado" =>
            !empty($data["estado"])
            ? $data["estado"]
            : null,

        "codigo_postal" =>
            !empty($data["codigo_postal"])
            ? $data["codigo_postal"]
            : null,

        "nacionalidad" =>
            !empty($data["nacionalidad"])
            ? $data["nacionalidad"]
            : null,

        "fecha_nacimiento" =>
            !empty($data["fecha_nacimiento"])
            ? $data["fecha_nacimiento"]
            : null,

        "genero" =>
            !empty($data["genero"])
            ? $data["genero"]
            : null,
        "empresa" =>
            !empty($data["empresa"])
            ? $data["empresa"]
            : null
    ];

    // 🔥 UPDATE
    if(!empty($data['id'])){

        $builder->where('id',$data['id'])
                ->update($payload);

        $id = $data['id'];

    }
    // 🔥 INSERT
    else{

        $builder->insert($payload);
        $id = $db->insertID();

    }

    return $this->response->setJSON([
        "ok" => true,
        "id" => $id
    ]);
}

public function guardarAcompanante()
{
    $data = $this->request->getJSON(true);

    $model = new RegistroAcompanantesModel();

    $model->save($data);

    return $this->response->setJSON([
        'ok' => true,
        'id' => $model->getInsertID()
    ]);
}

public function eliminarAcompanante($id)
{
    $model = new \App\Models\RegistroAcompanantesModel();

    if(!$model->find($id)){
        return $this->response->setJSON([
            'ok' => false,
            'msg' => 'Acompañante no existe'
        ]);
    }

    $model->delete($id);

    return $this->response->setJSON([
        'ok' => true
    ]);
}

public function verFoto($file)
{
    return $this->response->download(
        WRITEPATH . 'uploads/fotos/' . $file,
        null
    );
}
 

}