<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $motivo = $_POST['motivo'];
    $mensaje = $_POST['mensaje'];

   
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';      // Servidor SMTP (ej: Gmail)
        $mail->SMTPAuth   = true;                  // Habilitar autenticación
        $mail->Username   = 'jorcascero@gmail.com'; 
        $mail->Password   = 'ohmrrfmcaqphsneg'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;                   

        // Remitente y destinatario
        $mail->setFrom('jorcascero@gmail.com', 'Portfolio Web');
        $mail->addAddress('jorcas02@hotmail.com', 'Profesor'); 
        $mail->addBCC('jorcascero@gmail.com', 'Yo');
        //Si quiero que responder se le envie a la persona que lleno el formulario
        $mail->addReplyTo($email, "$nombre $apellido"); 

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = "Portfolio: $motivo";
        $mail->Body    = "
            <h2>Contacto desde la Web</h2>
            <p><strong>De:</strong> $nombre $apellido</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Motivo:</strong> $motivo</p>
            <p><strong>Mensaje:</strong> $mensaje</p>
        ";

        $mail->send();
        echo "<h1>¡Gracias por contactarte, $nombre!</h1>";
        echo "<p>Tu mensaje fue enviado correctamente.</p>";
        echo '<p><a href="index.html">Volver al portfolio</a></p>';

    } catch (Exception $e) {
        echo "<h1>Error al enviar el correo</h1>";
        echo "Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    echo "<p>Error: No se recibieron datos del formulario.</p>";
    echo '<p><a href="index.html">Volver</a></p>';
}
?>
