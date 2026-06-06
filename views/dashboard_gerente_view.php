
<div class="pb-2 mb-0">
    <div class="card border-0 shadow-sm mb-4 p-3 mt-4">
        <h1 class="tipoLetra fw-bold fs-3 text-black mb-0">
            Bienvenido a
            <span>
                <?php echo $nombre_tienda; ?>
            </span>
        </h1>
    </div>
</div>

<div class="row mt-0">
    <!-- Ventas del dia -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cash-coin fs-1" style="color: #012e46;"></i>
                    <div>
                        <p class="text-muted small mb-1">Ventas del día</p>
                        <h3 class="fw-bold mb-0" id="total_dia">$0.00</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Numero de ventas -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-receipt fs-1" style="color: #012e46;"></i>
                    <div>
                        <p class="text-muted small mb-1">Número de ventas</p>
                        <h3 class="fw-bold mb-0" id="num_ventas">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Productos bajo stock -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                    <div>
                        <p class="text-muted small mb-1">Productos bajo stock</p>
                        <h3 class="fw-bold mb-0 text-danger" id="bajo_stock">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---->
<div class="card border-0 shadow-sm mt-2">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3" id="titulo_productos"></h6>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>PRODUCTO</th>
                        <th>CANTIDAD VENDIDA</th>
                    </tr>
                </thead>
                <tbody id="contenedor_mas_vendidos">
                    <tr>
                        <td colspan="2" class="text-center text-muted">Cargando...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--JQuery-->
<script>
$(document).ready(function() {
    const fecha= new Date();
    const fechaDescriptiva = fecha.toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    $('#titulo_productos').text(`Productos más vendidos: ${fechaDescriptiva}`);
    
    $.getJSON('../api/dashboard/get_dashboard_gerente.php', function(resp) {
        if (resp.ok) {
            $('#total_dia').text('$' +resp.data.total_dia);
            $('#num_ventas').text(resp.data.num_ventas);
            $('#bajo_stock').text(resp.data.bajo_stock);

            if (resp.data.mas_vendidos.length > 0) {
                let html = '';
                resp.data.mas_vendidos.forEach(p => {
                    html += `
                        <tr>
                            <td>${p.nombre_producto}</td>
                            <td>${p.cantidad_vendida}</td>
                        </tr>
                    `;
                });
                $('#contenedor_mas_vendidos').html(html);
            } else {
                $('#contenedor_mas_vendidos').html('<tr><td colspan="2" class="text-center text-muted">No hay ventas el dia de hoy</td></tr>');
            }
        }
    });
});

</script>

