<?php
// Mostrar errores en desarrollo (⚠️ desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/conexion.php';

// Sesión para el token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Todas las respuestas serán JSON
header('Content-Type: application/json; charset=utf-8');

// Cargar variables de entorno (.env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Acceso inválido"]);
    exit;
}

// =========================
// 1) Validación token único
// =========================
$token = $_POST['token'] ?? '';
$sessionToken = $_SESSION['form_token'] ?? '';

if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
    echo json_encode([
        "success" => false,
        "message" => "Formulario inválido o ya enviado."
    ]);
    exit;
}
unset($_SESSION['form_token']); // consumir token

// =========================
// 2) Tomar datos del form
// =========================
$nombre   = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$email    = trim($_POST['email'] ?? '');
$motivo   = trim($_POST['motivo'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

// Validaciones mínimas
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($mensaje) < 10) {
    echo json_encode([
        "success" => false,
        "message" => "Datos inválidos."
    ]);
    exit;
}

// =========================
// 3) Guardar en la BD
// =========================
$nombre_completo = $nombre . " " . $apellido;
$sql = "INSERT INTO mensajes (nombre, email, motivo, mensaje) 
        VALUES (:nombre, :email, :motivo, :mensaje)";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':nombre'  => $nombre_completo,
    ':email'   => $email,
    ':motivo'  => $motivo,
    ':mensaje' => $mensaje
]);
$stmt = null;

// =========================
// 4) Enviar email
// =========================
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)$_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['SMTP_USER'], 'Portfolio Web');
    $mail->addAddress($_ENV['SMTP_USER'], 'Yo'); 
    $mail->addReplyTo($email, "$nombre $apellido");

    $mail->isHTML(true);
    $mail->Subject = "Portfolio: $motivo";
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='color:#4CAF50;'>Contacto desde la Web</h2>
        <p><strong>De:</strong> ".htmlspecialchars($nombre)." ".htmlspecialchars($apellido)."</p>
        <p><strong>Email:</strong> ".htmlspecialchars($email)."</p>
        <p><strong>Motivo:</strong> ".htmlspecialchars($motivo)."</p>
        <p><strong>Mensaje:</strong><br>".nl2br(htmlspecialchars($mensaje))."</p>
    </div>
    ";

    $mail->send();

    echo json_encode([
        "success" => true,
        "message" => "¡Gracias por contactarte, $nombre!"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "No se pudo enviar el correo: {$mail->ErrorInfo}"
    ]);
}
