<?php
require_once('Conexion.php');
$c = new Conexion();

if (isset($_GET["id_producto"])) {
    $id = $_GET["id_producto"];
    $resultado = $c->eliminarProducto($id);

    if ($resultado) {
        header("location: consultarProductos.php?p=4"); //eliminación exitosa
    } else {
        header("location: consultarProductos.php?p=5"); //error al eliminar
    }
} else {
    header("location: consultarProductos.php"); 
}
exit;
