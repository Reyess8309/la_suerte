<?php

namespace App\Http\Controllers;

use App\Models\TipoSorteo;
use App\Models\EventoSorteo;
use Illuminate\Http\Request;
use Carbon\Carbon; // Importamos Carbon para manejar fechas fácilmente

class SorteoController extends Controller
{
    /**
     * Muestra el panel de administración de sorteos (Mockup 2).
     * Muestra los eventos generados para HOY.
     */
    public function index()
    {
        $fechaHoy = Carbon::today(); // Obtiene la fecha de hoy (ej. 2025-11-12)

        // Buscamos los eventos de hoy, cargando la info de su 'tipoSorteo'
        $eventosDeHoy = EventoSorteo::where('fecha_evento', $fechaHoy)
                                   ->with('tipoSorteo') // Carga la relación
                                   ->get();

        return view('sorteos.index', [
            'eventos' => $eventosDeHoy,
            'fecha' => $fechaHoy->format('d/m/Y')
        ]);
    }

    /**
     * Esta es la lógica CLAVE para cumplir con RN-007, RN-008, RN-009.
     * Genera los 6 eventos para el día de hoy, si no existen.
     */
    public function generarEventosHoy()
    {
        $fechaHoy = Carbon::today();

        // 1. Verificamos si YA se generaron eventos para hoy
        $eventosExistentes = EventoSorteo::where('fecha_evento', $fechaHoy)->count();

        if ($eventosExistentes > 0) {
            // Si ya existen, no hacemos nada y solo redirigimos
            return redirect()->route('sorteos.index')
                             ->with('error', '¡Los eventos para hoy ya habían sido generados!');
        }

        // 2. Si no existen, los generamos
        $tiposSorteo = TipoSorteo::all(); // Obtenemos "La Santa", "La Rifa", "El Sorteo"

        foreach ($tiposSorteo as $tipo) {
            // Usamos la columna 'eventos_por_dia' de nuestra BD
            for ($i = 1; $i <= $tipo->eventos_por_dia; $i++) {
                EventoSorteo::create([
                    'tipo_sorteo_id' => $tipo->id,
                    'fecha_evento' => $fechaHoy,
                    'numero_evento' => $i,
                    'estado' => 'abierto', // Por defecto, listos para vender
                    'numero_ganador' => null,
                ]);
            }
        }

        // 3. Redirigimos con mensaje de éxito
        return redirect()->route('sorteos.index')
                         ->with('success', '¡Se generaron los 6 eventos para hoy exitosamente!');
    }
    
    // Aquí irán las funciones para registrar el número ganador
}