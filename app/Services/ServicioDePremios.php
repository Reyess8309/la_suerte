<?php

namespace App\Services;

use App\Models\EventoSorteo;
use App\Models\VentaDetalle;
use App\Models\Premio;
use Carbon\Carbon;

/**
 * Servicio para manejar la lógica de cálculo y generación de premios.
 * (Basado en el Diagrama de Actividades)
 */
class ServicioDePremios
{
    /**
     * Procesa todos los ganadores de un evento de sorteo.
     * RF-011, RF-012, RF-013, RF-015
     */
    public function procesarGanadores(EventoSorteo $evento)
    {
        $numeroGanador = $evento->numero_ganador;
        $fechaSorteo = Carbon::parse($evento->fecha_evento);
        $factorPago = $evento->tipoSorteo->factor_pago;

        // 1. Buscar todas las apuestas que ganaron
        $detallesGanadores = VentaDetalle::where('evento_sorteo_id', $evento->id)
                                ->where('numero_apostado', $numeroGanador)
                                ->with('venta.cliente') // Cargamos el cliente para el cumpleaños
                                ->get();

        $contadorPremios = 0;

        // 2. Iterar sobre cada apuesta ganadora
        foreach ($detallesGanadores as $detalle) {
            
            // Verificamos si ya existe un premio (por si se corre 2 veces)
            $premioExistente = Premio::where('venta_detalle_id', $detalle->id)->first();
            if ($premioExistente) {
                continue;
            }

            $cliente = $detalle->venta->cliente;
            $montoApostado = $detalle->monto_apostado;

            // 3. Calcular Monto Base (RF-012)
            $montoBase = $montoApostado * $factorPago;

            // 4. Calcular Bono Cumpleaños (RN-010 / RF-013)
            $bonoCumpleanos = 0;
            if ($cliente->fecha_nacimiento) {
                $fechaNacimiento = Carbon::parse($cliente->fecha_nacimiento);
                if ($fechaNacimiento->month == $fechaSorteo->month && $fechaNacimiento->day == $fechaSorteo->day) {
                    $bonoCumpleanos = $montoBase * 0.10; // 10% de bono
                }
            }
            
            // 5. Calcular Total
            $montoTotal = $montoBase + $bonoCumpleanos;

            // 6. Calcular Fecha de Vencimiento (RN-011 / RF-015)
            // 5 días hábiles (Carbon suma días de Lunes a Viernes)
            $fechaVencimiento = $fechaSorteo->copy()->addWeekdays(5);

            // 7. Crear el registro del Premio
            Premio::create([
                'venta_detalle_id' => $detalle->id,
                'cliente_id' => $cliente->id,
                'monto_base' => $montoBase,
                'bono_cumpleanos' => $bonoCumpleanos,
                'monto_total' => $montoTotal,
                'estado' => 'pendiente_pago',
                'fecha_vencimiento' => $fechaVencimiento,
            ]);

            $contadorPremios++;
        }
        
        // 8. Si no hubo ganadores, marcamos el evento (RF-019)
        if ($contadorPremios == 0) {
            // (Opcional) Guardar "desierto" en algún lado
            // Por ahora, solo devolvemos 0
        }

        return $contadorPremios; // Devolvemos cuántos premios se generaron
    }
}