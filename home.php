<?php
session_start();

$contenidoPath = "database/contenido.txt";
$indicePath = "database/indice.txt";
$modoEdicion = false;

/*
*/

$arrayActual = array();

//************************************EDITANDO ENTRADA****************************************

if (isset($_POST['editar'])) {
    
    $divStyle="display:block;";
    $divStyle2="display:none;";
    
    
    $actual = (int) $_POST['id'];
    $detalleIndexUpdate = fopen($indicePath, "r+");
    $detalleArchivoUpdate = fopen($contenidoPath, "r+");

      
    while (!feof($detalleIndexUpdate)) {

        $line = fgets($detalleIndexUpdate);
        if (!empty($line)) {
            $lines = explode(";", $line);
            foreach ($lines as $curLine) {
                if (!empty($curLine)) {
                    $datos = explode(",", $curLine);
                    if ($datos[0] == $actual) {
                        $lineaDato = (int) $datos[1]; //donde inicia
                        fseek($detalleArchivoUpdate, $lineaDato);
                        $fila = fread($detalleArchivoUpdate, intval($datos[2]));
                        array_push($arrayActual, explode(",", $fila));
                    }
                }
            }
        }
    }
     

}

 else {
     $divStyle="display:none;";
     $divStyle2="display:block;";
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
            <input type="text" id="clasificacion" value="<?php echo $filaActual[5]; ?>" name="clasificacion" placeholder='Clasificación' required>
            <input type="text" name="descripcion" value="<?php echo $filaActual[6]; ?>" placeholder="Descripción" required>
            <input type="submit" name="guardar" value="Guardar">
        </form>
    </div>
</div>

<div class="container-list" style="<?php echo $divStyle2 ?>">
    <a href="nueva_entrada.php">Agregar Nuevo</a>
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
