<?php

namespace App\Controllers;

use App\Models\RoomServiceProductoModel;
use CodeIgniter\RESTful\ResourceController;

class RoomService extends ResourceController
{
    protected $modelName = 'App\Models\RoomServiceProductoModel';
    protected $format    = 'json';

    // 🔹 GET /roomservice
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // 🔹 GET /roomservice/activos
    public function activos()
    {
        return $this->respond($this->model->getActivos());
    }

    // 🔹 GET /roomservice/front
    public function front()
    {
        return $this->respond($this->model->getForFront());
    }

    // 🔹 GET /roomservice/{id}
    public function show($id = null)
    {
        $data = $this->model->find($id);

        if (!$data) {
            return $this->failNotFound('Producto no encontrado');
        }

        return $this->respond($data);
    }

    // 🔹 POST /roomservice
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->model->insert($data)) {
            return $this->fail($this->model->errors());
        }

        return $this->respondCreated($data);
    }

    // 🔹 PUT /roomservice/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        if (!$this->model->update($id, $data)) {
            return $this->fail($this->model->errors());
        }

        return $this->respond(['message' => 'Actualizado correctamente']);
    }

    // 🔹 DELETE /roomservice/{id}
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Producto no encontrado');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['message' => 'Eliminado correctamente']);
    }

    public function servicios()
    {
        $db = \Config\Database::connect();

        $data = $db->table('room_service_productos')
            ->where('categoria', 'SERVICIO')
            ->where('activo', 1)
            ->limit(4)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $data
        ]);
    }


    public function buscar()
    {
        $q = trim($this->request->getGet('q'));

        if (empty($q)) {
            return $this->response->setJSON([
                "data" => []
            ]);
        }

        $db = \Config\Database::connect();

        $data = $db->table('room_service_productos')
            ->like('nombre', $q)
            ->where('activo', 1)
            ->limit(20)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            "data" => $data
        ]);
    }

    public function finalizarPedido()
    {
        $db = \Config\Database::connect();
        try {
            $json = $this->request->getJSON(true);
            $items = $json['items'] ?? [];
            $usuarioId = $json['usuario_id'] ?? null;
            $formaPagoId = $json['forma_pago_id'] ?? null;
            
            // Si viene registro_id, lo usamos. Si no viene o es 0, es venta independiente.
            $registroIdReal = isset($json['registro_id']) ? intval($json['registro_id']) : 0;
            $isVentaIndependiente = ($registroIdReal === 0);

            if (empty($items)) {
                throw new \Exception("No hay productos en el pedido");
            }

            $db->transException(true)->transStart();

            // 🔥 Usamos el registro_id recibido (será 0 si es Mostrador)
            $registroIdParaInsertar = $registroIdReal;

            $totalPedido = 0;

            // 🔥 Obtener el turno actual abierto
            $turno = $db->table('turnos_operacion')
                ->where('estado', 'ABIERTO')
                ->get()
                ->getRowArray();
            
            $turnoIdReal = $turno ? $turno['id'] : null;

            foreach ($items as $item) {
                $precio = floatval($item['precio'] ?? 0);
                $cantidad = intval($item['cantidad'] ?? 1);
                $subtotal = round($precio * $cantidad, 2);
                $totalPedido += $subtotal;

                $db->table('registro_room_service')->insert([
                    'registro_id'      => $registroIdParaInsertar,
                    'producto_id'      => !empty($item['id']) ? intval($item['id']) : 0,
                    'nombre_producto'  => $item['nombre'] ?? 'Producto S/N',
                    'precio_unitario'  => $precio,
                    'cantidad'         => $cantidad,
                    'subtotal'         => $subtotal,
                    'estado_cargo'     => 'ENTREGADO',
                    'hora_cargo'       => date('Y-m-d H:i:s'),
                    'hora_entrega'     => date('Y-m-d H:i:s'),
                    'usuario_id'       => $usuarioId,
                    'turno_id'         => $turnoIdReal,
                    'forma_pago_id'    => $formaPagoId,
                    'observaciones'    => $item['observaciones'] ?? ($isVentaIndependiente ? 'VENTA INDEPENDIENTE' : 'CARGO A HABITACIÓN'),
                    'created_at'       => date('Y-m-d H:i:s')
                ]);
            }

            // Si es cargo a habitación, también registramos en registro_cargos
            if (!$isVentaIndependiente) {
                $iva = round($totalPedido * 0.16, 2);
                $totalConIva = $totalPedido + $iva;

                $db->table('registro_cargos')->insert([
                    'registro_id'     => $registroIdReal,
                    'concepto'        => 'CONSUMO ROOM SERVICE',
                    'tipo'            => 'Extra',
                    'cantidad'        => 1,
                    'precio_unitario' => $totalPedido,
                    'subtotal'        => $totalPedido,
                    'iva'             => $iva,
                    'ish'             => 0,
                    'total'           => $totalConIva,
                    'aplica_iva'      => 1,
                    'aplica_ish'      => 0,
                    'departamento'    => 'ROOM SERVICE',
                    'estado'          => 'ACTIVO',
                    'created_at'      => date('Y-m-d H:i:s')
                ]);
            }

            $db->transComplete();

            return $this->response->setJSON([
                "ok" => true,
                "msg" => "Pedido finalizado correctamente",
                "total" => $totalPedido,
                "order_id" => $registroIdParaInsertar
            ]);

        } catch (\Throwable $e) {
            if ($db->transStatus() === false) { $db->transRollback(); }
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Error detallado: " . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}