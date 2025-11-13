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
     * (Basado en el Manual_Tecnico_BD_Espanol.md)
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
     * RELACIÓN: Un Premio pertenece a UN Detalle de Venta (la apuesta ganadora)
     */
    public function ventaDetalle()
    {
        return $this->belongsTo(VentaDetalle::class, 'venta_detalle_id', 'id');
    }

    /**
     * RELACIÓN: Un Premio pertenece a UN Cliente (para reportes)
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
}