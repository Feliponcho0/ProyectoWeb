window.addEventListener("DOMContentLoaded", () => {
    const contenedor = document.getElementById("burbujas-container");

    function crearBurbujas(){
        const div = document.createElement("div");
        div.classList.add("burbuja");

        div.style.left = Math.random() * 100 + "%";

        const size = Math.random() * 15 + 10;
        div.style.width = size + "px";
        div.style.height = size + "px";

        div.style.animation = `moverArriba ${Math.random() * 3 + 2}s linear`;

        contenedor.appendChild(div);

        div.addEventListener("animationend", () => {
            div.remove();
        });
    }

    setInterval(crearBurbujas, 300);
});
//funcion que determina quien entro




// const btn = document.querySelector(".btn-toggle");

// if(localStorage.getItem("dark-mode") === "true"){
//     document.body.classList.add("dark-theme");
//     btn.checked = true;
// }

// btn.addEventListener("change", function() {
//     document.body.classList.toggle("dark-theme", btn.checked);

//     localStorage.setItem("dark-mode", btn.checked);
// });
document.querySelectorAll('.filtroT').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('botonT').textContent = this.textContent;
        });
    });

    document.querySelectorAll('.ordenT').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('orden').textContent = this.textContent;
        });
    });

    function agregarTienda() {
        const nombre = document.getElementById('nuevoNombre').value;
        const rfc = document.getElementById('nuevoRFC').value;
        if (nombre.trim() === "" || rfc.trim() === "") {
            alert("Por favor, llena todos los campos");
            return;
        }

        const nuevaCard = `
            <div class="col-md-12 mb-3" id = "agregar">
                <div class="card shadow">
                    <div class="card-body titulo-secundario d-flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16"><path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/></svg>
                        <div>
                            <h2 class="mt-2 d-block fw-semibold mb-1">
                                ${nombre}
                            </h2>
                            <small class="d-block fw-semibold">
                                RFC: ${rfc}
                            </small>
                        </div>
                        <div class="mt-2 ms-auto">
                            <form action="" method="">
                                <span class = " ms-2" title = "Tienda Activa"></span>
                                <button class="boton-azul-hover btn bg-primary btn-sm me-2 text-white d-inline-flex align-items-center" type = "button"">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" /><path d="M16 5l3 3" /></svg>
                                    Editar
                                </button>
                                <button class="boton-rojo-hover btn bg-danger btn-sm me-2 text-white d-inline-flex align-items-center" type="button"">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-cancel"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /><path d="M16 19a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M17 21l4 -4" /></svg>
                                    Desactivar
                                </button>
                            </form>                   
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById("contenedorTiendas").insertAdjacentHTML("afterbegin", nuevaCard);
        document.getElementById('nuevoNombre').value = "";
        document.getElementById('nuevoRFC').value = "";
    }

    