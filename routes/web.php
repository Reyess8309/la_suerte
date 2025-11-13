<?php

    use App\Http\Controllers\ClienteController; //Para panel clientes
    use App\Http\Controllers\UsuarioController; //Para panel usuarios
    use App\Http\Controllers\SorteoController;  //Para panel sorteos

    // Esta ÚNICA línea crea TODAS las rutas para el CRUD de Clientes:
    // GET /clientes (index)
    // GET /clientes/create (create)
    // POST /clientes (store)
    // GET /clientes/{id} (show)
    // GET /clientes/{id}/edit (edit)
    // PUT /clientes/{id} (update)
    // DELETE /clientes/{id} (destroy)
    Route::resource('clientes', ClienteController::class); //Para panel clientes
    Route::resource('usuarios', UsuarioController::class); //Para panel usuarios
    Route::get('/sorteos', [SorteoController::class, 'index'])->name('sorteos.index'); //para panel sorteo
    Route::post('/sorteos/generar', [SorteoController::class, 'generarEventosHoy'])->name('sorteos.generar'); //para logica generar evento
