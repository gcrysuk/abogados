<?php

namespace App\Models;

use CodeIgniter\Model;

class CarpetasModel extends Model
{
    protected $table = 'carpetas';
    protected $primaryKey = 'id_carpeta';

    protected $allowedFields = [
        'no_expediente',
        'caratula',
        'fecha',
        'radicada_juzgado',
        'cliente',
        'contraparte',
        'situacion',
        'objeto',
        'parte',
        'propietario_id'
    ];

    protected $returnType = 'object';

    protected $validationRules = [
        'no_expediente' => 'required|max_length[50]',
        'caratula' => 'required|max_length[50]',
        'fecha' => 'required|valid_date',
        'cliente' => 'required|numeric',
        'situacion' => 'required|numeric',
        'objeto' => 'required|numeric',
        'parte' => 'required|in_list[actor,demandado]',
        'propietario_id' => 'required|numeric'
    ];

    protected $validationMessages = [
        'no_expediente' => [
            'required' => 'El número de expediente es obligatorio',
            'max_length' => 'El número no puede exceder 50 caracteres'
        ],
        'parte' => [
            'in_list' => 'Debe seleccionar actor o demandado'
        ]
    ];

    protected $useTimestamps = false;

    // ============ MÉTODOS RELACIONALES ============ //

    /**
     * Obtiene información completa de la carpeta con relaciones
     */
    public function getCarpetaCompleta($id)
    {
        $builder = $this->builder();

        return $builder
            ->select('carpetas.*, 
                j.nombre as juzgado_nombre,
                cli.nombre as cliente_nombre,
                cnt.nombre as contraparte_nombre,
                sit.nombre as situacion_nombre,
                obj.nombre as objeto_nombre,
                usr.nombre as propietario_nombre')
            ->join('juzgados j', 'j.id_juzgados = carpetas.radicada_juzgado', 'left')
            ->join('personas cli', 'cli.id_personas = carpetas.cliente')
            ->join('personas cnt', 'cnt.id_personas = carpetas.contraparte', 'left')
            ->join('situacion sit', 'sit.id_situacion = carpetas.situacion')
            ->join('objeto obj', 'obj.id_objeto = carpetas.objeto')
            ->join('usuarios usr', 'usr.id = carpetas.propietario_id')
            ->where('carpetas.id_carpeta', $id)
            ->get()
            ->getRow();
    }

    // ============ MÉTODOS DE BÚSQUEDA ============ //

    /**
     * Busca carpetas por número de expediente
     */
    public function buscarPorExpediente($expediente)
    {
        return $this->like('no_expediente', $expediente)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene carpetas por propietario
     */
    public function getPorPropietario($propietarioId)
    {
        return $this->where('propietario_id', $propietarioId)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    // ============ MÉTODOS DE GESTIÓN ============ //

    /**
     * Verifica si el usuario es propietario de la carpeta
     */
    public function esPropietario($carpetaId, $usuarioId)
    {
        return $this->where('id_carpeta', $carpetaId)
            ->where('propietario_id', $usuarioId)
            ->first() !== null;
    }

    /**
     * Obtiene carpetas por situación
     */
    public function getPorSituacion($situacionId)
    {
        return $this->where('situacion', $situacionId)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene estadísticas básicas de carpetas
     */
    public function getEstadisticas($propietarioId = null)
    {
        $builder = $this->builder();

        if ($propietarioId) {
            $builder->where('propietario_id', $propietarioId);
        }

        return [
            'total' => $builder->countAllResults(),
            'activas' => $builder->where('situacion !=', 3)->countAllResults(), // Asumiendo que 3 es situación "archivada"
            'recientes' => $builder->where('fecha >=', date('Y-m-d', strtotime('-30 days')))->countAllResults()
        ];
    }
}
