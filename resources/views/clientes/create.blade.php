<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Cliente</title>
    <!-- Carga Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Contenedor Principal -->
    <div class="container mx-auto p-8 max-w-lg">
        
        <!-- Encabezado -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Registrar Nuevo Cliente</h1>

        <!-- Formulario -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            
            <!-- El formulario enviará los datos al método 'store' del controlador -->
            <form action="{{ route('clientes.store') }}" method="POST">
                <!-- Token de seguridad OBLIGATORIO en Laravel -->
                @csrf

                <!-- Campo Documento ID -->
                <div class="mb-4">
                    <label for="documento_id" class="block text-gray-700 text-sm font-bold mb-2">Documento de Identificación (DPI)</label>
                    <input type="text" id="documento_id" name="documento_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Campo Nombre -->
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Campo Apellido -->
                <div class="mb-4">
                    <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Campo Fecha de Nacimiento -->
                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Campo Teléfono -->
                <div class="mb-4">
                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('clientes.index') }}" class="text-gray-600 hover:text-gray-800 font-medium transition duration-300">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>