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

    /**
     * Verifica si existen apuestas con el número ganador para este evento.
     */
    public function tieneGanadores()
    {
        // Si no hay número ganador registrado, no puede haber ganadores aún
        if ($this->numero_ganador === null) {
            return false;
        }

        // Buscamos si existe una apuesta
        // que coincida con este evento y con el número ganador.
        return VentaDetalle::where('evento_sorteo_id', $this->id)
                           ->where('numero_apostado', $this->numero_ganador)
                           ->exists();
    }

    /**
     * Devuelve una lista de nombres de los ganadores separados por coma.
     */
    public function obtenerNombresGanadores()
    {
        if ($this->numero_ganador === null) {
            return '';
        }

        // Buscar los detalles ganadores
        // Cargar la relación con la Venta y el Cliente
        // sacar solo el nombre completo
        $nombres = VentaDetalle::where('evento_sorteo_id', $this->id)
                           ->where('numero_apostado', $this->numero_ganador)
                           ->with('venta.cliente')
                           ->get()
                           ->map(function ($detalle) {
                               return $detalle->venta->cliente->nombre . ' ' . $detalle->venta->cliente->apellido;
                           })
                           ->unique(); // Evitam duplicidad si el mismo cliente compró 2 veces el mismo número

        return $nombres->implode(', '); // Convertir en array "Juan, Pedro, Maria"
    }
}