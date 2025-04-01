// Agregar campos de PDF
document.getElementById('agregar_pdf').addEventListener('click', function() {
    const numeroPDFs = document.getElementById('numero_pdfs').value;
    const pdfFieldsContainer = document.getElementById('pdf_fields');
    pdfFieldsContainer.innerHTML = ''; // Limpiar campos existentes

    for (let i = 0; i < numeroPDFs; i++) {
        const pdfField = document.createElement('div');
        pdfField.classList.add('mb-4');
        pdfField.innerHTML = `
            <label for="materiales_pdf_${i}" class="block text-gray-700 font-bold">Archivo PDF ${i + 1}</label>
            <input type="file" name="materiales_pdf[]" id="materiales_pdf_${i}" class="w-full p-2 border border-gray-300 rounded-md" accept=".pdf" required>
        `;
        pdfFieldsContainer.appendChild(pdfField);
    }
});

// Agregar campos de imágenes
document.getElementById('agregar_imagen').addEventListener('click', function() {
    const numeroImagenes = document.getElementById('numero_imagenes').value;
    const imagenFieldsContainer = document.getElementById('imagen_fields');
    imagenFieldsContainer.innerHTML = ''; // Limpiar campos existentes

    for (let i = 0; i < numeroImagenes; i++) {
        const imagenField = document.createElement('div');
        imagenField.classList.add('mb-4');
        imagenField.innerHTML = `
            <label for="imagen_${i}" class="block text-gray-700 font-bold">Imagen ${i + 1}</label>
            <input type="file" name="imagenes_curso[]" id="imagen_${i}" class="w-full p-2 border border-gray-300 rounded-md" accept="image/*" required>
        `;
        imagenFieldsContainer.appendChild(imagenField);
    }
});

// Agregar campos de videos
document.getElementById('agregar_video').addEventListener('click', function() {
    const numeroVideos = document.getElementById('numero_videos').value;
    const videoFieldsContainer = document.getElementById('video_fields');
    videoFieldsContainer.innerHTML = ''; // Limpiar campos existentes

    for (let i = 0; i < numeroVideos; i++) {
        const videoField = document.createElement('div');
        videoField.classList.add('mb-4');
        videoField.innerHTML = `
            <label for="video_${i}" class="block text-gray-700 font-bold">Video ${i + 1}</label>
            <input type="file" name="videos_curso[]" id="video_${i}" class="w-full p-2 border border-gray-300 rounded-md" accept="video/*" required>
        `;
        videoFieldsContainer.appendChild(videoField);
    }
});

// Agregar campos de link y datetime
document.getElementById('agregar_link').addEventListener('click', function () {
    const numeroLinks = parseInt(document.getElementById('numero_links').value, 10);

    // Validar entrada
    if (isNaN(numeroLinks) || numeroLinks < 1) {
        alert('Por favor, ingresa un número válido de enlaces.');
        return;
    }

    // Contenedores separados
    const enlacesContainer = document.getElementById('enlaces_container');
    const fechasContainer = document.getElementById('fechas_container');

    // Limpiar contenedores
    enlacesContainer.innerHTML = '';
    fechasContainer.innerHTML = '';

    for (let i = 0; i < numeroLinks; i++) {
        // Crear campos de enlace
        const enlaceField = document.createElement('div');
        enlaceField.className = 'p-2 bg-gray-100 border border-gray-300 rounded-md';
        enlaceField.innerHTML = `
            <label class="block text-gray-700 font-bold mb-1">Enlace ${i + 1}</label>
            <input type="url" name="enlaces_curso[]" placeholder="https://ejemplo.com" class="w-full p-2 border border-gray-300 rounded-md" required>
        `;
        enlacesContainer.appendChild(enlaceField);

        // Crear campos de fecha y hora
        const fechaField = document.createElement('div');
        fechaField.className = 'p-2 bg-gray-100 border border-gray-300 rounded-md';
        fechaField.innerHTML = `
            <label class="block text-gray-700 font-bold mb-1">Fecha y Hora para Enlace ${i + 1}</label>
            <input type="datetime-local" name="fechas_curso[]" class="w-full p-2 border border-gray-300 rounded-md" required>
        `;
        fechasContainer.appendChild(fechaField);
    }
});


