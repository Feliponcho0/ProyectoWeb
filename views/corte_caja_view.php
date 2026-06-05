<?php

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../index.php?error=2");
    exit();
}
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
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Efectivo contado</th>
                        <th style="color: #4a5568; font-weight: 600; padding: 14px 16px;">Diferencia</th>
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
                            <tr data-usuario-id="<?php echo $cajero['usuarios_id']; ?>"
                                data-usuario-nombre="<?php echo htmlspecialchars($cajero['nombre_usuario']); ?>"
                                data-corte-id="">
                                <td style="padding: 12px 16px;">
                                    <i class="bi bi-person me-2" style="color: #6c757d;"></i>
                                    <strong style="color: #1a2a3a;"><?php echo htmlspecialchars($cajero['nombre_usuario']); ?></strong>
                                </td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="col-fondo">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="col-ventas">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="col-efectivo">-</td>
                                <td style="color: #adb5bd; padding: 12px 16px;" class="col-diferencia">-</td>
                                <td style="padding: 12px 16px;" class="col-estado">
                                    <span class="badge px-3 py-2" style="background-color: #e9ecef; color: #6c757d; border-radius: 20px;">
                                        Pendiente
                                    </span>
                                </td>
                                <td style="padding: 12px 16px;" class="col-acciones">
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

<!-- Modal Abrir Turno -->
<div class="modal fade" id="modalAbrirTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-person-plus-fill me-2"></i> Abrir turno
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

<!-- Modal Cerrar Turno -->
<div class="modal fade" id="modalCerrarTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-lock-fill me-2"></i> Cerrar turno
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
                            <i class="bi bi-cash-stack me-1"></i> Total ventas sistema
                        </label>
                        <input type="text" class="form-control" id="totalVentas" readonly disabled style="background-color: #e9ecef;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #1a2a3a;">
                            <i class="bi bi-cash me-1"></i> Efectivo contado
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

<!-- Modal Ver Turno -->
<div class="modal fade" id="modalVerTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" style="color: #1a2a3a;">
                    <i class="bi bi-eye-fill me-2"></i> Detalle del turno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleTurnoBody"></div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    const modalAbrir  = new bootstrap.Modal(document.getElementById('modalAbrirTurno'));
    const modalCerrar = new bootstrap.Modal(document.getElementById('modalCerrarTurno'));
    const modalVer    = new bootstrap.Modal(document.getElementById('modalVerTurno'));

    // Helpers 
    function fmt(val) {
        return '$' + parseFloat(val || 0).toFixed(2);
    }

    function badgeEstado(estado) {
        const cfg = {
            pendiente: { bg: '#e9ecef', color: '#6c757d', label: 'Pendiente' },
            activo:    { bg: '#d1fae5', color: '#065f46', label: 'Turno activo' },
            cerrado:   { bg: '#fee2e2', color: '#991b1b', label: 'Cerrado' }
        };
        const s = cfg[estado] || cfg.pendiente;
        return `<span class="badge px-3 py-2" style="background-color:${s.bg};color:${s.color};border-radius:20px;">${s.label}</span>`;
    }

    //  Cargar turnos del día 
    function cargarTurnosActivos() {
        $.getJSON('../api/corte_turno/get_turnos.php', function (resp) {
            if (!resp.ok) return;

            resp.data.forEach(function (t) {
                const $fila = $('#tablaTurnosBody tr[data-usuario-id="' + t.usuarios_id + '"]');
                if ($fila.length === 0) return;

                $fila.attr('data-corte-id', t.corte_caja_id || '');

                if (t.estado === 'pendiente') {
                    $fila.find('.col-fondo').text('-').css('color', '#adb5bd');
                    $fila.find('.col-ventas').text('-').css('color', '#adb5bd');
                    $fila.find('.col-efectivo').text('-').css('color', '#adb5bd');
                    $fila.find('.col-diferencia').text('-').css('color', '#adb5bd');
                    $fila.find('.col-estado').html(badgeEstado('pendiente'));
                    $fila.find('.btn-iniciar').show();
                    $fila.find('.btn-cerrar').hide();
                    $fila.find('.btn-ver').hide();

                } else if (t.estado === 'activo') {
                    $fila.find('.col-fondo').text(fmt(t.saldo_inicial)).css('color', '#1a2a3a');
                    $fila.find('.col-ventas').text(fmt(t.total_sistema)).css('color', '#1a2a3a');
                    $fila.find('.col-efectivo').text('-').css('color', '#adb5bd');
                    $fila.find('.col-diferencia').text('-').css('color', '#adb5bd');
                    $fila.find('.col-estado').html(badgeEstado('activo'));
                    $fila.find('.btn-iniciar').hide();
                    $fila.find('.btn-cerrar').show();
                    $fila.find('.btn-ver').hide();

                } else if (t.estado === 'cerrado') {
                    const dif = parseFloat(t.diferencia || 0);
                    $fila.find('.col-fondo').text(fmt(t.saldo_inicial)).css('color', '#1a2a3a');
                    $fila.find('.col-ventas').text(fmt(t.total_sistema)).css('color', '#1a2a3a');
                    $fila.find('.col-efectivo').text(fmt(t.ingresos_efectivo)).css('color', '#1a2a3a');
                    $fila.find('.col-diferencia')
                        .text(fmt(dif))
                        .css('color', dif < 0 ? '#dc3545' : dif > 0 ? '#198754' : '#6c757d');
                    $fila.find('.col-estado').html(badgeEstado('cerrado'));
                    $fila.find('.btn-iniciar').hide();
                    $fila.find('.btn-cerrar').hide();
                    $fila.find('.btn-ver').show();
                }
            });
        });
    }

    cargarTurnosActivos();

    // ── Iniciar turno ─────────────────────────────────────────
    $(document).on('click', '.btn-iniciar', function () {
        const $fila = $(this).closest('tr');
        $('#cajeroId').val($fila.data('usuario-id'));
        $('#cajeroNombre').val($fila.data('usuario-nombre'));
        $('#fondoInicial').val('');
        modalAbrir.show();
    });

    $('#btnConfirmarApertura').on('click', function () {
        const cajeroId     = $('#cajeroId').val();
        const cajeroNombre = $('#cajeroNombre').val();
        const saldoInicial = parseFloat($('#fondoInicial').val());

        if (!saldoInicial || saldoInicial < 0) {
            Swal.fire({ icon: 'warning', title: 'Monto inválido', text: 'Ingresa un fondo inicial válido mayor a 0.' });
            return;
        }

        Swal.fire({ title: 'Iniciando turno...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.post('../api/corte_turno/iniciar_turno.php', {
            cajero_id:     cajeroId,
            saldo_inicial: saldoInicial
        }, function (resp) {
            Swal.close();
            if (resp.ok) {
                modalAbrir.hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Turno iniciado!',
                    html: `Turno iniciado para <strong>${cajeroNombre}</strong>.<br>Fondo: <strong>${fmt(saldoInicial)}</strong>`,
                    timer: 2500,
                    showConfirmButton: false
                });
                cargarTurnosActivos();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.msg || 'No se pudo iniciar el turno.' });
            }
        }, 'json').fail(function () {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo contactar al servidor.' });
        });
    });

    // ── Cerrar turno ──────────────────────────────────────────
    $(document).on('click', '.btn-cerrar', function () {
        const $fila = $(this).closest('tr');
        $('#cerrarCorteId').val($fila.attr('data-corte-id'));
        $('#cerrarCajeroNombre').val($fila.data('usuario-nombre'));
        $('#totalVentas').val($fila.find('.col-ventas').text());
        $('#efectivoFinal').val('');
        $('#observaciones').val('');
        modalCerrar.show();
    });

    $('#btnConfirmarCierre').on('click', function () {
        const corteId    = $('#cerrarCorteId').val();
        const efectivo   = parseFloat($('#efectivoFinal').val());
        const observaciones = $('#observaciones').val();

        if (isNaN(efectivo) || efectivo < 0) {
            Swal.fire({ icon: 'warning', title: 'Dato inválido', text: 'Ingresa el efectivo final contado.' });
            return;
        }

        Swal.fire({ title: 'Cerrando turno...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.post('../api/corte_turno/cerrar_turno.php', {
            corte_id:      corteId,
            efectivo_real: efectivo,
            observaciones: observaciones
        }, function (resp) {
            Swal.close();
            if (resp.ok) {
                modalCerrar.hide();
                const dif = parseFloat(resp.diferencia || 0);
                const difTxt = dif === 0
                    ? 'Sin diferencia.'
                    : dif > 0
                        ? `Sobrante: <strong style="color:#198754">${fmt(dif)}</strong>`
                        : `Faltante: <strong style="color:#dc3545">${fmt(Math.abs(dif))}</strong>`;
                Swal.fire({
                    icon: 'success',
                    title: '¡Turno cerrado!',
                    html: `Total sistema: <strong>${fmt(resp.total_sistema)}</strong><br>
                           Efectivo real: <strong>${fmt(resp.total_real)}</strong><br>${difTxt}`,
                    confirmButtonText: 'Aceptar'
                });
                cargarTurnosActivos();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: resp.msg || 'No se pudo cerrar el turno.' });
            }
        }, 'json').fail(function () {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo contactar al servidor.' });
        });
    });

    // ── Ver detalle del turno ─────────────────────────────────
    $(document).on('click', '.btn-ver', function () {
        const corteId = $(this).closest('tr').attr('data-corte-id');
        $('#detalleTurnoBody').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        modalVer.show();

        $.getJSON('../api/corte_turno/get_detalle_turno.php', { corte_id: corteId }, function (resp) {
            if (!resp.ok) {
                $('#detalleTurnoBody').html(`<p class="text-danger">${resp.msg}</p>`);
                return;
            }

            const t   = resp.turno;
            const dif = parseFloat(t.diferencia || 0);
            const difColor = dif < 0 ? '#dc3545' : dif > 0 ? '#198754' : '#6c757d';
            const difLabel = dif < 0 ? 'Faltante' : dif > 0 ? 'Sobrante' : 'Sin diferencia';

            let ventasHtml = '<p class="text-muted text-center mt-2">Sin ventas en este turno.</p>';
            if (resp.ventas.length > 0) {
                ventasHtml = `<table class="table table-sm table-bordered mt-2">
                    <thead style="background:#f8f9fa;">
                        <tr><th>Ticket</th><th>Hora</th><th class="text-end">Total</th></tr>
                    </thead><tbody>`;
                resp.ventas.forEach(function (v) {
                    const hora = v.fecha ? v.fecha.split(' ')[1] : '-';
                    ventasHtml += `<tr>
                        <td>#${v.numero_ticket}</td>
                        <td>${hora}</td>
                        <td class="text-end">${fmt(v.total)}</td>
                    </tr>`;
                });
                ventasHtml += '</tbody></table>';
            }

            $('#detalleTurnoBody').html(`
                <div class="row g-3 mb-2">
                    <div class="col-6">
                        <small class="text-muted d-block">Cajero</small>
                        <span class="fw-semibold">${t.nombre_usuario}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Inicio</small>
                        <span class="fw-semibold">${t.fecha_inicio || '-'}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Cierre</small>
                        <span class="fw-semibold">${t.fecha_fin || '-'}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Fondo inicial</small>
                        <span class="fw-semibold">${fmt(t.saldo_inicial)}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Total sistema</small>
                        <span class="fw-semibold">${fmt(t.total_sistema)}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Efectivo contado</small>
                        <span class="fw-semibold">${fmt(t.ingresos_efectivo)}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">${difLabel}</small>
                        <span class="fw-bold" style="color:${difColor};">${fmt(Math.abs(dif))}</span>
                    </div>
                    ${t.observaciones ? `<div class="col-12"><small class="text-muted d-block">Observaciones</small><span>${t.observaciones}</span></div>` : ''}
                </div>
                <hr>
                <h6 class="fw-semibold">Ventas del turno <span class="badge bg-secondary">${resp.ventas.length}</span></h6>
                ${ventasHtml}
            `);
        }).fail(function () {
            $('#detalleTurnoBody').html('<p class="text-danger">Error al cargar el detalle.</p>');
        });
    });

});
</script>
