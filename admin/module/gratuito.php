<?php
include '../../connection/database.php';

// Obtener todas las categorías disponibles
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);
$categorias = $resultCategorias->fetch_all(MYSQLI_ASSOC);
?>

<div class="max-w-md mx-auto mt-10">
    <h2 class="text-3xl font-bold mb-6 text-center text-purple-700">Subir Curso Gratuito</h2>
    <form action="./controllers/procesar_cursos_gratuitos.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md" id="cursoForm">

        <!-- Campo Nombre del Curso -->
        <div class="mb-4">
            <label for="nombre_curso" class="block text-gray-700 font-bold">Nombre del Curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" class="w-full p-2 border border-gray-300 rounded-md" required>
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

        <!-- Campo Descripción -->
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 font-bold">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="w-full p-2 border border-gray-300 rounded-md" rows="4"></textarea>
        </div>

        <!-- Campo Imagen de Portada del Curso -->
        <div class="mb-4">
            <label for="imagen_portada_gratuitos" class="block text-gray-700 font-bold">Imagen de Portada</label>
            <input type="file" name="imagen_portada_gratuitos" id="imagen_portada_gratuitos" class="w-full p-2 border border-gray-300 rounded-md" accept="image/*" required>
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