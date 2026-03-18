<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Medicamentos</title>
    <?php include('cabezera.php'); ?>
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
            background-color:rgb(124, 180, 236); /* Color de fondo suave en caso de que la imagen no cubra todo */
            overflow: hidden;
        }
        .card-img-top-wrapper img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>
    <?php include('menu.php'); ?>
<div class="content-wrapper">
    <div class="container-fluid mt-4 contenido-centrado">
    <script>
    function eliminarM(id) {
        bootbox.confirm({
            message: "¿Estás seguro de eliminar el medicamento con ID " + id + "?",
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
                    formulario.action = 'eliminarM.php';
                    formulario.method = 'get';

                    let input = document.createElement('input');
                    input.name = "id_medicamento";
                    input.value = id;

                    formulario.appendChild(input);
                    document.body.appendChild(formulario);
                    formulario.submit();
                }
            }
        });
    }
    </script>

    <div class="container-fluid">
      <div class="d-flex justify-content-end">
        <a href="agregarM.php" class="btn btn-primary mt-2">
          <i class="fas fa-plus me-1"></i> 
          Agregar Medicamento
        </a>
      </div>
    </div>

    <div class="container my-4">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php
        require_once('Conexion.php');
        $c = new Conexion();
        $res = $c->consultarMedicamentos();

        while ($medicamento = $res->fetch_assoc()) {
        ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-top-wrapper">
                    <img src="<?php echo $medicamento['imagen']; ?>" class="card-img-top" alt="Imagen del producto" style="width: 300px; height: 200px;">
        </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $medicamento['nombreComercial']; ?></h5>
                        <p class="card-text">
                            <strong>Genérico:</strong> <?php echo $medicamento['nombreGenerico']; ?><br>
                            <strong>Caducidad:</strong> <?php echo $medicamento['fecha_caducidad']; ?><br>
                            <strong>Receta:</strong> <?php echo $medicamento['requiere_receta'] ? 'Sí' : 'No'; ?><br>
                            <strong>Precio:</strong> $<?php echo number_format($medicamento['precio'], 2); ?><br>
                            <strong>Stock:</strong> <?php echo intval($medicamento['stock']); ?><br>
                        </p>

                        <a href="editarM.php?id=<?php echo $medicamento['id_medicamento']; ?>" 
                           class="btn btn-warning float-end"><span class="fa fa-pencil-square"></span></a>
                        <button onclick="eliminarM('<?php echo $medicamento['id_medicamento']; ?>')" 
                                class="btn btn-danger"><span class="fa fa-trash"></span></button>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
      </div>
    </div>

    <?php
    if (isset($_GET['e'])) {
        switch ($_GET['e']) {
            case 0:
                echo "<script>Swal.fire('Error', 'No se pudo agregar el medicamento', 'error');</script>";
                break;
            case 1:
                echo "<script>Swal.fire('¡Éxito!', 'El medicamento ha sido registrado correctamente', 'success');</script>";
                break;
            case 2:
                echo "<script>Swal.fire('¡Éxito!', 'El medicamento ha sido actualizado correctamente', 'success');</script>";
                break;
            case 3:
                echo "<script>Swal.fire('Error', 'No se pudo actualizar el medicamento', 'error');</script>";
                break;
            case 4:
                echo "<script>Swal.fire('¡Éxito!', 'El medicamento ha sido eliminado correctamente', 'success');</script>";
                break;
            case 5:
                echo "<script>Swal.fire('Error', 'No se pudo eliminar el medicamento', 'error');</script>";
                break;
            case 6:
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Fecha inválida',
                    text: 'Los medicamentos tienen establecido legalmente un límite máximo de caducidad de 5 años!'});</script>";
                break;
        }
    }
    ?>

</div>
</div>

</body>
</html>