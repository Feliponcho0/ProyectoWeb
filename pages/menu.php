<?php 
    require_once "../validations/check.php"; 
    require_once "../validations/conection.php";
    // Manejar la tienda SOLO si el usuario no es admin y tiene tienda asignada
    $nombre_tienda = "Sin tienda asignada"; // Valor por defecto
    
    if (isset($_SESSION['tiendas_id']) && $_SESSION['tiendas_id'] !== null && $_SESSION['tiendas_id'] > 0) {
        $id_t = $_SESSION['tiendas_id'];
        $res_t = $conn->query("SELECT nombre_tienda FROM tiendas WHERE tiendas_id = $id_t");
        if ($res_t && $res_t->num_rows > 0) {
            $fila_t = $res_t->fetch_assoc();
            $nombre_tienda = $fila_t['nombre_tienda'];
        }
    }
    
    // Obtener datos del usuario desde la sesión
    $nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
    $rol_usuario = $_SESSION['rol'] ?? '';
    $correo_usuario = $_SESSION['correo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORE</title>

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!--JQuery-->
    <script src="../js/jquery-4.0.0.js"></script>
    <script src = "../js/configuration.js"></script>

    <!--sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Google Fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!--CSS-->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid sidebar p-0">
    <div class="row flex-nowrap m-0">

        <!-- SIDEBAR -->
        <div class="col-auto col-md-3 col-xl-2 px-0 vh-100 overflow-auto bg-sidebar">
            <div class="d-flex flex-column justify-content-start pt-2 w-100">

                <div class="w-100 d-flex justify-content-center pt-2">
                    <a href="#">
                        <img class="logo" src="../img/logocore2.png" alt="Logo_CORE">
                    </a>
                </div>
                <nav class="sidebar d-flex flex-column nav nav-pills p-2 gap-1">
                    <hr class="my-2 border border-1 border-black mx-2 mt-3">
                    
                    <small>PRINCIPAL</small>
                    <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Gerente' || $rol_usuario === 'Cajero'): ?>
                    <a id="dashboard" href="dashboard.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-columns-gap"></i> Dashboard
                    </a>
                    <?php endif; ?>

                    <?php if ($rol_usuario === 'Gerente' || $rol_usuario === 'Cajero'): ?>
                    <a id="punto_venta" href="punto_venta.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-laptop"></i> Punto de Venta
                    </a>
                    <?php endif; ?>

                    <?php if ($rol_usuario === 'Gerente'): ?>
                    <a id="corte_caja" href="corte_caja.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-cash-stack"></i> Corte de Caja
                    </a>
                    <?php endif; ?>

                    <?php if ($rol_usuario === 'Gerente'): ?>
                    <small>GESTIÓN</small>
                    <a id="inventario" href="inventario.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-box-seam"></i> Inventario
                    </a>
                    <a id="reportes" href="reportes.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                    </a>
                    <a id="usuarios" href="usuarios.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <?php endif; ?>

                    <?php if ($rol_usuario === 'Administrador'): ?>
                    <small>GESTIÓN</small>
                    <a id="usuarios" href="usuarios.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <a id="tiendas" href="tiendas.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-shop"></i> Tiendas
                    </a>
                    <?php endif; ?>

                    <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Gerente'): ?>
                    <small>SISTEMA</small>
                    <a href="configuracion.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                    <?php endif; ?>

                    <hr class="my-2 border border-1 border-black mx-2">
                    
                    <a href="perfil.php" class="nav-link d-flex gap-2">
                        <i class="bi bi-person-circle fs-2"></i>
                        <div>
                            <span class="mt-1 d-block">
                                <?php echo htmlspecialchars($nombre_usuario); ?>
                            </span>
                            <small class="d-block">
                                <span>
                                    <?php echo htmlspecialchars($rol_usuario); ?>
                                </span>
                            </small>
                        </div>
                    </a>
                </nav>
            </div>
        </div>

        <div class="col p-0">

            <!-- TOPBAR -->
            <nav class="navbar navbar-superior px-4">
                <div class="btn-group ms-auto">
                    <button type="button" class="btn text-white bg-white bg-opacity-10">
                        <?php echo htmlspecialchars($nombre_usuario); ?>
                    </button>
                    <button type="button" class="btn text-white bg-white bg-opacity-10 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end menu-desplegable shadow-sm p-0 border-1">
                        <li class="p-1 text-white fondoDropdown mt-0">
                            <div class="d-flex align-items-center p-1 gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M9 10a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/></svg>
                                <div>
                                    <strong class="tipoLetra d-block"><?php echo htmlspecialchars($nombre_usuario); ?></strong>
                                    <small class="text-light d-block"><?php echo htmlspecialchars($correo_usuario); ?></small>
                                    <span class="badge bg-primary rounded-5"><?php echo htmlspecialchars($rol_usuario); ?></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="dropdown-item d-flex align-items-center p-2 ps-3 gap-2">
                                <i class="bi bi-person-circle fs-3"></i>
                                <div>
                                    <strong class="tipoLetra d-block">Mi perfil</strong>
                                    <small class="text-muted d-block">Editar datos y contraseña</small>
                                </div>
                            </a>
                        </li>

                        <li><hr class="mt-1 mb-1"></li>
                        <li>
                            <a href="../index.html" class="dropdown-item d-flex align-items-center text-danger p-2 mb-2 ps-3 gap-2">
                                <i class="bi bi-x-circle fs-3 text-danger"></i>
                                <div>
                                    <strong class="tipoLetra d-block">Cerrar sesion</strong>
                                    <small class="text-danger d-block">Salir del sistema CORE</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido de las paginas -->
            <div id = "contenido" class="p-4">
                <?php include $pagina;?>
            </div>

        </div>
    </div>
</div>

</body>
</html>