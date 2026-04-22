<?php

/* =====================================================
   MODELO: TicketConfigModel
   Maneja catálogo de configuraciones de ticket
=====================================================*/

namespace App\Models;
use CodeIgniter\Model;

class TicketConfigModel extends Model
{
    protected $table = 'ticket_config';
    protected $primaryKey = 'id';

    protected $allowedFields = [
    'nombre',
    'descripcion',
    'razon_social_id',
    'ancho_mm',
    'copias',
    'logo_visible',
    'mensaje_pie',
    'ver_habitacion',
    'ver_huesped',
    'ver_fecha',
    'ver_desglose',
    'ver_pago',
    'ver_folio',
    'activo'
];


}
