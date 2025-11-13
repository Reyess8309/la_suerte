<?php

    use App\Http\Controllers\ClienteController; //Para panel clientes
    use App\Http\Controllers\UsuarioController; //Para panel usuarios
    use App\Http\Controllers\SorteoController;  //Para panel sorteos
    use App\Http\Controllers\VentaController;   //Para panel ventas

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
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index'); //para panel ventas
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store'); //para guardar venta nueva
    Route::get('/api/buscar-cliente/{documento_id}', [VentaController::class, 'buscarCliente'])->name('api.clientes.buscar'); //para buscar cliente por documento_id
    Route::get('/ventas/{venta}/voucher', [VentaController::class, 'generarVoucher'])->name('ventas.voucher'); //para generar voucher de venta en PDF
    Route::post('/sorteos/{evento}/registrar-ganador', [SorteoController::class, 'registrarGanador'])->name('sorteos.registrarGanador'); //
    
