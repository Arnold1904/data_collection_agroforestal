<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mapa de Actores') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-6xl mx-auto">
        <div id="network" style="height: 600px;"></div>
    </div>
    <script type="text/javascript" src="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.js"></script>
    <link href="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.css" rel="stylesheet" />
    <script>
        // Preparar nodos y edges desde PHP
        const registros = @json($registros);
        let nodes = [];
        let edges = [];
        let nodeIds = new Set();
        registros.forEach(r => {
            // Actor como nodo principal
            if (!nodeIds.has(r.actor)) {
                nodes.push({ id: r.actor, label: r.actor, shape: 'ellipse', color: '#2563eb' });
                nodeIds.add(r.actor);
            }
            // Categoria como nodo
            if (!nodeIds.has(r.categoria)) {
                nodes.push({ id: r.categoria, label: r.categoria, shape: 'box', color: '#22c55e' });
                nodeIds.add(r.categoria);
            }
            // Rol como nodo
            if (!nodeIds.has(r.rol)) {
                nodes.push({ id: r.rol, label: r.rol, shape: 'diamond', color: '#f59e42' });
                nodeIds.add(r.rol);
            }
            // Influencia como nodo
            if (!nodeIds.has(r.influencia)) {
                nodes.push({ id: r.influencia, label: r.influencia, shape: 'star', color: '#eab308' });
                nodeIds.add(r.influencia);
            }
            // Relaciones
            edges.push({ from: r.actor, to: r.categoria, label: 'Categoría', arrows: 'to' });
            edges.push({ from: r.actor, to: r.rol, label: 'Rol', arrows: 'to' });
            edges.push({ from: r.actor, to: r.influencia, label: 'Influencia', arrows: 'to' });
        });
        const data = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
        const options = {
            nodes: { font: { size: 16 } },
            edges: { font: { align: 'middle' }, color: { color: '#888' } },
            layout: { improvedLayout: true },
            physics: { stabilization: true }
        };
        new vis.Network(document.getElementById('network'), data, options);
    </script>
</x-sidebar-layout>
