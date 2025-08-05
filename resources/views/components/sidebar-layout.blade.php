@php $user = auth()->user(); @endphp
<x-app-layout>
<div class="min-h-screen" class="relative">
    <!-- Sidebar -->
    <aside x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" :class="open ? 'w-64' : 'w-16'" class="fixed top-0 left-0 h-full z-40 transition-all duration-500 ease-in-out bg-white dark:bg-gray-800 shadow-lg flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-700" >
            <span class="font-bold text-lg text-gray-800 dark:text-gray-200" x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Panel</span>
            <span class="font-bold text-lg text-gray-800 dark:text-gray-200" x-show="!open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">&#9776;</span>
        </div>
        <nav class="flex-1 p-4 space-y-2 bg-gray-800 text-gray-200">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Dashboard</a>
            @if($user->isAdmin())
                <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900" x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Gestión de Usuarios</a>
                <a href="{{ route('trabajos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Recolección de datos</a>
                <a href="{{ route('mapa.actores') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Mapa de Actores</a>
                <a href="{{ route('categoria.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900" x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Categorías</a>
                <a href="{{ route('rol.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Roles del sector Agropecuario</a>
            @endif
            @if($user->isProfesor())
                <a href="{{ route('trabajos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Recolección de datos</a>
                <a href="{{ route('mapa.actores') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Mapa de Actores</a>
                <a href="{{ route('categoria.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Categorías</a>
                <a href="{{ route('rol.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Roles Agropecuarios</a>
                @endif
            @if($user->isEstudiante())
                <a href="{{ route('trabajos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Recolección de datos</a>
                <a href="{{ route('mapa.actores') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Mapa de Actores</a>
                <a href="{{ route('categoria.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Categorías</a>
                <a href="{{ route('rol.index') }}" class="block px-4 py-2 rounded hover:bg-blue-100 dark:hover:bg-blue-900"  x-show="open" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Roles Agropecuarios</a>
            @endif
        </nav>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto" x-show="open">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded bg-red-600 text-white font-bold hover:bg-red-700">Cerrar sesión</button>
            </form>
        </div>
    </aside>
    <!-- Main Content -->
    <main :class="open ? 'ml-64' : 'ml-16'" class="transition-all duration-500" style="margin-left: 4rem;">
        {{ $slot }}
    </main>
</div>
</x-app-layout>
