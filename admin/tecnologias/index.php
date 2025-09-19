<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener todas las tecnologías con sus categorías
$sql = "SELECT t.*, c.nombre_categoria 
        FROM tecnologias t 
        JOIN categorias_tecnologias c ON t.id_categoria = c.id_categoria 
        ORDER BY c.nombre_categoria, t.name_tecnologia";
$stmt = $conexion->query($sql);
$tecnologias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Tecnologías</title>
</head>
<body>
    <h1>Gestionar Tecnologías</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success'] ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="crear.php">➕ Crear Nueva Tecnología</a>
    <a href="../index.php">← Volver al Panel</a>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Versión</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tecnologias as $tec): ?>
            <tr>
                <td><?= $tec['id_tecnologia'] ?></td>
                <td><?= htmlspecialchars($tec['name_tecnologia']) ?></td>
                <td><?= htmlspecialchars($tec['version']) ?></td>
                <td><?= htmlspecialchars($tec['nombre_categoria']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $tec['id_tecnologia'] ?>">✏️ Editar</a>
                    <a href="eliminar.php?id=<?= $tec['id_tecnologia'] ?>" onclick="return confirm('¿Eliminar esta tecnología?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>