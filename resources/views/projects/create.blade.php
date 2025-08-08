@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-gray-900 text-white">
                    <h4>Crear Nuevo Proyecto</h4>
                </div>

                <div class="card-body bg-gray-700 text-white">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Proyecto</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                placeholder="Ingresa el nombre del proyecto" required>
                            @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Crear Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
