<?php

namespace App\Http\Controllers;

use App\Models\TipoSorteo;
use App\Models\EventoSorteo;
use App\Services\ServicioDePremios;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SorteoController extends Controller
{
    /**
     * Muestra el panel de administración de sorteos
     */
    public function index()
    {
        $fechaHoy = Carbon::today();
        $eventosDeHoy = EventoSorteo::where('fecha_evento', $fechaHoy)
                                   ->with('tipoSorteo')
                                   ->orderBy('tipo_sorteo_id') // Ordenar
                                   ->orderBy('numero_evento')
                                   ->get();

        return view('sorteos.index', [
            'eventos' => $eventosDeHoy,
            'fecha' => $fechaHoy->format('d/m/Y')
        ]);
    }

    /**
     * Genera los 6 eventos para el día de hoy si no existen.
     */
    public function generarEventosHoy()
    {
        $fechaHoy = Carbon::today();
        $eventosExistentes = EventoSorteo::where('fecha_evento', $fechaHoy)->count();

        if ($eventosExistentes > 0) {
            return redirect()->route('sorteos.index')
                             ->with('error', '¡Los eventos para hoy ya habían sido generados!');
        }

        $tiposSorteo = TipoSorteo::all(); 
        foreach ($tiposSorteo as $tipo) {
            for ($i = 1; $i <= $tipo->eventos_por_dia; $i++) {
                EventoSorteo::create([
                    'tipo_sorteo_id' => $tipo->id,
                    'fecha_evento' => $fechaHoy,
                    'numero_evento' => $i,
                    'estado' => 'abierto', 
                    'numero_ganador' => null,
                ]);
            }
        }

        return redirect()->route('sorteos.index')
                         ->with('success', '¡Se generaron los 6 eventos para hoy exitosamente!');
    }
    
    /**
     * Registra el número ganador y dispara el cálculo de premios
     */
    public function registrarGanador(Request $request, EventoSorteo $evento, ServicioDePremios $servicioDePremios)
    {
        // 1. Validar el input que tenga dos digitos
        $validator = Validator::make($request->all(), [
            'numero_ganador' => 'required|string|digits:2',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sorteos.index')
                             ->with('error', 'Número inválido. Debe tener 2 dígitos.');
        }

        // 2. Verificar que el evento no esté ya procesado
        if ($evento->numero_ganador) {
            return redirect()->route('sorteos.index')
                             ->with('error', 'Este sorteo ya tiene un número ganador registrado.');
        }

        $numero = $request->input('numero_ganador');

        // 3. Guardar el número ganador y cerrar el evento
        $evento->numero_ganador = $numero;
        $evento->estado = 'cerrado';
        $evento->save();

        // 4. LLAMAR AL SERVICIO
        $evento->load('tipoSorteo');
        $premiosGenerados = $servicioDePremios->procesarGanadores($evento);

        $mensajeExito = "¡Número [$numero] guardado! Se generaron $premiosGenerados premios.";
        if ($premiosGenerados == 0) {
            $mensajeExito = "¡Número [$numero] guardado! No hubo ganadores (Ganador Desierto).";
        }

        return redirect()->route('sorteos.index')
                         ->with('success', $mensajeExito);
    }
}