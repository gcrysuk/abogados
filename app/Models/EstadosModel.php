<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadosModel extends Model
{
    // Configuración básica
    protected $table         = 'estados';  // ✔ Nombre de la tabla en minúsculas (convención CI4)
    protected $primaryKey    = 'id_estado'; // ✔ Clave primaria
    protected $returnType    = 'object';    // ✔ Resultados como objetos (opcional: 'array')
    protected $useTimestamps = false;       // ✔ No usar created_at/updated_at

    // Campos editables (importante para insert/update)
    protected $allowedFields = ['estado'];  // ✔ Solo estos campos se pueden guardar

    // Validación automática
    protected $validationRules = [
        'estado' => 'required|max_length[50]|is_unique[estados.estado]' // ✔ Valida que sea único
    ];

    protected $validationMessages = [
        'estado' => [
            'required'    => 'El nombre del estado es obligatorio',
            'max_length'  => 'Máximo 50 caracteres',
            'is_unique'   => 'Este estado ya existe'
        ]
    ];

    // ✔ Métodos útiles predefinidos:

    /**
     * Obtiene todos los estados ordenados
     */
    public function listarEstados()
    {
        return $this->orderBy('estado', 'ASC')->findAll();
    }

    /**
     * Busca estado por ID
     */
    public function obtenerEstado($id)
    {
        return $this->find($id);
    }

    /**
     * Verifica si un estado existe
     */
    public function existeEstado($nombreEstado)
    {
        return $this->where('estado', $nombreEstado)->first();
    }
}
