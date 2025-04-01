<?php
// Incluye el archivo de conexión a la base de datos
require_once '../connection/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Sanitiza los datos de entrada
    $correo = mysqli_real_escape_string($conn, $_POST['email']);
    $contraseña = $_POST['password'];

    // Prepara la consulta para buscar el usuario
    $query = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica si la cuenta está activada
        if ($usuario['is_active'] == 0) {
            $_SESSION['error'] = "Tu cuenta no está activada. Por favor, revisa tu correo para activarla.";
            header("Location: https://luzmistica.net/?page=ingresar");
            exit();
        }

        // Verifica la contraseña
        if (password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];

            // Verifica si el usuario tiene una suscripción premium
            if ($usuario['es_premium'] == 1 && (is_null($usuario['premium_expires_at']) || strtotime($usuario['premium_expires_at']) > time())) {
                $_SESSION['es_premium'] = true;
            } else {
                $_SESSION['es_premium'] = false;
            }

            // Redirige al usuario a la página principal
            header("Location: ../");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No se encontró una cuenta con este correo.";
        header("Location: https://luzmistica.net/?page=ingresar");
        exit();
    }

    // Cierra las declaraciones y la conexión
    $stmt->close();
    $conn->close();
} else {
    header("Location: ./");
    exit();
}
?>
