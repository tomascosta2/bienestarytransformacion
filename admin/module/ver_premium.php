<?php
include("../connection/database.php");

// Consultar los cursos y sus archivos asociados
$sql = "SELECT cg.id, cg.nombre_curso, cg.descripcion, cg.imagen_portada,
               GROUP_CONCAT(DISTINCT apg.ruta_pdf SEPARATOR ',') AS pdfs,
               GROUP_CONCAT(DISTINCT icg.ruta_imagen SEPARATOR ',') AS imagenes,
               GROUP_CONCAT(DISTINCT vcg.ruta_video SEPARATOR ',') AS videos
        FROM cursos_premium cg
        LEFT JOIN archivos_pdf_premium apg ON cg.id = apg.curso_id
        LEFT JOIN imagenes_curso_premium icg ON cg.id = icg.curso_id
        LEFT JOIN videos_curso_premium vcg ON cg.id = vcg.curso_id
        GROUP BY cg.id";
$result = $conn->query($sql);
?>

<div class="max-w-6xl xl:max-w-7xl mx-auto mt-10 p-5">
    <h2 class="text-3xl font-bold mb-6 text-center text-purple-700">Cursos premium</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-purple-600 text-white">
                <tr>
                    <th class="py-3 px-4 border">Nombre del Curso</th>
                    <th class="py-3 px-4 border">Descripción</th>
                    <th class="py-3 px-4 border">Imagen de Portada</th>
                    <th class="py-3 px-4 border">Video del Curso</th>
                    <th class="py-3 px-4 border">Archivos PDF</th>
                    <th class="py-3 px-4 border">Imágenes del Curso</th>
                    <th class="py-3 px-4 border">Modificar</th>
                    <th class="py-3 px-4 border">Eliminar</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-200 transition duration-200">
                            <td class="py-3 px-4 border font-semibold text-gray-800"><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                            <td class="py-3 px-4 border text-gray-600">
                                <?php
                                $descripcion = $row['descripcion'];
                                $palabras = explode(' ', $descripcion);
                                $primerasPalabras = implode(' ', array_slice($palabras, 0, 10));
                                echo htmlspecialchars($primerasPalabras) . (count($palabras) > 10 ? '...' : '');
                                ?>
                            </td>
                            <td class="py-3 px-4 border">
                                <img src="../admin/controllers/<?php echo htmlspecialchars($row['imagen_portada']); ?>" alt="Imagen de Portada" class="w-20 h-20 object-cover rounded shadow-md">
                            </td>
                            <td class="py-3 px-4 border">
                                <?php
                                // Check if 'ruta_video' is available and not empty
                                if (!empty($row['videos'])):
                                    // Create a link to the page that will display all videos for this course
                                    $courseId = $row['id'];
                                    echo "<a href='./?page=videos_curso_premium&curso_id=" . htmlspecialchars($courseId) . "' class='text-blue-500 hover:underline'>Ver Todos los Videos</a>";
                                else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 border">
                                <?php
                                $pdfs = explode(',', $row['pdfs']);
                                foreach ($pdfs as $pdf) {
                                    if (!empty($pdf)) {
                                        echo "<a href='../admin/controllers/" . htmlspecialchars($pdf) . "' class='text-blue-500 hover:underline' target='_blank'>Descargar PDF</a><br>";
                                    }
                                }
                                ?>
                            </td>
                            <td class="py-3 px-4 border">
                                <?php
                                $imagenes = explode(',', $row['imagenes']);
                                foreach ($imagenes as $imagen) {
                                    if (!empty($imagen)) {
                                        echo "<img src='../admin/controllers/" . htmlspecialchars($imagen) . "' class='w-16 h-16 object-cover rounded-md shadow-sm mb-2' alt='Imagen del Curso'>";
                                    }
                                }
                                ?>
                            </td>
                            <!-- Botón Modificar -->
                            <td class="py-3 px-4 border text-center">
                                <a href="./?page=modificar_curso_titulo_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Título
                                </a>
                                <a href="./?page=modificar_curso_categorias&id=<?php echo $row['id']; ?>&tipo=premium"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Categorias
                                </a>
                                <a href="./?page=modificar_curso_descripcion_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Descripción
                                </a>
                                <a href="./?page=modificar_curso_portada_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Portada
                                </a>
                                <a href="./?page=modificar_curso_pdf_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar PDFs
                                </a>
                                <a href="./?page=modificar_curso_imagenes_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Imágenes
                                </a>
                                <a href="./?page=modificar_curso_links_premium&id=<?php echo $row['id']; ?>"
                                    class="inline-block bg-yellow-400 text-gray-800 font-semibold py-1 px-4 rounded-full shadow-md hover:bg-yellow-500 hover:shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                                    Modificar Enlaces
                                </a>
                            </td>

                            <!-- Botón Eliminar -->
                            <td class="py-3 px-4 border text-center">
                                <a href="./controllers/eliminar_curso_premium.php?id=<?php echo $row['id']; ?>" class="inline-block bg-red-500 text-white font-bold py-1 px-3 rounded hover:bg-red-600 transition duration-200">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-3 px-4 border text-center text-gray-600">No hay cursos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>