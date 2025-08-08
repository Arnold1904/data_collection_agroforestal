@extends('layouts.master')

@section('content')
<div class="py-8 max-w-6xl mx-auto">
    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 p-2 rounded">{{ session('success') }}</div>
    @endif
    
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-xl text-gray-700 leading-tight">
            Proyectos
        </h2>
        <div class="space-y-3 lg:space-y-0 lg:flex lg:space-x-2">
            @if($projects->count() > 0)
                <div class="flex space-x-2">
                    <button onclick="ExportUtils.exportToPDF('projects-table', 'lista-proyectos')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button onclick="ExportUtils.exportToExcel('projects-table', 'lista-proyectos')" 
                        class="text-white px-3 py-2 rounded font-bold text-sm" 
                        style="background-color: #16a34a;" 
                        onmouseover="this.style.backgroundColor='#15803d'" 
                        onmouseout="this.style.backgroundColor='#16a34a'">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            @endif
            @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                <div class="flex">
                    <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded font-bold text-sm">
                        <i class="fas fa-plus"></i> Crear Nuevo Proyecto
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($projects->count() > 0)
        <div class="overflow-x-auto mt-4 bg-gray-500 rounded-lg p-4">
            <table id="projects-table" class="min-w-full bg-gray-800 rounded shadow text-sm sm:text-base">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Nro</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Nombre del Proyecto</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Fecha de Creación</th>
                        <th class="px-2 py-1 sm:px-4 sm:py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $loop->iteration }}</td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $project->nombre }}</td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">{{ $project->created_at->format('d/m/Y') }}</td>
                        <td class="px-2 text-white py-1 sm:px-4 sm:py-2">
                            <a href="{{ route('projects.show', $project) }}" class="text-green-600 hover:underline">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                                <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:underline ml-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <!-- Modal Trigger -->
                                <button type="button" class="text-red-600 hover:underline ml-2" onclick="document.getElementById('delete-modal-project-{{ $project->id }}').style.display='flex'">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                
                                <!-- Modal -->
                                <div id="delete-modal-project-{{ $project->id }}" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden" style="display:none;">
                                    <div class="bg-white rounded-lg shadow-lg p-6 mx-auto w-full max-w-sm flex flex-col items-center relative">
                                        <button type="button" class="absolute top-2 right-2 text-red-600 text-2xl font-bold" onclick="document.getElementById('delete-modal-project-{{ $project->id }}').style.display='none'">&times;</button>
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <h3 class="text-lg font-bold mb-2 text-gray-900">¿Estás seguro de borrar este proyecto?</h3>
                                        <p class="text-gray-700 mb-6 text-center">Esta acción no se puede deshacer y eliminará todos los datos asociados.</p>
                                        <div class="flex w-full justify-between">
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="w-1/2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">Aceptar</button>
                                            </form>
                                            <button type="button" class="w-1/2 ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-300" onclick="document.getElementById('delete-modal-project-{{ $project->id }}').style.display='none'">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-gray-800 rounded-lg shadow p-8 text-center">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h5 class="text-gray-300 text-xl mb-2">No hay proyectos creados</h5>
            <p class="text-gray-400 mb-4">
                @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                    Crea tu primer proyecto haciendo clic en el botón "Crear Nuevo Proyecto"
                @else
                    No tienes permisos para crear proyectos, pero puedes ver los proyectos existentes
                @endif
            </p>
            @if(in_array(auth()->user()->role, ['admin', 'profesor']))
                <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-bold inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Crear Proyecto
                </a>
            @endif
        </div>
    @endif
</div>

<script src="{{ asset('js/export-utils.js') }}"></script>
@endsection
