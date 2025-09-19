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
        // Primero eliminar las relaciones con tecnologías
        $sql_relaciones = "DELETE FROM proyectos_tecnologias WHERE id_project = ?";
        $stmt = $conexion->prepare($sql_relaciones);
        $stmt->execute([$id]);
        
        // Luego eliminar el proyecto
        $sql_proyecto = "DELETE FROM proyectos WHERE id_project = ?";
        $stmt = $conexion->prepare($sql_proyecto);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Proyecto eliminado correctamente";
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al eliminar el proyecto: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID de proyecto no válido";
}

header("Location: index.php");
exit;