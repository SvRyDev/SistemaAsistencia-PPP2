$(document).ready(function () {
  const $input = $("#codigoEstudiante");
  const $warning = $("#focusWarning");

  // Enviar datos a la ventana principal
  $("#formulario").on("submit", function (e) {
    e.preventDefault();
    const codigo = $input.val().trim();

    // Validar que tenga exactamente 11 caracteres
    if (codigo.length !== 11) {
      $("#mensajeRespuesta").html(
        "El código debe tener exactamente 11 caracteres."
      );
      // Limpiar la información después de 0.5 segundos
      setTimeout(() => {
        $("#resultadoBusqueda span").html("");
      }, 1500);
      return;
    }

    $.ajax({
      url: base_url + "/attendance/registerAttendance",
      method: "POST",
      data: { codigo: codigo },
      beforeSend: function () {
        $("#mensajeRespuesta").html(
          '<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>'
        );
      },
      success: function (response) {
        if (response.status === "success") {
          $("#mensajeRespuesta").html("<b>ESTUDIANTE REGISTRADO CORRECTAMENTE</b>");
          //$("#resultadoBusqueda span").addClass("text-success");
          // Mostrar datos del estudiante
          $("#nombre").html('Nombre: ' + response.student.nombres);
          $("#apellido").html('Estudiante: ' + response.student.apellidos);
          $("#codigo").html('Código: ' + response.student.codigo);

          // Enviar a la ventana principal
          if (window.opener) {
            window.opener.postMessage(response.student, "*");
            $input.focus();
          }
        } else {
          $("#mensajeRespuesta").html(
            "Error al registrar el código: " + response.message
          );
          //$("#resultadoBusqueda span").removeClass("text-success").addClass("text-danger");
        }
      },
      error: function (xhr, status, error) {
        $("#mensajeRespuesta").html("Error en la búsqueda del código:", error);
        alert("Error al buscar el código. Intenta nuevamente.");
      },
      complete: function () {
        $input.val("");
        // Limpiar la información después de 0.5 segundos

        setTimeout(() => {
          $("#resultadoBusqueda span").html("");
          //$("#resultadoBusqueda span").removeClass("text-success").removeClass("text-danger");
        }, 1500);
      },
    });
  });

  // Mostrar advertencia si se pierde el foco
  $(window).on("blur", function () {
    $warning.show();
  });

  // Ocultar advertencia y enfocar input si se recupera el foco
  $(window).on("focus", function () {
    $warning.hide();
    $input.focus();
  });

  // Click sobre la advertencia vuelve a enfocar el input
  $warning.on("click", function () {
    window.focus();
    $input.focus();
    $warning.hide();
  });

  // Enfocar el input al inicio
  $input.focus();

  // Click en cualquier parte fuera del input o botón enfoca el input
  $(document).on("click", function (e) {
    if (
      !$(e.target).closest("#nombre").length &&
      !$(e.target).closest("button").length
    ) {
      $input.focus();
    }
  });
});
