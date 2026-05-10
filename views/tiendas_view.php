<div class="titulo-principal pb-2 mb-0">
    <!-- Título y botón (fijos) -->
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-bold pb-2">Tiendas</h1>
        
        <div class="ms-auto">
            <button onclick="window.miModal.showModal();" class="boton-azul-hover btn bg-primary me-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF">
                    <path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" />
                </svg>
                Nueva tienda
            </button>
            
            <dialog id="miModal" class="border rounded border-primary p-4">
                <form action="../db/tiendas/agregar_tiendas.php" method="POST">
                    <h1 class="fs-4 pb-3">Agregar tienda</h1>

                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="small">Nombre de la tienda</h2>
                            <div class="mb-3">
                                <input type="text" name="nombre_tienda" class="form-control" placeholder="Nombre" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h2 class="small">RFC</h2>
                            <div class="mb-3">
                                <input type="text" name="rfc" class="form-control" placeholder="RFC" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h2 class="small">Dirección</h2>
                            <div class="mb-3">
                                <input type="text" name="direccion" class="form-control" placeholder="Dirección" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h2 class="small">Telefono</h2>
                            <div class="mb-3">
                                <input type="text" name="telefono" class="form-control" placeholder="telefono" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 mt-3">
                        <button onclick= "window.miModal.close();" type="button" id="btn_cancelar_tienda" class="btn bg-danger boton-rojo-hover text-white">
                            Cancelar
                        </button>
                        <button onclick= "window.miModal.close();" type="submit" class="btn bg-success boton-verde-hover text-white">
                            Agregar
                        </button>
                    </div>
                </form>
            </dialog>
        </div>
    </div>

    <!-- Filtros (fijos) -->
    <div class="d-flex">
        <div class="w-50">
            <div class="input-group border border-primary rounded">
                <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5">
                        <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </span>
                <input id="searchTiendas" class="form-control border-1" type="search" placeholder="Buscar tienda...">
            </div>
        </div>
        
        <div class="dropdown px-3">
            <button class="btn btn-light border border-primary dropdown-toggle" type="button" id="botonT" data-bs-toggle="dropdown">
                Todas las tiendas
            </button>
            <ul class="dropdown-menu filtroT">
                <li><a class="dropdown-item filtro-tienda" data-estado="todas" href="#">Todas las tiendas</a></li>
                <li><a class="dropdown-item filtro-tienda" data-estado="activas" href="#">Tiendas Activas</a></li>
                <li><a class="dropdown-item filtro-tienda" data-estado="inactivas" href="#">Tiendas Inactivas</a></li>
            </ul>
        </div>
        
        <div class="dropdown">
            <button class="btn btn-light border border-primary dropdown-toggle" type="button" id="orden" data-bs-toggle="dropdown">
                Orden
            </button>
            <ul class="dropdown-menu ordenT">
                <li><a class="dropdown-item orden-select" data-orden="asc" href="#">A - Z</a></li>
                <li><a class="dropdown-item orden-select" data-orden="desc" href="#">Z - A</a></li>
            </ul>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <div id="contenedorTiendas"">

            </div>
        </div>
    </div>
</div>

<script>
    
    var tiendasData = [
        { tiendas_id: 1, nombre_tienda: "Abarrotes López", rfc: "ABC123", activo: 1 },
        { tiendas_id: 2, nombre_tienda: "Abarrotes Castillo", rfc: "DEF456", activo: 1 },
        { tiendas_id: 3, nombre_tienda: "Murillo", rfc: "GHI789", activo: 1 },
        { tiendas_id: 4, nombre_tienda: "Aurrera", rfc: "JKL012", activo: 0 }
    ];

    var siguienteId = 13;
    var ordenActual = "asc";
    var filtroEstadoActual = "todas";

    function renderizarTiendas() {
        var searchTerm = document.getElementById("searchTiendas").value.toLowerCase();
        var contenedor = document.getElementById("contenedorTiendas");
        var tiendasFiltradas = [];
        
        // Filtrar por búsqueda y estado
        for (var i = 0; i < tiendasData.length; i++) {
            var tienda = tiendasData[i];
            var coincideBusqueda = tienda.nombre_tienda.toLowerCase().indexOf(searchTerm) !== -1;
            
            var coincideEstado = true;
            if (filtroEstadoActual === "activas") {
                coincideEstado = (tienda.activo == 1);
            } else if (filtroEstadoActual === "inactivas") {
                coincideEstado = (tienda.activo == 0);
            }
            
            if (coincideBusqueda && coincideEstado) {
                tiendasFiltradas.push(tienda);
            }
        }
        
        // Ordenar (A - Z o Z - A)
        for (var i = 0; i < tiendasFiltradas.length; i++) {
            for (var j = i + 1; j < tiendasFiltradas.length; j++) {
                if (ordenActual === "asc") {
                    if (tiendasFiltradas[i].nombre_tienda > tiendasFiltradas[j].nombre_tienda) {
                        var temp = tiendasFiltradas[i];
                        tiendasFiltradas[i] = tiendasFiltradas[j];
                        tiendasFiltradas[j] = temp;
                    }
                } else {
                    if (tiendasFiltradas[i].nombre_tienda < tiendasFiltradas[j].nombre_tienda) {
                        var temp = tiendasFiltradas[i];
                        tiendasFiltradas[i] = tiendasFiltradas[j];
                        tiendasFiltradas[j] = temp;
                    }
                }
            }
        }
        
        contenedor.innerHTML = "";
        
        if (tiendasFiltradas.length === 0) {
            contenedor.innerHTML = '<div class="alert alert-info">No hay tiendas para mostrar</div>';
            return;
        }
        
        for (var i = 0; i < tiendasFiltradas.length; i++) {
            var tienda = tiendasFiltradas[i];
            var estadoText = (tienda.activo == 1) ? "Activo" : "Inactivo";
            var estadoColor = (tienda.activo == 1) ? "bg-success" : "bg-secondary";
            
            const tarjeta = `
                <div class="card shadow">
                    <div class="card-body titulo-secundario d-flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976z" />
                            <path d="M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5z" />
                        </svg>
                        <div>
                            <h2 class="mt-2 d-block fw-semibold mb-1">${tienda.nombre_tienda}</h2>
                            <small class="d-block fw-semibold">RFC: ${tienda.rfc}</small>
                            <span class="badge ${estadoColor} mt-1">${estadoText}</span>
                        </div>
                        <div class="mt-2 ms-auto">
                            ${tienda.activo == 1 ? 
                                `<button onclick="desactivarTienda(${tienda.tiendas_id})" class="boton-rojo-hover btn bg-danger btn-sm me-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25">
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                        <path d="M13.5 6.5l4 4" />
                                        <path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M17 21l4 -4" />
                                    </svg>
                                    Desactivar
                                </button>` :
                                `<button onclick="activarTienda(${tienda.tiendas_id})" class="boton-verde-hover btn bg-success btn-sm me-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25">
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Activar
                                </button>`
                            }
                            <button class="boton-azul-hover btn bg-primary btn-sm me-2 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25">
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Editar
                            </button>
                        </div>
                    </div>
                </div>
            `;

            contenedor.innerHTML = contenedor.innerHTML + tarjeta;
        }
    }

    function desactivarTienda(id) {
        for (var i = 0; i < tiendasData.length; i++) {
            if (tiendasData[i].tiendas_id == id) {
                tiendasData[i].activo = 0;
                break;
            }
        }
        renderizarTiendas();
        alert("Tienda desactivada");
    }

    function activarTienda(id) {
        for (var i = 0; i < tiendasData.length; i++) {
            if (tiendasData[i].tiendas_id == id) {
                tiendasData[i].activo = 1;
                break;
            }
        }
        
        renderizarTiendas();
        alert("Tienda activada");
    }

    function agregarTienda() {
        var nombre = document.getElementById("nuevoNombre").value.trim();
        var rfc = document.getElementById("nuevoRFC").value.trim();
        
        if (nombre === "" || rfc === "") {
            alert("Por favor, llena todos los campos");
            return false;
        }
        
        tiendasData.push({
            tiendas_id: siguienteId,
            nombre_tienda: nombre,
            rfc: rfc,
            activo: 1
        });
        
        siguienteId++;
        
        document.getElementById("nuevoNombre").value = "";
        document.getElementById("nuevoRFC").value = "";
        
        renderizarTiendas();
        alert("Tienda agregada correctamente");
        return true;
    }

    document.getElementById("searchTiendas").addEventListener("keyup", function() {
        renderizarTiendas();
    });
    
    document.querySelectorAll(".filtro-tienda").forEach(function(item) {
        item.addEventListener("click", function(e) {
            e.preventDefault();
            filtroEstadoActual = this.getAttribute("data-estado");
            document.getElementById("botonT").innerHTML = this.innerHTML;
            renderizarTiendas();
        });
    });
    
    document.querySelectorAll(".orden-select").forEach(function(item) {
        item.addEventListener("click", function(e) {
            e.preventDefault();
            ordenActual = this.getAttribute("data-orden");
            if (ordenActual === "asc") {
                document.getElementById("orden").innerHTML = "A - Z";
            } else {
                document.getElementById("orden").innerHTML = "Z - A";
            }
            renderizarTiendas();
        });
    });

    renderizarTiendas();
</script>