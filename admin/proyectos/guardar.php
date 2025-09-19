<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_project = $_POST['name_project'] ?? '';
    $description = $_POST['description'] ?? '';
    $state = $_POST['state'] ?? 0;
    $tecnologias = $_POST['tecnologias'] ?? [];
    
    // Validar datos
    if (empty($name_project) || empty($description)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
        header("Location: " . (isset($_POST['id_project']) ? "editar.php?id=" . $_POST['id_project'] : "crear.php"));
        exit;
    }
    
    try {
        $conexion->beginTransaction();
        
        if (isset($_POST['id_project'])) {
            // Editar proyecto existente
            $id = $_POST['id_project'];
            $sql = "UPDATE proyectos SET name_project = ?, description = ?, state = ? WHERE id_project = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$name_project, $description, $state, $id]);
            
            // Eliminar relaciones existentes
            $sql_delete = "DELETE FROM proyectos_tecnologias WHERE id_project = ?";
            $stmt = $conexion->prepare($sql_delete);
            $stmt->execute([$id]);
            
            $_SESSION['success'] = "Proyecto actualizado correctamente";
        } else {
            // Crear nuevo proyecto
            $sql = "INSERT INTO proyectos (name_project, description, state) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$name_project, $description, $state]);
            $id = $conexion->lastInsertId();
            
            $_SESSION['success'] = "Proyecto creado correctamente";
        }
        
        // Insertar nuevas relaciones con tecnologías
        if (!empty($tecnologias)) {
            $sql_insert = "INSERT INTO proyectos_tecnologias (id_project, id_tecnologia) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql_insert);
            
            foreach ($tecnologias as $tec_id) {
                $stmt->execute([$id, $tec_id]);
            }
        }
        
        $conexion->commit();
        header("Location: index.php");
        exit;
        
    } catch (PDOException $e) {
        $conexion->rollBack();
        $_SESSION['error'] = "Error al guardar el proyecto: " . $e->getMessage();
        header("Location: " . (isset($_POST['id_project']) ? "editar.php?id=" . $_POST['id_project'] : "crear.php"));
        exit;
    }
}