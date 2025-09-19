<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener todos los mensajes
$sql = "SELECT * FROM mensajes ORDER BY fecha DESC";
$stmt = $conexion->query($sql);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes Recibidos</title>
</head>
<body>
    <h1>Mensajes Recibidos</h1>
    
    <a href="../index.php">← Volver al Panel</a>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Motivo</th>
                <th>Mensaje</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mensajes as $mensaje): ?>
            <tr>
                <td><?= $mensaje['id_mensaje'] ?></td>
                <td><?= htmlspecialchars($mensaje['nombre']) ?></td>
                <td><?= htmlspecialchars($mensaje['email']) ?></td>
                <td><?= htmlspecialchars($mensaje['motivo']) ?></td>
                <td><?= htmlspecialchars(substr($mensaje['mensaje'], 0, 50)) ?>...</td>
                <td><?= $mensaje['fecha'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>