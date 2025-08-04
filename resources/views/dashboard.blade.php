<x-sidebar-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    @php $user = auth()->user(); @endphp 
                    <h3 class="text-lg font-bold mb-4">Bienvenido, {{ $user->name }}</h3>
                    @if($user->role === 'visitante')
                        <div class="mb-2 text-yellow-500">
                            Tu cuenta está pendiente de asignación de rol. Por favor espera a que un administrador te asigne un rol para acceder a la plataforma.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
