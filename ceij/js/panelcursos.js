// Sistema de modificación - Versión corregida
document.addEventListener('DOMContentLoaded', function() {
    function toggleEditMode(userId, enable) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (!row) return;
        
        // Usar clases en lugar de IDs
        const sucursalSelect = row.querySelector('select');
        const cursoSelect = row.querySelectorAll('select')[1];
        const fechaInicio = row.querySelector('input[type="date"]');
        const fechaFin = row.querySelectorAll('input[type="date"]')[1];
        const estadoSelect = row.querySelector('.estado-select');
        const certificadoInput = row.querySelector('.certificado-input');
        const pdfPreview = row.querySelector('.pdf-preview');
        
        const campos = [sucursalSelect, cursoSelect, fechaInicio, fechaFin, estadoSelect, certificadoInput];
        const btnModificar = row.querySelector('.btn-modificar');
        const botonGuardar = row.querySelector('.boton-guardar');
        
        campos.forEach(campo => {
            if (campo) {
                campo.disabled = !enable;
                if (enable) {
                    campo.style.backgroundColor = '#f8f9fa';
                    campo.style.borderColor = '#ffa200ff';
                } else {
                    campo.style.backgroundColor = '#fff';
                    campo.style.borderColor = '#ccc';
                }
            }
        });
        










        // Manejar eventos del PDF para esta fila específica
        if (enable && certificadoInput) {
            certificadoInput.addEventListener('change', function() {
                const file = this.files[0];
                const userId = row.getAttribute('data-user-id');
                const pdfPreview = document.getElementById(`pdfPreview_${userId}`);
                if (file && file.type === 'application/pdf') {
                    const pdfURL = URL.createObjectURL(file);
                    pdfPreview.textContent = file.name;
                    pdfPreview.dataset.pdfUrl = pdfURL;
                    
                    // Evento para abrir PDF
                    pdfPreview.onclick = function() {
                        if (pdfURL) {
                            const modal = document.getElementById('pdfModal');
                            const pdfFrame = document.getElementById('pdfFrame');
                            pdfFrame.src = pdfURL;
                            modal.style.display = 'flex';
                        }
                    };
                } else {
                    pdfPreview.textContent = 'Archivo no válido';
                    pdfPreview.onclick = null;
                }
            });
        }
        
        if (enable) {
            if (btnModificar) btnModificar.style.display = 'none';
            if (botonGuardar) botonGuardar.style.display = 'block';
        } else {
            if (btnModificar) btnModificar.style.display = 'block';
            if (botonGuardar) botonGuardar.style.display = 'none';
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
        
        // Seleccionar elementos por su posición en la tabla
      const cursoSelect = row.querySelector('.curso-select');
      const fechaInicio = row.querySelector('.fecha-inicio');
      const fechaFin = row.querySelector('.fecha-fin');
      const estadoSelect = row.querySelector('.estado-select');
      const certificadoInput = row.querySelector('.certificado-input');
      const sucursalSelect = row.querySelector('.sucursal-select');   

        const sucursal = sucursalSelect ? sucursalSelect.value : '';
        const curso = cursoSelect ? cursoSelect.value : '';
        const fechaInicioVal = fechaInicio ? fechaInicio.value : '';
        const fechaFinVal = fechaFin ? fechaFin.value : '';
        const estado = estadoSelect ? estadoSelect.value : '';
        const certificado = certificadoInput ? certificadoInput.files[0] : null;
        
        // Validar datos requeridos
        if (!curso || !fechaInicioVal || !fechaFinVal || !estado) {
            const modalCampos = new bootstrap.Modal(document.getElementById('modalCamposRequeridos'));
            modalCampos.show();
            return;
        }
         // Validar que fecha final sea mayor o igual a fecha inicial
    if (new Date(fechaFinVal) < new Date(fechaInicioVal)) {
        const modalFecha = new bootstrap.Modal(document.getElementById('modalFechaIncorrecta'));
        modalFecha.show();
        return;
    }
        
        // Crear FormData para enviar datos
        const formData = new FormData();
        formData.append('usuario_id', userId);
        formData.append('curso', curso);
        formData.append('fecha_inicio', fechaInicioVal);
        formData.append('fecha_finalizacion', fechaFinVal);
        formData.append('estado', estado);
        formData.append('sucursal', sucursal);
        if (certificado) {
            formData.append('certificado', certificado);
        }
        
        // Enviar datos al servidor
        fetch('guardarprogresoalumnos.php', {
            method: 'POST',
            body: formData
        })

        .then(response => response.json())

        .then(data => {
            if (data.success) {
                // Cambiar mensaje dinámicamente (opcional)
                document.getElementById('mensajeDatosGuardados').textContent = "Datos guardados correctamente";

                // Mostrar el modal
                const modalGuardado = new bootstrap.Modal(document.getElementById('modalDatosGuardados'));
                modalGuardado.show();

                // Cerrar automáticamente después de 1.2 segundos y recargar
                setTimeout(() => {
                    modalGuardado.hide();
                    // Salir del modo edición si usas toggleEditMode
                    toggleEditMode(userId, false);
                    location.reload();
                }, 1200);
            } else {
                // Mostrar modal de error con mensaje del servidor
                document.getElementById('mensajeErrorGuardado').textContent = 'Error al guardar: ' + data.message;
                const modalError = new bootstrap.Modal(document.getElementById('modalErrorGuardado'));
                modalError.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar modal de error genérico
            document.getElementById('mensajeErrorGuardado').textContent = 'Error al guardar los datos';
            const modalError = new bootstrap.Modal(document.getElementById('modalErrorGuardado'));
            modalError.show();
        });
    }
});


// Cerrar modal cuando se hace clic en la X
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('pdfModal').style.display = 'none';
    document.getElementById('pdfFrame').src = '';
});

// Cerrar modal cuando se hace clic fuera del contenido
document.getElementById('pdfModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
        document.getElementById('pdfFrame').src = '';
    }
});

// Mostrar PDFs existentes al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    
    // Agregar eventos para los PDFs existentes
    document.querySelectorAll('.pdf-preview span').forEach(preview => {
        const pdfName = preview.textContent.trim();
        if (pdfName) {
            preview.onclick = function() {
    const pdfURL = 'certificados/' + pdfName;
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    pdfFrame.src = pdfURL;
    modal.style.display = 'flex';
            };

        }
    });
});

// Abrir modal de historial
document.querySelectorAll('.ver-historial').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const userId = this.getAttribute('data-user-id');
        
        fetch(`historial.php?usuario_id=${userId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('contenidoHistorial').innerHTML = html;
                $('#historialModal').modal('show');
            });
    });
});
 

$(document).ready(function() {
    // Función para filtrar los resultados
    function filtrarUsuarios() {
        const searchTerm = $('#search').val().toLowerCase();
        const sucursalSeleccionada = $('#exampleFormControlSelect1').val().toLowerCase();
        
        $('.widget-26 .card').each(function() {
            const $card = $(this);
            const nombre = $card.find('.widget-26-job-title a').text().toLowerCase();
            const apellido = $card.find('.employer-name').text().toLowerCase();
            const sucursal = $card.find('.widget-26-job-info p').text().toLowerCase();
            
            const coincideTexto = nombre.includes(searchTerm) || 
                                apellido.includes(searchTerm) || 
                                sucursal.includes(searchTerm);
            
            const coincideSucursal = sucursalSeleccionada === '' || 
                                   sucursal.includes(sucursalSeleccionada);
            
            if (coincideTexto && coincideSucursal) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }
    
    // Evento para el campo de búsqueda
    $('#search').on('input', function() {
        filtrarUsuarios();
    });
    
    // Evento para el selector de sucursal
    $('#exampleFormControlSelect1').on('change', function() {
        filtrarUsuarios();
    });
    
    // Prevenir el envío del formulario
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        filtrarUsuarios();
    });
});




$(document).ready(function() {
    // Función para filtrar los resultados
    function filtrarUsuarios() {
        const searchTerm = $('#search').val().toLowerCase();
        const sucursalSeleccionada = $('#exampleFormControlSelect1').val().toLowerCase();
        
        // Filtrar las filas de la tabla (tr)
        $('table.widget-26 tbody tr').each(function() {
            const $fila = $(this);
            const nombre = $fila.find('.widget-26-job-title a').text().toLowerCase();
            const apellido = $fila.find('.employer-name').text().toLowerCase();
            const sucursal = $fila.find('.sucursal-select option:selected').text().toLowerCase();
            
            const coincideTexto = nombre.includes(searchTerm) || 
                                apellido.includes(searchTerm);
            
            const coincideSucursal = sucursalSeleccionada === '' || 
                                   sucursal.includes(sucursalSeleccionada);
            
            if (coincideTexto && coincideSucursal) {
                $fila.show();
            } else {
                $fila.hide();
            }
        });
    }
    
    // Evento para el campo de búsqueda
    $('#search').on('input', function() {
        filtrarUsuarios();
    });
    
    // Evento para el selector de sucursal
    $('#exampleFormControlSelect1').on('change', function() {
        filtrarUsuarios();
    });
    
    // Prevenir el envío del formulario
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        filtrarUsuarios();
    });
});