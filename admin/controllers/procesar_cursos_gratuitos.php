<?php
include("../connection/database.php");

// Manejo de la subida de archivos
$nombreCurso = $_POST['nombre_curso'];
$descripcion = $_POST['descripcion'];

// Capturar y convertir las categorías a JSON
$categorias = isset($_POST['categorias']) ? json_encode($_POST['categorias']) : json_encode([]);

// Definir las rutas donde se guardarán los archivos subidos
$targetDir = "uploads/";

// Verificar si el directorio existe y si no, crearlo
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Manejo de la imagen de portada
$imagenPortadaPath = $targetDir . basename($_FILES['imagen_portada_gratuitos']['name']);
move_uploaded_file($_FILES['imagen_portada_gratuitos']['tmp_name'], $imagenPortadaPath);

// Preparar y ejecutar la consulta SQL para insertar el curso en la tabla cursos_gratuitos
$sqlCurso = "INSERT INTO cursos_gratuitos (nombre_curso, descripcion, categorias, imagen_portada)
             VALUES (?, ?, ?, ?)";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("ssss", $nombreCurso, $descripcion, $categorias, $imagenPortadaPath);

if ($stmtCurso->execute()) {
    $cursoId = $stmtCurso->insert_id; // Obtener el ID del curso recién insertado

    // Manejo de los archivos PDF
    if (!empty($_FILES['materiales_pdf']['name'][0])) {
        $sqlPdf = "INSERT INTO archivos_pdf_gratuitos (curso_id, ruta_pdf) VALUES (?, ?)";
        $stmtPdf = $conn->prepare($sqlPdf);

        foreach ($_FILES['materiales_pdf']['tmp_name'] as $key => $tmpName) {
            $pdfPath = $targetDir . basename($_FILES['materiales_pdf']['name'][$key]);
            if (move_uploaded_file($tmpName, $pdfPath)) {
                $stmtPdf->bind_param("is", $cursoId, $pdfPath);
                $stmtPdf->execute();
            }
        }
        $stmtPdf->close();
    }

    // Manejo de las imágenes
    if (!empty($_FILES['imagenes_curso']['name'][0])) {
        $sqlImagen = "INSERT INTO imagenes_curso_gratuitos (curso_id, ruta_imagen) VALUES (?, ?)";
        $stmtImagen = $conn->prepare($sqlImagen);

        foreach ($_FILES['imagenes_curso']['tmp_name'] as $key => $tmpName) {
            $imagenPath = $targetDir . basename($_FILES['imagenes_curso']['name'][$key]);
            if (move_uploaded_file($tmpName, $imagenPath)) {
                $stmtImagen->bind_param("is", $cursoId, $imagenPath);
                $stmtImagen->execute();
            }
        }
        $stmtImagen->close();
    }

    // Manejo de los videos
    if (!empty($_FILES['videos_curso']['name'][0])) {
        $sqlVideo = "INSERT INTO videos_curso_gratuitos (curso_id, ruta_video) VALUES (?, ?)";
        $stmtVideo = $conn->prepare($sqlVideo);

        foreach ($_FILES['videos_curso']['tmp_name'] as $key => $tmpName) {
            $videoPath = $targetDir . basename($_FILES['videos_curso']['name'][$key]);
            if (move_uploaded_file($tmpName, $videoPath)) {
                $stmtVideo->bind_param("is", $cursoId, $videoPath);
                $stmtVideo->execute();
            }
        }
        $stmtVideo->close();
    }

    echo "Curso subido con éxito.";
} else {
    echo "Error al subir el curso: " . $stmtCurso->error;
}

$stmtCurso->close();
$conn->close();
?>
