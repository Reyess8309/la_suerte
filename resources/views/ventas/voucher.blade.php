<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voucher de Venta #{{ $venta->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 300px; /* Ancho de ticket de impresora térmica */
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            padding: 4px;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #f0f0f0;
            text-align: left;
        }
        .total {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Tienda "La Suerte"
        </div>

        <div class="info">
            <p><strong>Voucher No:</strong> {{ $venta->id }}</p>
            <p><strong>Fecha:</strong> {{ $fecha }}</p>
            <p><strong>Cliente:</strong> {{ $cliente->nombre }} {{ $cliente->apellido }}</p>
            <p><strong>Atendió:</strong> {{ $empleado->nombre }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Sorteo</th>
                    <th>Número</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->eventoSorteo->tipoSorteo->nombre }} ({{ $detalle->eventoSorteo->numero_evento }})</td>
                        <td>{{ $detalle->numero_apostado }}</td>
                        <td style="text-align: right;">Q{{ number_format($detalle->monto_apostado, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            TOTAL PAGADO: Q{{ number_format($venta->monto_total, 2) }}
        </div>

        <div class="footer">
            ¡Gracias por su compra!
            <p>Guarde este voucher para reclamar su premio.</p>
        </div>
    </div>
</body>
</html>