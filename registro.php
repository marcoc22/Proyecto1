<?php 

$contador = 0;

$directorio = "cuentas/cuentas.txt";

session_start();

if(!file_exists($directorio))
{
   $_SESSION['contador'] = 0;
   session_destroy();
}	

//Establece un contador para el registro
if(isset($_SESSION["contador"])){
	$contador = $_SESSION['contador'];
}	
	

if (file_exists($directorio))
  $archivo = fopen($directorio, "r+");
else
  $archivo = fopen($directorio, "a+");



if(empty($_GET['nombre']))
	$alerta = "¡No puede dejar el nombre en blanco!";
	
if(empty($_GET['contrasena']))
	$alerta = "¡No puede dejar la contraseña en blanco!";

if(isset($_GET['contrasena']) && isset($_GET['contrasena2']) && $_GET['nombre'])
{
	if($_GET['contrasena'] == $_GET['contrasena2'] && !empty($_GET['contrasena']) && !empty($_GET['contrasena2']) )
	{	
        
		$datos = array(str_pad($_GET['nombre'],30),
					   str_pad($_GET['contrasena'],30));
					   
		fseek($archivo,$contador*60,SEEK_SET);
        fwrite($archivo,implode(',',$datos));
        $array[$contador] = $datos;
	    $_SESSION['contador']= $contador + 1; 
	}
	else
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
            ?>
        
		</div>

    </form>

</div>

</body>

</html>
