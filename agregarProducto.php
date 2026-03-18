<?php
require_once('Conexion.php');
$c = new Conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $tipo = $_POST["tipo"];
    $id_categoria = $_POST["categoria"];
    $id_proveedor = $_POST["proveedor"];

    $imagen = "";
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $nombreImagen = uniqid() . "_" . $_FILES["imagen"]["name"];
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "img/" . $nombreImagen);
        $imagen = "img/" . $nombreImagen;
    }

    $r = $c->insertarProductoGeneral($nombre, $descripcion, $precio, $stock, $tipo, $id_categoria, $id_proveedor, $imagen);
    //echo "<div class='alert alert-success text-center'>Producto guardado correctamente.</div>";
    if ($r) {
        header("location: consultarProductos.php?p=1"); 
    } else {
        header("location: consultarProductos.php?p=0"); 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto General</title>
    <?php include('cabezera.php'); ?>
</head>
<body>
<?php include('menu.php'); ?>

<div class="container mt-5">
    <div class="content-wrapper">
    <h2>Agregar Producto General</h2>


    <form action="agregarProducto.php" method="post" enctype="multipart/form-data" class="mt-4 col-md-6 offset-md-3">
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="mb-3">
            <label>Descripción:</label>
            <textarea class="form-control" name="descripcion" required></textarea>
        </div>

        <div class="mb-3">
            <label>Precio:</label>
            <input type="number" step="0.01" class="form-control" name="precio" required>
        </div>

        <div class="mb-3">
            <label>Stock:</label>
            <input type="number" class="form-control" name="stock" required>
        </div>

        <div class="mb-3">
            <label>Tipo:</label>
            <input type="text" class="form-control" name="tipo" required>
        </div>

        <div class="mb-3">
            <label>Categoría:</label>
            <select class="form-control" name="categoria" required>
                <option value="1">Cuidado Personal</option>
                <option value="2">Bebés</option>
                <option value="3">Cosméticos</option>
                <option value="4">Alimentos</option>
                <option value="5">Higiene Bucal</option>
                <option value="6">Suplementos Alimenticios</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Proveedor:</label>
            <select class="form-control" name="proveedor" required>
                <option value="1">Proveedor A</option>
                <option value="2">Proveedor B</option>
                <option value="3">Proveedor C</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Imagen:</label>
            <input type="file" class="form-control" name="imagen" accept="image/*">
        </div>

        <div class="text-center">
            <button class="btn btn-primary">Guardar Producto</button>
        </div>

        
    </form>
</div>
</div>

</body>
</html>
