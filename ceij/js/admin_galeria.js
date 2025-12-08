document.addEventListener('DOMContentLoaded', () => {

  // FUNCIÓN PARA MOSTRAR EN PANTALLA COMPLETA
  function mostrarEnGrande(src, ext) {
    let modal = document.getElementById('modalVistaCompleta');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'modalVistaCompleta';
      modal.classList.add(
        'position-fixed','top-0','start-0','w-100','h-100',
        'd-flex','align-items-center','justify-content-center',
        'bg-dark','bg-opacity-75'
      );
      modal.style.zIndex = '2000';
      modal.innerHTML = `
        <div class="position-relative">
          <span id="cerrarVista"
                style="position:absolute;top:-40px;right:0;color:white;font-size:2rem;cursor:pointer;">
                &times;
          </span>
          <div id="contenidoVista"></div>
        </div>
      `;
      document.body.appendChild(modal);
      modal.querySelector('#cerrarVista').onclick = () => modal.remove();
      // Cerrar al hacer clic fuera de la imagen/video
      modal.addEventListener('click', e => {
        const contenido = modal.querySelector('#contenidoVista');
        if (!contenido.contains(e.target)) {
          modal.remove();
        }
      });
    }

    const cont = modal.querySelector('#contenidoVista');
    cont.innerHTML = '';

    let media;
    if (['mp4','webm','mov','avi','mkv'].includes(ext)) {
      media = document.createElement('video');
      media.src = src;
      media.controls = true;
      media.autoplay = true;
      media.style.maxHeight = '90vh';
      media.style.maxWidth = '90vw';
    } else {
      media = document.createElement('img');
      media.src = src;
      media.style.maxHeight = '90vh';
      media.style.maxWidth = '90vw';
      media.style.borderRadius = '8px';
    }
    cont.appendChild(media);
  }

  // EVENTOS AL ABRIR CUALQUIER MODAL
  document.querySelectorAll('.modal').forEach(modal => {
    // Al cerrar, limpiar previews e inputs
    modal.addEventListener('hidden.bs.modal', () => {
      modal.querySelectorAll('[id^="preview"]').forEach(p => {
        // No limpiar el preview del modal de eliminar contenido
        if (p.id !== 'previewEliminar') p.innerHTML = '';
      });

      modal.querySelectorAll('input[type="file"]').forEach(i => {
        i.value = '';
      });
    });

    // Al abrir el modal, conectar los menús ⋮
    modal.addEventListener('shown.bs.modal', () => {
      modal.querySelectorAll('.btn-menu').forEach(btn => {
        const container = btn.closest('.img-container');
        const menu = container.querySelector('.menu-opciones');

        // Botón de los tres puntos
        btn.addEventListener('click', e => {
          e.stopPropagation();
          modal.querySelectorAll('.menu-opciones').forEach(m => {
            if (m !== menu) m.classList.add('d-none');
          });
          menu.classList.toggle('d-none');
        });

        // Acción “Ver en pantalla completa”
        const btnVer = container.querySelector('.btn-ver-grande');
        if (btnVer) {
          btnVer.addEventListener('click', () => {
            const media = container.querySelector('img, video');
            const src = media.src;
            const ext = src.split('.').pop().toLowerCase();
            mostrarEnGrande(src, ext);
            menu.classList.add('d-none');
          });
        }
      });
    });
  });

  // CERRAR MENÚS AL HACER CLIC AFUERA
  document.addEventListener('click', e => {
    document.querySelectorAll('.menu-opciones').forEach(menu => {
      if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
        menu.classList.add('d-none');
      }
    });
  });

  // MOSTRAR IMÁGENES/VIDEOS EXISTENTES EN GRANDE
  document.querySelectorAll('.img-container').forEach(container => {
    const media = container.querySelector('img, video');

    container.addEventListener('click', e => {
      // Evitar clicks en checkbox, menú o menú de opciones
      if (
        e.target.closest('input[type="checkbox"]') ||
        e.target.closest('.btn-menu') ||
        e.target.closest('.menu-opciones')
      ) return;

      if (!media) return;

      const src = media.src;
      const ext = src.split('.').pop().toLowerCase();

      mostrarEnGrande(src, ext);
    });
  });

  // ZONA DE SUBIDA CON ARRASTRE Y PREVISUALIZACIÓN
  document.querySelectorAll('.upload-area').forEach(area => {
    const id = area.id.replace('uploadArea', '');
    const input = document.getElementById('fileInput' + id);
    const preview = document.getElementById('preview' + id);

    // Click → abrir selector
    area.addEventListener('click', () => input.click());

    // Arrastrar y soltar
    area.addEventListener('dragover', e => {
      e.preventDefault();
      area.classList.add('dragover');
    });
    area.addEventListener('dragleave', () => area.classList.remove('dragover'));
    area.addEventListener('drop', e => {
      e.preventDefault();
      area.classList.remove('dragover');
      mostrarPreview(e.dataTransfer.files, preview, input);
    });

    // Cambiar selección
    input.addEventListener('change', () => mostrarPreview(input.files, preview, input));

    // Función de previsualización
    function mostrarPreview(files, contenedor, input) {
        const nuevos = Array.from(files);

        nuevos.forEach(file => {
            const ext = file.name.split('.').pop().toLowerCase();
            const wrapper = document.createElement('div');
            wrapper.classList.add('preview-item', 'position-relative', 'd-inline-block', 'me-2');

            let media;
            if (['mp4','webm','mov','avi','mkv'].includes(ext)) {
                media = document.createElement('video');
                media.src = URL.createObjectURL(file);
                media.muted = true;
                media.loop = true;
                media.autoplay = true;
            } else {
                media = document.createElement('img');
                media.src = URL.createObjectURL(file);
            }

            media.classList.add('img-thumbnail');
            media.style.maxHeight = '80px';
            media.style.objectFit = 'cover';
            wrapper.appendChild(media);

            // ---- BOTÓN X PARA ELIMINAR ----
            const btnX = document.createElement('button');
            btnX.innerHTML = '×';
            btnX.type = 'button';
            btnX.classList.add('btn','btn-danger','btn-sm','position-absolute');
            btnX.style.top = '5px';
            btnX.style.right = '5px';
            btnX.onclick = (e) => {
                e.stopPropagation();
                wrapper.remove();

                // Eliminar archivo del input
                const dt = new DataTransfer();
                Array.from(input.files).forEach(f => {
                    if (f.name !== file.name || f.size !== file.size) {
                        dt.items.add(f);
                    }
                });
                input.files = dt.files;
            };
            wrapper.appendChild(btnX);

            // ---- VER EN PANTALLA COMPLETA ----
            wrapper.addEventListener('click', e => {
                if (e.target === btnX) return; 
                const src = media.src;
                const ext = file.name.split('.').pop().toLowerCase();
                mostrarEnGrande(src, ext);
            });

            contenedor.appendChild(wrapper);
        });

        // ---- ACUMULAR ARCHIVOS SIN DUPLICAR ----
        const dt = new DataTransfer();
        const actuales = Array.from(input.files);

        nuevos.forEach(file => {
            const existe = actuales.some(f => f.name === file.name && f.size === file.size);
            if (!existe) dt.items.add(file);
        });

        actuales.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }
  });
  // Obtener parámetro GET
    const params = new URLSearchParams(window.location.search);
    const seccionCreada = params.get('seccion_creada');

    if (seccionCreada) {
      // Cambiar mensaje dinámicamente
      document.getElementById('mensajeSeccionExito').textContent = `Sección ${seccionCreada} creada con éxito`;

      // Mostrar modal
      const modal = new bootstrap.Modal(document.getElementById('modalSeccionExito'));
      modal.show();

      // Cerrar automáticamente después de 1.2 segundos
      setTimeout(() => {
          modal.hide();
      }, 1200);

      // Limpiar la URL para no mostrar el modal al refrescar
      window.history.replaceState({}, document.title, window.location.pathname);
    }

  const modalEliminar = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
  let idSeccionSeleccionada = null;

  document.querySelectorAll('.btn-eliminar-seccion').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      idSeccionSeleccionada = btn.dataset.id;
      const nombreSeccion = btn.dataset.nombre;
      document.getElementById('mensajeEliminarSeccion').textContent = `¿Eliminar la sección ${nombreSeccion} y todo su contenido multimedia?`;
      modalEliminar.show();
    });
  });

  document.getElementById('btnEliminarConfirmado').addEventListener('click', () => {
    if (idSeccionSeleccionada) {
      // Ocultar modal de confirmación si está abierto
        const modalConfirmar = bootstrap.Modal.getInstance(document.getElementById('modalConfirmarEliminar'));
        if (modalConfirmar) {
            modalConfirmar.hide();
        }

        // Mostrar modal de éxito
        const modalExito = new bootstrap.Modal(document.getElementById('modalSeccionEliminada'));
        modalExito.show();

        // Cerrar automáticamente después de 1.2 segundos y redirigir
        setTimeout(() => {
            modalExito.hide();
            window.location.href = `admin_galeria.php?eliminar_seccion=${idSeccionSeleccionada}`;
        }, 1200);
    }
  });
  const nombreRenombrada = sessionStorage.getItem('seccionRenombrada');
  if (nombreRenombrada) {
    // Cambiar mensaje dinámicamente
    const modal = new bootstrap.Modal(document.getElementById('modalSeccionRenombrada'));
    document.getElementById('mensajeSeccionRenombradaTexto').textContent =
      `Sección "${nombreRenombrada}" renombrada con éxito`;
    modal.show();

    // Cerrar automáticamente después de 1.2 segundos
    setTimeout(() => modal.hide(), 1200);

    // Limpiar el sessionStorage para que no se vuelva a mostrar
    sessionStorage.removeItem('seccionRenombrada');
  }
  // Script para renombrar sin cerrar modal
  document.querySelectorAll('.renombrar-form').forEach(form => {
    form.addEventListener('submit', function handleRenombrar(e) {
      e.preventDefault();

      const id = form.dataset.id;
      const nuevoNombre = form.querySelector('input[name="nuevo_nombre"]').value.trim();
      if (!nuevoNombre) return;

      fetch('admin_galeria.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `renombrar_seccion=1&id_seccion=${id}&nuevo_nombre=${encodeURIComponent(nuevoNombre)}`
      })
      .then(res => res.text())
      .then(() => {
        // Actualizar el título dentro del modal
        const modalEditar = document.getElementById('modalEditar' + id);
        modalEditar.querySelector('.modal-title').textContent = `Editar galería: ${nuevoNombre}`;

        // Actualizar el nombre en el listado sin recargar
        const liNombre = document.querySelector(`li[data-id="${id}"] .nombre-seccion`);
        if (liNombre) liNombre.innerHTML = `<i class="bi bi-folder"></i> ${nuevoNombre}`;

        // Mostrar modal de éxito
        const modal = new bootstrap.Modal(document.getElementById('modalSeccionRenombrada'));
        document.getElementById('mensajeSeccionRenombradaTexto').textContent =
          `Sección ${nuevoNombre} renombrada con éxito`;
        modal.show();
        setTimeout(() => modal.hide(), 1200);
      });
    });
  });

  // VALIDAR SELECCIÓN DE ARCHIVOS ANTES DE ENVIAR
  document.querySelectorAll('form[id^="formUpload"]').forEach(form => {
    const id_seccion = form.querySelector('[name="id_seccion"]').value;
    const input = form.querySelector('input[type="file"]');
    const preview = form.querySelector(`#preview${id_seccion}`);

    // Subir contenido
    form.addEventListener('submit', e => {
      e.preventDefault();
      if(!input.files.length) {
        new bootstrap.Modal(document.getElementById('modalErrorSubir')).show();
        return;
      }

      const formData = new FormData(form);
      formData.append('agregar_imagen', 1);

      fetch('subir_contenido_mult.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            new bootstrap.Modal(document.getElementById('modalExitoSubir')).show();
            setTimeout(() => bootstrap.Modal.getInstance(document.getElementById('modalExitoSubir')).hide(), 1200);

            // Limpiar preview e input
            preview.innerHTML = '';
            input.value = '';

            // SOLO reemplazar las imágenes dentro de la d-flex del formulario
            const formEliminar = document.querySelector(`#formEliminar${data.id_seccion}`);
            if (formEliminar) {
                const dFlex = formEliminar.querySelector('.d-flex');
                if (dFlex) {
                    dFlex.innerHTML = data.html_imagenes; // reemplaza solo las imágenes
                    inicializarImgContainer(dFlex);
                }
            }
          } else {
            new bootstrap.Modal(document.getElementById('modalErrorSubir')).show();
          }
        }).catch(err => console.error(err));
    });
  });
  function inicializarImgContainer(contenedor) {
    contenedor.querySelectorAll('.img-container').forEach(container => {
      const media = container.querySelector('img, video');
      if (!media) return;

      // Click en la imagen/video → pantalla completa
      media.addEventListener('click', () => {
        const src = media.src;
        const ext = src.split('.').pop().toLowerCase();
        mostrarEnGrande(src, ext);
      });

      // Botón de menú
      const btnMenu = container.querySelector('.btn-menu');
      const menu = container.querySelector('.menu-opciones');
      if(btnMenu && menu) {
        btnMenu.addEventListener('click', e => {
          e.stopPropagation();
          document.querySelectorAll('.menu-opciones').forEach(m => {
            if (m !== menu) m.classList.add('d-none');
          });
          menu.classList.toggle('d-none');
        });
      }

      // Botón ver en grande dentro del menú
      const btnVer = container.querySelector('.btn-ver-grande');
      if (btnVer) {
        btnVer.addEventListener('click', () => {
          const src = media.src;
          const ext = src.split('.').pop().toLowerCase();
          mostrarEnGrande(src, ext);
          menu.classList.add('d-none');
        });
      }
    });
  }
  // Cuando el usuario pulsa "Eliminar seleccionados" dentro del form
  const modalEliminarContenido = new bootstrap.Modal(document.getElementById('modalConfirmarEliminarContenido'));
  const modalMensajeFinal = new bootstrap.Modal(document.getElementById('modalMensaje'));
  let archivosSeleccionados = [];

  // Capturamos el submit de cualquier form con btnEliminar
  document.querySelectorAll('.btnEliminar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault(); // ⚡ Evita que el form se envíe de forma tradicional

      const form = btn.closest('form');
      const checkboxes = form.querySelectorAll('input[name="seleccion[]"]:checked');

      if (checkboxes.length === 0) {
        new bootstrap.Modal(document.getElementById("modalErrorEliminar")).show();
        return;
      }

      // Guardamos los IDs seleccionados
      archivosSeleccionados = Array.from(checkboxes).map(cb => cb.value);

      // Mostrar preview
      const preview = document.getElementById("previewEliminar");
      preview.innerHTML = "";
      checkboxes.forEach(cb => {
        const ruta = cb.dataset.ruta;
        let ext = ruta.split('.').pop().toLowerCase();
        let elem;

        if (ruta.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
          elem = document.createElement("img");
          elem.src = ruta;
        } else {
          elem = document.createElement("video");
          elem.src = ruta;
          elem.controls = true;
          elem.muted = true;
          elem.loop = true;
        }

        elem.style.width = "120px";
        elem.style.maxHeight = "90px";
        elem.classList.add("img-thumbnail");
        elem.style.cursor = "pointer";
        elem.addEventListener('click', () => mostrarEnGrande(ruta, ext));

        preview.appendChild(elem);
      });

      modalEliminarContenido.show();

      // Guardamos el form actual para usarlo al confirmar
      modalEliminarContenido.form = form;
    });
  });

  // Botón confirmar eliminación
  document.getElementById('btnEliminarContenido').addEventListener('click', () => {
    if (!archivosSeleccionados.length) return;

    const form = modalEliminarContenido.form;
    const formData = new FormData(form);

    archivosSeleccionados.forEach(id => formData.append('seleccion[]', id));

    fetch('eliminar_contenido_mult.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        modalEliminarContenido.hide();

        // Mostrar modal de éxito
        document.getElementById('mensajeTexto').textContent = "Archivos eliminados correctamente.";
        modalMensajeFinal.show();

        // Cerrar automáticamente después de 1.2 segundos
        setTimeout(() => {
            modalMensajeFinal.hide();
        }, 1200);

        // Eliminamos visualmente los contenedores
        archivosSeleccionados.forEach(id => {
            const cont = form.querySelector(`.img-container input[value="${id}"]`)?.closest('.img-container');
            if (cont) cont.remove();
        });

        archivosSeleccionados = [];

      }
    })
    .catch(e => console.log("Error al eliminar archivos:", e));
  });
  
});


function confirmarLogout() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
  modalLogout.show();
}
