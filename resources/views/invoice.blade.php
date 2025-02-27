<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; margin: auto; border: 1px solid #ddd; padding: 10px; border-radius: 8px; }
        .header {text-align: center; margin-bottom: 10px; height: 80px;}
        .header h2 { margin: 5px 0; }
        .header p {margin: 2px 0; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; height: 200px; }
        .table, .table th, .table td { border: 1px solid black; text-align: center; padding: 8px; }
        .total { text-align: right; }
        .parent-table { margin-bottom: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CUENTA DE COBRO No. {{ $remission['consecutive'] }}</h2>
            @if (!$special)
                <p>Estampados Decorfer - 98.534.642-7</p>
                <p>Calle 24 # 65E - 25, Medellín - 3107192473</p>
                <p>ferchohc37@hotmail.com</p>
            @endif
        </div>
        <table class="parent-table" width="100%">
            <tr>
                <td width="50%" valign="top">
                    <table width="100%" cellspacing="0" cellpadding="5">
                        <tr>
                            <td><strong>Cliente:</strong></td>
                            @if (!$special)
                                <td>{{ $remission['customer']['name'] }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>Dirección:</strong></td>
                            @if (!$special)
                                <td>{{ $remission['customer']['address'] }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>Celular:</strong></td>
                            @if (!$special)
                                <td>{{ $remission['customer']['cellphone'] }}</td>
                            @endif
                        </tr>
                    </table>
                </td>
        
                <td width="50%" valign="top">
                    <table width="100%" cellspacing="0" cellpadding="5">
                        <tr>
                            <td><strong>NIT:</strong></td>
                            @if (!$special)
                                <td>{{ $remission['customer']['identification'] }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>Teléfono:</strong></td>
                            @if (!$special)
                            <td>{{ $remission['customer']['phone'] }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td>{{ $remission['createdAt'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        
        
        <table class="table">
            <thead>
                <tr>
                    <th>CANTIDAD</th>
                    <th>DESCRIPCIÓN</th>
                    <th>N° COLORES</th>
                    <th>PRECIO</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($remission['details'] as $detail)
                <tr>
                    <td>{{ $detail['quantity'] }}</td>
                    <td>{{ $detail['product']['name'] }} ({{ $detail['reference'] }})</td>
                    <td>{{ $detail['colors'] }}</td>
                    <td>{{ number_format($detail['price'], 2) }}</td>
                    <td>{{ number_format($detail['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div>
            <p class="total"><strong>TOTAL:</strong> {{ number_format($remission['total'], 2) }}</p>
            <p><strong>CANTIDAD DE PAQUETES:</strong> {{ $remission['totalpackages'] }}</p>
        </div>
    </div>
</body>
</html>
