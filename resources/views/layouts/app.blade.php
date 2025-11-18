<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda "La Suerte" - @yield('title', 'Sistema de Sorteos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex-grow: 1;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">

    <!-- BARRA DE NAVEGACIÓN PRINCIPAL -->
    <nav class="bg-blue-800 text-white shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo o Título Principal -->
                <div class="flex-shrink-0">
                    <a href="{{ route('ventas.index') }}" class="text-2xl font-bold">Tienda "La Suerte"</a>
                </div>
                
                <!-- Menú Principal -->
                <div class="hidden md:flex md:space-x-4">
                    <a href="{{ route('ventas.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Ventas</a>
                    <a href="{{ route('clientes.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Clientes</a>
                    <a href="{{ route('usuarios.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Empleados</a>
                    
                    <div class="h-6 border-l border-blue-600 my-auto"></div>

                    <a href="{{ route('sorteos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Panel de Sorteos</a>
                    <a href="{{ route('reportes.recaudacion') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Reporte Recaudación</a>
                    <a href="{{ route('reportes.ganadores') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Reporte Ganadores</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Aqui se inyectará el contenido de tus otras vistas -->
            @yield('content')
        </div>
    </main>

</body>
</html>