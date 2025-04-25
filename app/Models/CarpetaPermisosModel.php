<?php

namespace App\Models;

use CodeIgniter\Model;

class CarpetaPermisosModel extends Model
{
    protected $table = 'carpeta_permisos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_carpeta',
        'usuario_id',
        'permiso',
        'otorgado_por'
    ];

    protected $returnType = 'object';

    protected $validationRules = [
        'id_carpeta' => 'required|numeric',
        'usuario_id' => 'required|numeric',
        'permiso' => 'required|in_list[ver,editar,seguimiento]',
        'otorgado_por' => 'permit_empty|numeric'
    ];

    protected $validationMessages = [
        'permiso' => [
            'in_list' => 'Debe seleccionar un tipo de permiso válido (ver, editar o seguimiento)'
        ],
        'id_carpeta' => [
            'required' => 'Debe asociar el permiso a una carpeta'
        ],
        'usuario_id' => [
            'required' => 'Debe especificar un usuario'
        ]
    ];

    protected $useTimestamps = true;
    protected $createdField = 'creado_el';
    protected $updatedField = false; // No tenemos campo de actualización

    /**
     * Obtiene los permisos de un usuario para una carpeta específica
     */
    public function getPermisosUsuario($usuarioId, $carpetaId)
    {
        return $this->where('usuario_id', $usuarioId)
            ->where('id_carpeta', $carpetaId)
            ->findAll();
    }

    /**
     * Verifica si un usuario tiene un permiso específico
     */
    public function tienePermiso($usuarioId, $carpetaId, $permiso)
    {
        return $this->where('usuario_id', $usuarioId)
            ->where('id_carpeta', $carpetaId)
            ->where('permiso', $permiso)
            ->first() !== null;
    }

    /**
     * Obtiene todos los usuarios con permisos para una carpeta
     */
    public function getUsuariosConPermisos($carpetaId)
    {
        return $this->select('carpeta_permisos.*, usuarios.nombre, usuarios.username')
            ->join('usuarios', 'usuarios.id = carpeta_permisos.usuario_id')
            ->where('id_carpeta', $carpetaId)
            ->findAll();
    }

    /**
     * Revoca todos los permisos de un usuario para una carpeta
     */
    public function revocarPermisos($usuarioId, $carpetaId)
    {
        return $this->where('usuario_id', $usuarioId)
            ->where('id_carpeta', $carpetaId)
            ->delete();
    }

    /**
     * Otorga un nuevo permiso
     */
    public function otorgarPermiso($data)
    {
        // Verificar si el permiso ya existe
        $existe = $this->where('usuario_id', $data['usuario_id'])
            ->where('id_carpeta', $data['id_carpeta'])
            ->where('permiso', $data['permiso'])
            ->first();

        if (!$existe) {
            return $this->insert($data);
        }

        return false; // El permiso ya existía
    }
}
