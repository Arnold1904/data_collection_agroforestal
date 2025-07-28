<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Roles del Sector Agropecuario') }}
        </h2>
    </x-slot>
    <div class="py-4 acomodar">
        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-100 p-2 rounded">{{ session('success') }}</div>
        @endif
        <a href="{{ route('rol.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold">Nuevo Rol</a>
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $rol)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-2">{{ $rol->id }}</td>
                            <td class="px-4 py-2">{{ $rol->nombre_rol }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('rol.edit', $rol) }}" class="text-blue-600 hover:underline">Editar</a>
                                <!-- Modal Trigger -->
                                <button type="button" class="text-red-600 hover:underline ml-2" onclick="document.getElementById('delete-modal-rol-{{ $rol->id }}').style.display='flex'">Eliminar</button>
                                <!-- Modal -->
                                <div id="delete-modal-rol-{{ $rol->id }}" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden" style="display:none;">
                                    <div class="bg-white rounded-lg shadow-lg p-6 mx-auto w-full max-w-sm flex flex-col items-center relative">
                                        <button type="button" class="absolute top-2 right-2 text-red-600 text-2xl font-bold" onclick="document.getElementById('delete-modal-rol-{{ $rol->id }}').style.display='none'">&times;</button>
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <h3 class="text-lg font-bold mb-2 text-gray-900">¿Estás seguro de borrar este rol?</h3>
                                        <p class="text-gray-700 mb-6 text-center">Esta acción no se puede deshacer.</p>
                                        <div class="flex w-full justify-between">
                                            <form action="{{ route('rol.destroy', $rol) }}" method="POST" class="w-1/2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700">Aceptar</button>
                                            </form>
                                            <button type="button" class="w-1/2 ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded font-bold hover:bg-gray-300" onclick="document.getElementById('delete-modal-rol-{{ $rol->id }}').style.display='none'">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-sidebar-layout>
