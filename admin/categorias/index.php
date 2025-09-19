<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener todas las categorías
$sql = "SELECT * FROM categorias_tecnologias ORDER BY nombre_categoria";
$stmt = $conexion->query($sql);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Categorías</title>
</head>
<body>
    <h1>Gestionar Categorías de Tecnologías</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success'] ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="crear.php">➕ Crear Nueva Categoría</a>
    <a href="../index.php">← Volver al Panel</a>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?= $categoria['id_categoria'] ?></td>
                <td><?= htmlspecialchars($categoria['nombre_categoria']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $categoria['id_categoria'] ?>">✏️ Editar</a>
                    <a href="eliminar.php?id=<?= $categoria['id_categoria'] ?>" 
                       onclick="return confirm('¿Eliminar esta categoría? Se eliminarán todas las tecnologías asociadas.')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>