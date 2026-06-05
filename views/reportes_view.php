
<div id="alertBox" class="mt-3"></div>
<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Reporte de Ventas</h1>
        <button id="btn_exportar_pdf" class="btn btn-danger text-white" disabled>
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </button>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4 mt-3">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha inicio</label>
                    <input type="date" id="fecha_inicio" class="form-control border-primary">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha fin</label>
                    <input type="date" id="fecha_fin" class="form-control border-primary">
                </div>
                <div class="col-md-4">
                    <button id="btn_buscar" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" id="card_grafica" style="display:none;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Ventas por día</h6>
            <canvas id="graficaVentas" height="100"></canvas>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm" id="card_tabla" style="display:none;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>TICKET</th>
                            <th>FECHA</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="contenedor_reporte"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <h5 class="fw-bold">Total: $<span id="total_general">0.00</span></h5>
            </div>
        </div>
    </div>
</div>

<!--JQuery-->
<script>
    function showAlert(type, msg) {
        $('#alertBox').html(
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`
        );
    }

    // Botón de buscar
    $('#btn_buscar').click(function(){
        const fecha_inicio = $('#fecha_inicio').val();
        const fecha_fin = $('#fecha_fin').val();

        // Validar que ambas fechas estén seleccionadas
        if (!fecha_inicio || !fecha_fin) {
            showAlert('danger', 'Selecciona ambas fechas para buscar.');
            return;
        }

        // Validar que la fecha inicio no sea mayor a la fecha fin
        if (fecha_inicio > fecha_fin) {
            showAlert('danger', 'La fecha inicio no puede ser mayor a la fecha fin.');
            return;
        }

        $.getJSON('../api/reportes/get_reporte.php', { fecha_inicio, fecha_fin }, function(resp) {
            if (!resp.ok) {
                showAlert('danger', 'Error al obtener el reporte.');
                return;
            }

            // Validar que haya ventas en ese rango de fechas
            if (resp.data.length === 0) {
                showAlert('warning', 'No se encontraron ventas en ese rango de fechas.');
                $('#card_tabla').hide();// Ocultar la tabla
                $('#btn_exportar_pdf').prop('disabled', true);// Deshabilitar el botón de exportar PDF
                return;
            }

        let data = resp.data;
        let html = '';

        data.forEach(v => {
            html += `
                <tr>
                    <td>${v.numero_ticket}</td>
                    <td>${v.fecha}</td>
                    <td>$${v.total}</td>
                </tr>
            `;
        });

            $('#contenedor_reporte').html(html);
            $('#total_general').text(parseFloat(resp.total_general).toFixed(2));

            $('#card_tabla').show();
            $('#btn_exportar_pdf').prop('disabled', false);

        })


    })

    $('#btn_exportar_pdf').click(function(){
        const fecha_inicio = $('#fecha_inicio').val();
        const fecha_fin = $('#fecha_fin').val();

        if(!fecha_inicio || !fecha_fin){
            showAlert('danger', 'Selecciona fechas primero');
            return;
        }


        window.open('../api/reportes/reporte_pdf.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin,'_blank');
    });

</script>
