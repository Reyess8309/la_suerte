<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\EventoSorteo;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Para transacciones
use Illuminate\Support\Facades\Validator; // Para validar
use Illuminate\Support\Facades\Auth; // Para el login (a futuro)
use Barryvdh\DomPDF\Facade\Pdf; // Para el voucher PDF

class VentaController extends Controller
{
    /**
     * Muestra la página principal de ventas (Mockup 1).
     */
    public function index()
    {
        // Buscamos los eventos de HOY que sigan 'abiertos'
        $eventosHoy = EventoSorteo::where('fecha_evento', Carbon::today())
                                ->where('estado', 'abierto')
                                ->with('tipoSorteo') // Cargamos la info del tipo de sorteo
                                ->get();
        
        return view('ventas.index', ['eventosHoy' => $eventosHoy]);
    }

    /**
     * Busca un cliente por su Documento ID y devuelve JSON.
     * Esta ruta es llamada por JavaScript (fetch).
     */
    public function buscarCliente($documento_id)
    {
        $cliente = Cliente::where('documento_id', $documento_id)->first();

        if ($cliente) {
            // Verificamos si es su cumpleaños (RN-010)
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
                    'id' => $cliente->id, // ¡Importante para guardar la Venta!
                    'nombre' => $cliente->nombre . ' ' . $cliente->apellido,
                    'telefono' => $cliente->telefono,
                    'es_cumpleanos' => $esCumpleanos
                ]
            ]);
        }

        // Si no se encuentra
        return response()->json([
            'encontrado' => false,
            'mensaje' => 'Cliente no encontrado. Registre al cliente primero.'
        ]);
    }

    /**
     * ¡ACTUALIZADO!
     * Guarda la venta completa (Venta y VentaDetalles) en la BD.
     * Esta ruta es llamada por JavaScript.
     */
    public function store(Request $request)
    {
        // 1. Validar el Request (que venga cliente_id, y un array de apuestas)
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            // 'usuario_id' => 'required|exists:usuarios,id', // Lo tomaremos de Auth
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

        // 2. Usar DB::transaction para seguridad
        // Esto asegura que si algo falla, no se guarda nada.
        try {
            $venta = DB::transaction(function () use ($datos, &$totalVenta) {
                
                // REVISAR: Hardcodeado. Reemplazar con Auth::id() cuando haya login
                // $usuario_id = Auth::id(); 
                $usuario_id = 1; // Usamos el ID (1) de Don Juan por ahora

                // 3. Crear la Venta (tabla 'ventas')
                // Primero la creamos con total 0
                $nuevaVenta = Venta::create([
                    'cliente_id' => $datos['cliente_id'],
                    'usuario_id' => $usuario_id,
                    'monto_total' => 0, // Lo actualizaremos después
                ]);

                // 4. Recorrer el array de apuestas y crear los VentaDetalle
                foreach ($datos['apuestas'] as $apuesta) {
                    VentaDetalle::create([
                        'venta_id' => $nuevaVenta->id,
                        'evento_sorteo_id' => $apuesta['eventoId'],
                        'numero_apostado' => $apuesta['numero'],
                        'monto_apostado' => $apuesta['monto'],
                    ]);
                    
                    // 5. Calcular el monto_total
                    $totalVenta += $apuesta['monto'];
                }

                // 6. Actualizar la Venta con el total correcto.
                $nuevaVenta->monto_total = $totalVenta;
                $nuevaVenta->save();

                return $nuevaVenta; // Devolvemos la venta creada
            });

            // 7. Devolver una respuesta JSON con el ID de la venta y la URL del voucher
            return response()->json([
                'success' => true,
                'message' => "¡Venta #{$venta->id} registrada exitosamente!",
                'venta_id' => $venta->id,
                'voucher_url' => route('ventas.voucher', $venta->id) // Generamos la URL al voucher
            ]);

        } catch (\Exception $e) {
            // Si algo falló en la transacción, devolvemos un error
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la venta. Intente de nuevo.',
                'error_detalle' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ¡NUEVO!
     * Genera el PDF del voucher (RF-008).
     */
    public function generarVoucher(Venta $venta)
    {
        // Cargamos todas las relaciones que necesitamos para el voucher
        // (cliente, empleado, y los detalles con sus eventos)
        $venta->load('cliente', 'usuario', 'detalles.eventoSorteo.tipoSorteo');

        // Preparamos los datos para la vista
        $datos = [
            'venta' => $venta,
            'fecha' => Carbon::parse($venta->created_at)->format('d/m/Y H:i A'),
            'cliente' => $venta->cliente,
            'empleado' => $venta->usuario,
            'detalles' => $venta->detalles
        ];

        // Cargamos la vista 'voucher.blade.php' con los datos
        $pdf = Pdf::loadView('ventas.voucher', $datos);
        
        // Devolvemos el PDF al navegador
        return $pdf->stream('voucher_venta_'.$venta->id.'.pdf');
    }
}