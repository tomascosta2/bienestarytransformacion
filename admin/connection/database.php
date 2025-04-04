<?php
if ($_SERVER['HTTP_HOST'] === 'localhost:8000') {
    $host = 'localhost';
    $usuario = 'root';
    $password = 'root';
    $base_datos = 'u770229669_dbluzmistica'; // Puedes cambiar esto si el nombre de la base local es diferente
} else {
    $host = 'localhost'; // Cambia esto a tu host de producción si es diferente
    $usuario = 'u770229669_luzmistica';
    $password = 'LMacceso!25';
    $base_datos = 'u770229669_dbluzmistica';
}

// Crear conexión
$conn = mysqli_connect($host, $usuario, $password, $base_datos);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Opcional: Configurar el juego de caracteres
mysqli_set_charset($conn, "utf8");

?>
