<?php

use App\Models\ConfiguracionModel;

function cfg($nombre)
{
    static $cache = [];

    if(isset($cache[$nombre]))
        return $cache[$nombre];

    $model = new ConfiguracionModel();
    $valor = $model->getValor($nombre);

    $cache[$nombre] = $valor;

    return $valor;
}

function id_cfg($nombre)
{
    $model = new \App\Models\ConfiguracionModel();

    $row = $model->where('nombre',$nombre)->first();

    return $row ? $row['id'] : 0;
}