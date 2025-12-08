document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formRecuperacion");
  const alertageneral = document.querySelector(".alertageneral");
  const btnConfirmar = document.querySelector(".btn-confirmar");
  const btnCancelar = document.querySelector(".btn-cancelar");
  const btnModificar = document.getElementById("btnModificar");
  // MOSTRAR ALERTA CUANDO LE DAN "MODIFICAR"
  btnModificar.addEventListener("click", () => {
      alertageneral.style.display = "flex";
  });

  btnConfirmar.addEventListener("click", async () => {
    alertageneral.style.display = "none";

    const formData = new FormData(form);
    formData.append("token", form.querySelector('input[name="token"]').value);
    try {
      const res = await fetch("../html/restablecer.php", {
        method: "POST",
        body: formData
      });
      const data = await res.text();
      
      if (data.trim() === "success") {
        alert("Contraseña actualizada con éxito");
        window.location.href = "login.html";
      } else if (data.trim() === "token_invalido") {
        alert("Token inválido o inexistente. Vuelve a solicitar otro token para el restablecimiento de la contraseña.");
      } else if (data.trim() === "formato_invalido") {
        alert("La contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un carácter especial.");
      } else if (data.trim() === "no_coinciden") {
        alert("Las contraseñas no coinciden.");
      } else {
        alert("Error al modificar la contraseña. Intenta más tarde.");
      }
    } catch (error) {
      alert("Error de conexión con el servidor.");
    }
  });

  btnCancelar.addEventListener("click", () => {
    alertageneral.style.display = "none";
  });
});
