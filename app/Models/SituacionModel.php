<?php

namespace App\Models;

use CodeIgniter\Model;

class SituacionModel extends Model
{
    protected $table      = 'situacion';
    protected $primaryKey = 'id_situacion';

    protected $allowedFields = ['nombre'];
    protected $returnType    = 'object';

    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[situacion.nombre,id_situacion,{id_situacion}]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la situación es obligatorio',
            'max_length' => 'El nombre no puede exceder los 50 caracteres',
            'is_unique' => 'Esta situación ya existe'
        ]
    ];

    protected $useTimestamps = true;

    /**
     * Obtiene todas las situaciones ordenadas alfabéticamente
     */
    public function listarSituaciones()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Verifica si una situación existe por nombre
     */
    public function existeSituacion($nombre)
    {
        return $this->where('nombre', $nombre)->first() !== null;
    }

    /**
     * Obtiene opciones para dropdown/select
     */
    public function getOpcionesSituacion()
    {
        $situaciones = $this->orderBy('nombre', 'ASC')->findAll();
        $opciones = [];
        foreach ($situaciones as $sit) {
            $opciones[$sit->id_situacion] = $sit->nombre;
        }
        return $opciones;
    }
}
