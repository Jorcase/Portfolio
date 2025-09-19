<?php
require_once 'conexion.php'; // Incluir la conexión

if(isset($_POST['delete'])) {
    // Usar 'id' en lugar de 'user_id'
    $sql = "DELETE FROM users WHERE id = " . $_POST['userid'];
    
    if($con->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Usuario eliminado correctamente.</div>";
        // Redirigir después de eliminar
        header("Refresh: 2; URL=usuarios.php");
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar usuario: " . $con->error . "</div>";
    }
}
?>