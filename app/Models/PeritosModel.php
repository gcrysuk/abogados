<?php

namespace App\Models;

use CodeIgniter\Model;

class PeritosModel extends Model
{
    protected $table = 'peritos'; // Nombre de la tabla en minúsculas
    protected $primaryKey = 'id_perito'; // Clave primaria

    // Campos permitidos para asignación masiva
    protected $allowedFields = [
        'nombre',
        'materia',
        'domicilio_electronico'
    ];

    // Tipo de retorno (object o array)
    protected $returnType = 'object';

    // Validación automática
    protected $validationRules = [
        'nombre' => 'required|max_length[50]',
        'materia' => 'required|max_length[50]',
        'domicilio_electronico' => 'required|valid_email|max_length[50]|is_unique[peritos.domicilio_electronico,id_perito,{id_perito}]'
    ];

    // Mensajes de validación personalizados
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del perito es obligatorio',
            'max_length' => 'El nombre no puede exceder los 50 caracteres'
        ],
        'materia' => [
            'required' => 'La materia/especialidad es obligatoria',
            'max_length' => 'La materia no puede exceder los 50 caracteres'
        ],
        'domicilio_electronico' => [
            'required' => 'El domicilio electrónico es obligatorio',
            'valid_email' => 'Debe ingresar un email válido',
            'max_length' => 'El email no puede exceder los 50 caracteres',
            'is_unique' => 'Este email ya está registrado para otro perito'
        ]
    ];

    // Uso de timestamps (created_at, updated_at)
    protected $useTimestamps = true;

    // ============ MÉTODOS PERSONALIZADOS ============ //

    /**
     * Busca peritos por materia
     */
    public function buscarPorMateria($materia)
    {
        return $this->like('materia', $materia)->findAll();
    }

    /**
     * Obtiene todos los peritos ordenados por nombre
     */
    public function getPeritosOrdenados()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Verifica si un email ya está registrado
     */
    public function emailExiste($email)
    {
        return $this->where('domicilio_electronico', $email)->first() !== null;
    }

    /**
     * Obtiene un perito por ID con manejo de excepciones
     */
    public function getPerito($id)
    {
        $perito = $this->find($id);
        if (!$perito) {
            throw new \RuntimeException('Perito no encontrado');
        }
        return $perito;
    }
}
