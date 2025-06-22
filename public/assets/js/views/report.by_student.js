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
    dom: '<"d-flex justify-content-end mb-3"B>frtip',
    buttons: [
      {
        extend: "copy",
        text: '<i class="fas fa-copy"></i> Copiar',
        className: "btn btn-light mr-2 mb-1",
      },
      {
        extend: "csv",
        text: '<i class="fas fa-file-csv"></i> Exportar a CSV',
        className: "btn btn-info mr-2 mb-1",
      },
      {
        extend: "excel",
        text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
        className: "btn btn-success mr-2 mb-1",
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
        className: "btn btn-danger mr-2 mb-1",
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
            "25%",
            "25%",
            "45%",
          ]; // Para 4 columnas

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
        className: "btn btn-secondary mr-2 mb-1",
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
});

$("#searchStudentButton").on("click", function () {
  const studentCode = $("#studentCodeInput").val().trim();

  if (studentCode === "") {
    alert("Por favor, ingrese el código del estudiante.");
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByStudent",
    type: "POST",
    data: { code: studentCode },
    beforeSend: function () {
      table.clear().draw(); // Limpia resultados anteriores
      table.buttons().disable();
      $("#searchStudentButton").prop("disabled", true);
      $("#table-resultado tbody").html(`
        <tr><td colspan="4" style="text-align:center;">
        <div class="spinner-border m-2" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </td></tr>`);

      $("#info-student-contain").addClass(
        "animate__animated animate__fadeOut animate__faster"
      );
    },
    success: function (response) {
      if (response.status !== "success") {
        $("#table-resultado tbody").html(`
          <tr><td colspan="4" class="text-center text-muted">${
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

      $("#result-grado-est").html(response.student.grado);
      $("#result-seccion-est").html(response.student.seccion);

      
      const attendance = response.attendance_records;

      if (!attendance.length) {
        $("#table-resultado tbody").html(`
          <tr><td colspan="4" class="text-center text-muted">No se encontraron registros de asistencia.</td></tr>
        `);
        return;
      }

    

      attendance.forEach((record, index) => {
        table.row.add([
          index + 1,
          `${record.nombre_dia} - ${record.fecha_asistencia}`,
          record.condicion,
          record.observacion || "",
        ]);
      });

      table.draw(false);
      table.buttons().enable();
    },
    error: function () {
      alert("Error del servidor");
    },
    complete: function () {
      $("#searchStudentButton").prop("disabled", false);
      $("#info-student-contain").removeClass("animate__fadeOut");
      $("#info-student-contain").addClass("animate__fadeIn");
    },
  });
});
