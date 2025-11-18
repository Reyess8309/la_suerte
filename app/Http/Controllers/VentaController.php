<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\EventoSorteo;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
     // Muestra la página principal de ventas.

    public function index()
    {
        // Busca los eventos abiertos de hoy
        $eventosHoy = EventoSorteo::where('fecha_evento', Carbon::today())
                                ->where('estado', 'abierto')
                                ->with('tipoSorteo')
                                ->get();
        
        return view('ventas.index', ['eventosHoy' => $eventosHoy]);
    }

    /**
     * Busca un cliente por su identificacion
     */
    public function buscarCliente($documento_id)
    {
        $cliente = Cliente::where('documento_id', $documento_id)->first();

        if ($cliente) {
            // Verifica si es su cumpleaños
            $esCumpleanos = false;
            if ($cliente->fecha_nacimiento) {
                $fechaNacimiento = Carbon::parse($cliente->fecha_nacimiento);
                $hoy = Carbon::today();
                if ($fechaNacimiento->month == $hoy->month && $fechaNacimiento->day == $hoy->day) {
                    $esCumpleanos = true;
                }
            }

            return response()->json([
                'encontrado' => true,
                'cliente' => [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre . ' ' . $cliente->apellido,
                    'telefono' => $cliente->telefono,
                    'es_cumpleanos' => $esCumpleanos
                ]
            ]);
        }

        // Si no encuentra resultados
        return response()->json([
            'encontrado' => false,
            'mensaje' => 'Cliente no encontrado. Registre al cliente primero.'
        ]);
    }

    /**
     * Guarda la venta completa en la BD.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'apuestas' => 'required|array|min:1',
            'apuestas.*.eventoId' => 'required|exists:eventos_sorteo,id',
            'apuestas.*.numero' => 'required|string|digits:2',
            'apuestas.*.monto' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Datos inválidos.', 'errors' => $validator->errors()], 400);
        }

        $datos = $validator->validated();
        $totalVenta = 0;

        try {
            $venta = DB::transaction(function () use ($datos, &$totalVenta) {
                
                // REVISAR: Hardcodeado. Reemplazar con Auth::id() cuando haya login
                // $usuario_id = Auth::id(); 
                $usuario_id = 1;

                // Crear la Venta
                $nuevaVenta = Venta::create([
                    'cliente_id' => $datos['cliente_id'],
                    'usuario_id' => $usuario_id,
                    'monto_total' => 0,
                ]);


                foreach ($datos['apuestas'] as $apuesta) {
                    VentaDetalle::create([
                        'venta_id' => $nuevaVenta->id,
                        'evento_sorteo_id' => $apuesta['eventoId'],
                        'numero_apostado' => $apuesta['numero'],
                        'monto_apostado' => $apuesta['monto'],
                    ]);
                    
                    // Calcular el monto_total
                    $totalVenta += $apuesta['monto'];
                }

                // Actualizar la Venta con el total correcto.
                $nuevaVenta->monto_total = $totalVenta;
                $nuevaVenta->save();

                return $nuevaVenta;
            });

            return response()->json([
                'success' => true,
                'message' => "¡Venta #{$venta->id} registrada exitosamente!",
                'venta_id' => $venta->id,
                'voucher_url' => route('ventas.voucher', $venta->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la venta. Intente de nuevo.',
                'error_detalle' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera el PDF del voucher
     */
    public function generarVoucher(Venta $venta)
    {
        $venta->load('cliente', 'usuario', 'detalles.eventoSorteo.tipoSorteo');

        $datos = [
            'venta' => $venta,
            'fecha' => Carbon::parse($venta->created_at)->format('d/m/Y H:i A'),
            'cliente' => $venta->cliente,
            'empleado' => $venta->usuario,
            'detalles' => $venta->detalles
        ];

        $pdf = Pdf::loadView('ventas.voucher', $datos);
        
        return $pdf->stream('voucher_venta_'.$venta->id.'.pdf');
    }
}