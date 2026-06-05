<?php
    $error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset = "UTF-8">
    <link href = "https://fonts.googleapis.com/css2?family=Signika&display=swap" rel = "stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "css/styleBurbujas.css">
    <title>Core Multistore</title>
</head>

<!-- modal de errores -->
<div class="modal fade" id = "error" tabindex="-10">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
            </div>
            <div class="modal-body">
                <?php
                    switch($error){
                        case 1:
                            echo '<p class="text-danger">Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.</p>';
                            break;

                        case 2:
                            echo '<p class="text-danger">Debes iniciar sesión para acceder a esta página.</p>';
                            break;

                        case 3:
                            echo '<p class="text-danger">No tienes permiso para acceder a esta página.</p>';
                            break;

                        case 4:
                            echo '<p class="text-danger">Tu usuario está desactivado.</p>';
                            break;

                        case 5:
                            echo '<p class="text-danger" style="font-size: 24px;">NO TIENES PERMITIDO ACCEDER A ESTA AREA.</p>';
                            break;
                    }
                ?>
            </div>
            <div class = "modal-footer">
            <button type = "button" class = "btn btn-secondary" data-bs-dismiss="modal">
                Cerrar
            </button>
            </div>
        </div>
    </div>
</div>


<body class = "m-0 p-0">
    <div class = "d-flex vh-100 w-100 p-4">
        <div class = "d-flex w-100">
            <div class = "border border-dark border-2 flex-fill d-flex justify-content-center align-items-center">
                <div class = "text-center" style = "font-family: 'Signika', sans-serif;">
                    <div class = "d-flex align-items-center justify-content-center mb-4">
                        <div>
                            <img src = "img/Logo_Core.png" style = "width: 100px; height: auto;">
                        </div>
                        <div class = "ms-0" style = "width: 100px; height: auto;">
                            <h1 class = "fw-bold mb-1">
                                CORE
                            </h1>
                            <p class = "justify-content-center align-items-center fw-semibold text-secondary mb-4">
                                MULTISTORE
                            </p>
                        </div>
                    </div>
                    <h2 class = "h5 fw-bold mt-3">
                        Bienvenido al Sistema
                    </h2>
                    <p class = "text-secondary small mb-4">
                        Inicia sesión para continuar
                    </p>
                    
                    <form action = "validations/validarLogin.php" method = "post" class = "text-start">
                        <div class = "mb-3 form-floating mb-3">
                            <input type = "text" class = "form-control border border-primary border-2" id = "nombre_usuario" name = "nombre_usuario" required placeholder = "Ingresa tu usuario o correo">
                                <label for="usuario" class = "form-label fw-semibold">
                                    Usuario
                                </label>
                        </div>
                        <div class = "mb-3 form-floating mb-3">
                            <input type = "password" class = "form-control border border-primary border-2" id = "password" name = "password" required placeholder = "Ingresa tu contraseña">
                            <label for = "password" class = "form-label fw-semibold">
                                Contraseña
                            </label>
                        </div>
                        <div class = "d-grid gap-2 mt-4">
                            <button id = "iniciarSesion" action = "submit" href = "validations/validarLogin.php" class = "btn py-2 fw-semibold" type = "submit" style = "background-color: transparent; border: 2px solid #012e46; color: #012e46; transition: all 0.5s;" onmouseover = "this.style.backgroundColor = '#012e46'; this.style.color = 'white';" onmouseout = "this.style.backgroundColor = 'transparent'; this.style.color = '#012e46';">
                                Iniciar sesión
                            </button>
                        </div>
                    </form>
                    
                    <hr class = "my-4">
                    
                    <a href = "pages/olvidoContra.html" class = "text-decoration-none">
                        <p class = "small mt-3 mb-0">
                            ¿Olvidaste tu contraseña?
                        </p>
                    </a>
                </div>
            </div>
            <div id = "burbujas-container" class = "border border-dark border-2 flex-fill d-flex justify-content-center align-items-center" style = "background-color: #012e46;">
                <div class = "text-center">
                    <div class = "mb-4" style = "color: #fff">

                        <p>
                            SISTEMA MULTIEMPRESA
                        </p>
                        <h4 class = "fw-bold mb-3">
                            Gestiona tu negocio desde un solo lugar
                        </h4>
                        <p>
                            Control total sobre ventas, inventario, cajero reportes en tiempo real.
                        </p>

                        <div class = "border border-primary border-2 mb-3" style = "border-radius: 10px;">
                            <div class = "d-flex align-items-center ms-2 mt-2 mb-2" style = "gap: 15px;">
                                <div class = "" style = "border-radius: 10px; width: 60px; height: 60px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-buildings"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 21v-15c0 -1 1 -2 2 -2h5c1 0 2 1 2 2v15" /><path d="M16 8h2c1 0 2 1 2 2v11" /><path d="M3 21h18" /><path d="M10 12v.01" /><path d="M10 16v.01" /><path d="M10 8v.01" /><path d="M7 12v.01" /><path d="M7 16v.01" /><path d="M7 8v.01" /><path d="M17 12v.01" /><path d="M17 16v.01" /></svg>
                                </div>
                                <div class = "text-start" style = "border-radius: 10px; padding: 10px;">
                                    <p class = "fw-bold mb-1">
                                        <strong>Multitienda</strong>
                                    </p>
                                    <p class = " mb-0">
                                        Administra varias tiendas con roles independientes.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class = "border border-primary border-2 mb-3" style = "border-radius: 10px;">
                            <div class = "d-flex align-items-center ms-2 mt-2 mb-2" style = "gap: 15px;">
                                <div style = "border-radius: 10px; width: 60px; height: 60px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments-cog"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M6 4v4" /><path d="M6 12v8" /><path d="M13.199 14.399a2 2 0 1 0 -1.199 3.601" /><path d="M12 4v10" /><path d="M12 18v2" /><path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M18 4v1" /><path d="M18 9v2.5" /><path d="M17.001 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" /></svg>
                                </div>
                                <div class = "text-start" style = "border-radius: 10px; padding: 10px;">
                                    <p class = "fw-bold mb-1">
                                        <strong>Control de acceso</strong>
                                    </p>
                                    <p class = "mb-0">
                                        Admin, gerente y cajero con sus propios permisos.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class = "border border-primary border-2 mb-3" style = "border-radius: 10px;">
                            <div class = "d-flex align-items-center ms-2 mt-2 mb-2" style = "gap: 15px;">
                                <div style = "border-radius: 10px; width: 60px; height: 60px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-bolt"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M20.984 12.53a9 9 0 1 0 -7.552 8.355" /><path d="M12 7v5l3 3" /><path d="M19 16l-2 3h4l-2 3" /></svg>
                                </div>
                                <div class = "text-start" style = "border-radius: 10px; padding: 10px;">
                                    <p class = "fw-bold mb-1">
                                        <strong>Reportes en tiempo real</strong>
                                    </p>
                                    <p class = "mb-0">
                                        Ventas, corte de caja y inventario al instante.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- <a href = "#" class = "text-white" download>
                            <p class = "small mt-3 mb-0">
                                ¿Nesecitas saber como funciona nuestro sistema?
                            </p>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src = "js/configuration.js"></script>
    <script>
        const errorEnUrl = new URLSearchParams(window.location.search);
        const error = errorEnUrl.get('error');
        
        if (error && (error === '1' || error === '2' || error === '3' || error === '4' || error === '5')) {
            const errorModal = new bootstrap.Modal(document.getElementById('error'));
            errorModal.show();
            
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>