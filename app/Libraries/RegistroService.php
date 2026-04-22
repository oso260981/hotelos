namespace App\Libraries;

class RegistroService
{
    public function recalcularTotales($registro_id)
    {
        $db = \Config\Database::connect();

        $row = $db->table('registro_cargos')
            ->select("
                SUM(subtotal) as subtotal,
                SUM(iva) as iva,
                SUM(ish) as ish,
                SUM(total) as total
            ")
            ->where('registro_id', $registro_id)
            ->where('estado', 'ACTIVO')
            ->get()
            ->getRow();

        $subtotal = $row->subtotal ?? 0;
        $iva = $row->iva ?? 0;
        $ish = $row->ish ?? 0;
        $total = $row->total ?? 0;

        $db->table('registros')
            ->where('id', $registro_id)
            ->update([
                'precio' => $subtotal,
                'iva'    => $iva,
                'ish'    => $ish,
                'total'  => $total
            ]);

        return true;
    }
}