<?php
include '../../connection/database.php';

// Obtener todas las categorías disponibles
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);
$categorias = $resultCategorias->fetch_all(MYSQLI_ASSOC);
?>

<div class="max-w-md mx-auto mt-10">
    <h2 class="text-3xl font-bold mb-6 text-center text-purple-700">Subir Curso Premium</h2>
    <form action="./controllers/procesar_cursos_premium.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md" id="cursoForm">

        <!-- Campo Nombre del Curso -->
        <div class="mb-4">
            <label for="nombre_curso" class="block text-gray-700 font-bold">Nombre del Curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" class="w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción</label>
            <textarea
                id="descripcion"
                name="descripcion"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none"
                placeholder="Descripción"
                rows="5"></textarea>
        </div>
        
        <!-- Campo Categorías -->
        <div class="mb-4">
            <label for="categorias" class="block text-gray-700 font-bold">Categorías</label>
            <select name="categorias[]" id="categorias" class="w-full p-2 border border-gray-300 rounded-md" multiple required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['nombre']; ?>"><?php echo $categoria['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Campo Imagen de Portada del Curso -->
        <div class="mb-4">
            <label for="imagen_portada_premium" class="block text-gray-700 font-bold">Imagen de Portada</label>
            <input type="file" name="imagen_portada_premium" id="imagen_portada_premium" class="w-full p-2 border border-gray-300 rounded-md" accept="image/*" required>
        </div>

        <!-- Campo para el número de archivos PDF -->
        <div class="mb-4">
            <label for="numero_pdfs" class="block text-gray-700 font-bold">¿Cuántos archivos PDF vas a subir?</label>
            <input type="number" id="numero_pdfs" class="w-full p-2 border border-gray-300 rounded-md" min="1">
            <button type="button" id="agregar_pdf" class="mt-2 bg-purple-600 text-white p-2 rounded-md hover:bg-purple-700 transition duration-300">Agregar Campos PDF</button>
        </div>
        <div id="pdf_fields"></div>

        <!-- Campo para el número de archivos de imágenes -->
        <div class="mb-4">
            <label for="numero_imagenes" class="block text-gray-700 font-bold">¿Cuántas imágenes vas a subir?</label>
            <input type="number" id="numero_imagenes" class="w-full p-2 border border-gray-300 rounded-md" min="1">
            <button type="button" id="agregar_imagen" class="mt-2 bg-purple-600 text-white p-2 rounded-md hover:bg-purple-700 transition duration-300">Agregar Campos de Imágenes</button>
        </div>
        <div id="imagen_fields"></div>

        <!-- Campo para el número de videos -->
        <div class="mb-4">
            <label for="numero_videos" class="block text-gray-700 font-bold">¿Cuántos videos vas a subir?</label>
            <input type="number" id="numero_videos" class="w-full p-2 border border-gray-300 rounded-md" min="1">
            <button type="button" id="agregar_video" class="mt-2 bg-purple-600 text-white p-2 rounded-md hover:bg-purple-700 transition duration-300">Agregar Campos de Videos</button>
        </div>
        <div id="video_fields"></div>
        <!-- Campo para generar enlaces -->
        <div class="mb-4">
            <label for="numero_links" class="block text-gray-700 font-bold">¿Cuántos enlaces de clases vas a subir?</label>
            <input type="number" id="numero_links" class="w-full p-2 border border-gray-300 rounded-md" min="1">
            <button type="button" id="agregar_link" class="mt-2 bg-purple-600 text-white p-2 rounded-md hover:bg-purple-700 transition duration-300">
                Agregar Campos de Enlace
            </button>
        </div>

        <!-- Contenedores separados -->
        <div id="link_fields" class="space-y-4">
            <h3 class="text-lg font-bold text-gray-700">Enlaces Generados</h3>
            <div id="enlaces_container"></div>
        </div>

        <div id="date_fields" class="space-y-4 mt-4">
            <h3 class="text-lg font-bold text-gray-700">Fechas Generadas</h3>
            <div id="fechas_container"></div>
        </div>


        <!-- Botón Enviar -->
        <button type="submit" class="w-full bg-purple-600 text-white p-2 rounded-md hover:bg-purple-700 transition duration-300">Subir Curso</button>
    </form>
</div>

<script>
    new TomSelect("#categorias", {
        plugins: ['remove_button'],
        persist: false,
        create: false,
        placeholder: "Selecciona una o más categorías"
    });
</script>