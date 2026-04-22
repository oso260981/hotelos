<?php

namespace App\Controllers;

use App\Models\RegistroCargoModel;
use CodeIgniter\Controller;

class RegistroCargo extends Controller
{
    protected $cargoModel;

    public function __construct()
    {
        $this->cargoModel = new RegistroCargoModel();
    }

    // =========================
    // 💾 GUARDAR CARGO
    // =========================
 public function guardar()
{
    try {

        $json = $this->request->getJSON(true);

        // =========================
        // 🔒 VALIDACIONES
        // =========================
        if (empty($json['registro_id'])) {
            throw new \Exception("registro_id requerido");
        }

        $cantidad = (int)($json['cantidad'] ?? 1);
        $precio_unitario = (float)($json['precio_unitario'] ?? 0);
        $registro_id = $json['registro_id'];

        if ($cantidad <= 0) {
            throw new \Exception("Cantidad inválida");
        }

        if ($precio_unitario <= 0) {
            throw new \Exception("Precio unitario inválido");
        }

        // =========================
        // 🧠 BASE
        // =========================
        $subtotal = round($precio_unitario * $cantidad, 2);

        // =========================
        // ⚙️ IMPUESTOS
        // =========================
        $IVA_RATE = 0.16;
        $ISH_RATE = 0.03;

        $tipo = $json['tipo'] ?? 'Extra';

        // 🔥 lógica fiscal
        $aplicaIVA = 1;
        $aplicaISH = in_array($tipo, ['Hospedaje', 'Persona Extra']) ? 1 : 0;

        $iva = $aplicaIVA ? round($subtotal * $IVA_RATE, 2) : 0;
        $ish = $aplicaISH ? round($subtotal * $ISH_RATE, 2) : 0;

        // =========================
        // 💰 TOTAL
        // =========================
        $total = round($subtotal + $iva + $ish, 2);

        // =========================
        // 💾 INSERT
        // =========================
        $this->cargoModel->insert([
            'registro_id'     => $json['registro_id'],
            'concepto'        => $json['concepto'] ?? 'Cargo',
            'tipo'            => $tipo,
            'departamento'    => $json['departamento'] ?? 'Sistema',

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

          $this->recalcularTotalesRegistro($registro_id);

        // =========================
        // 📤 RESPONSE
        // =========================
        return $this->response->setJSON([
            "ok" => true,
            "data" => [
                "cantidad" => $cantidad,
                "precio_unitario" => $precio_unitario,
                "subtotal" => $subtotal,
                "iva" => $iva,
                "ish" => $ish,
                "total" => $total
            ]
        ]);

       

    } catch (\Throwable $e) {

        return $this->response->setJSON([
            "ok" => false,
            "msg" => $e->getMessage()
        ]);
    }

   
}

    // =========================
    // 📊 LISTAR CARGOS
    // =========================
    public function listar($registro_id)
    {
        try {

            $data = $this->cargoModel->getByRegistro($registro_id);

            return $this->response->setJSON([
                "ok" => true,
                "data" => $data
            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([
                "ok" => false,
                "msg" => $e->getMessage()
            ]);
        }
    }

    // =========================
    // 💰 TOTAL CARGOS
    // =========================
    public function total($registro_id)
    {
        try {

            $total = $this->cargoModel->getTotalByRegistro($registro_id);

            return $this->response->setJSON([
                "ok" => true,
                "total" => $total['total']
            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([
                "ok" => false,
                "msg" => $e->getMessage()
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

        $db = \Config\Database::connect();

        // =========================
        // 🔎 OBTENER CARGO
        // =========================
        $cargo = $db->table('registro_cargos')
            ->where('id', $json['id'])
            ->get()
            ->getRowArray();

        if (!$cargo) {
            throw new \Exception("Cargo no encontrado");
        }

        // =========================
        // ❌ CANCELAR (NO BORRAR)
        // =========================
        $updated = $db->table('registro_cargos')
            ->where('id', $json['id'])
            ->update([
                'estado' => 'CANCELADO'
            ]);

        if (!$updated) {
            throw new \Exception("No se pudo cancelar el cargo");
        }

        // 🔥 RECALCULAR TOTALES
        $this->recalcularTotalesRegistro($cargo['registro_id']);

        // =========================
        // 🧾 LOG
        // =========================
        $db->table('registro_logs')->insert([
            'fecha' => date('Y-m-d H:i:s'),
            'tipo' => 'cargo',
            'id_referencia' => $json['id'],
            'motivo' => $json['motivo'] ?? 'Sin motivo'
        ]);

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



}