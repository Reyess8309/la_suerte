<?php

namespace App\Http\Controllers;

use App\Models\TipoSorteo;
use App\Models\VentaDetalle;
use App\Models\Premio;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Muestra el Reporte de Recaudación (Mockup 3)
     * RF-020, RF-021
     */
    public function reporteRecaudacion(Request $request)
    {
        // 1. Obtener los tipos de sorteo para el dropdown de filtro
        $tiposSorteo = TipoSorteo::all();

        // 2. Definir fechas por defecto (hoy)
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $tipoSorteoId = $request->input('tipo_sorteo_id');

        // 3. Construir la consulta a la base de datos (Query Builder)
        // Usamos 'VentaDetalle' porque tiene el 'monto_apostado'
        $query = VentaDetalle::query();

        // 4. Aplicar filtros de fecha
        // Buscamos detalles de ventas creadas entre estas fechas
        $query->whereHas('venta', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        });

        // 5. Aplicar filtro de Tipo de Sorteo (si se seleccionó uno)
        if ($tipoSorteoId) {
            // Buscamos detalles que pertenezcan a un evento...
            $query->whereHas('eventoSorteo', function ($q) use ($tipoSorteoId) {
                // ...cuyo tipo de sorteo coincida
                $q->where('tipo_sorteo_id', $tipoSorteoId);
            });
        }

        // 6. Cargar las relaciones necesarias (para la tabla)
        $query->with('venta', 'eventoSorteo.tipoSorteo');

        // 7. Ejecutar la consulta y obtener los detalles
        $detalles = $query->get();

        // 8. Calcular el total (para la tarjeta grande)
        $recaudacionTotal = $detalles->sum('monto_apostado');

        // 9. Devolver la vista con todos los datos
        return view('reportes.recaudacion', [
            'tiposSorteo' => $tiposSorteo,
            'detalles' => $detalles,
            'recaudacionTotal' => $recaudacionTotal,
            'filtros' => [ // Para rellenar los inputs del formulario
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo_sorteo_id' => $tipoSorteoId
            ]
        ]);
    }

        /**
     * Muestra el Reporte de Ganadores y Premios (Mockup 3)
     * RF-016, RF-017, RF-018
     */
    public function reporteGanadores(Request $request)
    {
        // 1. Definir fechas por defecto (últimos 7 días)
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->subDays(7)->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $estado = $request->input('estado'); // 'pendiente_pago', 'pagado', 'vencido'

        // 2. Construir la consulta a la tabla 'premios'
        $query = Premio::query();

        // 3. Aplicar filtros de fecha (sobre la fecha de creación del premio)
        $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);

        // 4. Aplicar filtro de Estado (si se seleccionó uno)
        if ($estado) {
            $query->where('estado', $estado);
        }

        // 5. Cargar todas las relaciones necesarias para la tabla
        $query->with(
            'cliente', 
            'ventaDetalle.eventoSorteo.tipoSorteo'
        );
        
        // 6. Ordenar por más reciente y ejecutar
        $premios = $query->orderBy('created_at', 'desc')->get();

        // 7. Devolver la vista con todos los datos
        return view('reportes.ganadores', [
            'premios' => $premios,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => $estado
            ]
        ]);
    }

    /**
     * Cambia el estado de un premio a "Pagado"
     * RF-016
     */
    public function marcarPremioPagado(Request $request, Premio $premio)
    {
        // 1. Validar que el premio esté 'pendiente_pago'
        if ($premio->estado != 'pendiente_pago') {
            return redirect()->route('reportes.ganadores')
                             ->with('error', 'Este premio no se puede marcar como pagado (ya está pagado o vencido).');
        }

        // 2. Validar que no esté vencido (RN-011 / RF-017)
        if (Carbon::today()->gt(Carbon::parse($premio->fecha_vencimiento))) {
            // (Opcional: cambiar estado a 'vencido' aquí)
            $premio->estado = 'vencido';
            $premio->save();
            return redirect()->route('reportes.ganadores')
                             ->with('error', '¡El premio está VENCIDO! No se puede pagar.');
        }

        // 3. Marcar como pagado
        $premio->estado = 'pagado';
        $premio->save();

        return redirect()->route('reportes.ganadores')
                         ->with('success', "Premio #{$premio->id} marcado como PAGADO.");
    }
}