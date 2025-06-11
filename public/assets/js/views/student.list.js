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
      { title: "Grado", data: "grado" },
      { title: "Sección", data: "seccion" },
      { title: "Fecha de Registro", data: "date_created" },
      {
        title: "",
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
      <div class="dropdown">
        <button class="btn btn-sm btn-light " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item btn-edit" href="#" data-id="${row.estudiante_id}">
            <i class="fas fa-edit text-primary mr-2"></i>Editar
          </a>
          <a class="dropdown-item btn-delete" href="#" data-id="${row.estudiante_id}">
            <i class="fas fa-trash text-danger mr-2"></i>Eliminar
          </a>
        </div>
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
    buttons: [
      {
        extend: 'copy',
        text: '<i class="fas fa-copy"></i> Copiar',
        className: 'btn btn-light mr-1'
      },
      {
        extend: 'csv',
        text: '<i class="fas fa-file-csv"></i> CSV',
        className: 'btn btn-info mr-1'
      },
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn btn-success mr-1'
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-danger mr-1'
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Imprimir',
        className: 'btn btn-secondary mr-1'
      },
      {
        extend: 'colvis',
        text: '<i class="fas fa-columns"></i> Columnas',
        className: 'btn btn-info mr-1'
      }
    ]
  });
  /////////////////////////////////////////////////////////////////////////////////////////////
});