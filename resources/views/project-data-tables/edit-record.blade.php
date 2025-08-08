@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Editar Registro - {{ $project->nombre }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('projects.data-table.update-record', [$project, $record->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            @foreach($columns as $column)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        {{ $column->nombre }}
                                        @if($column->es_requerido)
                                            <span class="text-danger">*</span>
                                        @endif
                                        @if($column->es_fijo)
                                            <i class="fas fa-lock text-warning ms-1"></i>
                                        @endif
                                    </label>
                                    
                                    @php
                                        $currentValue = $record->datos[$column->nombre] ?? '';
                                    @endphp
                                    
                                    @if($column->tipo === 'text')
                                        <input type="text" name="campo_{{ $column->id }}" class="form-control" value="{{ $currentValue }}" {{ $column->es_requerido ? 'required' : '' }}>
                                    @elseif($column->tipo === 'number')
                                        <input type="number" name="campo_{{ $column->id }}" class="form-control" step="any" value="{{ $currentValue }}" {{ $column->es_requerido ? 'required' : '' }}>
                                    @elseif($column->tipo === 'date')
                                        <input type="date" name="campo_{{ $column->id }}" class="form-control" value="{{ $currentValue }}" {{ $column->es_requerido ? 'required' : '' }}>
                                    @elseif($column->tipo === 'select' && $column->opciones)
                                        <select name="campo_{{ $column->id }}" class="form-control" {{ $column->es_requerido ? 'required' : '' }}>
                                            <option value="">Seleccionar...</option>
                                            @foreach($column->opciones as $opcion)
                                                <option value="{{ $opcion }}" {{ $currentValue == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($column->tipo === 'boolean')
                                        <select name="campo_{{ $column->id }}" class="form-control" {{ $column->es_requerido ? 'required' : '' }}>
                                            <option value="">Seleccionar...</option>
                                            <option value="1" {{ $currentValue == '1' ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ $currentValue == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('projects.data-table.manage-data', $project) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Actualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
