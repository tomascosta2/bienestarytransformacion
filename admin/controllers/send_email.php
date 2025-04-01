<?php
require '../connection/database.php';
require '../../vendor/autoload.php'; // Incluye PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_GET['id'];
$query = "SELECT correo, nombre FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'luzmisticaescuela@gmail.com';
    $mail->Password = 'lmbofezojmjijhbr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('luzmisticaescuela@gmail.com', 'Luz Mistica');
    $mail->addAddress($user['correo'], $user['nombre']);

    $mail->isHTML(true);
    $mail->Subject = '¡Novedades de Luz Mistica!';
    $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px;
                    background-color: #6a1b9a;
                    color: #ffffff;
                    border-radius: 8px 8px 0 0;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                    color: #333333;
                }
                .content h2 {
                    color: #6a1b9a;
                    margin-bottom: 10px;
                }
                .content p {
                    line-height: 1.6;
                }
                .content a {
                    display: inline-block;
                    padding: 10px 20px;
                    margin-top: 20px;
                    background-color: #6a1b9a;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 16px;
                }
                .content a:hover {
                    background-color: #4a0072;
                }
                .footer {
                    text-align: center;
                    padding: 10px;
                    font-size: 12px;
                    color: #777777;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Luz Mistica</h1>
                </div>
                <div class="content">
                    <h2>¡Hola ' . htmlspecialchars($user['nombre']) . '!</h2>
                    <p>Tenemos grandes noticias para ti. Hemos subido un nuevo curso a nuestra plataforma.</p>
                    <p>Este curso te ayudará a profundizar en tu conexión interior, mejorar tus prácticas espirituales y encontrar equilibrio en tu vida diaria.</p>
                    <p>No te pierdas esta oportunidad de crecer junto a nuestra comunidad.</p>
                    <a href="https://luzmistica.net" target="_blank">Visitar Luz Mistica</a>
                </div>
                <div class="footer">
                    <p>Si tienes alguna duda o consulta, no dudes en contactarnos a luzmisticaescuela@gmail.com</p>
                </div>
            </div>
        </body>
        </html>';

    $mail->send();
    echo "Correo enviado con éxito.";
} catch (Exception $e) {
    echo "Error al enviar correo: " . $mail->ErrorInfo;
}
?>
