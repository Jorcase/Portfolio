<?php
// Permite acceso desde tu portfolio
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../conexion.php';

try {
    $sql = "SELECT p.*, GROUP_CONCAT(t.name_tecnologia SEPARATOR ', ') as tecnologias 
            FROM proyectos p 
            LEFT JOIN proyectos_tecnologias pt ON p.id_project = pt.id_project 
            LEFT JOIN tecnologias t ON pt.id_tecnologia = t.id_tecnologia 
            WHERE p.state = 1
            GROUP BY p.id_project
            ORDER BY p.id_project DESC";
    
    $stmt = $conexion->query($sql);
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($proyectos);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al cargar proyectos']);
}
?>