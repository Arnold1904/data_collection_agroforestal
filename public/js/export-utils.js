// Funciones de exportación para las diferentes vistas
class ExportUtils {
    
    // Función para exportar tabla a PDF
    static async exportToPDF(tableId, filename = 'documento') {
        try {
            // Mostrar indicador de carga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '⏳ Generando...';
            button.disabled = true;
            
            // Cargar jsPDF desde CDN si no está disponible
            if (typeof window.jsPDF === 'undefined') {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
            }
            
            // Cargar autoTable si no está disponible
            if (typeof window.jspdf === 'undefined' || !window.jspdf.jsPDF.API.autoTable) {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js');
            }
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            const table = document.getElementById(tableId);
            if (!table) {
                throw new Error('Tabla no encontrada');
            }
            
            // Configurar fuente
            doc.setFontSize(16);
            doc.text(filename.toUpperCase(), 20, 20);
            
            // Obtener datos de la tabla
            const rows = [];
            const headers = [];
            
            // Obtener headers (excluyendo la columna de acciones)
            const headerCells = table.querySelectorAll('thead tr th');
            headerCells.forEach((cell, index) => {
                const text = cell.textContent.trim();
                if (text !== 'Acciones') {
                    headers.push(text);
                }
            });
            
            // Obtener filas de datos
            const bodyRows = table.querySelectorAll('tbody tr');
            bodyRows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    // Evitar incluir columnas de acciones
                    const text = cell.textContent.trim();
                    if (!cell.querySelector('button') && !cell.querySelector('a.bg-') && index < headers.length) {
                        rowData.push(text);
                    }
                });
                if (rowData.length > 0) {
                    rows.push(rowData);
                }
            });
            
            // Crear tabla en PDF
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 30,
                styles: {
                    fontSize: 10,
                    cellPadding: 3
                },
                headStyles: {
                    fillColor: [55, 65, 81], // gray-700
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [249, 250, 251] // gray-50
                }
            });
            
            doc.save(`${filename}.pdf`);
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
            
        } catch (error) {
            console.error('Error al exportar PDF:', error);
            alert('Error al generar el PDF: ' + error.message);
            
            // Restaurar botón en caso de error
            if (event && event.target) {
                event.target.innerHTML = '📄 PDF';
                event.target.disabled = false;
            }
        }
    }
    
    // Función para exportar tabla a Excel
    static async exportToExcel(tableId, filename = 'documento') {
        try {
            // Mostrar indicador de carga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '⏳ Generando...';
            button.disabled = true;
            
            // Cargar SheetJS desde CDN si no está disponible
            if (typeof XLSX === 'undefined') {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js');
            }
            
            const table = document.getElementById(tableId);
            if (!table) {
                throw new Error('Tabla no encontrada');
            }
            
            // Clonar tabla para limpiarla
            const clonedTable = table.cloneNode(true);
            
            // Remover columnas de acciones
            const headerCells = clonedTable.querySelectorAll('thead tr th');
            const actionColumnIndex = Array.from(headerCells).findIndex(th => 
                th.textContent.trim() === 'Acciones'
            );
            
            if (actionColumnIndex !== -1) {
                // Remover header de acciones
                headerCells[actionColumnIndex].remove();
                
                // Remover celdas de acciones en cada fila
                const rows = clonedTable.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells[actionColumnIndex]) {
                        cells[actionColumnIndex].remove();
                    }
                });
            }
            
            // Convertir tabla a workbook
            const wb = XLSX.utils.table_to_book(clonedTable, { sheet: "Datos" });
            
            // Descargar archivo
            XLSX.writeFile(wb, `${filename}.xlsx`);
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
            
        } catch (error) {
            console.error('Error al exportar Excel:', error);
            alert('Error al generar el archivo Excel: ' + error.message);
            
            // Restaurar botón en caso de error
            if (event && event.target) {
                event.target.innerHTML = '📊 Excel';
                event.target.disabled = false;
            }
        }
    }
    
    // Función para exportar gráfico de red a PDF
    static async exportNetworkToPDF(networkContainer, filename = 'mapa-actores') {
        try {
            // Mostrar indicador de carga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '⏳ Generando...';
            button.disabled = true;
            
            if (typeof window.jsPDF === 'undefined') {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
            }
            
            // Cargar html2canvas para capturar el canvas
            if (typeof html2canvas === 'undefined') {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
            }
            
            const { jsPDF } = window.jspdf;
            
            // Capturar el contenedor de la red
            const canvas = await html2canvas(networkContainer, {
                backgroundColor: '#374151', // gray-700
                scale: 2,
                useCORS: true,
                allowTaint: true
            });
            
            const imgData = canvas.toDataURL('image/png');
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape
            
            // Calcular dimensiones
            const imgWidth = 280;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar título
            doc.setFontSize(16);
            doc.text(filename.replace('-', ' ').toUpperCase(), 20, 20);
            
            // Agregar imagen
            doc.addImage(imgData, 'PNG', 10, 30, imgWidth, Math.min(imgHeight, 180));
            
            doc.save(`${filename}.pdf`);
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
            
        } catch (error) {
            console.error('Error al exportar red a PDF:', error);
            alert('Error al generar el PDF del mapa: ' + error.message);
            
            // Restaurar botón en caso de error
            if (event && event.target) {
                event.target.innerHTML = '📄 Exportar PDF';
                event.target.disabled = false;
            }
        }
    }
    
    // Función para exportar gráfico de red a PNG
    static async exportNetworkToPNG(networkContainer, filename = 'mapa-actores') {
        try {
            // Mostrar indicador de carga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '⏳ Generando...';
            button.disabled = true;
            
            // Cargar html2canvas si no está disponible
            if (typeof html2canvas === 'undefined') {
                await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
            }
            
            // Capturar el contenedor de la red
            const canvas = await html2canvas(networkContainer, {
                backgroundColor: '#374151', // gray-700
                scale: 2,
                useCORS: true,
                allowTaint: true
            });
            
            // Crear enlace de descarga
            const link = document.createElement('a');
            link.download = `${filename}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
            
        } catch (error) {
            console.error('Error al exportar red a PNG:', error);
            alert('Error al generar la imagen PNG: ' + error.message);
            
            // Restaurar botón en caso de error
            if (event && event.target) {
                event.target.innerHTML = '🖼️ Exportar PNG';
                event.target.disabled = false;
            }
        }
    }
    
    // Función auxiliar para cargar scripts dinámicamente
    static loadScript(src) {
        return new Promise((resolve, reject) => {
            // Verificar si el script ya está cargado
            const existingScript = document.querySelector(`script[src="${src}"]`);
            if (existingScript) {
                resolve();
                return;
            }
            
            const script = document.createElement('script');
            script.src = src;
            script.onload = () => {
                console.log(`Script cargado: ${src}`);
                resolve();
            };
            script.onerror = (error) => {
                console.error(`Error cargando script: ${src}`, error);
                reject(new Error(`Failed to load script: ${src}`));
            };
            document.head.appendChild(script);
        });
    }
}

// Hacer disponible globalmente
window.ExportUtils = ExportUtils;
