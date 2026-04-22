<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TurnoOperacion extends Controller
{
    // =========================
    // 🔥 ABRIR TURNO
    // =========================
    public function abrir()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Sin datos"
            ]);
        }

        $turno_id   = $data['turno_id'] ?? null;
        $usuario_id = $data['usuario_id'] ?? null;

        if (!$turno_id || !$usuario_id) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Datos incompletos"
            ]);
        }

        // 🔥 validar si ya hay turno abierto hoy
        $existe = $db->table('turnos_operacion')
            ->where('fecha', date('Y-m-d'))
            ->where('estado', 'ABIERTO')
            ->countAllResults();

        if ($existe > 0) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "Ya hay un turno abierto"
            ]);
        }

        // 🔥 insertar turno real
        $db->table('turnos_operacion')->insert([
            'turno_id'          => $turno_id,
            'usuario_id'        => $usuario_id,
            'fecha'             => date('Y-m-d'),
            'hora_inicio_real'  => date('Y-m-d H:i:s'),
            'estado'            => 'ABIERTO',
            'observaciones'     => '',
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Turno iniciado correctamente"
        ]);
    }

    // =========================
    // 🔥 CERRAR TURNO
    // =========================
    public function cerrar()
    {
        $db = \Config\Database::connect();

        // 🔥 buscar turno abierto
        $turno = $db->table('turnos_operacion')
            ->where('fecha', date('Y-m-d'))
            ->where('estado', 'ABIERTO')
            ->get()
            ->getRowArray();

        if (!$turno) {
            return $this->response->setJSON([
                "ok" => false,
                "msg" => "No hay turno abierto"
            ]);
        }

        // 🔥 cerrar turno
        $db->table('turnos_operacion')
            ->where('id', $turno['id'])
            ->update([
                'estado'            => 'CERRADO',
                'hora_fin_real'     => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON([
            "ok" => true,
            "msg" => "Turno cerrado correctamente"
        ]);
    }

    // =========================
    // 🔥 VALIDAR TURNO ABIERTO
    // =========================
    public function turnoAbierto()
    {
        $db = \Config\Database::connect();

        $turno = $db->table('turnos_operacion')
            ->where('fecha', date('Y-m-d'))
            ->where('estado', 'ABIERTO')
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            "ok" => true,
            "data" => $turno
        ]);
    }
}