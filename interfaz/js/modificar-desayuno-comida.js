document.addEventListener('DOMContentLoaded', function() {
    const formModificarDesayunoComida = document.getElementById('formModificarDesayunoComida');
    const imgUrlInput = document.getElementById('img_url');
    const previewImagen = document.getElementById('preview-imagen');
    
    // Función para obtener parámetros de la URL
    function obtenerParametrosURL() {
        const parametros = new URLSearchParams(window.location.search);
        return {
            id: parametros.get('id'),
            tipo: parametros.get('tipo'),
            fecha: parametros.get('fecha'),
            descripcion: parametros.get('descripcion'),
            img_url: parametros.get('img_url')
        };
    }
    
    // Cargar datos desde la URL si existen
    function cargarDatosDesdeURL() {
        const parametros = obtenerParametrosURL();
        
        if (parametros.id) {
            document.getElementById('id').value = parametros.id;
            
            if (parametros.tipo) {
                document.getElementById('tipo').value = parametros.tipo;
            }
            
            if (parametros.fecha) {
                document.getElementById('fecha').value = parametros.fecha;
            }
            
            if (parametros.descripcion) {
                document.getElementById('descripcion').value = parametros.descripcion;
            }
            
            if (parametros.img_url) {
                document.getElementById('img_url').value = parametros.img_url;
                actualizarPreviewImagen(parametros.img_url);
            }
        }
    }
    
    // Función para limpiar el formulario
    function limpiarFormulario() {
        formModificarDesayunoComida.reset();
        actualizarPreviewImagen('');
    }
    
    // Función para actualizar la vista previa de la imagen
    function actualizarPreviewImagen(url) {
        if (url) {
            previewImagen.innerHTML = `<img src="${url}" alt="Vista previa" style="max-width: 100%; max-height: 200px;">`;
            previewImagen.style.display = 'block';
        } else {
            previewImagen.innerHTML = '';
            previewImagen.style.display = 'none';
        }
    }
    
    // Escuchar cambios en el campo de URL de imagen
    imgUrlInput.addEventListener('input', function() {
        actualizarPreviewImagen(this.value);
    });
    
    // Manejar el envío del formulario
    formModificarDesayunoComida.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('id').value;
        const tipo = document.getElementById('tipo').value;
        const fecha = document.getElementById('fecha').value;
        const descripcion = document.getElementById('descripcion').value;
        const imgUrl = document.getElementById('img_url').value;
        
        // Crear XML para enviar
        const xmlData = `
            <desayuno_comida>
                <id>${id}</id>
                <tipo>${tipo}</tipo>
                <fecha>${fecha}</fecha>
                <descripcion>${descripcion}</descripcion>
                <img_url>${imgUrl}</img_url>
            </desayuno_comida>
        `;
        
        // Realizar la petición PUT
        fetch('https://vmonge.me/api/comedores/desayunos_comidas/modificar', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/xml'
            },
            body: xmlData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text();
        })
        .then(data => {
            // Parsear la respuesta XML
            const parser = new DOMParser();
            const xmlResponse = parser.parseFromString(data, 'application/xml');
            
            const estado = xmlResponse.querySelector('estado').textContent === 'true';
            const mensaje = xmlResponse.querySelector('mensaje').textContent;
            
            // Mostrar resultado en alert
            alert(estado ? `Éxito: ${mensaje}` : `Error: ${mensaje}`);
            
            // Limpiar formulario si fue exitoso
            if (estado) {
                limpiarFormulario();
                // Redirigir a la página de desayunos_comidas
                window.location.href = 'visualizar-platillos.html';
            }

            
            
            if (!estado) {
                console.error('Error al modificar:', mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: Ocurrió un error al procesar la solicitud.');
        });
    });
    
    // Cargar datos cuando se inicia la página
    cargarDatosDesdeURL();
}); 