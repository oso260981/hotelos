<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;

/**
 * CRUD completo Usuarios HotelOS
 */
class Usuarios extends BaseController
{

    /**
     * LISTADO AJAX
     */
    public function list()
    {
        $model = new UsuarioModel();

        $data = $model
    ->select('
        usuarios.*,
        roles.nombre as rol,
        roles.recepcion,
        roles.reportes,
        roles.editar_sistema
    ')
    ->join('roles','roles.id = usuarios.rol_id','left')
   // ->where('usuarios.activo',1)
    ->findAll();


        return $this->response->setJSON($data);
    }


    /**
     * GUARDAR (alta o edición)
     */
    public function save()
    {
        $model = new UsuarioModel();

        $id = $this->request->getPost('id');

        $data = [
            'username' => $this->request->getPost('username'),
            'nombre'   => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion'=> $this->request->getPost('direccion'),
            'rol_id'   => $this->request->getPost('rol_id'),
            'activo'   => 1
        ];

        // password nueva solo si viene
        $pass = $this->request->getPost('password');

        if($pass){
            $data['password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        if($id){
            $model->update($id,$data);
        }else{

            // password por default si no envían
            if(!$pass){
                $data['password_hash'] = password_hash('1234', PASSWORD_DEFAULT);
            }

            $model->insert($data);
        }

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }


    /**
     * ELIMINAR (soft delete)
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        $model = new UsuarioModel();

        $model->update($id,[
            'activo'=>0
        ]);

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }


    /**
     * CAMBIAR PASSWORD
     */
    public function changePassword()
    {
        $id = $this->request->getPost('id');
        $new = $this->request->getPost('password');

        $model = new UsuarioModel();

        $model->update($id,[
            'password_hash'=>password_hash($new,PASSWORD_DEFAULT)
        ]);

        return $this->response->setJSON([
            "ok"=>true
        ]);
    }

    public function toggle()
{
    $id = $this->request->getPost('id');
    $activo = $this->request->getPost('activo');

    $model = new UsuarioModel();

    $model->update($id,[
        'activo'=>$activo
    ]);

    return $this->response->setJSON([
        "ok"=>true
    ]);
}


public function recepcionistas()
    {
        $db = \Config\Database::connect();

        try {

            $usuarios = $db->table('usuarios u')
                ->select('u.id, u.username, u.nombre, u.apellido')
                ->join('roles r', 'r.id = u.rol_id')
                ->where('u.activo', 1)
                ->where('r.activo', 1)
                ->where('r.recepcion', 1) // 🔥 clave
                ->orderBy('u.nombre', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'ok' => true,
                'data' => $usuarios
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'ok' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }


}
