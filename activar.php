<?php
// Incluye el archivo de conexión a la base de datos
require_once './connection/database.php';

// Verifica la conexión a la base de datos
if (!$conn) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

// Verifica si se ha pasado un token válido en la URL
if (isset($_GET['token']) && preg_match('/^[a-f0-9]{100}$/', $_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    // Busca el usuario que tiene este token
    $query = "SELECT * FROM usuarios WHERE token = '$token' AND is_active = 0";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Actualiza el estado del usuario a activo
        $updateQuery = "UPDATE usuarios SET is_active = 1, token = NULL WHERE token = '$token'";
        if (mysqli_query($conn, $updateQuery)) {
            // Muestra una alerta y redirige al usuario a la página de inicio de sesión
            echo "<script>
                alert('¡Cuenta activada con éxito! Ahora puedes iniciar sesión.');
                window.location.href = 'https://luzmistica.net/?page=ingresar';
            </script>";
            exit();
        } else {
            echo "<script>alert('Hubo un error al activar tu cuenta. Intenta nuevamente más tarde.');</script>";
        }
    } else {
        echo "<script>alert('Token inválido o ya activado.');</script>";
    }
} else {
    echo "<script>alert('Token no proporcionado o no válido.');</script>";
}

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>
