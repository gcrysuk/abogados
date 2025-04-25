<?php

namespace App\Controllers;

use App\Models\EstadosModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EstadosController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new EstadosModel();
    }

    /**
     * Muestra la lista de todos los estados
     */
    public function index()
    {
        $data = [
            'title' => 'Listado de Estados',
            'estados' => $this->model->listarEstados()
        ];

        return view('estados/index', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo estado
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Agregar Nuevo Estado'
        ];

        return view('estados/nuevo', $data);
    }

    /**
     * Guarda un nuevo estado
     */
    public function guardar()
    {
        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $estado = $this->request->getPost('estado');

        // Intentar guardar
        try {
            $this->model->save(['estado' => $estado]);
            return redirect()->to('/estados')->with('success', 'Estado creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para editar un estado
     */
    public function editar($id)
    {
        $estado = $this->model->obtenerEstado($id);

        if (!$estado) {
            throw new PageNotFoundException('No se encontró el estado solicitado');
        }

        $data = [
            'title' => 'Editar Estado',
            'estado' => $estado
        ];

        return view('estados/editar', $data);
    }

    /**
     * Actualiza un estado existente
     */
    public function actualizar($id)
    {
        // Verificar si el estado existe
        $estado = $this->model->obtenerEstado($id);
        if (!$estado) {
            throw new PageNotFoundException('No se encontró el estado solicitado');
        }

        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $nuevoEstado = $this->request->getPost('estado');

        // Intentar actualizar
        try {
            $this->model->update($id, ['estado' => $nuevoEstado]);
            return redirect()->to('/estados')->with('success', 'Estado actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un estado
     */
    public function eliminar($id)
    {
        $estado = $this->model->obtenerEstado($id);
        if (!$estado) {
            throw new PageNotFoundException('No se encontró el estado solicitado');
        }

        try {
            $this->model->delete($id);
            return redirect()->to('/estados')->with('success', 'Estado eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->to('/estados')->with('error', 'Error al eliminar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si un estado existe (para AJAX)
     */
    public function verificarEstado()
    {
        $nombreEstado = $this->request->getPost('estado');
        $existe = $this->model->existeEstado($nombreEstado);

        return $this->response->setJSON(['existe' => $existe !== null]);
    }
}
