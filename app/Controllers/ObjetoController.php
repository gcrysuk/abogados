<?php

namespace App\Controllers;

use App\Models\ObjetoModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait;

class ObjetoController extends BaseController
{
    use ResponseTrait;

    protected $model;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->model = new ObjetoModel();
    }

    /**
     * Lista todos los objetos (página principal)
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Objetos',
            'objetos' => $this->model->getObjetosOrdenados(),
            'pager' => $this->model->pager ?? null
        ];

        return view('objeto/index', $data);
    }

    /**
     * Busca objetos por nombre (para AJAX)
     */
    public function buscar()
    {
        $nombre = $this->request->getGet('term');

        if (empty($nombre)) {
            return $this->respond([]);
        }

        $resultados = $this->model->buscarPorNombre($nombre);
        return $this->respond($resultados);
    }

    /**
     * Muestra formulario de creación
     */
    public function nuevo()
    {
        $data = [
            'title' => 'Nuevo Objeto',
            'validation' => service('validation')
        ];

        return view('objeto/nuevo', $data);
    }

    /**
     * Guarda un nuevo objeto
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
            return redirect()->to('/objetos')->with('success', 'Objeto creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra formulario de edición
     */
    public function editar($id)
    {
        try {
            $objeto = $this->model->getObjeto($id);

            $data = [
                'title' => 'Editar Objeto',
                'objeto' => $objeto,
                'validation' => service('validation')
            ];

            return view('objeto/editar', $data);
        } catch (\RuntimeException $e) {
            throw new PageNotFoundException($e->getMessage());
        }
    }

    /**
     * Actualiza un objeto existente
     */
    public function actualizar($id)
    {
        // Verificar si el objeto existe
        try {
            $this->model->getObjeto($id);
        } catch (\RuntimeException $e) {
            throw new PageNotFoundException($e->getMessage());
        }

        // Validar los datos
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $nombre = $this->request->getPost('nombre');

        try {
            $this->model->update($id, ['nombre' => $nombre]);
            return redirect()->to('/objetos')->with('success', 'Objeto actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un objeto
     */
    public function eliminar($id)
    {
        try {
            $this->model->delete($id);
            return redirect()->to('/objetos')->with('success', 'Objeto eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/objetos')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene objetos para select (API)
     */
    public function obtenerParaSelect()
    {
        $objetos = $this->model->getObjetosParaSelect();
        return $this->respond($objetos);
    }

    /**
     * Verifica si un nombre existe (API)
     */
    public function verificarNombre()
    {
        $nombre = $this->request->getPost('nombre');
        $existe = $this->model->nombreExiste($nombre);

        return $this->respond(['existe' => $existe]);
    }
}
