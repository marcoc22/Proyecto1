<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>HOME - Nueva entrada</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="estilos/login.css" rel="stylesheet" type="text/css">
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
            <a href="home.php">Volver al listado</a>
            <form action='' method='post' enctype='multipart/form-data'>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre">
                
                <label for="autor">Autor</label>
                <input type="text" id="autor" name="autor">
                
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha">
                
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion">
                
                <label for="clasificacion">Clasificacion</label>
                <input type="text" id="clasificacion" name="clasificacion">
                
                <label for="archivo">Archivo</label>
                <input type="file" id="archivo" name="archivo"  accept=".mp3">
                
            </form>
        </main>
        <footer>

        </footer>
    </body>
</html>

