<?php
session_start();
$_SESSION['usuario'] = 'marco';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>HOME</title>
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
            <div>
                <a href="nueva_entrada.php">Agregar nueva entrada</a>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Autor</th>
                            <th>Fecha</th>
                            <th>Tamaño</th>
                            <th>Descripción</th>
                            <th>Clasificación</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </main>
        <footer>

        </footer>
    </body>
</html>

