<?php
require '../connection/database.php';

$id = $_GET['id'];

$query = "DELETE FROM clases_en_vivo WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Clase eliminada exitosamente.";
} else {
    echo "Error al eliminar la clase: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
