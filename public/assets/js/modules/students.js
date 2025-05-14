(function () {
/////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/" + module_name;
  const form_module_id = "#form_students";
/////////////////////////////////////////////////////////////////////////////////////////////
  function loadData() {
    let table = $("#tabla_estudiantes").DataTable({
      processing: true,
      serverSide: false,
      autoWidth: true,
      responsive: true,

      initComplete: function () {
        // Recorremos todas las columnas para agregar un campo de búsqueda
        this.api()
          .columns()
          .every(function () {
            var column = this;
            var title = column.header().textContent; // Obtenemos el nombre de la columna para personalizar el filtro

            // Crear un input para cada columna de la tabla, sin necesidad de modificar el HTML de la tabla
            var input = $(
              '<input type="text" class="column-search form-control" placeholder="Buscar ' +
                title +
                '">'
            )
              .appendTo($(column.header())) // Añadimos el input a la cabecera de cada columna
              .on("keyup change clear", function () {
                if (column.search() !== this.value) {
                  column.search(this.value).draw(); // Aplicamos el filtro de búsqueda cuando se modifica el input
                }
              });
          });
      },

      ajax: {
        url: base_url_module + "/show",
        dataSrc: "",
      },
      language: {
        url: base_url + '/public/assets/libs/data-table-js/languaje/Spanish.json',
      },

      //INGRESAR LA ESTRUCTURA DEL MODULO
      columns: [
      /*
        {
          title: "Acciones",
          data: "null",
          render: function (data, type, row) {
            return `

                                        <button type="button" class="btn btn-warning text-white mdl-edit-btn btn-sm" ><span class="span_icon_edit"></span><span class="d-none d-lg-inline"><i class="bi bi-pencil-square"></i> </span></button>
            
                                        <button type="button" class="btn btn-danger text-white mdl-delete-btn btn-sm" data-id="" ><span class="d-none d-lg-inline"><i class="bi bi-trash-fill"></i> </span></button>
            <button type="button" class="btn btn-secondary text-white mdl-delete-btn btn-sm" data-id="" ><span class="d-none d-lg-inline"><i class="bi bi-file-earmark-fill"></i> </span></button>
          `;
          },
        },
*/
        { title: "id", data: "id_estudiante" },
        { title: "N° Documento", data: "documento_identificacion" },
        {
          title: "Nombre",
          data: null, // Especificamos 'null' porque vamos a usar 'render' para personalizar la salida
          render: function (data, type, row) {
            return `${row.apellido_paterno} ${row.apellido_materno}, ${row.nombres}`;
          },
        },
        { title: "Pais", data: "pais_origen_desc" },
        { title: "Fecha Nacimiento", data: "fecha_nacimiento" },
      ],
    });
  }

  loadData();



  $.ajax({
    url: base_url_module + "/create", // La URL a la que se hace la solicitud
    type: "GET",
    dataType: "json",
    beforeSend: function () {
      
      $('.form--placeholder').fadeIn()
      $('.form--template').hide()
    },

    success: function (response) {
      // Limpiar las opciones actuales en ambos selects
  
      $("#est_lengua_materna").empty();
      $("#est_segunda_lengua").empty();

      $("#mad_grado_instruc").empty();
      $("#pad_grado_instruc").empty();

      $("#est_tipo_discapacidad").empty();
      $("#est_pais_nac").empty();
      $("#est_estado_civil").empty();
      $("#est_tipo_sangre").empty();

      // Agregar una opción por defecto
      $("#est_lengua_materna").append(
        '<option value="" disabled selected >Selecciona una lengua materna</option>'
      );
      $("#est_segunda_lengua").append(
        '<option value="" disabled selected >Seleccione </option>'
      );

      $("#mad_grado_instruc").append(
        '<option value="" disabled selected >Seleccione </option>'
      );

      $("#pad_grado_instruc").append(
        '<option value="" disabled selected >Seleccione </option>'
      );

      $("#est_tipo_discapacidad").append(
        '<option value="" disabled selected >Seleccione </option>'
      );
      $("#est_pais_nac").append(
        '<option value="" disabled selected >Seleccione </option>'
      );
      $("#est_estado_civil").append(
        '<option value="" disabled selected >Seleccione </option>'
      );
      $("#est_tipo_sangre").append(
        '<option value="" disabled selected >Seleccione </option>'
      );

      // Iterar sobre la respuesta JSON para agregar las opciones al select
      response.grado_instruccion.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_escolaridad) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        // Crear un nuevo elemento <option> para el segundo select
        var option2 = $("<option></option>")
          .val(item.id_escolaridad) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        // Agregar las opciones a sus respectivos selects
        $("#mad_grado_instruc").append(option1);
        $("#pad_grado_instruc").append(option2);
      });

      response.languajes.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_lengua) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        // Crear un nuevo elemento <option> para el segundo select
        var option2 = $("<option></option>")
          .val(item.id_lengua) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        // Agregar las opciones a sus respectivos selects
        $("#est_lengua_materna").append(option1);
        $("#est_segunda_lengua").append(option2);
      });

      response.genero.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_sexo) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        $("#est_sexo").append(option1);
      });

      response.discapacidad.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_discapacidad) // El valor de la opción
          .text(item.codigo + " - " + item.descripcion); // El texto que se mostrará en la opción

        $("#est_tipo_discapacidad").append(option1);
      });

      response.pais.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_pais) // El valor de la opción
          .text(item.nombre.toUpperCase()); // El texto que se mostrará en la opción

        $("#est_pais_nac").append(option1);
      });

      response.estado_civil.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_estado_civil) // El valor de la opción
          .text(item.descripcion.toUpperCase()); // El texto que se mostrará en la opción

        $("#est_estado_civil").append(option1);
      });

      response.tipo_sangre.forEach(function (item) {
        // Crear un nuevo elemento <option> para el primer select
        var option1 = $("<option></option>")
          .val(item.id_tipo_sangre) // El valor de la opción
          .text(item.codigo); // El texto que se mostrará en la opción

        $("#est_tipo_sangre").append(option1);
      });

      $('.form--placeholder').hide()
      $('.form--template').show()
    },
    error: function (xhr, status, error) {
      // Esta función se ejecuta si ocurre un error en la solicitud
      console.error(error);
    },
  });

  //UPDATE & CREATE - Manejar el envío del formulario con AJAX
  $(form_module_id).on("submit", function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario

    var form = $(this);
    var url = base_url_module + "/store";

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
  $("body").on("click", ".mdl-delete-btn", function () {
    var dataId = $(this).data("id");

    Swal.fire({
      title: "¿Eliminar registro?",
      text:
        "Registro a eliminar: Id de " + name_module_singular + ": " + dataId,
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

  //DELETE - Función AJAX para eliminar
  function deleteData(dataId) {
    $.ajax({
      url: base_url_module + "/delete/" + dataId,
      method: "POST",
      success: function (response) {
        if (response.status === "success") {
          Swal.fire(
            "¡Eliminado!",
            "El " + name_module + " ha sido eliminado.",
            "success"
          );
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
