<?php
// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Si se desea, destruir la sesión también
session_destroy();

// Redirigir a la página de inicio o a otra página después de salir
header("Location: ./"); // Cambia 'index.php' a la página que desees
exit; // Asegúrate de detener la ejecución del script
?>
