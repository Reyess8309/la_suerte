<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- El CSRF Token es VITAL para que JavaScript pueda enviar datos a Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Módulo de Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pequeño estilo para el spinner de carga */
        .loader {
            border-top-color: #3498db;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- CAMBIO 1: Contenedor principal para ocupar toda la altura -->
    <div class="min-h-screen flex flex-col">

        <div class="container mx-auto p-4 md:p-8 flex flex-col flex-1">
            
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Módulo de Venta</h1>

            <!-- Contenedor de Alertas Globales (para JS) -->
            <div id="global-message" class="mb-4"></div>

            <!-- Spinner de Carga Global -->
            <div id="loading-spinner" class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4 hidden"></div>

            <!-- CAMBIO 2: Grid principal que se expande para llenar el espacio -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1">

                <!-- ===================================== -->
                <!-- COLUMNA 1: BÚSQUEDA DE CLIENTE        -->
                <!-- ===================================== -->
                <!-- CAMBIO 3: Columna con altura completa y scroll interno si es necesario -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-lg flex flex-col h-full">
                    <h2 class="text-xl font-bold mb-4">1. Buscar Cliente</h2>
                    
                    <!-- Formulario de Búsqueda -->
                    <form id="cliente-search-form">
                        <label for="documento_id" class="block text-sm font-medium text-gray-700">Documento ID (CUI)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" id="documento_id_input" class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="1234567890123">
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 rounded-r-md">
                                Buscar
                            </button>
                        </div>
                    </form>

                    <!-- Info del Cliente (Oculto por defecto) -->
                    <div id="cliente-info-box" class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg hidden">
                        <h3 class="text-lg font-semibold text-blue-800" id="cliente-nombre"></h3>
                        <p class="text-sm text-blue-700" id="cliente-telefono"></p>
                        <input type="hidden" id="cliente_id_hidden">
                        <button id="limpiar-cliente-btn" class="mt-2 text-sm text-red-600 hover:text-red-800">Limpiar</button>
                    </div>
                    
                    <!-- Aviso de Cumpleaños (Oculto por defecto) -->
                    <div id="cumpleanos-aviso" class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg hidden shadow">
                        <p class="font-bold">¡Feliz Cumpleaños!</p>
                        <p class="text-sm">El cliente recibirá un 10% más en sus premios hoy.</p>
                    </div>
                </div>

                <!-- ===================================== -->
                <!-- COLUMNA 2: SORTEOS DISPONIBLES      -->
                <!-- ===================================== -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-lg flex flex-col h-full">
                    <h2 class="text-xl font-bold mb-4">2. Sorteos Disponibles (Hoy)</h2>
                    
                    <!-- REFINAMIENTO: El scroll se aplica solo a la lista, no a la columna entera -->
                    <div id="sorteos-lista" class="space-y-4 flex-1 overflow-y-auto">
                        @forelse ($eventosHoy as $evento)
                            <div class="border p-4 rounded-lg shadow-sm sorteo-card">
                                <h3 class="font-semibold text-lg">{{ $evento->tipoSorteo->nombre }} (Sorteo {{ $evento->numero_evento }})</h3>
                                <p class="text-sm text-gray-600">Paga Q{{ $evento->tipoSorteo->factor_pago }} por Q1.00</p>
                                <div class="flex space-x-2 mt-3">
                                    <input type="text" class="numero-apostado w-24 shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Nº (00-99)" maxlength="2">
                                    <input type="number" class="monto-apostado w-24 shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Monto Q." min="1">
                                    <button class="add-to-cart-btn flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-lg text-sm transition duration-300"
                                            data-evento-id="{{ $evento->id }}"
                                            data-evento-nombre="{{ $evento->tipoSorteo->nombre }} (Sorteo {{ $evento->numero_evento }})">
                                        Añadir
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center font-semibold">No hay sorteos abiertos en este momento.</p>
                            <p class="text-gray-400 text-center text-sm">Asegúrese de "Generar Sorteos para Hoy" en el panel de Admin.</p>
                        @endforelse
                    </div>
                </div>

                <!-- ===================================== -->
                <!-- COLUMNA 3: DETALLE DE VENTA ("CARRITO") -->
                <!-- ===================================== -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-lg flex flex-col h-full">
                    <h2 class="text-xl font-bold mb-4">3. Detalle de Venta</h2>
                    
                    <!-- REFINAMIENTO: El scroll se aplica solo al "carrito" -->
                    <div id="detalle-venta-lista" class="space-y-2 mb-4 flex-1 overflow-y-auto">
                        <p class="text-gray-400 text-center">Añada apuestas desde la Columna 2...</p>
                    </div>

                    <!-- Total (siempre visible) -->
                    <hr class="my-4">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total:</span>
                        <span id="total-venta">Q0.00</span>
                    </div>

                    <!-- Botones de Acción (siempre visibles) -->
                    <div class="mt-6 space-y-3">
                        <button id="registrar-venta-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 opacity-50 cursor-not-allowed" disabled>
                            Registrar Venta y Generar Voucher
                        </button>
                        <button id="limpiar-venta-btn" class="w-full bg-red-500 hover:bg-red-600 text-white font-light py-2 px-4 rounded-lg transition duration-300">
                            Limpiar Todo
                        </button>
                    </div>
                </div>

            </div> <!-- Fin del grid de 3 columnas -->
        </div> <!-- Fin del contenedor de página -->
    </div> <!-- Fin del contenedor full-screen -->

    <!-- ===================================== -->
    <!-- JAVASCRIPT                          -->
    <!-- ===================================== -->
    <script>
        // Este script se ejecuta cuando el HTML está completamente cargado
        document.addEventListener('DOMContentLoaded', () => {

            // --- ESTADO DE LA APLICACIÓN ---
            let clienteSeleccionado = null;
            let apuestas = []; // "Carrito" de apuestas

            // --- SELECTORES DE ELEMENTOS DEL DOM ---
            // Columna 1
            const clienteSearchForm = document.getElementById('cliente-search-form');
            const documentoIdInput = document.getElementById('documento_id_input');
            const clienteInfoBox = document.getElementById('cliente-info-box');
            const clienteNombreEl = document.getElementById('cliente-nombre');
            const clienteTelefonoEl = document.getElementById('cliente-telefono');
            const clienteIdHidden = document.getElementById('cliente_id_hidden');
            const cumpleanosAviso = document.getElementById('cumpleanos-aviso');
            const limpiarClienteBtn = document.getElementById('limpiar-cliente-btn');

            // Columna 3
            const listaDetalle = document.getElementById('detalle-venta-lista');
            const totalVentaEl = document.getElementById('total-venta');
            const registrarVentaBtn = document.getElementById('registrar-venta-btn');
            const limpiarVentaBtn = document.getElementById('limpiar-venta-btn');
            
            // Globales
            const loadingSpinner = document.getElementById('loading-spinner');
            const globalMessage = document.getElementById('global-message');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


            // --- EVENT LISTENERS ---
            clienteSearchForm.addEventListener('submit', buscarCliente);
            limpiarClienteBtn.addEventListener('click', limpiarCliente);
            limpiarVentaBtn.addEventListener('click', limpiarTodo);
            registrarVentaBtn.addEventListener('click', registrarVenta);

            // Delegación de eventos para botones CREADOS DINÁMICAMENTE
            // (Añadir a carrito y Eliminar de carrito)
            document.addEventListener('click', (e) => {
                // Si se hace clic en un botón "Añadir"
                if (e.target && e.target.classList.contains('add-to-cart-btn')) {
                    anadirApuesta(e.target);
                }
                // Si se hace clic en un botón "Eliminar" (X)
                if (e.target && e.target.classList.contains('remove-from-cart-btn')) {
                    eliminarApuesta(e.target.dataset.index);
                }
            });

            
            // --- FUNCIONES ---

            /**
             * 1. BUSCAR CLIENTE
             * Llama a la API interna para buscar un cliente por ID.
             */
            async function buscarCliente(e) {
                e.preventDefault();
                const documentoId = documentoIdInput.value;

                if (!documentoId) {
                    mostrarMensaje('Por favor ingrese un Documento ID.', 'error');
                    return;
                }

                mostrarLoading(true);
                limpiarMensaje();
                
                try {
                    // Usamos la ruta nombrada para construir la URL
                    const urlTemplate = `{{ route('api.clientes.buscar', ['documento_id' => 'DUMMY_ID']) }}`;
                    const url = urlTemplate.replace('DUMMY_ID', documentoId);
                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.encontrado) {
                        clienteSeleccionado = data.cliente;
                        clienteNombreEl.textContent = data.cliente.nombre;
                        clienteTelefonoEl.textContent = `Tel: ${data.cliente.telefono || 'N/A'}`;
                        clienteIdHidden.value = data.cliente.id;
                        clienteInfoBox.classList.remove('hidden');

                        // Mostrar aviso de cumpleaños (RN-010)
                        if (data.cliente.es_cumpleanos) {
                            cumpleanosAviso.classList.remove('hidden');
                        }

                        // Bloquear formulario
                        documentoIdInput.disabled = true;
                        clienteSearchForm.querySelector('button').disabled = true;

                    } else {
                        mostrarMensaje('Cliente no encontrado. Por favor, regístrelo primero.', 'error');
                        limpiarCliente();
                    }
                } catch (error) {
                    console.error('Error buscando cliente:', error);
                    mostrarMensaje('Error de red al buscar cliente.', 'error');
                } finally {
                    mostrarLoading(false);
                    actualizarEstadoBotones();
                }
            }

            /**
             * 2. AÑADIR APUESTA (AL "CARRITO")
             * Se llama al presionar un botón "Añadir".
             */
            function anadirApuesta(boton) {
                limpiarMensaje();
                const card = boton.closest('.sorteo-card');
                const numeroInput = card.querySelector('.numero-apostado');
                const montoInput = card.querySelector('.monto-apostado');
                
                const numero = numeroInput.value;
                const monto = parseFloat(montoInput.value);

                // Validación simple
                if (!/^\d{2}$/.test(numero)) {
                    mostrarMensaje('El número debe tener 2 dígitos (ej. 05, 30, 99).', 'error');
                    numeroInput.focus();
                    return;
                }
                if (isNaN(monto) || monto <= 0) {
                    mostrarMensaje('El monto debe ser un número mayor a cero.', 'error');
                    montoInput.focus();
                    return;
                }

                // Añadir al estado
                apuestas.push({
                    eventoId: boton.dataset.eventoId,
                    eventoNombre: boton.dataset.eventoNombre,
                    numero: numero,
                    monto: monto
                });

                // Actualizar la UI
                actualizarVistaDetalle();
                actualizarEstadoBotones();

                // Limpiar inputs
                numeroInput.value = '';
                montoInput.value = '';
                numeroInput.focus(); // Mover foco al siguiente número
            }

            /**
             * 3. ELIMINAR APUESTA (DEL "CARRITO")
             * Se llama al presionar (X)
             */
            function eliminarApuesta(index) {
                apuestas.splice(index, 1); // Elimina 1 elemento en la posición 'index'
                actualizarVistaDetalle();
                actualizarEstadoBotones();
            }

            /**
             * 4. REGISTRAR VENTA (GUARDAR EN BD)
             * Llama a la ruta 'ventas.store' con todos los datos.
             */
            async function registrarVenta() {
                if (!clienteSeleccionado || apuestas.length === 0) {
                    mostrarMensaje('Debe seleccionar un cliente y añadir al menos una apuesta.', 'error');
                    return;
                }

                mostrarLoading(true);
                limpiarMensaje();
                registrarVentaBtn.disabled = true;

                // **NOTA IMPORTANTE**: Aún no tenemos sistema de Login (Día 5-6).
                // Por ahora, asumiremos que el 'usuario_id' (empleado) es '1'.
                // Esto debe reemplazarse cuando haya autenticación.
                const payload = {
                    cliente_id: clienteSeleccionado.id,
                    usuario_id: 1, // REVISAR: Hardcodeado. Reemplazar con Auth::id()
                    apuestas: apuestas // El array de objetos
                };

                try {
                    const response = await fetch('{{ route("ventas.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken, // Token de seguridad de Laravel
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    // --- ¡AQUÍ ESTÁ EL CAMBIO! ---
                    if (response.ok && data.success) {
                        // 1. Mostrar el mensaje de éxito que viene del servidor
                        mostrarMensaje(data.message, 'success');
                        
                        // 2. Abrir el voucher PDF en una nueva pestaña
                        window.open(data.voucher_url, '_blank');
                        
                        // 3. Limpiar la interfaz para la siguiente venta
                        limpiarTodo();
                        
                    } else {
                        // Error de validación del backend u otro
                        mostrarMensaje(data.message || 'Error al guardar la venta.', 'error');
                    }

                } catch (error) {
                    console.error('Error registrando venta:', error);
                    mostrarMensaje('Error de red al guardar la venta.', 'error');
                } finally {
                    mostrarLoading(false);
                    // 'limpiarTodo' ya llama a 'actualizarEstadoBotones'
                }
            }


            // --- FUNCIONES UTILITARIAS ---

            /**
             * RENDER: Actualiza la Columna 3 (Carrito y Total)
             */
            function actualizarVistaDetalle() {
                listaDetalle.innerHTML = ''; // Limpiar lista
                let total = 0;

                if (apuestas.length === 0) {
                    listaDetalle.innerHTML = '<p class="text-gray-400 text-center">Añada apuestas desde la Columna 2...</p>';
                } else {
                    apuestas.forEach((apuesta, index) => {
                        total += apuesta.monto;
                        listaDetalle.innerHTML += `
                            <div class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50">
                                <div>
                                    <p class="font-semibold">${apuesta.eventoNombre}</p>
                                    <p class="text-sm text-gray-700">Número: <span class="font-bold text-blue-600">${apuesta.numero}</span> - Monto: <span class="font-bold">Q${apuesta.monto.toFixed(2)}</span></p>
                                </div>
                                <button data-index="${index}" class="remove-from-cart-btn text-red-500 hover:text-red-700 font-bold text-lg">&times;</button>
                            </div>
                        `;
                    });
                }
                totalVentaEl.textContent = `Q${total.toFixed(2)}`;
            }

            /**
             * Lógica de UI: Activa/desactiva el botón de Registrar Venta
             */
            function actualizarEstadoBotones() {
                const listoParaRegistrar = clienteSeleccionado && apuestas.length > 0;
                
                if (listoParaRegistrar) {
                    registrarVentaBtn.disabled = false;
                    registrarVentaBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    registrarVentaBtn.disabled = true;
                    registrarVentaBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
            
            function limpiarCliente() {
                clienteSeleccionado = null;
                clienteInfoBox.classList.add('hidden');
                cumpleanosAviso.classList.add('hidden');
                documentoIdInput.value = '';
                documentoIdInput.disabled = false;
                clienteSearchForm.querySelector('button').disabled = false;
                actualizarEstadoBotones();
            }

            function limpiarTodo() {
                limpiarCliente();
                apuestas = [];
                actualizarVistaDetalle(); // Esto limpia el carrito y pone total en 0
                limpiarMensaje();
            }

            function mostrarLoading(mostrar) {
                loadingSpinner.classList.toggle('hidden', !mostrar);
            }

            function limpiarMensaje() {
                globalMessage.innerHTML = '';
                globalMessage.className = 'mb-4'; // Resetea clases
            }

            function mostrarMensaje(mensaje, tipo = 'error') {
                limpiarMensaje();
                const tipoClase = tipo === 'success' 
                    ? 'bg-green-100 border border-green-400 text-green-700'
                    : 'bg-red-100 border border-red-400 text-red-700';
                
                globalMessage.innerHTML = `<div class="${tipoClase} px-4 py-3 rounded-lg relative" role="alert">${mensaje}</div>`;
            }
            
            // --- INICIALIZACIÓN ---
            actualizarEstadoBotones(); // Asegura estado inicial correcto

        }); // Fin de DOMContentLoaded
    </script>
</body>
</html>