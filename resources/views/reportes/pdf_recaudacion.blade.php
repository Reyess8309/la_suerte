<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Recaudación</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Tienda "La Suerte" - Reporte de Recaudación</h2>
        <p>Del: {{ $fecha_inicio }} Al: {{ $fecha_fin }}</p>
        <p style="font-size: 10px; color: #666;">Generado el: {{ $generado_el }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha Venta</th>
                <th>Sorteo</th>
                <th>Voucher #</th>
                <th>Número</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->venta->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $detalle->eventoSorteo->tipoSorteo->nombre }} ({{ $detalle->eventoSorteo->numero_evento }})</td>
                    <td>{{ $detalle->venta_id }}</td>
                    <td>{{ $detalle->numero_apostado }}</td>
                    <td>Q{{ number_format($detalle->monto_apostado, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL RECAUDADO: Q{{ number_format($recaudacionTotal, 2) }}
    </div>
</body>
</html>