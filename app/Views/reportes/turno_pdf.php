<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Turno</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        .section { margin-bottom: 20px; }
        .section-title { background: #f3f4f6; padding: 5px 10px; font-weight: bold; border-left: 4px solid #2563eb; margin-bottom: 10px; text-transform: uppercase; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th, table td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        table th { background: #f9fafb; color: #4b5563; font-weight: bold; }
        
        .total-row { font-weight: bold; background: #fef2f2; }
        .amount { text-align: right; font-family: 'Courier', monospace; }
        
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; }
        .summary-grid { display: table; width: 100%; }
        .summary-item { display: table-cell; width: 50%; padding: 5px; }
        
        .diferencia-box { margin-top: 10px; padding: 10px; text-align: center; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .diferencia-ok { background: #dcfce7; color: #166534; }
        .diferencia-error { background: #fee2e2; color: #991b1b; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        
        .signature { margin-top: 50px; text-align: center; }
        .signature-line { width: 200px; border-top: 1px solid #000; margin: 0 auto; margin-top: 40px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>HOTEL OS</h1>
        <p>Reporte Oficial de Cierre de Turno</p>
    </div>

    <div class="section">
        <div class="section-title">Información del Turno</div>
        <table>
            <tr>
                <td><strong>Turno ID:</strong> <?= $r['turno_info']['id'] ?></td>
                <td><strong>Número de Turno:</strong> <?= $r['turno_info']['numero'] ?></td>
            </tr>
            <tr>
                <td><strong>Recepcionista:</strong> <?= $r['turno_info']['usuario'] ?></td>
                <td><strong>Fecha de Operación:</strong> <?= date('d/m/Y', strtotime($r['turno_info']['fecha'])) ?></td>
            </tr>
            <tr>
                <td><strong>Hora Apertura:</strong> <?= $r['turno_info']['hora_apertura'] ?></td>
                <td><strong>Hora Cierre:</strong> <?= $r['turno_info']['hora_cierre'] ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Estadísticas de Ocupación</div>
        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th style="text-align: center;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Check-ins realizados</td>
                    <td style="text-align: center;"><?= $r['ocupacion']['checkins'] ?></td>
                </tr>
                <tr>
                    <td>Check-outs realizados</td>
                    <td style="text-align: center;"><?= $r['ocupacion']['checkouts'] ?></td>
                </tr>
                <tr>
                    <td>Total personas registradas</td>
                    <td style="text-align: center;"><?= $r['ocupacion']['personas'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Resumen Financiero</div>
        <table>
            <thead>
                <tr>
                    <th>Departamento / Concepto</th>
                    <th class="amount">Monto Ingresado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Hospedaje (Efectivo)</td>
                    <td class="amount">$<?= number_format($r['finanzas']['hospedaje_efectivo'], 2) ?></td>
                </tr>
                <tr>
                    <td>Hospedaje (Tarjeta / Transferencia)</td>
                    <td class="amount">$<?= number_format($r['finanzas']['hospedaje_tarjeta'], 2) ?></td>
                </tr>
                <tr>
                    <td>Room Service (Efectivo)</td>
                    <td class="amount">$<?= number_format($r['finanzas']['room_service_efectivo'], 2) ?></td>
                </tr>
                <tr class="total-row">
                    <td>SUBTOTAL INGRESOS TURNO</td>
                    <td class="amount">$<?= number_format($r['finanzas']['hospedaje_efectivo'] + $r['finanzas']['hospedaje_tarjeta'] + $r['finanzas']['room_service_efectivo'], 2) ?></td>
                </tr>
                <tr>
                    <td style="color: #666; font-size: 10px;">IVA Acumulado (Hospedaje)</td>
                    <td class="amount" style="color: #666; font-size: 10px;">$<?= number_format($r['finanzas']['iva'], 2) ?></td>
                </tr>
                <tr>
                    <td style="color: #666; font-size: 10px;">ISH Acumulado (Hospedaje)</td>
                    <td class="amount" style="color: #666; font-size: 10px;">$<?= number_format($r['finanzas']['ish'], 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Arqueo de Caja (Efectivo)</div>
        <div class="summary-box">
            <div class="summary-grid">
                <div class="summary-item">
                    <strong>Fondo Inicial:</strong>
                </div>
                <div class="summary-item amount">
                    $<?= number_format($r['arqueo']['fondo_inicial'], 2) ?>
                </div>
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <strong>(+) Total Entradas Efectivo:</strong>
                </div>
                <div class="summary-item amount">
                    $<?= number_format($r['finanzas']['hospedaje_efectivo'] + $r['finanzas']['room_service_efectivo'], 2) ?>
                </div>
            </div>
            <div class="summary-grid" style="border-top: 1px solid #cbd5e1; margin-top: 5px; padding-top: 5px;">
                <div class="summary-item">
                    <strong>EFECTIVO ESPERADO:</strong>
                </div>
                <div class="summary-item amount">
                    <strong>$<?= number_format($r['arqueo']['efectivo_esperado'], 2) ?></strong>
                </div>
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <strong>EFECTIVO REAL ENTREGADO:</strong>
                </div>
                <div class="summary-item amount">
                    <strong>$<?= number_format($r['arqueo']['efectivo_real'], 2) ?></strong>
                </div>
            </div>
            
            <?php 
                $dif = $r['arqueo']['diferencia'];
                $class = ($dif == 0) ? 'diferencia-ok' : (($dif > 0) ? 'diferencia-ok' : 'diferencia-error');
                $texto = ($dif == 0) ? 'CUADRE PERFECTO' : (($dif > 0) ? 'SOBRANTE EN CAJA' : 'FALTANTE EN CAJA');
            ?>
            <div class="diferencia-box <?= $class ?>">
                <?= $texto ?>: $<?= number_format($dif, 2) ?>
            </div>
        </div>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <p>Firma del Responsable</p>
        <p style="font-size: 9px; color: #999;"><?= $r['turno_info']['usuario'] ?></p>
    </div>

    <div class="footer">
        Generado automáticamente por HotelOS PMS - <?= date('d/m/Y H:i:s') ?>
    </div>

</body>
</html>
