$(document).ready(function () {
  let datosDesdePrincipal = {};

  window.addEventListener("message", function (event) {
    if (typeof event.data === "object" && event.data.entry_time) {
      datosDesdePrincipal = event.data;

      console.log("Recibido desde ventana principal:", datosDesdePrincipal);

      // Puedes mostrarlo en el HTML si quieres:
      $("#horaEntrada").text("Entrada: " + datosDesdePrincipal.entry_time);
      $("#horaSalida").text("Salida: " + datosDesdePrincipal.exit_time);
      $("#tolerancia").text("Tolerancia: " + datosDesdePrincipal.tolerance + " min");
    }
  });




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
      setTimeout(() => {
        $("#resultadoBusqueda span").html("");
      }, 1500);
      return;
    }

    $.ajax({
      url: base_url + "/attendance/registerAttendance",
      method: "POST",
      data: { codigo: codigo },
      dataType: "json",
      beforeSend: function () {
        $("#mensajeRespuesta").html(
          '<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>'
        );
      },
      success: function (response) {
        console.log(response);

        if (response.status === "success") {
          let mensaje = "<b>ASISTENCIA REGISTRADA</b><br>";
          mensaje += "Estado: <b>" + response.estado.nombre_estado.toUpperCase() + "</b><br>";
          mensaje += "Hora actual: " + response.hora_actual + "<br>";
          mensaje += "Límite puntualidad: " + response.limite_puntualidad;

          $("#mensajeRespuesta").html(mensaje);

          // Mostrar datos del estudiante
          $("#nombre").html("Nombre: " + response.student.nombres);
          $("#apellido").html("Apellido: " + response.student.apellidos);
          $("#codigo").html("Código: " + response.student.codigo);

          if (window.opener) {
            window.opener.postMessage(response, "*");
          }

        } else if (response.status === "warning") {
          // Estudiante ya tiene asistencia registrada
          let mensaje = "<b>ASISTENCIA YA REGISTRADA</b><br>";
         mensaje += "Hora registrada: " + response.hora_registrada;

          $("#mensajeRespuesta").html(mensaje);

          // Mostrar datos del estudiante
          $("#nombre").html("Nombre: " + response.student.nombres);
          $("#apellido").html("Apellido: " + response.student.apellidos);
          $("#codigo").html("Código: " + response.student.codigo);

        } else {
          $("#mensajeRespuesta").html("<b>Error:</b> " + response.message);
        }
      },

      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        $("#mensajeRespuesta").html("Error en la búsqueda del código.");
        alert("Error al buscar el código. Intenta nuevamente.");
      },
      complete: function () {
        $input.val("").focus();
        setTimeout(() => {
          $("#resultadoBusqueda span").html("");
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
