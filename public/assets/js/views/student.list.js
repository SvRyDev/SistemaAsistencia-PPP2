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


        


        const api = this.api();

        // Obtener el contenedor de DataTable
        const wrapper = $("#table_student_wrapper");
      
        // Mover el filtro al primer .col-md-6 (izquierda)
        const filter = wrapper.find("#table_student_filter");
        const wrapperRow = wrapper.find(".row");
        const leftCol = wrapperRow.find(".col-md-6").first();
      
        leftCol.empty().append(filter);
      
        // Asegurar que el contenedor y su contenido estén alineados a la izquierda
        filter.removeClass("text-right").addClass("text-left");
      
        // Estilizar el input si deseas
        filter.find("input")
          .addClass("form-control form-control-sm")
          .attr("placeholder", "Buscar estudiante...")
          .css({ width: "300px", display: "inline-block" }); // opcional: ancho visual claro
      
      
      },
    columns: [
      { title: "#", data: "estudiante_id" },
      { title: "Codigo", data: "codigo" },
      { title: "Nombre(s)", data: "nombres" },
      { title: "Apellidos", data: "apellidos" },
      { title: "DNI", data: "dni" },
      { title: "Grado", data: "grado_nombre" },
      { title: "Sección", data: "seccion" },

      {
        title: "",
        data: null,
        orderable: false,
        searchable: false,
        className: "text-center align-middle",
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-info d-inline-flex align-items-center btn-view-detail btn-modal-details" data-id="${row.estudiante_id}" title="Ver más">
              <i class="fas fa-eye mr-1"></i>
              <span class="text-nowrap">Ver más</span>
            </button>
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

  $(document).on("click", ".btn-modal-details", function () {
    const $btn = $(this); // Guarda referencia al botón
    const estudianteId = $(this).data("id");
    const originalContent = $btn.html(); // Guarda el contenido original del botón

    // Muestra spinner y desactiva el botón
    $btn
      .prop("disabled", true)
      .html(
        `<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Cargando...`
      );

    // Limpia valores anteriores (opcional)
    $(
      "#est-id, #est-codigo, #est-nombres, #est-apellidos, #est-dni, #est-grado, #est-seccion, #est-fecha"
    ).text("");
    $("#tablaAsistenciaEstudiante tbody").empty();

    // Petición AJAX para obtener los datos del estudiante
    $.ajax({
      url: base_url + "/student/getOneForDetail",
      method: "POST",
      data: { id: estudianteId },
      dataType: "json",
      success: function (data) {
        const estudiante = data.estudiante;
        const asistencias = data.asistencias;

        // Datos del estudiante
        $("#est-id").text(estudiante.estudiante_id);
        $("#est-codigo").text(estudiante.codigo);
        $("#est-nombres").text(estudiante.nombres);
        $("#est-apellidos").text(estudiante.apellidos);
        $("#est-dni").text(estudiante.dni);
        $("#est-grado").text(estudiante.grado_nombre);
        $("#est-seccion").text(estudiante.seccion);
        $("#est-fecha").text(estudiante.date_created);

        // Cargar asistencia

        // Limpiar bloque antes de cargar
        $("#bloqueAsistencia15").empty();

        asistencias.forEach((item) => {
          const fechaFormateada = formatFechaCorta(item.fecha); // ejemplo: "27 Jun"
          const icono = item.icon || "fas fa-question"; // seguridad por si falta el dato

          $("#bloqueAsistencia15").append(`
    <div class="col-2 col-sm-1 mb-2">
      <div class="text-muted small">${fechaFormateada}</div>
      <span class="badge badge-${item.clase_boostrap}">
                <i class="${icono}"></i>
              </span>
            </div>
          `);
        });

        // Mostrar el modal
        $("#modalDetalleEstudiante").modal("show");
      },
      error: function () {
        Swal.fire(
          "Error",
          "No se pudo cargar la información del estudiante.",
          "error"
        );
      },
      complete: function () {
        // Restaurar botón
        $btn.prop("disabled", false).html(originalContent);
      },
    });
  });

  /////////////////////////////////////////////////////////////////////////////////////////////
});
