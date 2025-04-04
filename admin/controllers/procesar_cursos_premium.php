<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connection/database.php");

if (!isset($_POST['nombre_curso'], $_POST['descripcion'], $_FILES['imagen_portada_premium'])) {
    die("Faltan datos necesarios para procesar el formulario.");
}

$nombreCurso = $_POST['nombre_curso'];
$descripcion = $_POST['descripcion'];
$categorias = isset($_POST['categorias']) ? $_POST['categorias'] : [];
$categoriasJson = json_encode($categorias);

// Directorio
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        die("Error al crear el directorio de subida.");
    }
}

// Imagen de portada
$imagenPortadaPath = $targetDir . basename($_FILES['imagen_portada_premium']['name']);
if ($_FILES['imagen_portada_premium']['error'] === UPLOAD_ERR_OK) {
    if (!move_uploaded_file($_FILES['imagen_portada_premium']['tmp_name'], $imagenPortadaPath)) {
        die("Error al subir la imagen de portada.");
    }
} else {
    die("Error con el archivo de imagen de portada: " . $_FILES['imagen_portada_premium']['error']);
}

// Insertar en cursos_premium incluyendo categorías
$sqlCurso = "INSERT INTO cursos_premium (nombre_curso, descripcion, imagen_portada, categorias) VALUES (?, ?, ?, ?)";
$stmtCurso = $conn->prepare($sqlCurso);

if (!$stmtCurso) {
    die("Error al preparar la consulta de curso: " . $conn->error);
}

$stmtCurso->bind_param("ssss", $nombreCurso, $descripcion, $imagenPortadaPath, $categoriasJson);

if ($stmtCurso->execute()) {
    $cursoId = $stmtCurso->insert_id;

    // PDF
    if (isset($_FILES['materiales_pdf'])) {
        foreach ($_FILES['materiales_pdf']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['materiales_pdf']['error'][$key] === UPLOAD_ERR_OK) {
                $pdfPath = $targetDir . basename($_FILES['materiales_pdf']['name'][$key]);
                if (move_uploaded_file($tmpName, $pdfPath)) {
                    $sqlPdf = "INSERT INTO archivos_pdf_premium (curso_id, ruta_pdf) VALUES (?, ?)";
                    $stmtPdf = $conn->prepare($sqlPdf);
                    if ($stmtPdf) {
                        $stmtPdf->bind_param("is", $cursoId, $pdfPath);
                        $stmtPdf->execute();
                        $stmtPdf->close();
                    }
                }
            }
        }
    }

    // Imágenes
    if (isset($_FILES['imagenes_curso'])) {
        foreach ($_FILES['imagenes_curso']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['imagenes_curso']['error'][$key] === UPLOAD_ERR_OK) {
                $imagenPath = $targetDir . basename($_FILES['imagenes_curso']['name'][$key]);
                if (move_uploaded_file($tmpName, $imagenPath)) {
                    $sqlImagen = "INSERT INTO imagenes_curso_premium (curso_id, ruta_imagen) VALUES (?, ?)";
                    $stmtImagen = $conn->prepare($sqlImagen);
                    if ($stmtImagen) {
                        $stmtImagen->bind_param("is", $cursoId, $imagenPath);
                        $stmtImagen->execute();
                        $stmtImagen->close();
                    }
                }
            }
        }
    }

    // Videos
    if (isset($_FILES['videos_curso'])) {
        foreach ($_FILES['videos_curso']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['videos_curso']['error'][$key] === UPLOAD_ERR_OK) {
                $videoPath = $targetDir . basename($_FILES['videos_curso']['name'][$key]);
                if (move_uploaded_file($tmpName, $videoPath)) {
                    $sqlVideo = "INSERT INTO videos_curso_premium (curso_id, ruta_video) VALUES (?, ?)";
                    $stmtVideo = $conn->prepare($sqlVideo);
                    if ($stmtVideo) {
                        $stmtVideo->bind_param("is", $cursoId, $videoPath);
                        $stmtVideo->execute();
                        $stmtVideo->close();
                    }
                }
            }
        }
    }

    // Enlaces y fechas
    if (isset($_POST['enlaces_curso'], $_POST['fechas_curso'])) {
        $links = $_POST['enlaces_curso'];
        $fechas = $_POST['fechas_curso'];

        if (count($links) === count($fechas)) {
            foreach ($links as $index => $enlace) {
                $fecha = $fechas[$index];
                if (!empty($enlace) && !empty($fecha)) {
                    $sql = "INSERT INTO enlaces_cursos_premium (curso_id, url_enlace, fecha) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("iss", $cursoId, $enlace, $fecha);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    }

    echo "Curso creado exitosamente.";
} else {
    die("Error al guardar el curso: " . $stmtCurso->error);
}
?>
