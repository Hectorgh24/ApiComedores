document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formInfoNutricional');
    const resultado = document.getElementById('resultado');
    
    // Obtener el ID desde los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const idFromURL = urlParams.get('id');
    
    // Si hay un ID en la URL, completar automáticamente el campo
    if (idFromURL) {
        const idInput = document.getElementById('id_desayuno_comida');
        idInput.value = idFromURL;
        // Opcionalmente, destacar el campo para indicar que ha sido autocompletado
        idInput.style.backgroundColor = '#e8f5e9';

    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener los datos del formulario
        const id_desayuno_comida = document.getElementById('id_desayuno_comida').value;
        const kcal = document.getElementById('kcal').value;
        const hc = document.getElementById('hc').value;
        const p = document.getElementById('p').value;
        const l = document.getElementById('l').value;
        
        // Crear el XML body
        const xmlBody = `<?xml version="1.0" encoding="UTF-8"?>
<informacion_nutrimental>
    <id_desayuno_comida>${id_desayuno_comida}</id_desayuno_comida>
    <kcal>${kcal}</kcal>
    <hc>${hc}</hc>
    <p>${p}</p>
    <l>${l}</l>
</informacion_nutrimental>`;
        
        try {
            // Mostrar estado de carga
            resultado.style.display = 'block';
            resultado.innerHTML = '<p>Enviando datos...</p>';
            
            // Realizar la petición POST
            const response = await fetch('https://vmonge.me/api/comedores/informacionNutrimental/crear', {
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
                    <p>Información nutricional creada correctamente</p>
                    <details>
                        <summary>Ver respuesta del servidor</summary>
                        <pre>${responseText}</pre>
                    </details>
                `;
                form.reset();
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        } catch (error) {
            resultado.innerHTML = `
                <h3 style="color: var(--error-color);">Error</h3>
                <p>No se pudo crear la información nutricional: ${error.message}</p>
            `;
        }
    });
}); 