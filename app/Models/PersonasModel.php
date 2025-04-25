<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonasModel extends Model
{
    protected $table = 'Personas'; // Nombre exacto de la tabla
    protected $primaryKey = 'id_personas'; // Clave primaria

    // Campos permitidos para asignación masiva
    protected $allowedFields = [
        'dni_cuit',
        'nombre',
        'domicilio',
        'telefono',
        'mail',
        'observaciones',
        'propietario_id'
    ];

    // Tipo de retorno (object o array)
    protected $returnType = 'object';

    // Validación automática
    protected $validationRules = [
        'dni_cuit' => 'required|numeric|is_unique[Personas.dni_cuit,id_personas,{id_personas}]|min_length[7]|max_length[11]',
        'nombre' => 'required|max_length[50]',
        'domicilio' => 'required|max_length[50]',
        'telefono' => 'required|max_length[15]',
        'mail' => 'required|valid_email|max_length[50]',
        'propietario_id' => 'required|numeric'
    ];

    // Mensajes de validación personalizados
    protected $validationMessages = [
        'dni_cuit' => [
            'required' => 'El DNI/CUIT es obligatorio',
            'numeric' => 'El DNI/CUIT debe contener solo números',
            'is_unique' => 'Este DNI/CUIT ya está registrado',
            'min_length' => 'El DNI/CUIT debe tener al menos 7 dígitos',
            'max_length' => 'El DNI/CUIT no puede exceder los 11 dígitos'
        ],
        'propietario_id' => [
            'required' => 'Debe asignar un propietario a la persona'
        ]
    ];

    // Uso de timestamps (created_at, updated_at)
    protected $useTimestamps = true;

    // Configuración de relaciones
    protected function initialize()
    {
        // Relación con usuarios (propietario)
        $this->belongsTo('propietario_id', 'App\Models\UsuariosModel', 'id');
    }

    // ============ MÉTODOS PERSONALIZADOS ============ //

    /**
     * Obtiene una persona con los datos del propietario
     */
    public function getPersonaConPropietario($idPersona)
    {
        return $this->select('Personas.*, Usuarios.nombre as propietario_nombre, Usuarios.email as propietario_email')
            ->join('Usuarios', 'Usuarios.id = Personas.propietario_id')
            ->find($idPersona);
    }

    /**
     * Busca personas por DNI/CUIT
     */
    public function buscarPorDni($dni)
    {
        return $this->where('dni_cuit', $dni)->first();
    }

    /**
     * Obtiene todas las personas de un propietario específico
     */
    public function getPorPropietario($propietarioId)
    {
        return $this->where('propietario_id', $propietarioId)->findAll();
    }

    /**
     * Obtiene todas las personas con información del propietario
     */
    public function getAllConPropietario()
    {
        return $this->select('Personas.*, Usuarios.nombre as propietario_nombre')
            ->join('Usuarios', 'Usuarios.id = Personas.propietario_id')
            ->findAll();
    }

    /**
     * Actualiza los datos de una persona verificando el propietario
     */
    public function actualizarPersona($id, $data, $propietarioId = null)
    {
        // Si se especifica propietario, verificar permisos
        if ($propietarioId) {
            $persona = $this->find($id);
            if ($persona && $persona->propietario_id != $propietarioId) {
                return false; // No tiene permisos
            }
        }

        return $this->update($id, $data);
    }
}
