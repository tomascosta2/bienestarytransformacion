<?php
require '../connection/database.php';
$id = $_GET['id'];
$query = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Usuario eliminado.";
} else {
    echo "Error: " . $conn->error;
}
?>
