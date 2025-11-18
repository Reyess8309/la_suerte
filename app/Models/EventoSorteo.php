<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoSorteo extends Model
{
    use HasFactory;

    /**
     * tabla asociada con el modelo.
     */
    protected $table = 'eventos_sorteo';

    /**
     * Los atributos que SÍ se pueden asignar masivamente.
     */
    protected $fillable = [
        'tipo_sorteo_id',
        'fecha_evento',
        'numero_evento',
        'estado',
        'numero_ganador',
    ];

    /**
     *  Un EventoSorteo pertenece a UN TipoSorteo
     */
    public function tipoSorteo()
    {
        return $this->belongsTo(TipoSorteo::class, 'tipo_sorteo_id', 'id');
    }
}