<?php

namespace App\Controllers;

use App\Models\RegistroPagosModel;
use CodeIgniter\RESTful\ResourceController;

class RegistroPagos extends ResourceController
{
    protected $modelName = 'App\Models\RegistroPagosModel';
    protected $format    = 'json';

    // ⭐ listar ledger
    public function porRegistro($registro_id)
    {
        $data = $this->model->getByRegistro($registro_id);

        return $this->respond($data);
    }

    // ⭐ crear movimiento
    public function guardarPago()
    {
        $input = $this->request->getJSON(true);

        $id = $this->model->insert([
            'registro_id'      => $input['registro_id'],
            'forma_pago_id'    => $input['forma_pago_id'],
            'monto'            => $input['monto'],
            'concepto'         => $input['concepto'],
            'hora_pago'        => date('Y-m-d H:i:s'),
            'referencia_pago'  => $input['referencia_pago'] ?? null,
            'banco'            => $input['banco'] ?? null,
            'estado'           => $input['estado'] ?? 'Cargado',
            'sistema'          => $input['sistema'] ?? 'RECEPCION',
            'usuario_id'       => session('user_id') ?? null,
            'observaciones'    => $input['observaciones'] ?? null,
            'tipo'             => $input['concepto'],
            'tipo_movimiento'  => $input['tipo_movimiento']
        ]);

        return $this->respond([
            'ok' => true,
            'id' => $id
        ]);
    }

    public function cancelar()
{
    $json = $this->request->getJSON(true);

    $db = \Config\Database::connect();

    // cancelar pago
    $db->table('registro_pagos')
        ->where('id', $json['id'])
        ->update([
            'estado' => 'CANCELADO'
        ]);

    // guardar log
    $db->table('registro_logs')->insert([
        'fecha' => date('Y-m-d H:i:s'),
        'tipo' => $json['tipo'],
        'id_referencia' => $json['id'],
        'motivo' => $json['motivo']
    ]);

    return $this->response->setJSON([
        "ok" => true
    ]);
}

    // ⭐ resumen financiero
    public function resumen($registro_id)
    {
        return $this->respond([
            'cargos' => $this->model->totalCargos($registro_id),
            'pagos'  => $this->model->totalPagos($registro_id),
            'saldo'  => $this->model->saldo($registro_id)
        ]);
    }

    


}