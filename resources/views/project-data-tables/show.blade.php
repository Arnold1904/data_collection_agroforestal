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
                {{ $project->nombre }} - Tabla de Datos
            </h2>
            <small class="text-gray-700">{{ $dataTable->descripcion }}</small>
        </div>
        <div class="flex space-x-2">
            @if(in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                <a href="{{ route('projects.data-table.manage-data', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold">
                    <i class="fas fa-database"></i> Agregar Datos
                </a>
                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-bold" data-bs-toggle="modal" data-bs-target="#addColumnModal">
                    <i class="fas fa-plus"></i> Agregar Columna
                </button>
            @else
                <span class="text-gray-600 px-4 py-2 bg-gray-200 rounded">
                    <i class="fas fa-eye"></i> Modo Solo Lectura
                </span>
            @endif
            <a href="{{ route('projects.show', $project) }}" class="bg-gray-700 hover:bg-gray-900 text-white px-4 py-2 rounded font-bold">
                <i class="fas fa-arrow-left"></i> Volver al Proyecto
            </a>
        </div>
    </div>

    <!-- Columnas actuales -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">
            <i class="fas fa-columns"></i> Estructura de la Tabla
        </h3>
        <div class="overflow-x-auto bg-gray-500 rounded-lg p-4">
            <table class="min-w-full bg-gray-800 rounded shadow text-sm sm:text-base">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Nro</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Nombre</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Tipo</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Requerido</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Fijo</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Opciones</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataTable->columns as $column)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $column->orden }}</td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            <strong>{{ $column->nombre }}</strong>
                            @if($column->es_fijo)
                                <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs ml-2">Fijo</span>
                            @endif
                        </td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            <span class="bg-cyan-600 text-white px-2 py-1 rounded text-xs">{{ ucfirst($column->tipo) }}</span>
                        </td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2 text-center">
                            @if($column->es_requerido)
                                <i class="fas fa-check text-green-400"></i>
                            @else
                                <i class="fas fa-times text-red-400"></i>
                            @endif
                        </td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2 text-center">
                            @if($column->es_fijo)
                                <i class="fas fa-lock text-yellow-400"></i>
                            @else
                                <i class="fas fa-unlock text-green-400"></i>
                            @endif
                        </td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            @if($column->opciones)
                                <small class="text-gray-300">{{ implode(', ', $column->opciones) }}</small>
                                @if($column->es_fijo && $column->tipo === 'select')
                                    <br>
                                    <button type="button" class="text-blue-400 hover:underline text-xs mt-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editOptionsModal{{ $column->id }}">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                @endif
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            @if(!$column->es_fijo && in_array(auth()->user()->role, ['admin', 'profesor', 'estudiante']))
                                <!-- Modal Trigger -->
                                <button type="button" class="text-red-600 hover:underline" onclick="document.getElementById('delete-modal-column-{{ $column->id }}').style.display='flex'">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                
                                <!-- Modal -->
                                <div id="delete-modal-column-{{ $column->id }}" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden" style="display:none;">
                                    <div class="bg-white rounded-lg shadow-lg p-6 mx-auto w-full max-w-sm flex flex-col items-center relative">
                                        <button type="button" class="absolute top-2 right-2 text-red-600 text-2xl font-bold" onclick="document.getElementById('delete-modal-column-{{ $column->id }}').style.display='none'">&times;</button>
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <h3 class="text-lg font-bold mb-2 text-gray-900">¿Estás seguro de eliminar esta columna?</h3>
                                        <p class="text-gray-700 mb-6 text-center">Esta acción no se puede deshacer y eliminará todos los datos de esta columna.</p>
                                        <div class="flex w-full justify-between">
                                            <form action="{{ route('projects.data-table.remove-column', [$project, $column]) }}" method="POST" class="w-1/2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">Aceptar</button>
                                            </form>
                                            <button type="button" class="w-1/2 ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-300" onclick="document.getElementById('delete-modal-column-{{ $column->id }}').style.display='none'">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vista previa de la tabla -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3">
            <i class="fas fa-eye"></i> Vista Previa de la Tabla
        </h3>
        <div class="overflow-x-auto bg-gray-500 rounded-lg p-4">
            <table class="min-w-full bg-gray-800 rounded shadow text-sm sm:text-base">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        @foreach($dataTable->columns as $column)
                            <th class="px-2 py-1 sm:px-4 sm:py-2">
                                {{ $column->nombre }}
                                @if($column->es_requerido)
                                    <span class="text-red-400">*</span>
                                @endif
                            </th>
                        @endforeach
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-700">
                        @foreach($dataTable->columns as $column)
                            <td class="px-2 text-gray-400 py-1 sm:px-4 sm:py-2">
                                @if($column->tipo === 'select' && $column->opciones)
                                    [{{ implode('|', $column->opciones) }}]
                                @else
                                    [{{ ucfirst($column->tipo) }}]
                                @endif
                            </td>
                        @endforeach
                        <td class="px-2 text-gray-400 py-1 sm:px-4 sm:py-2">
                            <button class="text-gray-500" disabled>
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar columna -->
<div class="modal fade" id="addColumnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.data-table.add-column', $project) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nueva Columna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Columna</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Campo</label>
                        <select name="tipo" class="form-control" id="tipoSelect" required>
                            <option value="text">Texto</option>
                            <option value="number">Número</option>
                            <option value="date">Fecha</option>
                            <option value="select">Selección (Lista)</option>
                            <option value="boolean">Sí/No</option>
                        </select>
                    </div>

                    <div class="mb-3" id="opcionesDiv" style="display: none;">
                        <label class="form-label">Opciones</label>
                        <div id="opcionesContainer">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control opciones-input" placeholder="Escribe una opción...">
                                <button type="button" class="btn btn-outline-danger" onclick="removeOpcionInput(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addOpcionInput()">
                            <i class="fas fa-plus"></i> Agregar Opción
                        </button>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="es_requerido" value="1">
                        <label class="form-check-label">Registro obligatorio</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Agregar Columna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales para editar opciones de columnas fijas -->
@foreach($dataTable->columns as $column)
    @if($column->es_fijo && $column->tipo === 'select')
    <div class="modal fade" id="editOptionsModal{{ $column->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('projects.data-table.update-column-options', [$project, $column]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Opciones - {{ $column->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Opciones</label>
                            <div id="editOpcionesContainer{{ $column->id }}">
                                @if($column->opciones && count($column->opciones) > 0)
                                    @foreach($column->opciones as $opcion)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control opciones-input" value="{{ $opcion }}" placeholder="Escribe una opción...">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeOpcionInput(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control opciones-input" placeholder="Escribe una opción...">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeOpcionInput(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="addEditOpcionInput({{ $column->id }})">
                                <i class="fas fa-plus"></i> Agregar Opción
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Opciones</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
document.getElementById('tipoSelect').addEventListener('change', function() {
    const opcionesDiv = document.getElementById('opcionesDiv');
    if (this.value === 'select') {
        opcionesDiv.style.display = 'block';
    } else {
        opcionesDiv.style.display = 'none';
    }
});

// Función para agregar una nueva opción al modal de crear columna
function addOpcionInput() {
    const container = document.getElementById('opcionesContainer');
    const newInputGroup = document.createElement('div');
    newInputGroup.className = 'input-group mb-2';
    newInputGroup.innerHTML = `
        <input type="text" class="form-control opciones-input" placeholder="Escribe una opción...">
        <button type="button" class="btn btn-outline-danger" onclick="removeOpcionInput(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newInputGroup);
}

// Función para agregar una nueva opción al modal de editar opciones
function addEditOpcionInput(columnId) {
    const container = document.getElementById('editOpcionesContainer' + columnId);
    const newInputGroup = document.createElement('div');
    newInputGroup.className = 'input-group mb-2';
    newInputGroup.innerHTML = `
        <input type="text" class="form-control opciones-input" placeholder="Escribe una opción...">
        <button type="button" class="btn btn-outline-danger" onclick="removeOpcionInput(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newInputGroup);
}

// Función para eliminar una opción
function removeOpcionInput(button) {
    const inputGroup = button.parentElement;
    const container = inputGroup.parentElement;
    
    // No permitir eliminar si solo hay un input
    if (container.children.length > 1) {
        inputGroup.remove();
    } else {
        // Si es el último, limpiar el valor pero no eliminar el input
        const input = inputGroup.querySelector('input');
        input.value = '';
        input.focus();
    }
}

// Procesar opciones antes de enviar el formulario - Modal Agregar Columna
document.querySelector('#addColumnModal form').addEventListener('submit', function(e) {
    const tipoSelect = document.getElementById('tipoSelect');
    if (tipoSelect.value === 'select') {
        // Limpiar opciones anteriores si existen
        this.querySelectorAll('input[name^="opciones["]').forEach(input => input.remove());
        
        // Obtener todas las opciones de los inputs
        const opcionesInputs = document.querySelectorAll('#opcionesContainer .opciones-input');
        const opciones = [];
        
        opcionesInputs.forEach(input => {
            const value = input.value.trim();
            if (value !== '') {
                opciones.push(value);
            }
        });
        
        // Crear inputs hidden para cada opción válida
        opciones.forEach((opcion, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `opciones[${index}]`;
            input.value = opcion;
            this.appendChild(input);
        });
        
        // Validar que hay al menos una opción
        if (opciones.length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos una opción para el campo de selección.');
            return false;
        }
    }
});

// Procesar opciones para modales de edición
document.querySelectorAll('[id^="editOptionsModal"] form').forEach(form => {
    form.addEventListener('submit', function(e) {
        // Limpiar opciones anteriores
        this.querySelectorAll('input[name^="opciones["]').forEach(input => input.remove());
        
        // Obtener el ID de la columna del modal
        const modalId = this.closest('.modal').id;
        const columnId = modalId.replace('editOptionsModal', '');
        
        // Obtener todas las opciones de los inputs
        const opcionesInputs = this.querySelectorAll('.opciones-input');
        const opciones = [];
        
        opcionesInputs.forEach(input => {
            const value = input.value.trim();
            if (value !== '') {
                opciones.push(value);
            }
        });
        
        // Crear inputs hidden para cada opción válida
        opciones.forEach((opcion, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `opciones[${index}]`;
            input.value = opcion;
            this.appendChild(input);
        });
        
        // Validar que hay al menos una opción
        if (opciones.length === 0) {
            e.preventDefault();
            alert('Debes tener al menos una opción para el campo de selección.');
            return false;
        }
    });
});

// Inicializar con una opción por defecto cuando se selecciona 'select'
document.getElementById('tipoSelect').addEventListener('change', function() {
    const opcionesDiv = document.getElementById('opcionesDiv');
    const container = document.getElementById('opcionesContainer');
    
    if (this.value === 'select') {
        opcionesDiv.style.display = 'block';
        // Si no hay opciones, agregar una por defecto
        if (container.children.length === 0) {
            addOpcionInput();
        }
    } else {
        opcionesDiv.style.display = 'none';
    }
});
</script>
@endsection
