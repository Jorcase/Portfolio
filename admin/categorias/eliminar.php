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
        // Primero verificar si hay tecnologías asociadas
        $sql_check = "SELECT COUNT(*) as count FROM tecnologias WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql_check);
        $stmt->execute([$id]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($count > 0) {
            $_SESSION['error'] = "No se puede eliminar la categoría porque tiene tecnologías asociadas";
            header("Location: index.php");
            exit;
        }
        
        // Si no hay tecnologías asociadas, eliminar la categoría
        $sql_delete = "DELETE FROM categorias_tecnologias WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql_delete);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Categoría eliminada correctamente";
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al eliminar la categoría: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID de categoría no válido";
}

header("Location: index.php");
exit;