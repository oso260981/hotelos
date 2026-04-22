<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket</title>

    <style>
    body {
        font-family: monospace;
        width: 280px;
        margin: 0;
    }

    .ticket {
        padding: 10px;
    }

    .center {
        text-align: center;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    .row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
    }
    </style>
</head>

<body onload="window.print()">

    <div class="ticket">

        <div class="center">
            <b>HOTEL FRONTDESK</b><br>
            Ticket de Registro
        </div>

        <div class="line"></div>

        <div class="row">
            <span>Folio:</span>
            <span><?= $folio ?></span>
        </div>

        <div class="row">
            <span>Habitación:</span>
            <span><?= $habitacion ?></span>
        </div>

        <div class="row">
            <span>Huésped:</span>
            <span><?= $huesped ?></span>
        </div>

        <div class="row">
            <span>Entrada:</span>
            <span><?= $entrada ?></span>
        </div>

        <div class="row">
            <span>Salida:</span>
            <span><?= $salida ?></span>
        </div>

        <div class="line"></div>

        <div class="row">
            <span>Total:</span>
            <span>$<?= number_format($total,2) ?></span>
        </div>

        <div class="row">
            <span>Pagado:</span>
            <span>$<?= number_format($pagado,2) ?></span>
        </div>

        <div class="row">
            <span>Saldo:</span>
            <span>$<?= number_format($saldo,2) ?></span>
        </div>

        <div class="line"></div>

        <div class="center">
            Gracias por su preferencia
        </div>

    </div>

</body>

</html>