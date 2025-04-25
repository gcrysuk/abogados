<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password',
        'rol',
        'activo',
        'nombre',
        'domicilio',
        'telefono',
        'mail'
    ];

    protected $returnType = 'object';

    protected $validationRules = [
        'username' => 'required|min_length[4]|max_length[50]|is_unique[usuarios.username,id,{id}]',
        'password' => 'required|min_length[8]',
        'rol' => 'required|in_list[admin,abogado,cliente]',
        'nombre' => 'required|max_length[50]',
        'mail' => 'required|valid_email|max_length[50]',
        'telefono' => 'max_length[15]',
        'domicilio' => 'max_length[50]'
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Este nombre de usuario ya está registrado',
            'required' => 'El nombre de usuario es obligatorio'
        ],
        'password' => [
            'required' => 'La contraseña es obligatoria'
        ],
        'rol' => [
            'in_list' => 'Debe seleccionar un rol válido'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Verifica las credenciales de un usuario
     */
    public function verificarCredenciales($username, $password)
    {
        $usuario = $this->where('username', $username)
            ->where('activo', 1)
            ->first();

        if ($usuario && password_verify($password, $usuario->password)) {
            return $usuario;
        }

        return false;
    }

    /**
     * Obtiene usuarios por rol
     */
    public function getPorRol($rol)
    {
        return $this->where('rol', $rol)
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    /**
     * Activa/desactiva un usuario
     */
    public function cambiarEstado($id, $estado)
    {
        return $this->update($id, ['activo' => $estado]);
    }

    /**
     * Obtiene abogados activos para select
     */
    public function getAbogadosParaSelect()
    {
        $abogados = $this->where('rol', 'abogado')
            ->where('activo', 1)
            ->orderBy('nombre', 'ASC')
            ->findAll();

        $resultado = [];
        foreach ($abogados as $abogado) {
            $resultado[$abogado->id] = $abogado->nombre;
        }

        return $resultado;
    }
}
