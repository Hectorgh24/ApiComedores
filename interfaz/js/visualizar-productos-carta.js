// Variables globales
let todosLosProductos = [];
let categorias = [];

// Elementos del DOM
const loadingContainer = document.getElementById('loadingContainer');
const errorContainer = document.getElementById('errorContainer');
const errorMessage = document.getElementById('errorMessage');
const categoriasContainer = document.getElementById('categoriasContainer');
const categoriasButtons = document.getElementById('categoriasButtons');
const productosContainer = document.getElementById('productosContainer');

// Al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
});

// Función para cargar todos los productos
async function cargarProductos() {
    try {
        showLoading(true);
        
        const response = await fetch('https://vmonge.me/api/comedores/producto_carta/obtenerTodos');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const xmlText = await response.text();
        
        // Parsear XML
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, 'text/xml');
        
        // Verificar si hay errores en el XML
        const parseError = xmlDoc.getElementsByTagName('parsererror');
        if (parseError.length > 0) {
            throw new Error('Error al parsear la respuesta XML');
        }
        
        // Obtener productos
        const productosXML = xmlDoc.getElementsByTagName('producto');
        
        if (productosXML.length === 0) {
            console.log('No se obtuvieron datos del servidor');
            alert('No hay productos disponibles en este momento');
            showLoading(false);
            return;
        }
        
        // Convertir XML a array de objetos
        todosLosProductos = [];
        for (let i = 0; i < productosXML.length; i++) {
            const producto = productosXML[i];
            
            todosLosProductos.push({
                id: producto.getElementsByTagName('id')[0]?.textContent || '',
                id_categoria: producto.getElementsByTagName('id_categoria')[0]?.textContent || '',
                nombre: producto.getElementsByTagName('nombre')[0]?.textContent || '',
                descripcion: producto.getElementsByTagName('descripcion')[0]?.textContent || null,
                precio: producto.getElementsByTagName('precio')[0]?.textContent || '0.00',
                img_url: producto.getElementsByTagName('img_url')[0]?.textContent || ''
            });
        }
        
        // Extraer categorías únicas
        extraerCategorias();
        
        // Mostrar interfaz
        showLoading(false);
        categoriasContainer.style.display = 'block';
        
        console.log('Productos cargados exitosamente:', todosLosProductos.length);
        
    } catch (error) {
        console.log('Error al cargar productos:', error);
        alert('Hubo un error al cargar la información. Por favor, intenta de nuevo más tarde.');
        showError('Error al cargar los productos: ' + error.message);
        showLoading(false);
    }
}

// Función para extraer categorías únicas de los productos
function extraerCategorias() {
    const categoriasUnicas = new Set();
    
    todosLosProductos.forEach(producto => {
        if (producto.id_categoria) {
            categoriasUnicas.add(producto.id_categoria);
        }
    });
    
    categorias = Array.from(categoriasUnicas).sort((a, b) => parseInt(a) - parseInt(b));
    
    // Crear botones de categorías
    crearBotonesCategorias();
}

// Función para crear los botones de categorías
function crearBotonesCategorias() {
    categoriasButtons.innerHTML = '';
    
    categorias.forEach(categoriaId => {
        const button = document.createElement('button');
        button.className = 'btn btn-secondary';
        button.textContent = `Categoría ${categoriaId}`;
        button.dataset.categoriaId = categoriaId;
        
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            document.querySelectorAll('.toggle-buttons .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Agregar clase active al botón seleccionado
            this.classList.add('active');
            
            // Filtrar y mostrar productos
            mostrarProductosPorCategoria(categoriaId);
        });
        
        categoriasButtons.appendChild(button);
    });
}

// Función para filtrar y mostrar productos por categoría
function mostrarProductosPorCategoria(categoriaId) {
    // Limpiar contenedor de productos
    productosContainer.innerHTML = '';
    
    // Filtrar productos por categoría
    const productosFiltrados = todosLosProductos.filter(producto => 
        producto.id_categoria === categoriaId
    );
    
    if (productosFiltrados.length === 0) {
        productosContainer.innerHTML = `
            <div class="alert alert-info">
                <p>No hay productos disponibles en esta categoría.</p>
            </div>
        `;
        return;
    }
    
    // Crear cards de productos
    const productosHTML = productosFiltrados.map(producto => `
        <div class="card producto-card">
            <div class="producto-header">
                <h3>${producto.nombre}</h3>
                <span class="producto-precio">$${parseFloat(producto.precio).toFixed(2)}</span>
            </div>
            
            ${producto.img_url ? `
                <div class="producto-imagen">
                    <img src="${producto.img_url}" alt="${producto.nombre}" 
                         onerror="this.style.display='none'" />
                </div>
            ` : ''}
            
            ${producto.descripcion && producto.descripcion.trim() ? `
                <div class="producto-descripcion">
                    <p>${producto.descripcion}</p>
                </div>
            ` : ''}
            
            <div class="producto-info">
                <small>ID: ${producto.id} | Categoría: ${producto.id_categoria}</small>
            </div>
            
            <div class="producto-acciones">
                <button class="btn btn-secondary" onclick="modificarProducto(${producto.id}, ${producto.id_categoria}, '${producto.nombre.replace(/'/g, '\\\'').replace(/"/g, '\\"')}', '${producto.descripcion ? producto.descripcion.replace(/'/g, '\\\'').replace(/"/g, '\\"') : ''}', ${producto.precio}, '${producto.img_url}')">
                    Modificar
                </button>
                <button class="btn btn-danger" onclick="eliminarProducto(${producto.id})">
                    Eliminar
                </button>
            </div>
        </div>
    `).join('');
    
    productosContainer.innerHTML = `
        <h4>Productos de la Categoría ${categoriaId} (${productosFiltrados.length} productos)</h4>
        <div class="productos-grid">
            ${productosHTML}
        </div>
    `;
}

// Función para mostrar/ocultar loading
function showLoading(show) {
    loadingContainer.style.display = show ? 'block' : 'none';
}

// Función para mostrar errores
function showError(message) {
    errorMessage.textContent = message;
    errorContainer.style.display = 'block';
}

// Función para ocultar errores
function hideError() {
    errorContainer.style.display = 'none';
}

// Función para eliminar producto
async function eliminarProducto(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
        return;
    }
    
    // Guardar la categoría actualmente seleccionada
    const categoriaActiva = document.querySelector('.toggle-buttons .btn.active')?.dataset.categoriaId;
    
    try {
        const response = await fetch(`https://vmonge.me/api/comedores/producto_carta/eliminar/${id}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const xmlText = await response.text();
        
        // Parsear XML
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, 'text/xml');
        
        // Verificar si hay errores en el XML
        const parseError = xmlDoc.getElementsByTagName('parsererror');
        if (parseError.length > 0) {
            throw new Error('Error al parsear la respuesta XML');
        }
        
        // Obtener estado y mensaje
        const estado = xmlDoc.getElementsByTagName('estado')[0]?.textContent || '';
        const mensaje = xmlDoc.getElementsByTagName('mensaje')[0]?.textContent || '';
        
        if (estado === 'true') {
            alert(mensaje);
            // Recargar productos y mantener la categoría seleccionada
            await cargarProductos();
            
            // Reseleccionar la categoría que estaba activa
            if (categoriaActiva) {
                const botonCategoria = document.querySelector(`[data-categoria-id="${categoriaActiva}"]`);
                if (botonCategoria) {
                    botonCategoria.click();
                }
            }
        } else {
            alert('Hubo un problema al eliminar el producto');
            console.log('Error en la respuesta del servidor:', { estado, mensaje });
        }
        
    } catch (error) {
        console.log('Error al eliminar producto:', error);
        alert('Hubo un problema al eliminar el producto');
    }
}

// Función para modificar producto (redirecciona a la vista de modificación)
function modificarProducto(id, id_categoria, nombre, descripcion, precio, img_url) {
    // Crear URL con parámetros
    const params = new URLSearchParams({
        id: String(id),
        id_categoria: String(id_categoria),
        nombre: String(nombre),
        descripcion: String(descripcion || ''),
        precio: String(precio),
        img_url: String(img_url)
    });
    
    // Redireccionar a la vista de modificación
    window.location.href = `modificar-producto-carta.html?${params.toString()}`;
} 