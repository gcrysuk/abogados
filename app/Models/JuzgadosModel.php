<?php

namespace App\Models;

use CodeIgniter\Model;

class JuzgadosModel extends Model
{
    protected $table = 'Juzgados';
    protected $primaryKey = 'id_juzgados';

    protected $allowedFields = ['nombre', 'direccion', 'fuero'];

    protected $returnType = 'object'; // o 'array' según prefieras

    protected $useTimestamps = false; // Si no manejas created_at, updated_at

    // Validaciones
    protected $validationRules = [
        'nombre' => 'required|max_length[50]',
        'direccion' => 'required|max_length[50]',
        'fuero' => 'required|max_length[50]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del juzgado es obligatorio',
            'max_length' => 'El nombre no puede exceder los 50 caracteres'
        ],
        'direccion' => [
            'required' => 'La dirección es obligatoria',
            'max_length' => 'La dirección no puede exceder los 50 caracteres'
        ],
        'fuero' => [
            'required' => 'El fuero es obligatorio',
            'max_length' => 'El fuero no puede exceder los 50 caracteres'
        ]
    ];

    // Método para obtener todos los juzgados
    public function getJuzgados()
    {
        return $this->findAll();
    }

    // Método para buscar juzgados por fuero
    public function getByFuero($fuero)
    {
        return $this->where('fuero', $fuero)->findAll();
    }
}
