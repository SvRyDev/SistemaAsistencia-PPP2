$("#btnUpdateConfigAccount").click(function () {
    const currentPass = $("#user-password-current").val().trim();
    const newPass = $("#user-password-new").val().trim();
    const confirmPass = $("#user-password-confirm").val().trim();
  
    if (!currentPass || !newPass || !confirmPass) {
      Swal.fire("Campos vacíos", "Por favor completa todos los campos.", "warning");
      return;
    }
  
    if (newPass.length < 6) {
      Swal.fire("Contraseña insegura", "La nueva contraseña debe tener al menos 6 caracteres.", "warning");
      return;
    }
  
    if (newPass !== confirmPass) {
      Swal.fire("Contraseñas no coinciden", "La confirmación no coincide con la nueva contraseña.", "warning");
      return;
    }
  
    Swal.fire({
      title: "¿Actualizar contraseña?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, actualizar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/account/updatePassword", // Asegúrate de que esta ruta sea la correcta
          method: "POST",
          dataType: "json",
          data: {
            current_password: currentPass,
            new_password: newPass,
            confirm_password: confirmPass, // <- ESTE es el que faltaba
          },
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          success: function (response) {
            if (response.status === "success") {
              Swal.fire("Actualizado", "La contraseña fue cambiada correctamente.", "success");
              $("#user-password-current, #user-password-new, #user-password-confirm").val("");
            } else {
              Swal.fire("Error", response.message || "No se pudo cambiar la contraseña.", "error");
            }
          },
          error: function () {
            Swal.fire("Error", "Hubo un problema con la solicitud.", "error");
          }
        });
      }
    });
  });
  