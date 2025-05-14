//MODULO matricula.js
(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/" + module_name;
  const form_module_id = "#COMPLETAR";
  /////////////////////////////////////////////////////////////////////////////////////////////
  const teleView = {
    /////////////////////////////////////////////////////////////////////////////////////////////
    ///// ** VISTA Matricula ** ///////////////////////////////////////////////////////////////
    matricula: function () {
      console.log("Cargando funciones de new-matricula");
      function loadData() {
        let table = $("#tabla_estudiantes").DataTable({
          processing: true,
          serverSide: false,
          autoWidth: true,
          responsive: true,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var title = column.header().textContent;
                var input = $(
                  '<input type="text" class="column-search form-control" placeholder="Buscar ' +
                    title +
                    '">'
                )
                  .appendTo($(column.header()))
                  .on("keyup change clear", function () {
                    if (column.search() !== this.value) {
                      column.search(this.value).draw();
                    }
                  });
              });
          },

          ajax: {
            url: "" + base_url_module,
            dataSrc: "",
          },
          language: {},

          //INGRESAR LA ESTRUCTURA DEL MODULO
          columns: [
            {
              title: "Acciones",
              data: "null",
              render: function (data, type, row) {
                return `
                                                      <button type="button" class="btn btn-warning text-white mdl-edit-btn btn-sm" data-id="${data.idEstado}" data-bs-toggle="modal" data-bs-target=""><span class="span_icon_edit">${icon_edit}</span><span class="d-none d-lg-inline">Editar</span></button>
                          <button type="button" class="btn btn-danger text-white mdl-delete-btn btn-sm" data-id="${data.idEstado}" >${icon_delete}<span class="d-none d-lg-inline">Eliminar</span></button>
                        `;
              },
            },
            { title: "id", data: "estudiante_id" },
            { title: "Nombre", data: "nombre" },
            { title: "Apellidos", data: "apellido" },
            { title: "Edad", data: "edad" },
          ],
        });
      }
      loadData();

      const anio = new Date().getFullYear();
      function cargarAño() {
        $.ajax({
          url: base_url_module + "/show/" + anio, // La URL a la que se hace la solicitud
          type: "GET",
          dataType: "json",
          beforeSend: function () {
            $("#msg--is-ready").hide();
            $("#msg--isnt-ready").hide();
            $("#spinner--ready-year").show();
            $("#small-msg").hide();
          },

          success: function (response) {
            $("#spinner--ready-year").hide();

            console.log("respuserta de actualizar año " + response);

            if (response == 1) {
              $("#msg--is-ready").fadeIn();
              $("#small-msg").show();
              $("#modal--new-anio").prop("disabled", true);
          
            } else {
              $("#msg--isnt-ready").fadeIn();
              $("#small-msg").hide();
              $("#modal--new-anio").prop("disabled", false);
              $("#btn---new--matricula").prop("disabled", true);
           
            }
          },
          error: function (xhr, status, error) {
            // Esta función se ejecuta si ocurre un error en la solicitud
            console.error(error);
          },
        });
      }
      cargarAño();


      //UPDATE & CREATE - Manejar el envío del formulario con AJAX
      $("#form_new_year").on("submit", function (event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario

        var form = $(this);
        var url = base_url_module + "/activeMatricula/" + anio;

        // Verificar las validaciones antes de enviar el formulario
        if (!validateForm(form[0])) {
          return;
        }

        $.ajax({
          url: url,
          method: "POST",
          data: form.serialize(),
          success: function (response) {
            console.log(response);

            if (response.status === "success") {
              Swal.fire({
                title: "Operacion Exitosa!",
                text: response.message,
                icon: "success",
              });

              cargarAño();
              $("#modal-new-matricula").modal("hide");
              $("#btn---new--matricula").prop("disabled", false);
            }
          },
          error: function (xhr, status, error) {
            console.error(
              "Error al actualizar los datos de " + module_name + ":",
              error
            );
            alert(
              "Hubo un error al actualizar los datos de " +
                +". Inténtelo de nuevo."
            );
          },
        });
      });
    },

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///// ** VISTA Preinsciption ** /////////////////////////////////////////////////////////////
    "matricula/preinscripcion": function () {
      console.log("Cargando funciones de matricula/preinscripcion");

      let debounceTimer;

      $("#est_full_name").on("input", function () {
        const nombre_o_dni = $(this).val();

        clearTimeout(debounceTimer); // Cancela el temporizador anterior
        debounceTimer = setTimeout(function () {
          if (nombre_o_dni.length > 0) {
            $.ajax({
              url: base_url_module + "/searchStudent",
              type: "POST",
              dataType: "json",
              data: {
                q: nombre_o_dni,
              },
              beforeSend: function () {
                $("#results-students").empty();
                $("#results-students").append(
                  '<li><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>  <i> Cargando...</i></li>'
                );
              },
              success: function (response) {
                $("#results-students").empty();

                if (response.respuesta.length === 0) {
                  $("#results-students").append(
                    '<li><i class="bi bi-exclamation-circle"></i><i> Sin Resultado</i></li>'
                  );
                } else {
                  // Si hay resultados, agregarlos
                  response.respuesta.forEach(function (item) {
                    $("#results-students").append(
                      '<li class="students--item" data-id="' +
                        item.id_estudiante +
                        '">' +
                        item.documento_identificacion +
                        " - " +
                        item.apellido_paterno +
                        " " +
                        item.apellido_materno +
                        ", " +
                        item.nombres +
                        "</li>"
                    );
                  });
                }

                // Asigna el evento de clic después de cargar los elementos
                $("#results-students .students--item").on(
                  "mousedown",
                  function () {
                    var studentId = $(this).data("id");
                    var studentName = $(this).text();
                    $("#est_full_name").val(studentName);
                    $("#est_id").val(studentId);
                    $("#button--search").html('<i class="bi bi-x"></i>');
                    $("#button--search").addClass("bg-danger text-white");
                    $("#button--search").prop("disabled", false);
                    $("#est_full_name").prop("disabled", true);
                    $("#results-students").hide();
                  }
                );

                $("#est_full_name").on("focus", function () {
                  $("#results-students").empty();
                  $("#results-students").show();
                });

                $("#est_full_name").on("blur", function () {
                  $("#results-students").hide();
                });

                $("#button--search").on("click", function () {
                  $(this).removeClass("bg-danger text-white");
                  $(this).prop("prop", true);
                  $("#est_full_name").prop("disabled", false);
                  $("#button--search").html(
                    '<i class="bi bi-search" disabled></i>'
                  );
                  $("#est_full_name").val(null);

                  $("#est_id").val(null);
                });
              },
              error: function (xhr, status, error) {
                console.error("Error: " + error);
              },
            });
          } else {
            $("#results-students").empty();
          }
        }, 400); // Retraso de
      });

      $.ajax({
        url: base_url_module + "/create", // La URL a la que se hace la solicitud
        type: "GET",
        dataType: "json",
        beforeSend: function () {
          $("#form_matricula").hide();
          $(".form--placeholder").show();
        },

        success: function (response) {
          $(".form--placeholder").hide();
          $("#form_matricula").fadeIn();
          //cargar datos personal
          $("#user_nombre_completo").val(
            response.personal.nombre_completo.toUpperCase()
          );
          $("#user_cargo").val(response.personal.rol_desc.toUpperCase());

          //cargar datos configuracion
          $("#school_nombre").val(response.info_school.NOMBRE_ENTIDAD);
          $("#periodo_academico").append(
            "<option value=" +
              response.periodo_anual.id_periodo_anual +
              "> " +
              response.periodo_anual.nombre_año +
              "</option>"
          );

          // Arrays de datos recibidos
          let array_grados = response.grados;
          let array_niveles = response.niveles;
          let array_secciones = response.secciones;

          // Función para agregar opciones a un <select>
          function appendOptionsToSelect(
            selector,
            items,
            valueKey,
            textKey,
            additionalText
          ) {
            $(selector).empty(); // Limpia las opciones existentes
            items.forEach(function (item) {
              var option = $("<option></option>")
                .val(item[valueKey])
                .text(
                  item[textKey] +
                    (additionalText ? " - " + item[additionalText] : "")
                );
              $(selector).append(option);
            });
          }

          // Inicialización de combos
          appendOptionsToSelect(
            "#mat_nivel",
            array_niveles,
            "id_nivel",
            "nombre_nivel"
          );
          appendOptionsToSelect("#mat_grado", [], "id_grado", "nombre_grado");
          appendOptionsToSelect(
            "#mat_seccion",
            [],
            "id_seccion",
            "codigo_seccion"
          );
          appendOptionsToSelect(
            "#mat_turno",
            response.turnos,
            "id_turno",
            "codigo",
            "nombre_turno"
          );
          appendOptionsToSelect(
            "#mat_situacion",
            response.situacionMatricula,
            "id_situacion_matricula",
            "codigo",
            "descripcion"
          );

          appendOptionsToSelect(
            "#apod_g_instruccion",
            response.escolaridad,
            "id_escolaridad",
            "codigo",
            "descripcion"
          );

          // Manejo de cambios en combos
          $("#mat_nivel").on("change", function () {
            let selectedNivel = parseInt($(this).val());
            let filteredGrados = array_grados.filter(
              (grado) => grado.id_nivel === selectedNivel
            );
            appendOptionsToSelect(
              "#mat_grado",
              filteredGrados,
              "id_grado",
              "nombre_grado"
            );
            $("#mat_grado").trigger("change"); // Forzar cambio para actualizar secciones
          });

          $("#mat_grado").on("change", function () {
            let selectedGrado = parseInt($(this).val());
            let filteredSecciones = array_secciones.filter(
              (seccion) => seccion.id_grado === selectedGrado
            );
            appendOptionsToSelect(
              "#mat_seccion",
              filteredSecciones,
              "id_seccion",
              "codigo_seccion"
            );
          });

          // Seleccionar valores iniciales si es necesario
          $("#mat_nivel").trigger("change");
        },

        error: function (xhr, status, error) {
          // Esta función se ejecuta si ocurre un error en la solicitud
          console.error(error);
        },
      });

      $("#form_matricula").on("submit", function (event) {
        event.preventDefault();

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
    },
  };

  if (teleView[short_url_link]) {
    teleView[short_url_link]();
  } else {
    console.warn(`No se encontró una función para la vista: ${short_url_link}`);
  }

  /////////////////////////////////////////////////////////////////////////////////////////////
})();
