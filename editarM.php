<?php
require_once('Conexion.php');
$c = new Conexion();
$c->conectar(); // Nos aseguramos de que $c->enlace no sea null

// 1) PROCESAMIENTO DEL POST (actualizar medicamento)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_medicamento'])) {
    // 1.1) Obtener valores enviados
    $id_medicamento   = intval($_POST["id_medicamento"]);
    $nombreComercial  = $_POST["nombreComercial"];
    $nombreGenerico   = $_POST["nombreGenerico"];
    $fecha_caducidad  = $_POST["fecha_caducidad"];
    $requiere_receta  = isset($_POST["requiere_receta"]) ? 1 : 0;
    $precio           = floatval($_POST["precio"]);
    $stock            = intval($_POST["stock"]);

    // 1.2) Recuperar la ruta de la imagen actual (si existe)
    $stmtImg = $c->enlace->prepare("SELECT imagen FROM medicamentos WHERE id_medicamento = ?");
    $stmtImg->bind_param("i", $id_medicamento);
    $stmtImg->execute();
    $resImg    = $stmtImg->get_result();
    $rowImg    = $resImg->fetch_assoc();
    $rutaActual = $rowImg['imagen'] ?? '';
    $stmtImg->close();

    // 1.3) Procesar posible nueva imagen subida
    $rutaImagen = $rutaActual; 
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        $ext          = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $nombreImagen = uniqid("med_") . "." . $ext;
        $destino      = __DIR__ . "/img/" . $nombreImagen;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino)) {
            $rutaImagen = "img/" . $nombreImagen;
            // Eliminar la imagen antigua si existía
            if (!empty($rutaActual) && file_exists(__DIR__ . "/" . $rutaActual)) {
                unlink(__DIR__ . "/" . $rutaActual);
            }
        }
    }

    // 1.4) Llamar al método actualizarMedicamento (debes implementarlo en Conexion.php)
    // Asegúrate de añadir en tu clase Conexion un método así:
    // public function actualizarMedicamento($id, $nombreComercial, $nombreGenerico, $fecha_caducidad,
    //                                       $requiere_receta, $precio, $stock, $imagen) { … }
    $ok = $c->actualizarMedicamento(
        $id_medicamento,
        $nombreComercial,
        $nombreGenerico,
        $fecha_caducidad,
        $requiere_receta,
        $precio,
        $stock,
        $rutaImagen
    );

    if ($ok) {
        header("Location: consultarM.php?me=2"); // Éxito al actualizar
    } else {
        header("Location: consultarM.php?me=3"); // Error al actualizar
    }
    exit;
}

// 2) SI NO ES POST, ENTRAMOS EN MÉTODO GET PARA MOSTRAR FORMULARIO PRELLENADO

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger m-5'>No se especificó el ID del medicamento.</div>";
    exit;
}

$id = intval($_GET['id']);
$stmt = $c->enlace->prepare("SELECT * FROM medicamentos WHERE id_medicamento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<div class='alert alert-danger m-5'>Medicamento no encontrado.</div>";
    exit;
}

$medicamento = $res->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Medicamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include('cabezera.php'); ?>
</head>
<body>
    <?php include('menu.php'); ?>

    <div class="container mt-5">
        <h2>Editar Medicamento</h2>

        <form action="editarM.php" method="post" enctype="multipart/form-data" class="mt-4 col-md-6 offset-md-3">
            <!-- Campo oculto con el ID -->
            <input type="hidden" name="id_medicamento" value="<?php echo $medicamento['id_medicamento']; ?>">

            <div class="mb-3">
                <label for="nombreComercial" class="form-label">Nombre Comercial:</label>
                <input type="text" id="nombreComercial" name="nombreComercial"
                       class="form-control" 
                       value="<?php echo htmlspecialchars($medicamento['nombreComercial']); ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="nombreGenerico" class="form-label">Nombre Genérico:</label>
                <input type="text" id="nombreGenerico" name="nombreGenerico"
                       class="form-control"
                       value="<?php echo htmlspecialchars($medicamento['nombreGenerico']); ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="fecha_caducidad" class="form-label">Fecha de Caducidad:</label>
                <input type="date" id="fecha_caducidad" name="fecha_caducidad"
                       class="form-control"
                       value="<?php echo $medicamento['fecha_caducidad']; ?>"
                       required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" id="requiere_receta" name="requiere_receta" value="1"
                       class="form-check-input"
                       <?php echo ($medicamento['requiere_receta'] ? 'checked' : ''); ?>>
                <label for="requiere_receta" class="form-check-label">Requiere Receta</label>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($):</label>
                <input type="number" id="precio" name="precio"
                       class="form-control" step="0.01" min="0"
                       value="<?php echo $medicamento['precio']; ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock (unidades):</label>
                <input type="number" id="stock" name="stock"
                       class="form-control" min="0"
                       value="<?php echo $medicamento['stock']; ?>"
                       required>
            </div>

            <div class="mb-3">
                <label>Imagen Actual:</label><br>
                <?php if (!empty($medicamento['imagen']) && file_exists(__DIR__ . "/" . $medicamento['imagen'])): ?>
                    <img src="<?php echo $medicamento['imagen']; ?>"
                         alt="Imagen Medicamento"
                         style="width: 150px; height: auto; margin-bottom:10px;">
                <?php else: ?>
                    <p>No hay imagen disponible.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Subir Nueva Imagen (opcional):</label>
                <input type="file" id="imagen" name="imagen"
                       class="form-control"
                       accept="image/*">
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
