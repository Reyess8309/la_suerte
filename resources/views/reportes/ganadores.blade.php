@extends('layouts.app')
@section('title', 'Reporte de ganadores')

@section('content')

    <div class="container mx-auto p-8">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Reporte de Ganadores y Premios</h1>

        <!-- Mensajes de Éxito o Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow" role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filtros de fechas -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <form action="{{ route('reportes.ganadores') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filtro Fecha Inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio (Creación)</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $filtros['fecha_inicio'] }}" class="mt-1 shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <!-- Filtro Fecha Fin -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin (Creación)</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $filtros['fecha_fin'] }}" class="mt-1 shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <!-- Filtro Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado del Premio</label>
                        <select id="estado" name="estado" class="mt-1 shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Todos los Estados --</option>
                            <option value="pendiente_pago" {{ $filtros['estado'] == 'pendiente_pago' ? 'selected' : '' }}>Pendiente de Pago</option>
                            <option value="pagado" {{ $filtros['estado'] == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="vencido" {{ $filtros['estado'] == 'vencido' ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>
                    <!-- Botón Generar reporte -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            Ver Reporte
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabla de Premios -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Premios Generados</h2>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Cliente Ganador</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Sorteo y Apuesta</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Monto del Premio</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Estado y Vencimiento</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($premios as $premio)
                        <tr class="hover:bg-gray-100">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <p class="font-semibold">{{ $premio->cliente->nombre }} {{ $premio->cliente->apellido }}</p>
                                <p class="text-xs text-gray-600">ID: {{ $premio->cliente->documento_id }}</p>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <p class="font-semibold">{{ $premio->ventaDetalle->eventoSorteo->tipoSorteo->nombre }} ({{ $premio->ventaDetalle->eventoSorteo->numero_evento }})</p>
                                <p>Apostó <span class="font-bold">Q{{ number_format($premio->ventaDetalle->monto_apostado, 2) }}</span> al <span class="font-bold text-blue-700">{{ $premio->ventaDetalle->numero_apostado }}</span></p>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <p class="font-bold text-green-700 text-lg">Q{{ number_format($premio->monto_total, 2) }}</p>
                                @if($premio->bono_cumpleanos > 0)
                                    <p class="text-xs text-yellow-600">(Incluye bono de Q{{ number_format($premio->bono_cumpleanos, 2) }})</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                @if($premio->estado == 'pendiente_pago')
                                    <span class="font-semibold text-yellow-700">Pendiente de Pago</span>
                                    <p class="text-xs text-gray-600">Vence: <span class="font-bold">{{ \Carbon\Carbon::parse($premio->fecha_vencimiento)->format('d/m/Y') }}</span></this>
                                @elseif($premio->estado == 'pagado')
                                    <span class="font-semibold text-green-700">Pagado</span>
                                @else
                                    <!-- mensaje vencido -->
                                    <span class="font-semibold text-red-700">Vencido</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                @if($premio->estado == 'pendiente_pago' && \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($premio->fecha_vencimiento)))
                                    <!-- Si está pendiente Y no ha vencido, mostrar el botón pagado -->
                                    <form action="{{ route('reportes.marcarPagado', $premio->id) }}" method="POST">
                                        @csrf
                                        <button type"submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-md transition duration-300">
                                            Marcar como Pagado
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500">--</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="font-semibold text-gray-700">No se encontraron premios para los filtros seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection