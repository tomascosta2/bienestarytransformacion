<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    echo "<script>
        alert('Debes iniciar sesión para acceder a esta sección.');
        window.location.href = 'https://espaciobienestarintegral.com/?page=ingresar'; // Redirigir a la página de inicio de sesión
    </script>";
    exit();
}

require_once 'mercadopago_config.php';  // Configuración de Mercado Pago
include("./connection/database.php");

// Obtener el correo del usuario desde la base de datos
$user_id = $_SESSION['id'];
$sql = "SELECT correo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_email = $user['correo'] ?? null;
$stmt->close();

if (!$user_email) {
    echo "Error: No se pudo obtener el email del usuario.";
    die();
}

// Forzar la zona horaria UTC
date_default_timezone_set('UTC');

// Generar las fechas en formato ISO 8601 extendido con milisegundos
$start_date = (new DateTime('now'))->modify('+1 minute')->format('Y-m-d\TH:i:s.v\Z');
$end_date = (new DateTime('now'))->modify('+1 year')->format('Y-m-d\TH:i:s.v\Z');

// Depuración: Verificar las fechas generadas
echo "Start Date: " . $start_date . "<br>";
echo "End Date: " . $end_date . "<br>";

// Crear la suscripción (Preapproval) para el Plan Premium
$preapproval = new MercadoPago\Preapproval();
$preapproval->payer_email = $user_email;  // Email del usuario
$preapproval->back_url = "https://espaciobienestarintegral.com/pago_exitoso.php";  // URL de retorno
$preapproval->reason = "Suscripción mensual al Plan Premium";  // Descripción de la suscripción
$preapproval->auto_recurring = array(
    "frequency" => 1,  // Frecuencia de cobro (1 mes)
    "frequency_type" => "months",  // Tipo de frecuencia (mensual)
    "transaction_amount" => 15.00,  // Monto a cobrar
    "currency_id" => "ARS",  // Moneda (ajusta según tu país)
    "start_date" => $start_date,  // Fecha de inicio
    "end_date" => $end_date  // Fecha de finalización
);

// Guardar la suscripción
$preapproval->save();

if (isset($preapproval->error)) {
    echo "<h3>Error de MercadoPago:</h3><pre>";
    print_r($preapproval->error);
    echo "</pre>";
    exit();
}

if ($preapproval->id) {
    // Guardar la suscripción en la base de datos
    $sql = "INSERT INTO suscripciones (user_id, subscription_id, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $status = "active";
    $stmt->bind_param("iss", $user_id, $preapproval->id, $status);
    $stmt->execute();
    $stmt->close();

    // Redirigir al usuario al enlace de pago de MercadoPago
    header("Location: " . $preapproval->init_point);
    exit();
} else {
    echo "Error: No se pudo crear la suscripción en MercadoPago.";
}
?>