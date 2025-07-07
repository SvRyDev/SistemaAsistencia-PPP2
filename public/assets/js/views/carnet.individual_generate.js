$(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////////

  cargarConfig();

  function cargarConfig() {
    $.post(base_url + "/setting/getConfig", function (response) {
      const $anio = $("#anio_individual");
      $anio.val(response.setting.academic_year);
    });
  }

  $(document).ready(function () {
  
  // Asignar el evento load del iframe UNA SOLA VEZ
// Al cargar el iframe (cuando el PDF está listo)
$("#preview-carnets").on("load", function () {
  ajustarAlturaIframe();
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

    var dni_estudiante = $("#dni_estudiante").val();


    if (!dni_estudiante) {
      Swal.fire({
        icon: "warning",
        title: "Campos requeridos",
        text: "Ingrese el Dni a Consultar",
        confirmButtonText: "Aceptar",
      });
      return;
    }

   
   // Intentar limpiar el contenido del iframe
   var iframe = document.getElementById("preview-carnets");
   if (iframe && iframe.contentWindow) {
     try {
       iframe.contentWindow.document.open();
       iframe.contentWindow.document.write("");
       iframe.contentWindow.document.close();
     } catch (err) {
       // No se puede limpiar por CORS, fallback a about:blank
       iframe.src = "about:blank";
     }
   } else {
     iframe.src = "about:blank";
   }

    // Crear formulario dinámico para enviar al iframe
    const form = $("<form>", {
      method: "POST",
      action: base_url + "/carnet/previewCarnetIndividual",
      target: "preview-carnets",
    });


    form.append($("<input>", {
      type: "hidden",
      name: "dni_estudiante",
      value: dni_estudiante,
    }));

    $("body").append(form);
    form.submit();
    form.remove();
  });



    
    
    $("#btn-export-pdf").on("click", function (e) {
      e.preventDefault();

      var dni_estudiante = $("#dni_estudiante").val();

      if (!dni_estudiante) {
        Swal.fire({
          icon: "warning",
          title: "Campos requeridos",
          text: "Ingrese el Dni a Consultar",
          confirmButtonText: "Aceptar",
        });
        return;
      }

      // Abrir PDF en nueva pestaña, usando query params o mejor: POST con form
      const form = $("<form>", {
        method: "POST",
        action: base_url + "/carnet/generateCarnetIndividual",
        target: "_blank",
      });


      form.append($("<input>", {
        type: "hidden",
        name: "dni_estudiante",
        value: dni_estudiante,
      }));
  

      $("body").append(form);
      form.submit();
      form.remove();
    });
  });



  $(window).on("resize", function () {
    ajustarAlturaIframe()
   
  });

  function ajustarAlturaIframe(){
    try {
      var iframe = document.getElementById("preview-carnets");
      var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
      var newHeight = $(iframeDoc).height();
      $("#preview-carnets").height(newHeight);
    } catch (e) {
      console.warn("No se pudo ajustar la altura del iframe:", e);
    }
  }
  /////////////////////////////////////////////////////////////////////////////////////////////
});
