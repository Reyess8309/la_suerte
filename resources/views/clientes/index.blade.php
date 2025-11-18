@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
    <!-- Alerta de Éxito -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <!-- Encabezado de la sección -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Listado de Clientes</h1>
            <a href="{{ route('clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300">
                + Registrar Nuevo Cliente
            </a>
        </div>

        <!-- Tabla de Clientes -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Documento ID</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nombre Completo</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Teléfono</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha Nacimiento</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <!-- Bucle Blade para mostrar los clientes -->
                    @forelse ($clientes as $cliente)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $cliente->documento_id }}</td>
                            <td class="py-3 px-4">{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td class="py-3 px-4">{{ $cliente->telefono }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-blue-600 hover:text-blue-800">Editar</a>
                                <!-- (Aquí iría el form de Eliminar) -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 px-4 text-center text-gray-500">
                                No hay clientes registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection