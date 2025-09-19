<?php
// Incluir la conexión primero
require_once 'conexion.php';
require_once 'header.php';

$sql = "SELECT * FROM users";
$result = $con->query($sql);

if($result->num_rows > 0) {
?>
<div class="container">
    <h2>Listado de usuarios</h2>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Dirección</th>
            <th>Contacto</th>
            <th width="70px">Borrar</th>
            <th width="70px">Editar</th>
        </tr>
        <?php
        while($row = $result->fetch_assoc()) {
            echo "<form action='delete.php' method='POST'>";
            echo "<input type='hidden' value='".$row['id']."' name='userid' />";
            echo "<tr>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".$row['address']."</td>";
            echo "<td>".$row['contact']."</td>";
            echo "<td><input type='submit' name='delete' value='Borrar' class='btn btn-danger' /></td>";
            echo "<td><a href='edit.php?id=".$row['id']."' class='btn btn-info'>Editar</a></td>";
            echo "</tr>";
            echo "</form>";
        }
        ?>
    </table>
</div>
<?php
} else {
    echo "<div class='container'><br><br>No se encontraron registros</div>";
}

require_once 'footer.php';
?>