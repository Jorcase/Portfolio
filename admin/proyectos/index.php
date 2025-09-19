<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener todos los proyectos con sus tecnologías
$sql = "SELECT p.*, 
               GROUP_CONCAT(t.name_tecnologia SEPARATOR ', ') as tecnologias
        FROM proyectos p 
        LEFT JOIN proyectos_tecnologias pt ON p.id_project = pt.id_project 
        LEFT JOIN tecnologias t ON pt.id_tecnologia = t.id_tecnologia 
        GROUP BY p.id_project 
        ORDER BY p.id_project DESC";
$stmt = $conexion->query($sql);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Proyectos</title>
    <style>
        .tecnologias {
            font-size: 12px;
            color: #666;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <h1>Gestionar Proyectos</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success'] ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="crear.php">➕ Crear Nuevo Proyecto</a>
    <a href="../index.php">← Volver al Panel</a>
    
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Tecnologías</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proyectos as $proyecto): ?>
            <tr>
                <td><?= $proyecto['id_project'] ?></td>
                <td><?= htmlspecialchars($proyecto['name_project']) ?></td>
                <td><?= htmlspecialchars($proyecto['description']) ?></td>
                <td class="tecnologias">
                    <?php if (!empty($proyecto['tecnologias'])): ?>
                        <?= htmlspecialchars($proyecto['tecnologias']) ?>
                    <?php else: ?>
                        <em>Sin tecnologías asignadas</em>
                    <?php endif; ?>
                </td>
                <td><?= $proyecto['state'] ? '✅ Activo' : '⏸️ Inactivo' ?></td>
                <td>
                    <a href="editar.php?id=<?= $proyecto['id_project'] ?>">✏️ Editar</a>
                    <a href="eliminar.php?id=<?= $proyecto['id_project'] ?>" 
                       onclick="return confirm('¿Eliminar este proyecto?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>