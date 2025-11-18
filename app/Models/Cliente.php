<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * 'documento_id' es nuestra clave
     * en lugar de 'id'.
     *
     * @var string
     */
    protected $primaryKey = 'documento_id';

    /**
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
     * los campos de tu formulario.
     */
    protected $fillable = [
        'documento_id',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'telefono'
    ];
}