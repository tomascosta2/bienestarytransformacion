<?php
// Parámetros de conexión a la base de datos
$host = 'localhost'; // Cambia esto a tu host de base de datos
$usuario = 'root'; // Cambia esto a tu usuario de base de datos
$password = 'root'; // Cambia esto a tu contraseña de base de datos
$base_datos = 'u770229669_dbluzmistica'; // Cambia esto al nombre de tu base de datos

// Crear conexión
$conn = mysqli_connect($host, $usuario, $password, $base_datos);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Opcional: Configurar el juego de caracteres
mysqli_set_charset($conn, "utf8");

?>
