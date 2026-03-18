<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['nombre'])
    && isset($_POST['apellido'])
) {
    require_once('Conexion.php');
    $c = new Conexion();
    $r = $c->insertarCliente(
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['correo']   ?? null,
        $_POST['telefono'] ?? null,
        $_POST['direccion']?? null
    );

    if ($r) {
        header("Location: consultarCliente.php?m=1"); // Cliente insertado
    } else {
        header("Location: consultarCliente.php?m=0"); // Fallo al insertar
    }

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('cabezera.php'); ?>
</head>
<body>
    <?php include('menu.php'); ?>

    <div class="container-fluid">
        <div class="content-wrapper">
        <h2>Agregar Nuevo Cliente</h2>
        <form action="rcliente.php" method="post" class="mt-5 offset-3 col-6">
            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" require placeholder="ejemplo@correo.com">
            </div>
            <div class="mb-3">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10,}" title="El teléfono debe contener al menos 10 dígitos" required>
            </div>
            <div class="mb-3">
                <label for="direccion">Dirección:</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
<script>
document.querySelector("form").addEventListener("submit", function (e) {
    const telefono = document.querySelector("input[name='telefono']").value;
    const correo = document.querySelector("input[name='correo']").value;
    const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
    const telefonoValido = /^[0-9]{10,}$/.test(telefono);

    if (!correoValido || !telefonoValido) {
        e.preventDefault();
        let mensaje = "";
        if (!correoValido) mensaje += "Correo electrónico inválido.\n";
        if (!telefonoValido) mensaje += "Teléfono debe tener al menos 10 dígitos.\n";
        alert(mensaje);
    }
});
</script>
