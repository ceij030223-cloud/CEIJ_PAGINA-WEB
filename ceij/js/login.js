document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.querySelector('.contenedor');
    const switchFormLinks = document.querySelectorAll('.switch-form');
    const recuperacionLink = document.querySelector('.recuperacion-link');
    const regresarbt = document.getElementById('bt-regresar');

    // Cambios de sección
    switchFormLinks.forEach(link => {
        link.addEventListener('click', () => {
            if(link.textContent.trim() === 'Registrarse'){
                contenedor.classList.remove('activo-login');
                contenedor.classList.add('activo-registro');
            }
            else if (link.textContent.trim() === 'Iniciar sesión'){
                contenedor.classList.remove('activo-registro');
                contenedor.classList.add('activo-login');
            }
        });
    });

    // Transición a la recuperación de contraseña
    if (recuperacionLink) {
        recuperacionLink.addEventListener('click', () => {
            contenedor.classList.remove('activo-login', 'activo-registro');
            contenedor.classList.add('activo-recuperacion');
        });
    }

    // Botón regresar
    if (regresarbt) {
        regresarbt.addEventListener('click', (e) => {
            e.preventDefault();
            contenedor.classList.remove('activo-recuperacion');
            contenedor.classList.add('activo-login');
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
  const contenedor = document.querySelector('.contenedor');
  const loginForm = document.querySelector('.form-contenedor.login');
  const registroForm = document.querySelector('.form-contenedor.registro');
  const recuperacionForm = document.querySelector('.form-contenedor.recuperacionc');

  function ajustarAlturaContenedor() {
    const anchoPantalla = window.innerWidth;
    let altura = 0;

    if (anchoPantalla <= 1024) {
      if (contenedor.classList.contains('activo-login')) {
        altura = loginForm.scrollHeight + 20;
      } else if (contenedor.classList.contains('activo-recuperacion')) {
        altura = recuperacionForm.scrollHeight + 20;
      } else if (contenedor.classList.contains('activo-registro')) {
        altura = registroForm.scrollHeight + 20;
      }

      contenedor.style.height = `${altura}px`;
    } else {
      contenedor.style.height = "auto";
    }
  }

  const observer = new MutationObserver(ajustarAlturaContenedor);
  observer.observe(contenedor, { attributes: true, attributeFilter: ['class'] });

  window.addEventListener('load', ajustarAlturaContenedor);
  window.addEventListener('resize', ajustarAlturaContenedor);
});