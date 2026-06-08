<div id="alertBox" class="mt-3"></div>
<div class="pb-2 mb-0">
    <h1 class="tipoLetra fw-semibold pb-2 fs-4">Corte de Caja
        <small class="fs-6 fw-normal text-muted ms-2" id="fecha_hoy"></small>
    </h1>

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

<!-- Modal ver detalles -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_detalles_corte">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Detalles del Corte de Caja
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalle_corte_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const hoy = new Date();
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('fecha_hoy').textContent = hoy.toLocaleDateString('es-MX', opciones);

    let intervaloActualizacion = null;

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function mostrarDetallesCorte(data, nombreCajero) {
        const fechaInicio = data.fecha_inicio && data.fecha_inicio !== '-' ? new Date(data.fecha_inicio).toLocaleString('es-MX') : '-';
        const fechaFin = data.fecha_fin && data.fecha_fin !== '-' ? new Date(data.fecha_fin).toLocaleString('es-MX') : '-';
        
        const diferenciaValor = parseFloat(data.diferencia);
        const diferenciaSigno = diferenciaValor >= 0 ? '+' : '-';
        
        let ventasHtml = '';
        if (data.ventas && data.ventas.length > 0) {
            ventasHtml = `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <small class="text-muted text-uppercase">Ventas realizadas</small>
                            <div class="table-responsive mt-2" style="max-height: 300px;">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Folio</th>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.ventas.map(venta => `
                                            <tr>
                                                <td>${escapeHtml(venta.folio || venta.id || venta.folio_venta || '-')}</td>
                                                <td>${venta.fecha_venta || venta.fecha ? new Date(venta.fecha_venta || venta.fecha).toLocaleString('es-MX') : '-'}</td>
                                                <td>$${parseFloat(venta.total || venta.monto_total || 0).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2" class="text-end fw-bold">Total ventas:</td>
                                            <td class="fw-bold">$${parseFloat(data.total_sistema).toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (data.num_ventas && parseInt(data.num_ventas) > 0) {
            ventasHtml = `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <small class="text-muted text-uppercase">Ventas realizadas</small>
                            <p class="text-muted mt-2 mb-0">No hay detalles de ventas disponibles</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        const html = `
            <div class="card border-0">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted text-uppercase">Cajero</small>
                                <h5 class="mb-0">${escapeHtml(nombreCajero)}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted">Fecha de Inicio</small>
                                <p class="mb-0 fw-semibold">${fechaInicio}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <small class="text-muted">Fecha de Cierre</small>
                                <p class="mb-0 fw-semibold">${fechaFin}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted">Fondo Inicial</small>
                                <h4 class="mb-0">$${data.saldo_inicial}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted">Total Sistema</small>
                                <h4 class="mb-0">$${data.total_sistema}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted">Efectivo Contado</small>
                                <h4 class="mb-0">$${data.total_real}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="border rounded p-3 text-center">
                                <small class="text-muted">Diferencia</small>
                                <h4 class="mb-0">${diferenciaSigno} $${Math.abs(diferenciaValor).toFixed(2)}</h4>
                            </div>
                        </div>
                    </div>
                    
                    ${ventasHtml}
                    
                    ${data.observaciones ? `
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <small class="text-muted">Observaciones</small>
                                <p class="mb-0">${escapeHtml(data.observaciones)}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        $('#detalle_corte_body').html(html);
    }

    function cargarCajeros() {
        $.getJSON('../api/corte_turno/get_turnos.php', function(resp) {
            if (!resp.ok) return;

            let html = '';
            resp.data.forEach(c => {
                let estado, acciones, fondo, sistema, efectivo, diferencia;

                if (!c.corte_caja_id) {
                    estado = '<span class="badge bg-secondary">Pendiente</span>';
                    acciones = `<button class="btn btn-sm btn-success btn_iniciar" data-id="${c.usuarios_id}" data-nombre="${c.nombre_usuario}">Iniciar Turno</button>`;
                    fondo = '-'; 
                    sistema = '-'; 
                    efectivo = '-'; 
                    diferencia = '-';

                } else if (!c.fecha_fin || c.fecha_fin === '0000-00-00 00:00:00') {
                    estado = '<span class="badge bg-success">Activo</span>';
                    acciones = `<button class="btn btn-sm btn-danger btn_cerrar" data-corte="${c.corte_caja_id}" data-nombre="${c.nombre_usuario}">Cerrar Turno</button>`;
                    fondo = '$' + parseFloat(c.saldo_inicial || 0).toFixed(2);
                    sistema = '$' + parseFloat(c.total_sistema || 0).toFixed(2);
                    efectivo = '<span class="text-muted">Pendiente</span>';
                    diferencia = '<span class="text-muted">Pendiente</span>';

                } else {
                    estado = '<span class="badge bg-danger">Cerrado</span>';
                    acciones = `
                        <button class="btn btn-sm btn-info btn_ver_detalles me-1" 
                                data-corte='${JSON.stringify(c).replace(/'/g, "&apos;")}'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Ver detalles
                        </button>
                    `;
                    fondo = '$' + parseFloat(c.saldo_inicial || 0).toFixed(2);
                    sistema = '$' + parseFloat(c.total_sistema || 0).toFixed(2);
                    efectivo = '$' + parseFloat(c.ingresos_efectivo || 0).toFixed(2);
                    const diferenciaValor = parseFloat(c.diferencia || 0);
                    diferencia = (diferenciaValor >= 0 ? '+' : '-') + '$' + Math.abs(diferenciaValor).toFixed(2);
                }

                html += `
                    <tr>
                        <td><strong>${escapeHtml(c.nombre_usuario)}</strong></td>
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

    function actualizarTotalesEnTiempoReal() {
        $.getJSON('../api/corte_turno/get_turnos_tiempo_real.php', function(resp) {
            if (resp.ok && resp.data) {
                resp.data.forEach(turno => {
                    const fila = $(`button[data-corte="${turno.corte_caja_id}"]`).closest('tr');
                    if (fila.length) {
                        fila.find('td:eq(2)').html('$' + parseFloat(turno.total_sistema).toFixed(2));
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        const modalIniciar = new bootstrap.Modal(document.getElementById('modal_iniciar_turno'));
        const modalCerrar = new bootstrap.Modal(document.getElementById('modal_cerrar_turno'));
        const modalDetalles = new bootstrap.Modal(document.getElementById('modal_detalles_corte'));

        function showAlert(type, msg) {
            $('#alertBox').html(
                `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`
            );
            
            setTimeout(() => {
                $('#alertBox .alert').alert('close');
            }, 5000);
        }

        $(document).on('click', '.btn_ver_detalles', function() {
            try {
                const corteData = $(this).attr('data-corte');
                const cleanData = corteData.replace(/&apos;/g, "'");
                const c = JSON.parse(cleanData);
                
                const detalleData = {
                    saldo_inicial: parseFloat(c.saldo_inicial || 0).toFixed(2),
                    total_sistema: parseFloat(c.total_sistema || 0).toFixed(2),
                    total_real: parseFloat(c.ingresos_efectivo || 0).toFixed(2),
                    diferencia: parseFloat(c.diferencia || 0).toFixed(2),
                    num_ventas: c.num_ventas || '0',
                    fecha_inicio: c.fecha_inicio || '-',
                    fecha_fin: c.fecha_fin || '-',
                    observaciones: c.observaciones || '',
                    ventas: c.ventas || []
                };
                
                mostrarDetallesCorte(detalleData, c.nombre_usuario);
                modalDetalles.show();
            } catch(e) {
                console.error('Error al parsear datos:', e);
                showAlert('danger', 'Error al cargar los detalles del corte');
            }
        });

        $(document).on('click', '.btn_iniciar', function() {
            $('#cajero_id').val($(this).data('id'));
            $('#cajero_nombre').val($(this).data('nombre'));
            $('#saldo_inicial').val('');
            modalIniciar.show();
        });

        $('#btn_confirmar_iniciar').click(function() {
            const cajero_id = $('#cajero_id').val();
            const saldo_inicial = $('#saldo_inicial').val();

            if (!saldo_inicial || saldo_inicial <= 0) {
                showAlert('danger', 'Ingresa un fondo inicial valido.');
                return;
            }

            $.post('../api/corte_turno/iniciar_turno.php', {
                cajero_id: cajero_id,
                saldo_inicial: saldo_inicial
            }, function(resp) {
                try { 
                    resp = JSON.parse(resp); 
                } catch(e) { 
                    resp = { ok: false }; 
                }
                if (resp.ok) {
                    modalIniciar.hide();
                    showAlert('success', 'Turno iniciado correctamente.');
                    cargarCajeros();
                } else {
                    showAlert('danger', resp.msg || 'Error al iniciar turno.');
                }
            });
        });

        $(document).on('click', '.btn_cerrar', function() {
            $('#corte_id').val($(this).data('corte'));
            $('#cerrar_cajero_nombre').val($(this).data('nombre'));
            $('#efectivo_real').val('');
            $('#observaciones').val('');
            modalCerrar.show();
        });

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
                try { 
                    resp = JSON.parse(resp); 
                } catch(e) { 
                    resp = { ok: false }; 
                }
                if (resp.ok) {
                    modalCerrar.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Turno cerrado',
                        html: `Total sistema: <strong>$${parseFloat(resp.total_sistema).toFixed(2)}</strong><br>
                        Efectivo real: <strong>$${parseFloat(resp.total_real).toFixed(2)}</strong><br>
                        Diferencia: <strong>${parseFloat(resp.diferencia) >= 0 ? '+' : '-'}$${Math.abs(parseFloat(resp.diferencia)).toFixed(2)}</strong>`,
                        confirmButtonText: 'Aceptar'
                    });
                    cargarCajeros();
                } else {
                    showAlert('danger', resp.msg || 'Error al cerrar turno.');
                }
            });
        });

        cargarCajeros();
        
        intervaloActualizacion = setInterval(function() {
            cargarCajeros();
        }, 15000);
    });
</script>