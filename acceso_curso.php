<?php
// Conexión a la base de datos
include("./connection/database.php");

// Obtener parámetros desde la URL
$curso_id = $_GET['id'] ?? null;
$usuario_id = $_GET['user'] ?? null;
$status = $_GET['status'] ?? null;

// Validar parámetros
if (!$curso_id || !$usuario_id || !$status) {
    echo "Faltan parámetros necesarios.";
    exit();
}

// Verificar si el pago fue aprobado
if ($status === 'approved') {
    // Registrar la compra en la tabla `usuarios_cursos`
    $sqlInsert = "INSERT INTO usuarios_cursos (usuario_id, curso_id, estado_pago, fecha_pago) 
                  VALUES (?, ?, 1, NOW())";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ii", $usuario_id, $curso_id);

    if ($stmt->execute()) {
        // Redirigir al usuario a la página de "Mis Cursos"
        header("Location: https://luzmistica.net/?page=mis_cursos");
        exit();
    } else {
        echo "Error al registrar la compra: " . $conn->error;
    }
} elseif ($status === 'failure') {
    // Manejar pago fallido
    echo "El pago no fue exitoso. Por favor, inténtalo nuevamente.";
} else {
    echo "Estado desconocido.";
}

$conn->close();
?>
