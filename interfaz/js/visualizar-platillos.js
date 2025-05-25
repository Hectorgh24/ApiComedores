class VisualizadorPlatillos {
    constructor() {
        this.endpoints = {
            desayunos: 'https://vmonge.me/api/comedores/desayunos/obtenerDesayunos',
            comidas: 'https://vmonge.me/api/comedores/comidas/obtenerComidas'
        };
        
        this.tipoActual = 'desayunos';
        this.platillos = [];
        this.filtroActivo = 'fecha'; // 'fecha' o 'id'
        this.ordenFecha = 'desc'; // Descendente por defecto
        this.ordenId = 'asc'; // Ascendente por defecto
        
        this.initEventListeners();
        this.cargarPlatillos('desayunos');
    }
    
    initEventListeners() {
        const btnDesayunos = document.getElementById('btnDesayunos');
        const btnComidas = document.getElementById('btnComidas');
        
        btnDesayunos.addEventListener('click', () => {
            this.cambiarTipo('desayunos');
        });
        
        btnComidas.addEventListener('click', () => {
            this.cambiarTipo('comidas');
        });

        // Eventos para los filtros de fecha
        const btnFechaAsc = document.getElementById('btnFechaAsc');
        const btnFechaDesc = document.getElementById('btnFechaDesc');
        
        btnFechaAsc.addEventListener('click', () => {
            this.cambiarFiltroActivo('fecha');
            this.cambiarOrdenFecha('asc');
        });
        
        btnFechaDesc.addEventListener('click', () => {
            this.cambiarFiltroActivo('fecha');
            this.cambiarOrdenFecha('desc');
        });
        
        // Eventos para los filtros de ID
        const btnIdAsc = document.getElementById('btnIdAsc');
        const btnIdDesc = document.getElementById('btnIdDesc');
        
        btnIdAsc.addEventListener('click', () => {
            this.cambiarFiltroActivo('id');
            this.cambiarOrdenId('asc');
        });
        
        btnIdDesc.addEventListener('click', () => {
            this.cambiarFiltroActivo('id');
            this.cambiarOrdenId('desc');
        });
    }
    
    cambiarTipo(tipo) {
        if (tipo === this.tipoActual) return;
        
        this.tipoActual = tipo;
        this.actualizarBotones();
        this.cargarPlatillos(tipo);
    }
    
    cambiarFiltroActivo(filtro) {
        if (filtro === this.filtroActivo) return;
        
        this.filtroActivo = filtro;
        this.actualizarEstadoFiltros();
    }
    
    cambiarOrdenFecha(orden) {
        if (orden === this.ordenFecha) return;
        
        this.ordenFecha = orden;
        this.actualizarBotonesFiltro('Fecha', orden);
        this.ordenarYMostrarPlatillos();
    }
    
    cambiarOrdenId(orden) {
        if (orden === this.ordenId) return;
        
        this.ordenId = orden;
        this.actualizarBotonesFiltro('Id', orden);
        this.ordenarYMostrarPlatillos();
    }
    
    actualizarEstadoFiltros() {
        // Actualizar visualmente qué grupo de filtros está activo
        const grupoFecha = document.querySelector('.filtros-grupos .filtro-grupo:nth-child(1)');
        const grupoId = document.querySelector('.filtros-grupos .filtro-grupo:nth-child(2)');
        
        if (this.filtroActivo === 'fecha') {
            grupoFecha.classList.add('filtro-activo');
            grupoId.classList.remove('filtro-activo');
        } else {
            grupoFecha.classList.remove('filtro-activo');
            grupoId.classList.add('filtro-activo');
        }
        
        this.ordenarYMostrarPlatillos();
    }
    
    actualizarBotones() {
        const btnDesayunos = document.getElementById('btnDesayunos');
        const btnComidas = document.getElementById('btnComidas');
        
        if (this.tipoActual === 'desayunos') {
            btnDesayunos.className = 'btn btn-primary active';
            btnComidas.className = 'btn btn-secondary';
        } else {
            btnDesayunos.className = 'btn btn-secondary';
            btnComidas.className = 'btn btn-primary active';
        }
    }
    
    actualizarBotonesFiltro(tipo, orden) {
        const btnAsc = document.getElementById(`btn${tipo}Asc`);
        const btnDesc = document.getElementById(`btn${tipo}Desc`);
        
        if (orden === 'asc') {
            btnAsc.classList.add('active');
            btnDesc.classList.remove('active');
        } else {
            btnAsc.classList.remove('active');
            btnDesc.classList.add('active');
        }
    }
    
    async cargarPlatillos(tipo) {
        this.mostrarLoading(true);
        this.ocultarError();
        
        try {
            const response = await fetch(this.endpoints[tipo]);
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const xmlText = await response.text();
            this.platillos = this.parseXML(xmlText, tipo);
            this.ordenarYMostrarPlatillos();
            
        } catch (error) {
            console.error('Error al cargar platillos:', error);
            this.mostrarError(`Error al cargar ${tipo}: ${error.message}`);
        } finally {
            this.mostrarLoading(false);
        }
    }
    
    ordenarYMostrarPlatillos() {
        // Ordenar según el filtro activo
        if (this.filtroActivo === 'fecha') {
            // Ordenar por fecha
            if (this.ordenFecha === 'asc') {
                this.platillos.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
            } else {
                this.platillos.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
            }
        } else {
            // Ordenar por ID
            if (this.ordenId === 'asc') {
                this.platillos.sort((a, b) => parseInt(a.id) - parseInt(b.id));
            } else {
                this.platillos.sort((a, b) => parseInt(b.id) - parseInt(a.id));
            }
        }
        
        this.mostrarPlatillos();
    }
    
    parseXML(xmlText, tipo) {
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, 'text/xml');
        
        // Verificar si hay errores de parsing
        const parserError = xmlDoc.querySelector('parsererror');
        if (parserError) {
            throw new Error('Error al parsear XML');
        }
        
        const elementName = tipo === 'desayunos' ? 'desayuno' : 'comida';
        const elementos = xmlDoc.querySelectorAll(elementName);
        
        const platillos = [];
        elementos.forEach(elemento => {
            const platillo = {
                id: elemento.querySelector('id')?.textContent || '',
                tipo: elemento.querySelector('tipo')?.textContent || '',
                fecha: elemento.querySelector('fecha')?.textContent || '',
                descripcion: elemento.querySelector('descripcion')?.textContent || '',
                img_url: elemento.querySelector('img_url')?.textContent || ''
            };
            platillos.push(platillo);
        });
        
        return platillos;
    }
    
    mostrarPlatillos() {
        const container = document.getElementById('platillosContainer');
        
        if (this.platillos.length === 0) {
            container.innerHTML = `
                <div class="alert alert-info">
                    <p>No se encontraron ${this.tipoActual} disponibles.</p>
                </div>
            `;
            return;
        }
        
        const platillosHTML = this.platillos.map(platillo => {
            return this.crearTarjetaPlatillo(platillo);
        }).join('');
        
        container.innerHTML = platillosHTML;
        
        // Lazy loading para imágenes
        this.setupLazyLoading();
        
        // Añadir event listeners para los botones de eliminar
        this.setupBotonesEliminar();
        
        // Añadir event listeners para los botones de modificar
        this.setupBotonesModificar();
    }
    
    crearTarjetaPlatillo(platillo) {
        const fechaFormateada = this.formatearFecha(platillo.fecha);
        const tipoCapitalizado = platillo.tipo.charAt(0).toUpperCase() + platillo.tipo.slice(1);
        
        return `
            <div class="card platillo-card" data-id="${platillo.id}" data-tipo="${platillo.tipo}">
                <div class="platillo-header">
                    <div class="platillo-info">
                        <h3>${tipoCapitalizado} #${platillo.id}</h3>
                        <p class="fecha">${fechaFormateada}</p>
                    </div>
                    <div class="platillo-tipo">
                        <span class="badge badge-${platillo.tipo}">${tipoCapitalizado}</span>
                    </div>
                </div>
                
                <div class="platillo-contenido">
                    <div class="platillo-imagen">
                        <img 
                            src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2YwZjBmMCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTRweCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhcmdhbmRvLi4uPC90ZXh0Pjwvc3ZnPg=="
                            data-src="${platillo.img_url}" 
                            alt="Imagen de ${platillo.tipo} #${platillo.id}"
                            class="lazy-image"
                            loading="lazy"
                        >
                    </div>
                    
                    <div class="platillo-descripcion">
                        <h4>Descripción:</h4>
                        <p>${platillo.descripcion}</p>
                    </div>
                </div>
                
                <div class="platillo-acciones">
                    <button class="btn btn-secondary btn-modificar" data-id="${platillo.id}" data-tipo="${platillo.tipo}">Modificar</button>
                    <button class="btn btn-error btn-eliminar" data-id="${platillo.id}" data-tipo="${platillo.tipo}">Eliminar</button>
                </div>
            </div>
        `;
    }
    
    setupBotonesEliminar() {
        const botonesEliminar = document.querySelectorAll('.btn-eliminar');
        
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', (e) => {
                const id = e.target.dataset.id;
                const tipo = e.target.dataset.tipo;
                this.eliminarPlatillo(id, tipo);
            });
        });
    }
    
    setupBotonesModificar() {
        const botonesModificar = document.querySelectorAll('.btn-modificar');
        
        botonesModificar.forEach(boton => {
            boton.addEventListener('click', (e) => {
                const id = e.target.dataset.id;
                const tipo = e.target.dataset.tipo;
                this.modificarPlatillo(id, tipo);
            });
        });
    }
    
    modificarPlatillo(id, tipo) {
        // Buscar el platillo en la lista
        const platillo = this.platillos.find(p => p.id === id && p.tipo === tipo);
        
        if (!platillo) {
            console.error(`No se encontró el platillo con ID ${id} y tipo ${tipo}`);
            return;
        }
        
        // Construir la URL con los parámetros
        const params = new URLSearchParams();
        params.append('id', platillo.id);
        params.append('tipo', platillo.tipo);
        params.append('fecha', platillo.fecha);
        params.append('descripcion', platillo.descripcion);
        params.append('img_url', platillo.img_url);
        
        // Redireccionar a la página de modificación con los parámetros
        window.location.href = `modificar-desayuno-comida.html?${params.toString()}`;
    }
    
    async eliminarPlatillo(id, tipo) {
        if (!confirm(`¿Está seguro que desea eliminar este ${tipo} #${id}?`)) {
            return;
        }
        
        try {
            // Construir la URL del endpoint de eliminación
            const endpoint = `https://vmonge.me/api/comedores/desayunos_comidas/eliminar/${id}`;
            
            const response = await fetch(endpoint, {
                method: 'DELETE'
            });
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const xmlText = await response.text();
            const resultado = this.parseRespuestaEliminar(xmlText);
            
            if (resultado.estado) {
                // Eliminación exitosa
                alert(resultado.mensaje);
                
                // Eliminar el platillo de la lista y actualizar la vista
                this.platillos = this.platillos.filter(p => !(p.id === id && p.tipo === tipo));
                this.mostrarPlatillos();
            } else {
                throw new Error(resultado.mensaje);
            }
            
        } catch (error) {
            console.error('Error al eliminar platillo:', error);
            alert('Hubo un error al eliminar el platillo');
        }
    }
    
    parseRespuestaEliminar(xmlText) {
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, 'text/xml');
        
        // Verificar si hay errores de parsing
        const parserError = xmlDoc.querySelector('parsererror');
        if (parserError) {
            return {
                estado: false,
                mensaje: 'Error al parsear la respuesta XML'
            };
        }
        
        return {
            estado: xmlDoc.querySelector('estado')?.textContent === 'true',
            mensaje: xmlDoc.querySelector('mensaje')?.textContent || 'Operación completada'
        };
    }
    
    formatearFecha(fechaStr) {
        try {
            const fecha = new Date(fechaStr);
            return fecha.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (error) {
            return fechaStr;
        }
    }
    
    setupLazyLoading() {
        const images = document.querySelectorAll('.lazy-image');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-image');
                    observer.unobserve(img);
                    
                    img.onerror = () => {
                        img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y1ZjVmNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTRweCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
                    };
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    mostrarLoading(mostrar) {
        const loadingContainer = document.getElementById('loadingContainer');
        const platillosContainer = document.getElementById('platillosContainer');
        
        if (mostrar) {
            loadingContainer.style.display = 'block';
            platillosContainer.style.display = 'none';
        } else {
            loadingContainer.style.display = 'none';
            platillosContainer.style.display = 'block';
        }
    }
    
    mostrarError(mensaje) {
        const errorContainer = document.getElementById('errorContainer');
        const errorMessage = document.getElementById('errorMessage');
        
        errorMessage.textContent = mensaje;
        errorContainer.style.display = 'block';
    }
    
    ocultarError() {
        const errorContainer = document.getElementById('errorContainer');
        errorContainer.style.display = 'none';
    }
}

// Inicializar la aplicación cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', () => {
    const visualizador = new VisualizadorPlatillos();
    
    // Inicializar el estado de los filtros
    visualizador.actualizarEstadoFiltros();
}); 