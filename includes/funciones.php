<?php
// includes/funciones.php

function obtenerProyectos($conexion) {
    $sql = "SELECT p.*, GROUP_CONCAT(t.name_tecnologia SEPARATOR ', ') as tecnologias 
            FROM proyectos p 
            LEFT JOIN proyectos_tecnologias pt ON p.id_project = pt.id_project 
            LEFT JOIN tecnologias t ON pt.id_tecnologia = t.id_tecnologia 
            GROUP BY p.id_project";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerTecnologias($conexion) {
    $sql = "SELECT t.*, c.nombre_categoria 
            FROM tecnologias t 
            JOIN categorias_tecnologias c ON t.id_categoria = c.id_categoria 
            ORDER BY c.nombre_categoria, t.name_tecnologia";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerHabilidades($conexion) {
    // Agrupar tecnologías por categoría
    $tecnologias = obtenerTecnologias($conexion);
    $habilidades = [];
    
    foreach ($tecnologias as $tec) {
        $categoria = $tec['nombre_categoria'];
        if (!isset($habilidades[$categoria])) {
            $habilidades[$categoria] = [];
        }
        $habilidades[$categoria][] = $tec['name_tecnologia'] . ($tec['version'] ? ' ' . $tec['version'] : '');
    }
    
    return $habilidades;
}
?>