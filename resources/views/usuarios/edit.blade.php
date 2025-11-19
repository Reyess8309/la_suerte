@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
    <div class="container mx-auto p-8 max-w-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Empleado</h1>

        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="rol" class="block text-gray-700 text-sm font-bold mb-2">Rol</label>
                    <select id="rol" name="rol" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500" required>
                        <option value="employee" {{ $usuario->rol == 'employee' ? 'selected' : '' }}>Empleado</option>
                        <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <hr class="my-4 border-gray-300">
                <p class="text-sm text-gray-500 mb-2">Dejar en blanco para mantener la contraseña actual.</p>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nueva Contraseña (Opcional)</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('usuarios.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Actualizar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection