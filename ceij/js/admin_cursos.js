const modalEditar = document.getElementById('modalEditarCurso');
modalEditar.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;

    document.getElementById('edit-id').value = button.dataset.id;
    document.getElementById('edit-titulo').value = button.dataset.titulo;
    document.getElementById('edit-descripcion').value = button.dataset.descripcion;
    document.getElementById('edit-duracion').value = button.dataset.duracion;
    document.getElementById('edit-alumnos').value = button.dataset.alumnos;
    document.getElementById('edit-fechainicio').value = button.dataset.fechainicio;
    document.getElementById('edit-fechafin').value = button.dataset.fechafin;

    // Convertir horario de "hh:mm am/pm - hh:mm am/pm" a inputs tipo time
    let horario = button.dataset.horario.split(" - ");
    if(horario.length === 2){
        function parse12to24(h){
            let [time, ampm] = h.split(" ");
            let [hh, mm] = time.split(":");
            hh = parseInt(hh);
            if(ampm.toLowerCase() === "pm" && hh < 12) hh+=12;
            if(ampm.toLowerCase() === "am" && hh === 12) hh=0;
            return hh.toString().padStart(2,'0')+":"+mm;
        }
        document.getElementById('edit-hora_inicio').value = parse12to24(horario[0]);
        document.getElementById('edit-hora_fin').value = parse12to24(horario[1]);
    }

    document.getElementById('edit-dias').value = button.dataset.dias;

    // Modalidad
    let modalidades = document.getElementsByName('modalidad');
    modalidades.forEach(radio => radio.checked = radio.value === button.dataset.modalidad);

    // Sucursal
    let sucursales = document.getElementsByName('sucursal');
    sucursales.forEach(radio => radio.checked = radio.value === button.dataset.sucursal);

    document.getElementById('edit-costototal').value = button.dataset.costototal;
    document.getElementById('edit-inscripcion').value = button.dataset.inscripcion;
    document.getElementById('edit-sesion').value = button.dataset.sesion;
    document.getElementById('preview-imagen').src = button.dataset.imagen;
});

function unirHorarioEditar(){
    let inicio = document.getElementById("edit-hora_inicio").value;
    let fin = document.getElementById("edit-hora_fin").value;
    function formato12h(hora){
        let [h, m] = hora.split(":");
        h = parseInt(h);
        let ampm = h>=12 ? "pm" : "am";
        h = h%12||12;
        return `${h}:${m} ${ampm}`;
    }
    document.getElementById("edit-horario").value = `${formato12h(inicio)} - ${formato12h(fin)}`;
}
function unirHorario() {
    let inicio = document.getElementById("hora_inicio").value;
    let fin = document.getElementById("hora_fin").value;
    function formato12h(hora) {
        let [h, m] = hora.split(":");
        h = parseInt(h);
        let ampm = h >= 12 ? "pm" : "am";
        h = h % 12 || 12;
        return `${h}:${m} ${ampm}`;
    }
    document.getElementById("horario").value = `${formato12h(inicio)} - ${formato12h(fin)}`;
}
// Función para manejar drag & drop + preview + fullscreen
function setupDropzone(inputId, previewId, zoneId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  const zone = document.getElementById(zoneId);

  zone.addEventListener('click', () => input.click());
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('hover'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('hover'));
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('hover');
    input.files = e.dataTransfer.files;
    showPreview();
  });

  input.addEventListener('change', showPreview);

  function showPreview() {
    if (input.files[0]) {
      preview.src = URL.createObjectURL(input.files[0]);
      preview.style.display = 'block';
      preview.style.maxWidth = '150px';
      preview.style.maxHeight = '150px';
      preview.style.cursor = 'pointer';
      preview.style.margin = '10px auto'; // centrado horizontal
      preview.style.display = 'block';
    }
  }

  // Click para fullscreen
  preview.addEventListener('click', () => {
    if (preview.requestFullscreen) preview.requestFullscreen();
    else if (preview.webkitRequestFullscreen) preview.webkitRequestFullscreen();
    else if (preview.msRequestFullscreen) preview.msRequestFullscreen();
  });
}

// Click en imagen actual para fullscreen
function setupFullscreen(imgId) {
  const img = document.getElementById(imgId);
  if (!img) return;
  img.style.cursor = 'pointer';
  img.addEventListener('click', () => {
    if (img.requestFullscreen) img.requestFullscreen();
    else if (img.webkitRequestFullscreen) img.webkitRequestFullscreen();
    else if (img.msRequestFullscreen) img.msRequestFullscreen();
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // Modal Agregar
  setupDropzone('imagen', 'preview-agregar', 'drop-agregar');

  // Modal Editar
  setupDropzone('edit-imagen', 'preview-nueva', 'drop-editar');
  setupFullscreen('preview-imagen'); // Imagen actual editable
});
// Activar tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
[...tooltipTriggerList].map(t => new bootstrap.Tooltip(t));
// Script de confirmación de cierre de sesión
function confirmarLogout() {
  var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));
  modalLogout.show();
}