<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premio extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'premios';

    /**
     * Los atributos que SÍ se pueden asignar masivamente.
     */
    protected $fillable = [
        'venta_detalle_id',
        'cliente_id',
        'monto_base',
        'bono_cumpleanos',
        'monto_total',
        'estado',
        'fecha_vencimiento',
    ];

    /**
     * Un Premio pertenece a UN Detalle de Venta
     */
    public function ventaDetalle()
    {
        return $this->belongsTo(VentaDetalle::class, 'venta_detalle_id', 'id');
    }

    /**
     * Un Premio pertenece a UN Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
}