<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoSorteo extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
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
     * RELACIÓN: Un EventoSorteo pertenece a UN TipoSorteo (ej. La Santa)
     */
    public function tipoSorteo()
    {
        // belongsTo(Modelo, 'llave_foranea_en_esta_tabla', 'llave_local_en_la_otra_tabla')
        return $this->belongsTo(TipoSorteo::class, 'tipo_sorteo_id', 'id');
    }
}