<div class="pb-2 mb-0">
    <!-- Bienvenida -->
    <div class="card border-0 shadow-sm mb-0 p-3">
        <h1 class="tipoLetra fw-bold fs-2 text-black mb-0">
            Panel de administración de control de sucursales 
        </h1>
    </div>
    <div class="row g-4 mt-1">

        <!-- Tiendas activas -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-shop fs-1" style="color: #012e46;"></i>
                        <div>
                            <p class="text-muted small mb-1">Tiendas activas</p>
                            <h3 class="fw-bold mb-0" id="total_tiendas">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios activos -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-people fs-1" style="color: #012e46;"></i>
                        <div>
                            <p class="text-muted small mb-1">Usuarios activos</p>
                            <h3 class="fw-bold mb-0" id="total_usuarios">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Ventas por tienda -->
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-body p-4">
            
            <h6 class="fw-bold mb-3" id="titulo_tiendas"></h6>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>TIENDA</th>
                            <th>VENTAS DEL DÍA</th>
                            <th>NÚMERO DE VENTAS</th>
                        </tr>
                    </thead>
                    <tbody id="contenedor_ventas_tiendas">
                        <tr>
                            <td colspan="3" class="text-center text-muted">Cargando...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        // Obtener fecha actual y formatearla de forma descriptiva
        const fecha= new Date();
        const fechaDescriptiva = fecha.toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        $('#titulo_tiendas').text(`Ventas del día ${fechaDescriptiva}`);

        $.getJSON('../api/dashboard/get_dashboard_admin.php', function(resp) {
            if (resp.ok) {
                $('#total_tiendas').text(resp.data.total_tiendas);
                $('#total_usuarios').text(resp.data.total_usuarios);

                let html = '';
                resp.data.ventas_tiendas.forEach(t => {
                    html += `
                        <tr>
                            <td>${t.nombre_tienda}</td>
                            <td>$${parseFloat(t.total_dia).toFixed(2)}</td>
                            <td>${t.num_ventas}</td>
                        </tr>
                    `;
                });
                $('#contenedor_ventas_tiendas').html(html);
            }
        });
    });
</script>