<?php
session_start();

$contenidoPath = "database/contenido.txt";
$indicePath = "database/indice.txt";

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
                    echo "<form action='' method='post' enctype='multipart/form-data'>";
                    $btnEliminar = "<input type='submit' name='eliminar' value='Eliminar'>";
                    $btnEditar = "<input type='submit' name='editar' value='Editar'>";
                    $btnMp3 = '<input type="file" id="archivo" name="archivo"  accept=".mp3" style="width:134px;" required>';
                    $row = array_pop($arrayContenido);
                    echo '<tr>';
                    echo "<td>{$row[1]}</td>";
                    echo "<td>{$row[2]}</td>";
                    echo "<td>{$row[3]}</td>";
                    echo "<td>{$row[4]}</td>";
                    echo "<td>{$row[5]}</td>";
                    echo "<td>{$row[6]}</td>";
                    echo '<td>';
                    echo strlen($row[7])<=2?$btnMp3:"<span>{$row[7]}</span>";
                    echo $btnEditar;
                    echo $btnEliminar;
                    echo '</td>';
                    echo '</tr>';
                    echo '<input type="hidden" name="id" value="'.$row[0].'"';
                    echo '</form>';
                    
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include('comun/footer.php');
