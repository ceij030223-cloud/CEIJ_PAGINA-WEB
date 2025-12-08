// Función para abrir PDF en modal
function abrirPDF(ruta) {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    pdfFrame.src = ruta;
    modal.style.display = 'flex';
}

// Cerrar modal PDF
document.addEventListener('DOMContentLoaded', function() {

    // Cerrar modal al hacer clic en la X o fuera del iframe
    const closeModal = document.getElementById('closeModal');
    const pdfModal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            pdfModal.style.display = 'none';
            pdfFrame.src = '';
        });
    }
    if (pdfModal) {
        pdfModal.addEventListener('click', e => {
            if (e.target === pdfModal) {
                pdfModal.style.display = 'none';
                pdfFrame.src = '';
            }
        });
    }

    // ---- Sistema de pestañas ----
    const botones = document.querySelectorAll(".tab-btn");
    const contenidos = document.querySelectorAll(".tab-content");
    botones.forEach(btn => {
        btn.addEventListener("click", () => {
            botones.forEach(b => b.classList.remove("activo"));
            contenidos.forEach(c => c.classList.remove("activo"));
            btn.classList.add("activo");
            document.getElementById(btn.dataset.tab).classList.add("activo");
        });
    });

    // ---- Sistema de modificación de datos ----
    const btnModificar = document.getElementById('btn-modificar');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const divGuardar = document.getElementById('div-guardar');
    const vistaInfo = document.getElementById('vista-info');
    const editarInfo = document.getElementById('editar-info');

    const nombreInput = document.getElementById('nombre-editar');
    const apellidoInput = document.getElementById('apellido-editar');
    const emailInput = document.getElementById('email-editar');
    const telefonoInput = document.getElementById('telefono-editar');

    const errorNombre = document.getElementById('error-nombre');
    const errorApellido = document.getElementById('error-apellido');
    const errorEmail = document.getElementById('error-email');
    const errorTelefono = document.getElementById('error-telefono');

    // --- Expresión regular para letras, tildes y ñ ----
    const regexLetras = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/;
    btnModificar.addEventListener('click', function() {
        vistaInfo.style.display = 'none';
        editarInfo.style.display = 'block';
        btnModificar.style.display = 'none';
        divGuardar.style.display = 'block';
    });

    btnCancelar.addEventListener('click', function() {
        vistaInfo.style.display = 'block';
        editarInfo.style.display = 'none';
        btnModificar.style.display = 'block';
        divGuardar.style.display = 'none';
        resetErrores();
    });

    // Validaciones
    nombreInput.addEventListener('keyup', function () {
        if (!regexLetras.test(this.value)) {
            errorNombre.style.display = 'block';
        } else {
            errorNombre.style.display = 'none';
        }
        // Solo se formatea al salir del campo o confirmar
    });

    apellidoInput.addEventListener('keyup', function () {
        if (!regexLetras.test(this.value)) {
            errorApellido.style.display = 'block';
        } else {
            errorApellido.style.display = 'none';
        }
    });

    // Formatear solo cuando termine de escribir
    nombreInput.addEventListener('blur', function () {
        this.value = formatearTexto(this.value);
    });
    apellidoInput.addEventListener('blur', function () {
        this.value = formatearTexto(this.value);
    });

    emailInput.addEventListener('input', function() {
        errorEmail.style.display = this.value.includes('@') ? 'none' : 'block';
    });

    telefonoInput.addEventListener('input', function() {
        errorTelefono.style.display = (this.value.length !== 10 || !/^\d+$/.test(this.value)) ? 'block' : 'none';
    });

    btnGuardar.addEventListener('click', function(e) {
        e.preventDefault();
        if (!validarFormulario()) {
            alert('Por favor corrige los errores antes de guardar');
            return;
        }
        const modalEditarEl = document.getElementById('modalEditar');
        const modalEditar = new bootstrap.Modal(modalEditarEl);

        modalEditarEl.dataset.abrirVerificacion = "0";   
        modalEditar.show();
    });
    document.getElementById('btnConfirmarModificar').addEventListener('click', function () {
        const modalEditarEl = document.getElementById('modalEditar');
        modalEditarEl.dataset.abrirVerificacion = "1";

        const modalEditar = bootstrap.Modal.getInstance(modalEditarEl);
        modalEditar.hide();
    });
    document.getElementById('modalEditar').addEventListener('hidden.bs.modal', function () {
        if (this.dataset.abrirVerificacion === "1") {
            this.dataset.abrirVerificacion = "0";
            mostrarAlertaContrasena();
        }
    });


    // ---- Funciones auxiliares ----
    function validarFormulario() {
        let valido = true;

        if (!regexLetras.test(nombreInput.value)) {
            errorNombre.style.display = 'block';
            valido = false;
        }

        if (!regexLetras.test(apellidoInput.value)) {
            errorApellido.style.display = 'block';
            valido = false;
        }

        if (!emailInput.value.includes('@')) {
            errorEmail.style.display = 'block';
            valido = false;
        }
        if (telefonoInput.value.length !== 10 || !/^\d+$/.test(telefonoInput.value)) {
            errorTelefono.style.display = 'block';
            valido = false;
        }

        return valido;
    }
    
    function resetErrores() {
        errorNombre.style.display = 'none';
        errorApellido.style.display = 'none';
        errorEmail.style.display = 'none';
        errorTelefono.style.display = 'none';
    }

    // --- Función para formatear el texto ---
    function formatearTexto(texto) {
        return texto
            .normalize("NFD")
            .replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, "")
            .replace(/\s{2,}/g, " ") // Permite un solo espacio
            .trim()
            .toLowerCase()
            .split(" ")
            .map(p => p.charAt(0).toUpperCase() + p.slice(1))
            .join(" ");
    }


    function mostrarAlertaContrasena() {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div style="
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                font-family: 'Poppins', sans-serif;
            ">

                <div style="
                    background: #fff;
                    border-radius: 16px;
                    width: 90%;
                    max-width: 380px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                    overflow: hidden;
                    animation: fadeIn 0.3s ease-out;
                ">

                    <!-- Header degradado -->
                    <div style="
                        background: linear-gradient(135deg, #FF7D29, #F3C623);
                        color: white;
                        padding: 15px;
                        text-align: center;
                        font-weight: 600;
                        font-size: 1.2rem;
                        position: relative;
                    ">
                        Verificar identidad
                        <span id="cerrar-modal" style="
                            position: absolute;
                            right: 15px;
                            top: 10px;
                            font-size: 1.3rem;
                            cursor: pointer;
                            color: white;
                            font-weight: bold;
                        ">&times;</span>
                    </div>

                    <!-- Contenido -->
                    <div style="padding-bottom: 25px; padding-left: 25px; padding-right: 25px; text-align: center; background: #fff;">
                        <p style="color: #333; font-size: 0.95rem; margin-bottom: 15px;">
                            Por favor, ingresa tu contraseña para confirmar los cambios en tu perfil.
                        </p>

                        <div style="display: flex; justify-content: center;">
                            <input type="password" id="contrasena-verificar"
                                placeholder="Ingresa tu contraseña"
                                minlength="8" maxlength="15"
                                style="
                                    width: 90%;
                                    max-width: 280px;
                                    padding: 10px;
                                    border: 1px solid #ccc;
                                    border-radius: 8px;
                                    outline: none;
                                    font-size: 0.95rem;
                                    text-align: center;
                                ">
                        </div>
                        <p id="error-contrasena" style="
                            color: red;
                            display: none;
                            margin-top: 15px;
                            font-size: 0.9rem;
                        ">Contraseña incorrecta</p>

                        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
                            <button id="btn-verificar-contrasena" style="
                                background: linear-gradient(135deg, #FF7D29, #F3C623);
                                color: white;
                                border: none;
                                border-radius: 8px;
                                padding: 8px 15px;
                                cursor: pointer;
                                font-weight: bold;
                                transition: transform 0.2s ease, opacity 0.2s ease;
                            ">Verificar</button>

                            <button id="btn-cancelar-verificacion" style="
                                background: #ccc;
                                color: #333;
                                border: none;
                                border-radius: 8px;
                                padding: 8px 18px;
                                cursor: pointer;
                                font-weight: bold;
                                transition: background 0.2s ease;
                            ">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                @keyframes fadeIn {
                    from { opacity: 0; transform: scale(0.9); }
                    to { opacity: 1; transform: scale(1); }
                }
            </style>
        `;
        document.body.appendChild(modal);
        // --- enfocar el input para que se pueda escribir inmediatamente ---
        const inputLocal = modal.querySelector('#contrasena-verificar');
        setTimeout(() => {
        if (inputLocal) inputLocal.focus();
        }, 50);
        document.getElementById('btn-verificar-contrasena').addEventListener('click', verificarContrasena);
        document.getElementById('btn-cancelar-verificacion').addEventListener('click', () => document.body.removeChild(modal));
        const cerrarModal = modal.querySelector('#cerrar-modal');
        document.getElementById('contrasena-verificar').addEventListener('keypress', e => {
            if (e.key === 'Enter') verificarContrasena();
        });
        const cerrar = () => modal.remove();
        cerrarModal.addEventListener('click', cerrar);
        function verificarContrasena() {
            const contrasena = document.getElementById('contrasena-verificar').value;
            const errorContrasena = document.getElementById('error-contrasena');

            if (!contrasena) {
                errorContrasena.textContent = 'Por favor ingresa tu contraseña';
                errorContrasena.style.display = 'block';
                return;
            }
            if (contrasena.length < 8 || contrasena.length > 15) {
                errorContrasena.textContent = 'La contraseña debe tener entre 8 y 15 caracteres';
                errorContrasena.style.display = 'block';
                return;
            }

            const formData = new FormData();
            formData.append('contrasena', contrasena);

            fetch('verificacionpass.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.body.removeChild(modal);
                    actualizarDatosPerfil();
                } else {
                    errorContrasena.textContent = 'Contraseña incorrecta';
                    errorContrasena.style.display = 'block';
                }
            })
            .catch(() => {
                errorContrasena.textContent = 'Error al verificar contraseña';
                errorContrasena.style.display = 'block';
            });
        }
    }
    function actualizarDatosPerfil() {
        const formData = new FormData();
        formData.append('nombre', nombreInput.value);
        formData.append('apellido', apellidoInput.value);
        formData.append('email', emailInput.value);
        formData.append('telefono', telefonoInput.value);

        fetch('actualizarperfil.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                var modal = new bootstrap.Modal(document.getElementById('modalExitoActualizar'));
                modal.show();

                setTimeout(() => {
                    location.reload();
                }, 1500); // 1.5 segundos para ver el modal
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);

            const modalError = new bootstrap.Modal(document.getElementById('modalErrorSubir'));
            modalError.show();
        });
    }
});
// ---- Cerrar sesión ----
function cerrarSesion() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
    modalLogout.show();
}
