@extends('layouts.app')
@section('title', 'Reporte de recaudacion')

@section('content')

    <div class="container mx-auto p-8">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Reporte de Recaudación</h1>

        <!-- Barra de Filtros -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <form action="{{ route('reportes.recaudacion') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filtro Fecha Inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $filtros['fecha_inicio'] }}" class="mt-1 shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <!-- Filtro Fecha Fin -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $filtros['fecha_fin'] }}" class="mt-1 shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <!-- Filtro Tipo de Sorteo -->
                    <div>
                        <label for="tipo_sorteo_id" class="block text-sm font-medium text-gray-700">Tipo de Sorteo</label>
                        <select id="tipo_sorteo_id" name="tipo_sorteo_id" class="mt-1 shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Todos los Sorteos --</option>
                            @foreach ($tiposSorteo as $tipo)
                                <option value="{{ $tipo->id }}" {{ $filtros['tipo_sorteo_id'] == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Botón de Generar reporte -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            Ver Reporte
                        </button>

                        <button type="submit" formaction="{{ route('reportes.recaudacion.pdf') }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                            PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Total -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white rounded-lg shadow-xl p-8 mb-6 text-center">
            <h2 class="text-xl font-semibold uppercase tracking-wide">Recaudación Total del Período</h2>
            <p class="text-5xl font-extrabold mt-2">Q{{ number_format($recaudacionTotal, 2) }}</p>
        </div>

        <!-- Tabla -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Desglose de Recaudación</h2>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Fecha Venta
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Sorteo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Voucher No.
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Número Apostado
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Recaudación (Q)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detalles as $detalle)
                        <tr class="hover:bg-gray-100">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                {{ $detalle->venta->created_at->format('d/m/Y H:i A') }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <p class="font-semibold">{{ $detalle->eventoSorteo->tipoSorteo->nombre }} ({{ $detalle->eventoSorteo->numero_evento }})</p>
                                <p class="text-xs text-gray-600">Fecha Evento: {{ \Carbon\carbon::parse($detalle->eventoSorteo->fecha_evento)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                {{ $detalle->venta_id }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm font-bold text-blue-700">
                                {{ $detalle->numero_apostado }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm font-semibold">
                                Q{{ number_format($detalle->monto_apostado, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="font-semibold text-gray-700">No se encontraron ventas para los filtros seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection