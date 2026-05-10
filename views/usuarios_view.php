<div class="titulo-principal pb-2 mb-0">
    <div class="d-flex justify-content-between">
        <h1 class="tipoLetra fw-bold pb-2">Usuarios</h1>
        <button onclick="document.getElementById('agregarUser').showModal();" class="boton-azul-hover btn bg-primary me-2 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFFFFF">
                <path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" />
            </svg>
            Agregar usuario
        </button>
        <dialog id="agregarUser" class="border rounded border-primary p-4">
            <h3>
                Datos del nuevo usuario
            </h3>
            <div class="form-floating mt-4 mb-3">
                <input type="text" class="form-control" id="nuevo_nombre" placeholder="Nombre de usuario" required>
                <label>
                    Nombre de usuario
                </label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="nuevo_password" placeholder="Contraseña" required>
                <label>
                    Contraseña
                </label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="nuevo_correo" placeholder="Correo electronico">
                <label>
                    Correo electrónico
                </label>
            </div>
            <p class="mb-1">Rol</p>
            <div class="mb-3">
                <label class="form-check-inline">
                    <input type="radio" name="nuevo_rol" value="Administrador">
                    Administrador
                </label>
                <label class="form-check-inline">
                    <input type="radio" name="nuevo_rol" value="Gerente">
                    Gerente
                </label>
                <label class="form-check-inline">
                    <input type="radio" name="nuevo_rol" value="Cajero">
                    Cajero
                </label>
            </div>
            <div class="d-flex justify-content-center gap-2 mt-4">
                <button onclick="document.getElementById('agregarUser').close();" class="boton-rojo-hover btn bg-danger text-white">Cancelar</button>
                <button onclick="agregarUsuario();" class="boton-verde-hover btn bg-success text-white">Agregar</button>
            </div>
        </dialog>
    </div>
    <div class="d-flex">
        <div class="w-50">
            <div class="input-group border border-primary rounded">
                <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5">
                        <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </span>
                <input id="searchUsuarios" class="form-control border-1" type="search" placeholder="Buscar usuario...">
            </div>
        </div>
        <div class="dropdown px-3">
            <button class="btn btn-light border border-primary dropdown-toggle" type="button" id="filtroRolBtn" data-bs-toggle="dropdown">
                Todos los roles
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item filtro-rol" data-rol="todos" href="#">Todos los roles</a></li>
                <li><a class="dropdown-item filtro-rol" data-rol="Administrador" href="#">Administrador</a></li>
                <li><a class="dropdown-item filtro-rol" data-rol="Gerente" href="#">Gerente</a></li>
                <li><a class="dropdown-item filtro-rol" data-rol="Cajero" href="#">Cajero</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <div id="contenedorUsuarios"></div>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="modalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="id_edit">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="edit_nombre" placeholder="Nombre">
                        <label>Nombre de usuario</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="edit_password" placeholder="Password">
                        <label>Contraseña</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="edit_correo" placeholder="Correo">
                        <label>Correo electrónico</label>
                    </div>
                    <p class="mb-1">Rol</p>
                    <div class="mb-3">
                        <label class="form-check-inline">
                            <input type="radio" name="edit_rol" value="Administrador"> Administrador
                        </label>
                        <label class="form-check-inline">
                            <input type="radio" name="edit_rol" value="Gerente"> Gerente
                        </label>
                        <label class="form-check-inline">
                            <input type="radio" name="edit_rol" value="Cajero"> Cajero
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let filtroRolActual = 'todos';
    let busquedaActual = '';

    function loadUsuarios() {
        const busqueda = document.getElementById('searchUsuarios').value;
        const rol = filtroRolActual;
        
        $.getJSON("../db/consult/userList.php", { search: busqueda, rol: rol }, function(resp) {
            if (!resp.ok) {
                alert("Error al cargar usuarios");
                return;
            }
            const rows = resp.data.map(usuario => {
                const estadoTexto = usuario.activo == 1 ? 'Activo' : 'Inactivo';
                const estadoClass = usuario.activo == 1 ? 'bg-success' : 'bg-danger';
                return `
                <div class="card shadow mb-3">
                    <div class="card-body titulo-secundario d-flex gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M9 10a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                        <div>
                            <h2 class="mt-2 d-block fw-semibold mb-1">${escapeHtml(usuario.nombre_usuario)}</h2>
                            <span class="badge ${estadoClass} mb-2">${estadoTexto}</span>
                            <span class="d-block badge bg-primary rounded-5">${usuario.rol}</span>
                        </div>
                        <div class="mt-3 ms-auto">
                            ${usuario.activo == 1 ?
                                `<button onclick="desactivarUsuario(${usuario.usuarios_id})" class="boton-rojo-hover btn bg-danger btn-sm me-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25">
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                        <path d="M13.5 6.5l4 4" />
                                    </svg>
                                    Desactivar
                                </button>`
                                :
                                `<button onclick="activarUsuario(${usuario.usuarios_id})" class="boton-verde-hover btn bg-success btn-sm me-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25">
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                        <path d="M13.5 6.5l4 4" />
                                    </svg>
                                    Activar
                                </button>`
                            }
                            <button class="btn-edit boton-azul-hover btn bg-primary btn-sm me-2 text-white" data-id="${usuario.usuarios_id}">
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
            });
            $('#contenedorUsuarios').html(rows.join(''));
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function agregarUsuario() {
        const nombre = document.getElementById('nuevo_nombre').value.trim();
        const password = document.getElementById('nuevo_password').value;
        const correo = document.getElementById('nuevo_correo').value.trim();
        const rol = document.querySelector('input[name="nuevo_rol"]:checked');
        
        if (!nombre) {
            alert('El nombre de usuario es requerido');
            return;
        }
        if (!password) {
            alert('La contraseña es requerida');
            return;
        }
        if (!rol) {
            alert('Debe seleccionar un rol');
            return;
        }
        
        $.post("../db/insert/insertUsers.php", {
            nombre_usuario: nombre,
            password: password,
            correo: correo,
            rol: rol.value
        }, function(resp) {
            if (resp.ok) {
                document.getElementById('agregarUser').close();
                document.getElementById('nuevo_nombre').value = '';
                document.getElementById('nuevo_password').value = '';
                document.getElementById('nuevo_correo').value = '';
                document.querySelectorAll('input[name="nuevo_rol"]').forEach(r => r.checked = false);
                loadUsuarios();
                alert('Usuario agregado correctamente');
            } else {
                alert(resp.msg || 'Error al agregar usuario');
            }
        }, "json");
    }

    function activarUsuario(id) {
        $.post("../db/change/updateEstado.php", { usuarios_id: id, activo: 1 }, function(resp) {/////////////////////////////////////////////////////////////////////////
            if (resp.ok) {
                loadUsuarios();
                alert('Usuario activado');
            }
        }, "json");
    }

    function desactivarUsuario(id) {
        if (confirm('¿Estás seguro de desactivar este usuario?')) {
            $.post("../db/change/updateEstado.php", { usuarios_id: id, activo: 0 }, function(resp) {
                if (resp.ok) {
                    loadUsuarios();
                    alert('Usuario desactivado');
                }
            }, "json");
        }
    }

    $(document).ready(function() {
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        
        document.querySelectorAll(".filtro-rol").forEach(function(item) {
            item.addEventListener("click", function(e) {
                e.preventDefault();
                filtroRolActual = this.getAttribute("data-rol");
                document.getElementById("filtroRolBtn").innerHTML = this.innerHTML;
                loadUsuarios();
            });
        });
        
        document.getElementById("searchUsuarios").addEventListener("keyup", function() {
            loadUsuarios();
        });
        
        $(document).on("click", ".btn-edit", function() {
            const usuarios_id = $(this).data("id");
            $.getJSON("../db/consult/getUsuario.php", { usuarios_id: usuarios_id }, function(resp) {
                if (!resp.ok) {
                    alert("Usuario no encontrado");
                    return;
                }
                const u = resp.data;
                $("#id_edit").val(u.usuarios_id);
                $("#edit_nombre").val(u.nombre_usuario);
                $("#edit_password").val('');
                $("#edit_correo").val(u.correo);
                $('input[name="edit_rol"][value="' + u.rol + '"]').prop("checked", true);
                modalEdit.show();
            });
        });
        
        $("#formEdit").submit(function(e) {
            e.preventDefault();
            $.post("../db/update/updateUsuario.php", {
                usuarios_id: $("#id_edit").val(),
                nombre_usuario: $("#edit_nombre").val(),
                password: $("#edit_password").val(),
                correo: $("#edit_correo").val(),
                rol: $('input[name="edit_rol"]:checked').val()
            }, function(resp) {
                if (resp.ok) {
                    modalEdit.hide();
                    loadUsuarios();
                    alert('Usuario actualizado');
                } else {
                    alert(resp.msg || 'Error al actualizar');
                }
            }, "json");
        });
        
        loadUsuarios();
    });
</script>