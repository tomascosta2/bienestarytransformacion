<?php
include("../connection/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_pdf') {
    $pdf_ruta = $_POST['pdf_ruta'];
    $curso_id = intval($_POST['curso_id']);

    // Eliminar el archivo del sistema de archivos
    $file_path = "../admin/controllers/" . $pdf_ruta;
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Eliminar el registro de la base de datos
    $sql = "DELETE FROM archivos_pdf_gratuitos WHERE curso_id = ? AND ruta_pdf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $curso_id, $pdf_ruta);

    if ($stmt->execute()) {
        echo "El archivo PDF ha sido eliminado exitosamente.";
    } else {
        echo "Error al eliminar el archivo PDF: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
