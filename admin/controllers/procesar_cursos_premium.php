<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connection/database.php");

// Validar que los datos requeridos están presentes
if (!isset($_POST['nombre_curso'], $_POST['descripcion'], $_FILES['imagen_portada_premium'])) {
    die("Faltan datos necesarios para procesar el formulario.");
}

$nombreCurso = $_POST['nombre_curso'];
$descripcion = $_POST['descripcion'];

// Directorio para guardar los archivos subidos
$targetDir = "uploads/";

// Crear el directorio si no existe
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        die("Error al crear el directorio de subida.");
    }
}

// Manejo de la imagen de portada
$imagenPortadaPath = $targetDir . basename($_FILES['imagen_portada_premium']['name']);
if ($_FILES['imagen_portada_premium']['error'] === UPLOAD_ERR_OK) {
    if (!move_uploaded_file($_FILES['imagen_portada_premium']['tmp_name'], $imagenPortadaPath)) {
        die("Error al subir la imagen de portada.");
    }
} else {
    die("Error con el archivo de imagen de portada: " . $_FILES['imagen_portada_premium']['error']);
}

// Insertar datos en la tabla `cursos_premium`
$sqlCurso = "INSERT INTO cursos_premium (nombre_curso, descripcion, imagen_portada) VALUES (?, ?, ?)";
$stmtCurso = $conn->prepare($sqlCurso);

if (!$stmtCurso) {
    die("Error al preparar la consulta de curso: " . $conn->error);
}

$stmtCurso->bind_param("sss", $nombreCurso, $descripcion, $imagenPortadaPath);

if ($stmtCurso->execute()) {
    $cursoId = $stmtCurso->insert_id;

    // Subir y registrar archivos PDF
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
                    } else {
                        echo "Error al preparar la consulta de PDF: " . $conn->error;
                    }
                }
            }
        }
    }

    // Subir y registrar imágenes adicionales
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
                    } else {
                        echo "Error al preparar la consulta de imagen: " . $conn->error;
                    }
                }
            }
        }
    }

    // Subir y registrar videos adicionales
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
                    } else {
                        echo "Error al preparar la consulta de video: " . $conn->error;
                    }
                }
            }
        }
    }

// Procesar enlaces y fechas
if (isset($_POST['enlaces_curso'], $_POST['fechas_curso'])) {
    $links = $_POST['enlaces_curso'];
    $fechas = $_POST['fechas_curso'];

    // Validar que el número de enlaces y fechas coincidan
    if (count($links) === count($fechas)) {
        foreach ($links as $index => $enlace) {
            $fecha = $fechas[$index];
            if (!empty($enlace) && !empty($fecha)) {
                $sql = "INSERT INTO enlaces_cursos_premium (curso_id, url_enlace, fecha) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("iss", $cursoId, $enlace, $fecha);
                    if (!$stmt->execute()) {
                        echo "Error al guardar el enlace y la fecha: " . $stmt->error . "<br>";
                    } else {
                        echo "Enlace guardado: $enlace con fecha $fecha<br>";
                    }
                    $stmt->close();
                } else {
                    echo "Error al preparar la consulta de enlace: " . $conn->error . "<br>";
                }
            }
        }
    } else {
        echo "La cantidad de enlaces no coincide con las fechas.<br>";
    }

    }
    echo "Curso creado exitosamente.";
} else {
    die("Error al guardar el curso: " . $stmtCurso->error);
}


?>

