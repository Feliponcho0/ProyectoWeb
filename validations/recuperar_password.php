<?php
require_once 'conection.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../lib/PHPMailer-7.1.1/src/Exception.php';
require_once '../lib/PHPMailer-7.1.1/src/PHPMailer.php';
require_once '../lib/PHPMailer-7.1.1/src/SMTP.php';

header('Content-Type: application/json');

$correo = trim($_POST['correo'] ?? '');

if (empty($correo)) {
    echo json_encode(['ok' => false, 'msg' => 'Ingresa tu correo electrónico']);
    exit;
}

// Buscar usuario por correo
$stmt = $conn->prepare("SELECT usuarios_id, nombre_usuario, correo FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'No existe una cuenta con ese correo']);
    exit;
}

$usuario = $result->fetch_assoc();

// Generar contraseña temporal de 8 caracteres
$temp_password = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);


if (!password_verify($temp_password, $hashed_password)) {
    echo json_encode(['ok' => false, 'msg' => 'Error interno: no se pudo generar la contraseña correctamente']);
    exit;
}

// Actualizar contraseña
$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuarios_id = ?");
$stmt->bind_param("si", $hashed_password, $usuario['usuarios_id']);
// Actualizar contraseña en la base de datos
$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuarios_id = ?");
$stmt->bind_param("si", $hashed_password, $usuario['usuarios_id']);

if (!$stmt->execute()) {
    echo json_encode(['ok' => false, 'msg' => 'Error al generar nueva contraseña']);
    exit;
}

// Configurar PHPMailer con tu cuenta de Gmail
$mail = new PHPMailer(true);

try {
    // Configuración SMTP de Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'castillofelipejesus@gmail.com';
    $mail->Password   = 'ytjmohawgfoqbgix';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente y destinatario
    $mail->setFrom('castillofelipejesus@gmail.com', 'CORE Multistore');
    $mail->addAddress($correo, $usuario['nombre_usuario']);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de contraseña - CORE Multistore';
    $mail->Body = "
    <html>
    <head>
        <title>Recuperación de contraseña</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #1A2B4A; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f5f5f5; }
            .password { background: #fff; padding: 15px; text-align: center; font-size: 28px; font-weight: bold; letter-spacing: 2px; border-radius: 10px; margin: 20px 0; border: 1px solid #ddd; }
            .footer { text-align: center; padding: 15px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>CORE Multistore</h2>
                <p>Sistema de Gestión de Tiendas</p>
            </div>
            <div class='content'>
                <h3>Hola {$usuario['nombre_usuario']}</h3>
                <p>Has solicitado restablecer tu contraseña. Aquí tienes tu nueva contraseña temporal:</p>
                <div class='password'>{$temp_password}</div>
                <p><strong> Importante:</strong></p>
                <ul>
                    <li>Al iniciar sesión, ve a la sección de <strong>Perfil</strong> para cambiar tu contraseña</li>
                    <li>No compartas esta contraseña con nadie</li>
                </ul>
                <p>Si no solicitaste este cambio, ignora este mensaje.</p>
            </div>
            <div class='footer'>
                <p>© 2024 CORE Multistore - Todos los derechos reservados</p>
                <p>Este es un mensaje automático, por favor no responder.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->send();
    echo json_encode(['ok' => true, 'msg' => 'Se envió una nueva contraseña temporal a tu correo']);
    
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'msg' => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
}
?>