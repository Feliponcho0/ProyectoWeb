<div id="alertBox" class="mt-3"></div>
<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Inventario</h1>
        <div>
            <button id="btn_agregar_producto" class="boton-azul-hover btn bg-primary me-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
                Agregar Producto
            </button>
        </div>
    </div>
    <div class="d-flex gap-2 mb-4">
        <div class="input-group border border-primary rounded w-50">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input id="search_productos" class="form-control border-1" type="search" placeholder="Buscar producto...">
        </div>
        <select id="filtro_estado_productos" class="form-select border border-primary w-auto">
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
                        <tr>
                            <th>CÓDIGO</th>
                            <th>NOMBRE</th>
                            <th>COSTO</th>
                            <th>PRECIO</th>
                            <th>STOCK</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="contenedor_productos">
                        <tr>
                            <td class="text-center text-secondary p-4" colspan="7">Cargando...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="no_encontrado_productos" class="d-none text-center mt-4">
                <h3 class="text-secondary fs-5">No se encontraron productos.</h3>
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
                    <input type="hidden" name="tiendas_id" value="<?php echo $_SESSION['tiendas_id']; ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="small">Código</h2>
                                <div class="mb-3">
                                    <input type="text" id="codigoProd" name="codigo" class="form-control" placeholder="Código" minlength="5" maxlength="20" required>
                                    <small class="text-mute">Escanea el producto</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="small">Nombre del producto</h2>
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
                        <!--
                        <div class="row mt-3">
                            <div class="col-md-6">

                                <h2 class="small">Imagen del producto</h2>
                                <form id="form_agregar_producto" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="file" name="imagen" accept="image/*" required>
                                    </div>
                                </form>
                            </div>
                        </div>-->
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
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">Nombre</h2>
                            <div class="mb-3">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre" minlength="2" maxlength="100" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Costo</h2>
                            <div class="mb-3">
                                <input type="number" name="costo" class="form-control" placeholder="Costo" min="0" step="0.01" required>
                            </div> 
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h2 class="small">Precio</h2>
                            <div class="mb-3">
                                <input type="number" name="precio" class="form-control" placeholder="Precio" min="0" step="0.01" required>
                            </div>
                        </div>
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
            $('#form_agregar_producto')[0].reset();//resetea el formulario
            modalAgregar.show();
        });

        function cargarProductos() {
            $.getJSON('../api/inventario/list_productos.php', function(resp) {
                if (resp.ok){
                    const data= resp.data;
                    let html = '';
                    data.forEach(p => {
                        let estadoTexto;
                        let estadoClaseBtn;

                        if (p.activo==1){
                            estadoTexto = 'Activa';
                            estadoClaseBtn = 'btn-success';
                        }else{
                            estadoTexto = 'Inactiva';
                            estadoClaseBtn = 'btn-danger';
                        }

                        let stockCantidad=p.stock;
                        if (p.stock <=5){
                            stockCantidad = `<span class="badge bg-danger">${p.stock}</span>`
                        }
                        html += `
                            <tr>
                                <td>${p.codigo}</td>
                                <td>${p.nombre_producto}</td>
                                <td>${p.precio_compra}</td>
                                <td>${p.precio_venta}</td>
                                <td>${p.stock}</td>
                                <td>${p.activo == 1 ? 'Activo' : 'Inactivo'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn_editar" data-id="${p.producto_id}">Editar</button>
                                    <button class="btn btn-sm ${estadoClaseBtn} btn_cambiar_estado" data-id="${p.producto_id}" data-nombre="${p.nombre_producto}" data-estado="${p.activo}">
                                        ${estadoTexto}
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#contenedor_productos').html(html);
                    filtrarProductos();
                }

            });
        }

        // Agregar producto
        $('#form_agregar_producto').submit(function(e) {
            e.preventDefault();
            console.log("Datos enviados:", $(this).serialize());
            $.post('../api/inventario/insert_producto.php', $(this).serialize(), function(resp) {
                console.log("Servidor dice:", resp);
                modalAgregar.hide();
                if (resp.ok){
                    showAlert('success', 'Producto agregado correctamente.');
                    cargarProductos();
                } else {

                    showAlert('danger',resp.msg || 'Error al agregar el producto.');
                }
            }, 'json');
        });

        // Agrega producto con el evento 
        $('#codigoProd').on('keypress', function(e) {
            if (e.which === 13) { //enter
                e.preventDefault();
                $('#form_agregar_producto [name="nombre"]').focus();
            }
        });
        $('#modal_agregar_producto').on('shown.bs.modal', function () {
            $('#codigoProd').focus();
        });


        // Obtener datos para editar producto
        $(document).on('click', '.btn_editar', function() {
            const id = $(this).data('id');
            $.getJSON('../api/inventario/get_producto.php', {id:id}, function(resp) {
                if (resp.ok){
                    const p = resp.data;
                    $('#editar_id').val(p.producto_id);
                    $('#form_editar_producto [name="nombre"]').val(p.nombre_producto);
                    $('#form_editar_producto [name="costo"]').val(p.precio_compra);
                    $('#form_editar_producto [name="precio"]').val(p.precio_venta);
                    $('#form_editar_producto [name="stock"]').val(p.stock);
                    modaledit.show();
                } else {
                    showAlert('danger', 'No se pudo cargar el producto para editar.');
                }
            });
        });
        // Editar producto
        $('#form_editar_producto').on('submit', function(e) {
            e.preventDefault();
            $.post('../api/inventario/update_producto.php', $(this).serialize(), function(resp) {
                try { 
                    resp =JSON.parse(resp); 
                } catch(e) { 
                    resp = {ok: false, msg: 'Error al actualizar producto'}; 
                }

                if (!resp.ok) {
                    showAlert('danger', resp.msg || 'Error al editar el producto');
                    return;
                }

                modaledit.hide();
                showAlert('success', 'Producto actualizado correctamente');   
                cargarProductos(); 
            });
        });

        // Estado producto
        $(document).on('click', '.btn_cambiar_estado', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const estadoActual = $(this).data('estado'); 


            Swal.fire({
                title: (estadoActual == 1) ? `¿ Quieres desactivar el producto ${nombre}?` : `¿Quieres activar el producto ${nombre}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#22b043",
                cancelButtonColor: "#ff0000",
                confirmButtonText: "Sí, cambiar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {   
                    console.log("Enviando ID producto:", id);
                    
                    $.post('../api/inventario/estado_producto.php', { id: id }, function(resp) {
                        
                        try {
                            const res = JSON.parse(resp);
                            if (res.ok) {
                                Swal.fire("¡Listo!", "Estado actualizado", "success");
                                cargarProductos(); 
                            } else {
                                Swal.fire("Error", res.msg || "No se pudo cambiar", "error");
                            }
                        } catch(e) {
                            Swal.fire("Error", "Error en la respuesta del servidor", "error");
                        }
                        
                    });
                }
            });
        });

        // funcion de busqueda y filtro de productos
        function filtrarProductos() {
            const busqueda_producto = $('#search_productos').val().toLowerCase();
            const filtro_productos = $('#filtro_estado_productos').val();
            let hayCoincidencias= false

            $('#contenedor_productos tr').each(function() {
                const codigo= $(this).find('td').eq(0).text().toLowerCase();// codigo
                const nombre = $(this).find('td').eq(1).text().toLowerCase();// nombre
                const activo = $(this).find('.btn_cambiar_estado').data('estado');

                const coincideTexto = (busqueda_producto ==='' || codigo.includes(busqueda_producto) || nombre.includes(busqueda_producto));
                const coincideEstado = (filtro_productos === '' || activo == filtro_productos);

                if(coincideTexto && coincideEstado){
                    $(this).show();
                    hayCoincidencias = true;
                } else{
                    $(this).hide();
                }
            });
            if (hayCoincidencias){
                $('#no_encontrado_productos').addClass('d-none');
            } else {
                $('#no_encontrado_productos').removeClass('d-none');
            }
        }

        $("#search_productos").on("input", function() {
            filtrarProductos();
        });

        $("#filtro_estado_productos").on("change", function() {
            filtrarProductos();
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

