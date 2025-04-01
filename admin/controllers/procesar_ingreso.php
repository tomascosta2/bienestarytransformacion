<?php
// Incluir el archivo de conexión a la base de datos
include '../connection/database.php'; // Asegúrate de que la ruta es correcta

// Iniciar una sesión específica para el panel de administración
session_name("admin_session");
session_start();

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Preparar la consulta para verificar si el usuario existe y es administrador
    $sql = "SELECT id, nombre, apellido, password, role FROM administrador WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro del correo electrónico
        $stmt->bind_param("s", $email);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado
        $stmt->store_result();
        
        // Verificar si se encontró un administrador con ese correo
        if ($stmt->num_rows == 1) {
            // Vincular las variables al resultado
            $stmt->bind_result($id, $nombre, $apellido, $hashed_password, $role);
            $stmt->fetch();
            
            // Verificar la contraseña
            if (password_verify($password, $hashed_password) && $role === 'administrador') {
                // Iniciar sesión exitosamente
                $_SESSION['admin_id'] = $id;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['role'] = $role;
                
                // Redirigir al panel de administración (ajustar ruta)
                header("Location: ../");
                exit;
            } else {
                // Contraseña incorrecta o no es administrador
                echo "Credenciales incorrectas o no tienes permisos de administrador.";
            }
        } else {
            // Administrador no encontrado
            echo "No se encontró ninguna cuenta con ese correo electrónico.";
        }

        // Cerrar la sentencia
        $stmt->close();
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
