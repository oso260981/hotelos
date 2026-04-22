<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Estado de Cuenta - <?= $folio['folio'] ?></title>
<style>
    /* Configuración de Página para PDF/Impresión */
    @page {
        margin: 0;
        size: A4;
    }
    
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 11px;
        line-height: 1.4;
        color: #1e293b;
        margin: 0;
        padding: 0;
        background-color: #ffffff;
    }

    .container {
        padding: 40px;
        position: relative;
    }

    /* SELLO PAGADO */
    .pagado-watermark {
        position: absolute;
        top: 350px;
        left: 150px;
        font-size: 100px;
        font-weight: 900;
        color: rgba(16, 185, 129, 0.12); /* Verde esmeralda muy tenue */
        transform: rotate(-30deg);
        border: 15px solid rgba(16, 185, 129, 0.12);
        padding: 10px 40px;
        border-radius: 20px;
        z-index: -1;
        text-transform: uppercase;
        letter-spacing: 10px;
    }

    /* HEADER */
    .header-table {
        width: 100%;
        margin-bottom: 30px;
    }

    .logo {
        width: 60px;
        margin-bottom: 10px;
    }

    .hotel-name {
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.5px;
        text-transform: uppercase;
    }

    .hotel-sub {
        font-size: 9px;
        color: #64748b;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .folio-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        text-align: right;
        min-width: 180px;
    }

    .folio-label {
        font-size: 9px;
        font-weight: 900;
        color: #4f46e5;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .folio-number {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
    }

    /* INFO CARD */
    .guest-card {
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 25px;
        background: linear-gradient(to right, #f8fafc, #ffffff);
    }

    .info-grid {
        width: 100%;
    }

    .info-label {
        font-size: 8px;
        font-weight: 900;
        color: #94a3b8;
        text-transform: uppercase;
        padding-bottom: 2px;
    }

    .info-value {
        font-size: 11px;
        font-weight: bold;
        color: #1e293b;
        padding-bottom: 8px;
    }

    /* TABLES */
    .section-title {
        font-size: 10px;
        font-weight: 900;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1px;
        border-left: 3px solid #4f46e5;
        padding-left: 8px;
    }

    table.data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table.data-table th {
        background-color: #0f172a;
        color: #ffffff;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 12px;
        text-align: left;
    }

    table.data-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 10px;
    }

    .badge {
        font-size: 8px;
        font-weight: bold;
        background-color: #eff6ff;
        color: #3b82f6;
        padding: 2px 6px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    /* TOTALS BOX */
    .footer-container {
        margin-top: 30px;
        width: 100%;
    }

    .totals-wrapper {
        width: 280px;
        float: right;
        background-color: #0f172a;
        color: #ffffff;
        border-radius: 16px;
        padding: 20px;
    }

    .total-row {
        width: 100%;
        margin-bottom: 5px;
    }

    .total-row td {
        border: none;
        padding: 2px 0;
        color: rgba(255,255,255,0.6);
        font-size: 10px;
    }

    .total-row td.val {
        text-align: right;
        color: #ffffff;
        font-weight: bold;
    }

    .grand-total {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .grand-total td {
        color: #ffffff;
        font-size: 14px;
        font-weight: 900;
    }

    .grand-total td.val {
        color: #10b981;
        font-size: 18px;
    }

    /* SIGNATURES */
    .signature-box {
        margin-top: 60px;
        width: 100%;
    }

    .sig-line {
        border-top: 1px solid #e2e8f0;
        width: 200px;
        padding-top: 5px;
        font-size: 8px;
        text-align: center;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: bold;
    }

    .disclaimer {
        margin-top: 40px;
        font-size: 8px;
        color: #94a3b8;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
</head>

<body>

<div class="container">
    
    <!-- MARCA DE AGUA -->
    <?php if(($folio['estado_registro'] ?? '') === 'CHECKOUT' || ($total <= 0)): ?>
        <div class="pagado-watermark">PAGADO</div>
    <?php endif; ?>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="60%">
                <div class="hotel-name">HOLELOS LUXURY</div>
                <div class="hotel-sub">HOSPITALITY MANAGEMENT SYSTEM</div>
                <p style="margin-top: 5px; font-size: 9px; color: #64748b;">
                    Av. Paseo de la Reforma 405, CDMX<br>
                    RFC: HOL-001215-E12 | Tel: (55) 5555-0123
                </p>
            </td>
            <td width="40%" align="right">
                <div class="folio-box">
                    <div class="folio-label">Estado de Cuenta Oficial</div>
                    <div class="folio-number">#<?= $folio['folio'] ?></div>
                    <div style="font-size: 9px; color: #64748b; margin-top: 5px;">
                        Generado: <?= date('d/m/Y H:i') ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- GUEST INFO -->
    <div class="guest-card">
        <table class="info-grid">
            <tr>
                <td width="50%">
                    <div class="info-label">Huésped Titular</div>
                    <div class="info-value"><?= $folio['Nombre_Huesped'] ?></div>
                </td>
                <td width="50%">
                    <div class="info-label">Unidad y Categoría</div>
                    <div class="info-value"><?= $folio['tip_habitacion'] ?> — Hab. #<?= $folio['Numero_Habitacion'] ?></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-label">Check-In (Entrada)</div>
                    <div class="info-value"><?= date('d/m/Y H:i', strtotime($folio['hora_entrada'])) ?></div>
                </td>
                <td>
                    <div class="info-label">Check-Out (Salida)</div>
                    <div class="info-value"><?= $folio['hora_salida'] ? date('d/m/Y H:i', strtotime($folio['hora_salida'])) : '---' ?></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- CONSUMOS -->
    <div class="section-title">Detalle de Consumos y Servicios</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Fecha</th>
                <th width="50%">Descripción del Concepto</th>
                <th width="15%">Depto.</th>
                <th width="20%" style="text-align:right">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $subtotal = 0;
            foreach($cargos as $c): 
                $subtotal += $c['monto'];
            ?>
            <tr>
                <td style="color: #64748b;"><?= date('d/m H:i', strtotime($c['fecha'])) ?></td>
                <td style="font-weight: bold;"><?= $c['descripcion'] ?></td>
                <td><span class="badge"><?= $c['departamento'] ?></span></td>
                <td style="text-align:right; font-weight: bold;">$<?= number_format($c['monto'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGOS -->
    <?php if(!empty($pagos)): ?>
    <div class="section-title">Historial de Pagos y Abonos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">Fecha</th>
                <th width="60%">Método / Referencia de Pago</th>
                <th width="20%" style="text-align:right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pagos as $p): ?>
            <tr>
                <td style="color: #64748b;"><?= date('d/m/Y', strtotime($p['fecha'])) ?></td>
                <td><?= $p['metodo'] ?></td>
                <td style="text-align:right; font-weight: bold; color: #10b981;">-$<?= number_format($p['monto'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- TOTALES -->
    <?php
    $iva = $subtotal * 0.16;
    $ish = $subtotal * 0.03;
    $total_bruto = $subtotal + $iva + $ish;
    $saldo_final = $total_bruto - $pagado;
    ?>

    <div class="footer-container">
        <table width="100%">
            <tr>
                <td width="60%" valign="bottom">
                    <!-- Firmas -->
                    <table class="signature-box">
                        <tr>
                            <td>
                                <div class="sig-line">Firma del Huésped</div>
                            </td>
                            <td>
                                <div class="sig-line">Auditoría / Recepción</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table class="totals-wrapper">
                        <tr class="total-row">
                            <td>Subtotal</td>
                            <td class="val">$<?= number_format($subtotal, 2) ?></td>
                        </tr>
                        <tr class="total-row">
                            <td>IVA (16%)</td>
                            <td class="val">$<?= number_format($iva, 2) ?></td>
                        </tr>
                        <tr class="total-row">
                            <td>ISH (3%)</td>
                            <td class="val">$<?= number_format($ish, 2) ?></td>
                        </tr>
                        <tr class="total-row">
                            <td style="color: #10b981;">Abonos realizados</td>
                            <td class="val" style="color: #10b981;">-$<?= number_format($pagado, 2) ?></td>
                        </tr>
                        <tr class="grand-total">
                            <td>SALDO FINAL</td>
                            <td class="val">$<?= number_format($saldo_final, 2) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="disclaimer">
        Este documento es un comprobante de estado de cuenta interno y no representa una factura fiscal electrónica (CFDI)
    </div>

</div>

</body>
</html>