<x-sidebar-layout>
    <div class="w-full bg-gray-800 py-6 shadow text-center">
        <h2 class="font-semibold text-2xl text-gray-200 leading-tight">Diagrama de mapeo de actores</h2>
        <div class="flex justify-center space-x-2 mt-4">
            <button onclick="ExportUtils.exportNetworkToPDF(document.getElementById('network'), 'mapa-actores')" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
                📄 Exportar PDF
            </button>
            <button onclick="ExportUtils.exportNetworkToPNG(document.getElementById('network'), 'mapa-actores')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                🖼️ Exportar PNG
            </button>
        </div>
    </div>
    <div class="w-full h-[calc(100vh-120px)]" style="height: calc(100vh - 120px);">
        <div id="network" style="width:100%; height:100%;"></div>
    </div>
    <script type="text/javascript" src="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.js"></script>
    <link href="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.css" rel="stylesheet" />
    <script src="{{ asset('js/Nodos.js') }}"></script>
    <script>
        // Preparar nodos y edges desde PHP usando la clase Nodos
        const registros = @json($registros);
        
        // Inicializar configurador de nodos
        const configuradorNodos = new Nodos();
        
        // Procesar todos los registros
        configuradorNodos.procesarRegistros(registros);
        
        // Obtener datos y opciones configuradas
        const data = configuradorNodos.obtenerDatos();
        const options = configuradorNodos.obtenerOpciones();
        
        // Crear la red
        const network = new vis.Network(document.getElementById('network'), data, options);
        
        // Limitar el movimiento para que no se pierda el diagrama
        network.on('dragEnd', function () {
            network.fit({ animation: true });
        });
    </script>
    
    <script src="{{ asset('js/export-utils.js') }}"></script>
</x-sidebar-layout>
