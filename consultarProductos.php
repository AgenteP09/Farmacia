<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('cabezera.php');?>

    <style>
        body {
            padding-top: 0;
        }
        .contenido-centrado {
            max-width: 1000px; 
            margin-left: auto;
            margin-right: auto;
        }
        .card-img-top-wrapper {
            width: 100%;
            height: 200px;             
            display: flex;
            align-items: center;
            justify-content: center;
            background-color:rgb(148, 198, 249); /* Color de fondo  */
            overflow: hidden;
        }
        .card-img-top-wrapper img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php include('menu.php');?>

        <div class="content-wrapper">
            <div class="container-fluid mt-4 contenido-centrado">
                 <br>
                <div class="d-flex justify-content-end mb-3">
                    <a href="agregarProducto.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Producto
                    </a>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php
                    require_once('conexion.php');
                    $c = new Conexion();
                    $res = $c->consultarProductosGenerales(); 

                    while ($producto = $res->fetch_assoc()) {
                    ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top-wrapper">
                                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                                         alt="Imagen del producto">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    <p class="card-text mb-2">
                                        <strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?><br>
                                        <strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?><br>
                                        <strong>Stock:</strong> <?php echo htmlspecialchars($producto['stock']); ?><br>
                                        <strong>Tipo:</strong> <?php echo htmlspecialchars($producto['tipo']); ?>
                                        <br>
                                         
                                    </p>
                                    
                                       <a href="editarProducto.php?id=<?php echo $producto['id_producto']; ?>"
                                           class="btn btn-warning btn-sm">
                                            <span class="fa fa-pencil-square"></span>
                                        </a>
                                        <button onclick="eliminarP('<?php echo $producto['id_producto']; ?>')"
                                                class="btn btn-danger btn-sm">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

    
                <?php
                if (isset($_GET['p'])) {
                    switch ($_GET['p']) {
                        case 0:
                            echo "<script>Swal.fire('Error', 'No se pudo agregar el producto', 'error');</script>";
                            break;
                        case 1:
                            echo "<script>Swal.fire('¡Éxito!', 'El producto ha sido registrado correctamente', 'success');</script>";
                            break;
                        case 2:
                            echo "<script>Swal.fire('¡Éxito!', 'El producto ha sido actualizado correctamente', 'success');</script>";
                            break;
                        case 3:
                            echo "<script>Swal.fire('Error', 'No se pudo actualizar el producto', 'error');</script>";
                            break;
                        case 4:
                            echo "<script>Swal.fire('¡Éxito!', 'El producto ha sido eliminado correctamente', 'success');</script>";
                            break;
                        case 5:
                            echo "<script>Swal.fire('Error', 'No se pudo eliminar el producto', 'error');</script>";
                            break;
                    }
                }
                ?>
            </div>
        </div>

    </div>

   
    <script>
        function eliminarP(id) {
            bootbox.confirm({
                message: "¿Estás seguro de eliminar el producto con ID " + id + "?",
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
                        let formulario = document.createElement('form');
                        formulario.action = 'eliminarProducto.php';
                        formulario.method = 'get';

                        let input = document.createElement('input');
                        input.name = "id_producto";
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
