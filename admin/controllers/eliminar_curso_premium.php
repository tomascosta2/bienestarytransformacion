<?php
include("../connection/database.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegurar que sea un entero

    // Consultar los archivos asociados al curso
    $query = "SELECT cg.imagen_portada, 
                     apg.ruta_pdf AS pdfs, 
                     icg.ruta_imagen AS imagenes, 
                     vcg.ruta_video AS videos
              FROM cursos_premium cg
              LEFT JOIN archivos_pdf_premium apg ON cg.id = apg.curso_id
              LEFT JOIN imagenes_curso_premium icg ON cg.id = icg.curso_id
              LEFT JOIN videos_curso_premium vcg ON cg.id = vcg.curso_id
              WHERE cg.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Eliminar imagen de portada
            if (!empty($row['imagen_portada']) && file_exists("../admin/controllers/" . $row['imagen_portada'])) {
                unlink("../admin/controllers/" . $row['imagen_portada']);
            }

            // Eliminar PDFs
            $pdfs = explode(',', $row['pdfs']);
            foreach ($pdfs as $pdf) {
                if (!empty($pdf) && file_exists("../admin/controllers/" . $pdf)) {
                    unlink("../admin/controllers/" . $pdf);
                }
            }

            // Eliminar imágenes
            $imagenes = explode(',', $row['imagenes']);
            foreach ($imagenes as $imagen) {
                if (!empty($imagen) && file_exists("../admin/controllers/" . $imagen)) {
                    unlink("../admin/controllers/" . $imagen);
                }
            }

            // Eliminar videos
            $videos = explode(',', $row['videos']);
            foreach ($videos as $video) {
                if (!empty($video) && file_exists("../admin/controllers/" . $video)) {
                    unlink("../admin/controllers/" . $video);
                }
            }
        }
    }

    // Eliminar registros de la base de datos
    $conn->begin_transaction();
    try {
        // Eliminar los archivos relacionados
        $conn->query("DELETE FROM archivos_pdf_premium WHERE curso_id = $id");
        $conn->query("DELETE FROM imagenes_curso_premium WHERE curso_id = $id");
        $conn->query("DELETE FROM videos_curso_premium WHERE curso_id = $id");

        // Eliminar el curso
        $conn->query("DELETE FROM cursos_premium WHERE id = $id");

        $conn->commit();
        header("Location: ../"); // Redirigir con un mensaje de éxito
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../"); // Redirigir con un mensaje de error
        exit();
    }
} else {
    header("Location: ../");
    exit();
}
?>
