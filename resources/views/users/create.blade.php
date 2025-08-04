<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>
    <div class="py-8 flex justify-center">
        <form action="{{ route('users.store') }}" method="POST" class="w-full max-w-lg bg-gray-800 p-8 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Rol</label>
                <select name="role" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">Seleccione</option>
                    <option value="admin" @selected(old('role')=='admin')>Admin</option>
                    <option value="profesor" @selected(old('role')=='profesor')>Profesor</option>
                    <option value="estudiante" @selected(old('role')=='estudiante')>Estudiante</option>
                    <option value="visitante" @selected(old('role')=='visitante')>Visitante</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Contraseña</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-200 font-bold mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-bold">Guardar</button>
                <a href="{{ route('users.index') }}" class="ml-2 text-white hover:text-gray-500">Cancelar</a>
            </div>
        </form>
    </div>
</x-sidebar-layout>
