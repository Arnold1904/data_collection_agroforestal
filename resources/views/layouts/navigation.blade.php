<nav x-data="{ open: false }" class="border-b border-blue-900 bg-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"><img src="{{ asset('/images/favicon_io/android-chrome-192x192.png') }}" alt="Logo" class="h-9 w-auto" /></a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" style="color: #ffffff;">
                        {{ __('Inicio') }}
                    </x-nav-link>
                    
                    @if(in_array(Auth::user()->role, ['admin', 'profesor', 'estudiante']))
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" style="color: #ffffff;">
                            <i class="fas fa-folder mr-1"></i>
                            {{ __('Proyectos') }}
                        </x-nav-link>
                    @endif
                    
                    @if(in_array(Auth::user()->role, ['admin', 'profesor', 'estudiante']))
                        <x-nav-link :href="route('mapa.actores')" :active="request()->routeIs('mapa.actores')" style="color: #ffffff;">
                            <i class="fas fa-map mr-1"></i> {{ __('Mapa de Actores') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" style="color: #ffffff;">
                            <i class="fas fa-users mr-1"></i> {{ __('Gestión de Usuarios') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-white text-sm leading-4 font-medium rounded-md bg-gray-800 hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800" style="position: absolute; top: 0; left: 4rem; width: calc(100vw - 4rem); z-index: 50;">
        <!-- Botón para cerrar el menú -->
        <div class="flex justify-end p-2 bg-gray-800">
            <button @click="open = false" class="p-2 rounded bg-red-600 text-white font-bold hover:bg-red-700">Cerrar ✕</button>
        </div>
        <div class="pt-2 pb-3 space-y-1 bg-gray-800">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            
            @if(in_array(Auth::user()->role, ['admin', 'profesor', 'estudiante']))
                <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                    {{ __('Proyectos') }}
                </x-responsive-nav-link>
            @endif
            
            @if(in_array(Auth::user()->role, ['admin', 'profesor', 'estudiante']))
                <x-responsive-nav-link :href="route('mapa.actores')" :active="request()->routeIs('mapa.actores')">
                    <i class="fas fa-map mr-2"></i> {{ __('Mapa de Actores') }}
                </x-responsive-nav-link>
            @endif
            
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    <i class="fas fa-users mr-2"></i> {{ __('Gestión de Usuarios') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700 bg-gray-800">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
