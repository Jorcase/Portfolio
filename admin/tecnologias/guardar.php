<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_tecnologia = $_POST['name_tecnologia'] ?? '';
    $version = $_POST['version'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? 0;
    
    // Validar datos
    if (empty($name_tecnologia) || empty($id_categoria)) {
        $_SESSION['error'] = "Nombre y categoría son obligatorios";
        header("Location: " . (isset($_POST['id_tecnologia']) ? "editar.php?id=" . $_POST['id_tecnologia'] : "crear.php"));
        exit;
    }
    
    try {
        if (isset($_POST['id_tecnologia'])) {
            // Editar tecnología existente
            $id = $_POST['id_tecnologia'];
            $sql = "UPDATE tecnologias SET name_tecnologia = ?, version = ?, id_categoria = ? WHERE id_tecnologia = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$name_tecnologia, $version, $id_categoria, $id]);
            $_SESSION['success'] = "Tecnología actualizada correctamente";
        } else {
            // Crear nueva tecnología
            $sql = "INSERT INTO tecnologias (name_tecnologia, version, id_categoria) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$name_tecnologia, $version, $id_categoria]);
            $_SESSION['success'] = "Tecnología creada correctamente";
        }
        
        header("Location: index.php");
        exit;
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al guardar la tecnología: " . $e->getMessage();
        header("Location: " . (isset($_POST['id_tecnologia']) ? "editar.php?id=" . $_POST['id_tecnologia'] : "crear.php"));
        exit;
    }
}