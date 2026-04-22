<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class ConfigPerifericos extends Controller
{


public function guardarConfig()
{
    $model = new \App\Models\ConfigPerifericosModel();

    $data = $this->request->getJSON(true);

    foreach ($data as $item) {

        $model->where("clave", $item["clave"])
              ->set([
                    "activo" => $item["activo"],
                    "nombre_detectado" => $item["nombre"],
                    "fecha_actualiza" => date("Y-m-d H:i:s")
              ])
              ->update();

        if ($model->affectedRows() == 0) {
            $model->insert([
                "clave" => $item["clave"],
                "activo" => $item["activo"],
                "nombre_detectado" => $item["nombre"],
                "fecha_actualiza" => date("Y-m-d H:i:s")
            ]);
        }
    }

    return $this->response->setJSON(["ok"=>true]);
}


    public function detectarUsb()
    {
        // obtiene dispositivos USB conectados
        $cmd = 'powershell "Get-PnpDevice -PresentOnly | Where-Object {$_.InstanceId -match \'USB\'} | Select FriendlyName | ConvertTo-Json"';

        $output = shell_exec($cmd);

        $data = json_decode($output, true);

        return $this->response->setJSON($data);
    }

    public function detectarCamaras()
    {
        $cmd = 'powershell "Get-PnpDevice -Class Camera | Select FriendlyName | ConvertTo-Json"';

        $output = shell_exec($cmd);

        $data = json_decode($output, true);

        return $this->response->setJSON($data);
    }

    public function detectarDiscos()
    {
        $cmd = 'powershell "Get-WmiObject Win32_LogicalDisk | Where {$_.DriveType -eq 2} | Select DeviceID | ConvertTo-Json"';

        $output = shell_exec($cmd);

        $data = json_decode($output, true);

        return $this->response->setJSON($data);
    }

}



