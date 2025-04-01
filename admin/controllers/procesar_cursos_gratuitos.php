<?php
include("../connection/database.php");

// Manejo de la subida de archivos
$nombreCurso = $_POST['nombre_curso'];
$descripcion = $_POST['descripcion'];

// Definir las rutas donde se guardarán los archivos subidos
$targetDir = "uploads/"; // Asegúrate de que esta carpeta existe y tiene permisos de escritura

// Verificar si el directorio existe y si no, crearlo
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Crea la carpeta si no existe
}

// Manejo de la imagen de portada
$imagenPortadaPath = $targetDir . basename($_FILES['imagen_portada_gratuitos']['name']);
move_uploaded_file($_FILES['imagen_portada_gratuitos']['tmp_name'], $imagenPortadaPath);

// Preparar y ejecutar la consulta SQL para insertar los datos en la tabla cursos_gratuitos
$sqlCurso = "INSERT INTO cursos_gratuitos (nombre_curso, descripcion, imagen_portada)
             VALUES (?, ?, ?)";
$stmtCurso = $conn->prepare($sqlCurso);
$stmtCurso->bind_param("sss", $nombreCurso, $descripcion, $imagenPortadaPath);

if ($stmtCurso->execute()) {
    $cursoId = $stmtCurso->insert_id; // Obtener el ID del curso recién insertado

    // Manejo de los archivos PDF
    if (isset($_FILES['materiales_pdf'])) {
        $pdfPaths = [];
        foreach ($_FILES['materiales_pdf']['tmp_name'] as $key => $tmpName) {
            $pdfPath = $targetDir . basename($_FILES['materiales_pdf']['name'][$key]);
            if (move_uploaded_file($tmpName, $pdfPath)) {
                $pdfPaths[] = $pdfPath; // Guardar la ruta del archivo subido
            }
        }

        // Insertar los archivos PDF en la tabla
        if (!empty($pdfPaths)) {
            $sqlPdf = "INSERT INTO archivos_pdf_gratuitos (curso_id, ruta_pdf) VALUES (?, ?)";
            $stmtPdf = $conn->prepare($sqlPdf);
            
            foreach ($pdfPaths as $pdfPath) {
                $stmtPdf->bind_param("is", $cursoId, $pdfPath);
                $stmtPdf->execute();
            }
            $stmtPdf->close();
        }
    }

    // Manejo de las imágenes
    if (isset($_FILES['imagenes_curso'])) {
        $imagenPaths = [];
        foreach ($_FILES['imagenes_curso']['tmp_name'] as $key => $tmpName) {
            $imagenPath = $targetDir . basename($_FILES['imagenes_curso']['name'][$key]);
            if (move_uploaded_file($tmpName, $imagenPath)) {
                $imagenPaths[] = $imagenPath; // Guardar la ruta de la imagen subida
            }
        }

        // Insertar las imágenes en la tabla
        if (!empty($imagenPaths)) {
            $sqlImagen = "INSERT INTO imagenes_curso_gratuitos (curso_id, ruta_imagen) VALUES (?, ?)";
            $stmtImagen = $conn->prepare($sqlImagen);
            
            foreach ($imagenPaths as $imagenPath) {
                $stmtImagen->bind_param("is", $cursoId, $imagenPath);
                $stmtImagen->execute();
            }
            $stmtImagen->close();
        }
    }

    // Manejo de los videos adicionales del curso
    if (isset($_FILES['videos_curso'])) {
        $videoPaths = [];
        foreach ($_FILES['videos_curso']['tmp_name'] as $key => $tmpName) {
            $videoPath = $targetDir . basename($_FILES['videos_curso']['name'][$key]);
            if (move_uploaded_file($tmpName, $videoPath)) {
                $videoPaths[] = $videoPath; // Guardar la ruta del video subido
            }
        }

        // Insertar los videos en la tabla
        if (!empty($videoPaths)) {
            $sqlVideo = "INSERT INTO videos_curso_gratuitos (curso_id, ruta_video) VALUES (?, ?)";
            $stmtVideo = $conn->prepare($sqlVideo);
            
            foreach ($videoPaths as $videoPath) {
                $stmtVideo->bind_param("is", $cursoId, $videoPath);
                $stmtVideo->execute();
            }
            $stmtVideo->close();
        }
    }

    echo "Curso subido con éxito.";
} else {
    echo "Error al subir el curso: " . $stmtCurso->error;
}

$stmtCurso->close();
$conn->close();
?>
