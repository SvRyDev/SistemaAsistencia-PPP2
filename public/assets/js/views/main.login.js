$(function () {
  $("#loginForm").submit(function (e) {
    e.preventDefault();

const usuario = $("#usuario").val().trim();
const clave = $("#clave").val().trim();

if (usuario === "" || clave === "") {
  Swal.fire({
    icon: "warning",
    title: "Campos requeridos",
    text: "Por favor, complete ambos campos.",
    confirmButtonText: "Entendido",
  });
  return;
}

$.ajax({
  url: base_url + "/login/auth",
  method: "POST",
  data: { usuario, clave },
  dataType: "json",
  beforeSend: function () {
    Swal.fire({
      title: "Verificando...",
      html: `
        <div class="d-flex flex-column align-items-center">
          <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
          <span>Espere un momento</span>
        </div>
      `,
      allowOutsideClick: false,
      showConfirmButton: false,
      timer: 1000, // solo 1 segundo
    });
  },
  success: function (res) {
    if (res.status === "success") {
      Swal.fire({
        icon: "success",
        title: "¡Bienvenido!",
        text: res.message,
        showConfirmButton: false,
        timer: 1000,
      });

      setTimeout(() => {
        window.location.href = res.redirect || base_url + "/";
      }, 1000);
    } else {
      Swal.fire({
        icon: "error",
        title: "Error de acceso",
        text: res.message || "Usuario o contraseña incorrectos.",
      });
    }
  },
  error: function (xhr) {
    Swal.fire({
      icon: "error",
      title: "Error de servidor",
      text: "Ocurrió un problema al intentar ingresar. Intente más tarde.",
    });
  },
});


  });
});
