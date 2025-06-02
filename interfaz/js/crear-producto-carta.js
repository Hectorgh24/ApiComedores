document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formProductoCarta');
    const resultado = document.getElementById('resultado');
    const imgUrlInput = document.getElementById('img_url');
    const previewDiv = document.getElementById('preview-imagen');
    
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
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener los datos del formulario
        const id_categoria = document.getElementById('id_categoria').value;
        const nombre = document.getElementById('nombre').value;
        const descripcion = document.getElementById('descripcion').value;
        const precio = document.getElementById('precio').value;
        const img_url = document.getElementById('img_url').value;
        
        // Crear el XML body
        const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
<producto>
    <id_categoria>${id_categoria}</id_categoria>
    <nombre>${nombre}</nombre>
    <descripcion>${descripcion}</descripcion>
    <precio>${precio}</precio>
    <img_url>${img_url}</img_url>
</producto>`;
        
        try {
            // Mostrar estado de carga
            resultado.style.display = 'block';
            resultado.innerHTML = '<p>Enviando datos...</p>';
            
            console.info('XML Body:', xmlBody);

            // Realizar la petición POST
            const response = await fetch('https://vmonge.me/api/comedores/producto_carta/crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/xml',
                },
                body: xmlBody
            });
            
            if (response.ok) {
                const responseText = await response.text();
                resultado.innerHTML = `
                    <h3 style="color: var(--success-color);">¡Éxito!</h3>
                    <p>Producto de carta creado correctamente</p>
                    <details>
                        <summary>Ver respuesta del servidor</summary>
                        <pre>${responseText}</pre>
                    </details>
                `;
                form.reset();
                previewDiv.innerHTML = '';
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        } catch (error) {
            resultado.innerHTML = `
                <h3 style="color: var(--error-color);">Error</h3>
                <p>No se pudo crear el producto: ${error.message}</p>
            `;
        }
    });
}); 