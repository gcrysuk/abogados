<?php

namespace App\Controllers;

use App\Models\PeritosModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait;

class PeritosController extends BaseController
{
    use ResponseTrait;

    protected $model;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->model = new PeritosModel();
    }

    /**
     * Lista todos los peritos (página principal)
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Peritos',
            'peritos' => $this->model->getPeritosOrdenados(),
            'materias' => $this->model->distinct()->select('materia')->findAll()
        ];

        return view('peritos/index', $data);
    }

    /**
     * Busca peritos por materia (página filtrada)
     */
    public function porMateria($materia)
    {
        $data = [
            'title' => "Peritos de $materia",
            'peritos' => $this->model->buscarPorMateria($materia),
            'materias' => $this->model->distinct()->select('materia')->findAll(),
            'materia_actual' => $materia
        ];

        return view('peritos/index', $data);
    }

    /**
     * Muestra formulario de creación
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Registrar Nuevo Perito',
            'validation' => service('validation')
        ];

        return view('peritos/nuevo', $data);
    }

    /**
     * Guarda un nuevo perito
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
            'materia' => $this->request->getPost('materia'),
            'domicilio_electronico' => $this->request->getPost('domicilio_electronico')
        ];

        try {
            $this->model->save($data);
            return redirect()->to('/peritos')->with('success', 'Perito registrado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra formulario de edición
     */
    public function editar($id)
    {
        try {
            $perito = $this->model->getPerito($id);

            $data = [
                'title' => 'Editar Perito',
                'perito' => $perito,
                'validation' => service('validation')
            ];

            return view('peritos/editar', $data);
        } catch (\RuntimeException $e) {
            throw new PageNotFoundException($e->getMessage());
        }
    }

    /**
     * Actualiza un perito existente
     */
    public function actualizar($id)
    {
        // Verificar si el perito existe
        try {
            $this->model->getPerito($id);
        } catch (\RuntimeException $e) {
            throw new PageNotFoundException($e->getMessage());
        }

        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'materia' => $this->request->getPost('materia'),
            'domicilio_electronico' => $this->request->getPost('domicilio_electronico')
        ];

        try {
            $this->model->update($id, $data);
            return redirect()->to('/peritos')->with('success', 'Perito actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un perito
     */
    public function eliminar($id)
    {
        try {
            $this->model->delete($id);
            return redirect()->to('/peritos')->with('success', 'Perito eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/peritos')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si un email existe (API)
     */
    public function verificarEmail()
    {
        $email = $this->request->getPost('email');
        $existe = $this->model->emailExiste($email);

        return $this->respond(['existe' => $existe]);
    }

    /**
     * Busca peritos por materia (API)
     */
    public function buscarPorMateriaApi()
    {
        $materia = $this->request->getGet('materia');
        $peritos = $this->model->buscarPorMateria($materia);
        return $this->respond($peritos);
    }
}
