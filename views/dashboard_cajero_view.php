<div class="container-fluid mt-4">
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light p-4">
                <h1 class="tipoLetra fw-bold fs-3">
                    Bienvenido cajero a
                    <span><?php echo $nombre_tienda; ?></span>
                </h1>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-receipt fs-1" style="color: #012e46;"></i>
                        <div>
                            <p class="text-muted small mb-1" id="fecha_cajero"></p>
                            <h3 class="fw-bold mb-0" id="num_ventas">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cash-stack fs-1" style="color: #012e46;"></i>
                        <div>
                            <p class="text-muted small mb-1">Total vendido</p>
                            <h3 class="fw-bold mb-0" id="total_ventas">$0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    const fecha= new Date();
    const fechaDescriptiva = fecha.toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    $('#fecha_cajero').text(`Ventas realizadas hoy: ${fechaDescriptiva}`);

        $.getJSON('../api/dashboard/get_dashboard_cajero.php', function(resp) {
            console.log(resp); 
            if (resp.ok) {
                $('#num_ventas').text(resp.data.num_ventas);
                $('#total_ventas').text('$' +resp.data.total_dia);
            }
        });
    });
</script>