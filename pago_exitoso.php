<?php
session_start();
require_once 'mercadopago_config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    echo "<script>
        alert('Debes iniciar sesión para acceder a esta sección.');
        window.location.href = 'https://luzmistica.net/?page=ingresar'; // Redirigir a la página de inicio de sesión
    </script>";
    exit();
}

// Obtener el ID de la suscripción desde la URL
$subscription_id = $_GET['preapproval_id'];

// Verificar el estado de la suscripción
$subscription = MercadoPago\Preapproval::find_by_id($subscription_id);

if ($subscription && ($subscription->status == 'authorized' || $subscription->status == 'pending')) {
    // Suponiendo que tienes el `user_id` en sesión después del login
    $user_id = $_SESSION['id'];
    
    include("./connection/database.php");

    // Verificar errores de conexión a la base de datos
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Verificar si el usuario ya tiene una suscripción activa
    $sql_check = "SELECT es_premium FROM usuarios WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $user_data = $result_check->fetch_assoc();
    $stmt_check->close();

    if ($user_data['es_premium'] == 1) {
        echo "Ya eres un usuario premium. No es necesario activar la suscripción nuevamente.";
        exit();
    }

    // Calcula las fechas de activación y expiración
    $fecha_actual = date('Y-m-d H:i:s'); // Fecha actual
    $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+30 days')); // 30 días después
    
    // Actualizar el usuario a premium con fechas de activación y expiración
    $sql = "UPDATE usuarios 
            SET es_premium = 1, premium_activated_at = ?, premium_expires_at = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fecha_actual, $fecha_expiracion, $user_id);

    if ($stmt->execute()) {
        // Redirigir al usuario a una página de confirmación
        header("Location: https://luzmistica.net/pago_exitoso_confirmacion.php");
        exit();
    } else {
        echo "Hubo un error al actualizar tu estado a premium: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "La suscripción no fue autorizada o está en un estado no válido. Intenta nuevamente.";
}
?>