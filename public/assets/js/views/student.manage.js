$(function () {
    /////////////////////////////////////////////////////////////////////////////////////////////
    const base_url_module = base_url + "/roles";
    const form_module_id = "#form_roles";
    /////////////////////////////////////////////////////////////////////////////////////////////
    let table = $("#table_student").DataTable({
      ajax: {
        url: base_url + "/student/getAll", // tu endpoint
        dataSrc: ""
      },
      initComplete: function () {
        // Recorremos todas las columnas para agregar un campo de búsqueda
        this.api().columns();
        table.buttons().container().appendTo('#table_student_wrapper .col-md-6:eq(0)');
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
          }
          
          
        }
      ],
        columnDefs: [
      {
        targets: 0,         // La primera columna (#)
        width: "30px",      // Ancho mínimo fijo o pequeño
        className: "text-left",  // Centrar texto si quieres
        orderable: false
      },
      {
        targets: -1,        // Última columna (Opciones)
        width: "40px",
        orderable: false,
        searchable: false,
        className: "text-center"
      }
    ],
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      language: {
        url: base_url + "/public/assets/libs/data-table-js/languaje/Spanish.json"
      },
     
    });
    /////////////////////////////////////////////////////////////////////////////////////////////
  });