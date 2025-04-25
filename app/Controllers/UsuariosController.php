<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait;

class UsuariosController extends BaseController
{
    use ResponseTrait;

    protected $model;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->model = new UsuariosModel();
    }

    /**
     * Lista todos los usuarios
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $this->model->orderBy('nombre', 'ASC')->findAll(),
            'roles' => ['admin', 'abogado', 'cliente']
        ];

        return view('usuarios/index', $data);
    }

    /**
     * Muestra formulario de creación
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Nuevo Usuario',
            'validation' => service('validation'),
            'roles' => ['admin', 'abogado', 'cliente']
        ];

        return view('usuarios/nuevo', $data);
    }

    /**
     * Guarda un nuevo usuario
     */
    public function guardar()
    {
        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'rol' => $this->request->getPost('rol'),
            'activo' => $this->request->getPost('activo') ? 1 : 0,
            'nombre' => $this->request->getPost('nombre'),
            'mail' => $this->request->getPost('mail'),
            'telefono' => $this->request->getPost('telefono'),
            'domicilio' => $this->request->getPost('domicilio')
        ];

        try {
            $this->model->save($data);
            return redirect()->to('/usuarios')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * Muestra formulario de edición
     */
    public function editar($id)
    {
        $usuario = $this->model->find($id);

        if (!$usuario) {
            throw new PageNotFoundException('Usuario no encontrado');
        }

        $data = [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'validation' => service('validation'),
            'roles' => ['admin', 'abogado', 'cliente']
        ];

        return view('usuarios/editar', $data);
    }

    /**
     * Actualiza un usuario existente
     */
    public function actualizar($id)
    {
        // Verificar si el usuario existe
        $usuario = $this->model->find($id);
        if (!$usuario) {
            throw new PageNotFoundException('Usuario no encontrado');
        }

        // Reglas de validación (excluir password si no se cambia)
        $rules = $this->model->validationRules;
        if (empty($this->request->getPost('password'))) {
            unset($rules['password']);
        }

        // Validar los datos
        if (!$this->validate($rules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $data = [
            'username' => $this->request->getPost('username'),
            'rol' => $this->request->getPost('rol'),
            'activo' => $this->request->getPost('activo') ? 1 : 0,
            'nombre' => $this->request->getPost('nombre'),
            'mail' => $this->request->getPost('mail'),
            'telefono' => $this->request->getPost('telefono'),
            'domicilio' => $this->request->getPost('domicilio')
        ];

        // Solo actualizar password si se proporcionó uno nuevo
        if (!empty($this->request->getPost('password'))) {
            $data['password'] = $this->request->getPost('password');
        }

        try {
            $this->model->update($id, $data);
            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Cambia el estado de un usuario (activo/inactivo)
     */
    public function cambiarEstado($id)
    {
        $usuario = $this->model->find($id);
        if (!$usuario) {
            throw new PageNotFoundException('Usuario no encontrado');
        }

        $nuevoEstado = $usuario->activo ? 0 : 1;

        try {
            $this->model->cambiarEstado($id, $nuevoEstado);
            return redirect()->to('/usuarios')->with('success', 'Estado del usuario actualizado');
        } catch (\Exception $e) {
            return redirect()->to('/usuarios')->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene usuarios por rol (API)
     */
    public function porRol($rol)
    {
        $usuarios = $this->model->getPorRol($rol);
        return $this->respond($usuarios);
    }

    /**
     * Obtiene abogados para select (API)
     */
    public function abogadosSelect()
    {
        $abogados = $this->model->getAbogadosParaSelect();
        return $this->respond($abogados);
    }

    /**
     * Verifica si username existe (API)
     */
    public function verificarUsername()
    {
        $username = $this->request->getPost('username');
        $existe = $this->model->where('username', $username)->first() !== null;

        return $this->respond(['existe' => $existe]);
    }
}
