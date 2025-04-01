<?php

include("../connection/database.php");

// Obtener los datos del formulario
$class_name = isset($_POST['class_name']) ? $conn->real_escape_string(trim($_POST['class_name'])) : null;
$class_link = isset($_POST['class_link']) ? $conn->real_escape_string(trim($_POST['class_link'])) : null;
$class_date = isset($_POST['class_date']) ? $conn->real_escape_string(trim($_POST['class_date'])) : null;

// Validar que los campos no estén vacíos
if ($class_name && $class_link && $class_date) {
    // Preparar la consulta SQL
    $sql = "INSERT INTO clases_en_vivo (nombre_clase, enlace_clase, fecha_clase) VALUES ('$class_name', '$class_link', '$class_date')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "Clase en vivo agregada exitosamente.";
    } else {
        echo "Error al agregar la clase: " . $conn->error;
    }
} else {
    echo "Por favor, completa todos los campos.";
}

// Cerrar la conexión
$conn->close();
?>
