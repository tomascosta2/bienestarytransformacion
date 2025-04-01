<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del curso
    $curso_id = intval($_POST['curso_id']);
    $target_dir = "../admin/controllers/uploads/"; // Directorio de destino para videos
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($_FILES["video_curso"]["name"], PATHINFO_EXTENSION));

    // Permitir ciertos formatos de archivo de video (ejemplo: mp4, avi, mov)
    if (!in_array($videoFileType, ['mp4', 'avi', 'mov'])) {
        echo "<p class='text-red-500 text-center'>Lo siento, solo se permiten videos en formato MP4, AVI y MOV.</p>";
        $uploadOk = 0;
    }

    // Limitar el tamaño del archivo (ejemplo: 50MB)
if ($_FILES["video_curso"]["size"] > 500 * 1024 * 1024) { // 500MB
        echo "<p class='text-red-500 text-center'>Lo siento, el video es demasiado grande.</p>";
        $uploadOk = 0;
    }

    // Verificar si se debe proceder con la carga del archivo
    if ($uploadOk === 1) {
        $target_file = $target_dir . basename($_FILES["video_curso"]["name"]);
        if (move_uploaded_file($_FILES["video_curso"]["tmp_name"], $target_file)) {
            // Insertar la nueva ruta del video en la base de datos
            $sql = "INSERT INTO videos_curso_premium (curso_id, ruta_video) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $video_ruta = "uploads/" . basename($_FILES["video_curso"]["name"]); // Ruta a guardar en la base de datos
            $stmt->bind_param("is", $curso_id, $video_ruta);

            if ($stmt->execute()) {
                echo "<p class='text-green-500 text-center'>El video ha sido agregado exitosamente.</p>";
            } else {
                echo "<p class='text-red-500 text-center'>Error al agregar el video: " . $stmt->error . "</p>";
            }
            $stmt->close(); // Cerrar el stmt después de usarlo
        } else {
            echo "<p class='text-red-500 text-center'>Lo siento, hubo un error al cargar su archivo.</p>";
        }
    }

    $conn->close(); // Cerrar la conexión después de completar la operación
}

?>

<!-- Formulario para agregar video -->
<div class="max-w-7xl mx-auto mt-10 p-5">
    <h2 class="text-3xl font-bold mb-6 text-center text-purple-700">Agregar Video al Curso</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="curso_id" value="<?php echo $_GET['curso_id']; ?>">

        <div class="mb-4">
            <label for="video_curso" class="block text-gray-700">Seleccionar Video</label>
            <input type="file" name="video_curso" id="video_curso" class="mt-2 p-2 border border-gray-300 rounded" required>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="bg-purple-600 text-white py-2 px-6 rounded hover:bg-purple-700">Agregar Video</button>
        </div>
    </form>
</div>
