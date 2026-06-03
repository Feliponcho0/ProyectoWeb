<!-- perfil_view.php -->
<div class="pb-2 mb-0">
    <h1 class="tipoLetra fw-semibold pb-2 fs-4">Mi Perfil</h1>
</div>

<div id="alertBox" class="mb-3"></div>

<div class="row">
    <div class="col-md-6">
        <div class="card p-4 shadow mb-4">
            <h5 class="mb-3">Información personal</h5>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre de usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" value="<?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>" readonly disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" value="<?php echo htmlspecialchars($_SESSION['correo']); ?>" readonly disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Rol</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['rol']); ?>" readonly disabled>
            </div>
        </div>
    </div>

    <!-- Cambiar contraseña -->
    <div class="col-md-6">
        <div class="card p-4 shadow mb-4">
            <h5 class="mb-3">Cambiar contraseña</h5>
            <form id="formCambiarPassword">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contraseña actual</label>
                    <input type="password" class="form-control" id="password_actual" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nueva contraseña</label>
                    <input type="password" class="form-control" id="password_nueva" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirmar nueva contraseña</label>
                    <input type="password" class="form-control" id="password_confirmar" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formCambiarPassword').on('submit', function(e) {
        e.preventDefault();
        
        const passActual = $('#password_actual').val();
        const passNueva = $('#password_nueva').val();
        const passConfirmar = $('#password_confirmar').val();
        
        if (passNueva !== passConfirmar) {
            showAlert('danger', 'Las contraseñas nuevas no coinciden');
            return;
        }
        
        if (passNueva.length < 6) {
            showAlert('danger', 'La contraseña debe tener al menos 6 caracteres');
            return;
        }
        
        $.post('../api/usuarios/cambiar_password.php', {
            password_actual: passActual,
            password_nueva: passNueva
        }, function(resp) {
            if (resp.ok) {
                showAlert('success', 'Contraseña actualizada correctamente');
                setTimeout(function() {
                    window.location.href = '../index.html';
                }, 1000);
            } else {
                showAlert('danger', resp.msg || 'Error al cambiar la contraseña');
            }
        }, 'json').fail(function() {
            showAlert('danger', 'Error de conexión con el servidor');
        });
    });
    
    function showAlert(type, msg) {
        $('#alertBox').html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        setTimeout(() => $('.alert').fadeOut(), 4000);
    }
});
</script>