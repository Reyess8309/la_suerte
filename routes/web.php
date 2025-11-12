<?php

    use App\Http\Controllers\ClienteController;

    // Esta ÚNICA línea crea TODAS las rutas para el CRUD de Clientes:
    // GET /clientes (index)
    // GET /clientes/create (create)
    // POST /clientes (store)
    // GET /clientes/{id} (show)
    // GET /clientes/{id}/edit (edit)
    // PUT /clientes/{id} (update)
    // DELETE /clientes/{id} (destroy)
    Route::resource('clientes', ClienteController::class);