<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Editar Registro de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-8 flex justify-center">
        <form action="{{ route('trabajos.update', $trabajo) }}" method="POST" class="w-full max-w-lg bg-gray-800 p-8 rounded shadow">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Actor</label>
                <input type="text" name="actor" value="{{ old('actor', $trabajo->actor) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Categoría</label>
                <select name="categoria" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}" @selected(old('categoria', $trabajo->categoria)==$cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Rol</label>
                <select name="rol" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol }}" @selected(old('rol', $trabajo->rol)==$rol)>{{ $rol }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Influencia</label>
                <select name="influencia" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione</option>
                    @foreach($influencias as $influencia)
                        <option value="{{ $influencia }}" @selected(old('influencia', $trabajo->influencia)==$influencia)>{{ $influencia }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Relación con el cambio climático</label>
                <input type="text" name="relcamclim" value="{{ old('relcamclim', $trabajo->relcamclim) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Vinculación con la agroforestería</label>
                <input type="text" name="vinagro" value="{{ old('vinagro', $trabajo->vinagro) }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Observaciones</label>
                <input type="text" name="observaciones" value="{{ old('observaciones', $trabajo->observaciones) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-bold">Actualizar</button>
                <a href="{{ route('trabajos.index') }}" class="ml-2 text-white hover:text-gray-500">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
