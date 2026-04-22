<?php

namespace App\Controllers;
use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $session = session();
        $model = new UsuarioModel();

        $user = $model
            ->where('username',$this->request->getPost('username'))
            ->where('activo',1)
            ->first();

        if(!$user){
            return redirect()->back()->with('error','Usuario no encontrado');
        }

        if(!password_verify($this->request->getPost('password'), $user['password_hash'])){
            return redirect()->back()->with('error','Password incorrecto');
        }

        $session->set([
            'id' => $user['id'],
            'user_id' => $user['id'],
            'username' => $user['username'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'rol' => $user['rol_id'],
            'logged' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    
}