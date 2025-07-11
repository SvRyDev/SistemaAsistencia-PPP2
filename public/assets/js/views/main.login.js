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
      url: base_url + "/login/auth", // <-- Cambia esto a tu endpoint real
      method: "POST",
      data: { usuario, clave },
      dataType: "json",
      beforeSend: function () {
        Swal.fire({
          title: "Verificando...",
          text: "Espere un momento",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function (res) {
        if (res.success) {
          Swal.fire({
            icon: "success",
            title: "¡Bienvenido!",
            text: res.message,
            showConfirmButton: false,
            timer: 2000,
          });

          setTimeout(() => {
            window.location.href = res.redirect || "dashboard.php"; // <- redirige
          }, 2000);
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
