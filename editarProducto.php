<?php
require_once('Conexion.php');
$c = new Conexion();
$c->conectar(); 

if (
    isset($_POST['id_producto']) &&
    isset($_POST['nombre']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['precio']) &&
    isset($_POST['stock']) &&
    isset($_POST['tipo']) &&
    isset($_POST['categoria']) &&
    isset($_POST['proveedor'])
) {
    $id_producto  = intval($_POST['id_producto']);
    $nombre       = $_POST['nombre'];
    $descripcion  = $_POST['descripcion'];
    $precio       = floatval($_POST['precio']);
    $stock        = intval($_POST['stock']);
    $tipo         = $_POST['tipo'];
    $id_categoria = intval($_POST['categoria']);
    $id_proveedor = intval($_POST['proveedor']);

    //Recupera ruta de la imagen actual
    $stmtImg = $c->enlace->prepare("SELECT imagen FROM productos_generales WHERE id_producto = ?");
    $stmtImg->bind_param("i", $id_producto);
    $stmtImg->execute();
    $resImg   = $stmtImg->get_result();
    $rowImg   = $resImg->fetch_assoc();
    $rutaActual = $rowImg['imagen'] ?? '';
    $stmtImg->close();

    //  Procesar nueva imagen
    $rutaImagen = $rutaActual; // Si no suben archivo, se conserva la actual
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $destino       = "img/" . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $rutaImagen = $destino;
            // Eliminar la imagen anterior si existía
            if (!empty($rutaActual) && file_exists($rutaActual)) {
                unlink($rutaActual);
            }
        }
    }

    $ok = $c->actualizarProducto(
        $id_producto,
        $nombre,
        $descripcion,
        $precio,
        $stock,
        $tipo,
        $id_categoria,
        $id_proveedor,
        $rutaImagen
    );

    if ($ok) {
        header("Location: consultarProductos.php?p=2");  // Éxito al actualizar
    } else {
        header("Location: consultarProductos.php?p=3");  // Error al actualizar
    }
    exit;
}


// Verificar que llega el id por GET
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger m-5'>No se especificó el ID del producto.</div>";
    exit;
}

$id = intval($_GET['id']);
$stmt = $c->enlace->prepare("SELECT * FROM productos_generales WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<div class='alert alert-danger m-5'>Producto no encontrado.</div>";
    exit;
}

$producto = $res->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('cabezera.php'); ?>
</head>
<body>
    <?php include('menu.php'); ?>

    <div class="container-fluid">
        <form action="editarProducto.php" method="post" enctype="multipart/form-data" class="mt-5 offset-3 col-6">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="tipo">Tipo:</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($producto['tipo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="categoria">Categoría:</label>
                <select class="form-control" id="categoria" name="categoria" required>
                    <option value="1" <?php echo ($producto['id_categoria'] == 1) ? 'selected' : ''; ?>>Cuidado Personal</option>
                    <option value="2" <?php echo ($producto['id_categoria'] == 2) ? 'selected' : ''; ?>>Bebés</option>
                    <option value="3" <?php echo ($producto['id_categoria'] == 3) ? 'selected' : ''; ?>>Cosméticos</option>
                    <option value="4" <?php echo ($producto['id_categoria'] == 4) ? 'selected' : ''; ?>>Alimentos</option>
                    <option value="5" <?php echo ($producto['id_categoria'] == 5) ? 'selected' : ''; ?>>Higiene Bucal</option>
                    <option value="6" <?php echo ($producto['id_categoria'] == 6) ? 'selected' : ''; ?>>Suplementos Alimenticios</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="proveedor">Proveedor:</label>
                <select class="form-control" id="proveedor" name="proveedor" required>
                    <option value="1" <?php echo ($producto['id_proveedor'] == 1) ? 'selected' : ''; ?>>Proveedor A</option>
                    <option value="2" <?php echo ($producto['id_proveedor'] == 2) ? 'selected' : ''; ?>>Proveedor B</option>
                    <option value="3" <?php echo ($producto['id_proveedor'] == 3) ? 'selected' : ''; ?>>Proveedor C</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Imagen actual:</label><br>
                <?php if (!empty($producto['imagen']) && file_exists($producto['imagen'])): ?>
                    <img src="<?php echo $producto['imagen']; ?>" alt="Imagen producto" style="width: 150px; height: auto; margin-bottom:10px;">
                <?php else: ?>
                    <p>No hay imagen.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="imagen">Subir nueva imagen (opcional):</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
            </div>

            <div class="mb-3">
                <button class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
