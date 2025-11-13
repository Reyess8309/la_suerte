<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Sorteos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="container mx-auto p-8">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Panel de Sorteos (Admin)</h1>
            <!-- Botón para generar eventos -->
            <!-- Usamos un formulario POST para llamar a la acción -->
            <form action="{{ route('sorteos.generar') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Generar Sorteos para Hoy ({{ $fecha }})
                </button>
            </form>
        </div>

        <!-- Mensajes de Éxito o Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative mb-4 shadow" role="alert">
                <strong class="font-bold">Aviso:</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif


        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Eventos Generados para Hoy ({{ $fecha }})</h2>

        <!-- Tabla de Eventos del Día (Mockup 2) -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Sorteo (Evento)
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Número Ganador (00-99)
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eventos as $evento)
                        <tr class="hover:bg-gray-100">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $evento->tipoSorteo->nombre }} (Sorteo {{ $evento->numero_evento }})</p>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $evento->estado == 'abierto' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $evento->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <!-- (Aquí irá el formulario para ingresar el número) -->
                                <input type="text" maxlength="2" class="shadow-sm appearance-none border rounded w-20 py-2 px-3 text-gray-700" placeholder="00">
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded-full shadow transition duration-300">
                                    Guardar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="font-semibold text-gray-700">No hay eventos generados para hoy.</p>
                                <p class="text-gray-500">Haz clic en el botón verde de arriba para generarlos.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>