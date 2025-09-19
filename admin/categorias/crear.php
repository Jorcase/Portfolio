<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría</title>
</head>
<body>
    <h1>Crear Nueva Categoría</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="index.php">← Volver a Categorías</a>
    
    <form action="guardar.php" method="POST">
        <div>
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" id="nombre_categoria" name="nombre_categoria" required maxlength="100">
        </div>
        
        <button type="submit">Guardar Categoría</button>
    </form>
</body>
</html>