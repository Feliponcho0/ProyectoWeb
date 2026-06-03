<?php
$tienda_id = $_SESSION['tiendas_id'] ?? 0;
$nombre_tienda = "Sin tienda asignada";

if ($tienda_id > 0) {
    $query_tienda = "SELECT nombre_tienda FROM tiendas WHERE tiendas_id = ?";
    $stmt_tienda = $conn->prepare($query_tienda);
    $stmt_tienda->bind_param("i", $tienda_id);
    $stmt_tienda->execute();
    $result_tienda = $stmt_tienda->get_result();
    if ($row_tienda = $result_tienda->fetch_assoc()) {
        $nombre_tienda = $row_tienda['nombre_tienda'];
    }
}

$query_cajeros = "SELECT usuarios_id, nombre_usuario, correo, activo 
                FROM usuarios 
                WHERE rol = 'Cajero' 
                AND tiendas_id = ?
                AND activo = 1
                ORDER BY nombre_usuario ASC";
$stmt_cajeros = $conn->prepare($query_cajeros);
$stmt_cajeros->bind_param("i", $tienda_id);
$stmt_cajeros->execute();
$result_cajeros = $stmt_cajeros->get_result();
$cajeros = [];
while ($row = $result_cajeros->fetch_assoc()) {
    $cajeros[] = $row;
}
?>

<div class="mb-4">
    <h2 class="mb-1" style="color: #1a2a3a; font-weight: 600;">
        <i class="bi bi-calculator-fill me-2" style="color: #2a5298;"></i>
        Corte de caja
    </h2>
    <p class="text-muted mb-0">
        <i class="bi bi-calendar3 me-1"></i> Corte del día: <?php echo date('d/m/Y'); ?>
    </p>
    <p class="text-muted small mt-1">
        <i class="bi bi-shop me-1"></i> Tienda: <strong><?php echo htmlspecialchars($nombre_tienda); ?></strong>
    </p>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 16px 16px 0 0;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-semibold" style="color: #1a2a3a;">
                <i class="bi bi-people-fill me-2" style="color: #2a5298;"></i>
                Control de turnos - Cajeros
                <span class="badge ms-2" style="background-color: #e9ecef; color: #1a2a3a;">
                    <?php echo count($cajeros); ?> cajeros
                </span>
            </h5>
            <button class="btn btn-sm" id="btnNuevoTurno" style="background-color: #136b42; color: white; border-radius: 8px; padding: 6px 16px;">
                <i class="bi bi-plus-circle me-1"></i> Nuevo turno
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" style="background: white;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Cajero</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Fondo inicial</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Ventas turno</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Efectivo</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Tarjeta</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Estado</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaTurnosBody">
                    <?php if (empty($cajeros)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4" style="color: #6c757d;">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2 mb-0">No hay cajeros registrados en esta tienda</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cajeros as $cajero): ?>
                            <tr data-usuario-id="<?php echo $cajero['usuarios_id']; ?>" data-usuario-nombre="<?php echo htmlspecialchars($cajero['nombre_usuario']); ?>">
                                <td style="padding: 12px 16px;">
                                    <i class="bi bi-person me-2" style="color: #6c757d;"></i>
                                    <strong style="color: #1a2a3a;"><?php echo htmlspecialchars($cajero['nombre_usuario']); ?></strong>
                                </td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="fondo-inicial">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="ventas">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="efectivo">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="tarjeta">-</td>
                                <td style="padding: 12px 16px;" class="estado">
                                    <span class="badge px-3 py-2" style="background-color: #e9ecef; color: #6c757d; border-radius: 20px;">
                                        Pendiente
                                    </span>
                                </td>
                                <td style="padding: 12px 16px;" class="acciones">
                                    <button class="btn btn-sm btn-iniciar" style="background-color: #136b42; color: white; border: none; border-radius: 6px; padding: 4px 12px;">
                                        <i class="bi bi-play-fill me-1"></i> Iniciar
                                    </button>
                                    <button class="btn btn-sm btn-cerrar" style="background-color: #dc3545; color: white; border: none; border-radius: 6px; padding: 4px 12px; display: none;">
                                        <i class="bi bi-lock-fill me-1"></i> Cerrar
                                    </button>
                                    <button class="btn btn-sm btn-ver btn-outline-secondary" style="border-radius: 6px; padding: 4px 12px; display: none;">
                                        <i class="bi bi-eye-fill me-1"></i> Ver
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAbrirTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-person-plus-fill me-2"></i> 
                    Abrir turno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #1a2a3a;">
                        <i class="bi bi-person-fill me-1"></i> Cajero
                    </label>
                    <input type="text" class="form-control" id="cajeroNombre" readonly disabled style="background-color: #e9ecef;">
                    <input type="hidden" id="cajeroId">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #1a2a3a;">
                        <i class="bi bi-cash me-1"></i> Fondo inicial
                    </label>
                    <input type="number" class="form-control" id="fondoInicial" placeholder="$0.00" step="100" min="0">
                    <small class="text-muted">Monto con el que el cajero inicia su turno</small>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn" id="btnConfirmarApertura" style="background-color: #136b42; color: white; border: none;">
                    Iniciar turno
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCerrarTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-lock-fill me-2"></i> 
                    Cerrar turno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #1a2a3a;">
                        <i class="bi bi-person-fill me-1"></i> Cajero
                    </label>
                    <input type="text" class="form-control" id="cerrarCajeroNombre" readonly disabled style="background-color: #e9ecef;">
                    <input type="hidden" id="cerrarCorteId">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #1a2a3a;">
                            <i class="bi bi-cash-stack me-1"></i> Total ventas
                        </label>
                        <input type="text" class="form-control" id="totalVentas" readonly disabled style="background-color: #e9ecef;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #1a2a3a;">
                            <i class="bi bi-cash me-1"></i> Efectivo final
                        </label>
                        <input type="number" class="form-control" id="efectivoFinal" placeholder="$0.00" step="10" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #1a2a3a;">
                        <i class="bi bi-chat-text me-1"></i> Observaciones
                    </label>
                    <textarea class="form-control" id="observaciones" rows="2" placeholder="Notas sobre el cierre de turno..."></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn" id="btnConfirmarCierre" style="background-color: #dc3545; color: white; border: none;">
                    Cerrar turno
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-eye-fill me-2"></i> 
                    Detalle del turno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleTurnoBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const modalAbrir = new bootstrap.Modal(document.getElementById('modalAbrirTurno'));
    const modalCerrar = new bootstrap.Modal(document.getElementById('modalCerrarTurno'));
    const modalVer = new bootstrap.Modal(document.getElementById('modalVerTurno'));
    
    // turnos activos
    function cargarTurnosActivos() {
        console.log('Cargando turnos activos...');
        $.getJSON('../api/turnos/turnos_activos.php', function(response) {
            console.log('Respuesta turnos activos:', response);
            if (response.ok) {
                const turnosActivos = response.data;
                
                $('#tablaTurnosBody tr').each(function() {
                    const usuarioId = $(this).data('usuario-id');
                    const turno = turnosActivos.find(t => t.usuarios_id == usuarioId);
                    
                    if (turno) {
                        const ventasTurno = turno.total_sistema - turno.saldo_inicial;
                        
                        $(this).find('.fondo-inicial').text('$' + parseFloat(turno.saldo_inicial).toLocaleString());
                        $(this).find('.ventas').text('$' + ventasTurno.toLocaleString());
                        $(this).find('.efectivo').text('$' + parseFloat(turno.ingresos_efectivo || 0).toLocaleString());
                        $(this).find('.tarjeta').text('$' + (ventasTurno - (turno.ingresos_efectivo || 0)).toLocaleString());
                        $(this).find('.estado').html(`
                            <span class="badge px-3 py-2" style="background-color: #eab30820; color: #b45309; border-radius: 20px;">
                                En curso
                            </span>
                        `);
                        $(this).find('.btn-iniciar').hide();
                        $(this).find('.btn-cerrar').show().data('corte-id', turno.corte_caja_id);
                        $(this).find('.btn-ver').hide();
                        $(this).css('border-left', '3px solid #eab308');
                        $(this).css('background-color', '#fefce8');
                    } else {
                        cargarHistorialCajero(usuarioId, $(this));
                    }
                });
            } else {
                console.error('Error al cargar turnos:', response);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error en petición AJAX:', textStatus, errorThrown);
            console.error('Respuesta:', jqXHR.responseText);
        });
    }
    
    // historial de cajeros
    function cargarHistorialCajero(usuarioId, $fila) {
        const fecha = new Date().toISOString().split('T')[0];
        $.getJSON(`../api/turnos/historial_turnos.php?fecha=${fecha}`, function(response) {
            if (response.ok) {
                const turnoCerrado = response.data.find(t => t.usuarios_id == usuarioId);
                
                if (turnoCerrado) {
                    const ventasTurno = turnoCerrado.total_sistema - turnoCerrado.saldo_inicial;
                    
                    $fila.find('.fondo-inicial').text('$' + parseFloat(turnoCerrado.saldo_inicial).toLocaleString());
                    $fila.find('.ventas').text('$' + ventasTurno.toLocaleString());
                    $fila.find('.efectivo').text('$' + parseFloat(turnoCerrado.ingresos_efectivo || 0).toLocaleString());
                    $fila.find('.tarjeta').text('$' + (ventasTurno - (turnoCerrado.ingresos_efectivo || 0)).toLocaleString());
                    $fila.find('.estado').html(`
                        <span class="badge px-3 py-2" style="background-color: #f8d7da; color: #842029; border-radius: 20px;">
                            Cerrado
                        </span>
                    `);
                    $fila.find('.btn-iniciar').hide();
                    $fila.find('.btn-cerrar').hide();
                    $fila.find('.btn-ver').show().data('corte-id', turnoCerrado.corte_caja_id);
                    $fila.css('opacity', '0.7');
                } else {
                    $fila.find('.fondo-inicial').text('-');
                    $fila.find('.ventas').text('-');
                    $fila.find('.efectivo').text('-');
                    $fila.find('.tarjeta').text('-');
                    $fila.find('.estado').html(`
                        <span class="badge px-3 py-2" style="background-color: #e9ecef; color: #6c757d; border-radius: 20px;">
                            Pendiente
                        </span>
                    `);
                    $fila.find('.btn-iniciar').show();
                    $fila.find('.btn-cerrar').hide();
                    $fila.find('.btn-ver').hide();
                    $fila.css('border-left', 'none');
                    $fila.css('background-color', 'white');
                    $fila.css('opacity', '1');
                }
            }
        });
    }
    
    // iniviar turno
    $(document).on('click', '.btn-iniciar', function() {
        const fila = $(this).closest('tr');
        const cajeroId = fila.data('usuario-id');
        const cajeroNombre = fila.data('usuario-nombre');
        
        $('#cajeroId').val(cajeroId);
        $('#cajeroNombre').val(cajeroNombre);
        $('#fondoInicial').val('');
        
        modalAbrir.show();
    });
    
    $('#btnConfirmarApertura').click(function() {
        const cajeroId = $('#cajeroId').val();
        const cajeroNombre = $('#cajeroNombre').val();
        const saldoInicial = parseFloat($('#fondoInicial').val());
        
        // Validaciones
        if (!cajeroId) {
            Swal.fire('Error', 'No se seleccionó ningún cajero', 'error');
            return;
        }
        
        if (!saldoInicial || saldoInicial < 100) {
            Swal.fire('Error', 'El fondo inicial debe ser de al menos $100', 'error');
            return;
        }
        
        // mostrar loading
        Swal.fire({
            title: 'Iniciando turno...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.post('../api/turnos/iniciar_turno.php', {
            cajero_id: cajeroId,
            saldo_inicial: saldoInicial
        }, function(response) {
            console.log('Respuesta iniciar turno:', response);
            
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Turno iniciado!',
                    html: `Turno iniciado para <strong>${cajeroNombre}</strong><br>Fondo inicial: $${saldoInicial.toLocaleString()}`,
                    timer: 2000,
                    showConfirmButton: false
                });
                modalAbrir.hide();
                cargarTurnosActivos();
            } else {
                Swal.fire('Error', response.msg || 'Error al iniciar turno', 'error');
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error en petición:', textStatus, errorThrown);
            console.error('Respuesta del servidor:', jqXHR.responseText);
            Swal.fire('Error', 'Error de conexión con el servidor.', 'error');
        });
    });
    
    // cerrar turno
    $(document).on('click', '.btn-cerrar', function() {
        const fila = $(this).closest('tr');
        const cajeroNombre = fila.data('usuario-nombre');
        const corteId = $(this).data('corte-id');
        const ventas = fila.find('.ventas').text().replace('$', '').replace(/,/g, '');
        const efectivoActual = fila.find('.efectivo').text().replace('$', '').replace(/,/g, '');
        
        $('#cerrarCajeroNombre').val(cajeroNombre);
        $('#cerrarCorteId').val(corteId);
        $('#totalVentas').val('$' + parseFloat(ventas).toLocaleString());
        $('#efectivoFinal').val(parseFloat(efectivoActual));
        $('#observaciones').val('');
        
        modalCerrar.show();
    });
    
    $('#btnConfirmarCierre').click(function() {
        const corteId = $('#cerrarCorteId').val();
        const efectivoFinal = parseFloat($('#efectivoFinal').val());
        const observaciones = $('#observaciones').val();
        
        if (isNaN(efectivoFinal) || efectivoFinal < 0) {
            Swal.fire('Error', 'Ingresa un monto válido para el efectivo final', 'error');
            return;
        }
        
        Swal.fire({
            title: '¿Cerrar turno?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sí, cerrar turno',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Cerrando turno...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.post('../api/turnos/cerrar_turno.php', {
                    corte_id: corteId,
                    total_real: efectivoFinal,
                    observaciones: observaciones
                }, function(response) {
                    console.log('Respuesta cerrar turno:', response);
                    
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Turno cerrado',
                            html: response.msg,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        modalCerrar.hide();
                        cargarTurnosActivos();
                    } else {
                        Swal.fire('Error', response.msg || 'Error al cerrar turno', 'error');
                    }
                }, 'json');
            }
        });
    });
    
    // detalles
    $(document).on('click', '.btn-ver', function() {
        const corteId = $(this).data('corte-id');
        
        $.getJSON(`../api/turnos/ver_turno.php?id=${corteId}`, function(response) {
            if (response.ok) {
                const t = response.data;
                const fechaInicio = new Date(t.fecha_inicio).toLocaleString();
                const fechaFin = t.fecha_fin ? new Date(t.fecha_fin).toLocaleString() : 'En curso';
                const ventasTurno = t.total_sistema - t.saldo_inicial;
                const diferenciaClass = t.diferencia >= 0 ? 'text-success' : 'text-danger';
                const diferenciaTexto = t.diferencia >= 0 ? 'Sobrante' : 'Faltante';
                
                $('#detalleTurnoBody').html(`
                    <div class="mb-3">
                        <label class="fw-semibold">Cajero:</label>
                        <p>${t.nombre_usuario}</p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Fecha apertura:</label>
                            <p class="small">${fechaInicio}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Fecha cierre:</label>
                            <p class="small">${fechaFin}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Fondo inicial:</label>
                            <p>$${parseFloat(t.saldo_inicial).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Total ventas:</label>
                            <p>$${ventasTurno.toLocaleString()}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Efectivo:</label>
                            <p>$${parseFloat(t.ingresos_efectivo || 0).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Tarjeta:</label>
                            <p>$${(ventasTurno - (t.ingresos_efectivo || 0)).toLocaleString()}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Total sistema:</label>
                            <p>$${parseFloat(t.total_sistema).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Total real:</label>
                            <p>$${parseFloat(t.total_real || 0).toLocaleString()}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold ${diferenciaClass}">${diferenciaTexto}:</label>
                        <p class="${diferenciaClass}">$${Math.abs(t.diferencia || 0).toLocaleString()}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold">Observaciones:</label>
                        <p class="small">${t.observaciones || 'Ninguna'}</p>
                    </div>
                `);
                modalVer.show();
            }
        });
    });
    
    // nuevo turno
    $('#btnNuevoTurno').click(function() {
        
    });
    cargarTurnosActivos();
    
    setInterval(cargarTurnosActivos, 30000);
});
</script>