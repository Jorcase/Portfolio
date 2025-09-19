<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // Primero eliminar las relaciones con proyectos
        $sql_relaciones = "DELETE FROM proyectos_tecnologias WHERE id_tecnologia = ?";
        $stmt = $conexion->prepare($sql_relaciones);
        $stmt->execute([$id]);
        
        // Luego eliminar la tecnología
        $sql_tecnologia = "DELETE FROM tecnologias WHERE id_tecnologia = ?";
        $stmt = $conexion->prepare($sql_tecnologia);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Tecnología eliminada correctamente";
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al eliminar la tecnología: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID de tecnología no válido";
}

header("Location: index.php");
exit;