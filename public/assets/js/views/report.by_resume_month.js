let table;

$(document).ready(function () {
  let logoBase64 = "";


  /*
  table = $("#table-resultado").DataTable({
    dom: `t`,

    buttons: dtButtons,
    initComplete: function () {
      $(".left-text").html("");
      this.api().buttons().container().appendTo("#table-buttons-wrapper");
      this.api().buttons().disable(); // ✅ esto funciona
    },
    ordering: false,
    searching: false,
    paging: false,
    info: false,
    responsive: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
  });

  $.ajax({
    url: base_url + "/report/getGroupFilterOptions", // tu ruta correcta
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        // Llenar grados
        const gradoSelect = $("#select-grado");
        gradoSelect
          .empty()
          .append('<option value="">-- Seleccionar Grado --</option>');
        response.grados.forEach((grado) => {
          gradoSelect.append(
            `<option value="${grado.id_grado}">${grado.nombre_completo}</option>`
          );
        });

        // Llenar secciones
        const seccionSelect = $("#select-seccion");
        seccionSelect
          .empty()
          .append('<option value="">-- Seleccionar Sección --</option>');
        response.secciones.forEach((seccion) => {
          seccionSelect.append(
            `<option value="${seccion.id_seccion}">${seccion.nombre_seccion}</option>`
          );
        });
      } else {
        alert(
          response.message ||
          "No se pudo cargar la información de grados y secciones."
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar filtros del grupo:", error);
      alert("Hubo un error al obtener los filtros. Intenta más tarde.");
    },
  });
*/
  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});
