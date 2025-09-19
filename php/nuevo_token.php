<?php
// php/nuevo_token.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar un token nuevo y guardarlo en sesión
$_SESSION['form_token'] = bin2hex(random_bytes(32));

// Devolver como JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(["token" => $_SESSION['form_token']]);
