<?php
require_once 'mercadopago_config.php';
include("./connection/database.php");

// Recibe la notificación de MercadoPago
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Verifica si es un pago recurrente aprobado
if ($data["type"] == "payment") {
    $payment = MercadoPago\Payment::find_by_id($data["data"]["id"]);

    if ($payment->status == "approved") {
        // Actualizar la suscripción en la base de datos
        $sql = "UPDATE suscripciones SET last_payment_date = NOW(), status = 'active' WHERE subscription_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $payment->preapproval_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
?>
