<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientosModel extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'id_movimiento';

    protected $allowedFields = [
        'id_carpeta',
        'fecha_carga',
        'fecha_movimiento',
        'fecha_vencimiento',
        'tiempo_trabajo',
        'id_otro_movimiento',
        'id_estado',
        'descripcion',
        'tarea_pendiente',
        'desc_tarea_pend',
        'notificacion'
    ];

    protected $returnType = 'object';

    protected $validationRules = [
        'id_carpeta' => 'required|numeric',
        'fecha_movimiento' => 'required|valid_date',
        'id_estado' => 'required|numeric',
        'tiempo_trabajo' => 'decimal',
        'tarea_pendiente' => 'permit_empty|in_list[0,1]',
        'notificacion' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_carpeta' => [
            'required' => 'Debe asociar el movimiento a una carpeta'
        ],
        'fecha_movimiento' => [
            'required' => 'La fecha del movimiento es obligatoria'
        ]
    ];

    protected $useTimestamps = false;

    /**
     * Obtiene movimientos por carpeta ordenados cronológicamente
     */
    public function getPorCarpeta($idCarpeta, $limit = null)
    {
        $builder = $this->builder();
        $builder->where('id_carpeta', $idCarpeta)
            ->orderBy('fecha_movimiento', 'DESC')
            ->orderBy('id_movimiento', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResult();
    }

    /**
     * Obtiene movimientos pendientes (con tareas pendientes)
     */
    public function getPendientes($idCarpeta = null)
    {
        $builder = $this->builder();
        $builder->where('tarea_pendiente', 1)
            ->orderBy('fecha_vencimiento', 'ASC');

        if ($idCarpeta) {
            $builder->where('id_carpeta', $idCarpeta);
        }

        return $builder->get()->getResult();
    }

    /**
     * Obtiene movimientos que requieren notificación
     */
    public function getParaNotificar()
    {
        return $this->where('notificacion', 1)
            ->where('fecha_vencimiento >=', date('Y-m-d'))
            ->findAll();
    }

    /**
     * Obtiene un movimiento con información relacionada
     */
    public function getMovimientoCompleto($idMovimiento)
    {
        return $this->select('movimientos.*, e.nombre as estado_nombre, c.no_expediente, c.caratula')
            ->join('estados e', 'e.id_estado = movimientos.id_estado')
            ->join('carpetas c', 'c.id_carpeta = movimientos.id_carpeta')
            ->where('movimientos.id_movimiento', $idMovimiento)
            ->first();
    }

    /**
     * Calcula el tiempo total trabajado en una carpeta
     */
    public function getTiempoTotalCarpeta($idCarpeta)
    {
        $result = $this->builder()
            ->selectSum('tiempo_trabajo')
            ->where('id_carpeta', $idCarpeta)
            ->get()
            ->getRow();

        return $result->tiempo_trabajo ?? 0;
    }

    /**
     * Marca una tarea como completada
     */
    public function completarTarea($idMovimiento)
    {
        return $this->update($idMovimiento, [
            'tarea_pendiente' => 0,
            'desc_tarea_pend' => null
        ]);
    }

    /**
     * Obtiene el último movimiento de una carpeta
     */
    public function getUltimoMovimiento($idCarpeta)
    {
        return $this->where('id_carpeta', $idCarpeta)
            ->orderBy('fecha_movimiento', 'DESC')
            ->orderBy('id_movimiento', 'DESC')
            ->first();
    }
}
