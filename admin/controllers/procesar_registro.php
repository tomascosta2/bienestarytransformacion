<?php
// procesar_registro.php

// Incluir el archivo de conexión a la base de datos
include '../connection/database.php';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['role'];
    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar el nuevo usuario
    $sql = "INSERT INTO administrador (nombre, apellido, email, password, role) VALUES (?, ?, ?, ?, ?)";

    // Inicializar la sentencia preparada
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $hashed_password, $role);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Registro exitoso
            echo "Registro completado exitosamente.";
            header("Location: ../"); // Redirigir al inicio de sesión o a otra página
            exit;
        } else {
            // Error en la ejecución de la consulta
            echo "Error: " . $stmt->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
