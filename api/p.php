<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Document
    </title>
</head>
<body>
    <div class="dropdown mb-3 contenedor-tiendas" id="contenedor_tiendas">
        
    </div>
</body>
</html>


<script>
    function cargarTiendas() {
        $.getJSON('../tiendas/list_tiendas.php', function (resp) {
            if (!resp.ok) {
                alert("Error al cargar usuarios");
                return;
            }
            const rows = resp.data.map(data => {
                return `
                <div class="col-md-4 tarjeta_tienda">
                    <div class="card shadow-sm mb-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="p-0">
                                    <i class="bi bi-shop fs-3" style="color: #1A2B4A"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h6 fw-bold mb-0">${data.nombre_tienda}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });
            $('#contenedor_tiendas').html(rows.join(''));
        });
    }
    cargarTiendas();
</script>