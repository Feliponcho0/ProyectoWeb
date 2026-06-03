<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../index.php?error=2");
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light p-4">
                <h1 class="tipoLetra fw-bold fs-3">
                    Bienvenido a
                    <span>
                        <?php echo $nombre_tienda; ?>
                    </span>
                </h1>
            </div>
        </div>
    </div>
</div>