<?php
if (empty($_GET['nombre'])) {
    $alerta = "¡No puede dejar el nombre en blanco!";
}

if (empty($_GET['contrasena'])) {
    $alerta = "¡No puede dejar la contraseña en blanco!";
}

if (isset($_GET['contrasena']) && isset($_GET['contrasena2']) && $_GET['nombre']) {
    if ($_GET['contrasena'] == $_GET['contrasena2'] && !empty($_GET['contrasena']) && !empty($_GET['contrasena2'])) {


        date_default_timezone_set('UTC');

        try {

            // Crea la conección a la base de datos
            $file_db = new PDO('sqlite:database/usuarios.db');
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ingresa los valores que el usuario digitó dentro del array            
            $usuarios = array(
                array('nombre' => $_GET['nombre'],
                    'contrasena' => $_GET['contrasena'])
            );

            //Inserta los valores
            $insert = "INSERT INTO usuarios (nombre, contrasena) 
                VALUES (:nombre, :contrasena)";

            $stmt = $file_db->prepare($insert);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contrasena', $contrasena);

            foreach ($usuarios as $m) {
    
                $nombre = $m['nombre'];
                $contrasena = $m['contrasena'];

                $stmt->execute();
            }


            //Cierra la conección
            $file_db = null;

        } catch (PDOException $e) {
            
            echo ' Problema con nombre de usuario ';
        }
        
        if (!mkdir('archivos/' . $_GET['nombre'] . '/', 0777, true)) {
            $alerta = "Hay un usuario con ese nombre registrado, ¡utilice otro!";
        }
        else
        {
            $success = "¡Cuenta creada correcctamente!";
        }
    } else
        $alerta = "¡Las contraseñas no coinciden! Por favor, vuelva a digitarlas";
}
?>

<html>
    <head> 
        <link href="estilos/login.css" rel="stylesheet" type="text/css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pantalla para crear cuenta </title> 
    </head>
    <body>

        <div class="container">

            <form action="" method="GET" id="inicio">

                <div class="header">

                    <h3>Crea tu cuenta</h3>

                    <p>Digita un nombre de usuario y una contraseña</p>

                </div>

                <div class="sep"></div>

                <div class="inputs">

                    <input type="text" name="nombre" placeholder="Nombre de usuario" autofocus />

                    <input type="password" name="contrasena" placeholder="Contraseña" />

                    <input type="password" name="contrasena2" placeholder="Confirmar contraseña" />	

                    <input type="submit" id="submit" name="Incio" value = "Registrar" />

                    <p>¿Ya te has registrado? <a href="login.php">Inicia Sesión</a></p>		

                    <?php
                    if (isset($alerta)) {
                        echo '<p style= "color:red">' . $alerta . '</p>';
                    }
                    if (isset($success)) {
                        echo '<p style= "color:green">' . $success . '</p>';
                    }
                    ?>

                </div>

            </form>

        </div>

    </body>

</html>