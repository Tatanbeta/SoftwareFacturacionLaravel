@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Listado de Facturas</h3>
    <a href="{{ route('invoices.create') }}" class="btn btn-success">
        <strong>+</strong> Nueva Factura
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label>Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                           value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label>Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                           value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3">
                    <label>Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Contado" {{ request('tipo') == 'Contado' ? 'selected' : '' }}>Contado</option>
                        <option value="Credito" {{ request('tipo') == 'Credito' ? 'selected' : '' }}>Crédito</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Cliente (Nombre o Cédula)</label>
                    <input type="text" name="cliente" class="form-control"
                           value="{{ request('cliente') }}">
                </div>
            </div>
            <button class="btn btn-dark mt-3">Filtrar</button>
        </form>

    </div>
</div>
<div class="card">
    <div class="card-body">
        @if($invoices->count())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td>{{ $inv->id }}</td>
                        <td>{{ $inv->persona->nombre }}</td>
                        <td>{{ $inv->issue_date }}</td>
                        <td>{{ $inv->invoice_type }}</td>
                        <td>${{ number_format($inv->subtotal, 2) }}</td>
                        <td>${{ number_format($inv->total, 2) }}</td>

                        <td>
                            <a href="{{ route('invoices.show', $inv->id) }}" 
                               class="btn btn-sm btn-primary">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $invoices->links() }}
            </div>
        @else
            <p class="text-center">No hay facturas registradas.</p>
        @endif
    </div>
</div>
@endsection