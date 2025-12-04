@extends('layouts.app')
@section('content')

<h3 class="mb-4">Editar Cliente</h3>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('personas.update', $persona->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Cédula <span class="text-danger">*</span></label>
                <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $persona->cedula) }}" required placeholder="Ingrese la cédula">
                @error('cedula')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $persona->nombre) }}" required placeholder="Ingrese el nombre completo">
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $persona->email) }}" required placeholder="ejemplo@correo.com">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <strong>Actualizar</strong>
                </button>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection