<?php
require_once('Conexion.php');

if (isset($_POST['idCliente'])) {
    $id = intval($_POST['idCliente']); 
    $c = new Conexion();
    $resultado = $c->eliminarCliente($id);

    if ($resultado) {
        header("Location: consultarCliente.php?m=4");
    } else {
        header("Location: consultarCliente.php?m=5");
    }
}

?>
