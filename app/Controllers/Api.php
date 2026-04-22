<?php

namespace App\Controllers;
use App\Models\HabitacionModel;
use App\Models\RegistroModel;

class Api extends BaseController
{
    public function habitaciones()
    {
        $hab = new HabitacionModel();

        return $this->response->setJSON(
            $hab->findAll()
        );
    }

    public function registros()
    {
        $reg = new RegistroModel();

        return $this->response->setJSON(
            $reg->findAll()
        );
    }
}