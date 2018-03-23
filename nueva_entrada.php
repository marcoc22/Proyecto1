<?php
session_start();
if(isset($_POST['guardar'])){//vamos a guardar los datos del formulario en el archivo y la carpeta que corresponde al usuario logueado
    $nombre = isset($_POST['nombre'])?$_POST['nombre']:'';
    $autor = isset($_POST['autor'])?$_POST['autor']:'';
    $fecha = isset($_POST['fecha'])?$_POST['fecha']:'';
    $clasificacion = isset($_POST['clasificacion'])?$_POST['clasificacion']:'';
    $descripcion = isset($_POST['descripcion'])?$_POST['descripcion']:'';
    
    $alerta = '';
    if(empty($nombre)){
        $alerta .= 'El nombre no puede estar vacío<br>';
    }
}
//crear archivo txt

?>
<!DOCTYPE html>
<html>
    <head>
        <title>HOME - Nueva entrada</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="estilos/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <nav class="header-nav">
                <ul>
                    <li><a class="active" href="home.php">Home</a></li>
                    <li><a href="ayuda.php">Ayuda</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <div class="container">
                <a href="home.php">Volver al listado</a>
                <div class="form-style-6" align="center">
                    <h1>Contact Us</h1>
                    <form action='' method='post' enctype='multipart/form-data'>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre">
                        <input type="file" id="archivo" name="archivo"  accept=".mp3">
                        <input type="text" id="autor" name="autor" placeholder="Autor">
                        <input type="date" id="fecha" name="fecha" placeholder="Fecha">
                        <input type="text" id="clasificacion" name="clasificacion">
                        <textarea name="descripcion" placeholder="Descripción"></textarea>
                        <input type="submit" name="guardar" value="Guardar" />
                    </form>
                </div>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>

