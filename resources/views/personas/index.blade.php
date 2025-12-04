@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Clientes</h3>
    <a href="{{ route('personas.create') }}" class="btn btn-success">
        <strong>+</strong> Nuevo Cliente
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($personas->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th width="200">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($personas as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->cedula }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('personas.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('personas.destroy', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('¿Está seguro de eliminar este cliente?')" class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $personas->links() }}
            </div>
        @else
            <p class="text-center text-muted mb-0">No hay clientes registrados.</p>
        @endif
    </div>
</div>
@endsection