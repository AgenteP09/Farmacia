<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('cabezera.php'); ?>

    <style>
        body {
            padding-top: 0;
        }
        .contenido-centrado {
            max-width: 800px; 
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php include('menu.php'); ?>

    <div class="content-wrapper">
        <div class="container contenido-centrado mt-4">
<br>
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="mb-4">Lista de Clientes</h2>

                    <form action="consultarCliente.php" method="post" class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" name="nombre" placeholder="Buscar por nombre" class="form-control"
                                   value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="consultarCliente.php" class="btn btn-secondary">Limpiar filtro</a>
                        </div>
                        <div class="col-auto">
                            <a href="rcliente.php" class="btn btn-primary"
                               style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                Agregar cliente
                            </a>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['nombre']) && $_POST['nombre'] !== '') {
                        echo '<div class="alert alert-info mt-3">Filtro aplicado: <strong>' 
                             . htmlspecialchars($_POST['nombre']) . '</strong></div>';
                    }
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once('Conexion.php');
                        $conexion = new Conexion();

                        $nombreFiltro = isset($_POST['nombre']) && $_POST['nombre'] !== '' 
                                        ? $_POST['nombre'] 
                                        : null;
                        $resultado = $conexion->consultarCliente($nombreFiltro);

                        if ($resultado && $resultado->num_rows > 0) {
                            while ($fila = $resultado->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $fila['id_cliente'] . '</td>';
                                echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['apellido']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['correo']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['telefono']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['direccion']) . '</td>';
                                echo '<td>';
                                echo '<a href="editarCliente.php?id_cliente=' . $fila['id_cliente'] 
                                     . '" class="btn btn-warning btn-sm">'
                                     . '<span class="fa fa-pencil-square"></span></a> ';
                                echo '<button class="btn btn-danger btn-sm" onclick="eliminarCliente(' 
                                     . $fila['id_cliente'] . ')">'
                                     . '<span class="fa fa-trash"></span></button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7">'
                                 . '<div class="alert alert-warning text-center">'
                                 . 'No se encontraron clientes registrados.'
                                 . '</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            if (isset($_GET['m'])) {
                switch ($_GET['m']) {
                    case 0:
                        echo "<script>Swal.fire('Error', 'No se pudo agregar el cliente', 'error');</script>";
                        break;
                    case 1:
                        echo "<script>Swal.fire('¡Éxito!', 'El cliente ha sido registrado correctamente', 'success');</script>";
                        break;
                    case 2:
                        echo "<script>Swal.fire('¡Éxito!', 'El cliente ha sido actualizado correctamente', 'success');</script>";
                        break;
                    case 3:
                        echo "<script>Swal.fire('Error', 'No se pudo actualizar el cliente', 'error');</script>";
                        break;
                    case 4:
                        echo "<script>Swal.fire('¡Éxito!', 'El cliente ha sido eliminado correctamente', 'success');</script>";
                        break;
                    case 5:
                        echo "<script>Swal.fire('Error', 'No se pudo eliminar el cliente', 'error');</script>";
                        break;
                }
            }
            ?>
        </div>
    </div>

    <script>
        function eliminarCliente(id) {
            bootbox.confirm({
                message: "¿Estás seguro de eliminar el cliente con ID " + id + "?",
                buttons: {
                    confirm: {
                        label: 'Sí',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        // Crea un formulario dinámico que envía { idCliente: id } por POST
                        let formulario = document.createElement('form');
                        formulario.action = 'eliminarCliente.php';
                        formulario.method = 'post';

                        let input = document.createElement('input');
                        input.name = "idCliente";
                        input.value = id;

                        formulario.appendChild(input);
                        document.body.appendChild(formulario);
                        formulario.submit();
                    }
                }
            });
        }
    </script>
</body>
</html>

