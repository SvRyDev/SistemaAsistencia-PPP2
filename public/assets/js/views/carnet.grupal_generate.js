$(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////////

  cargarGrados();
  cargarSecciones();
  cargarConfig();

  //Funciones de carga
  function cargarGrados() {
    $.get(base_url + "/grade/getAll", function (response) {
      const $grado = $("#grado_grupo");
      $grado.empty().append('<option value="">-- Seleccione --</option>');

      if (response.status === "success" && Array.isArray(response.grades)) {
        response.grades.forEach((g) => {
          $grado.append(
            `<option value="${g.id_grado}">${g.nombre_completo}</option>`
          );
        });
      }
    });
  }

  function cargarSecciones() {
    $.get(base_url + "/section/getAll", function (response) {
      const $seccion = $("#seccion_grupo");
      $seccion.empty().append('<option value="">-- Seleccione --</option>');

      if (response.status === "success" && Array.isArray(response.sections)) {
        response.sections.forEach((s) => {
          $seccion.append(
            `<option value="${s.id_seccion}">${s.nombre_seccion}</option>`
          );
        });
      }
    });
  }

  function cargarConfig() {
    $.post(base_url + "/setting/getConfig", function (response) {
      const $anio = $("#anio");
      $anio.val(response.setting.academic_year);
    });
  }

  $(document).ready(function () {
  
  // Asignar el evento load del iframe UNA SOLA VEZ
// Al cargar el iframe (cuando el PDF está listo)
$("#preview-carnets").on("load", function () {
  // Mostrar alerta
  $("#carnet-alert").show();

  // Habilitar el botón de exportar
  $("#btn-export-pdf").removeClass("disabled").css({
    cursor: "pointer",
    "pointer-events": "auto",
    opacity: "1",
  });
});


  $("#btn-generate-carnets").on("click", function (e) {
    e.preventDefault();

    var grado = $("#grado_grupo").val();
    var seccion = $("#seccion_grupo").val();

    if (!grado || !seccion) {
      Swal.fire({
        icon: "warning",
        title: "Campos requeridos",
        text: "Seleccione grado y sección",
        confirmButtonText: "Aceptar",
      });
      return;
    }


    // Crear formulario dinámico para enviar al iframe
    const form = $("<form>", {
      method: "POST",
      action: base_url + "/carnet/generateCarnetGrupal",
      target: "preview-carnets",
    });

    form.append($("<input>", {
      type: "hidden",
      name: "grado_grupo",
      value: grado,
    }));

    form.append($("<input>", {
      type: "hidden",
      name: "seccion_grupo",
      value: seccion,
    }));

    $("body").append(form);
    form.submit();
    form.remove();
  });



    
    
    $("#btn-export-pdf").on("click", function (e) {
      e.preventDefault();

      var grado = $("#grado_grupo").val();
      var seccion = $("#seccion_grupo").val();

      if (!grado || !seccion) {
        Swal.fire({
          icon: "warning",
          title: "Campos requeridos",
          text: "Seleccione grado y sección",
          confirmButtonText: "Aceptar",
        });
        return;
      }

      // Abrir PDF en nueva pestaña, usando query params o mejor: POST con form
      const form = $("<form>", {
        method: "POST",
        action: base_url + "/carnet/generateCarnetGrupal",
        target: "_blank",
      });

      form.append(
        $("<input>", {
          type: "hidden",
          name: "grado_grupo",
          value: grado,
        })
      );

      form.append(
        $("<input>", {
          type: "hidden",
          name: "seccion_grupo",
          value: seccion,
        })
      );

      $("body").append(form);
      form.submit();
      form.remove();
    });
  });

  /////////////////////////////////////////////////////////////////////////////////////////////
});
