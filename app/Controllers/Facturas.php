<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class Facturas extends BaseController
{
    public function pdf($registroId = null)
    {
        if (!$registroId) {
            return $this->response->setStatusCode(400)->setBody('ID requerido');
        }

        $db = \Config\Database::connect();

        // =========================
        // 🔹 DATOS
        // =========================
        $folio = $db->query("
            SELECT *
            FROM vw_trabajo
            WHERE id_reservacion = ?
        ", [$registroId])->getRowArray();

        if (!$folio) {
            return $this->response->setStatusCode(404)->setBody('Registro no encontrado');
        }

        $cargos = $db->query("
            SELECT 
                created_at AS fecha,
                concepto AS descripcion,
                departamento,
                subtotal AS monto
            FROM registro_cargos
            WHERE registro_id = ?
            ORDER BY created_at ASC
        ", [$registroId])->getResultArray();

        $pagos = $db->query("
            SELECT 
                hora_pago AS fecha,
                CONCAT(concepto, ' (', forma_pago_id, ')') AS metodo,
                monto
            FROM registro_pagos
            WHERE registro_id = ?
            AND tipo_movimiento = 'PAGO'
            ORDER BY hora_pago ASC
        ", [$registroId])->getResultArray();

        // =========================
        // 🔹 TOTALES
        // =========================
        $subtotal = array_sum(array_column($cargos, 'monto'));
        $pagado   = array_sum(array_column($pagos, 'monto'));

        $iva = $subtotal * 0.16;
        $ish = $subtotal * 0.03;

        $total = $subtotal + $iva + $ish - $pagado;

        // =========================
        // 🔥 HTML (DISEÑO COMPLETO)
        // =========================
        ob_start();
        ?>

        <style>
        body {
            font-family: DejaVu Sans, Arial;
            font-size: 10px;
            color: #0f172a;
        }

        /* HEADER */
        .header {
            width: 100%;
            margin-bottom: 15px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .folio-box {
            text-align: right;
            border: 1px solid #dbeafe;
            padding: 8px;
            background: #eff6ff;
        }

        /* SECTION */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            border-left: 4px solid #2563eb;
            padding-left: 8px;
            margin: 15px 0 8px;
        }

        /* TABLE WRAPPER */
        .table-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0f172a;
            color: white;
            padding: 8px;
            font-size: 10px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .right {
            text-align: right;
            font-weight: bold;
        }

        .negativo {
            color: #dc2626;
        }

        .pago {
            color: #16a34a;
            font-weight: bold;
        }

        /* BADGES */
        .badge {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
            background: #e2e8f0;
        }

        .badge.servicio {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge.sistema {
            background: #fef3c7;
            color: #92400e;
        }

        /* TOTALES */
        .totales {
            width: 260px;
            float: right;
            margin-top: 10px;
        }

        .totales td {
            padding: 4px;
        }

        .total-final {
            font-size: 14px;
            font-weight: bold;
        }

        /* WATERMARK */
        .pagado {
            position: fixed;
            top: 40%;
            left: 25%;
            font-size: 60px;
            color: rgba(0,150,0,0.08);
        }
        </style>

        <?php if ($total <= 0): ?>
            <div class="pagado">PAGADO</div>
        <?php endif; ?>

        <!-- HEADER -->
        <table class="header">
            <tr>
                <td width="60%">
                    <div class="title">HOLELOS LUXURY</div>
                </td>
                <td width="40%">
                    <div class="folio-box">
                        <strong>ESTADO DE CUENTA</strong><br>
                        #<?= $folio['folio'] ?><br>
                        <?= date('d/m/Y H:i') ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- INFO -->
        <div class="table-card">
            <table>
                <tr>
                    <td><strong>Huésped:</strong></td>
                    <td><?= $folio['Nombre_Huesped'] ?></td>

                    <td><strong>Habitación:</strong></td>
                    <td><?= $folio['Numero_Habitacion'] ?></td>
                </tr>
                <tr>
                    <td><strong>Entrada:</strong></td>
                    <td><?= $folio['hora_entrada'] ?></td>

                    <td><strong>Salida:</strong></td>
                    <td><?= $folio['hora_salida'] ?: '---' ?></td>
                </tr>
            </table>
        </div>

        <!-- CONSUMOS -->
        <div class="section-title">DETALLE DE CONSUMOS Y SERVICIOS</div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Depto</th>
                        <th class="right">Importe</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cargos as $c): ?>
                    <tr>
                        <td><?= date('d/m H:i', strtotime($c['fecha'])) ?></td>
                        <td><?= $c['descripcion'] ?></td>
                        <td>
                            <span class="badge <?= strtolower($c['departamento']) ?>">
                                <?= $c['departamento'] ?>
                            </span>
                        </td>
                        <td class="right <?= $c['monto'] < 0 ? 'negativo' : '' ?>">
                            $<?= number_format($c['monto'], 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGOS -->
        <div class="section-title">HISTORIAL DE PAGOS Y ABONOS</div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método / Referencia</th>
                        <th class="right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pagos as $p): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($p['fecha'])) ?></td>
                        <td><?= $p['metodo'] ?></td>
                        <td class="right pago">
                            -$<?= number_format($p['monto'], 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- TOTALES -->
        <table class="totales">
            <tr>
                <td>Subtotal</td>
                <td class="right">$<?= number_format($subtotal,2) ?></td>
            </tr>
            <tr>
                <td>IVA</td>
                <td class="right">$<?= number_format($iva,2) ?></td>
            </tr>
            <tr>
                <td>ISH</td>
                <td class="right">$<?= number_format($ish,2) ?></td>
            </tr>
            <tr>
                <td>Pagos</td>
                <td class="right">-$<?= number_format($pagado,2) ?></td>
            </tr>
            <tr class="total-final">
                <td>TOTAL</td>
                <td class="right">$<?= number_format($total,2) ?></td>
            </tr>
        </table>

        <?php
        $html = ob_get_clean();

        // =========================
        // 🔥 DOMPDF
        // =========================
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
    }
}