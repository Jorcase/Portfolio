<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

// Obtener la categoría a editar
$id = $_GET['id'] ?? 0;
$categoria = null;

if ($id) {
    $sql = "SELECT * FROM categorias_tecnologias WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$categoria) {
    $_SESSION['error'] = "Categoría no encontrada";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
</head>
<body>
    <h1>Editar Categoría</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="index.php">← Volver a Categorías</a>
    
    <form action="guardar.php" method="POST">
        <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
        
        <div>
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" id="nombre_categoria" name="nombre_categoria" 
                   value="<?= htmlspecialchars($categoria['nombre_categoria']) ?>" 
                   required maxlength="100">
        </div>
        
        <button type="submit">Actualizar Categoría</button>
    </form>
</body>
</html>