<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * Esto le dice a Laravel que tu tabla se llama 'clientes' (en español)
     * y no 'clients' (en inglés).
     */
    protected $table = 'clientes';

    /**
     * Define los campos que SÍ se pueden llenar desde un formulario.
     * Protege contra asignación masiva.
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'documento_id',
        'fecha_nacimiento',
        'telefono',
    ];

    /**
     * Define los campos que deben ser tratados como fechas.
     * 'fecha_nacimiento' será un objeto Carbon automáticamente.
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // --- RELACIONES FUTURAS ---

    /**
     * Relación: Un Cliente puede tener muchas Ventas.
     * (La definiremos más adelante)
     */
    // public function ventas()
    // {
    //     return $this->hasMany(Venta::class, 'cliente_id', 'id');
    // }
}