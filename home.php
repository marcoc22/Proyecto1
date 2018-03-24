<?php
session_start();

$contenidoPath = "database/contenido.txt";
$indicePath = "database/indice.txt";


//***********************************ELIMINANDO ENTRADA***************************************
if (isset($_POST['eliminar'])) {
    $actual = (int) $_POST['id'];
    $detalleIndexUpdate = fopen($indicePath, "r+");
    $pos = 0;
    while (!feof($detalleIndexUpdate)) {
        $line = fgets($detalleIndexUpdate);
        $pos += strlen($line);
        if (!empty($line)) {
            $datos = explode(",", $line);
            if ($datos[0] == $actual) {
                $datos[1] = 0;
                $nuevaLinea = implode(",", $datos) . "\n";
                fseek($detalleIndexUpdate, $pos - strlen($line), SEEK_SET);
                fwrite($detalleIndexUpdate, $nuevaLinea);
                $arrayIndex[$datos[0]] = $datos;
                break;
            }
        }
        unset($line);
    }
    fclose($detalleIndexUpdate);
}

//**************************************LEYENDO ENTRADAS**************************************
$detalleArchivo = fopen($contenidoPath, "r");
$detalleIndex = fopen($indicePath, "r");
$arrayIndex = array();
$arrayContenido = array();
$ultimaLinea = 0;
$file = new SplFileObject($contenidoPath);
while (!feof($detalleIndex)) {
    $line = fgets($detalleIndex);
    if (!empty($line)) {
        $datos = explode(",", $line);
        if ($datos[1] == 1) {
            $arrayIndex[$datos[0]] = $datos;
            $lineaDato = (int) $datos[0];
            $file->seek($lineaDato);
            array_push($arrayContenido, explode(",", $file->current()));
        }
        $ultimaLinea++;
    }
    unset($line);
}
fclose($detalleArchivo);
fclose($detalleIndex);
unset($file);




include('comun/header.php');
?>

<div class="container-list">
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
