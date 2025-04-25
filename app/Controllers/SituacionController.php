<?php

namespace App\Controllers;

use App\Models\SituacionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait;

class SituacionController extends BaseController
{
    use ResponseTrait;

    protected $model;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->model = new SituacionModel();
    }

    /**
     * Lista todas las situaciones
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Situaciones',
            'situaciones' => $this->model->listarSituaciones()
        ];

        return view('situacion/index', $data);
    }

    /**
     * Muestra formulario de creación
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Nueva Situación',
            'validation' => service('validation')
        ];

        return view('situacion/nuevo', $data);
    }

    /**
     * Guarda una nueva situación
     */
    public function guardar()
    {
        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $nombre = $this->request->getPost('nombre');

        try {
            $this->model->save(['nombre' => $nombre]);
            return redirect()->to('/situaciones')->with('success', 'Situación creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra formulario de edición
     */
    public function editar($id)
    {
        $situacion = $this->model->find($id);

        if (!$situacion) {
            throw new PageNotFoundException('No se encontró la situación solicitada');
        }

        $data = [
            'title' => 'Editar Situación',
            'situacion' => $situacion,
            'validation' => service('validation')
        ];

        return view('situacion/editar', $data);
    }

    /**
     * Actualiza una situación existente
     */
    public function actualizar($id)
    {
        // Verificar si la situación existe
        $situacion = $this->model->find($id);
        if (!$situacion) {
            throw new PageNotFoundException('No se encontró la situación solicitada');
        }

        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $nombre = $this->request->getPost('nombre');

        try {
            $this->model->update($id, ['nombre' => $nombre]);
            return redirect()->to('/situaciones')->with('success', 'Situación actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una situación
     */
    public function eliminar($id)
    {
        $situacion = $this->model->find($id);
        if (!$situacion) {
            throw new PageNotFoundException('No se encontró la situación solicitada');
        }

        try {
            $this->model->delete($id);
            return redirect()->to('/situaciones')->with('success', 'Situación eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/situaciones')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene opciones para select (API)
     */
    public function getOpciones()
    {
        $opciones = $this->model->getOpcionesSituacion();
        return $this->respond($opciones);
    }

    /**
     * Verifica si una situación existe (API)
     */
    public function verificarSituacion()
    {
        $nombre = $this->request->getPost('nombre');
        $existe = $this->model->existeSituacion($nombre);

        return $this->respond(['existe' => $existe]);
    }
}
