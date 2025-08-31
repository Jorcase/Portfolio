<html>
    <head>
        <title>EJERCICIO: ANALISIS DE FORMULARIO</title>
    </head>
    <body>
        <h1>EJERCICIO: ANALISIS DE FORMULARIO</h1><br>
        <br>
        <h1>analisis de formularios (analisis.php)</h1>
        <?php
            if($_POST['gender'] == 0) {
                echo "Hola Don ";} 
            else {
                echo "Hola Doña ";
            }
            echo "<b>{$_POST['lastname']}</b>, encantado de saludarte.";
        ?>
    </body>
</html>