@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-gray-900 text-white">
                    <h4>{{ $project->nombre }}</h4>
                    <div class="btn-group">
                        @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar Nombre
                            </a>
                        @endif
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list"></i> Ver lista de proyectos
                        </a>
                    </div>
                </div>

                <div class="card-body bg-gray-700 text-white">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Información del Proyecto</strong></h6>
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Nombre:</strong> 
                                    <span>{{ $project->nombre }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Fecha de Creación:</strong> 
                                    <span>{{ $project->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Última Actualización:</strong> 
                                    <span>{{ $project->updated_at->format('d/m/Y H:i') }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Sección de Datos</strong></h6>
                            <div class="card bg-light mt-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-table fa-3x text-primary mb-3"></i>
                                    <h6>Tabla de Datos</h6>
                                    <p class="text-muted small">
                                        @if(auth()->user()->role === 'estudiante')
                                            Ver los datos del proyecto (solo lectura).
                                        @else
                                            Gestiona las columnas y datos de tu proyecto.
                                        @endif
                                        <br>Incluye columnas fijas: <strong>Actor</strong>, <strong>Rol</strong>, <strong>Categoría</strong>
                                    </p>
                                    <a href="{{ route('projects.data-table', $project) }}" class="btn btn-primary">
                                        <i class="fas fa-{{ auth()->user()->role === 'estudiante' ? 'eye' : 'cogs' }}"></i> 
                                        {{ auth()->user()->role === 'estudiante' ? 'Ver Datos' : 'Gestionar Datos' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área para futuras funcionalidades -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Funcionalidades:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Tabla con columnas Actor, Rol y Categoría (no removibles)</li>
                                    @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                                        <li>Agregar columnas personalizadas</li>
                                        <li>Gestión de datos en la tabla</li>
                                    @else
                                        <li>Visualización de columnas y datos (solo lectura)</li>
                                    @endif
                                    <li>Mapa de actores</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
