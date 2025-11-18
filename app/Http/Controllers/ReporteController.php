<?php

namespace App\Http\Controllers;

use App\Models\TipoSorteo;
use App\Models\VentaDetalle;
use App\Models\Premio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Muestra el Reporte de Recaudación
     */
    public function reporteRecaudacion(Request $request)
    {
        // 1. Obtener los tipos de sorteo para el dropdown de filtro
        $tiposSorteo = TipoSorteo::all();

        // 2. Definir fechas por defecto
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $tipoSorteoId = $request->input('tipo_sorteo_id');

        // 3. Construir la consulta a la base de datos
        $query = VentaDetalle::query();

        // 4. Aplicar filtros de fecha
        $query->whereHas('venta', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        });

        // 5. Aplicar filtro de Tipo de Sorteo
        if ($tipoSorteoId) {
            // Buscamos detalles que pertenezcan a un evento que coincida con el tipo seleccionado
            $query->whereHas('eventoSorteo', function ($q) use ($tipoSorteoId) {
                $q->where('tipo_sorteo_id', $tipoSorteoId);
            });
        }

        // 6. Cargar las relaciones necesarias
        $query->with('venta', 'eventoSorteo.tipoSorteo');

        // 7. Ejecutar la consulta y obtener los detalles
        $detalles = $query->get();

        // 8. Calcular el total
        $recaudacionTotal = $detalles->sum('monto_apostado');

        // 9. Devolver la vista con todos los datos
        return view('reportes.recaudacion', [
            'tiposSorteo' => $tiposSorteo,
            'detalles' => $detalles,
            'recaudacionTotal' => $recaudacionTotal,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo_sorteo_id' => $tipoSorteoId
            ]
        ]);
    }

    /**
     * Generar PDF de Reporte de Recaudación
     */
    public function descargarPDFRecaudacion(Request $request)
    {
        // Reutilizamos la misma lógica de filtros
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $tipoSorteoId = $request->input('tipo_sorteo_id');

        $query = VentaDetalle::query();
        $query->whereHas('venta', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        });

        if ($tipoSorteoId) {
            $query->whereHas('eventoSorteo', function ($q) use ($tipoSorteoId) {
                $q->where('tipo_sorteo_id', $tipoSorteoId);
            });
        }
        $query->with('venta', 'eventoSorteo.tipoSorteo');
        $detalles = $query->get();
        $recaudacionTotal = $detalles->sum('monto_apostado');

        // Generamos el PDF
        $pdf = Pdf::loadView('reportes.pdf_recaudacion', [
            'detalles' => $detalles,
            'recaudacionTotal' => $recaudacionTotal,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'generado_el' => Carbon::now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->stream('reporte_recaudacion.pdf');
    }

    /**
     * Muestra el Reporte de Ganadores y Premios
     */
    public function reporteGanadores(Request $request)
    {
        // 1. Definir fechas por defecto, 7 dias
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->subDays(7)->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $estado = $request->input('estado');

        // 2. Construir la consulta a la labla
        $query = Premio::query();

        // 3. Aplicar filtros de fecha
        $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);

        // 4. Aplicar filtro de Estado
        if ($estado) {
            $query->where('estado', $estado);
        }

        // 5. Cargar lo necesario para la tabla
        $query->with(
            'cliente', 
            'ventaDetalle.eventoSorteo.tipoSorteo'
        );
        
        // 6. Ordenar por más reciente y ejecutar
        $premios = $query->orderBy('created_at', 'desc')->get();

        // 7. Cargar la vista con todos los datos
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
     * Generar PDF de Ganadores
     */
    public function descargarPDFGanadores(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->subDays(7)->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::today()->toDateString());
        $estado = $request->input('estado');

        $query = Premio::query();
        $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        
        if ($estado) {
            $query->where('estado', $estado);
        }
        $query->with('cliente', 'ventaDetalle.eventoSorteo.tipoSorteo');
        $premios = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reportes.pdf_ganadores', [
            'premios' => $premios,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'generado_el' => Carbon::now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->stream('reporte_ganadores.pdf');
    }

    /**
     * Cambiar el estado de un premio a "Pagado"
     */
    public function marcarPremioPagado(Request $request, Premio $premio)
    {
        // 1. Validar que el premio esté 'pendiente_pago'
        if ($premio->estado != 'pendiente_pago') {
            return redirect()->route('reportes.ganadores')
                             ->with('error', 'Este premio no se puede marcar como pagado (ya está pagado o vencido).');
        }

        // 2. Validar que no esté vencido
        if (Carbon::today()->gt(Carbon::parse($premio->fecha_vencimiento))) {
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