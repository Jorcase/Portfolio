<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener categorías para el select
$sql_categorias = "SELECT * FROM categorias_tecnologias ORDER BY nombre_categoria";
$categorias = $conexion->query($sql_categorias)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Tecnología</title>
</head>
<body>
    <h1>Crear Nueva Tecnología</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="index.php">← Volver a Tecnologías</a>
    
    <form action="guardar.php" method="POST">
        <div>
            <label for="name_tecnologia">Nombre de la Tecnología:</label>
            <input type="text" id="name_tecnologia" name="name_tecnologia" required maxlength="60">
        </div>
        
        <div>
            <label for="version">Versión:</label>
            <input type="text" id="version" name="version" maxlength="10" placeholder="Opcional">
        </div>
        
        <div>
            <label for="id_categoria">Categoría:</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>">
                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit">Guardar Tecnología</button>
    </form>
</body>
</html>