console.log("PAsarando por la js");

$("#searchStudentButton").on("click", function () {
  const studentCode = $("#studentCodeInput").val(); // Supongamos que tienes un campo de entrada con el ID del estudiante



  $.ajax({
    url: base_url + "/report/RecordByStudent", // Cambia esto a la URL de tu endpoint
    type: "POST",
    data: { code: studentCode },
    success: function (response) {
      const student = response.student;
      const daysActive = response.days_active;
      const attendance = response.attendance_records;

      $("#td_nombres").html(`${student.nombres} ${student.apellidos}`);

      // Agrupar daysActive por mes
      const groupedByMonth = {};

      daysActive.forEach((day) => {
        const date = new Date(day.fecha);
        const yearMonth =
          date.getFullYear() +
          "-" +
          String(date.getMonth() + 1).padStart(2, "0");
        if (!groupedByMonth[yearMonth]) {
          groupedByMonth[yearMonth] = [];
        }
        groupedByMonth[yearMonth].push(day);
      });

      // Construir cabecera
      let header1 =
        '<tr class="bg-success"><th style="width:150px">Nombre y Apellido</th>';
      let header2 = '<tr class="bg-success"><td></td>';
      let header3 = '<tr class="table-success"><td></td>';

      for (const [monthKey, days] of Object.entries(groupedByMonth)) {
        const sampleDate = new Date(days[0].fecha);
        const monthName = sampleDate
          .toLocaleString("es-ES", { month: "long" })
          .toUpperCase();
        header1 += `<th colspan="${days.length}">${monthName}</th>`;

        days.forEach((day) => {
          header2 += `<td>${day.nombre.charAt(0)}</td>`;
          header3 += `<td>${new Date(day.fecha).getDate()}</td>`;
        });
      }

      header1 += "</tr>";
      header2 += "</tr>";
      header3 += "</tr>";

      $("#table-resultado thead").html(header1 + header2 + header3);

      // Construir body
      let tbody = `<tr><td id="td_nombres">${student.nombres} ${student.apellidos}</td>`;

      for (const days of Object.values(groupedByMonth)) {
        days.forEach((day) => {
          const registro = attendance.find(
            (r) => r.dia_fecha_id === day.dia_fecha_id
          );

          let simbolo = "-";
          if (registro) {
            switch (registro.condicion) {
              case "asistencia":
                simbolo = '<i class="fas fa-check text-success"></i>';
                break;
              case "inasistencia":
                simbolo = '<i class="fas fa-times text-danger"></i>';
                break;
              case "justificado":
                simbolo = '<i class="fas fa-exclamation text-warning"></i>';
                break;
              case "tardanza":
                simbolo = '<i class="fas fa-clock text-secondary"></i>';
                break;
            }
          }

          tbody += `<td style="width: 40px; text-align: center;">${simbolo}</td>`;
        });
      }

      tbody += "</tr>";

      $("#table-resultado tbody").html(tbody);











        // Destruir previa si ya existe
  if ($.fn.DataTable.isDataTable("#table-resultado")) {
    $("#table-resultado").DataTable().destroy();
  }
  // Inicializar DataTable con botones de exportación
  $("#table-resultado").DataTable({
    paging: false, // Desactiva paginación si no la necesitas
    searching: false, // Desactiva buscador si no aplica
    ordering: false, // Desactiva ordenamiento si no lo necesitas
    scrollX: true, // Scroll horizontal
    dom: "Bfrtip",
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
      ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  });












  

      // Calcular totales
      let totalAsistencias = attendance.filter(
        (r) => r.condicion === "asistencia"
      ).length;
      let totalInasistencias = attendance.filter(
        (r) => r.condicion === "inasistencia"
      ).length;
      let totalJustificaciones = attendance.filter(
        (r) => r.condicion === "justificado"
      ).length;
      let totalTardanzas = attendance.filter(
        (r) => r.condicion === "tardanza"
      ).length;

      $(".mt-3").html(`
                Total asistencias: <span class="text-success">${totalAsistencias}</span><br>
                Total inasistencias: <span class="text-danger">${totalInasistencias}</span><br>
                Total justificaciones: <span class="text-warning">${totalJustificaciones}</span><br>
                Total tardanzas: <span class="text-info">${totalTardanzas}</span>
            `);
    },

    error: function (xhr, status, error) {
      // Manejo de errores
      console.error("Error al buscar estudiante:", error);
      $("#studentInfo").html("<p>Error al buscar estudiante.</p>");
    },
  });


});
