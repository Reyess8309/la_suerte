<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSorteo extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'tipos_sorteo';

    public $timestamps = false;

    /**
     * Los atributos que SÍ se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'factor_pago',
        'eventos_por_dia',
    ];

    /**
     * Un Tipo de Sorteo tiene MUCHOS EventosSorteo
     */
    public function eventos()
    {
        return $this->hasMany(EventoSorteo::class, 'tipo_sorteo_id', 'id');
    }
}