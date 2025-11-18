<!DOCTYPE html>
<html>
<head>
    <title>Sorteos del Día</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .desierto { color: red; font-weight: bold; }
        .ganadores { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Tienda "La Suerte" - Sorteos del Día</h2>
        <p>Fecha: {{ $fecha }}</p>
        <p style="font-size: 10px; color: #666;">Generado el: {{ $generado_el }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sorteo</th>
                <th>Evento #</th>
                <th>Estado</th>
                <th>Número Ganador</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eventos as $evento)
                <tr>
                    <td>{{ $evento->tipoSorteo->nombre }}</td>
                    <td>{{ $evento->numero_evento }}</td>
                    <td>{{ ucfirst($evento->estado) }}</td>
                    <td style="font-size: 14px; font-weight: bold;">
                        {{ $evento->numero_ganador ?? '--' }}
                    </td>
                    <td>
                        @if($evento->numero_ganador)
                            @if(!$evento->tieneGanadores())
                                <span class="desierto">GANADOR DESIERTO</span>
                            @else
                                <span class="ganadores">GANADOR</span>
                                <br><small>{{ $evento->obtenerNombresGanadores() }}</small>
                            @endif
                        @else
                            Pendiente
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>