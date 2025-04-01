<?php
include("../connection/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete_image') {
    $imagenRuta = $_POST['imagen_ruta'];
    $cursoId = intval($_POST['curso_id']);

    // Eliminar archivo del sistema de archivos
    $filePath = "../admin/controllers/" . $imagenRuta;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Eliminar registro de la base de datos
    $sql = "DELETE FROM imagenes_curso_premium WHERE curso_id = ? AND ruta_imagen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $cursoId, $imagenRuta);

    if ($stmt->execute()) {
        echo "Imagen eliminada exitosamente.";
    } else {
        echo "Error al eliminar la imagen.";
    }

    $stmt->close();
    $conn->close();
}
?>
