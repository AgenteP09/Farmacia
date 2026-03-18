<?php
$currentPage = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
   
    <style>
      /* Ajuste para que la tarjeta dentro del navbar no desborde */
      .navbar-card {
        width: 100%;
        max-height: 60px;
        overflow: hidden;
        padding: 0;
      }
      .navbar-card img {
        object-fit: cover;
        height: 60px;
        width: 100%;
      }
      .navbar-card .card-img-overlay {
        background: rgba(0, 0, 0, 0.3);
        padding: 0.25rem 0.5rem;
      }
      .navbar-card .card-title {
        font-size: 1rem;
        margin-bottom: 0;
      }
      .navbar-card .card-text small {
        font-size: 0.65rem;
      }
    </style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <div class="container">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <div class="card text-bg-dark navbar-card ml-auto">
                  <img src="img/enca.jpg" class="card-img" alt="Imagen Farmacia Juanito">
                  <div class="card-img-overlay d-flex flex-column justify-content-center">
                    <h5 class="card-title text-white">Farmacia Juanito</h5>
                    <p class="card-text"><small class="text-light"> Porque hoy Huetamo es Grande.</small></p>
                  </div>
                </div>
            </div>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="img/doc.jpg" alt="Farmacia Juanito Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Farmacia Juanito</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="img/lu.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info d-flex flex-column justify-content-center">
                        <a href="#" class="d-block text-white">Juan Manuel</a>
                        <span class="text-muted small">Administrador</span>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="m.php" class="nav-link <?php echo ($currentPage === 'm.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="consultarProductos.php" class="nav-link <?php echo ($currentPage === 'consultarProductos.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>Productos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="consultarM.php" class="nav-link <?php echo ($currentPage === 'consultarM.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fa fa-medkit" aria-hidden="true"></i>
                                <p>Medicamentos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="consultarCliente.php" class="nav-link <?php echo ($currentPage === 'consultarCliente.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Clientes</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>
    </div>

  
</body>
</html>
