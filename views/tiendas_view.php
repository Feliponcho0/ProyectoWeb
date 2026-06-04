
<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Gestión de Tiendas</h1>
        <button id="btn_agregar_tienda" class="boton-azul-hover btn bg-primary me-2 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
            Agregar Tienda
        </button>
    </div>

    <div class="d-flex gap-2 mb-4">
        <div class="input-group border border-primary rounded w-50">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input id="search_tiendas" class="form-control border-1" type="search" placeholder="Buscar tienda...">
        </div>

        <select id="filtro_estado" class="form-select border border-primary w-auto">
            <option value="">Todas las tiendas</option>
            <option value="1" selected>Activas</option>
            <option value="0">Inactivas</option>
        </select>  
    </div>

    <div id="alertBox"></div>
    <div class="row mt-3" id="contenedor_tiendas"></div>
</div>
<!-- Modal agregar tienda -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_agregar_tienda">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-3">
                    <i class="bi bi-shop-window fs-2" style="color: #1A2B4A"></i>
                    Agregar Tienda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_agregar_tienda" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Nombre de la tienda</h2>
                            <div class="mb-3">
                                <input type="text" name="nombre_tienda" class="form-control border-primary" placeholder="Nombre" minlength="2" maxlength="100" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">RFC</h2>
                            <div class="mb-3">
                                <input type="text" name="rfc" class="form-control border-primary" placeholder="RFC"
                                pattern="^[A-ZÑ&]{3,4}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[A-Z\d]{3}$" title="Escribe un RFC válido." required style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Teléfono</h2>
                            <div class="mb-3">
                                <input type="text" name="telefono" class="form-control border-primary" placeholder="Teléfono" 
                                pattern="[0-9]{10}" title="Escribe un número de teléfono válido." required>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="mt-4">
                    <h6 class="fw-bold mb-3" style="color: #1A2B4A">Dirección de negocio</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">Código Postal</h2>
                            <div class="mb-3">
                                <input type="text" name="codigo_postal" class="form-control border-primary" placeholder="Ej. 23600" pattern="[0-9]{5}" title="Debe contener exactamente 5 números" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Estado</h2>
                            <div class="mb-3">
                                <select id="tienda_estado" name="estado" class="form-select border-primary" required>
                                    <option value="" selected disabled>Selecciona un estado...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Colonia</h2>
                            <div class="mb-3">
                                <input type="text" name="colonia" class="form-control border-primary" placeholder="Ej. Centro" minlength="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Calle y Número</h2>
                            <div class="mb-3">
                                <input type="text" name="calle" class="form-control border-primary" placeholder="Ej. Av. Juárez #123" minlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-3">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal editar tienda -->    
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modal_editar_tienda">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-3">
                    <i class="bi bi-shop-window fs-2" style="color: #1A2B4A"></i>
                    Editar Tienda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_editar_tienda" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_edit" id="id_edit">

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Nombre de la tienda</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_nombre_tienda" name="nombre_tienda" class="form-control border-primary" minlength="2" maxlength="100" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">RFC</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_rfc" name="rfc" class="form-control border-primary"
                                pattern="^[A-ZÑ&]{3,4}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[A-Z\d]{3}$" title="Escribe un RFC válido" required style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Teléfono</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_telefono" name="telefono" class="form-control border-primary"
                                pattern="[0-9]{10}" title="Escribe un número de teléfono válido" required>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">
                    <h6 class="fw-bold mb-3" style="color: #1A2B4A">Dirección de negocio</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">Código Postal</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_codigo_postal" name="codigo_postal" class="form-control border-primary" pattern="[0-9]{5}" title="Debe contener exactamente 5 números" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="small">Estado</h2>
                            <div class="mb-3">
                                <select id="edit_estado" name="estado" class="form-select border-primary" required>
                                    <option value="" selected disabled hidden>Selecciona un estado...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Colonia</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_colonia" name="colonia" class="form-control border-primary" placeholder="Ej. Centro" minlength="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small">Calle y Número</h2>
                            <div class="mb-3">
                                <input type="text" id="edit_calle" name="calle" class="form-control border-primary" minlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-3">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--JQUERY DE TIENDAS-->
<script>

    $(document).ready(function () {
        const modalAgregarTienda = new bootstrap.Modal(document.getElementById('modal_agregar_tienda'));
        const modalEditarTienda = new bootstrap.Modal(document.getElementById('modal_editar_tienda'));

        //Estados de la republica para los select de agregar y editar
        const estadosMexico = [
            "Aguascalientes", "Baja California", "Baja California Sur", "Campeche", "Chiapas", 
            "Chihuahua", "Coahuila", "Colima", "Ciudad de México", "Durango", "Guanajuato", 
            "Guerrero", "Hidalgo", "Jalisco", "México", "Michoacán", "Morelos", "Nayarit", 
            "Nuevo León", "Oaxaca", "Puebla", "Querétaro", "Quintana Roo", "San Luis Potosí", 
            "Sinaloa", "Sonora", "Tabasco", "Tamaulipas", "Tlaxcala", "Veracruz", "Yucatán", "Zacatecas"
        ];

        let opcionesEstados = '<option value="" selected disabled hidden>Selecciona un estado...</option>'; 

        estadosMexico.forEach(estado => {
            opcionesEstados= opcionesEstados+`<option value="${estado}">${estado}</option>`;
        });

        $('#tienda_estado').html(opcionesEstados);
        $('#edit_estado').html(opcionesEstados);

        // Agregar mensaje de "No se encontraron tiendas" si no existe
        if ($("#no_encontrado").length === 0) {
            $("#contenedor_tiendas").after('<div id="no_encontrado" class="text-center mt-4 d-none"><h1 class="fs-4 text-muted">No se encontraron tiendas que coincidan con los filtros</h1></div>');
        }
        
        // Abrir modal agregar
        $("#btn_agregar_tienda").click(function () {
            $("#form_agregar_tienda")[0].reset(); // Limpiar antes de abrir
            modalAgregarTienda.show();
        });

        function cargarTiendas() {
                $.getJSON('../api/tiendas/list_tiendas.php', function (resp) {
                    if (resp.ok) {
                        const data = resp.data;
                        let html = ''; 

                        data.forEach(t => {
                            let estadoClaseFiltro;
                            let estadoTexto;
                            let estadoClaseBtn;

                            if (t.activo==1) {
                                estadoClaseFiltro = 'tienda-activa activa';
                                estadoTexto = 'Activa';
                                estadoClaseBtn = 'btn-success';
                            } else{
                                estadoClaseFiltro = 'tienda-inactiva';
                                estadoTexto = 'Inactiva';
                                estadoClaseBtn = 'btn-danger';
                            }

                            html += `
                            <div class="col-md-4 tarjeta_tienda ${estadoClaseFiltro}">
                                <div class="card shadow-sm mb-3 border-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="p-0">
                                                <i class="bi bi-shop fs-3"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h3 class="h6 fw-bold mb-0">${t.nombre_tienda}</h3>
                                            </div>
                                        </div>
                                        <p class="mb-1 small"><strong>RFC:</strong> ${t.rfc}</p>
                                        <p class="mb-1 small"><strong>Calle:</strong> ${t.calle}</p>
                                        <p class="mb-1 small"><strong>Colonia:</strong> ${t.colonia}</p>
                                        <p class="mb-3 small"><strong>Teléfono:</strong> ${t.telefono}</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn ${estadoClaseBtn} btn-sm btn_cambiar_estado" data-id="${t.tiendas_id}" data-nombre="${t.nombre_tienda}" data-estado="${t.activo}">
                                                ${estadoTexto}
                                            </button>
                                            <button class="btn btn-primary btn-sm btn_abrir_editar" data-id="${t.tiendas_id}">
                                                Editar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $('#contenedor_tiendas').html(html);
                        filtrarTiendas();
                    }
                });
            }

            cargarTiendas();
        // Envío del formulario de agregar tienda
        $("#form_agregar_tienda").on('submit', function(e){
            e.preventDefault();
            $.post('../api/tiendas/insert_tienda.php', $(this).serialize(), function(resp){
                try{resp=JSON.parse(resp);}catch(e){resp={ok:false, msg: 'Error al registrar tienda'}}
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'Error al registrar la tienda');
                    return;
                }
                modalAgregarTienda.hide(); 
                showAlert('success', 'Tienda registrada con éxito');
                $('#form_agregar_tienda')[0].reset();
                cargarTiendas(); 
            });
        });

        // Envío del formulario de edición
        $(document).on('click', '.btn_abrir_editar', function() {
                const id = $(this).data('id'); 

                $.getJSON('../api/tiendas/get_tienda.php', { id: id }, function(resp) {

                    if (!resp.ok) {
                        showAlert('danger', resp.msg || 'Error al obtener datos');
                        return;
                    }

                    const t = resp.data;
                    $('#id_edit').val(t.tiendas_id); 
                    $('#edit_nombre_tienda').val(t.nombre_tienda);
                    $('#edit_rfc').val(t.rfc);
                    $('#edit_codigo_postal').val(t.codigo_postal); 
                    $('#edit_calle').val(t.calle);
                    $('#edit_estado').val(t.estado);
                    $('#edit_telefono').val(t.telefono);
                    $('#edit_colonia').val(t.colonia);


                    modalEditarTienda.show();
                    //$('#modal_editar_tienda').modal('show');
                });
            });

            // Funcion que guarda los cambios de la tienda editada
            $("#form_editar_tienda").on('submit', function(e) {
                e.preventDefault();

                $.post('../api/tiendas/update_tienda.php', $(this).serialize(), function(resp) {
                    try {
                        resp = JSON.parse(resp);  
                    } catch(e) {
                        resp = {ok: false, msg: 'Error al actualizar tienda'};
                    }

                    if (!resp.ok) {
                        showAlert('danger', resp.msg || 'Error al editar la tienda');
                        return;
                    }

                    modalEditarTienda.hide();
                    showAlert('success', 'Tienda actualizada correctamente');   
                    cargarTiendas(); 
                });
            });

        $(document).on('click', '.btn_cambiar_estado', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const estadoActual = $(this).data('estado'); 

            Swal.fire({
                title: (estadoActual == 1) ? `¿Desactivar ${nombre}?` : `¿Activar ${nombre}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor:"#22b043",
                cancelButtonColor:"#ff0000",
                confirmButtonText: "Sí, cambiar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {   
                    console.log("Enviando ID:", id);
                    $.post('../api/tiendas/estado_tiendas.php', { tiendas_id: id }, function(resp) {
                        const res = JSON.parse(resp);
                        
                        if (res.ok) {
                            Swal.fire("¡Listo!", "Estado actualizado", "success");
                            cargarTiendas();
                        } else {
                            Swal.fire("Error", "No se pudo cambiar", "error");
                        }
                    });
                }
            });
        });

        //Funcion para mostrar alertas
        function showAlert(type, msg){
            $("#alertBox").html(
                `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg} 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            )}
        
        // funcion de busqueda y filtros
        function filtrarTiendas() {
            const busqueda_tiendas = $("#search_tiendas").val().toLowerCase();
            const filtro_tiendas = $("#filtro_estado").val();
            let resultado=false;

            $(".tarjeta_tienda").each(function (){
                const nombre_tienda =$(this).find("h3").text().toLowerCase();
                const activo = $(this).hasClass("activa");

                if(busqueda_tiendas!="" && !nombre_tienda.includes(busqueda_tiendas)){
                    $(this).hide();
                    return;
                }
    
                if (filtro_tiendas=="1" && !activo){
                    $(this).hide();
                    return;
                }
    
                if (filtro_tiendas=="0" && activo){
                    $(this).hide();
                    return;
                }

                $(this).show();
                resultado=true;
            });

            //Se agrega el mensaje de usuario no encontrado o eliminarlo
            if (resultado) {
                $("#no_encontrado").addClass("d-none");
            } else {
                $("#no_encontrado").removeClass("d-none");
            }
        }

        $("#search_tiendas").on("input", function() {
            filtrarTiendas();

        });

        // Filtrar los activos y inactivos
        $("#filtro_estado").on("change", function() {
            filtrarTiendas();
        });

    });
</script>
