<?php
include("../connection/database.php");

if (isset($_POST['video_id'])) {
    $video_id = intval($_POST['video_id']);

    // Obtener la ruta del video desde la base de datos
    $sql = "SELECT ruta_video FROM videos_curso_gratuitos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $video = $result->fetch_assoc();
        $video_ruta = $video['ruta_video'];

        // Eliminar el archivo de video del servidor
        if (file_exists("../" . $video_ruta)) {
            unlink("../" . $video_ruta); // Elimina el archivo del servidor
        }

        // Eliminar el registro del video en la base de datos
        $delete_sql = "DELETE FROM videos_curso_gratuitos WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $video_id);

        if ($delete_stmt->execute()) {
            echo "<p class='text-green-500 text-center'>El video ha sido eliminado exitosamente.</p>";
            // Redirigir al usuario a la página del curso (cambia "ver_curso.php" por la página correspondiente)
            header("Location: ../");
            exit();
        } else {
            echo "<p class='text-red-500 text-center'>Error al eliminar el video: " . $delete_stmt->error . "</p>";
        }

        $delete_stmt->close();
    } else {
        echo "<p class='text-red-500 text-center'>No se encontró el video.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p class='text-red-500 text-center'>No se ha especificado un video para eliminar.</p>";
}
?>
