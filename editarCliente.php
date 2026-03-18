<?php

require_once('Conexion.php');
$c = new Conexion();

if (
    isset($_POST['id_cliente']) &&
    isset($_POST['nombre']) &&
    isset($_POST['apellido'])
) {
    $id = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $direccion = $_POST['direccion'] ?? null;

    $r = $c->actualizarCliente($id, $nombre, $apellido, $correo, $telefono, $direccion);

    if ($r) {
        header("Location: consultarCliente.php?m=2"); // Cliente actualizado
    } else {
        header("Location: consultarCliente.php?m=3"); // Fallo al actualizar
    }

}


// Verificar que llega el id por GET
if (!isset($_GET['id_cliente'])) {
    echo "<div class='alert alert-danger m-5'>No se especificó el ID del cliente.</div>";
    exit;
}

$id_cliente = $_GET['id_cliente'];
$cliente = $c->obtenerClientePorId($id_cliente);

if (!$cliente) {
    echo "<div class='alert alert-danger m-5'>Cliente no encontrado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('cabezera.php'); ?>
</head>
<body>
    <?php include('menu.php'); ?>

    <div class="container-fluid">
        <form action="editarCliente.php" method="post" class="mt-5 offset-3 col-6">
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $cliente['apellido']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $cliente['correo']; ?>">
            </div>

            <div class="mb-3">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $cliente['telefono']; ?>">
            </div>

            <div class="mb-3">
                <label for="direccion">Dirección:</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo $cliente['direccion']; ?></textarea>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
