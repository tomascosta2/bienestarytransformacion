<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del curso
    $curso_id = intval($_POST['curso_id']);
    $target_dir = "../admin/controllers/uploads/"; // Directorio de destino para imágenes
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["imagen_curso"]["name"], PATHINFO_EXTENSION));

    // Permitir ciertos formatos de archivo de imagen
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<p class='text-red-500 text-center'>Lo siento, solo se permiten imágenes en formato JPG, JPEG, PNG y GIF.</p>";
        $uploadOk = 0;
    }

    // Limitar el tamaño del archivo (ejemplo: 5MB)
    if ($_FILES["imagen_curso"]["size"] > 5000000) {
        echo "<p class='text-red-500 text-center'>Lo siento, la imagen es demasiado grande.</p>";
        $uploadOk = 0;
    }

    // Verificar si se debe proceder con la carga del archivo
    if ($uploadOk === 1) {
        $target_file = $target_dir . basename($_FILES["imagen_curso"]["name"]);
        if (move_uploaded_file($_FILES["imagen_curso"]["tmp_name"], $target_file)) {
            // Insertar la nueva ruta de la imagen en la base de datos
            $sql = "INSERT INTO imagenes_curso_destacados (curso_id, ruta_imagen) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $imagen_ruta = "uploads/" . basename($_FILES["imagen_curso"]["name"]); // Ruta a guardar en la base de datos
            $stmt->bind_param("is", $curso_id, $imagen_ruta);

            if ($stmt->execute()) {
                echo "<p class='text-green-500 text-center'>La imagen ha sido agregada exitosamente.</p>";
            } else {
                echo "<p class='text-red-500 text-center'>Error al agregar la imagen: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p class='text-red-500 text-center'>Lo siento, hubo un error al cargar su archivo.</p>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    // Obtener el ID del curso a modificar
    $curso_id = intval($_GET['id']);

    // Consultar información del curso
    $sql = "SELECT nombre_curso, descripcion, imagen_portada,
    GROUP_CONCAT(DISTINCT ruta_imagen SEPARATOR ',') AS imagenes
FROM cursos_destacados cg
LEFT JOIN imagenes_curso_destacados icg ON cg.id = icg.curso_id
WHERE cg.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $curso = $result->fetch_assoc();
    } else {
        echo "<p class='text-red-500 text-center'>Curso no encontrado.</p>";
        exit;
    }
}
?>
<div class="max-w-2xl mx-auto mt-10 p-5 bg-white rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">Modificar Imágenes del Curso</h2>
    
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="imagen_curso">Nueva Imagen del Curso</label>
            <input type="file" name="imagen_curso" id="imagen_curso" accept=".jpg,.jpeg,.png,.gif" required class="border border-gray-300 rounded p-2 w-full">
        </div>
        <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-4 rounded hover:bg-purple-700">Actualizar Imagen</button>
    </form>

    <div class="mt-6">
        <h3 class="text-xl font-bold text-gray-800">Imágenes Actuales:</h3>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <?php
            if (!empty($curso['imagenes'])) {
                $imagenes = explode(',', $curso['imagenes']);
                foreach ($imagenes as $imagen): ?>
                    <div class="relative">
                        <img src="../admin/controllers/<?php echo $imagen; ?>" alt="Imagen del Curso" class="rounded shadow-lg w-full h-auto">
                        <button onclick="eliminarImagen('<?php echo $imagen; ?>', <?php echo $curso_id; ?>)" 
        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 transition duration-200 ease-in-out transform hover:scale-110 hover:shadow-xl">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

                    </div>
                <?php endforeach;
            } else {
                echo "<p class='text-gray-500'>No hay imágenes para este curso.</p>";
            }
            ?>
        </div>
    </div>
</div>

<script>
    function eliminarImagen(imagenRuta, cursoId) {
        if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
            const formData = new FormData();
            formData.append('action', 'delete_image');
            formData.append('imagen_ruta', imagenRuta);
            formData.append('curso_id', cursoId);

            fetch('./controllers/delete_imagen_destacado.php', { // Ajusta la URL al formato modular
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Mostrar mensaje de éxito o error
                location.reload(); // Recargar la página para actualizar la lista
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
