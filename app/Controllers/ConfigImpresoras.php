<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class ConfigImpresoras extends Controller
{

    /**
     * Obtiene impresoras instaladas en el servidor Windows
     */

    public function listarImpresoras()
{
       $cmd = 'powershell "Get-Printer | Select Name,PortName | ConvertTo-Json"';

    $output = shell_exec($cmd);

    $data = json_decode($output, true);

    return $this->response->setJSON($data);
}


}