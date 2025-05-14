//MODULO aministration.js
(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/" + module_name;
  const form_module_id = "#form_students";
  /////////////////////////////////////////////////////////////////////////////////////////////

  // Example starter JavaScript for disabling form submissions if there are invalid fields

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });

  // Función para habilitar/deshabilitar el formulario según el checkbox
  function toggleFormState() {
    const form = document.getElementById("form_config");
    const inputs = form.querySelectorAll("input, select");
    const checkbox = document.getElementById("enableFormCheckbox");

    // Si el checkbox está marcado, habilita los campos
    if (checkbox.checked) {
      inputs.forEach((input) => {
        input.disabled = false;
      });
    } else {
      // Si el checkbox no está marcado, deshabilita los campos
      inputs.forEach((input) => {
        input.disabled = true;
        $("#submitButton").prop("disabled", true);
      });
    }
  }

  // Función para habilitar el botón de submit si hay cambios
  function enableSubmitIfChanged() {
    const form = document.getElementById("form_config");
    const submitButton = document.getElementById("submitButton");
    const inputs = form.querySelectorAll("input, select");

    // Comprobar si algún campo tiene cambios
    let isChanged = false;

    inputs.forEach((input) => {
      if (input.value !== input.defaultValue) {
        isChanged = true;
      }
    });

    // Si algún campo se ha cambiado, habilita el botón submit
    if (isChanged) {
      submitButton.disabled = false;
    } else {
      submitButton.disabled = true;
    }
  }

  // Evento para detectar cambios en los campos
  document
    .querySelectorAll("#form_config input, #form_config select")
    .forEach((input) => {
      input.addEventListener("input", enableSubmitIfChanged);
    });

  // Ejecuta la función cada vez que se cambie el estado del checkbox
  document
    .getElementById("enableFormCheckbox")
    .addEventListener("change", toggleFormState);

  // Ejecutar la función para que el formulario esté deshabilitado al inicio

  toggleFormState();

  $("#form_config").on("submit", function (event) {
    event.preventDefault();

    var form = $(this);
    var url = base_url_module + "/saveConfig";
    console.log("el url de la peticion es : " + url);

    $.ajax({
      url: url,
      method: "POST",
      data: form.serialize(),
      success: function (response) {
        if (response.status === "success") {
          console.log(response.status);
        } else {
          console.log("error al actualizar datos D:" + response.status);
        }

        toastr.success(
          "Se ha modificado los cambios :D.",
          "Cambios Guardados!!",
          {
            timeOut: 2000,
            positionClass: "toast-bottom-right",
          }
        );

        form.find("input, select, textarea, button").prop("disabled", true);
        $("#enableFormCheckbox").prop("checked", false);
      },
      error: function (xhr, status, error) {
        console.error("Error al actualizar los datos de : ", error);
      },
    });
  });

  $("#submitButton").on("click", function () {
    $("#form_config").submit();
  });

  $.ajax({
    url: base_url_module + "/loadForm",
    method: "GET",
    success: function (response) {
      response.opc_modalidad.forEach(function (item) {
        var option1 = $("<option></option>")
          .val(item.id_modalidad) // El valor de la opción
          .text(
            item.codigo.toUpperCase() + " - " + item.descripcion.toUpperCase()
          ); // El texto que se mostrará en la opción
        $("#config_modalidad").append(option1);
      });

      response.opc_forma.forEach(function (item) {
        var option1 = $("<option></option>")
          .val(item.id_forma) // El valor de la opción
          .text(
            item.codigo.toUpperCase() + " - " + item.descripcion.toUpperCase()
          ); // El texto que se mostrará en la opción
        $("#config_forma").append(option1);
      });

      $("#config_modalidad").val(response.data_config.ID_MODALIDAD);
      $("#config_forma").val(response.data_config.ID_FORMA);
      $("#config_direccion").val(response.data_config.DIRECCION_ENTIDAD);
      $("#config_centro_poblado").val(
        response.data_config.CENTRO_POBLADO_ENTIDAD
      );
      $("#config_distrito").val(response.data_config.DISTRITO_ENTIDAD);
      $("#config_provincia").val(response.data_config.PROVINCIA_ENTIDAD);
      $("#config_departamento").val(response.data_config.DEPARTAMENTO_ENTIDAD);
    },
    error: function (xhr, status, error) {
      console.error("Error al actualizar los datos de : ", error);
    },
  });

  /////////////////////////////////////////////////////////////////////////////////////////////


})();
