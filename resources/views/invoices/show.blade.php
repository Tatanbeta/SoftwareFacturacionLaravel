@extends('layouts.app')
@section('content')

<h2 class="mb-4">Detalle de Factura #{{ $invoice->id }}</h2>

<div class="card mb-4">
    <div class="card-body row">
        <div class="col-md-6">
            <h5>Información del Cliente</h5>
            <p><strong>Nombre:</strong> {{ $invoice->persona->nombre }}</p>
            <p><strong>Cédula:</strong> {{ $invoice->persona->cedula }}</p>
            <p><strong>Email:</strong> {{ $invoice->persona->email }}</p>
        </div>
        <div class="col-md-6">
            <h5>Información de la Factura</h5>
            <p><strong>Fecha Emisión:</strong> {{ $invoice->issue_date }}</p>
            <p><strong>Fecha Vencimiento:</strong> {{ $invoice->due_date }}</p>
            <p><strong>Tipo:</strong> {{ $invoice->invoice_type }}</p>
            <p><strong>Usuario:</strong> {{ $invoice->usuario->nick }}</p>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h5>Items de la Factura</h5>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
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
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5>Totales</h5>
        <p><strong>Subtotal:</strong> ${{ number_format($invoice->subtotal, 2) }}</p>
        <p><strong>IVA:</strong> ${{ number_format($invoice->tax_total, 2) }}</p>
        <p><strong>Total:</strong> ${{ number_format($invoice->total, 2) }}</p>

    </div>
</div>
<hr>
<a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-danger mb-3">
    Descargar PDF
</a>
@endsection