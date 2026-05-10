<!--Inventario backend-->
<?php
    $productos = [
        [
            "codigo" => "001",
            "nombre" => "Sabritas",
            "categoria" => "Alimentos",
            "stock" => 30,
            "precio" => 25.00,
            "estado" => "Activo"
        ],
        [
            "codigo" => "002",
            "nombre" => "Coca Cola",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "003",
            "nombre" => "Pepsi",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ]
        ,
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ],
        [
            "codigo" => "004",
            "nombre" => "Cerveza",
            "categoria" => "Bebidas",
            "stock" => 15,
            "precio" => 18.50,
            "estado" => "Activo"
        ]
        
        
    ];
    $i=0;
?>

<div class="pb-2 mb-0">
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-semibold pb-2 fs-4">Inventario</h1>
        <button class="boton-azul-hover btn bg-primary me-2 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
            Agregar Producto
        </button>
    </div>

    <div class="d-flex">
        <div class="input-group border border-primary rounded w-50">
            <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input id="search_inventario" class="form-control border-1 " type="search" placeholder="Buscar producto...">
        </div>
        
        <div class="d-flex dropdown px-3">
            <button class="btn btn-light border border-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Todas las categorías
            </button>
            <ul class="dropdown-menu shadow">
                <li><a class="dropdown-item" href="#">Alimentos</a></li>
                <li><a class="dropdown-item" href="#">Bebidas</a></li>
            </ul>
        </div>

        <form action="#" method="">
            <div class="d-flex dropdown px-0">
                <button class="btn btn-light border border-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Todos
                </button>
                <ul class="dropdown-menu shadow">
                    <li><a class="dropdown-item" href="#">Todos</a></li>
                    <li><a class="dropdown-item" href="#">Activos</a></li>
                    <li><a class="dropdown-item" href="#">Inactivos</a></li>
                </ul>
            </div>
        </form>
    </div>

    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="card shadow ">
                <div class="card-body"> 
                    <!--Tabla de inventario-->
                    <div class="overflow-auto p-3" style="max-height: 60vh;">
                        <table id = "tabla_punto_venta" class = "table table-striped table-hover">

                            <thead style="width: 100px;">
                                <th >Codigo</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th style="width: 100px;">Acciones</th>
                            </thead>
                            <?php while ($i<count ($productos)){?>
                                <?php $elemento = $productos[$i]; ?>
                                <tr class="align-middle">
                                    <td><?= $elemento["codigo"] ?></td>
                                    <td><?= $elemento["nombre"] ?></td>
                                    <td><?= $elemento["categoria"] ?></td>
                                    <td><?= $elemento["stock"] ?></td>
                                    <td>$<?= $elemento["precio"] ?></td>
                                    <td><?= $elemento["estado"] ?></td>
                                    <td class="">
                                        <button class="boton-azul-hover btn bg-primary btn-sm text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" /><path d="M16 5l3 3" /></svg>
                                        </button>
                                        <button class="boton-rojo-hover btn bg-danger btn-sm text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-cancel"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /><path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php } ?> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <div class="d-flex justify-content-end">
                <button class="boton-azul-hover btn bg-primary mt-4 me-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF" class="icon icon-tabler icons-tabler-filled icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" /></svg>
                    Agregar categoría
                </button>
            </div>


</div>  

