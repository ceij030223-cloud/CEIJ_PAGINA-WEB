// Sistema de modificación 
document.addEventListener('DOMContentLoaded', function() {
    function toggleEditMode(userId, enable) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (!row) return;
        
        // Habilitar/deshabilitar el select de rol también
        const rolSelect = row.querySelector('.rol-select');
        const btnModificar = row.querySelector('.btn-modificar');
        const botonGuardar = row.querySelector('.boton-guardar');
        const btnEliminar = row.querySelector('.btn-eliminar');

        // Aplicar cambios al select de rol
    if (rolSelect) {
        rolSelect.disabled = !enable;
        if (enable) {
            rolSelect.style.backgroundColor = '#f8f9fa';
            rolSelect.style.borderColor = '#ffa200ff';
        } else {
            rolSelect.style.backgroundColor = '#fff';
            rolSelect.style.borderColor = '#ccc';
        }
    }
        
        
        if (enable) {
            if (btnModificar) btnModificar.style.display = 'none';
            if (botonGuardar) botonGuardar.style.display = 'block';
            if (btnEliminar) btnEliminar.style.display = 'none'; // Ocultar eliminar
        } else {
            if (btnModificar) btnModificar.style.display = 'block';
            if (botonGuardar) botonGuardar.style.display = 'none';
            if (btnEliminar) btnEliminar.style.display = 'block'; // Mostrar eliminar
        }
      }
      
    
    // Evento para modificar
    document.querySelectorAll('.btn-modificar').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            toggleEditMode(userId, true);
        });
    });
    
    // Evento para cancelar
    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            toggleEditMode(userId, false);
        });
    });
    
    // Evento para guardar
    document.querySelectorAll('.btn-cambiar').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            guardarDatos(userId);
        });
    });
    
    // Función para guardar datos en la BD
    function guardarDatos(userId) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (!row) return;
        
       // Obtener el valor del select de rol
    const rolSelect = row.querySelector('.rol-select');
    const nuevoRol = rolSelect ? rolSelect.value : '';
     // Validar que se haya seleccionado un rol
    if (!nuevoRol) {
        alert('Por favor seleccione un rol');
        return;
    }
    
        
        // Crear FormData para enviar datos
        const formData = new FormData();
        formData.append('usuario_id', userId);
        formData.append('rol', nuevoRol);
        
        // Enviar datos al servidor
        fetch('actualizar_rol.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar modal de éxito
                const modalExitoRol = new bootstrap.Modal(document.getElementById('modalRolActualizado'));
                document.getElementById('mensajeRolActualizado').textContent = "Rol actualizado correctamente.";
                modalExitoRol.show();

                // Recargar después de 1.2 segundos
                setTimeout(() => location.reload(), 1200);

                toggleEditMode(userId, false);
            } else {
                $('#mensajeErrorRol').text('Error al actualizar el rol: ' + data.message);
                $('#modalErrorRol').modal('show');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#modalErrorConexion').modal('show');
        });
    }
});
// Cerrar modal para botones "Cancelar" / "X" / data-dismiss / data-bs-dismiss
document.addEventListener('click', function(e) {
  // seleccionadores que queremos soportar
  const selector = [
    '[data-dismiss="modal"]',    // bootstrap4 attr
    '[data-bs-dismiss="modal"]', // bootstrap5 attr
    '.custom-close',             // nuestra x personalizada
    '.modal-cancel'              // clase extra para botones cancelar si quieres
  ].join(',');

  const btn = e.target.closest(selector);
  if (!btn) return;

  // Evitar que un button type=submit envíe un form y recargue la página antes de cerrar
  if (btn.tagName === 'BUTTON' && btn.type.toLowerCase() === 'submit') {
    e.preventDefault();
  }

  // Buscar el modal padre más cercano
  const modal = btn.closest('.modal');
  if (!modal) return;

  // Intentar cerrar con Bootstrap 5 API
  if (window.bootstrap && bootstrap.Modal) {
    const inst = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
    try {
      inst.hide();
      return;
    } catch (err) { /* si falla, caer al fallback */ }
  }

  // Intentar cerrar con jQuery/Bootstrap4
  if (window.jQuery && $(modal).modal) {
    try {
      $(modal).modal('hide');
      return;
    } catch (err) { /* fallback */ }
  }

  // Fallback manual (quita clase .show, oculta modal y backdrop)
  modal.classList.remove('show');
  modal.style.display = 'none';
  document.body.classList.remove('modal-open');

  // remover backdrop(s)
  document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
});

// Variables globales para almacenar datos entre modales
let currentUserId = null;

// Manejo del modal de eliminación - PRIMER PASO
document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', function() {
        currentUserId = this.getAttribute('data-user-id');
        obtenerCorreoUsuario(currentUserId); // Llamar a la función aquí
        $('#modalConfirmacion').modal('show');
    });
});

// Continuar al modal de correo
document.getElementById('btnContinuar').addEventListener('click', function() {
    $('#modalConfirmacion').modal('hide');
    $('#modalEliminar').modal('show');
});

// Función para mostrar la alerta flotante de contraseña
function mostrarAlertaContrasena() {
    const modal = document.createElement('div');
    modal.innerHTML = `
        <div style="
            position:fixed; 
            top:0; left:0; 
            width:100%; height:100%; 
            background:rgba(0,0,0,0.6); 
            display:flex; 
            justify-content:center; 
            align-items:center; 
            z-index:10000;
            font-family: 'Poppins', sans-serif;
        ">
            <div style="
                background: #fff; 
                border-radius:16px; 
                width: 90%; 
                max-width: 380px;
                box-shadow:0 4px 10px rgba(0,0,0,0.3);
                overflow:hidden;
                animation: fadeInScale 0.3s ease-out;
            ">
            
                <!-- HEADER -->
                <div style="
                    background:linear-gradient(135deg, #FF7D29, #F3C623); 
                    color:white; 
                    padding: 15px;
                    text-align: center; 
                    position: relative;
                    font-weight: 600;
                    font-size: 1.2rem; 
                ">
                    Verificar identidad
                    <span id="cerrar-modal-verificacion" style="
                        position:absolute; 
                        right:15px; 
                        top: 10px;
                        font-size: 1.3rem; 
                        cursor:pointer; 
                        color:white;
                        font-weight: bold;
                    ">&times;</span>
                </div>

                <!-- CONTENIDO -->
                <div style="padding:25px; text-align:center; background:#fff;">
                    <p style="color: #333; font-size: 0.95rem; margin-bottom:15px;">
                        Por favor, ingresa tu contraseña para confirmar los cambios:
                    </p>

                    <div style="display:flex; justify-content:center;">
                        <input type="password" id="contrasena-verificar" 
                            placeholder="Ingresa tu contraseña"
                            minlength="8" maxlength="15"
                            style="
                                width:90%; 
                                max-width:280px;
                                padding:10px; 
                                border:1px solid #ccc; 
                                border-radius:8px; 
                                outline:none;
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
                
                    <div style="display:flex; justify-content:center; gap: 10px; margin-top:20px;">
                        <button id="btn-verificar-contrasena" style="
                            background: linear-gradient(135deg, #FF7D29, #F3C623);
                            color:white; 
                            border:none; 
                            border-radius:8px; 
                            padding:8px 15px;
                            font-weight: bold; 
                            cursor:pointer;
                            transition: transform 0.2s ease, opacity 0.2s ease;
                        ">Verificar</button>

                        <button id="btn-cancelar-verificacion" style="
                            background:#ccc; 
                            color: #333;
                            border:none; 
                            border-radius: 8px; 
                            padding:8px 15px;
                            font-weight: bold; 
                            cursor:pointer;
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

    // Eventos del modal de verificación
    document.getElementById('btn-verificar-contrasena').addEventListener('click', function() {
        const contrasena = document.getElementById('contrasena-verificar').value;
        verificarContrasena(contrasena, modal);
    });

    document.getElementById('btn-cancelar-verificacion').addEventListener('click', function() {
        document.body.removeChild(modal);
    });
    // Cerrar modal
    modal.querySelector('#cerrar-modal-verificacion').onclick = () => modal.remove();
}

// Función para verificar contraseña
  function verificarContrasena(contrasena, modal) {
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
            document.body.removeChild(modal);
            enviarCorreoEliminacion();
        } else {
            errorContrasena.textContent = data.message || 'Contraseña incorrecta';
            errorContrasena.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorContrasena.textContent = 'Error al verificar contraseña';
        errorContrasena.style.display = 'block';
    });
}

// Función para enviar correo después de verificación
function enviarCorreoEliminacion() {
    const formData = new FormData(document.getElementById('formEliminar'));
     formData.append('usuario_id', currentUserId);

    fetch('enviarcorreo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('mensajeCorreoExito').textContent = data.message;
            // Mostrar el modal de éxito
            const modalCorreoExito = new bootstrap.Modal(document.getElementById('modalCorreoExito'));
            modalCorreoExito.show();

            // Cerrar automáticamente después de 1.2 segundos y recargar la página
            setTimeout(() => {
                modalCorreoExito.hide();
                location.reload();
            }, 1200);

            // Cerrar el modal de eliminar si estaba abierto
            $('#modalEliminar').modal('hide');
        } else {
            // Mostrar modal de error
            document.getElementById('mensajeErrorCorreo').textContent = data.message;
            const modalErrorCorreo = new bootstrap.Modal(document.getElementById('modalErrorCorreo'));
            modalErrorCorreo.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Mostrar modal de error genérico
        document.getElementById('mensajeErrorCorreo').textContent = "Error al procesar la eliminación";
        const modalErrorCorreo = new bootstrap.Modal(document.getElementById('modalErrorCorreo'));
        modalErrorCorreo.show();
    });
}


// Función para obtener y mostrar el correo del usuario
function obtenerCorreoUsuario(userId) {
    // Buscar la fila del usuario
    const row = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (row) {
        // Obtener el correo del usuario desde la tabla
        const correo = row.querySelector('.employer-name').textContent.trim();
        document.getElementById('correoDestino').value = correo;
    }
}

/// Envío del formulario - TERCER PASO
document.getElementById('btnEnviarCorreo').addEventListener('click', function() {
    const comentario = document.getElementById('comentario').value.trim();
    
    if (!comentario) {
        const modalComentarioVacio = new bootstrap.Modal(document.getElementById('modalComentarioVacio'));
        modalComentarioVacio.show();
        // Enfocar el campo al cerrar el modal
        document.getElementById('modalComentarioVacio').addEventListener('hidden.bs.modal', () => {
            document.getElementById('comentario').focus();
        });
        return;
    }
    
    $('#modalEliminar').modal('hide');
    mostrarAlertaContrasena();
});
const inputImagen = document.getElementById('imagen');
const vistaPrevia = document.getElementById('vistaPrevia');

inputImagen.addEventListener('change', (event) => {
  const archivo = event.target.files[0];
  vistaPrevia.innerHTML = ''; // limpia vista previa

  if (archivo) {
    const lector = new FileReader();
    lector.onload = (e) => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.alt = 'Vista previa';
      img.classList.add('img-miniatura');
      img.addEventListener('click', () => {
        abrirImagenGrande(e.target.result);
      });
      vistaPrevia.appendChild(img);
    };
    lector.readAsDataURL(archivo);
  }
});

// Función para mostrar imagen grande en un modal nativo
function abrirImagenGrande(src) {
  const modal = document.createElement('div');
  modal.classList.add('modal-imagen');
  modal.innerHTML = `
    <div class="modal-imagen-contenido">
      <span class="cerrar-imagen">&times;</span>
      <img src="${src}" alt="Vista ampliada">
    </div>
  `;
  document.body.appendChild(modal);

  // Cerrar al hacer clic
  modal.querySelector('.cerrar-imagen').addEventListener('click', () => modal.remove());
  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.remove();
  });
}
