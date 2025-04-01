<?php
include("../connection/database.php");

if (isset($_GET['id'])) {
    $curso_id = intval($_GET['id']);
    
    // Eliminar imágenes asociadas
    $sql_imagenes = "SELECT ruta_imagen FROM imagenes_curso_destacados WHERE curso_id = ?";
    $stmt_imagenes = $conn->prepare($sql_imagenes);
    $stmt_imagenes->bind_param("i", $curso_id);
    $stmt_imagenes->execute();
    $result_imagenes = $stmt_imagenes->get_result();

    while ($row = $result_imagenes->fetch_assoc()) {
        $ruta_imagen = "../admin/controllers/" . $row['ruta_imagen'];
        if (file_exists($ruta_imagen)) {
            unlink($ruta_imagen); // Eliminar el archivo de la imagen
        }
    }

    // Eliminar todos los registros relacionados con el curso
    $sql_delete = "DELETE FROM cursos_destacados WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $curso_id);
    if ($stmt_delete->execute()) {
        // Eliminar archivos PDF y videos asociados
        $conn->query("DELETE FROM archivos_pdf_destacados WHERE curso_id = $curso_id");
        $conn->query("DELETE FROM videos_curso_destacados WHERE curso_id = $curso_id");
        
        // Eliminar imágenes del curso
        $conn->query("DELETE FROM imagenes_curso_destacados WHERE curso_id = $curso_id");

        // Redirigir después de la eliminación
        header("Location: ../");
        exit;
    } else {
        echo "Error al eliminar el curso.";
    }

    $stmt_delete->close();
}

$conn->close();
?>
