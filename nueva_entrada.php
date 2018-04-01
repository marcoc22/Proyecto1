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

    $detalleIndex = fopen($indicePath, "r+");
    $arrayIndex = array();
    $arrayDisponibles = array(); //almaceno los campos de archivo que fueron borrados pero puedo utilizarlos para almacenar
    $ultimaLinea = 0;
    $arrayIndicesUsados = array();
    while (!feof($detalleIndex)) {
        $line = fgets($detalleIndex);

        if (!empty($line)) {
            $lines = explode(";", $line);
            foreach ($lines as $curLine) {
                if (!empty($curLine)) {
                    $datos = explode(",", $curLine);
                    if ($datos[3] == 1) {
                        array_push($arrayIndicesUsados, $datos[0]);
                    }
                }
            }unset($curLine);
        }
        unset($line);
    }
    fclose($detalleIndex);
    $detalleIndex = fopen($indicePath, "r+");
    while (!feof($detalleIndex)) {
        $line = fgets($detalleIndex);

        if (!empty($line)) {
            $lines = explode(";", $line);
            foreach ($lines as $curLine) {
                if (!empty($curLine)) {
                    $datos = explode(",", $curLine);
                    if ($datos[3] == 1) {
                        $arrayIndex[$datos[0]] = $datos;
                    } else {
                        if (!in_array($datos[0], $arrayIndicesUsados)) {
                            array_push($arrayDisponibles, $datos);
                        }
                    }
                    $ultimaLinea++;
                }
            }unset($curLine);
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
        if (!isset($alerta)) {
            $datoLinea = "{$ultimaLinea},{$nombre},{$autor},{$fecha},,{$clasificacion},{$descripcion},";
            $tamContenido = strlen($datoLinea);

            $detalleArchivo = fopen($contenidoPath, "r+"); //abro en modo lectura para luego leer su ultima posicion
            $filaUtilizable = NULL;
            if (count($arrayDisponibles) > 0) {
                foreach ($arrayDisponibles as $filaDisponible) {
                    if (intval($filaDisponible[2]) > $tamContenido) {//se puede utilizar
                        $filaUtilizable = $filaDisponible;
                        break;
                    }
                }unset($filaDisponible);
            }
            if (!is_null($filaUtilizable)) {
                fseek($detalleArchivo,intval($filaUtilizable[1])); //puntero al inicio de ese dato
                $inicioContenido = ftell($detalleArchivo);
                $lineaIndex = "{$filaUtilizable[0]},{$inicioContenido},{$tamContenido},1;";
                $datoLinea = "{$filaUtilizable[0]},{$nombre},{$autor},{$fecha},,{$clasificacion},{$descripcion},";
            } else {
                fseek($detalleArchivo, 0, SEEK_END); //puntero al final del archivo
                $inicioContenido = ftell($detalleArchivo);
                $lineaIndex = "{$ultimaLinea},{$inicioContenido},{$tamContenido},1;";
            }
            fwrite($detalleIndex, $lineaIndex); //se almacena: id, inicio, tam del contenido, 1 o 0 si esta activo
            fwrite($detalleArchivo, $datoLinea);
            fclose($detalleArchivo);
            $success = 'Datos guardados correctamente.';
        }
    }

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
            <input type="date" id="fecha" name="fecha" placeholder="Fecha" required value="12/12/2017">
            <input type="text" id="clasificacion" name="clasificacion" placeholder='Clasificación' required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>
            <input type="submit" name="guardar" value="Guardar">
        </form>
    </div>
</div>
<?php
include('comun/footer.php');
