<?php
require_once('Conexion.php');
$c = new Conexion();

if (isset($_GET["id_medicamento"])) {
    $id = $_GET["id_medicamento"];
    $resultado = $c->eliminarMedicamento($id);

    if ($resultado) {
        header("location: consultarM.php?e=4"); //eliminación exitosa
    } else {
        header("location: consultarM.php?e=5"); //error al eliminar
    }
} else {
    header("location: consultarM.php"); 
}
exit;
