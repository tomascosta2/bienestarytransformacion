<?php

session_start();

// Incluye la configuración de Mercado Pago
require_once 'mercadopago_config.php';

// Obtener el ID del curso que el usuario ha seleccionado
$curso_id = $_GET['id']; // Asegúrate de pasar el ID del curso en la URL

// Conexión a la base de datos
include("./connection/database.php");

// Consulta para obtener el curso seleccionado
$sqlCurso = "SELECT * FROM cursos_destacados WHERE id = ?";
$stmt = $conn->prepare($sqlCurso);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica si el curso existe
if ($result->num_rows > 0) {
    $curso = $result->fetch_assoc();

    // Crear un objeto de preferencia de pago
    $preference = new MercadoPago\Preference();

    // Crear un ítem (curso, producto o servicio)
    $item = new MercadoPago\Item();
    $item->title = $curso['nombre_curso']; // Nombre del curso
    $item->quantity = 1; // Cantidad
    $item->unit_price = floatval($curso['precio']); // Precio del curso en USD
    $item->currency_id = "ARS"; // Moneda

    // Agregar el ítem a la preferencia
    $preference->items = array($item);

    // Obtener el usuario actual
    session_start();
    if (!isset($_SESSION['id'])) {
        echo "Usuario no autenticado. Por favor inicia sesión.";
        exit();
    }
    $usuario_id = $_SESSION['user_id'];

    // Definir la URL de retorno en caso de éxito
    $preference->back_urls = array(
        "success" => "https://espaciobienestarintegral.com/acceso_curso.php?id=$curso_id&user=$usuario_id&status=approved", // Página de acceso tras pago exitoso
        "failure" => "https://espaciobienestarintegral.com/acceso_curso.php?id=$curso_id&status=failure", // Página de error en caso de fallo
    );

    // Definir el tipo de pago
    $preference->auto_return = "approved";

    // Guardar la preferencia
    $preference->save();

    // Redirigir al usuario a Mercado Pago para completar el pago
    header("Location: " . $preference->init_point);
    exit();
} else {
    // Si no se encuentra el curso, redirigir al usuario a una página de error
    echo "Curso no encontrado.";
    exit();
}

$conn->close();
?>
