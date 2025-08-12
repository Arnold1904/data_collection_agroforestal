@extends('layouts.master')

@section('content')
<div class="py-8 max-w-7xl mx-auto">
    <!-- Información antes de exportar -->
    <div class="mb-4">
        <div class="bg-cyan-100 border border-cyan-400 text-cyan-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-info-circle mr-2 text-cyan-600"></i>
            <span class="font-medium">Antes de exportar a PNG o PDF, recomendamos usar el botón "Centrar Vista" para una mejor visualización.</span>
        </div>
    </div>
    <!-- Panel de Control Simplificado -->
    <div class="mb-6">
        <div class="bg-gray-800 rounded-lg shadow">
            <div class="flex justify-between items-center p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-map mr-2"></i> Mapa de Actores - Análisis de Influencias
                </h2>
                <div class="flex space-x-2">
                    <button onclick="fitNetwork()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-expand-arrows-alt"></i> Centrar Vista
                    </button>
                    <button onclick="exportToPNG()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-image"></i> Exportar PNG
                    </button>
                    <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Selector de Proyecto -->
                    <div class="lg:col-span-3">
                        <label class="block text-white font-bold mb-2">Proyecto:</label>
                        <select id="projectSelect" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white focus:outline-none focus:border-blue-500" onchange="changeProject()">
                            <option value="">-- Datos Originales --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $project->id == $selectedProjectId ? 'selected' : '' }}>
                                    {{ $project->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Configuración de Columnas -->
                    <div class="lg:col-span-6">
                        <label class="block text-white font-bold mb-2">Columnas a visualizar:</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2" id="columnCheckboxes">
                            @if($selectedProject && $columns->count() > 0)
                                @foreach($columns as $column)
                                    <div class="flex items-center">
                                        <input class="column-checkbox mr-2" 
                                            type="checkbox" 
                                            id="col_{{ $column->id }}" 
                                            value="{{ $column->nombre }}"
                                            data-column-id="{{ $column->id }}"
                                            {{ $loop->index < 3 ? 'checked' : '' }}>
                                        <label class="text-gray-300 text-sm" for="col_{{ $column->id }}">
                                            {{ $column->nombre }}
                                            @if($column->es_fijo)
                                                <i class="fas fa-lock text-yellow-400 ml-1"></i>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-full">
                                    <small class="text-gray-400">Selecciona un proyecto para ver las columnas disponibles</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Controles de Configuración -->
                    <div class="lg:col-span-3">
                        <label class="block text-white font-bold mb-2">Configuración:</label>
                        <div class="space-y-2">
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded font-bold text-sm" onclick="updateVisualization()">
                                <i class="fas fa-sync mr-1"></i> Actualizar Visualización
                            </button>
                            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded font-bold text-sm" data-bs-toggle="modal" data-bs-target="#colorConfigModal">
                                <i class="fas fa-palette mr-1"></i> Configurar Colores
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualización Principal -->
    <div class="mb-6">
        <div class="bg-gray-800 rounded-lg shadow">
            <div class="p-4">
                <div id="network" class="bg-white rounded-lg border-4 border-gray-900" style="width:100%; height:650px;"></div>
            </div>
        </div>
    </div>

    <!-- Información de Interacciones -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Información del Actor Seleccionado
                    </h3>
                </div>
                <div class="p-4" id="selectedNodeInfo">
                    <div id="actorInfo">
                        <p class="text-gray-400">Haz clic en un actor para ver su información detallada y conexiones.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-lg shadow" style="min-height: 300px;">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i> Resumen de la Red
                    </h3>
                </div>
                <div class="p-4">
                    <div id="networkStats">
                        <div class="mb-3 p-3 bg-gray-700 rounded">
                            <div class="flex justify-between items-center">
                                <strong class="text-white">Total Nodos:</strong> 
                                <span id="totalActors" class="bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">0</span>
                            </div>
                        </div>
                        <div class="mb-3 p-3 bg-gray-700 rounded">
                            <div class="flex justify-between items-center">
                                <strong class="text-white">Conexiones:</strong> 
                                <span id="totalConnections" class="bg-green-600 text-white px-2 py-1 rounded text-sm font-bold">0</span>
                            </div>
                        </div>
                        <div class="mb-3 p-3 bg-gray-700 rounded">
                            <strong class="text-white">Actor Central:</strong>
                            <div class="mt-2">
                                <span id="centralActor" class="text-blue-400 font-bold">-</span>
                            </div>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="mt-4">
                            <h4 class="text-white text-sm font-bold mb-3 flex items-center">
                                <i class="fas fa-network-wired mr-2"></i> Estado de la Red
                            </h4>
                            <div class="bg-gray-700 p-3 rounded">
                                <small class="text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i> 
                                    Haz clic en cualquier nodo para ver sus conexiones y detalles.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Configuración de Colores por Columna -->
<div class="modal fade" id="colorConfigModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-gray-800 text-white">
            <div class="modal-header border-gray-700">
                <h5 class="modal-title text-white flex items-center">
                    <i class="fas fa-palette mr-2"></i> Configurar Colores por Columna
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="colorConfiguration">
                    <div class="bg-blue-900 border border-blue-700 text-blue-100 p-4 rounded mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Configuración Simplificada de Colores:</strong><br>
                        Cambia el color de <strong>toda una categoría de nodos a la vez</strong>. Por ejemplo, todos los actores tendrán el mismo color, todos los valores de "Categoría" tendrán el mismo color, etc.
                    </div>
                    <div id="columnColorConfig">
                        <!-- Se generará dinámicamente -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-gray-700">
                <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded font-bold" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold" onclick="applyColorConfiguration()">
                    <i class="fas fa-check mr-1"></i> Aplicar Configuración
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Scripts necesarios -->
<script type="text/javascript" src="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.js"></script>
<link href="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Datos disponibles
let currentProjectData = @json($chartData);
let oldSystemData = @json($registrosAntiguos);
let availableColumns = @json($columns);
let network = null;
let columnColorConfig = {};

// Configuración inicial de colores por columna
const defaultColumnColors = {
    'Actor': '#4CAF50'  // Solo guardamos el color base para actores
};

// Función para mostrar mensajes personalizados cuando no hay datos
function showCustomEmptyMessage(title, message, iconClass) {
    console.log('Mostrando mensaje personalizado:', title);
    const networkContainer = document.getElementById('network');
    if (!networkContainer) {
        console.error('No se encontró el contenedor network');
        return;
    }
    
    networkContainer.innerHTML = `
        <div class="flex justify-center items-center" style="height: 500px;">
            <div class="bg-gray-800 rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 border border-gray-700">
                <div class="text-center">
                    <!-- Icono con gradiente -->
                    <div class="mb-6 relative">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-cyan-400 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="${iconClass} text-2xl text-white"></i>
                        </div>
                        <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 via-blue-500 to-blue-600 rounded-full opacity-30 blur animate-pulse"></div>
                    </div>
                    
                    <!-- Título -->
                    <h3 class="text-xl font-bold text-white mb-3">
                        ${title}
                    </h3>
                    
                    <!-- Mensaje -->
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        ${message}
                    </p>
                    
                    <!-- Línea decorativa -->
                    <div class="w-16 h-0.5 bg-gradient-to-r from-cyan-400 to-blue-600 mx-auto mb-6 rounded-full"></div>
                    
                    <!-- Sugerencias -->
                    <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                            <span class="text-sm font-medium text-gray-200">Sugerencias:</span>
                        </div>
                        <ul class="text-xs text-gray-400 text-left space-y-1">
                            <li class="flex items-center">
                                <i class="fas fa-plus text-green-400 mr-2 text-xs"></i>
                                Agrega registros en la sección de proyectos
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-table text-blue-400 mr-2 text-xs"></i>
                                Configura las columnas de datos necesarias
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-users text-purple-400 mr-2 text-xs"></i>
                                Define actores e influencias para la visualización
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando visualización...');
    console.log('Datos del proyecto:', currentProjectData);
    console.log('Columnas disponibles:', availableColumns);
    
    initializeColorConfiguration();
    
    // Verificar si mostrar mensaje personalizado
    if (currentProjectData.length === 0 && oldSystemData.length === 0) {
        console.log('No hay datos disponibles - mostrando mensaje personalizado');
        showCustomEmptyMessage(
            'Sin datos para visualizar', 
            'No hay registros disponibles para generar el mapa de actores. Agrega algunos datos para comenzar.',
            'fas fa-database'
        );
        return;
    }
    
    updateVisualization();
    
    // Event listeners para checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('column-checkbox')) {
            console.log('Columna checkbox cambiado:', e.target.value, e.target.checked);
            updateVisualization();
        }
    });
});

function changeProject() {
    const projectId = document.getElementById('projectSelect').value;
    if (projectId) {
        window.location.href = `{{ route('mapa.actores') }}?proyecto=${projectId}`;
    } else {
        window.location.href = `{{ route('mapa.actores') }}`;
    }
}

function initializeColorConfiguration() {
    console.log('Inicializando configuración de colores...');
    
    // Verificar que availableColumns esté disponible
    if (!availableColumns || !Array.isArray(availableColumns)) {
        console.log('availableColumns no está disponible');
        return;
    }
    
    // Inicializar configuración simplificada
    availableColumns.forEach(column => {
        if (!columnColorConfig[column.nombre]) {
            columnColorConfig[column.nombre] = {
                '_baseColor': getDefaultColorForColumn(column.nombre)
            };
        }
    });
    
    generateColorConfigurationUI();
}

function generateColorConfigurationUI() {
    const configDiv = document.getElementById('columnColorConfig');
    if (!configDiv) return;
    
    configDiv.innerHTML = '';
    
    // Obtener columnas seleccionadas
    const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => cb.value);
    
    if (selectedColumns.length === 0) {
        configDiv.innerHTML = '<p class="text-muted">Selecciona al menos una columna para configurar colores.</p>';
        return;
    }
    
    // Crear configuración por tipo de columna (no por valor individual)
    const columnsToShow = ['Actor', ...selectedColumns.filter(col => col !== 'Actor')];
    
    columnsToShow.forEach(columnName => {
        const currentColor = getColumnNodeColor(columnName);
        
        const columnDiv = document.createElement('div');
        columnDiv.className = 'mb-4 p-3 border border-gray-600 rounded bg-gray-700';
        columnDiv.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                <div class="lg:col-span-3">
                    <h6 class="mb-1 text-white font-bold flex items-center">
                        <i class="fas fa-${getColumnIcon(columnName)} mr-2"></i> 
                        Tipo: <strong>${columnName}</strong>
                    </h6>
                    <small class="text-gray-400">
                        ${getColumnDescription(columnName)}
                    </small>
                </div>
                <div class="lg:col-span-6">
                    <div class="flex items-center space-x-3">
                        <input type="color" 
                            class="w-20 h-10 border-2 border-gray-500 rounded cursor-pointer" 
                            value="${currentColor}"
                            onchange="updateColumnTypeColor('${columnName}', this.value)">
                        <span class="px-4 py-2 rounded text-white font-bold text-sm" 
                            style="background-color: ${currentColor};"
                            id="preview_${columnName.replace(/\s+/g, '_')}">
                            Vista previa
                        </span>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <button type="button" 
                            class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded text-sm font-bold" 
                            onclick="resetColumnColor('${columnName}')">
                        <i class="fas fa-undo mr-1"></i> Restaurar
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 mt-2">
                <div class="col-span-1">
                    <div class="w-full bg-gray-600 rounded-full h-2">
                        <div class="h-2 rounded-full" 
                             style="background-color: ${currentColor}; width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        `;
        configDiv.appendChild(columnDiv);
    });
}

function getColumnIcon(columnName) {
    const icons = {
        'Actor': 'user',
        'Categoría': 'tags',
        'Rol': 'briefcase',
        'Influencia': 'star',
        'Categoria': 'tags'
    };
    
    return icons[columnName] || 'circle';
}

function getColumnDescription(columnName) {
    const descriptions = {
        'Actor': 'Todos los nodos de actores tendrán este color',
        'Categoría': 'Todos los valores de categoría tendrán este color', 
        'Rol': 'Todos los valores de rol tendrán este color',
        'Influencia': 'Todos los valores de influencia tendrán este color',
        'Categoria': 'Todos los valores de categoría tendrán este color'
    };
    
    return descriptions[columnName] || 'Todos los valores de esta columna tendrán este color';
}

function updateColumnTypeColor(columnName, color) {
    // Actualizar el color base para este tipo de columna
    if (columnName === 'Actor') {
        // Los actores siempre usan el mismo color
        defaultColumnColors['Actor'] = color;
    } else {
        // Para otras columnas, cambiar el color base
        if (!columnColorConfig[columnName]) {
            columnColorConfig[columnName] = {};
        }
        columnColorConfig[columnName]['_baseColor'] = color;
    }
    
    // Actualizar vista previa
    const preview = document.getElementById(`preview_${columnName.replace(/\s+/g, '_')}`);
    if (preview) {
        preview.style.backgroundColor = color;
        preview.style.color = getContrastColor(color);
    }
    
    // Actualizar barra de progreso
    const progressBar = preview?.parentElement?.parentElement?.parentElement?.nextElementSibling?.querySelector('.progress-bar');
    if (progressBar) {
        progressBar.style.backgroundColor = color;
    }
}

function resetColumnColor(columnName) {
    const defaultColor = getDefaultColorForColumn(columnName);
    updateColumnTypeColor(columnName, defaultColor);
    
    // Actualizar el input de color
    const colorInput = document.querySelector(`input[onchange*="'${columnName}'"]`);
    if (colorInput) {
        colorInput.value = defaultColor;
    }
}

function getDefaultColorForColumn(columnName) {
    const defaults = {
        'Actor': '#4CAF50',
        'Categoría': '#2196F3',
        'Rol': '#FF9800', 
        'Influencia': '#9C27B0',
        'Categoria': '#2196F3'
    };
    
    return defaults[columnName] || '#607D8B';
}

function getContrastColor(hexColor) {
    // Convert hex to RGB
    const r = parseInt(hexColor.substr(1, 2), 16);
    const g = parseInt(hexColor.substr(3, 2), 16);
    const b = parseInt(hexColor.substr(5, 2), 16);
    
    // Calculate luminance
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    
    return luminance > 0.5 ? '#000000' : '#ffffff';
}

function getUniqueValuesForColumn(columnName) {
    const values = new Set();
    
    // Datos del proyecto actual
    currentProjectData.forEach(item => {
        if (item.data[columnName]) {
            values.add(item.data[columnName]);
        }
    });
    
    // Datos del sistema anterior (si no hay proyecto seleccionado)
    if (!document.getElementById('projectSelect').value) {
        oldSystemData.forEach(item => {
            if (item[columnName.toLowerCase()]) {
                values.add(item[columnName.toLowerCase()]);
            }
        });
    }
    
    return Array.from(values).filter(v => v !== null && v !== undefined && v !== '');
}

function updateColumnColor(columnName, value, color) {
    if (!columnColorConfig[columnName]) {
        columnColorConfig[columnName] = {};
    }
    columnColorConfig[columnName][value] = color;
}

function applyColorConfiguration() {
    updateVisualization();
    document.querySelector('#colorConfigModal .btn-close').click();
}

function updateVisualization() {
    console.log('Actualizando visualización...');
    
    const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => cb.value);
    console.log('Columnas seleccionadas:', selectedColumns);
    
    if (selectedColumns.length === 0) {
        // Seleccionar automáticamente las primeras 3 columnas si no hay ninguna seleccionada
        const checkboxes = document.querySelectorAll('.column-checkbox');
        let autoSelected = [];
        
        for (let i = 0; i < Math.min(3, checkboxes.length); i++) {
            checkboxes[i].checked = true;
            autoSelected.push(checkboxes[i].value);
        }
        
        if (autoSelected.length > 0) {
            console.log('Auto-seleccionando columnas:', autoSelected);
            selectedColumns.push(...autoSelected);
        } else {
            console.log('No hay columnas disponibles - mostrando mensaje personalizado');
            // Mostrar mensaje personalizado de que no hay datos
            showCustomEmptyMessage('No hay columnas disponibles', isNewProject ? 
                'Selecciona un proyecto y agrega algunas columnas para ver la visualización del mapa de actores.' : 
                'No hay columnas configuradas en el sistema para mostrar.'
            , 'fas fa-database');
            return;
        }
    }
    
    const isNewProject = document.getElementById('projectSelect').value !== '';
    console.log('Es proyecto nuevo:', isNewProject);
    
    let data;
    if (isNewProject && currentProjectData && currentProjectData.length > 0) {
        console.log('Usando datos del proyecto actual');
        data = prepareNewProjectVisualization(selectedColumns);
    } else if (oldSystemData && oldSystemData.length > 0) {
        console.log('Usando datos del sistema anterior');
        data = prepareOldSystemVisualization(selectedColumns);
    } else {
        console.log('No hay datos disponibles');
        // Mostrar mensaje personalizado de que no hay datos
        showCustomEmptyMessage('No hay datos para visualizar', isNewProject ? 
            'Agrega algunos registros al proyecto para ver la visualización del mapa de actores.' : 
            'No hay datos disponibles en el sistema para mostrar.'
        , 'fas fa-exclamation-triangle');
        return;
    }
    
    console.log('Datos preparados:', data);
    
    if (data.nodes.length === 0) {
        console.log('No se generaron nodos');
        showCustomEmptyMessage('Sin datos suficientes', 
            'Necesitas agregar más registros para generar la visualización de red del mapa de actores.',
            'fas fa-info-circle'
        );
        return;
    }
    
    createAdvancedNetworkVisualization(data.nodes, data.edges);
    updateNetworkStats(data);
    
    // Actualizar configuración de colores UI
    generateColorConfigurationUI();
}

function prepareNewProjectVisualization(selectedColumns) {
    console.log('=== Preparando visualización del proyecto ===');
    console.log('Columnas seleccionadas:', selectedColumns);
    console.log('Datos del proyecto:', currentProjectData);
    
    const nodes = [];
    const edges = [];
    const nodeIdCounter = { value: 0 };
    const columnValueNodes = {}; // Para almacenar nodos de valores de columnas
    const actorNodes = {}; // Para almacenar nodos de actores
    
    if (!currentProjectData || currentProjectData.length === 0) {
        console.log('No hay datos del proyecto disponibles');
        return { nodes, edges };
    }
    
    // Primero crear todos los nodos de actores
    currentProjectData.forEach((item, index) => {
        console.log(`Procesando actor ${index}:`, item);
        
        const actorName = item.data?.Actor || item.name || `Actor ${index + 1}`;
        const actorId = `actor_${index}`;
        
        console.log(`Creando nodo actor: ${actorName}`);
        
        actorNodes[actorId] = {
            id: nodeIdCounter.value++,
            label: actorName.toUpperCase(),
            title: `<b>ACTOR:</b> ${actorName}`,
            color: {
                background: getColumnNodeColor('Actor'),
                border: darkenColor(getColumnNodeColor('Actor'), 30),
                highlight: { background: lightenColor(getColumnNodeColor('Actor'), 20), border: darkenColor(getColumnNodeColor('Actor'), 40) }
            },
            size: 45,
            shape: 'ellipse',
            font: { size: 16, color: '#000000', face: 'Arial Black' },
            group: 'actors',
            data: item.data || {}
        };
        
        nodes.push(actorNodes[actorId]);
    });
    
    console.log(`Creados ${Object.keys(actorNodes).length} nodos de actores`);
    
    // Crear nodos para cada valor de columna seleccionada
    selectedColumns.forEach(columnName => {
        if (columnName === 'Actor') return; // Skip Actor column as it's already handled
        
        console.log(`Procesando columna: ${columnName}`);
        const uniqueValues = getUniqueValuesForColumn(columnName);
        console.log(`Valores únicos para ${columnName}:`, uniqueValues);
        
        uniqueValues.forEach(value => {
            const nodeKey = `${columnName}_${value}`;
            
            if (!columnValueNodes[nodeKey]) {
                console.log(`Creando nodo para valor: ${columnName} = ${value}`);
                
                columnValueNodes[nodeKey] = {
                    id: nodeIdCounter.value++,
                    label: value.toString().toUpperCase(),
                    title: `<b>${columnName.toUpperCase()}:</b><br>${value}`,
                    color: {
                        background: getColumnNodeColor(columnName),
                        border: darkenColor(getColumnNodeColor(columnName), 30),
                        highlight: { 
                            background: lightenColor(getColumnNodeColor(columnName), 20), 
                            border: darkenColor(getColumnNodeColor(columnName), 40) 
                        }
                    },
                    size: getColumnNodeSize(columnName),
                    shape: 'box',
                    font: { size: 14, color: '#000000', face: 'Arial Bold' },
                    group: columnName.toLowerCase(),
                    columnType: columnName,
                    value: value
                };
                
                nodes.push(columnValueNodes[nodeKey]);
            }
        });
    });
    
    console.log(`Creados ${Object.keys(columnValueNodes).length} nodos de valores de columnas`);
    
    // Crear conexiones entre actores y valores de columnas
    currentProjectData.forEach((item, index) => {
        const actorId = `actor_${index}`;
        const actorNodeId = actorNodes[actorId].id;
        const actorData = item.data || {};
        
        selectedColumns.forEach(columnName => {
            if (columnName === 'Actor') return;
            
            const value = actorData[columnName];
            if (value) {
                const nodeKey = `${columnName}_${value}`;
                const columnNode = columnValueNodes[nodeKey];
                
                if (columnNode) {
                    console.log(`Creando conexión: ${actorData.Actor || 'Actor'} → ${columnName}: ${value}`);
                    
                    edges.push({
                        from: actorNodeId,
                        to: columnNode.id,
                        color: { 
                            color: getConnectionColor(columnName), 
                            opacity: 0.8 
                        },
                        width: 3,
                        arrows: { to: { enabled: true, scaleFactor: 1.0 } },
                        label: columnName,
                        title: `${actorData.Actor || 'Actor'} → ${columnName}: ${value}`,
                        smooth: { type: 'curvedCW', roundness: 0.3 },
                        font: {
                            size: 11,
                            face: 'Arial',
                            background: 'rgba(255,255,255,0.9)',
                            strokeWidth: 1,
                            strokeColor: '#ffffff'
                        }
                    });
                } else {
                    console.log(`No se encontró nodo para ${nodeKey}`);
                }
            }
        });
    });
    
    console.log(`Creadas ${edges.length} conexiones`);
    console.log(`Total nodos: ${nodes.length}, Total conexiones: ${edges.length}`);
    
    return { nodes, edges };
}

function prepareOldSystemVisualization(selectedColumns) {
    const nodes = [];
    const edges = [];
    const nodeIdCounter = { value: 0 };
    const columnValueNodes = {};
    const actorNodes = {};
    
    // Crear nodos de actores del sistema anterior
    oldSystemData.forEach((item, index) => {
        const actorName = item.actor || `Actor ${index + 1}`;
        const actorId = `actor_${index}`;
        
        actorNodes[actorId] = {
            id: nodeIdCounter.value++,
            label: actorName.toUpperCase(),
            title: `<b>ACTOR:</b> ${actorName}`,
            color: {
                background: '#4CAF50',
                border: '#2E7D32',
                highlight: { background: '#66BB6A', border: '#1B5E20' }
            },
            size: 45,
            shape: 'ellipse',
            font: { size: 16, color: '#000000', face: 'Arial Black' },
            group: 'actors',
            data: item
        };
        
        nodes.push(actorNodes[actorId]);
    });
    
    // Crear nodos para valores de columnas
    selectedColumns.forEach(columnName => {
        const uniqueValues = getUniqueValuesForColumnOldSystem(columnName);
        
        uniqueValues.forEach(value => {
            const nodeKey = `${columnName}_${value}`;
            
            if (!columnValueNodes[nodeKey]) {
                columnValueNodes[nodeKey] = {
                    id: nodeIdCounter.value++,
                    label: value.toString().toUpperCase(),
                    title: `<b>${columnName.toUpperCase()}:</b><br>${value}`,
                    color: {
                        background: getColumnNodeColor(columnName),
                        border: darkenColor(getColumnNodeColor(columnName), 30),
                        highlight: { 
                            background: lightenColor(getColumnNodeColor(columnName), 20), 
                            border: darkenColor(getColumnNodeColor(columnName), 40) 
                        }
                    },
                    size: getColumnNodeSize(columnName),
                    shape: 'box',
                    font: { size: 14, color: '#000000', face: 'Arial Bold' },
                    group: columnName.toLowerCase(),
                    columnType: columnName,
                    value: value
                };
                
                nodes.push(columnValueNodes[nodeKey]);
            }
        });
    });
    
    // Crear conexiones
    oldSystemData.forEach((item, index) => {
        const actorId = `actor_${index}`;
        const actorNodeId = actorNodes[actorId].id;
        
        selectedColumns.forEach(columnName => {
            const value = item[columnName.toLowerCase()];
            if (value) {
                const nodeKey = `${columnName}_${value}`;
                const columnNodeId = columnValueNodes[nodeKey]?.id;
                
                if (columnNodeId !== undefined) {
                    edges.push({
                        from: actorNodeId,
                        to: columnNodeId,
                        color: { 
                            color: getConnectionColor(columnName), 
                            opacity: 0.8 
                        },
                        width: 3,
                        arrows: { to: { enabled: true, scaleFactor: 1.0 } },
                        label: columnName,
                        title: `${item.actor} → ${columnName}: ${value}`,
                        smooth: { type: 'curvedCW', roundness: 0.3 },
                        font: {
                            size: 11,
                            face: 'Arial',
                            background: 'rgba(255,255,255,0.9)',
                            strokeWidth: 1,
                            strokeColor: '#ffffff'
                        }
                    });
                }
            }
        });
    });
    
    return { nodes, edges };
}

function getUniqueValuesForColumnOldSystem(columnName) {
    const values = new Set();
    
    oldSystemData.forEach(item => {
        const value = item[columnName.toLowerCase()];
        if (value && value !== null && value !== undefined && value !== '') {
            values.add(value);
        }
    });
    
    return Array.from(values);
}

function getColumnNodeSize(columnName) {
    const sizes = {
        'Actor': 45,
        'Categoría': 35,
        'Rol': 40,
        'Influencia': 30,
        'Categoria': 35,
        'categoria': 35
    };
    
    return sizes[columnName] || 30;
}

function getConnectionColor(columnName) {
    const colors = {
        'Categoría': '#1976D2',
        'Rol': '#F57C00',
        'Influencia': '#7B1FA2',
        'Categoria': '#1976D2',
        'categoria': '#1976D2'
    };
    
    return colors[columnName] || '#616161';
}

function darkenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.max(0, (num >> 16) - amt);
    const G = Math.max(0, (num >> 8 & 0x00FF) - amt);
    const B = Math.max(0, (num & 0x0000FF) - amt);
    return "#" + (0x1000000 + R * 0x10000 + G * 0x100 + B).toString(16).slice(1);
}

function getColumnNodeColor(columnName) {
    // Verificar si hay un color personalizado configurado
    if (columnColorConfig[columnName] && columnColorConfig[columnName]['_baseColor']) {
        return columnColorConfig[columnName]['_baseColor'];
    }
    
    // Si es Actor, verificar color personalizado
    if (columnName === 'Actor' && defaultColumnColors['Actor']) {
        return defaultColumnColors['Actor'];
    }
    
    // Colores por defecto
    const colors = {
        'Actor': '#4CAF50',       // Verde
        'Categoría': '#2196F3',   // Azul
        'Rol': '#FF9800',         // Naranja
        'Influencia': '#9C27B0',  // Púrpura
        'Categoria': '#2196F3',   // Compatibilidad
        'categoria': '#2196F3'    // Compatibilidad
    };
    
    return colors[columnName] || '#607D8B'; // Gris por defecto
}

function getNodeColorByColumn(data, columnName) {
    // Esta función ya no se usa con la nueva lógica, pero la mantenemos para compatibilidad
    return getColumnNodeColor(columnName);
}

function getNodeSizeByImportance(data) {
    const influencia = data.Influencia || data.influencia || 'Baja';
    if (influencia === 'Alta' || influencia === 'Alto') return 50;
    if (influencia === 'Media' || influencia === 'Medio') return 35;
    return 25;
}

function getNodeShapeByCategory(categoria) {
    if (!categoria) return 'dot';
    
    const shapes = {
        'Público': 'triangle',
        'Privado': 'circle',
        'Mixto': 'diamond',
        'Academia': 'square',
        'ONG': 'star'
    };
    
    return shapes[categoria] || 'dot';
}

function findRelationship(data1, data2, selectedColumns) {
    // Esta función ya no se usa con la nueva arquitectura de nodos separados
    return null;
}

function findRelationshipOldSystem(item1, item2, selectedColumns) {
    // Esta función ya no se usa con la nueva arquitectura de nodos separados
    return null;
}

function getRelationshipStrength(columnType) {
    const strengths = {
        'Categoría': 4,
        'Rol': 3,
        'Influencia': 2
    };
    
    return strengths[columnType] || 1;
}

function getRelationshipColor(columnType) {
    const colors = {
        'Categoría': '#4CAF50',
        'Rol': '#2196F3', 
        'Influencia': '#FF9800'
    };
    
    return colors[columnType] || '#757575';
}

function lightenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) + amt;
    const G = (num >> 8 & 0x00FF) + amt;
    const B = (num & 0x0000FF) + amt;
    return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
}

function createAdvancedNetworkVisualization(nodes, edges) {
    const container = document.getElementById('network');
    const data = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
    
    const options = {
        layout: {
            improvedLayout: true,
            hierarchical: {
                enabled: false
            }
        },
        physics: {
            enabled: true,
            forceAtlas2Based: {
                gravitationalConstant: -120,
                centralGravity: 0.01,
                springLength: 250,
                springConstant: 0.05,
                damping: 0.6,
                avoidOverlap: 1
            },
            maxVelocity: 30,
            solver: 'forceAtlas2Based',
            timestep: 0.35,
            stabilization: {
                enabled: true,
                iterations: 300,
                updateInterval: 25
            }
        },
        interaction: {
            dragNodes: true,
            dragView: true,
            zoomView: true,
            selectConnectedEdges: true,
            hover: true
        },
        nodes: {
            borderWidth: 3,
            shadow: { 
                enabled: true, 
                color: 'rgba(0,0,0,0.4)', 
                size: 12, 
                x: 4, 
                y: 4 
            },
            font: { 
                size: 16, 
                face: 'Arial Black', 
                color: '#000000',
                strokeWidth: 0
            },
            margin: {
                top: 10,
                right: 15,
                bottom: 10,
                left: 15
            }
        },
        edges: {
            smooth: { 
                type: 'curvedCW',
                forceDirection: 'none',
                roundness: 0.4
            },
            shadow: { 
                enabled: true, 
                color: 'rgba(0,0,0,0.3)', 
                size: 6, 
                x: 3, 
                y: 3 
            },
            font: {
                size: 12,
                face: 'Arial Bold',
                background: 'rgba(255,255,255,0.9)',
                strokeWidth: 2,
                strokeColor: '#ffffff',
                align: 'middle'
            },
            labelHighlightBold: true,
            arrows: {
                to: {
                    enabled: true,
                    scaleFactor: 1.2,
                    type: 'arrow'
                }
            }
        },
        groups: {
            actors: {
                color: { background: '#4CAF50', border: '#2E7D32' },
                shape: 'ellipse'
            },
            categoría: {
                color: { background: '#2196F3', border: '#1565C0' },
                shape: 'box'
            },
            rol: {
                color: { background: '#FF9800', border: '#E65100' },
                shape: 'diamond'
            },
            influencia: {
                color: { background: '#9C27B0', border: '#6A1B9A' },
                shape: 'triangle'
            }
        }
    };
    
    network = new vis.Network(container, data, options);
    
    // Eventos de interacción mejorados
    network.on('click', function(params) {
        if (params.nodes.length > 0) {
            const nodeId = params.nodes[0];
            const nodeData = data.nodes.get(nodeId);
            showDetailedNodeInfo(nodeData);
            highlightNodeConnections(nodeId, data);
        }
    });
    
    network.on('stabilizationIterationsDone', function() {
        network.setOptions({ physics: { enabled: false } });
        console.log('Visualización estabilizada - Red de actores lista');
    });
    
    network.on('hoverNode', function(params) {
        const nodeId = params.node;
        // Efecto visual de hover
        network.setSelection({ nodes: [nodeId] }, { highlightEdges: true });
    });
    
    network.on('blurNode', function(params) {
        // Quitar efecto de hover
        network.setSelection({ nodes: [] });
    });
}

function highlightNodeConnections(nodeId, data) {
    const connectedEdges = network.getConnectedEdges(nodeId);
    const connectedNodes = network.getConnectedNodes(nodeId);
    
    // Información sobre las conexiones
    let connectionInfo = '<div class="mt-3"><h6>Conexiones:</h6><ul class="list-group">';
    
    connectedEdges.forEach(edgeId => {
        const edge = data.edges.get(edgeId);
        const fromNode = data.nodes.get(edge.from);
        const toNode = data.nodes.get(edge.to);
        
        connectionInfo += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>${fromNode.label}</strong> → <strong>${toNode.label}</strong></span>
                <span class="badge bg-primary">${edge.label}</span>
            </li>
        `;
    });
    
    connectionInfo += '</ul></div>';
    
    // Agregar información de conexiones al panel de información
    const infoDiv = document.getElementById('selectedNodeInfo');
    const existingContent = infoDiv.innerHTML;
    infoDiv.innerHTML = existingContent + connectionInfo;
}

function showDetailedNodeInfo(nodeData) {
    const infoDiv = document.getElementById('selectedNodeInfo');
    
    let details = '';
    
    if (nodeData.group === 'actors') {
        // Información de actor
        const data = nodeData.data || {};
        details = `
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user"></i> ACTOR: ${nodeData.label}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
        `;
        
        Object.keys(data).forEach(key => {
            if (data[key] && data[key] !== '') {
                details += `
                    <div class="col-md-6 mb-2">
                        <strong class="text-primary">${key}:</strong><br>
                        <span class="badge bg-secondary">${data[key]}</span>
                    </div>
                `;
            }
        });
        
        details += `
                    </div>
                </div>
            </div>
        `;
    } else {
        // Información de nodo de valor de columna
        details = `
            <div class="card">
                <div class="card-header" style="background-color: ${nodeData.color.background}; color: white;">
                    <h6 class="mb-0">
                        <i class="fas fa-tag"></i> ${nodeData.columnType?.toUpperCase() || 'VALOR'}
                    </h6>
                </div>
                <div class="card-body">
                    <h5 class="text-center mb-3">${nodeData.label}</h5>
                    <p class="text-muted text-center">
                        Este nodo representa un valor de la columna <strong>${nodeData.columnType}</strong>
                    </p>
                    
                    <div id="actorsConnectedToThisValue" class="mt-3">
                        <h6>Actores conectados a este valor:</h6>
                        <div class="d-flex flex-wrap gap-2" id="connectedActorsBadges">
                            <!-- Se llenará dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Mostrar actores conectados a este valor
        setTimeout(() => {
            const connectedNodes = network.getConnectedNodes(nodeData.id);
            const badgesContainer = document.getElementById('connectedActorsBadges');
            
            if (badgesContainer) {
                badgesContainer.innerHTML = '';
                const nodes = network.body.data.nodes;
                
                connectedNodes.forEach(connectedNodeId => {
                    const connectedNode = nodes.get(connectedNodeId);
                    if (connectedNode && connectedNode.group === 'actors') {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-success';
                        badge.textContent = connectedNode.label;
                        badgesContainer.appendChild(badge);
                    }
                });
                
                if (badgesContainer.children.length === 0) {
                    badgesContainer.innerHTML = '<span class="text-muted">No hay actores conectados</span>';
                }
            }
        }, 100);
    }
    
    infoDiv.innerHTML = details;
}

function showDetailedNodeInfo(nodeData) {
    const infoDiv = document.getElementById('selectedNodeInfo');
    const data = nodeData.data;
    
    let details = `
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-user"></i> ${nodeData.label}</h6>
            </div>
            <div class="card-body">
                <div class="row">
    `;
    
    // Mostrar toda la información disponible
    Object.keys(data).forEach(key => {
        if (data[key] && data[key] !== '') {
            details += `
                <div class="col-md-6 mb-2">
                    <strong class="text-primary">${key}:</strong><br>
                    <span class="badge bg-secondary">${data[key]}</span>
                </div>
            `;
        }
    });
    
    details += `
                </div>
            </div>
        </div>
    `;
    
    infoDiv.innerHTML = details;
}

function updateNetworkStats(data) {
    document.getElementById('totalActors').textContent = data.nodes.length;
    document.getElementById('totalConnections').textContent = data.edges.length;
    
    // Encontrar el actor más conectado
    const connectionCount = {};
    data.edges.forEach(edge => {
        connectionCount[edge.from] = (connectionCount[edge.from] || 0) + 1;
        connectionCount[edge.to] = (connectionCount[edge.to] || 0) + 1;
    });
    
    let maxConnections = 0;
    let centralActorIndex = null;
    Object.keys(connectionCount).forEach(nodeIndex => {
        if (connectionCount[nodeIndex] > maxConnections) {
            maxConnections = connectionCount[nodeIndex];
            centralActorIndex = nodeIndex;
        }
    });
    
    const centralActorName = centralActorIndex !== null ? 
        data.nodes[centralActorIndex].label : 'Ninguno';
    document.getElementById('centralActor').textContent = `${centralActorName} (${maxConnections} conexiones)`;
}

function fitNetwork() {
    if (network) {
        network.fit({ 
            animation: { duration: 1000, easingFunction: 'easeInOutQuad' }
        });
    }
}

function exportToPNG() {
    if (network) {
        // Mostrar mensaje de carga
        const originalContent = document.getElementById('network').innerHTML;
        
        html2canvas(document.getElementById('network'), {
            backgroundColor: '#ffffff',
            scale: 2,
            logging: false,
            useCORS: true,
            allowTaint: false,
            foreignObjectRendering: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'mapa-actores-' + new Date().toISOString().slice(0,10) + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(err => {
            console.error('Error al exportar PNG:', err);
            alert('Error al generar la exportación PNG');
        });
    } else {
        alert('La visualización no está lista. Intenta actualizar primero.');
    }
}

function exportToPDF() {
    if (network) {
        // Mostrar mensaje de carga
        const loadingMsg = document.createElement('div');
        loadingMsg.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Generando PDF...</div>';
        
        html2canvas(document.getElementById('network'), {
            backgroundColor: '#ffffff',
            scale: 2,
            logging: false,
            useCORS: true,
            allowTaint: false,
            foreignObjectRendering: false
        }).then(canvas => {
            try {
                const { jsPDF } = window.jspdf;
                
                // Crear PDF en formato landscape para mejor visualización
                const pdf = new jsPDF('landscape', 'mm', 'a4');
                
                // Dimensiones del PDF (A4 landscape: 297x210mm)
                const pdfWidth = 297;
                const pdfHeight = 210;
                
                // Calcular dimensiones de la imagen manteniendo aspecto
                const canvasWidth = canvas.width;
                const canvasHeight = canvas.height;
                const ratio = canvasWidth / canvasHeight;
                
                let imgWidth = pdfWidth - 20; // Margen de 10mm por lado
                let imgHeight = imgWidth / ratio;
                
                // Si la altura es mayor que el PDF, ajustar por altura
                if (imgHeight > pdfHeight - 20) {
                    imgHeight = pdfHeight - 20;
                    imgWidth = imgHeight * ratio;
                }
                
                // Centrar imagen en el PDF
                const x = (pdfWidth - imgWidth) / 2;
                const y = (pdfHeight - imgHeight) / 2;
                
                // Convertir canvas a imagen
                const imgData = canvas.toDataURL('image/png', 1.0);
                
                // Agregar título
                pdf.setFontSize(16);
                pdf.setTextColor(40);
                pdf.text('Mapa de Actores - Análisis de Influencias', 10, 15);
                
                // Agregar fecha
                pdf.setFontSize(10);
                pdf.setTextColor(100);
                pdf.text('Generado el: ' + new Date().toLocaleDateString('es-ES'), 10, 25);
                
                // Agregar imagen
                pdf.addImage(imgData, 'PNG', x, y + 10, imgWidth, imgHeight - 10);
                
                // Agregar pie de página
                pdf.setFontSize(8);
                pdf.setTextColor(150);
                pdf.text('Sistema de Recolección de Datos Agroforestales', 10, pdfHeight - 10);
                
                // Guardar PDF
                const fileName = 'mapa-actores-' + new Date().toISOString().slice(0,10) + '.pdf';
                pdf.save(fileName);
                
            } catch (error) {
                console.error('Error al crear PDF:', error);
                alert('Error al generar el PDF: ' + error.message);
            }
        }).catch(err => {
            console.error('Error al capturar imagen para PDF:', err);
            alert('Error al capturar la imagen para PDF');
        });
    } else {
        alert('La visualización no está lista. Intenta actualizar primero.');
    }
}

// Función legacy para compatibilidad
function exportChart() {
    exportToPNG();
}

</script>

@endsection
