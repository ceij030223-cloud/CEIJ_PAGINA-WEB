document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".tab-btn");
  const contenidos = document.querySelectorAll(".tab-content");

  botones.forEach((btn) => {
    btn.addEventListener("click", () => {
      botones.forEach(b => b.classList.remove("activo"));
      contenidos.forEach(c => c.classList.remove("activo"));

      btn.classList.add("activo");
      document.getElementById(btn.dataset.tab).classList.add("activo");
    });
  });
});

// Sistema de modificación de datos personales
    document.addEventListener('DOMContentLoaded', function() {
    const btnModificar = document.getElementById('btn-modificar');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const divGuardar = document.getElementById('div-guardar');
    const vistaInfo = document.getElementById('vista-info');
    const editarInfo = document.getElementById('editar-info');
    const nombreInput = document.getElementById('nombre-editar');
    const apellidoInput = document.getElementById('apellido-editar');
    const errorNombre = document.getElementById('error-nombre');
    const errorApellido = document.getElementById('error-apellido');
    const emailInput = document.getElementById('email-editar');
    const telefonoInput = document.getElementById('telefono-editar');
    const errorEmail = document.getElementById('error-email');
    const errorTelefono = document.getElementById('error-telefono');

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

    // Validación en tiempo real del nombre
    nombreInput.addEventListener('input', function() {
        const patron = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u;
        if (!patron.test(this.value)) {
            errorNombre.style.display = 'block';
        } else {
            errorNombre.style.display = 'none';
        }
    });

    // Validación en tiempo real del apellido
    apellidoInput.addEventListener('input', function() {
        const patron = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u;
        if (!patron.test(this.value)) {
            errorApellido.style.display = 'block';
        } else {
            errorApellido.style.display = 'none';
        }
    });


    // Validaciones en tiempo real
    emailInput.addEventListener('input', function() {
        if (!this.value.includes('@')) {
            errorEmail.style.display = 'block';
        } else {
            errorEmail.style.display = 'none';
        }
    });

    telefonoInput.addEventListener('input', function() {
        if (this.value.length !== 10 || !/^\d+$/.test(this.value)) {
            errorTelefono.style.display = 'block';
        } else {
            errorTelefono.style.display = 'none';
        }
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

// Función para mostrar la alerta flotante de contraseña
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
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                animation: fadeIn 0.3s ease-out;
            ">

                <!-- Header -->
                <div style="
                    background: linear-gradient(135deg, #FF7D29, #F3C623);
                    color: white;
                    padding: 15px;
                    text-align: center;
                    position: relative;
                    font-weight: 600;
                    font-size: 1.2rem;
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

                <!-- Cuerpo del modal -->
                <div style="padding-top: 5px; padding-bottom: 25px; padding-left: 25px; padding-right: 25px; text-align: center; background: #fff;">
                    <p style="color: #333; font-size: 0.95rem; margin-bottom: 15px;">
                        Por favor, ingresa tu contraseña para confirmar los cambios:
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
                            font-weight: bold;
                            cursor: pointer;
                            transition: transform 0.2s ease opacity 0.2s ease;
                        ">Verificar</button>

                        <button id="btn-cancelar-verificacion" style="
                            background: #ccc;
                            color: #333;
                            border: none;
                            border-radius: 8px;
                            padding: 8px 15px;
                            font-weight: bold;
                            cursor: pointer;
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

    // Eventos para los botones del modal
    document.getElementById('btn-verificar-contrasena').addEventListener('click', function() {
        verificarContrasena();
    });

    document.getElementById('btn-cancelar-verificacion').addEventListener('click', function() {
        document.body.removeChild(modal);
    });

    // Permitir verificación con Enter
    document.getElementById('contrasena-verificar').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verificarContrasena();
        }
    });
    const cerrarModal = modal.querySelector('#cerrar-modal');
    const cerrar = () => modal.remove();
    cerrarModal.addEventListener('click', cerrar);

    // Función para verificar la contraseña
    function verificarContrasena() {
        const contrasena = document.getElementById('contrasena-verificar').value;
        const errorContrasena = document.getElementById('error-contrasena');

        if (!contrasena) {
            errorContrasena.textContent = 'Por favor ingresa tu contraseña';
            errorContrasena.style.display = 'block';
            return;
        }
        // Validar longitud de la contraseña
         if (contrasena.length < 8 || contrasena.length > 15) {
        errorContrasena.textContent = 'La contraseña debe tener entre 8 y 15 caracteres';
        errorContrasena.style.display = 'block';
        return;
        }
        // Verificar contraseña con la base de datos
        const formData = new FormData();
        formData.append('contrasena', contrasena);

        fetch('verificacionpass.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Contraseña correcta, proceder con la actualización
                document.body.removeChild(modal);
                actualizarDatosPerfil();
            } else {
                errorContrasena.textContent = 'Contraseña incorrecta';
                errorContrasena.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorContrasena.textContent = 'Error al verificar contraseña';
            errorContrasena.style.display = 'block';
        });
    }
}
function formatearNombre(nombre) {
    return nombre
        .toLowerCase()
        .replace(/\b\w/g, l => l.toUpperCase()); // primera letra de cada palabra mayúscula
}

function formatearNombre(nombre) {
    return nombre
        .toLowerCase()
        .replace(/\b\w/g, l => l.toUpperCase()); // primera letra de cada palabra mayúscula
}

function actualizarDatosPerfil() {
    const formData = new FormData();
    formData.append('nombre', formatearNombre(nombreInput.value));
    formData.append('apellido', formatearNombre(apellidoInput.value));
    formData.append('email', emailInput.value);
    formData.append('telefono', telefonoInput.value);

    fetch('actualizarperfil.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
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

function validarFormulario() {
    let valido = true;
    const patron = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u;

    // Nombre
    if (!patron.test(nombreInput.value)) {
        errorNombre.style.display = 'block';
        valido = false;
    } else {
        errorNombre.style.display = 'none';
    }

    // Apellido
    if (!patron.test(apellidoInput.value)) {
        errorApellido.style.display = 'block';
        valido = false;
    } else {
        errorApellido.style.display = 'none';
    }

    // Email
    if (!emailInput.value.includes('@')) {
        errorEmail.style.display = 'block';
        valido = false;
    } else {
        errorEmail.style.display = 'none';
    }

    // Teléfono
    if (telefonoInput.value.length !== 10 || !/^\d+$/.test(telefonoInput.value)) {
        errorTelefono.style.display = 'block';
        valido = false;
    } else {
        errorTelefono.style.display = 'none';
    }

    return valido;
}
});

// ---- Cerrar sesión ----
function cerrarSesion() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
    modalLogout.show();
}

