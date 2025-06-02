document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formDesayunoComida');
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
    
    // Función para crear ventana modal
    function crearVentanaModal(id) {
        // Crear el fondo oscuro
        const modalOverlay = document.createElement('div');
        modalOverlay.style.position = 'fixed';
        modalOverlay.style.top = '0';
        modalOverlay.style.left = '0';
        modalOverlay.style.width = '100%';
        modalOverlay.style.height = '100%';
        modalOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modalOverlay.style.display = 'flex';
        modalOverlay.style.justifyContent = 'center';
        modalOverlay.style.alignItems = 'center';
        modalOverlay.style.zIndex = '1000';
        
        // Crear la ventana modal
        const modalWindow = document.createElement('div');
        modalWindow.style.backgroundColor = 'white';
        modalWindow.style.borderRadius = '8px';
        modalWindow.style.padding = '20px';
        modalWindow.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
        modalWindow.style.maxWidth = '400px';
        modalWindow.style.width = '90%';
        
        // Contenido de la modal
        modalWindow.innerHTML = `
            <h3 style="margin-top: 0; color: var(--primary-color);">Información Nutricional</h3>
            <p>El desayuno/comida se ha guardado correctamente. ¿Desea agregar la información nutricional ahora?</p>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button id="btnModalCancelar" class="btn">No, continuar aquí</button>
                <button id="btnModalRedirigir" class="btn btn-primary">Sí, agregar información</button>
            </div>
        `;
        
        modalOverlay.appendChild(modalWindow);
        document.body.appendChild(modalOverlay);
        
        // Agregar event listeners para los botones
        document.getElementById('btnModalRedirigir').addEventListener('click', function() {
            // Redirigir a la página de información nutricional con el ID como parámetro
            window.location.href = `crear-info-nutricional.html?id=${id}`;
        });
        
        document.getElementById('btnModalCancelar').addEventListener('click', function() {
            // Cerrar la modal
            document.body.removeChild(modalOverlay);
            // Resetear el formulario
            form.reset();
            previewDiv.innerHTML = '';
        });
    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener los datos del formulario
        const tipo = document.getElementById('tipo').value;
        const fecha = document.getElementById('fecha').value;
        const descripcion = document.getElementById('descripcion').value;
        const img_url = document.getElementById('img_url').value;
        
        // Crear el XML body
        const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
<desayuno_comida>
    <tipo>${tipo}</tipo>
    <fecha>${fecha}</fecha>
    <descripcion>${descripcion}</descripcion>
    <img_url>${img_url}</img_url>
</desayuno_comida>`;
        
        try {
            // Mostrar estado de carga
            resultado.style.display = 'block';
            resultado.innerHTML = '<p>Enviando datos...</p>';

            console.info('XML Body:', xmlBody);
            
            // Realizar la petición POST
            const response = await fetch('https://vmonge.me/api/comedores/desayunos_comidas/crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/xml',
                },
                body: xmlBody
            });
            
            if (response.ok) {
                const responseText = await response.text();
                
                // Extraer el ID del XML de respuesta
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(responseText, "text/xml");
                const idElement = xmlDoc.getElementsByTagName("id")[0];
                const id = idElement ? idElement.textContent : null;
                
                resultado.innerHTML = `
                    <h3 style="color: var(--success-color);">¡Éxito!</h3>
                    <p>Desayuno/Comida creado correctamente</p>
                    <details>
                        <summary>Ver respuesta del servidor</summary>
                        <pre>${responseText}</pre>
                    </details>
                `;
                
                if (id) {
                    // Mostrar ventana modal de confirmación
                    crearVentanaModal(id);
                } else {
                    form.reset();
                    previewDiv.innerHTML = '';
                }
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        } catch (error) {
            resultado.innerHTML = `
                <h3 style="color: var(--error-color);">Error</h3>
                <p>No se pudo crear el desayuno/comida: ${error.message}</p>
            `;
        }
    });
}); 