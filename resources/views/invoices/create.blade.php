@extends('layouts.app')
@section('content')

<h3 class="mb-4">Crear Nueva Factura</h3>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label for="persona_id" class="form-label">Cliente</label>
                    <select name="persona_id" class="form-select" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id }}">
                                {{ $persona->cedula }} - {{ $persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('personas.create') }}" class="btn btn-link p-0 mt-1">
                        Crear nuevo cliente
                    </a>
                </div>
                <div class="col-md-3">
                    <label>Tipo de Factura</label>
                    <select name="invoice_type" id="invoice_type" class="form-select" required>
                        <option value="contado">Contado</option>
                        <option value="credito">Crédito (30 días)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Fecha Emisión</label>
                    <input type="date" name="issue_date" id="issue_date" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Fecha Vencimiento</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" required>
                    <small class="text-muted" id="vencimiento_info"></small>
                </div>
            </div>
            <hr>
            <h5>Items de la Factura</h5>
            <table class="table table-bordered mt-3" id="tabla-items">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cant.</th>
                        <th>IVA</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="item_code[]" class="form-control" required></td>
                        <td><input type="text" name="item_name[]" class="form-control" required></td>
                        <td><input type="number" step="0.01" min="0" name="unit_price[]" class="form-control" required></td>
                        <td><input type="number" name="quantity[]" min="1" class="form-control" required></td>
                        <td class="text-center">
                            <input type="checkbox" name="applies_tax[]" value="1" class="form-check-input" checked style="width: 20px; height: 20px;">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">X</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-success" onclick="agregarFila()">+ Agregar Item</button>
            <hr>
            <div class="bg-light p-4 rounded-3 border">
                <h5 class="mb-3">Resumen de Factura</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Subtotal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">$</span>
                            <input type="text" id="subtotal_view" class="form-control" value="0.00" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">IVA (19%)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">$</span>
                            <input type="text" id="iva_view" class="form-control" value="0.00" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Total</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white fw-bold">$</span>
                            <input type="text" id="total_view" class="form-control fw-bold fs-5 text-success" value="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Factura</button>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
</div>
<script>
//ponemos la fecha del dia
    document.addEventListener('DOMContentLoaded', function() {
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('issue_date').value = hoy;
        calcularFechaVencimiento();
    });
    //Calculamos fecha de vencimiento
    function calcularFechaVencimiento() {
        const tipoFactura = document.getElementById('invoice_type').value;
        const fechaEmision = document.getElementById('issue_date').value;
        const campoVencimiento = document.getElementById('due_date');
        const infoVencimiento = document.getElementById('vencimiento_info');

        if (!fechaEmision) {
            infoVencimiento.textContent = '';
            return;
        }

        const fecha = new Date(fechaEmision + 'T00:00:00');

        if (tipoFactura === 'credito') {
            // Ponemos los 30 dias
            fecha.setDate(fecha.getDate() + 30);
            const fechaVencimiento = fecha.toISOString().split('T')[0];
            campoVencimiento.value = fechaVencimiento;
        } else {
            // para contado misma fecha de hoy
            campoVencimiento.value = fechaEmision;
        }
    }

    // escuchamos el change de los elementos
    document.getElementById('invoice_type').addEventListener('change', calcularFechaVencimiento);
    document.getElementById('issue_date').addEventListener('change', calcularFechaVencimiento);

    // recalcular totales
    function recalcularTotales() {
        let subtotal = 0;
        let iva = 0;
        const filas = document.querySelectorAll('#tabla-items tbody tr');
        filas.forEach(fila => {
            let precio = parseFloat(fila.querySelector('input[name="unit_price[]"]').value) || 0;
            let cantidad = parseFloat(fila.querySelector('input[name="quantity[]"]').value) || 0;
            let checkboxIva = fila.querySelector('input[name="applies_tax[]"]');
            let aplicaIva = checkboxIva ? checkboxIva.checked : false;
            let linea = precio * cantidad;
            subtotal += linea;
            if (aplicaIva) {
                iva += linea * 0.19;
            }
        });
        document.getElementById('subtotal_view').value = subtotal.toFixed(2);
        document.getElementById('iva_view').value = iva.toFixed(2);
        document.getElementById('total_view').value = (subtotal + iva).toFixed(2);
    }
    // recalcular cuando se escribe en inputs o cambia checkbox
    document.addEventListener('input', function(e) {
        if (e.target.name === 'unit_price[]' ||
            e.target.name === 'quantity[]') {
            recalcularTotales();
        }
    });
    document.addEventListener('change', function(e) {
        if (e.target.name === 'applies_tax[]') {
            recalcularTotales();
        }
    });
    // agregar y eliminar filas
    function agregarFila() {
        let fila = `
            <tr>
                <td><input type="text" name="item_code[]" class="form-control" required></td>
                <td><input type="text" name="item_name[]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="unit_price[]" class="form-control" required></td>
                <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                <td class="text-center">
                    <input type="checkbox" name="applies_tax[]" value="1" class="form-check-input" checked style="width: 20px; height: 20px;">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">X</button>
                </td>
            </tr>
        `;

        document.querySelector('#tabla-items tbody').insertAdjacentHTML('beforeend', fila);
        recalcularTotales();
    }

    function eliminarFila(btn) {
        btn.closest('tr').remove();
        recalcularTotales();
    }
</script>
@endsection