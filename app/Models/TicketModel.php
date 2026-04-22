<?php

namespace App\Models;
use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'ticket_config';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre_establecimiento',
        'rfc',
        'telefono',
        'ancho_ticket',
        'logo_ticket',
        'copias',
        'mensaje_pie',
        'ver_num_habitacion',
        'ver_nombre_huesped',
        'ver_fecha_entrada',
        'ver_desglose',
        'ver_forma_pago',
        'ver_folio'
    ];
}