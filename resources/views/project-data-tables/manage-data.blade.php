@extends('layouts.master')

@section('content')
<div class="py-8 max-w-6xl mx-auto">
    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 p-4 rounded-lg border border-green-600 relative" id="successAlert">
            <button type="button" class="absolute top-2 right-2 text-green-700 hover:text-green-900 text-xl font-bold" onclick="document.getElementById('successAlert').style.display='none'">
                &times;
            </button>
            <div class="pr-8">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 text-red-700 bg-red-100 p-4 rounded-lg border border-red-600 relative" id="errorAlert">
            <button type="button" class="absolute top-2 right-2 text-red-700 hover:text-red-900 text-xl font-bold" onclick="document.getElementById('errorAlert').style.display='none'">
                &times;
            </button>
            <div class="pr-8">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->nombre }} - Gestión de Datos
            </h2>
            <small class="text-gray-700">{{ $dataTable->descripcion }}</small>
        </div>
        <div class="space-y-3 lg:space-y-0 lg:flex lg:space-x-2">
            <!-- Grupo de Exportación -->
            @if($records->count() > 0)
                <div class="flex space-x-2">
                    <button onclick="ExportUtils.exportToPDF('data-records-table', 'datos-{{ $project->nombre }}')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button onclick="ExportUtils.exportToExcel('data-records-table', 'datos-{{ $project->nombre }}')" 
                        class="text-white px-3 py-2 rounded font-bold text-sm" 
                        style="background-color: #16a34a;" 
                        onmouseover="this.style.backgroundColor='#15803d'" 
                        onmouseout="this.style.backgroundColor='#16a34a'">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            @endif
            
            <!-- Grupo de Gestión -->
            <div class="flex space-x-2">
                @if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                    <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded font-bold text-sm" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                        <i class="fas fa-plus"></i> Agregar Registro
                    </button>
                @endif
                <a href="{{ route('mapa.actores') }}?proyecto={{ $project->id }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-bold text-sm">
                    <i class="fas fa-map"></i> Mapa de Actores
                </a>
            </div>
            
            <!-- Grupo de Navegación -->
            <div class="flex space-x-2">
                <a href="{{ route('projects.data-table', $project) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-2 rounded font-bold text-sm">
                    <i class="fas fa-cogs"></i> Configurar Columnas
                </a>
                <a href="{{ route('projects.show', $project) }}" class="bg-gray-700 hover:bg-gray-900 text-white px-3 py-2 rounded font-bold text-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de datos -->
    @if($records->count() > 0)
        <div class="overflow-x-auto bg-gray-500 rounded-lg p-4">
            <table id="data-records-table" class="min-w-full bg-gray-800 rounded shadow text-sm sm:text-base">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="px-2 py-1 sm:px-4 sm:py-2">#</th>
                        @foreach($columns as $column)
                            <th class="px-2 py-1 sm:px-4 sm:py-2">
                                {{ $column->nombre }}
                                @if($column->es_requerido)
                                    <span class="text-red-400">*</span>
                                @endif
                                @if($column->es_fijo)
                                    <i class="fas fa-lock text-yellow-400 ms-1"></i>
                                @endif
                            </th>
                        @endforeach
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Fecha</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $index => $record)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $records->firstItem() + $index }}</td>
                        @foreach($columns as $column)
                            <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                                @php
                                    $value = $record->datos[$column->nombre] ?? '-';
                                @endphp
                                
                                @if($column->tipo === 'boolean')
                                    @if($value == '1' || $value === true)
                                        <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">Sí</span>
                                    @elseif($value == '0' || $value === false)
                                        <span class="bg-red-600 text-white px-2 py-1 rounded text-xs">No</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($column->tipo === 'date' && $value && $value !== '-')
                                    {{ \Carbon\Carbon::parse($value)->format('d/m/Y') }}
                                @elseif($column->tipo === 'select')
                                    <span class="bg-cyan-600 text-white px-2 py-1 rounded text-xs">{{ $value }}</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $record->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            @if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                                <a href="{{ route('projects.data-table.edit-record', [$project, $record->id]) }}" class="text-blue-400 hover:underline mr-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <!-- Modal Trigger -->
                                <button type="button" class="text-red-600 hover:underline" onclick="document.getElementById('delete-modal-record-{{ $record->id }}').style.display='flex'">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                
                                <!-- Modal -->
                                <div id="delete-modal-record-{{ $record->id }}" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden" style="display:none;">
                                    <div class="bg-white rounded-lg shadow-lg p-6 mx-auto w-full max-w-sm flex flex-col items-center relative">
                                        <button type="button" class="absolute top-2 right-2 text-red-600 text-2xl font-bold" onclick="document.getElementById('delete-modal-record-{{ $record->id }}').style.display='none'">&times;</button>
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <h3 class="text-lg font-bold mb-2 text-gray-900">¿Estás seguro de eliminar este registro?</h3>
                                        <p class="text-gray-700 mb-6 text-center">Esta acción no se puede deshacer y eliminará permanentemente este registro.</p>
                                        <div class="flex w-full justify-between">
                                        <form action="{{ route('projects.data-table.delete-record', [$project, $record->id]) }}" method="POST" class="w-1/2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">Aceptar</button>
                                        </form>
                                        <button type="button" class="w-1/2 ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-300" onclick="document.getElementById('delete-modal-record-{{ $record->id }}').style.display='none'">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                            @else
                                <span class="text-gray-500">Solo lectura</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="flex justify-center mt-4">
            {{ $records->links() }}
        </div>
    @else
        <div class="bg-gray-800 rounded-lg shadow p-8 text-center">
            <div class="mb-4">
                <i class="fas fa-database text-6xl text-gray-400"></i>
            </div>
            <h5 class="text-gray-300 text-xl mb-2">No hay registros</h5>
            <p class="text-gray-400 mb-4">
                @if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                    Comienza agregando tu primer registro de datos
                @else
                    No hay datos disponibles para mostrar (solo lectura)
                @endif
            </p>
            @if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded font-bold inline-flex items-center" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    <i class="fas fa-plus mr-2"></i> Agregar Primer Registro
                </button>
            @endif
        </div>
    @endif
</div>

@if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
<!-- Modal para agregar registro -->
<div class="modal fade" id="addRecordModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-gray-800 text-white border-0">
            <form action="{{ route('projects.data-table.store-record', $project) }}" method="POST">
                @csrf
                <div class="modal-header border-gray-700 bg-gray-900">
                    <h5 class="modal-title text-white flex items-center">
                        <i class="fas fa-plus mr-2"></i> Agregar Nuevo Registro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-gray-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($columns as $column)
                            <div class="mb-3">
                                <label class="block text-white font-bold mb-2">
                                    {{ $column->nombre }}
                                    @if($column->es_requerido)
                                        <span class="text-red-400">*</span>
                                    @endif
                                    @if($column->es_fijo)
                                        <i class="fas fa-lock text-yellow-400 ml-1"></i>
                                    @endif
                                </label>
                                
                                @if($column->tipo === 'text')
                                    <input type="text" name="campo_{{ $column->id }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" {{ $column->es_requerido ? 'required' : '' }}>
                                @elseif($column->tipo === 'number')
                                    <input type="number" name="campo_{{ $column->id }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" step="any" {{ $column->es_requerido ? 'required' : '' }}>
                                @elseif($column->tipo === 'date')
                                    <input type="date" name="campo_{{ $column->id }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" {{ $column->es_requerido ? 'required' : '' }}>
                                @elseif($column->tipo === 'select' && $column->opciones)
                                    <select name="campo_{{ $column->id }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" {{ $column->es_requerido ? 'required' : '' }}>
                                        <option value="">Seleccionar...</option>
                                        @foreach($column->opciones as $opcion)
                                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                                        @endforeach
                                    </select>
                                @elseif($column->tipo === 'boolean')
                                    <select name="campo_{{ $column->id }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" {{ $column->es_requerido ? 'required' : '' }}>
                                        <option value="">Seleccionar...</option>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-gray-700 bg-gray-800">
                    <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script src="{{ asset('js/export-utils.js') }}"></script>
@endsection
