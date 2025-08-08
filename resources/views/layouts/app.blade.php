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
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Verificación de FontAwesome -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Verificar si FontAwesome se cargó
                var testElement = document.createElement('i');
                testElement.className = 'fas fa-home';
                testElement.style.display = 'none';
                document.body.appendChild(testElement);
                
                var computedStyle = window.getComputedStyle(testElement, ':before');
                if (computedStyle.content === 'none' || computedStyle.content === '') {
                    console.warn('FontAwesome no se cargó correctamente');
                    // Mostrar fallbacks
                    document.querySelectorAll('i.fas, i.far, i.fab').forEach(function(icon) {
                        if (icon.nextElementSibling && icon.nextElementSibling.style.display === 'none') {
                            icon.style.display = 'none';
                            icon.nextElementSibling.style.display = 'inline-block';
                        }
                    });
                } else {
                    console.log('FontAwesome cargado correctamente');
                }
                
                document.body.removeChild(testElement);
            });
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: #F3F4F6;">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main style="background-color: #F3F4F6;">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
