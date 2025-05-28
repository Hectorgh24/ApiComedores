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
        // Limpiar preview anterior
        previewDiv.innerHTML = '';
        
        if (!url) return;
        
        // Crear elemento de imagen
        const img = document.createElement('img');
        img.style.maxWidth = '200px';
        img.style.maxHeight = '150px';
        img.style.border = '1px solid #ccc';
        img.style.borderRadius = '4px';
        
        // Manejar carga exitosa
        img.onload = function() {
            previewDiv.innerHTML = '<p><strong>Previsualización:</strong></p>';
            previewDiv.appendChild(img);
        };
        
        // Manejar error de carga
        img.onerror = function() {
            previewDiv.innerHTML = '<p style="color: red;">Error: No se pudo cargar la imagen</p>';
        };
        
        img.src = url;
    }
    
    // Escuchar cambios en el input de URL de imagen
    imgUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            mostrarPreviewImagen(url);
        } else {
            previewDiv.innerHTML = '';
        }
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
            previewDiv.innerHTML = '';
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
        const descripcion = document.getElementById('descripcion').value;
        const precio = document.getElementById('precio').value;
        const img_url = document.getElementById('img_url').value;
        
        // Crear el XML body
        const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
<producto>
    <id>${id}</id>
    <id_categoria>${id_categoria}</id_categoria>
    <nombre>${nombre}</nombre>
    <descripcion>${descripcion}</descripcion>
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