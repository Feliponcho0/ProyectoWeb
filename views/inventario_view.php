<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Inventario</h1>
        <button id="btn_agregar_producto" class="boton-azul-hover btn bg-primary me-2 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
            Agregar Producto
        </button>
    </div>
    <div class="d-flex gap-2 mb-4">
        <div class="input-group border border-primary rounded w-50">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input id="search_productos" class="form-control border-1" type="search" placeholder="Buscar producto...">
        </div>
        <select id="filtro_estatus" class="form-select border border-primary w-auto">
            <option value="">Todas los productos</option>
            <option value="1" selected>Activos</option>
            <option value="0">Inactivos</option>
        </select>   
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabletstudent">
                    <thead>
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th>COSTO</th>
                        <th>PRECIO</th>
                        <th>STOCK</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                    </thead>
                    <thead>
                        <tbody id="contenedor_productos">
                            <tr>
                                <td class="text-center text-secondary p-4" colspan="7">Cargando...</td>
                            </tr>
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    <!-- Modal agregar producto -->
    <div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_agregar_producto">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-3">
                        <i class="bi bi-shop-window fs-2" style="color: #1A2B4A"></i>
                        Agregar Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_agregar_producto" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="small">Código</h2>
                                <div class="mb-3">
                                    <input type="text" name="codigo" class="form-control" placeholder="Código" minlength="5" maxlength="20" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="small">Nombre</h2>
                                <div class="mb-3">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" minlength="2" maxlength="100" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h2 class="small">Costo</h2>
                                <div class="mb-3">
                                    <input type="number" name="costo" class="form-control" placeholder="Costo" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="small">Precio</h2>
                                <div class="mb-3">
                                    <input type="number" name="precio" class="form-control" placeholder="Precio" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h2 class="small">Stock</h2>
                                <div class="mb-3">
                                    <input type="number" name="stock" class="form-control" placeholder="Stock" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-3">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Modal editar producto -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_editar_producto">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-3">
                    <i class="bi bi-shop-window fs-2" style="color: #1A2B4A"></i>
                    Editar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_editar_producto" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">Código</h2>
                            <div class="mb-3">
                                <input type="text" name="codigo" class="form-control" placeholder="Código" minlength="5" maxlength="20" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Nombre</h2>
                            <div class="mb-3">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre" minlength="2" maxlength="100" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h2 class="small">Costo</h2>
                            <div class="mb-3">
                                <input type="number" name="costo" class="form-control" placeholder="Costo" min="0" step="0.01" required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Precio</h2>
                            <div class="mb-3">
                                <input type="number" name="precio" class="form-control" placeholder="Precio" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h2 class="small">Stock</h2>
                            <div class="mb-3">
                                <input type="number" name="stock" class="form-control" placeholder="Stock" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-3">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Editar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--JQUERY-->
<script>
    $(document).ready(function() {
        const modalAgregar = new bootstrap.Modal(document.getElementById('modal_agregar_producto'));
        const modaledit = new bootstrap.Modal(document.getElementById('modal_editar_producto'));

        $('#btn_agregar_producto').click(function() {
            $('#form_agregar_producto')[0].reset();
            modalAgregar.show();
        });

        function cargarProductos() {
            $.getJSON('../api/inventario/list_productos.php', function(resp) {
                if (resp.ok){
                    const data= resp.data;
                    let html = '';
                    data.forEach(producto => {

                        let stockCantidad='';
                        if (producto.stock <=5){
                            stockCantidad = `<span class="badge bg-danger">${producto.stock}</span>`
                        }
                        html += `
                            <tr>
                                <td>${producto.codigo}</td>
                                <td>${producto.nombre_producto}</td>
                                <td>${producto.precio_compra}</td>
                                <td>${producto.precio_venta}</td>
                                <td>${producto.stock}</td>
                                <td>${producto.activo == 1 ? 'Activo' : 'Inactivo'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn_editar" data-id="${producto.id}">Editar</button>
                                    <button class="btn btn-sm btn-danger btn_eliminar" data-id="${producto.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#contenedor_productos').html(html);
                }

            });
        }

        $("#search_productos").on("keyup", function() {
            var textoEscrito = $(this).val().toLowerCase();

            $("#contenedor_productos tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().includes(textoEscrito))
            });
        });

        cargarProductos();
        function showAlert(type, msg){
            $('#alertBox').html(
                `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg} 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            )
        }
    });
</script>

