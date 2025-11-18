<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SorteoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;

// --- Rutas Públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Las rutas de Auth
require __DIR__.'/auth.php';


// --- Rutas que requieren login ---
// requiere que el usuario esté logueado
Route::middleware(['auth'])->group(function () {

    // --- Rutas para todos los Empleados ---
    // (venta y la búsqueda de clientes)
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/store', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/voucher/{venta}', [VentaController::class, 'generarVoucher'])->name('ventas.voucher');
    Route::get('/api/buscar-cliente/{documento_id}', [VentaController::class, 'buscarCliente'])->name('api.clientes.buscar');

    // Gestión de clientes, solo admin puede borrar
    Route::resource('clientes', ClienteController::class)->except(['destroy']);


    // --- Rutas para admin ---
    Route::middleware(['role:admin'])->group(function () {
        
        // CRUD de Empleados
        Route::resource('usuarios', UsuarioController::class);

        // Panel de Sorteos
        Route::get('/sorteos', [SorteoController::class, 'index'])->name('sorteos.index');
        Route::post('/sorteos/generar', [SorteoController::class, 'generarEventosHoy'])->name('sorteos.generar');
        Route::post('/sorteos/{evento}/registrar-ganador', [SorteoController::class, 'registrarGanador'])->name('sorteos.registrarGanador');
        Route::get('/sorteos/pdf', [SorteoController::class, 'descargarPDFSorteos'])->name('sorteos.pdf');

        // Reportes
        Route::get('/reportes/recaudacion', [ReporteController::class, 'reporteRecaudacion'])->name('reportes.recaudacion');
        Route::get('/reportes/recaudacion/pdf', [ReporteController::class, 'descargarPDFRecaudacion'])->name('reportes.recaudacion.pdf');
        Route::get('/reportes/ganadores', [ReporteController::class, 'reporteGanadores'])->name('reportes.ganadores');
        Route::get('/reportes/ganadores/pdf', [ReporteController::class, 'descargarPDFGanadores'])->name('reportes.ganadores.pdf');
        Route::post('/reportes/premios/{premio}/pagar', [ReporteController::class, 'marcarPremioPagado'])->name('reportes.marcarPagado');

    });

});