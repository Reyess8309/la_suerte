<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     * (Como lo definimos en el Paso 4)
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * La clave primaria asociada con la tabla.
     * Le decimos a Laravel que 'documento_id' es nuestra clave
     * en lugar de 'id'.
     *
     * @var string
     */
    protected $primaryKey = 'documento_id';

    /**
     * Indica si la clave primaria es autoincremental.
     * En nuestro caso, no lo es (es un string).
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * El tipo de dato de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * ¡NUEVO!
     * Los atributos que SÍ se pueden asignar masivamente.
     * Estos son los campos de tu formulario.
     */
    protected $fillable = [
        'documento_id',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'telefono'
    ];
}