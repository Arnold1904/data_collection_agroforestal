<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @php $user = auth()->user(); @endphp 
                    <h3 class="text-lg font-bold mb-4">Bienvenido, {{ $user->name }}</h3>
                    
                    @if($user->role === 'visitante')
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">
                                        <strong>Cuenta pendiente de asignación de rol.</strong><br>
                                        Tu cuenta está pendiente de asignación de rol. Por favor espera a que un administrador te asigne un rol para acceder a las funcionalidades de la plataforma.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if(in_array($user->role, ['admin', 'profesor', 'estudiante']))
                                <div class="bg-blue-600 rounded-lg p-6 text-center">
                                    <i class="fas fa-folder text-3xl mb-3"></i>
                                    <h4 class="text-lg font-semibold">Proyectos</h4>
                                    <p class="text-sm opacity-90">Gestiona tus proyectos de investigación</p>
                                    <a href="{{ route('projects.index') }}" class="mt-3 inline-block bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded text-sm">Ver Proyectos</a>
                                </div>
                            @endif
                            
                            @if(in_array($user->role, ['admin', 'profesor', 'estudiante']))
                                <div class="bg-green-600 rounded-lg p-6 text-center">
                                    <i class="fas fa-map text-3xl mb-3"></i>
                                    <h4 class="text-lg font-semibold">Mapa de Actores</h4>
                                    <p class="text-sm opacity-90">Visualiza la red de actores</p>
                                    <a href="{{ route('mapa.actores') }}" class="mt-3 inline-block bg-green-700 hover:bg-green-800 px-4 py-2 rounded text-sm">Ver Mapa</a>
                                </div>
                            @endif
                            
                            @if($user->role === 'admin')
                                <div class="rounded-lg p-6 text-center" style="background-color: #6366f1;">
                                    <div class="text-3xl mb-3">
                                        <i class="fas fa-users" style="display: inline-block;"></i>
                                        <span style="display: none;">👥</span>
                                    </div>
                                    <h4 class="text-lg font-semibold">Gestión de Usuarios</h4>
                                    <p class="text-sm opacity-90">Administra usuarios y permisos</p>
                                    <a href="{{ route('users.index') }}" class="mt-3 inline-block px-4 py-2 rounded text-sm" style="background-color: #4f46e5; color: white; text-decoration: none;">Gestionar Usuarios</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold mb-3">Tu rol: <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ ucfirst($user->role) }}</span></h4>
                            @if($user->role === 'admin')
                                <p class="text-gray-300">Como administrador, tienes acceso completo a todas las funcionalidades del sistema.</p>
                            @elseif($user->role === 'profesor')
                                <p class="text-gray-300">Como profesor, puedes acceder a proyectos y mapa de actores.</p>
                            @elseif($user->role === 'estudiante')
                                <p class="text-gray-300">Como estudiante, puedes ver proyectos y mapa de actores (solo lectura en proyectos).</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
