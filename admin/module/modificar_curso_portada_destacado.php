<?php
include("../connection/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del curso
    $curso_id = intval($_POST['curso_id']);
    $target_dir = "../admin/controllers/uploads/"; // Directorio de destino
    $target_file = $target_dir . basename($_FILES["imagen_portada"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si la imagen es válida
    $check = getimagesize($_FILES["imagen_portada"]["tmp_name"]);
    if ($check === false) {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "Lo siento, la imagen ya existe.";
        $uploadOk = 0;
    }

    // Limitar el tamaño del archivo (ejemplo: 5MB)
    if ($_FILES["imagen_portada"]["size"] > 5000000) {
        echo "Lo siento, la imagen es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Lo siento, solo se permiten imágenes JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Verificar si se debe proceder con la carga del archivo
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES["imagen_portada"]["tmp_name"], $target_file)) {
            // Actualizar la base de datos con la nueva imagen de portada
            $sql = "UPDATE cursos_destacados SET imagen_portada = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $imagen_ruta = "uploads/" . basename($_FILES["imagen_portada"]["name"]); // Ruta a guardar en la base de datos
            $stmt->bind_param("si", $imagen_ruta, $curso_id);

            if ($stmt->execute()) {
                echo "La imagen de portada ha sido actualizada exitosamente.";
            } else {
                echo "Error al actualizar la imagen de portada: " . $stmt->error;
            }
        } else {
            echo "Lo siento, hubo un error al cargar su archivo.";
        }
    }

    $conn->close();
} else {
    // Obtener el ID del curso a modificar
    $curso_id = intval($_GET['id']);

    // Consultar información del curso
    $sql = "SELECT nombre_curso, imagen_portada FROM cursos_destacados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $curso = $result->fetch_assoc();
    } else {
        echo "Curso no encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Portada del Curso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto mt-10 p-5 bg-white rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">Modificar Portada del Curso</h2>
        
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="imagen_portada">Nueva Imagen de Portada</label>
                <input type="file" name="imagen_portada" id="imagen_portada" required class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-6">
                <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-4 rounded hover:bg-purple-700 transition duration-200">Actualizar Portada</button>
            </div>
        </form>
    </div>
</body>
</html>
