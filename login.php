<?php
$directorio = "cuentas/cuentas.txt";
$data = "";

$archivo = fopen($directorio, "r+");

while ($data = fread($archivo, 60)) {
    $array[] = explode(',', $data);
};

for ($i = 0; $i < sizeof($array); $i++) {
    for ($j = 0; $j < 2; $j++) {
        $array2[$i][$j] = trim($array[$i][$j], " ");
    }

    if (isset($_GET['nombre']) && isset($_GET['contrasena']) && isset($_GET['Inicio'])) {
        if ($_GET['nombre'] == $array2[$i][0] && $_GET['contrasena'] == $array2[$i][1]) {
            session_start();
            $_SESSION['usuario'] = $_GET['nombre'];
            header('Location: home.php');
            break;
        } else {
            $alerta = "Nombre de usuario o contraseña incorrecta. ¡Ingréselos nuevamente!";
        }
    }
}
?>

<html>
    <head> 
        <link href="estilos/login.css" rel="stylesheet" type="text/css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pantalla de inicio de Sesion </title> 
    </head>
    <body>

        <div class="container">

            <form action="" method="GET" id="inicio">

                <div class="header">

                    <h3>Iniciar de sesión</h3>

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
