<?php
require_once 'conexion.php';
require_once 'header.php';

// Procesar el formulario cuando se envía
if(isset($_POST['addnew'])){
    if( empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['address']) || empty($_POST['contact']) ){
        $error_message = "Debe completar todos los campos";
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        
        $sql = "INSERT INTO users(firstname, lastname, address, contact)
                VALUES('$firstname','$lastname','$address','$contact')";
        
        if($con->query($sql) === TRUE){
            $success_message = "Usuario agregado correctamente";
        } else {
            $error_message = "Error: Ocurrió un error mientras se agregaba el usuario";
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Agregar Usuario</h3>
                
                <?php if(isset($success_message)): ?>
                    <div class='alert alert-success'><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                    <div class='alert alert-danger'><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <label for="firstname">Nombre</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>"><br>
                    
                    <label for="lastname">Apellido</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>"><br>
                    
                    <label for="address">Dirección</label>
                    <textarea rows="4" name="address" class="form-control"><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea><br>
                    
                    <label for="contact">Contacto</label>
                    <input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : ''; ?>"><br>
                    
                    <br>
                    <input type="submit" name="addnew" class="btn btn-success" value="Agregar Usuario">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>