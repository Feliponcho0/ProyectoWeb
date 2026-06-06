<div id="alertBox" class="mt-3"></div>
<div class="pb-2 mb-0">
    <h1 class="tipoLetra fw-semibold pb-2 fs-4">Corte de Caja</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>CAJERO</th>
                            <th>FONDO INICIAL</th>
                            <th>TOTAL SISTEMA</th>
                            <th>EFECTIVO CONTADO</th>
                            <th>DIFERENCIA</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="contenedor_cajeros">
                        <tr>
                            <td colspan="7" class="text-center text-muted">Cargando...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal iniciar turno -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_iniciar_turno">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Iniciar Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cajero_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cajero</label>
                    <input type="text" id="cajero_nombre" class="form-control" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fondo inicial</label>
                    <input type="number" id="saldo_inicial" class="form-control border-primary" placeholder="$0.00" min="0" step="0.01">
                </div>
            </div>
            <div class="modal-footer gap-3">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_confirmar_iniciar" class="btn btn-success">Iniciar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cerrar turno -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_cerrar_turno">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cerrar Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="corte_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cajero</label>
                    <input type="text" id="cerrar_cajero_nombre" class="form-control" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Efectivo contado</label>
                    <input type="number" id="efectivo_real" class="form-control border-primary" placeholder="$0.00" min="0" step="0.01">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea id="observaciones" class="form-control border-primary" rows="2" placeholder="Notas del cierre..."></textarea>
                </div>
            </div>
            <div class="modal-footer gap-3">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_confirmar_cerrar" class="btn btn-primary">Cerrar Turno</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const modalIniciar = new bootstrap.Modal(document.getElementById('modal_iniciar_turno'));
    const modalCerrar = new bootstrap.Modal(document.getElementById('modal_cerrar_turno'));

    function showAlert(type, msg) {
        $('#alertBox').html(
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`
        );
    }

    function cargarCajeros() {
        $.getJSON('../api/corte_turno/get_turnos.php', function(resp) {
            if (!resp.ok) return;

            let html = '';
            resp.data.forEach(c => {
                let estado, acciones, fondo, sistema, efectivo, diferencia;

                if (!c.corte_caja_id) {
                    estado = '<span class="badge bg-secondary">Pendiente</span>';
                    acciones = `<button class="btn btn-sm btn-success btn_iniciar" data-id="${c.usuarios_id}" data-nombre="${c.nombre_usuario}">Iniciar</button>`;
                    fondo = '-'; sistema = '-'; efectivo = '-'; diferencia = '-';

                } else if (!c.fecha_fin) {
                    estado = '<span class="badge bg-success">Activo</span>';
                    acciones = `<button class="btn btn-sm btn-danger btn_cerrar" data-corte="${c.corte_caja_id}" data-nombre="${c.nombre_usuario}">Cerrar</button>`;
                    fondo = '$' + parseFloat(c.saldo_inicial).toFixed(2);
                    sistema = '-'; efectivo = '-'; diferencia = '-';

                } else {
                    estado = '<span class="badge bg-danger">Cerrado</span>';
                    acciones = '-';
                    fondo = '$' + parseFloat(c.saldo_inicial).toFixed(2);
                    sistema = '$' + parseFloat(c.total_sistema).toFixed(2);
                    efectivo = '$' + parseFloat(c.ingresos_efectivo).toFixed(2);
                    diferencia = '$' + parseFloat(c.diferencia).toFixed(2);
                }

                html += `
                    <tr>
                        <td>${c.nombre_usuario}</td>
                        <td>${fondo}</td>
                        <td>${sistema}</td>
                        <td>${efectivo}</td>
                        <td>${diferencia}</td>
                        <td>${estado}</td>
                        <td>${acciones}</td>
                    </tr>
                `;
            });
            $('#contenedor_cajeros').html(html);
        });
    }

    cargarCajeros();

    // Abrir modal iniciar
    $(document).on('click', '.btn_iniciar', function() {
        $('#cajero_id').val($(this).data('id'));
        $('#cajero_nombre').val($(this).data('nombre'));
        $('#saldo_inicial').val('');
        modalIniciar.show();
    });

    // Confirmar iniciar turno
    $('#btn_confirmar_iniciar').click(function() {
        const cajero_id = $('#cajero_id').val();
        const saldo_inicial = $('#saldo_inicial').val();

        if (!saldo_inicial || saldo_inicial <= 0) {
            showAlert('danger', 'Ingresa un fondo inicial válido.');
            return;
        }

        $.post('../api/corte_turno/iniciar_turno.php', {
            cajero_id: cajero_id,
            saldo_inicial: saldo_inicial
        }, function(resp) {
            try { resp = JSON.parse(resp); } catch(e) { resp = { ok: false }; }
            if (resp.ok) {
                modalIniciar.hide();
                showAlert('success', 'Turno iniciado correctamente.');
                cargarCajeros();
            } else {
                showAlert('danger', resp.msg || 'Error al iniciar turno.');
            }
        });
    });

    // Abrir modal cerrar
    $(document).on('click', '.btn_cerrar', function() {
        $('#corte_id').val($(this).data('corte'));
        $('#cerrar_cajero_nombre').val($(this).data('nombre'));
        $('#efectivo_real').val('');
        $('#observaciones').val('');
        modalCerrar.show();
    });

    // Confirmar cerrar turno
    $('#btn_confirmar_cerrar').click(function() {
        const corte_id = $('#corte_id').val();
        const efectivo_real = $('#efectivo_real').val();
        const observaciones = $('#observaciones').val();

        if (!efectivo_real || efectivo_real < 0) {
            showAlert('danger', 'Ingresa el efectivo contado.');
            return;
        }

        $.post('../api/corte_turno/cerrar_turno.php', {
            corte_id: corte_id,
            efectivo_real: efectivo_real,
            observaciones: observaciones
        }, function(resp) {
            try { resp = JSON.parse(resp); } catch(e) { resp = { ok: false }; }
            if (resp.ok) {
                modalCerrar.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Turno cerrado',
                    html: `Total sistema: <strong>$${resp.total_sistema}</strong><br>
                           Efectivo real: <strong>$${resp.total_real}</strong><br>
                           Diferencia: <strong>$${resp.diferencia}</strong>`,
                    confirmButtonText: 'Aceptar'
                });
                cargarCajeros();
            } else {
                showAlert('danger', resp.msg || 'Error al cerrar turno.');
            }
        });
    });
});
</script>