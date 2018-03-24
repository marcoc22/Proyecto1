<?php
session_start();
$success = '';
if (!isset($_SESSION['usuario'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
if (isset($_POST['guardar'])) {//vamos a guardar los datos del formulario en el archivo y la carpeta que corresponde al usuario logueado
    $contenidoPath = "database/contenido.txt";
    $indicePath = "database/indice.txt";

    $detalleArchivo = fopen($contenidoPath, "a+");
    $detalleIndex = fopen($indicePath, "a+");
    $arrayIndex = array();
    $ultimaLinea = 0;
    while (!feof($detalleIndex)) {
        $line = fgets($detalleIndex);
        if (!empty($line)) {
            $datos = explode(",", $line);
            if ($datos[1] == 1) {
                $arrayIndex[$datos[0]] = $datos;
            }
            $ultimaLinea++;
        }
        unset($line);
    }

    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $autor = isset($_POST['autor']) ? $_POST['autor'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $clasificacion = isset($_POST['clasificacion']) ? $_POST['clasificacion'] : '';
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';


    if (empty($nombre)) {
        $alerta .= 'El nombre no puede estar vacío<br>';
    } else {
        /* $dir_subida = 'archivos/'.$_SESSION['usuario'].'/';
          $fichero_subido = $dir_subida . basename($_FILES['archivo']['name']);
          
         
          if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)){
          $alerta .= 'El archivo .mp3 no pudo ser guardado.<br>';
          } */
        if (!isset($alerta)) {
            $datoLinea = "{$ultimaLinea},{$nombre},{$autor},{$fecha},,{$clasificacion},{$descripcion},\n";
            $lineaIndex = "{$ultimaLinea},1\n";
            fwrite($detalleIndex, $lineaIndex);
            fwrite($detalleArchivo, $datoLinea);
            $success = 'Datos guardados correctamente.';
        }
    }

    fclose($detalleArchivo);
    fclose($detalleIndex);
}
include('comun/header.php');
?>

<div class="container">
    <a href="home.php">Volver al listado</a>
    <div class="form-style-6" align="center">
        <h1>Datos de mp3</h1>
        <font color="<?php echo isset($alerta) ? 'red' : 'green'; ?>"><?php echo isset($alerta) ? $alerta : $success; ?></font>
        <form action='' method='post' enctype='multipart/form-data'>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
            <!--<input type="file" id="archivo" name="archivo"  accept=".mp3" required>-->
            <input type="text" id="autor" name="autor" placeholder="Autor" required>
            <input type="date" id="fecha" name="fecha" placeholder="Fecha" required>
            <input type="text" id="clasificacion" name="clasificacion" placeholder='Clasificación' required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>
            <input type="submit" name="guardar" value="Guardar">
        </form>
    </div>
</div>
<?php
include('comun/footer.php');
