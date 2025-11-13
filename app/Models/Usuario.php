<?php

namespace App\Models;

// ¡OJO! Importamos la clase de Autenticación
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

// Extendemos de 'Authenticatable' en lugar de 'Model'
// Esto le da a Laravel los métodos de login, etc.
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'usuarios';

    /**
     * Los atributos que SÍ se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol', // 'admin' o 'employee'
    ];

    /**
     * Los atributos que deben ocultarse en las serializaciones (JSON).
     * ¡Importante por seguridad!
     */
    protected $hidden = [
        'password',
        'remember_token', // Laravel usa esto internamente
    ];

    /**
     * Los atributos que deben ser "casteados" a tipos nativos.
     * Le decimos a Laravel que 'password' es un campo hasheado.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // ¡IMPORTANTE!
    ];
}