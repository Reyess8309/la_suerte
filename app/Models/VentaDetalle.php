<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'ventas_detalle';

    /**
     * Los atributos que SÍ se pueden asignar masivamente.
     */
    protected $fillable = [
        'venta_id',
        'evento_sorteo_id',
        'numero_apostado',
        'monto_apostado',
    ];

    /**
     * RELACIÓN: Un Detalle pertenece a UN EventoSorteo
     */
    public function eventoSorteo()
    {
        return $this->belongsTo(EventoSorteo::class, 'evento_sorteo_id', 'id');
    }
    /**
    * RELACIÓN: Un Detalle de Venta pertenece a UNA Venta (su "padre")
    */
    public function venta()
    {
        // belongsTo(Modelo, 'llave_foranea_en_esta_tabla', 'llave_local_en_la_otra_tabla')
        return $this->belongsTo(Venta::class, 'venta_id', 'id');
    }
}