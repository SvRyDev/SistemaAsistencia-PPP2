let table;

$(document).ready(function () {
  let logoBase64 = "";

  function convertImgToBase64(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
      var reader = new FileReader();
      reader.onloadend = function () {
        callback(reader.result);
      };
      reader.readAsDataURL(xhr.response);
    };
    xhr.open("GET", url);
    xhr.responseType = "blob";
    xhr.send();
  }

  // Llama esta función en tu inicialización:
  convertImgToBase64(
    `${base_url}/public/assets/img/static/logo_.png`,
    function (base64) {
      logoBase64 = base64;
    }
  );

  table = $("#table-resultado").DataTable({
    dom: `
    <'row mb-3'
      <'col-12 col-md-5 d-flex align-items-center order-2 order-md-1'<'left-text font-weight-bold'>>
      <'col-12 col-md-7 d-flex justify-content-md-end justify-content-start order-1 order-md-2'B>
    >
    <'row'
      <'col-sm-12'f>
    >
    <'row'
      <'col-sm-12'tr>
    >
    <'row'
      <'col-sm-5'i>
      <'col-sm-7'p>
    >
  `,

    buttons: [
      {
        extend: "copy",
        text: '<i class="fas fa-copy"></i> Copiar',
        className: "btn btn-light mr-2 mb-1 rounded",
      },
      {
        extend: "csv",
        text: '<i class="fas fa-file-csv"></i> Exportar a CSV',
        className: "btn btn-info mr-2 mb-1 rounded",
      },
      {
        extend: "excel",
        text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
        className: "btn btn-success mr-2 mb-1 rounded",
        title: "Reporte de Asistencia",
        messageTop: function () {
          return `Generado el ${new Date().toLocaleDateString()} - Estudiante: ${$(
            "#studentCodeInput"
          ).val()}`;
        },
      },
      {
        extend: "pdf",
        text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
        className: "btn btn-danger mr-2 mb-1 rounded",
        title: "", // evita duplicación de título
        customize: function (doc) {
          const estudiante = $("#studentCodeInput").val();
          const fecha = new Date().toLocaleDateString();

          // Cabecera con logo y texto
          doc.content.splice(0, 0, {
            table: {
              widths: ["20%", "80%"], // Distribución proporcional del ancho
              body: [
                [
                  {
                    image: logoBase64, // Asegúrate de que logoBase64 contiene el string base64 del logo
                    width: 45,
                    alignment: "right",
                    margin: [0, 0, 0, 0],
                  },
                  {
                    stack: [
                      {
                        text: "INSTITUCIÓN EDUCATIVA Mx. SAN LUIS",
                        style: "header",
                      },
                      { text: "Reporte de Asistencia", style: "subheader" },
                      {
                        text: `Estudiante: ${estudiante}    Generado el: ${fecha}`,
                      },
                    ],
                    alignment: "right",
                    margin: [10, 10, 10, 10],
                  },
                ],
              ],
            },
            layout: "noBorders",
            margin: [0, 0, 0, 10],
          });

          /*
          // Subtítulo con estudiante y fecha
          doc.content.splice(1, 0, {
            
            alignment: 'left',
            fontSize: 10,
            margin: [0, 0, 0, 10]
          });
*/

          // Pie de página
          doc.footer = function (currentPage, pageCount) {
            return {
              columns: [
                {
                  text: "Fin del reporte - Generado por el sistema",
                  alignment: "center",
                  fontSize: 9,
                },
              ],
              margin: [0, 10, 0, 0],
            };
          };

          // ✔ Agrega margen a las celdas de la tabla de datos
          doc.content[doc.content.length - 1].layout = {
            paddingLeft: function () {
              return 9;
            },
            paddingRight: function () {
              return 9;
            },
            paddingTop: function () {
              return 3;
            },
            paddingBottom: function () {
              return 3;
            },

            hLineColor: function () {
              return "#FFFFFF";
            }, // líneas horizontales
            vLineColor: function () {
              return "#FFFFFF";
            }, // líneas verticales

            hLineWidth: function () {
              return 1;
            }, // grosor de línea horizontal
            vLineWidth: function () {
              return 1;
            }, // grosor de línea vertical
          };

          doc.content[doc.content.length - 1].table.widths = [
            "5%",
            "15%",
            "20%",
            "15%",
            "45%",
          ]; // Para 5 columnas

          // Estilos
          doc.styles.header = {
            fontSize: 14,
            bold: true,
          };
          doc.styles.subheader = {
            fontSize: 11,
            margin: [0, 2, 0, 2],
          };
        },
      },

      {
        extend: "print",
        text: '<i class="fas fa-print"></i> Imprimir',
        className: "btn btn-secondary mr-2 mb-1 rounded",
        customize: function (win) {
          const estudiante = $("#studentCodeInput").val();
          const fecha = new Date().toLocaleDateString();

          // Cabecera personalizada con logo
          $(win.document.body).prepend(`
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
    <div style="flex: 1;">
      <img src="${base_url}/public/assets/img/static/logo_.png" style="height: 60px;">
    </div>
    <div style="flex: 2; text-align: center;">
      <h2 style="margin: 0;">INSTITUCIÓN EDUCATIVA</h2>
      <p style="margin: 0;">Reporte de Asistencia</p>
    </div>
    <div style="flex: 1;"></div> <!-- Espacio vacío para balancear -->
  </div>

  <div style="text-align: center; margin-bottom: 10px;">
    <p style="margin: 0;">Estudiante: <strong>${estudiante}</strong></p>
    <p style="margin: 0;">Generado el: ${fecha}</p>
  </div>
`);

          // Pie de página
          $(win.document.body).append(`
  <div style="position:fixed; bottom:10px; left:0; right:0; text-align:center; font-size:10px;">
    <hr style="width:90%; margin-bottom:4px;">
    <p style="margin:0;">Fin del reporte - Generado por el sistema</p>
  </div>
`);

          // Estilos CSS
          const css = `
  @page {
    size: portrait;
    margin: 0 12mm;
  }
  body {
    font-size: 12px;
  }
  table {
    width: 600px !important;
    font-size: 12px;
    border-collapse: collapse !important;
  }
  table th{
    background-color:rgb(162, 222, 134) !important;
    text-align: center;
    font-weight: bold;
  }
  table thead th, table tbody td {
    padding: 4px !important;
  }
  @media print {
 
    body {
      padding: 12mm 6mm !important;
    }
    header, footer {
      display: none !important;
    }
  }
`;

          $(win.document.body)
            .find("h1, .page-title, .dashboard-title")
            .remove();

          // Insertar CSS
          const style = document.createElement("style");
          style.innerHTML = css;
          win.document.head.appendChild(style);
        },
      },
    ],
    initComplete: function () {
      $(".left-text").html("");
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
  
  $(".left-text").html('<h5 class="mb-0">Mes de Agosto</h5>');

  // Cargar meses en el select

  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});

$("#searchStudentButton").on("click", function () {
  const studentDNI = $("#studentDNIInput").val().trim();
  const nombreMes = $("#select-mes").val().trim();

  if (studentDNI === "") {
    alert("Por favor, ingrese el código del estudiante.");
    return;
  }

  if (nombreMes === "") {
    alert("Por favor, ingrese el mes a consultar.");
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByStudent",
    type: "POST",
    data: { dni_est: studentDNI, mes: nombreMes.toUpperCase() },
    beforeSend: function () {
      table.clear().draw(); // Limpia resultados anteriores
      table.buttons().disable();
      $("#searchStudentButton").prop("disabled", true);
      $("#table-resultado tbody").html(`
        <tr><td colspan="5" style="text-align:center;">
        <div class="spinner-border m-2" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </td></tr>`);

      $('#student-info-loader').show();
      $('#info-student-contain').hide();
  
      $(".left-text").html("");
      $("#result-nombre-est").html('-');
      $("#result-codigo-est").html('-');
      $("#result-grado-est").html('-');




      $(".total-asistencias").html('0');
      $(".total-inasistencias").html('0');
      $(".total-justificados").html('0');
      $(".total-tardanzas").html('0');
    },
    success: function (response) {
      if (response.status !== "success") {
        $("#table-resultado tbody").html(`
          <tr><td colspan="5" class="text-center text-muted">${
            response.message || "Error al obtener datos."
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
