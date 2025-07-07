$(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/roles";
  const form_module_id = "#form_roles";
  /////////////////////////////////////////////////////////////////////////////////////////////
  let table = $("#table_student").DataTable({
    ajax: {
      url: base_url + "/student/getAll", // tu endpoint
      dataSrc: "",
    },
    initComplete: function () {
      // Recorremos todas las columnas para agregar un campo de búsqueda
      this.api().columns();
      table
        .buttons()
        .container()
        .appendTo("#table_student_wrapper .col-md-6:eq(0)");
    },
    columns: [
      { title: "#", data: "estudiante_id" },
      { title: "Codigo", data: "codigo" },
      { title: "Nombre(s)", data: "nombres" },
      { title: "Apellidos", data: "apellidos" },
      { title: "DNI", data: "dni" },
      { title: "Grado", data: "grado_nombre" },
      { title: "Sección", data: "seccion" },
      { title: "Fecha de Registro", data: "date_created" },
      {
        title: "",
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
              <div class="d-inline-flex">
                <button class="btn btn-sm btn-warning text-white btn-edit mr-1" data-id="${row.estudiante_id}" title="Editar">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.estudiante_id}" title="Eliminar">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            `;
        },
      },
    ],
    columnDefs: [
      {
        targets: 0, // La primera columna (#)
        width: "30px", // Ancho mínimo fijo o pequeño
        className: "text-left", // Centrar texto si quieres
        orderable: false,
      },
      {
        targets: -1, // Última columna (Opciones)
        width: "40px",
        orderable: false,
        searchable: false,
        className: "text-center",
      },
    ],
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    language: {
      url: base_url + "/public/assets/libs/data-table-js/languaje/Spanish.json",
    },
  });

  $(document).on("click", ".btn-edit", function () {
    const $btn = $(this);
    const $icon = $btn.find("i");
    const originalIconClass = $icon.attr("class");
    const estudianteId = $btn.data("id");

    // Mostrar spinner
    $icon.removeClass().addClass("fas fa-spinner fa-spin");

    $.ajax({
      url: base_url + "/student/getOne",
      method: "POST",
      data: { estudiante_id: estudianteId },
      dataType: "json",
      success: function (data) {
        const estudiante = data.student;

        $("#estudiante_id").val(estudiante.estudiante_id);
        $("#codigo_est").val(estudiante.codigo);
        $("#dni_est").val(estudiante.dni);
        $("#nombre_est").val(estudiante.nombres);
        $("#apellidos_est").val(estudiante.apellidos);
        $("#grado_est").val(estudiante.grado_id);
        $("#seccion_est").val(estudiante.seccion_id);

        configureModal("edit");
        $("#modalEstudiante").modal("show");
      },
      error: function () {
        Swal.fire(
          "Error",
          "No se pudo obtener los datos del estudiante",
          "error"
        );
      },
      complete: function () {
        // Restaurar ícono original
        $icon.removeClass().addClass(originalIconClass);
      },
    });
  });

  cargarGrados();
  cargarSecciones();

  //Funciones de carga
  function cargarGrados() {
    $.get(base_url + "/grade/getAll", function (response) {
      const $grado = $("#grado_est");
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
      const $seccion = $("#seccion_est");
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

  $(document).on("click", ".btn-delete", function () {
    const estudianteId = $(this).data("id");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción eliminará al estudiante.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/student/delete", // Asegúrate que este endpoint exista
          type: "POST",
          data: { estudiante_id: estudianteId },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Swal.fire("Eliminado", response.message, "success");
              $("#table_student").DataTable().ajax.reload(null, false); // Recarga sin perder paginación
            } else {
              Swal.fire("Error", response.message, "error");
            }
          },
          error: function () {
            Swal.fire("Error", "No se pudo eliminar el estudiante.", "error");
          },
        });
      }
    });
  });

  $(document).on("click", ".btn-create", function () {
    $("#formEstudiante")[0].reset(); // Limpia campos
    $("#estudiante_id").val(""); // Quita el ID oculto
    configureModal("create"); // Ajusta colores/textos
    $("#modalEstudiante").modal("show"); // Muestra modal
  });

  /**
   * Configura el modal para nuevo o edición.
   * @param {'create'|'edit'} mode
   */
  function configureModal(mode) {
    const $header = $("#modalEstudiante .modal-header");
    const $title = $("#modalEstudianteTitle");
    const $btn = $("#btnGuardar");

    // Limpiar clases comunes
    $header.removeClass("bg-success bg-warning");
    $btn.removeClass("btn-success btn-warning");

    if (mode === "create") {
      $header.addClass("bg-success");
      $title.html('<i class="fas fa-user-plus mr-2"></i> Nuevo Estudiante');
      $btn
        .addClass("btn-success")
        .html('<i class="fas fa-save mr-1"></i> Guardar');
    } else if (mode === "edit") {
      $header.addClass("bg-warning");
      $title.html('<i class="fas fa-edit mr-2"></i> Editar Estudiante');
      $btn
        .addClass("btn-warning text-white")
        .html('<i class="fas fa-save mr-1"></i> Actualizar');
    }




    
  }



  $('#formEstudiante').on('submit', function (e) {
    e.preventDefault();
  
    const form = this;
    const formData = $(form).serialize();
    const estudianteId = $('#estudiante_id').val();
    const url = base_url + (estudianteId ? '/student/update' : '/student/store');
    const isNew = !estudianteId;
  
    Swal.fire({
      title: isNew ? '¿Registrar estudiante?' : '¿Actualizar datos?',
      text: isNew
        ? 'Se guardará un nuevo registro en el sistema.'
        : 'Se actualizarán los datos del estudiante.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: isNew ? 'Sí, registrar' : 'Sí, actualizar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(url, formData, function (response) {
          if (response.status === 'success') {
            $('#modalEstudiante').modal('hide');
            form.reset();
  
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: response.message,
              timer: 2000,
              showConfirmButton: false
            });
  
            $('#tablaEstudiantes').DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message
            });
          }
        }, 'json').fail(function () {
          Swal.fire({
            icon: 'error',
            title: 'Error del servidor',
            text: 'No se pudo procesar la solicitud.'
          });
        });
      }
    });
  });
  
  /////////////////////////////////////////////////////////////////////////////////////////////
});
