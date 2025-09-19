<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener la tecnología a editar
$id = $_GET['id'] ?? 0;
$tecnologia = null;

if ($id) {
    $sql = "SELECT * FROM tecnologias WHERE id_tecnologia = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $tecnologia = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$tecnologia) {
    $_SESSION['error'] = "Tecnología no encontrada";
    header("Location: index.php");
    exit;
}

// Obtener categorías para el select
$sql_categorias = "SELECT * FROM categorias_tecnologias ORDER BY nombre_categoria";
$categorias = $conexion->query($sql_categorias)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tecnología</title>
</head>
<body>
    <h1>Editar Tecnología</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="index.php">← Volver a Tecnologías</a>
    
    <form action="guardar.php" method="POST">
        <input type="hidden" name="id_tecnologia" value="<?= $tecnologia['id_tecnologia'] ?>">
        
        <div>
            <label for="name_tecnologia">Nombre de la Tecnología:</label>
            <input type="text" id="name_tecnologia" name="name_tecnologia" 
                   value="<?= htmlspecialchars($tecnologia['name_tecnologia']) ?>" 
                   required maxlength="60">
        </div>
        
        <div>
            <label for="version">Versión:</label>
            <input type="text" id="version" name="version" 
                   value="<?= htmlspecialchars($tecnologia['version']) ?>" 
                   maxlength="10" placeholder="Opcional">
        </div>
        
        <div>
            <label for="id_categoria">Categoría:</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" 
                        <?= $categoria['id_categoria'] == $tecnologia['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit">Actualizar Tecnología</button>
    </form>
</body>
</html>