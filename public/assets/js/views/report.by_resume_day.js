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
  */


  $.ajax({
    url: base_url + "/student/getTotalStudents", // tu ruta correcta
    type: "GET",
    dataType: "json",
    beforeSend: function () {
      $('#total-estudiantes').html('<div class="spinner-grow spinner-grow-sm" role="status">   <span class="sr-only">Loading...</span> </div>');
    },
    success: function (response) {
      console.log("Total estudiantes:", response.total_estudiantes);
      $('#total-estudiantes').html(response.total);

    },
    error: function (xhr, status, error) {
    },
  });

  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});


$('#').on('click', function () {
  const nombreMes = $("#select-mes").val().trim();


  if (nombreMes === "") {
    alert("Por favor, ingrese el mes a consultar.");
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByResumeMonth",
    type: "POST",
    data: { mes: nombreMes.toUpperCase() },
    beforeSend: function () {
     
    },
    success: function (response) {
      if (response.status !== "success") {
        $("#table-resultado tbody").html(`
          <tr><td colspan="5" class="text-center text-muted">${response.message || "Error al obtener datos."
          }</td></tr>
        `);
        return;
      }

      $("#result-nombre-est").html(
        (
          response.student.nombres +
          " " +
          response.student.apellidos
        ).toUpperCase()
      );
      $("#result-codigo-est").html(response.student.codigo.toUpperCase());

      $("#result-grado-est").html(
        response.student.grado_nombre + " - " + response.student.seccion
      );

      $(".left-text").html(
        (
          response.student.nombres +
          " " +
          response.student.apellidos +
          " - " +
          response.student.codigo
        ).toUpperCase()
      );

      const attendance = response.attendance_records;

      if (!attendance.length) {
        $("#table-resultado tbody").html(`
          <tr><td colspan="5" class="text-center text-muted">No se encontraron registros de asistencia.</td></tr>
        `);
        return;
      }

      attendance.forEach((record, index) => {
        table.row.add([
          index + 1,
          `${getNombreMes(record.fecha_asistencia).toUpperCase()}`,
          `${getDia(
            record.fecha_asistencia
          )} - ${record.nombre_dia.toUpperCase()}`,
          record.estado_asistencia,
          record.observacion || "",
        ]);
      });

      table.draw(false);
      table.buttons().enable();

      let totalAsistencias = 0;
      let totalFaltas = 0;
      let totalJustificados = 0;
      let totalTardanzas = 0;

      attendance.forEach((registro) => {
        const abrev = registro.abreviatura;

        if (abrev === "P") totalAsistencias++;
        else if (abrev === "F") totalFaltas++;
        else if (abrev === "J") totalJustificados++;
        else if (abrev === "T") totalTardanzas++;
      });

      // Actualiza en el DOM
      $(".total-asistencias").html(totalAsistencias);
      $(".total-inasistencias").html(totalFaltas);
      $(".total-justificados").html(totalJustificados);
      $(".total-tardanzas").html(totalTardanzas);
    },
    error: function () {
      alert("Error del servidor");
    },
    complete: function () {
      $("#searchStudentButton").prop("disabled", false);
      $('#student-info-loader').hide();
      $('#info-student-contain').show();


    },
  });
});