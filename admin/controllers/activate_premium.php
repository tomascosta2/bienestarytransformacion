<?php
require '../connection/database.php';

$id = $_GET['id'];

// Calcula las fechas de activación y expiración
$fecha_actual = date('Y-m-d H:i:s'); // Fecha actual
$fecha_expiracion = date('Y-m-d H:i:s', strtotime('+30 days')); // 30 días después

// Actualizar el usuario a premium con fechas de activación y expiración
$query = "UPDATE usuarios 
          SET es_premium = 1, premium_activated_at = ?, premium_expires_at = ? 
          WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $fecha_actual, $fecha_expiracion, $id);

if ($stmt->execute()) {
    echo "Usuario actualizado a Premium. La membresía expira el $fecha_expiracion.";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
