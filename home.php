<?php
session_start();
$success = '';
if (!isset($_SESSION['usuario'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
$contenidoPath = "database/contenido.txt";
$indicePath = "database/indice.txt";
$modoEdicion = false;
$modoGuardar = FALSE;
$arrayActual = array();

echo exec($contenidoPath);

/*
  $filas = file($contenidoPath);
  $ultima_linea = $filas[count($filas)-1];
  echo $ultima_linea;
 */
//************************************GUARDANDO ENTRADA****************************************
if (isset($_POST['nuevo'])) {
    $divStyle = "display:none;";
    $divStyle2 = "display:none;";
    $divStyleGuardar = "display:block;";
} else if (isset($_POST['guardar'])) {//vamos a guardar los datos del formulario en el archivo y la carpeta que corresponde al usuario logueado
    $modoGuardar = TRUE;
    $divStyle = "display:none;";
    $divStyle2 = "display:none;";
    $divStyleGuardar = "display:block;";
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
                fseek($detalleArchivo, intval($filaUtilizable[1])); //puntero al inicio de ese dato
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


//*********************************LEYENDO ENTRADA EDITAR****************************************
else if (isset($_POST['editar'])) {

    $divStyle = "display:block;";
    $divStyle2 = "display:none;";
    $divStyleGuardar = "display:none;";

    $actual = (int) $_POST['id'];
    $detalleIndexUpdate = fopen($indicePath, "r+");
    $detalleArchivoUpdate = fopen($contenidoPath, "r+");

//Leo los valores del actual

    while (!feof($detalleIndexUpdate)) {

        $line = fgets($detalleIndexUpdate);
        if (!empty($line)) {
            $lines = explode(";", $line);
            foreach ($lines as $curLine) {
                if (!empty($curLine)) {
                    $datos = explode(",", $curLine);
                    if ($datos[0] == $actual) {
                        $datosActuales = $datos;
                        $lineaDato = (int) $datos[1]; //donde inicia
                        fseek($detalleArchivoUpdate, $lineaDato);
                        $fila = fread($detalleArchivoUpdate, intval($datos[2]));
                        array_push($arrayActual, explode(",", $fila));
                    }
                }
            }
        }
    }

    /*
     Esto de aquí era una prueba, no le haga caso XD
      $arrayPivote = array_values($arrayActual);

      while (count($arrayPivote) > 0) {
      $cambioActual = array_pop($arrayPivote);
      }

      $nombreActual = $cambioActual[1];
      $autorActual = $cambioActual[2];
      $fechaActual = $cambioActual[3];
      $clasActual = $cambioActual[5];
      $descripActual = $cambioActual[6];
     */
    //Obtiene los valores del form , (este código lo tomé prestado de abajo)
    if (isset($_POST['editando'])) {
        
    $nombreActualizar = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $autorActualizar = isset($_POST['autor']) ? $_POST['autor'] : '';
    $fechaActualizar = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $clasificacionActualizar = isset($_POST['clasificacion']) ? $_POST['clasificacion'] : '';
    $descripcionActualizar = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

    $datoActualizar = "{$nombreActualizar},{$autorActualizar},{$fechaActualizar},,{$clasificacionActualizar},{$descripcionActualizar},";
    $tamanioContenido = strlen($datoActualizar);
    $lineaIndexActualizar = "{$datosActuales[0]},{$datosActuales[1]},{$tamanioContenido},1;";
    
    //Si los datos que vienen en el form son menores al tamaño actual inserte
    if ($datosActuales[2] > $tamanioContenido) {

    //El puntero ya se encuentra en la posición que necesito por el fseek pasado
        fwrite($detalleIndexUpdate, $lineaIndexActualizar); //se almacena: id, inicio, tam del contenido, 1 o 0 si esta activo
        fwrite($detalleArchivoUpdate, $datoActualizar);
    //Si no entonces bórreme el valor de forma lógica y luego insérte al final del archivo   
    } else {

        //"Llamo" al método eliminar para que lo edite 
        while (!feof($detalleIndexUpdate)) {

            $linea = fgets($detalleIndexUpdate);
            $nuevasLineas = "";
            if (!empty($linea)) {
                $lineas = explode(";", $linea);
                foreach ($lineas as $lineaActual) {
                    if (!empty($lineaActual)) {
                        $datos = explode(",", $lineaActual);
                        if ($datos[0] == $actual) {
                            $datos[3] = 0;
                        }
                        $nuevaLinea = implode(",", $datos) . ';';
                        $nuevasLineas .= $nuevaLinea;
                    }
                }unset($lineaActual);
            }
        }
        
        fclose($detalleIndexUpdate);
        fclose($detalleArchivoUpdate);
        
        //Esto ingresa los valores al final
        $detalleIndexLineaFinal = fopen($indicePath, "a+");
        $detalleArchivoLineaFinal = fopen($contenidoPath, "a+");
        
        //Ya borrado lo inserta al final
        fwrite($detalleArchivoLineaFinal, $datoActualizar);
        fwrite($detalleArchivoIndexFinal, $lineaIndexActualizar);
        
        fclose($detalleArchivoLineaFinal);
        fclose($detalleArchivoIndexFinal);
    }    
    }
} else {
    $divStyle = "display:none;";
    $divStyle2 = "display:block;";
    $divStyleGuardar = "display:none;";
}




//***********************************ELIMINANDO ENTRADA***************************************
if (isset($_POST['eliminar'])) {
    $actual = (int) $_POST['id'];
    $detalleIndexUpdate = fopen($indicePath, "r+");
    $pos = 0;
    while (!feof($detalleIndexUpdate)) {

        $line = fgets($detalleIndexUpdate);
        $nuevasLineas = "";
        if (!empty($line)) {
            $lines = explode(";", $line);
            foreach ($lines as $curLine) {
                if (!empty($curLine)) {
                    $datos = explode(",", $curLine);
                    if ($datos[0] == $actual) {
                        $datos[3] = 0;
                    }
                    $nuevaLinea = implode(",", $datos) . ';';
                    $nuevasLineas .= $nuevaLinea;
                }
            }unset($curLine);
        }
    }
    rewind($detalleIndexUpdate);
    fwrite($detalleIndexUpdate, $nuevasLineas);
    fclose($detalleIndexUpdate);
}

//**************************************LEYENDO ENTRADAS**************************************
$detalleArchivo = fopen($contenidoPath, "r");
$detalleIndex = fopen($indicePath, "r");
$arrayIndex = array();
$arrayContenido = array();
$ultimaLinea = 0;

while (!feof($detalleIndex)) {
    $line = fgets($detalleIndex);
    if (!empty($line)) {
        $lines = explode(";", $line);
        foreach ($lines as $curLine) {
            if (!empty($curLine)) {
                $datos = explode(",", $curLine);
                if ($datos[3] == 1) {
                    $arrayIndex[$datos[0]] = $datos;
                    $lineaDato = (int) $datos[1]; //donde inicia
                    fseek($detalleArchivo, $lineaDato);
                    $fila = fread($detalleArchivo, intval($datos[2])); //leer los bytes necesarios
                    array_push($arrayContenido, explode(",", $fila));
                }
                $ultimaLinea++;
            }
        }unset($curLine);
    }
    unset($line);
}

fclose($detalleArchivo);
fclose($detalleIndex);

echo gettype($descripActual);
echo $tamanioContenido;
var_dump($datosActuales);

include('comun/header.php');
?>

<div class="container" style="<?php echo $divStyle ?>">
    <a href="home.php">Volver al listado</a>
    <div class="form-style-6" align="center">
        <h1>Datos de mp3</h1>
        <font color="<?php echo isset($alerta) ? 'red' : 'green'; ?>"><?php echo isset($alerta) ? $alerta : $success; ?></font>
<?php
while (count($arrayActual) > 0) {
    $filaActual = array_pop($arrayActual);
}
?>
        <form action='' method='post' enctype='multipart/form-data'>
            <input type="text" id="nombre" value="<?php echo $filaActual[1]; ?>" name="nombre" placeholder="Nombre" required>
            <!--<input type="file" id="archivo" name="archivo"  accept=".mp3" required>-->
            <input type="text" id="autor" name="autor" value ="<?php echo $filaActual[2]; ?>"placeholder="Autor" required>
            <input type="date" id="fecha" name="fecha" value="<?php echo $filaActual[3]; ?>" placeholder="Fecha" required >
            <input type="text" id="clasificacion" name="clasificacion" value="<?php echo $filaActual[5]; ?>"  placeholder='Clasificación' required>
            <input type="text" name="descripcion" value="<?php echo $filaActual[6]; ?>" placeholder="Descripción" required>
            <input type="submit" name="editarlo" value="Editar">
        </form>
    </div>
</div>
<div class="container" style="<?php echo $divStyleGuardar ?>">
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
<div class="container-list" style="<?php echo $divStyle2 ?>">
    <form method="POST">
        <input type="submit" name="nuevo" value="Agregar Nuevo">
    </form>
    <div class="form-style-8" align="center">
        <h1>Mis archivos mp3</h1>
        <table width='100%'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Fecha</th>
                    <th>Tamaño</th>
                    <th>Descripción</th>
                    <th>Clasificación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
<?php
while (count($arrayContenido) > 0) {

    $btnEliminar = "<input type='submit' name='eliminar' value='Eliminar'>";
    $btnEditar = "<input type='submit' name='editar' value='Editar'>";
    $btnMp3 = '<input type="file" name="archivo"  accept=".mp3" style="width:134px;">';
    $btnMp3 .= '<input type="submit" name="subir" value="Subir mp3" >';
    $row = array_pop($arrayContenido);
    echo '<tr>';

    echo "<td>{$row[1]}</td>";
    echo "<td>{$row[2]}</td>";
    echo "<td>{$row[3]}</td>";
    echo "<td>{$row[4]}</td>";
    echo "<td>{$row[5]}</td>";
    echo "<td>{$row[6]}</td>";
    echo '<td>';
    echo "<form action='' method='post' enctype='multipart/form-data'>";
    echo strlen($row[7]) <= 2 ? $btnMp3 : "<span>{$row[7]}</span>";
    echo $btnEditar;
    echo $btnEliminar;
    echo '<input type="hidden" name="id" value="' . $row[0] . '" >';
    echo "</form>";
    echo '</td>';
    echo '</tr>';
}
?>
            </tbody>
        </table>
    </div>
</div>
                <?php
                include('comun/footer.php');
                