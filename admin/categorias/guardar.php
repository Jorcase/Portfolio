<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_categoria = trim($_POST['nombre_categoria'] ?? '');
    
    // Validar datos
    if (empty($nombre_categoria)) {
        $_SESSION['error'] = "El nombre de la categoría es obligatorio";
        header("Location: " . (isset($_POST['id_categoria']) ? "editar.php?id=" . $_POST['id_categoria'] : "crear.php"));
        exit;
    }
    
    try {
        if (isset($_POST['id_categoria'])) {
            // Editar categoría existente
            $id = $_POST['id_categoria'];
            $sql = "UPDATE categorias_tecnologias SET nombre_categoria = ? WHERE id_categoria = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre_categoria, $id]);
            $_SESSION['success'] = "Categoría actualizada correctamente";
        } else {
            // Crear nueva categoría
            $sql = "INSERT INTO categorias_tecnologias (nombre_categoria) VALUES (?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre_categoria]);
            $_SESSION['success'] = "Categoría creada correctamente";
        }
        
        header("Location: index.php");
        exit;
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al guardar la categoría: " . $e->getMessage();
        header("Location: " . (isset($_POST['id_categoria']) ? "editar.php?id=" . $_POST['id_categoria'] : "crear.php"));
        exit;
    }
}