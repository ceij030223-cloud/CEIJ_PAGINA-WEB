document.addEventListener("DOMContentLoaded", () => {
  const secciones = JSON.parse(document.getElementById('secciones-data').textContent);
  const galeria = document.getElementById("galeria");

  function mostrarSeccion(id) {
    galeria.innerHTML = ""; // limpiar contenido previo
    const sec = secciones[id];

    if (!sec) {
      galeria.innerHTML = '<div class="col-12 text-center">⚠️ Sección no encontrada.</div>';
      return;
    }

    if (sec.imagenes.length === 0) {
      galeria.innerHTML = `
      <div class="col-12">
        <div class="alert alert-warning text-center">
          ⚠️ No existe contenido multimedia en la sección. Por favor, le recomendamos navegar en otra.
        </div>
      </div>
      `;
      return;
    }

    // Si hay imágenes/videos
    sec.imagenes.forEach(img => {
      const ruta = img.ruta;
      const ext = ruta.split('.').pop().toLowerCase();
      const tipo = ['mp4', 'mov', 'webm'].includes(ext) ? 'video' : 'imagen';
      const col = document.createElement("div");
      col.className = "col-md-4 col-sm-6 seccion" + id;
      col.innerHTML = `
      <div class="card shadow-sm">
        ${tipo === 'video' ? `
        <video class="card-img-top img-fluid" muted autoplay loop playsinline
          data-bs-toggle="modal" data-bs-target="#imageModal"
          data-bs-src="${ruta}">
          <source src="${ruta}" type="video/mp4">
        </video>
        ` : `
        <img src="${ruta}" class="card-img-top img-fluid" 
          data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-img="${ruta}">
        `}
      </div>
      `;
      galeria.appendChild(col);
    });
  }

  // --- Función para manejar hash ---
  function hashChange() {
    const hash = window.location.hash.replace("#seccion", "");
    if (hash) {
      mostrarSeccion(hash);
    } else {
      galeria.innerHTML = '<div class="col-12 text-center">⚠️ Selecciona una sección.</div>';
    }
  }

  // --- Escuchamos cambios de hash ---
  window.addEventListener("hashchange", hashChange);

  // --- Ejecutamos al cargar la página ---
  hashChange();

  // --- Modal ---
  const imageModal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImg");
  const modalVideo = document.getElementById("modalVideo");

  imageModal.addEventListener("show.bs.modal", (event) => {
    const trigger = event.relatedTarget;
    const imgSrc = trigger?.getAttribute("data-bs-img");
    const videoSrc = trigger?.getAttribute("data-bs-src");

    if (videoSrc) {
      modalImg.style.display = "none";
      modalVideo.style.display = "block";
      modalVideo.querySelector("source").src = videoSrc;
      modalVideo.load();
      modalVideo.play();
    } else {
      modalVideo.pause();
      modalVideo.style.display = "none";
      modalImg.style.display = "block";
      modalImg.src = imgSrc;
    }
  });

  imageModal.addEventListener("hidden.bs.modal", () => {
    modalVideo.pause();
  });
});
// --- Cerrar offcanvas automáticamente cuando se cambia de sección dentro de la misma página ---
function cerrarOffcanvasSiExiste() {
  const offcanvasEl = document.querySelector(".offcanvas.show");
  if (offcanvasEl) {
    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
    offcanvas.hide(); // se cierra suavemente como si lo hubieras tocado
  }
}

// Cuando se navega entre secciones (hashchange = #seccionX)
window.addEventListener("hashchange", () => {
  cerrarOffcanvasSiExiste();
});

// También por si el usuario hace clic directo en el link dentro del menú mobile
document.querySelectorAll('.dropdown-item[href^="galerias.php#seccion"]').forEach(link => {
  link.addEventListener("click", () => {
    setTimeout(cerrarOffcanvasSiExiste, 200); // pequeña espera para que cambie el hash antes de cerrarlo
  });
});
// Script de confirmación de cierre de sesión
function confirmarLogout() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
  modalLogout.show();
}