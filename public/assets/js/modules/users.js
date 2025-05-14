(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/users";
  const form_module_id = "#form_users";
  /////////////////////////////////////////////////////////////////////////////////////////////
  let table = $("#tabla_usuarios").DataTable({
    processing: true,
    serverSide: false,
    autoWidth: true,
    responsive: true,

    initComplete: function () {
      // Recorremos todas las columnas para agregar un campo de búsqueda
      this.api().columns();
    },

    ajax: {
      url: base_url + "/users/show",
      dataSrc: "",
    },
    language: {
      url: base_url + "/public/assets/libs/data-table-js/languaje/Spanish.json",
    },

    //INGRESAR LA ESTRUCTURA DEL MODULO
    columns: [
      {
        title: "Acciones",
        data: "null",
        render: function (data, type, row) {
          return `
                <button type="button" class="btn btn-warning text-white user-edit-btn btn-sm" data-id="${row.id_usuario}"><span class="span_icon_edit"></span><span class="d-none d-lg-inline"><i class="bi bi-pencil-square"></i> </span></button>
                <button type="button" class="btn btn-danger text-white user-delete-btn btn-sm" data-id="${row.id_usuario}" ><span class="d-none d-lg-inline"><i class="bi bi-trash-fill"></i> </span></button>
              `;
        },
      },
      { title: "id", data: "id_usuario" },
      { title: "Usuario", data: "usuario" },
      {
        title: "Clave",
        data: "null",
        render: function (data, type, row) {
          return `
               ******
               `;
        },
      },
      { title: "Propietario", data: "nombre_completo" },
      { title: "Cargo", data: "rol_desc" },
      { title: "Estado", data: "activo" },
    ],
  });

  if ($("#id_user").val()) {

    $('#user_clave').prop('required', false);
    const id_user = $("#id_user").val();
    $("#btn--submit--form")
      .addClass("btn-primary")
      .removeClass("btn-success")
      .html('<i class="bi bi-pencil-square"></i>  Actualizar Usuario');
    $.ajax({
      url: base_url_module + "/get/" + id_user,
      method: "POST",

      success: function (response) {
        console.log(response);

        $("#user_nombre_completo").val(response.nombre_persona);
        $("#user_nombre_usuario").val(response.nombre_usuario);
        $("#user_rol").val(response.id_rol);
      },
      error: function (xhr, status, error) {
        console.log();
      },
    });
  }

  $(".form--template").hide();

  $.ajax({
    url: base_url_module + "/loadForm",
    type: "GET",
    dataType: "json",
    beforeSend: function () {
      $(".form--placeholder").show();
      $(".form--template").hide();
    },
    success: function (response) {
      response.roles.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_rol) // El valor de la opción
          .text(item.descripcion.toUpperCase()); // El texto que se mostrará en la opción

        $("#user_rol").append(option1);
      });

      $(".form--placeholder").hide();
      $(".form--template").fadeIn();
    },
    error: function (xhr, status, error) {
      console.error(
        "Error al actualizar los datos de " + module_name + ":",
        error
      );
    },
  });

  //Show de Editar Datos
  $("body").on("click", ".user-edit-btn", function () {
    var dataId = $(this).data("id");
    loadView("users/edit/" + dataId);
  });

  //███UPDATE & CREATE - Manejar el envío del formulario con AJAX
  $("#form_users").on("submit", function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario

    var form = $(this);
    const id_user = $("#id_user").val();

    if (!id_user) {
      var url = base_url_module + "/store";
    } else {
      var url = base_url_module + "/update";
    }

    // Verificar las validaciones antes de enviar el formulario
    if (!validateForm(form[0])) {
      return;
    }

    $.ajax({
      url: url,
      method: "POST",
      data: form.serialize(),
      success: function (response) {
        if (response.status === "success") {
          Swal.fire({
            title: "Operacion Exitosa!",
            text: response.message,
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Regresar a la vista anterior
              window.history.back(); // O redirige explícitamente
              // Ejemplo: window.location.href = '/ruta-a-la-vista';
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            text: "Error al actualizar " + module_name + "!",
          });
          console.log(response);
        }
      },
      error: function (xhr, status, error) {
        console.error(
          "Error al actualizar los datos de " + module_name + ":",
          error
        );
        alert(
          "Hubo un error al actualizar los datos de " +
            name_module +
            ". Inténtelo de nuevo."
        );
      },
    });
  });

  //Modal de Confirmacion para Eliminar Datos
  $("body").on("click", ".user-delete-btn", function () {
    var dataId = $(this).data("id");

    Swal.fire({
      title: "¿Eliminar registro?",
      text: "Estas por eliminar el usuario N° " + dataId,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Eliminar",
    }).then((result) => {
      if (result.isConfirmed) {
        deleteData(dataId);
      }
    });
  });

  //███ DELETE - Función AJAX para eliminar
  function deleteData(dataId) {
    $.ajax({
      url: base_url_module + "/delete/" + dataId,
      method: "POST",
      success: function (response) {
        if (response.status === "success") {
          Swal.fire("¡Eliminado!", "El usuario ha sido eliminado.", "success");
          table.ajax.reload(null, false); // Recargar la tabla
        } else {
          Swal.fire(
            "Error",
            "Hubo un error al eliminar el registro. Inténtelo de nuevo.",
            "error"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al eliminar el registro:", error);
        Swal.fire(
          "Error",
          "Hubo un error al eliminar el registro. Inténtelo de nuevo.",
          "error"
        );
      },
    });
  }

  /////////////////////////////////////////////////////////////////////////////////////////////
})();
