<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css" />
    <title>Insertar datos</title>
</head>
<body>
    <div id="main-container"> 

    <div class="box-titulo">
    <h1 class="titulo">TO-DO LIST</h1>
    </div>
        
    <div id="container-to-do-list">
    <div class="add-input">
        <form class="main-form" action="index.php" method="POST">
            <input class="input-text" type="text" name="texto" id="texto" placeholder="Escribir tarea">
            <input class="input-submit" type="submit" value="Añadir">
        </form>
    </div> 
    
    <div id="mostrar-todo-conatiner">
        <form id="formMostrarTodo" method="POST" action="index.php">
            <input class="input-checkbox" type="checkbox" name="mostrar-todo" onchange="mostrarTodo(this)" 
            <?php if(isset($_POST['mostrar-todo'])){
                if($_POST['mostrar-todo']=="on"){
                    echo " checked";
                }
            } ?> > Mostrar todo 
            
        </form>
    </div>
    </div>

    <div id="todolist"> 
        <?php
            //Conexión a la DB
            $servidor = "localhost:3306";
            $nombre = "root";
            $password = "Guarmi258";
            $db = "todolistDB";
            
            $conexion = new mysqli($servidor, $nombre, $password, $db);
            
            if($conexion->connect_error){
                die("conexion fallida: " . $conexion->connect_error);
            }

            // Validación de datos de entrada
            if(isset($_POST['texto'])){
                $texto = $_POST['texto'];
                if($texto != ""){
                    $sql = "INSERT INTO todoTable(texto, completado) VALUES ('$texto', false)";

                if ($conexion->query($sql)=== true) {
                    // correcto
                }else {
                    die("error al insertar datos" . $conexion->error);
                }
                }
                
            }
            
            // Actualizar datos
            else if(isset($_POST['completar'])){ 
                $id = $_POST['completar'];

                $sql ="UPDATE todoTable SET completado = 1 WHERE id = $id";

                if ($conexion->query($sql)=== true) {
                    // correcto
                }else {
                    die("error al actualizar los datos" . $conexion->error);
                }
            }
            
            // Eliminar datos
            else if(isset($_POST['eliminar'])) {
                $id = $_POST['eliminar'];

                $sql ="DELETE FROM todoTable WHERE id = $id";

                if ($conexion->query($sql)=== true) {
                    // Correcto
                }else {
                    die("error al actualizar los datos" . $conexion->error);
                }
            }

            // Ordenar datos
            $sql = "";
            if (isset($_POST['mostrar-todo'])) {
                $ordenar = $_POST['mostrar-todo'];

                if ($ordenar == "on") {
                    $sql = "SELECT * FROM todoTable ORDER BY completado DESC";
                }
            }else {
                $sql = "SELECT * FROM todoTable WHERE completado = 0";
            }

            // Obtener datos de la tabla
            $resultado = $conexion->query($sql);

            if($resultado->num_rows > 0){
                while($row = $resultado->fetch_assoc()){
                ?>

                <div class="pendiente">
                <form method="POST" class="form_actualizar" id="form<?php echo $row['id']; ?>">
                        <input class="input-checkbox" name ="completar" value="<?php echo $row['id']; ?>" id="<?php echo $row['id']; ?>" type="checkbox" onchange="completarPendiente(this)" <?php if($row['completado'] == 1) echo " checked disabled"; ?>><div class="texto <?php if($row['completado'] == 1) echo " deshabilitado"; ?>"><?php echo $row['texto'];?></div>
                    </form>

                    <form method="POST" class="form_eliminar">
                        <input class="input-eliminar" type="hidden" name="eliminar" value="<?php echo $row['id']; ?>"  />
                        <input class="input-eliminar"type="submit" value="Eliminar">
                    </form>

                </div>
                <?php

                }
                ?>
                <div class="contador-tareas">
                    <?php echo "Tareas: " . $resultado->num_rows; ?>
                </div>
                <?php
            }

            $conexion->close();

        ?>
        
    </div>

    <!-- /container -->
    </div>

    <script>
        function completarPendiente(e) {
            var id = "form" + e.id;
            var formulario = document.getElementById(id);
            formulario.submit();
            //console.log(id);
        }

        function mostrarTodo(e) {
            var formulario = document.getElementById("formMostrarTodo");
            formulario.submit();
        }
    </script>

</body>
</html>