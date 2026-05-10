<div class="pb-2 mb-0">
    <h1 class=" tipoLetra fw-semibold pb-2 fs-4">Punto de venta</h1>
    <div class="row">
        <div class="col-sm-9">
            <div class="card shadow h-100">
                <div class="card-body">
                    <nav class="navbar">
                        <div class="container-fluid">
                            <form class="w-100" role="search">
                                <div class="input-group border border-primary rounded">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                    </span>
                                    <input id="search_punto_venta" class="form-control border-1" type="search" placeholder="Buscar por nombre, código de barras...">
                                </div>
                            </form>
                        </div>
                    </nav>
                    <!--Tabla del punto de venta-->
                    <div class="px-3">
                        <table id = "tabla_punto_venta" class = "table table-striped table-hover mt-3">
                            <tr class="align-middle">
                                <th>Codigo</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Descuento</th>
                                <th>Total</th>
                            </tr>
                            <tr class="align-middle">
                                <td>001</td>
                                <td>Sabritas</td>
                                <td>3</td>
                                <td>$25.00</td>
                                <td> </td>
                                <td>$75.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow">
                <div class="card-body titulo-secundario titulo-terciario">
                    <h2 class=" fw-bold pb-2">Resumen de venta</h2>
                    <hr class="my-2 border border-primary mt-1 opacity-25x">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>$150.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Iva</span>
                        <span>$24.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Descuento</span>
                        <span>$0.00</span>
                    </div>
                    <hr class="my-2 border border-2 border-primary mt-1 opacity-75">
                    <div class="d-flex justify-content-between py-2">
                        <span class="h5 fw-bold">TOTAL</span>
                        <span>$174.00</span>
                    </div>
                    <hr class="my-2 border border-primary mt-1 opacity-25">
                    <h3 class="fw-bold">Método de pago</h3>
                    <div class="d-flex flex-row nav nav-pills justify-content-center gap-2">
                        <a href="#" class="nav-link border border-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-coin"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 7v10" /></svg>
                            Efectivo
                        </a>
                        <a href="#" class="nav-link border border-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -8" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                            Tarjeta
                        </a>
                    </div>
                    <h3 class="fw-bold">Monto recibido</h3>
                    <div class="d-flex flex-column">
                        <span class="fw-bold border text-end w-100 rounded-1 py-1 px-2">$200.00</span>
                    </div>
                    <div class="d-flex border-success border-opacity-50 bg-success bg-opacity-10 text-success justify-content-between align-items-center border rounded-1 py-1 px-2 mt-3">
                        <span class="small">Cambio a entregar</span>
                        <span class="fw-bold ">$26.00</span>
                    </div>
                    <div class="mt-3">
                        <form action="#" method="">
                            <button type="submit" class="boton-azul-hover btn bg-primary w-100 text-white">
                                Cobrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>