<?php

namespace App\Controllers;

use App\Models\JuzgadosModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class JuzgadosController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new JuzgadosModel();
        helper('form');
    }

    /**
     * Lista todos los juzgados
     */
    public function index()
    {
        $data = [
            'title' => 'Listado de Juzgados',
            'juzgados' => $this->model->getJuzgados(),
            'fueros' => $this->model->distinct()->select('fuero')->findAll()
        ];

        return view('juzgados/index', $data);
    }

    /**
     * Filtra juzgados por fuero
     */
    public function porFuero($fuero)
    {
        $data = [
            'title' => "Juzgados del fuero $fuero",
            'juzgados' => $this->model->getByFuero($fuero),
            'fueros' => $this->model->distinct()->select('fuero')->findAll(),
            'fuero_actual' => $fuero
        ];

        return view('juzgados/index', $data);
    }

    /**
     * Muestra formulario de creación
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Agregar Nuevo Juzgado'
        ];

        return view('juzgados/nuevo', $data);
    }

    /**
     * Guarda un nuevo juzgado
     */
    public function guardar()
    {
        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'fuero' => $this->request->getPost('fuero')
        ];

        // Intentar guardar
        try {
            $this->model->save($data);
            return redirect()->to('/juzgados')->with('success', 'Juzgado creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el juzgado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra formulario de edición
     */
    public function editar($id)
    {
        $juzgado = $this->model->find($id);

        if (!$juzgado) {
            throw new PageNotFoundException('No se encontró el juzgado solicitado');
        }

        $data = [
            'title' => 'Editar Juzgado',
            'juzgado' => $juzgado
        ];

        return view('juzgados/editar', $data);
    }

    /**
     * Actualiza un juzgado existente
     */
    public function actualizar($id)
    {
        // Verificar si el juzgado existe
        $juzgado = $this->model->find($id);
        if (!$juzgado) {
            throw new PageNotFoundException('No se encontró el juzgado solicitado');
        }

        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'fuero' => $this->request->getPost('fuero')
        ];

        // Intentar actualizar
        try {
            $this->model->update($id, $data);
            return redirect()->to('/juzgados')->with('success', 'Juzgado actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el juzgado: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un juzgado
     */
    public function eliminar($id)
    {
        $juzgado = $this->model->find($id);
        if (!$juzgado) {
            throw new PageNotFoundException('No se encontró el juzgado solicitado');
        }

        try {
            $this->model->delete($id);
            return redirect()->to('/juzgados')->with('success', 'Juzgado eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->to('/juzgados')->with('error', 'Error al eliminar el juzgado: ' . $e->getMessage());
        }
    }
}
