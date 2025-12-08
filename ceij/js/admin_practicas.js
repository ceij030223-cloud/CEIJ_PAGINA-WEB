const modalTarjeta = new bootstrap.Modal(document.getElementById('modalTarjeta'));
const formTarjeta = document.getElementById('formTarjeta');
const tituloModal = document.getElementById('tituloModal');

const dropZone = document.getElementById('dropZone');
const imagenInput = document.getElementById('imagenInput');
const preview = document.getElementById('preview');

// Tooltip para el botón flotante
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.map(t => new bootstrap.Tooltip(t))

// Abrir modal para agregar
document.getElementById('btnAgregar').addEventListener('click', () => {
  formTarjeta.reset();
  tituloModal.textContent = 'Agregar área de práctica';

  // Limpiar campos
  document.getElementById("id_tarjeta").value = "";
  document.getElementById("titulo").value = "";
  document.getElementById("descripcion").value = "";
  document.getElementById("id_seccion").value = "";
  
  // Limpiar imágenes
  document.getElementById("currentImageContainer").classList.add("d-none");
  document.getElementById("imagenInput").value = "";
  document.getElementById("preview").classList.add("d-none");

  // Ocultar error
  document.getElementById("errorImagen").classList.add("d-none");

  // Mostrar modal
  const modal = new bootstrap.Modal(document.getElementById("modalTarjeta"));
  modal.show();
});

// Abrir modal para editar
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    tituloModal.textContent = 'Editar área de práctica';
    document.getElementById('id_tarjeta').value = btn.dataset.id;
    document.getElementById('titulo').value = btn.dataset.titulo;
    document.getElementById('descripcion').value = btn.dataset.descripcion;
    document.getElementById('id_seccion').value = btn.dataset.seccion;
    const currentImageContainer = document.getElementById('currentImageContainer');
    const currentImage = document.getElementById('currentImage');

    // Permitir editar sin subir nueva imagen
    imagenInput.removeAttribute('required');

    // Mostrar imagen actual
    if (btn.dataset.imagen) {
      currentImage.src = btn.dataset.imagen;
      currentImageContainer.classList.remove('d-none');
    } else {
      currentImageContainer.classList.add('d-none');
      currentImage.src = '';
    }

    // Limpiar preview de nueva imagen
    preview.src = '';
    preview.classList.add('d-none');

    modalTarjeta.show();
  });
});

let tarjetaAEliminar = null;

// Detectar click en botón eliminar
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn-delete');
  if (btn) {
    tarjetaAEliminar = btn.dataset.id;

    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('modalEliminarTarjeta'));
    modal.show();
  }
});

// Cuando el usuario confirma la eliminación
document.getElementById('btnEliminarTarjetaConfirmado').addEventListener('click', async () => {

  if (!tarjetaAEliminar) return;

  const formData = new FormData();
  formData.append('id_tarjeta', tarjetaAEliminar);

  const respuesta = await fetch('eliminar_tarjeta.php', {
    method: 'POST',
    body: formData
  });

  const resultado = await respuesta.json();

  if (resultado.status === 'ok') {

    // Ocultar modal de confirmación si está abierto
    let modalConfirm = bootstrap.Modal.getInstance(document.getElementById('modalEliminarTarjeta'));
    if (modalConfirm) {
      modalConfirm.hide();
    }

    // Mostrar modal de éxito
    const modalExito = new bootstrap.Modal(document.getElementById('modalExitoTarjeta'));
    modalExito.show();

    // Recargar después de 1.2s
    setTimeout(() => location.reload(), 1200);

  } else {
    document.getElementById("mensajeErrorEliminar").innerText = resultado.mensaje;
    const modalConfirmar = bootstrap.Modal.getInstance(document.getElementById('modalEliminarTarjeta'));
    if (modalConfirmar) modalConfirmar.hide();

    let modalError = new bootstrap.Modal(document.getElementById('modalErrorEliminar'));
    modalError.show();
  }
});


// Guardar tarjeta (crear o editar)
formTarjeta.addEventListener('submit', async (e) => {
  e.preventDefault();
  const isAgregar = !document.getElementById('id_tarjeta').value;
  const errorImagen = document.getElementById('errorImagen');
  // Validar imagen solo al AGREGAR
  if (isAgregar && imagenInput.files.length === 0) {
    errorImagen.textContent = 'Debes seleccionar una imagen';
    errorImagen.classList.remove('d-none');
    return;
  }
  console.log("ID:", document.getElementById("id_tarjeta").value);

  // Ocultar error si ya se seleccionó imagen
  document.getElementById('errorImagen').classList.add('d-none');
  
  const formData = new FormData(formTarjeta);
  const id = formData.get('id_tarjeta');
  const url = id ? 'editar_tarjeta.php' : 'guardar_tarjeta.php';

  try {
    const respuesta = await fetch(url, {
      method: 'POST',
      body: formData
    });
    const resultado = await respuesta.json();
    if (resultado.status === 'ok') {
      const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalTarjeta'))
      new bootstrap.Modal(document.getElementById('modalTarjeta'));
      modalInstance.hide();

      // Mostrar texto dinámico por si cambia en PHP
      document.getElementById('mensajeExitoGuardarTarjeta').textContent = resultado.mensaje;

      // Mostrar modal de éxito guardar
      const modalExitoGuardar = new bootstrap.Modal(document.getElementById('modalExitoGuardarTarjeta'));
      modalExitoGuardar.show();

      // Recargar en 1.2s
      setTimeout(() => location.reload(), 1200);
    } else {
    // Mostrar mensaje del servidor si existe
    document.getElementById("mensajeErrorGuardarDB").innerText =
      resultado.mensaje || "Error al guardar en la base de datos.";

    // Ocultar el modal principal para evitar superposición
    const modalPrincipal = bootstrap.Modal.getInstance(document.getElementById('modalTarjeta'));
    if (modalPrincipal) modalPrincipal.hide();

    // Mostrar modal elegante de error
    const modalErrorGuardar = new bootstrap.Modal(document.getElementById('modalErrorGuardarDB'));
    modalErrorGuardar.show();
    }
  } catch (error) {
  const modalErrorConexion = new bootstrap.Modal(document.getElementById('modalErrorConexion'));

  // Asegurar que el modal principal se oculte para evitar superposición
  const modalPrincipal = bootstrap.Modal.getInstance(document.getElementById('modalTarjeta'));
  if (modalPrincipal) modalPrincipal.hide();

  modalErrorConexion.show();
  }
});

// Clic en el área → abrir selector
dropZone.addEventListener('click', () => imagenInput.click());

// Mostrar preview de nueva imagen (DropZone)
imagenInput.addEventListener('change', (e) => {
  if (e.target.files.length) {
    mostrarVistaPrevia(e.target.files[0]); // solo afecta la sección "Agregar / Cambiar imagen"
  }
  if (imagenInput.files.length > 0) {
    document.getElementById('errorImagen').classList.add('d-none');
  }
});

// Permitir arrastrar archivo
dropZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropZone.style.borderColor = '#e45e1b';
  dropZone.style.backgroundColor = '#fff7f3';
});

dropZone.addEventListener('dragleave', () => {
  dropZone.style.borderColor = '#ccc';
  dropZone.style.backgroundColor = '#fff';
});

// Soltar imagen
dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropZone.style.borderColor = '#ccc';
  dropZone.style.backgroundColor = '#fff';
  if (e.dataTransfer.files.length) {
    imagenInput.files = e.dataTransfer.files;
    mostrarVistaPrevia(e.dataTransfer.files[0]);
  }
});

function mostrarVistaPrevia(file) {
  const reader = new FileReader();
  reader.onload = (e) => {
    preview.src = e.target.result;
    preview.classList.remove('d-none');
  };
  reader.readAsDataURL(file);
}

function abrirFullscreen(img) {
  if (img.requestFullscreen) {
    img.requestFullscreen();
  } else if (img.webkitRequestFullscreen) { /* Safari */
    img.webkitRequestFullscreen();
  } else if (img.msRequestFullscreen) { /* IE11 */
    img.msRequestFullscreen();
  }
}

[currentImage, preview].forEach(img => {
  img.addEventListener('click', () => {
    if (img.src) abrirFullscreen(img);
  });
});
modalTarjeta._element.addEventListener('hidden.bs.modal', function () {
  document.getElementById('btnAgregar').classList.remove('no-tooltip');
});
// --- Manejo robusto del tooltip del botón flotante en dispositivos táctiles / móviles ---
// elemento del botón
const btnFloat = document.getElementById('btnAgregar');

// Si existe, obtenemos la instancia de Bootstrap Tooltip (si la hay)
let bsTooltipInstance = null;
if (btnFloat) {
  bsTooltipInstance = bootstrap.Tooltip.getInstance(btnFloat) || bootstrap.Tooltip.getOrCreateInstance(btnFloat);

  // Guarda el title original para restaurarlo luego
  if (btnFloat.title) btnFloat.dataset._origTitle = btnFloat.title;
}

// Función que desactiva el tooltip (CSS + bootstrap)
function disableFloatTooltip() {
  if (!btnFloat) return;
  // 1) Añadir clase que oculta tu pseudo-tooltip CSS (ya tienes la regla .no-tooltip)
  btnFloat.classList.add('no-tooltip');

  // 2) Ocultar / destruir tooltip de Bootstrap si existe
  if (bsTooltipInstance) {
    try {
      bsTooltipInstance.hide();
      bsTooltipInstance.disable(); // evita que vuelva a mostrarse
      // opcional: bsTooltipInstance.dispose();
    } catch (err) { /* no-fall */ }
  }

  // 3) Quitar el atributo title (algunos navegadores nativos siguen mostrando)
  if (btnFloat.getAttribute('title')) {
    btnFloat.dataset._savedTitle = btnFloat.getAttribute('title');
    btnFloat.removeAttribute('title');
  }
  // También quitar data-bs-original-title si existe
  if (btnFloat.getAttribute('data-bs-original-title')) {
    btnFloat.dataset._savedBsOriginal = btnFloat.getAttribute('data-bs-original-title');
    btnFloat.removeAttribute('data-bs-original-title');
  }
}

// Función que vuelve a activar el tooltip (cuando el modal se cierre)
function enableFloatTooltip() {
  if (!btnFloat) return;
  btnFloat.classList.remove('no-tooltip');

  // Restaurar atributos title originales (si estaban guardados)
  if (btnFloat.dataset._savedTitle) {
    btnFloat.setAttribute('title', btnFloat.dataset._savedTitle);
    delete btnFloat.dataset._savedTitle;
  }
  if (btnFloat.dataset._savedBsOriginal) {
    btnFloat.setAttribute('data-bs-original-title', btnFloat.dataset._savedBsOriginal);
    delete btnFloat.dataset._savedBsOriginal;
  }

  // Re-enable Bootstrap tooltip instance (recrear si fue desactivado)
  try {
    if (bsTooltipInstance) {
      bsTooltipInstance.enable();
    } else {
      bsTooltipInstance = bootstrap.Tooltip.getOrCreateInstance(btnFloat);
    }
  } catch (err) { /* no-fall */ }
}

// Llamar a disableFloatTooltip() cuando el modal se abra; restaurar al cerrarse
const modalEl = document.getElementById('modalTarjeta');
if (modalEl) {
  modalEl.addEventListener('show.bs.modal', disableFloatTooltip);
  modalEl.addEventListener('shown.bs.modal', disableFloatTooltip); // extra seguro
  modalEl.addEventListener('hidden.bs.modal', enableFloatTooltip);
}
function confirmarLogout() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
  modalLogout.show();
}
