document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formModificarProductoCarta');
    const resultado = document.getElementById('resultado');
    const imgUrlInput = document.getElementById('img_url');
    const previewDiv = document.getElementById('preview-imagen');
    const enlaceVolver = document.getElementById('enlaceVolver');
    
    // Cargar datos desde URL params
    cargarDatosDesdeParams();
    
    // Función para cargar datos desde parámetros de URL
    function cargarDatosDesdeParams() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Obtener valores de los parámetros
        const id = urlParams.get('id');
        const id_categoria = urlParams.get('id_categoria');
        const nombre = urlParams.get('nombre');
        const descripcion = urlParams.get('descripcion');
        const precio = urlParams.get('precio');
        const img_url = urlParams.get('img_url');
        
        // Llenar los campos del formulario
        if (id) document.getElementById('id').value = id;
        if (id_categoria) {
            document.getElementById('id_categoria').value = id_categoria;
            // Actualizar enlace para volver con el id_categoria
            enlaceVolver.href = `visualizar-productos-carta.html?id_categoria=${id_categoria}`;
        }
        if (nombre) document.getElementById('nombre').value = nombre;
        if (descripcion) document.getElementById('descripcion').value = descripcion;
        if (precio) document.getElementById('precio').value = precio;
        if (img_url) {
            document.getElementById('img_url').value = img_url;
            mostrarPreviewImagen(img_url);
        }
    }
    
    // Función para mostrar previsualización de imagen
    function mostrarPreviewImagen(url) {
        // Crear contenedor base si no existe
        if (!previewDiv.querySelector('.preview-container')) {
            previewDiv.innerHTML = `
                <p><strong>Previsualización:</strong></p>
                <div class="preview-container" style="
                    width: 200px;
                    height: 150px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: #f5f5f5;
                    color: #999;
                    font-size: 14px;
                    text-align: center;
                    overflow: hidden;
                ">
                    <span class="placeholder-text">Sin imagen</span>
                </div>
            `;
        }
        
        const container = previewDiv.querySelector('.preview-container');
        
        if (!url || url.trim() === '') {
            // Mostrar placeholder cuando no hay URL
            container.style.backgroundColor = '#f5f5f5';
            container.innerHTML = '<span class="placeholder-text" style="color: #999; font-size: 14px;">Sin imagen</span>';
            return;
        }
        
        // Mostrar estado de carga
        container.style.backgroundColor = '#f9f9f9';
        container.innerHTML = '<span style="color: #666; font-size: 12px;">Cargando...</span>';
        
        // Crear elemento de imagen
        const img = document.createElement('img');
        img.style.maxWidth = '100%';
        img.style.maxHeight = '100%';
        img.style.objectFit = 'contain';
        
        // Manejar carga exitosa
        img.onload = function() {
            container.style.backgroundColor = 'transparent';
            container.innerHTML = '';
            container.appendChild(img);
        };
        
        // Manejar error de carga
        img.onerror = function() {
            container.style.backgroundColor = '#ffe6e6';
            container.innerHTML = '<span style="color: #d32f2f; font-size: 12px; padding: 10px;">Error al cargar imagen</span>';
        };
        
        img.src = url;
    }
    
    // Escuchar cambios en el input de URL de imagen
    imgUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        mostrarPreviewImagen(url);
    });
    
    // Escuchar cuando se pierde el foco (blur) para validar URL completa
    imgUrlInput.addEventListener('blur', function() {
        const url = this.value.trim();
        if (url) {
            mostrarPreviewImagen(url);
        }
    });
    
    // Escuchar cambios en el select de categoría para actualizar el enlace de volver
    document.getElementById('id_categoria').addEventListener('change', function() {
        const id_categoria = this.value;
        if (id_categoria) {
            enlaceVolver.href = `visualizar-productos-carta.html?id_categoria=${id_categoria}`;
        } else {
            enlaceVolver.href = 'visualizar-productos-carta.html';
        }
    });
    
    // Función para limpiar formulario
    window.limpiarFormulario = function() {
        if (confirm('¿Estás seguro de que deseas limpiar todos los campos?')) {
            form.reset();
            mostrarPreviewImagen('');
            resultado.style.display = 'none';
        }
    };
    
    // Manejar envío del formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener los datos del formulario
        const id = document.getElementById('id').value;
        const id_categoria = document.getElementById('id_categoria').value;
        const nombre = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion').value.trim();
        const precio = document.getElementById('precio').value;
        const img_url = document.getElementById('img_url').value;
        
        // Crear el XML body
        let xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
<producto>
    <id>${id}</id>
    <id_categoria>${id_categoria}</id_categoria>
    <nombre>${nombre}</nombre>`;
        
        // Solo incluir descripción si no está vacía
        if (descripcion) {
            xmlBody += `
    <descripcion>${descripcion}</descripcion>`;
        }
        
        xmlBody += `
    <precio>${precio}</precio>
    <img_url>${img_url}</img_url>
</producto>`;
        
        try {
            // Mostrar estado de carga
            resultado.style.display = 'block';
            resultado.innerHTML = '<p>Enviando modificaciones...</p>';
            
            console.info('XML Body:', xmlBody);

            // Realizar la petición PUT
            const response = await fetch('https://vmonge.me/api/comedores/producto_carta/modificar', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/xml',
                },
                body: xmlBody
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
                alert('La modificación se ha guardado correctamente');
                // Redireccionar a la vista de productos con la categoría actual seleccionada
                window.location.href = `visualizar-productos-carta.html?id_categoria=${id_categoria}`;
            } else {
                alert('Error al modificar el producto');
                console.log('Error en la respuesta del servidor:', { estado, mensaje, xmlText });
                resultado.innerHTML = `
                    <h3 style="color: var(--error-color);">Error</h3>
                    <p>No se pudo modificar el producto</p>
                    <details>
                        <summary>Ver respuesta del servidor</summary>
                        <pre>${xmlText}</pre>
                    </details>
                `;
            }
            
        } catch (error) {
            console.log('Error al modificar producto:', error);
            alert('Error al modificar el producto');
            resultado.innerHTML = `
                <h3 style="color: var(--error-color);">Error</h3>
                <p>No se pudo modificar el producto: ${error.message}</p>
            `;
        }
    });
}); 