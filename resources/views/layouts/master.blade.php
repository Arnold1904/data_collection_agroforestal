<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Ingeniería Agroforestal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Estilos adicionales para compatibilidad de dropdown -->
        <style>
            /* Asegurar que el dropdown funcione correctamente con Bootstrap */
            .relative {
                position: relative !important;
            }
            
            .absolute {
                position: absolute !important;
            }
            
            .z-50 {
                z-index: 1050 !important; /* Usar z-index de Bootstrap para modals */
            }
            
            /* Estilos específicos para el dropdown de usuario */
            [x-data] {
                /* Asegurar que Alpine.js funcione con Bootstrap */
            }
            
            /* Prevenir conflictos de eventos */
            .dropdown-menu {
                pointer-events: auto !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: #F3F4F6;">
            <!-- Navigation fija en la parte superior -->
            <div style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000;">
                @include('layouts.navigation')
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-800 shadow" style="margin-top: 64px;">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="main-content" style="background-color: #F3F4F6; padding-top: 2rem; height: calc(100vh - 64px); overflow-y: auto; margin-top: 64px;">
                @yield('content')
            </main>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Alpine.js compatibility fix -->
        <script>
            // Solucionar conflictos entre Bootstrap y Alpine.js
            document.addEventListener('DOMContentLoaded', function() {
                // Desactivar Bootstrap dropdown automation en elementos Alpine
                document.querySelectorAll('[x-data]').forEach(function(element) {
                    // Prevenir que Bootstrap maneje dropdowns que usan Alpine.js
                    element.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });
                
                // Forzar inicialización de Alpine.js
                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(document.body);
                }
            });
            
            // Prevenir conflictos de eventos Bootstrap con Alpine.js
            document.addEventListener('show.bs.dropdown', function (e) {
                if (e.target.closest('[x-data]')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        </script>
    </body>
</html>
