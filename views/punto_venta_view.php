
<?php
    $puede_vender = true;
    $mensaje_turno = '';

    if ($_SESSION['rol'] === 'Cajero') {
        $check_turno = $conn->prepare("SELECT corte_caja_id FROM corte_caja WHERE usuarios_id = ? AND fecha_fin IS NULL");
        $check_turno->bind_param("i", $_SESSION['id_usuario']);
        $check_turno->execute();
        $result_turno = $check_turno->get_result();
        
        if ($result_turno->num_rows === 0) {
            $puede_vender = false;
            $mensaje_turno = 'No tienes un turno activo. Por favor, solicita al gerente que inicie tu turno para poder realizar ventas.';
        }
    }
?>

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

    <div class="d-flex gap-2 mb-4">
        <div class="input-group border border-primary rounded w-50">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input type="text" id="busqueda_producto" class="form-control border-primary" placeholder="Buscar producto...">
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabletventa">
                    <thead>
                        <tr>
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>CANTIDAD</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <h4 class="mt-2">
                    Total: $<span id="total_venta">0.00</span>
                </h4>
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

    //Calcula el total de la venta
    function calcularTotalVenta(){
        let total=0;
        $('#tabletventa tbody tr').each(function(){
            let precio =parseFloat($(this).find('.precio').text());
            let cantidad= parseInt($(this).find('.cantidad').text());
            
            total=total+precio*cantidad;
        });
        $('#total_venta').text(total.toFixed(2));
    }

    //Elimina la fila
    $(document).on('click', '.btn_eliminar', function(){
        let fila =$(this).closest('tr');
        let nombre =$(fila).find('.nombre').text();

        Swal.fire({
            title: `¿Eliminar el producto ${nombre}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#22b043",
            cancelButtonColor: "#ff0000",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }) .then((result) => {
            if (result.isConfirmed) {
                $(fila).remove();
                calcularTotalVenta();

                Swal.fire({
                    icon: "success",
                    title: "Producto eliminado correctamente",
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })
    })

    //Genera la venta
    $('#btn_generar_venta').click(function(){
        let productos= [];
        $('#tabletventa tbody tr').each(function(){
            let producto ={
                codigo: $(this).find('.codigo').text(),
                precio: parseFloat($(this).find('.precio').text()),
                cantidad :parseInt($(this).find('.cantidad').text())
            };
            productos.push(producto);
        });
        console.log(productos);

        if (productos.length === 0){
            showAlert('danger', 'No hay productos en la venta');
            return;
        }

        let total= $('#total_venta').text();

        $.post('../api/ventas/generar_venta.php',{
            productos: JSON.stringify(productos),
            total: total
        }, function(resp){
            console.log(resp);
            
            if (resp.ok){
                showAlert('success', 'Venta generada correctamente');
                //limpiar carrito
                $('#tabletventa tbody').html('');
                $('#total_venta').text('0.00');

                //abrir pdf
                window.open('../api/ventas/ticket_pdf.php?id=' + resp.data, '_blank');
                
            }else{
                showAlert('danger', resp.msg || 'Error al generar la venta');
            }
        }, 'json');
    });



    $(document).on('keypress', '#busqueda_producto', function(e) {
        if (e.which === 13) { //enter
            let codigo= $(this).val();

            if(codigo.trim()===''){
                showAlert('danger', 'Ingresa un código de producto');
                return;
            }

            $.getJSON('../api/inventario/get_one_producto.php', {codigo: codigo}, function(resp) {
                if(!resp.ok){
                    showAlert('danger', resp.msg || 'Error');
                    return;
                }

                let p= resp.data;

                //Verificar si el producto está activo
                if (p.activo== 0){
                    showAlert('danger', 'Producto no encontrado');
                    return;
                }

                let filaExiste= $(`#tabletventa tbody tr[data-codigo="${p.codigo}"]`);
                if (filaExiste.length > 0){//Si la fila ya existe 
                    let cantidad= filaExiste.find('.cantidad');
                    let nuevaCantidad= parseInt(cantidad.text()) + 1;
                    cantidad.text(nuevaCantidad);
                } else{
                    let fila= `
                        
                        <tr data-codigo= "${p.codigo}">
                            <td class="codigo">${p.codigo}</td>
                            <td class="nombre">${p.nombre_producto}</td>
                            <td class="precio">${p.precio_venta}</td>
                            <td class="cantidad">1</td>
                            <td>
                                <button class="btn btn-danger btn-sm btn_eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                    $('#tabletventa tbody').append(fila);
                }
                calcularTotalVenta();
                $('#busqueda_producto').val('').focus();

            });
        }
    });
    });

</script>