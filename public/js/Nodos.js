/**
 * Configuración y creación de nodos para el mapa de actores
 * Sistema de mapeo de actores agroforestales
 */

class Nodos {
    constructor() {
        this.nodeIds = new Set();
        this.nodes = [];
        this.edges = [];
    }

    /**
     * Crear nodo de Actor (Círculo azul)
     */
    crearNodoActor(actor) {
        if (!this.nodeIds.has(actor)) {
            this.nodes.push({
                id: actor,
                label: actor,
                shape: 'circle',
                color: { background: '#1e40af', border: '#1e3a8a' },
                font: { color: '#ffffff', size: 14, face: 'Arial' },
                size: 30
            });
            this.nodeIds.add(actor);
        }
    }

    /**
     * Crear nodo de Categoría (Rectángulo verde)
     */
    crearNodoCategoria(categoria) {
        if (!this.nodeIds.has(categoria)) {
            this.nodes.push({
                id: categoria,
                label: categoria,
                shape: 'box',
                color: { background: '#16a34a', border: '#15803d' },
                font: { color: '#ffffff', size: 14, face: 'Arial' },
                margin: 10
            });
            this.nodeIds.add(categoria);
        }
    }

    /**
     * Crear nodo de Rol (Óvalo naranja)
     */
    crearNodoRol(rol) {
        if (!this.nodeIds.has(rol)) {
            this.nodes.push({
                id: rol,
                label: rol,
                shape: 'ellipse',
                color: { background: '#ea580c', border: '#c2410c' },
                font: { color: '#ffffff', size: 14, face: 'Arial' },
                margin: 8
            });
            this.nodeIds.add(rol);
        }
    }

    /**
     * Crear nodo de Influencia (Rectángulo redondeado amarillo)
     */
    crearNodoInfluencia(influencia) {
        if (!this.nodeIds.has(influencia)) {
            this.nodes.push({
                id: influencia,
                label: influencia,
                shape: 'box',
                color: { background: '#ca8a04', border: '#a16207' },
                font: { color: '#ffffff', size: 14, face: 'Arial' },
                margin: 10,
                shapeProperties: { borderRadius: 15 }
            });
            this.nodeIds.add(influencia);
        }
    }

    /**
     * Crear conexión entre nodos
     */
    crearConexion(desde, hacia, etiqueta) {
        this.edges.push({
            from: desde,
            to: hacia,
            label: etiqueta,
            arrows: 'to'
        });
    }

    /**
     * Procesar registros y crear todos los nodos y conexiones
     */
    procesarRegistros(registros) {
        registros.forEach(registro => {
            // Crear nodos
            this.crearNodoActor(registro.actor);
            this.crearNodoCategoria(registro.categoria);
            this.crearNodoRol(registro.rol);
            this.crearNodoInfluencia(registro.influencia);

            // Crear conexiones
            this.crearConexion(registro.actor, registro.categoria, 'Categoría');
            this.crearConexion(registro.actor, registro.rol, 'Rol');
            this.crearConexion(registro.actor, registro.influencia, 'Influencia');
        });
    }

    /**
     * Obtener configuración de opciones para vis.js
     */
    obtenerOpciones() {
        return {
            nodes: {
                font: {
                    size: 14,
                    face: 'Arial',
                    color: '#ffffff',
                    strokeWidth: 1,
                    strokeColor: '#000000'
                },
                borderWidth: 2,
                shadow: true,
                chosen: {
                    node: function(values, id, selected, hovering) {
                        values.shadow = true;
                        values.shadowSize = 10;
                    }
                }
            },
            edges: {
                font: {
                    align: 'middle',
                    size: 12,
                    color: '#374151',
                    strokeWidth: 1,
                    strokeColor: '#ffffff'
                },
                color: { color: '#6b7280', highlight: '#374151' },
                width: 2,
                smooth: {
                    type: 'continuous',
                    roundness: 0.5
                }
            },
            layout: { improvedLayout: true },
            physics: false,
            interaction: {
                dragNodes: true,
                dragView: true,
                zoomView: true,
                multiselect: true
            },
            manipulation: { enabled: false },
            minZoom: 0.5,
            maxZoom: 2,
            autoResize: true,
            height: '100%',
            width: '100%',
            restrictMove: true
        };
    }

    /**
     * Obtener datos preparados para vis.js
     */
    obtenerDatos() {
        return {
            nodes: new vis.DataSet(this.nodes),
            edges: new vis.DataSet(this.edges)
        };
    }

    /**
     * Reiniciar todos los datos
     */
    reiniciar() {
        this.nodeIds.clear();
        this.nodes = [];
        this.edges = [];
    }
}

// Hacer disponible globalmente
window.Nodos = Nodos;
