<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nueva Categoría') }}
        </h2>
    </x-slot>
    <div class="py-8 flex justify-center">
        <form action="{{ route('categoria.store') }}" method="POST" class="w-full max-w-lg bg-white dark:bg-gray-800 p-8 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Nombre de la Categoría</label>
                <input type="text" name="nombre_cat" value="{{ old('nombre_cat') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-bold">Guardar</button>
                <a href="{{ route('categoria.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">Cancelar</a>
            </div>
        </form>
    </div>
</x-sidebar-layout>
