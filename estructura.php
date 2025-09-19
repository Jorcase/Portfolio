<?php
// Ver estructura de la base de datos
require __DIR__ . '/php/conexion.php';

echo "<h3>📊 Tablas en tu base de datos:</h3>";
$stmt = $conexion->query("SHOW TABLES");
$tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($tablas as $tabla) {
    echo "<h4>Tabla: $tabla</h4>";
    
    $stmt2 = $conexion->query("DESCRIBE $tabla");
    $columnas = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
    echo "<tr><th>Columna</th><th>Tipo</th><th>Nulo</th><th>Llave</th></tr>";
    foreach ($columnas as $columna) {
        echo "<tr>
                <td>{$columna['Field']}</td>
                <td>{$columna['Type']}</td>
                <td>{$columna['Null']}</td>
                <td>{$columna['Key']}</td>
              </tr>";
    }
    echo "</table><br>";
}
?>