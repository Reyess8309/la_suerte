<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    
    <!--para estilos específicos de cada página-->
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
                
                <!-- Menú Principal-->
                <div class="hidden md:flex md:space-x-4">
                    <a href="{{ route('ventas.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Ventas</a>
                    <a href="{{ route('clientes.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Clientes</a>
                    
                    <!-- Solo Admin ve estos enlaces -->
                    @if(Auth::user()->rol == 'admin')
                        <a href="{{ route('usuarios.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Empleados</a>
                        
                        <div class="h-6 border-l border-blue-600 my-auto"></div>

                        <a href="{{ route('sorteos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Sorteos</a>
                        <a href="{{ route('reportes.recaudacion') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Reporte Recaudación</a>
                        <a href="{{ route('reportes.ganadores') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Reporte Ganadores</a>
                    @endif
                </div>

                <!-- Info de Usuario y Botón de Logout -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- Comprueba si el usuario está logueado -->
                    @auth
                        <span class="text-sm text-blue-200">Hola, {{ Auth::user()->nombre }}</span>
                        <!-- Formulario de Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium bg-blue-700 hover:bg-blue-600">
                                Cerrar Sesión
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL DE LA PÁGINA -->
    <main class="main-content py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

</body>
</html>