<meta charset="UTF-8">
<table>
    <tr>
        <th colspan="2" style="font-size: 16px; font-weight: bold;">HOTEL OS - REPORTE DE TURNO</th>
    </tr>
    <tr>
        <td>ID Turno:</td>
        <td><?= $r['turno_info']['id'] ?></td>
    </tr>
    <tr>
        <td>Número:</td>
        <td><?= $r['turno_info']['numero'] ?></td>
    </tr>
    <tr>
        <td>Fecha:</td>
        <td><?= $r['turno_info']['fecha'] ?></td>
    </tr>
    <tr>
        <td>Apertura:</td>
        <td><?= $r['turno_info']['hora_apertura'] ?></td>
    </tr>
    <tr>
        <td>Cierre:</td>
        <td><?= $r['turno_info']['hora_cierre'] ?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
        <th colspan="2" style="background: #eee;">ESTADÍSTICAS</th>
    </tr>
    <tr>
        <td>Check-ins:</td>
        <td><?= $r['ocupacion']['checkins'] ?></td>
    </tr>
    <tr>
        <td>Check-outs:</td>
        <td><?= $r['ocupacion']['checkouts'] ?></td>
    </tr>
    <tr>
        <td>Total Personas:</td>
        <td><?= $r['ocupacion']['personas'] ?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
        <th colspan="2" style="background: #eee;">FINANZAS</th>
    </tr>
    <tr>
        <td>Hospedaje Efectivo:</td>
        <td><?= $r['finanzas']['hospedaje_efectivo'] ?></td>
    </tr>
    <tr>
        <td>Hospedaje Tarjeta:</td>
        <td><?= $r['finanzas']['hospedaje_tarjeta'] ?></td>
    </tr>
    <tr>
        <td>Room Service Efectivo:</td>
        <td><?= $r['finanzas']['room_service_efectivo'] ?></td>
    </tr>
    <tr>
        <td><strong>Total Ingresos:</strong></td>
        <td><?= $r['finanzas']['hospedaje_efectivo'] + $r['finanzas']['hospedaje_tarjeta'] + $r['finanzas']['room_service_efectivo'] ?></td>
    </tr>
    <tr>
        <td>IVA:</td>
        <td><?= $r['finanzas']['iva'] ?></td>
    </tr>
    <tr>
        <td>ISH:</td>
        <td><?= $r['finanzas']['ish'] ?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
        <th colspan="2" style="background: #eee;">ARQUEO DE CAJA</th>
    </tr>
    <tr>
        <td>Fondo Inicial:</td>
        <td><?= $r['arqueo']['fondo_inicial'] ?></td>
    </tr>
    <tr>
        <td>Entradas Efectivo:</td>
        <td><?= $r['finanzas']['hospedaje_efectivo'] + $r['finanzas']['room_service_efectivo'] ?></td>
    </tr>
    <tr>
        <td><strong>Efectivo Esperado:</strong></td>
        <td><?= $r['arqueo']['efectivo_esperado'] ?></td>
    </tr>
    <tr>
        <td><strong>Efectivo Real:</strong></td>
        <td><?= $r['arqueo']['efectivo_real'] ?></td>
    </tr>
    <tr>
        <td><strong>Diferencia:</strong></td>
        <td><?= $r['arqueo']['diferencia'] ?></td>
    </tr>
</table>
