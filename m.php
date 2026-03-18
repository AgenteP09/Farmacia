<?php
require_once('Conexion.php');
$conexion = new Conexion();
$totalClientes = $conexion->contarClientes();
$totalProductos = $conexion->contarProductos();
$totalMedicamentos = $conexion->contarMedicamentos();
$medicamentosCaducos = $conexion->caducos(15);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <?php include('cabezera.php'); ?>
</head>
<body>
  <?php include('menu.php'); ?>
  <div class="content-wrapper">
    <div class="main-content">
      <div class="container-fluid">
        <h1>Bienvenido a Farmacia Juanito</h1>
        <p>Sistema de gestión farmacéutica</p>

        <div class="row">
          <div class="col-lg-3 col-6">
            <a href="consultarM.php" style="text-decoration: none; color: inherit;">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $totalMedicamentos;?></h3>
                <p>Medicamentos</p>
              </div>
              <div class="icon">
                <i class="fas fa-pills"></i>
              </div>
            </div>
            </a>
          </div>

          <div class="col-lg-3 col-6">
            <a href="consultarProductos.php" style="text-decoration: none; color: inherit;">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo $totalProductos; ?></h3>
                  <p>Productos</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-basket"></i>
                </div>
              </div>
            </a>
          </div>

          <div class="col-lg-3 col-6">
            <a href="consultarCliente.php" style="text-decoration: none; color: inherit;">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?php echo $totalClientes; ?></h3>
                  <p>Clientes</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </a>
          </div>

          <div class="col-lg-3 col-6">
            <a href="consultarM.php" style="text-decoration: none; color: inherit;">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $medicamentosCaducos;?></h3>
                <p>Medicamentos a Caducar</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
