<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener todas las tecnologías agrupadas por categoría
$sql_tecnologias = "SELECT t.*, c.nombre_categoria 
                   FROM tecnologias t 
                   JOIN categorias_tecnologias c ON t.id_categoria = c.id_categoria 
                   ORDER BY c.nombre_categoria, t.name_tecnologia";
$tecnologias = $conexion->query($sql_tecnologias)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Proyecto</title>
</head>
<body>
    <h1>Crear Nuevo Proyecto</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="index.php">← Volver a Proyectos</a>
    
    <form action="guardar.php" method="POST">
        <div>
            <label for="name_project">Nombre del Proyecto:</label>
            <input type="text" id="name_project" name="name_project" required maxlength="60">
        </div>
        
        <div>
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" required maxlength="400"></textarea>
        </div>
        
        <div>
            <label for="state">Estado:</label>
            <select id="state" name="state" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        
        <div>
            <label>Tecnologías utilizadas:</label>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                <?php
                $categoria_actual = '';
                foreach ($tecnologias as $tec):
                    if ($categoria_actual != $tec['nombre_categoria']):
                        $categoria_actual = $tec['nombre_categoria'];
                        echo "<h4>" . htmlspecialchars($categoria_actual) . "</h4>";
                    endif;
                ?>
                    <label style="display: block; margin: 5px 0;">
                        <input type="checkbox" name="tecnologias[]" value="<?= $tec['id_tecnologia'] ?>">
                        <?= htmlspecialchars($tec['name_tecnologia']) ?>
                        <?= !empty($tec['version']) ? '(' . htmlspecialchars($tec['version']) . ')' : '' ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <button type="submit">Guardar Proyecto</button>
    </form>
</body>
</html>