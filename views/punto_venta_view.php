<div id="alertBox" class="mt-3"></div>
<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Punto de venta</h1>
        <div>
            <button id="btn_generar_venta" class="boton-azul-hover btn bg-primary me-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
                Generar Venta
            </button>
        </div>
    </div>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <input type="text" id="busqueda_producto" class="form-control border-primary" placeholder="Buscar producto">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabletventa">
                    <thead>
                        <tr>
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>CANTIDAD</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

    function showAlert(type, msg){
        $('#alertBox').html(
            `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${msg} 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
        )
    }

    $(document).on('keypress', '#busqueda_producto', function(e) {
        if (e.which === 13) { //enter
            let codigo= $(this).val();
            $.getJSON('../api/inventario/get_one_producto.php', { codigo: codigo }, function(resp) {
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'Error');
                    return;
                }

                let p= resp.data;

                let fila= `
                    <tr>
                        <td>${p.codigo}</td>
                        <td>${p.nombre_producto}</td>
                        <td>${p.precio_venta}</td>
                        <td>${p.stock}</td>
                    </tr>
                `;

                $('#tabletventa tbody').append(fila);
            });
        }
    });
    })

</script>





