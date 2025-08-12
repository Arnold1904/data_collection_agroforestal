<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 500 - Problema en el Servidor | {{ config('app.name', 'Sistema de Recolección de Datos') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Contenedor principal -->
        <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">
            
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 p-6 text-center">
                <div class="float">
                    <div class="w-24 h-24 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4 pulse-glow">
                        <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Error 500</h1>
                <p class="text-red-100 text-lg">Problema en el Servidor</p>
            </div>

            <!-- Contenido principal -->
            <div class="p-8">
                <!-- Mensaje principal -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-4">
                        ¡Ups! Parece que hubo un error en la conexión
                    </h2>
                    <div class="text-gray-300 leading-relaxed space-y-3">
                        <p class="text-lg">
                            <i class="fas fa-wifi mr-2 text-cyan-400"></i>
                            Por favor, <strong class="text-white">revisa tu conexión a internet</strong>
                        </p>
                        <p>
                            Si tu conexión está funcionando correctamente, puede ser un error temporal con nuestro servidor.
                        </p>
                        <p class="text-cyan-200">
                            <i class="fas fa-clock mr-2"></i>
                            Te pedimos que <strong>esperes unos momentos</strong> mientras se restablece el servicio.
                        </p>
                    </div>
                </div>

                <!-- Línea decorativa -->
                <div class="w-24 h-1 bg-gradient-to-r from-cyan-400 to-blue-600 mx-auto mb-8 rounded-full"></div>

                <!-- Sugerencias -->
                <div class="bg-gray-700 rounded-xl p-6 mb-8 border border-gray-600">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                        <h3 class="text-lg font-semibold text-white">¿Qué puedes hacer mientras tanto?</h3>
                    </div>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-redo text-green-400 mr-3 text-sm"></i>
                            <span>Recarga la página en unos segundos</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-network-wired text-blue-400 mr-3 text-sm"></i>
                            <span>Verifica tu conexión a internet</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-history text-purple-400 mr-3 text-sm"></i>
                            <span>Intenta nuevamente en unos minutos</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-orange-400 mr-3 text-sm"></i>
                            <span>Si persiste, contacta al administrador del sistema</span>
                        </li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()" 
                            class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>
                        Intentar Nuevamente
                    </button>
                    <button onclick="window.history.back()" 
                            class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Página Anterior
                    </button>
                </div>

                <!-- Mensaje de agradecimiento -->
                <div class="mt-8 p-4 bg-gradient-to-r from-cyan-900 via-blue-900 to-blue-800 rounded-lg border border-cyan-700">
                    <p class="text-center text-cyan-100">
                        <i class="fas fa-heart text-red-400 mr-2"></i>
                        <strong>Agradecemos tu paciencia y colaboración.</strong>
                        Estamos trabajando para brindarte el mejor servicio.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-750 px-8 py-4 border-t border-gray-700">
                <div class="flex justify-between items-center text-sm text-gray-400">
                    <span>
                        <i class="fas fa-code mr-1"></i>
                        Error Code: 500
                    </span>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>

        <!-- Información técnica (solo visible en desarrollo) -->
        @if(config('app.debug'))
        <div class="mt-6 bg-gray-800 rounded-lg border border-gray-700 p-4">
            <details class="text-gray-300">
                <summary class="cursor-pointer font-semibold text-yellow-400 hover:text-yellow-300">
                    <i class="fas fa-bug mr-2"></i>Información de Desarrollo
                </summary>
                <div class="mt-3 text-xs bg-gray-900 p-3 rounded border-l-4 border-red-500">
                    <p><strong>Time:</strong> {{ date('Y-m-d H:i:s') }}</p>
                    <p><strong>URL:</strong> {{ request()->url() }}</p>
                    <p><strong>Method:</strong> {{ request()->method() }}</p>
                    <p><strong>User Agent:</strong> {{ request()->userAgent() }}</p>
                </div>
            </details>
        </div>
        @endif
    </div>

    <script>
        // Mostrar hora actual
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleString('es-ES');
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Auto-recarga después de 30 segundos
        let countdown = 30;
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.reload();
            }
        }, 1000);
    </script>
</body>
</html>
