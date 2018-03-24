<?php
date_default_timezone_set('UTC');

try {

    // Crea la conexión con la base de datos
    $file_db = new PDO('sqlite:database/usuarios.db');
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Selecciona todos los archivos que se encuentran en la tabla
    $result = $file_db->query('SELECT * FROM usuarios');
    $arrayNombres[] = "";
    $arrayContrasenas[] = "";
    $i = 0;

    //Mete los valores en los array de nombres y contraseñas
    foreach ($result as $row) {
        $arrayNombres[$i] = $row['nombre'];
        $arrayContrasenas[$i] = $row['contrasena'];
        $i++;
    }

    //Esto es para saber las contraseñas y los perfiles, luego lo puede quitar :v
    //print_r($arrayNombres);
    //print_r($arrayContrasenas);
} catch (PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}

if (isset($_GET['nombre']) && isset($_GET['contrasena']) && isset($_GET['Inicio'])) {
    for ($j = 0; $j < sizeof($arrayNombres); $j++) { {
            if ($_GET['nombre'] == $arrayNombres[$j] && $_GET['contrasena'] == $arrayContrasenas[$j]) {
                session_start();
                $_SESSION['usuario'] = $_GET['nombre'];
                header('Location: home.php');
                //Cierra la conexión con la base
                $file_db = null;
                break;
            } else {
                $alerta = "Nombre de usuario o contraseña incorrecta. ¡Ingréselos nuevamente!";
            }
        }
    }
}
?>

<html>
    <head> 
        <link href="estilos/login.css" rel="stylesheet" type="text/css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pantalla de inicio de sesión</title> 
    </head>
    <body>

        <div class="container">

            <form action="" method="GET" id="inicio">

                <div class="header">

                    <h3>Iniciar sesión</h3>

                    <p>Introduce los datos de cuenta</p>

                </div>

                <div class="sep"></div>

                <div class="inputs">

                    <input type="text" name="nombre" placeholder="Nombre de usuario" autofocus />

                    <input type="password" name = "contrasena" placeholder="Contraseña" />

                    <p>¿No tienes cuenta aún? <a href="registro.php">Registrate</a></p>		

                    <input type="submit" id="submit" name="Inicio" value = "Iniciar sesión" />

                    <?php
                    if (isset($alerta)) {
                        echo '<p style= "color:red">' . $alerta . '</p>';
                    }
                    ?>

                </div>

            </form>

        </div>

    </body>

</html>
