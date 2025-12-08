window.archivosSeleccionados = []; // Global
document.addEventListener('DOMContentLoaded', () => {
  const uploadArea = document.getElementById('uploadAreaCarrusel');
  const fileInput = document.getElementById('fileInputCarrusel');
  const previewContainer = document.getElementById('previewCarrusel');
  const archivosSeleccionados = window.archivosSeleccionados; // Alias local

  function makeId() {
      return Date.now().toString(36) + Math.random().toString(36).slice(2,8);
  }

  uploadArea.addEventListener('click', () => fileInput.click());

  uploadArea.addEventListener('dragover', e => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
  });

  uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));

  uploadArea.addEventListener('drop', e => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = Array.from(e.dataTransfer.files || []);
      agregarArchivos(files);
  });

  fileInput.addEventListener('change', e => {
      const files = Array.from(e.target.files || []);
      agregarArchivos(files);
      fileInput.value = '';
  });

  function agregarArchivos(files) {
      let anyAdded = false;
      files.forEach(f => {
          if (!f.type.startsWith('image/')) return;
          const existe = archivosSeleccionados.some(a => a.file.name === f.name && a.file.size === f.size);
          if (!existe) {
              archivosSeleccionados.push({ id: makeId(), file: f });
              anyAdded = true;
          }
      });
      if (anyAdded) actualizarPreviewsYInput();
  }

  function actualizarPreviewsYInput() {
      previewContainer.innerHTML = '';
      const dt = new DataTransfer();

      archivosSeleccionados.forEach(item => dt.items.add(item.file));

      archivosSeleccionados.forEach(item => {
          const reader = new FileReader();
          reader.onload = e => {
              const wrapper = document.createElement('div');
              wrapper.className = 'preview-item position-relative d-inline-block me-2';
              const img = document.createElement('img');
              img.src = e.target.result;
              img.style.height = '100px';
              img.style.width = '120px';
              img.style.objectFit = 'cover';
              img.style.borderRadius = '6px';
              img.style.border = '1px solid #ddd';
              img.style.cursor = 'pointer';
              img.classList.add("preview-img");
              img.addEventListener('click', () => verFullscreen(img));
              const btnX = document.createElement('button');
              btnX.type = 'button';
              btnX.className = 'btn-eliminar-preview';
              btnX.title = 'Eliminar de la lista';
              btnX.innerHTML = '&times;';
              btnX.addEventListener('click', () => {
                  const idx = archivosSeleccionados.findIndex(a => a.id === item.id);
                  if (idx > -1) {
                      archivosSeleccionados.splice(idx, 1);
                      actualizarPreviewsYInput();
                      fileInput.value = '';
                  }
              });
              wrapper.appendChild(img);
              wrapper.appendChild(btnX);
              previewContainer.appendChild(wrapper);
          };
          reader.readAsDataURL(item.file);
      });
      fileInput.files = dt.files;
  }

  // Reemplaza tu verFullscreen actual por esta versión
  function verFullscreen(imgElement) {
    // Si ya estamos en fullscreen, salir
    if (document.fullscreenElement) {
        document.exitFullscreen();
        return;
    }

    // Crear contenedor (wrapper) para fullscreen
    const wrapper = document.createElement('div');
    wrapper.id = 'fsWrapper';
    wrapper.style.cssText = `
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        z-index: 99999;
    `;

    // Clonar la imagen para no moverla del DOM original
    const imgClone = document.createElement('img');
    imgClone.src = imgElement.src || imgElement.getAttribute('src');
    imgClone.alt = imgElement.alt || '';
    // IMPORTANT: mantener proporción y que nunca sobresalga de la pantalla
    imgClone.style.cssText = `
        max-width: 100%;
        max-height: 100vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 6px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.6);
        cursor: pointer;
    `;

    // Click en la imagen: salir del fullscreen (y quitamos el wrapper luego)
    imgClone.addEventListener('click', () => {
        if (document.fullscreenElement) document.exitFullscreen();
        else if (wrapper.parentElement) wrapper.remove();
    });

    // También permitir click fuera (en wrapper) para salir
    wrapper.addEventListener('click', (e) => {
        if (e.target === wrapper) {
        if (document.fullscreenElement) document.exitFullscreen();
        else wrapper.remove();
        }
    });

    wrapper.appendChild(imgClone);
    document.body.appendChild(wrapper);

    // Solicitar fullscreen sobre el wrapper (más consistente que sobre la img sola)
    // Algunos navegadores requieren llamado desde evento de usuario; aquí lo estamos llamando desde un click, así que ok.
    const request = wrapper.requestFullscreen ? wrapper.requestFullscreen() : Promise.resolve();

    // Cuando termine el fullscreen (se cierre con ESC u otra cosa), removemos el wrapper
    function cleanup() {
        if (wrapper.parentElement) wrapper.remove();
        document.removeEventListener('fullscreenchange', onFsChange);
    }
    function onFsChange() {
        if (!document.fullscreenElement) cleanup();
    }
    document.addEventListener('fullscreenchange', onFsChange);

    // Por seguridad: si el request falla, removemos el wrapper después de 1s
    request.catch(() => {
        setTimeout(() => { if (wrapper.parentElement) wrapper.remove(); }, 1000);
    });
    }

  // Selecciona todas las imágenes existentes y les agrega el click
  document.querySelectorAll('#formEliminarMultiple .preview-img').forEach(img => {
    img.addEventListener('click', () => verFullscreen(img));
  });
  window.limpiarPreviewsCarrusel = function() {
      archivosSeleccionados.length = 0;
      actualizarPreviewsYInput();
  };
  const btnEliminar = document.getElementById("btnEliminarSeleccionadas");
  const modalConfirmarEliminar = new bootstrap.Modal(document.getElementById("modalConfirmarEliminar"));
  const modalMensaje = new bootstrap.Modal(document.getElementById("modalMensaje"));
  const btnEliminarConfirmado = document.getElementById("btnEliminarConfirmado");
  const modalErrorSubir = new bootstrap.Modal(document.getElementById("modalErrorSubir"));
  const modalExitoSubir = new bootstrap.Modal(document.getElementById("modalExitoSubir"));
  const form = document.getElementById("formCarrusel");

  btnEliminar.addEventListener("click", () => {
    const seleccionados = document.querySelectorAll('input[name="seleccion[]"]:checked');

    if (seleccionados.length === 0) {
        let modal = new bootstrap.Modal(document.getElementById("modalErrorEliminar"));
        modal.show();
        return;
    }

    const contenedorPrev = document.getElementById("previewEliminar");
    contenedorPrev.innerHTML = "";

    seleccionados.forEach(ch => {
        const src = ch.closest(".col-4").querySelector("img").src;

        const imgPrev = document.createElement("img");
        imgPrev.src = src;
        imgPrev.classList.add("preview-img");
        imgPrev.style.height = "70px";
        imgPrev.style.width = "90px";
        imgPrev.style.objectFit = "cover";
        imgPrev.style.borderRadius = "6px";
        imgPrev.style.border = "1px solid #ddd";
        imgPrev.style.cursor = "pointer";

        // Abrir fullscreen
        imgPrev.addEventListener("click", () => verFullscreen(imgPrev));

        contenedorPrev.appendChild(imgPrev);
    });

    modalConfirmarEliminar.show();
  });

  btnEliminarConfirmado.addEventListener("click", async () => {
    const seleccionados = [...document.querySelectorAll('input[name="seleccion[]"]:checked')]
                          .map(ch => ch.value);

    const formData = new FormData();
    seleccionados.forEach(id => formData.append("seleccion[]", id));

    try {
      const res = await fetch("../html/carrusel_eliminar.php", {
        method: "POST",
        body: formData
      });

      const data = await res.text();

      // Si PHP regresa "success"
      if (data.trim() === "success") {

        // Remover elementos del DOM sin refrescar
        seleccionados.forEach(id => {
          const img = document.querySelector(`input[value="${id}"]`).closest(".col-4");
          if (img) img.remove();
        });

        modalConfirmarEliminar.hide();

        document.getElementById("mensajeTexto").textContent = "Imágenes eliminadas correctamente.";
        modalMensaje.show();
      } else {
        document.getElementById("mensajeContenido").textContent = "Ocurrió un error al eliminar.";
        modalMensaje.show();
      }

    } catch (e) {
      document.getElementById("mensajeContenido").textContent = "Error de conexión.";
      modalMensaje.show();
    }
  });
  form.addEventListener("submit", async function(e) {
    e.preventDefault(); // No recargar

    if (!prepararEnvioCarrusel()) return;

    const formData = new FormData(form);

    try {
        const res = await fetch("../html/carrusel_subir.php", {
            method: "POST",
            body: formData
        });

        const data = await res.text();

        if (data.trim() === "success") {
            modalExitoSubir.show();
            window.limpiarPreviewsCarrusel();

            const modalElemento = document.getElementById("modalExitoSubir");

            modalElemento.addEventListener("hidden.bs.modal", function () {
                actualizarGaleriaCarrusel();
            }, { once: true });
        } else {
            modalErrorSubir.show();
        }

    } catch (error) {
        modalErrorSubir.show();
    }
  });

});

//Validar antes de enviar el formulario
function prepararEnvioCarrusel() {
    const fileInput = document.getElementById('fileInputCarrusel');

    // Sincronizar archivos seleccionados al input
    const dt = new DataTransfer();
    window.archivosSeleccionados.forEach(item => dt.items.add(item.file));
    fileInput.files = dt.files;

    // Validar si no hay archivos
    if (!fileInput.files.length) {
        let modal = new bootstrap.Modal(document.getElementById("modalErrorSubir"));
        modal.show();
        return false; // Evita submit tradicional
    }

    return true; // Permite continuar al manejador JS
}


function cerrarModal() {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    }
}
async function actualizarGaleriaCarrusel() {
    try {
        const res = await fetch("../html/carrusel_listar.php");
        const data = await res.json();

        const contenedor = document.getElementById("contenedorImagenesExistentes");
        contenedor.innerHTML = "";

        data.forEach(item => {
            const div = document.createElement("div");
            div.className = "col-4 mb-3 text-center position-relative";

            div.innerHTML = `
                <input type="checkbox" name="seleccion[]" value="${item.id}" class="position-absolute m-1">

                <img src="../img/carrusel/${item.imagen}" 
                    class="img-fluid rounded border preview-img" 
                    style="height:120px; object-fit:cover; cursor:pointer;">
            `;

            // Agregar fullscreen
            div.querySelector("img").addEventListener("click", e => verFullscreen(e.target));

            contenedor.appendChild(div);
        });

    } catch (error) {
        console.error("Error actualizando galería:", error);
    }
}
function confirmarLogout() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
  modalLogout.show();
}