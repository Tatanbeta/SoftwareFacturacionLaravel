<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th, table td {
            border: 1px solid #777;
            padding: 6px;
            text-align: left;
        }

        table th {
            background: #f0f0f0;
        }

        .totales {
            margin-top: 20px;
            width: 40%;
            float: right;
        }

        .totales table {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Factura #{{ $invoice->id }}</div>
        <div>Generada el {{ date('Y-m-d') }}</div>
    </div>
    <table>
        <tr>
            <td width="50%">
                <div class="section-title">Información del Cliente</div>
                Nombre: {{ $invoice->persona->nombre }} <br>
                Cédula: {{ $invoice->persona->cedula }} <br>
                Email: {{ $invoice->persona->email }} <br>
            </td>

            <td width="50%">
                <div class="section-title">Información de la Factura</div>
                Fecha Emisión: {{ $invoice->issue_date }} <br>
                Fecha Vencimiento: {{ $invoice->due_date }} <br>
                Tipo: {{ $invoice->invoice_type }} <br>
                Usuario: {{ $invoice->usuario->nick }} <br>
            </td>
        </tr>
    </table>
    <div class="section-title">Items</div>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio Unit.</th>
                <th>Cant.</th>
                <th>IVA</th>
                <th>Subtotal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->detalles as $detalle)
            <tr>
                <td>{{ $detalle->item_code }}</td>
                <td>{{ $detalle->item_name }}</td>
                <td>${{ number_format($detalle->unit_price, 2) }}</td>
                <td>{{ $detalle->quantity }}</td>
                <td>{{ $detalle->applies_tax ? 'Sí' : 'No' }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
                <td>${{ number_format($detalle->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totales">
        <table>
            <tr>
                <th>Subtotal</th>
                <td>${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>IVA</th>
                <td>${{ number_format($invoice->tax_total, 2) }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>