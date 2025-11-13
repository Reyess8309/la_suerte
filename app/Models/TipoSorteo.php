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

    /**
     * Indica si el modelo debe tener timestamps (created_at, updated_at).
     * Esta es una tabla de "catálogo" o configuración, no los necesita.
     */
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
     * RELACIÓN: Un Tipo de Sorteo (ej. La Santa) tiene MUCHOS EventosSorteo
     */
    public function eventos()
    {
        // hasMany(Modelo, 'llave_foranea_en_la_otra_tabla', 'llave_local_en_esta_tabla')
        return $this->hasMany(EventoSorteo::class, 'tipo_sorteo_id', 'id');
    }
}