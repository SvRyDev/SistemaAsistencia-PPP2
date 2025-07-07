$(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////////

  cargarGrados();
  cargarSecciones();
  $("#fecha_grupo").val(getFechaActual());

  //Funciones de carga
  function cargarGrados() {
    $.get(base_url + "/grade/getAll", function (response) {
      const $grado = $("#grado_grupo");
      $grado.empty().append('<option value="">-- TODO --</option>');

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
      const $seccion = $("#seccion_grupo");
      $seccion.empty().append('<option value="">-- TODO --</option>');

      if (response.status === "success" && Array.isArray(response.sections)) {
        response.sections.forEach((s) => {
          $seccion.append(
            `<option value="${s.id_seccion}">${s.nombre_seccion}</option>`
          );
        });
      }
    });
  }

  $(document).ready(function () {
    // Manejo del envío del formulario
    $("#attendanceForm").on("submit", function (e) {
      e.preventDefault();

      const data = {
        fecha: $("#fecha_grupo").val(),
        grado: $("#grado_grupo").val(),
        seccion: $("#seccion_grupo").val(),
      };

      // Validación básica
      if (!data.fecha) {
        Swal.fire({
          icon: "warning",
          title: "Campos incompletos",
          text: "Debe seleccionar la fecha",
          confirmButtonText: "Aceptar",
        });
        return;
      }

      // Enviar datos por AJAX POST
      $.ajax({
        url: base_url + "/attendance/getListByFilter",
        type: "POST",
        data: data,
        dataType: "json",
        beforeSend: function () {
          // Limpia el mensaje antes de la carga
          $("#alert-info").html(`
            <i class="fas fa-spinner fa-spin text-secondary mr-2"></i>
            Buscando registros de asistencia, por favor espere...
          `);

          // Destruir DataTable si ya estaba inicializado
          if ($.fn.DataTable.isDataTable("#table_attendance")) {
            $("#table_attendance").DataTable().clear().destroy();
          }
        },
        success: function (response) {
          if (response.status === "success" && response.data.length > 0) {
            // Suponiendo que la fecha viene en el primer registro
            const cantidad = response.data.length;
            const fecha = response.data[0].fecha;

            $("#alert-info").html(`
              <i class="fas fa-calendar-check text-success mr-2"></i>
              Se encontraron <strong>${cantidad}</strong> registros de asistencia para el día 
              <strong>${fecha}</strong>.
            `);

            const rows = response.data.map((item) => {
              return `
                  <tr>
                    <td>${item.asistencia_estudiante_id}</td>
                    <td>${item.codigo}</td>
                    <td>${item.apellidos} ${item.nombres}</td>
                    <td>${item.grado}</td>
                    <td>${item.seccion}</td>
                    <td>
                      <span class="badge badge-${item.clase_boostrap}" >
                        <i class="${item.icon}"></i> ${item.nombre_estado}
                      </span>
                    </td>
                    <td>
                 <button class="btn btn-outline-dark btn-sm rounded-pill btn-detalle" data-id="${item.asistencia_estudiante_id}">
                    <i class="fas fa-eye mr-1"></i> Detalles
                 </button>
                    </td>
                  </tr>
                `;
            });

            $("#table_attendance tbody").html(rows.join(""));

            // Inicializar DataTable
            $("#table_attendance").DataTable({
              responsive: false,
              language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
              },
            });
            //------------------------------------------------------
            // Evento click para abrir modal con detalle -cargando de los mismos datos anteriores
            //---------------------------------------------------------
            $("#table_attendance").on("click", ".btn-detalle", function () {
                const id = $(this).data("id");
                const registro = response.data.find(
                  (item) => item.asistencia_estudiante_id == id
                );
              
                if (registro) {
                  // Datos del estudiante
                  $("#detalle-id").text(registro.asistencia_estudiante_id || "—");
                  $("#detalle-codigo").text(registro.codigo || "—");
                  $("#detalle-estudiante").text(
                    `${registro.apellidos || ""} ${registro.nombres || ""}`.trim() || "—"
                  );
                  $("#detalle-grado").text(registro.grado || "—");
                  $("#detalle-seccion").text(registro.seccion || "—");
              
                  // Datos de la asistencia
                  $("#detalle-dia").text(`${registro.fecha || "—"}`);
                  $("#detalle-hora").text(registro.hora_entrada || "—");
              
                  // Estado de asistencia con badge
                  $("#detalle-estado").html(`
                    <span class="badge badge-${registro.clase_boostrap || 'secondary'}">
                      <i class="${registro.icon || 'fas fa-question-circle'}"></i> ${registro.nombre_estado || 'Desconocido'}
                    </span>
                  `);
              
                  // Observación, si está vacía mostramos mensaje alternativo
                  $("#detalle-observacion").text(registro.observacion || "Sin observaciones");
              
                  // Mostrar modal
                  const modal = new bootstrap.Modal(document.getElementById("modalDetalleAsistencia"));
                  modal.show();
                }
              });
              



          } else {
            $("#alert-info").html(`
                <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                No se encontraron registros para la fecha seleccionada.
              `);
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un error al consultar las asistencias.",
          });
          $("#alert-info").html(`
            <i class="fas fa-exclamation-circle text-warning mr-2"></i>
            No se encontraron registros para la fecha seleccionada.
          `);
        },
      });
    });
  });

  /////////////////////////////////////////////////////////////////////////////////////////////
});
