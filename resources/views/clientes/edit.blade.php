@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
    <div class="container mx-auto p-8 max-w-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Cliente</h1>

        <div class="bg-white shadow-lg rounded-lg p-8">
            <!-- Formulario con metodo PUT -->
            <form action="{{ route('clientes.update', $cliente->documento_id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Documento ID -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Documento ID</label>
                    <input type="text" value="{{ $cliente->documento_id }}" class="shadow appearance-none border bg-gray-100 rounded w-full py-2 px-3 text-gray-700 leading-tight cursor-not-allowed" readonly>
                </div>

                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2">Apellido</label>
                    <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $cliente->apellido) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('clientes.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection