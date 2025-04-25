<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjetoModel extends Model
{
    protected $table = 'objeto'; // Nombre de la tabla en minúsculas
    protected $primaryKey = 'id_objeto'; // Clave primaria

    // Campos permitidos para asignación masiva
    protected $allowedFields = [
        'nombre'
    ];

    // Tipo de retorno (object o array)
    protected $returnType = 'object';

    // Validación automática
    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[objeto.nombre,id_objeto,{id_objeto}]'
    ];

    // Mensajes de validación personalizados
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del objeto es obligatorio',
            'max_length' => 'El nombre no puede exceder los 50 caracteres',
            'is_unique' => 'Este nombre de objeto ya existe'
        ]
    ];

    // Uso de timestamps (created_at, updated_at)
    protected $useTimestamps = true;

    // ============ MÉTODOS PERSONALIZADOS ============ //

    /**
     * Obtiene todos los objetos ordenados por nombre
     */
    public function getObjetosOrdenados()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Busca objetos por nombre (coincidencia parcial)
     */
    public function buscarPorNombre($nombre)
    {
        return $this->like('nombre', $nombre)->findAll();
    }

    /**
     * Verifica si un nombre de objeto ya existe
     */
    public function nombreExiste($nombre)
    {
        return $this->where('nombre', $nombre)->first() !== null;
    }

    /**
     * Obtiene un objeto por ID con manejo de excepciones
     */
    public function getObjeto($id)
    {
        $objeto = $this->find($id);
        if (!$objeto) {
            throw new \RuntimeException('Objeto no encontrado');
        }
        return $objeto;
    }

    /**
     * Obtiene los objetos en formato para dropdown
     */
    public function getObjetosParaSelect()
    {
        $objetos = $this->orderBy('nombre', 'ASC')->findAll();
        $result = [];
        foreach ($objetos as $objeto) {
            $result[$objeto->id_objeto] = $objeto->nombre;
        }
        return $result;
    }
}
