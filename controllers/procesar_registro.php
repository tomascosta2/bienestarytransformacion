<?php
// Incluye el archivo de conexión a la base de datos
require_once '../connection/database.php';

// Incluye PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de tener configurado el autoload de Composer si estás usando Composer.
require '../vendor/autoload.php';


// Verifica que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Entra";
    // Recupera y limpia los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['name']);
    $correo = mysqli_real_escape_string($conn, $_POST['email']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmar_contraseña = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Validación de datos
    if (empty($nombre) || empty($correo) || empty($contraseña) || empty($confirmar_contraseña)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit();
    }

    if ($contraseña !== $confirmar_contraseña) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Verifica si el correo ya está registrado
    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "El correo ya está registrado.";
        exit();
    }

    // Hash de la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Generar un token único para la activación de la cuenta
    $token = bin2hex(random_bytes(50));
    // Inserta el usuario en la base de datos (con token)
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, token, is_active) VALUES ('$nombre', '$correo', '$contraseña_hash', '$token', 0)";

    if (mysqli_query($conn, $sql)) {
        // Redirige a la página de inicio de sesión después del registro exitoso
        echo "Registro exitoso. Enviando correo de confirmación...";

        // Enviar correo de confirmación con PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Usa el servidor SMTP de tu elección
            $mail->SMTPAuth = true;
            $mail->Username = 'bienestarintegralescuela@gmail.com'; // Tu correo de Gmail
            $mail->Password = 'usufewmgrfqpazpf'; // Tu contraseña de Gmail o app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // o 465

            print_r($mail);

            // Remitente y destinatario
            $mail->setFrom('bienestarintegralescuela@gmail.com', 'Escuela Bienestar Integral');
            $mail->addAddress($correo, $nombre); // Destinatario

            echo 'pasa 2';

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta en Escuela Bienestar Integral';
            $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
            $activation_url = $base_url . "/activar.php?token=" . urlencode($token);
            $mail->Body    = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: 'Helvetica', Arial, sans-serif;
                            background-color: #f7f7f7;
                            margin: 0;
                            padding: 0;
                            color: #333;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #fff;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                            padding: 20px;
                        }
                        .header {
                            background-color: #0056b3;
                            color: #fff;
                            padding: 10px;
                            text-align: center;
                            border-radius: 8px 8px 0 0;
                        }
                        .header h1 {
                            margin: 0;
                            font-size: 24px;
                        }
                        .content {
                            padding: 20px;
                        }
                        .content h2 {
                            color: #0056b3;
                            font-size: 22px;
                            margin-bottom: 10px;
                        }
                        .content p {
                            font-size: 16px;
                            line-height: 1.6;
                            color: #555;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            font-size: 16px;
                            color: #fff !important;
                            background-color: #28a745;
                            border-radius: 4px;
                            text-decoration: none;
                            margin-top: 20px;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 20px;
                            font-size: 12px;
                            color: #999;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Escuela Bienestar Integral</h1>
                        </div>
                        <div class='content'>
                            <h2>Gracias por registrarte, $nombre</h2>
                            <p>Estamos muy contentos de que te hayas unido a nuestra plataforma. Para activar tu cuenta, simplemente haz clic en el siguiente botón:</p>
                            <a href='$activation_url' class='button'>Activar Cuenta</a>
                        </div>
                        <div class='footer'>
                            <p>Si tienes algún problema, no dudes en contactarnos a soporte@espaciobienestarintegral.com</p>
                        </div>
                    </div>
                </body>
                </html>";

            echo 'pasa 3';

            // Enviar el correo
            $mail->send();
            echo 'Correo de confirmación enviado.';

            echo 'pasa 4';

            // Redirige a la página de éxito después de enviar el correo
            header("Location: /pages/registro-exitoso");
            exit();

        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error en el registro: " . mysqli_error($conn);
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
}
?>
