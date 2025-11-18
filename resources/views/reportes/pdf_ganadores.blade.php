<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Ganadores</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Tienda "La Suerte" - Reporte de Ganadores</h2>
        <p>Del: {{ $fecha_inicio }} Al: {{ $fecha_fin }}</p>
        <p style="font-size: 10px; color: #666;">Generado el: {{ $generado_el }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Sorteo / Apuesta</th>
                <th>Premio Total</th>
                <th>Estado</th>
                <th>Vence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($premios as $premio)
                <tr>
                    <td>{{ $premio->cliente->nombre }} {{ $premio->cliente->apellido }}<br><small>{{ $premio->cliente->documento_id }}</small></td>
                    <td>{{ $premio->ventaDetalle->eventoSorteo->tipoSorteo->nombre }}<br>Num: <b>{{ $premio->ventaDetalle->numero_apostado }}</b> (Q{{ number_format($premio->ventaDetalle->monto_apostado, 2) }})</td>
                    <td>Q{{ number_format($premio->monto_total, 2) }}
                        @if($premio->bono_cumpleanos > 0) <br><small>(Con Bono)</small> @endif
                    </td>
                    <td>{{ ucfirst($premio->estado) }}</td>
                    <td>{{ \Carbon\Carbon::parse($premio->fecha_vencimiento)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>