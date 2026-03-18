<?php
require_once('Conexion.php');
$c = new Conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreComercial  = $_POST["nombreComercial"];
    $nombreGenerico   = $_POST["nombreGenerico"];
    $fecha_caducidad  = $_POST["fecha_caducidad"];
    $requiere_receta  = isset($_POST["requiere_receta"]) ? 1 : 0;
    $precio           = $_POST["precio"];
    $stock            = $_POST["stock"];
    $imagen = null;

    $hoy = date("Y-m-d");
    $cincoAniosDespues = date("Y-m-d", strtotime("+5 years"));
  // Validar que la fecha de caducidad sea al menos 5 años después de hoy
    if ($fecha_caducidad <= $cincoAniosDespues) {
        header("Location: consultarM.php?e=6"); 
    
    }
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nombreImagen = uniqid("med_") . "." . $ext;
        $rutaDestino = __DIR__ . "/img/" . $nombreImagen;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen = "img/" . $nombreImagen;
        }
    }

    $resultado = $c->insertarMedicamento($nombreComercial, $nombreGenerico, $fecha_caducidad, $requiere_receta, $precio, $stock, $imagen);

    if ($resultado) {
        header("Location: consultarM.php?e=1");
        exit;
    } else {
        header("Location: consultarM.php?e=0");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Medicamento</title>
    <?php include('cabezera.php'); ?>
</head>
<body>
   

    <?php include('menu.php'); ?>

    <div class="container mt-5">\
        <div class="content-wrapper">
        <h2>Agregar Nuevo Medicamento</h2>

        <form action="agregarM.php" method="post" enctype="multipart/form-data" class="mt-4 col-md-6 offset-md-3">
            <div class="mb-3">
                <label for="nombreComercial" class="form-label">Nombre Comercial:</label>
                <input type="text" id="nombreComercial" name="nombreComercial" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nombreGenerico" class="form-label">Nombre Genérico:</label>
                <input type="text" id="nombreGenerico" name="nombreGenerico" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fecha_caducidad" class="form-label">Fecha de Caducidad:</label>
                <input type="date" id="fecha_caducidad" name="fecha_caducidad" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" id="requiere_receta" name="requiere_receta" value="1" class="form-check-input">
                <label for="requiere_receta" class="form-check-label">Requiere Receta</label>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($):</label>
                <input type="number" id="precio" name="precio" class="form-control" step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock (unidades):</label>
                <input type="number" id="stock" name="stock" class="form-control" min="0" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar Medicamento</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>
