let table;

let OBJ_REP_ESTUDIANTE = {
  nombres: "",
  apellidos: "",
  grado: "",
  grado_corto: "",
  seccion: "",
  mes_consulta: "",
  codigo: "",
};

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
        title: function () {
          return `REPORTE_${OBJ_REP_ESTUDIANTE.apellidos || ""}_${
            OBJ_REP_ESTUDIANTE.grado_corto || ""
          }${OBJ_REP_ESTUDIANTE.seccion || ""}_${
            OBJ_REP_ESTUDIANTE.mes_consulta || ""
          }`;
        },
        messageTop: function () {
          const fecha = new Date().toLocaleDateString();
          const estudiante =
            OBJ_REP_ESTUDIANTE.nombres + " " + OBJ_REP_ESTUDIANTE.apellidos;
          const mes = OBJ_REP_ESTUDIANTE.mes_consulta; // Captura el texto del mes
          const grado =
            OBJ_REP_ESTUDIANTE.grado + " " + OBJ_REP_ESTUDIANTE.seccion; // Asumiendo que hay un label visible con el grado

          return `Mes de Consulta: ${mes}  | Generado el: ${fecha}`;
        },
      },

      {
        extend: "pdf",
        text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
        className: "btn btn-danger mr-2 mb-1 rounded",
        title: function () {
          return `REPORTE_${OBJ_REP_ESTUDIANTE.apellidos || ""}_${
            OBJ_REP_ESTUDIANTE.grado_corto || ""
          }${OBJ_REP_ESTUDIANTE.seccion || ""}_${
            OBJ_REP_ESTUDIANTE.mes_consulta || ""
          }`;
        },
        customize: function (doc) {
          // Elimina cualquier título automático generado por DataTables/pdfMake
          if (
            doc.content[0]?.text ===
            `REPORTE_${OBJ_REP_ESTUDIANTE.apellidos || ""}_${
              OBJ_REP_ESTUDIANTE.grado_corto || ""
            }${OBJ_REP_ESTUDIANTE.seccion || ""}_${
              OBJ_REP_ESTUDIANTE.mes_consulta || ""
            }`
          ) {
            doc.content.shift(); // Quita el primer elemento si es el título
          }

          const fecha = new Date().toLocaleDateString();
          const estudiante_nombre =
            OBJ_REP_ESTUDIANTE.nombres + " " + OBJ_REP_ESTUDIANTE.apellidos;
          const estudiante_grado_seccion =
            OBJ_REP_ESTUDIANTE.grado + " " + OBJ_REP_ESTUDIANTE.seccion;
          const mes_consulta = OBJ_REP_ESTUDIANTE.mes_consulta; // Captura el texto del mes

          // Cabecera con logo y texto
          doc.content.splice(0, 0, {
            table: {
              widths: ["80%", "20%"], // Distribución proporcional del ancho
              body: [
                [
                  {
                    stack: [
                      {
                        text: "INSTITUCION EDUCATIVA MX. SAN LUIS",
                        style: "header",
                      },
                      {
                        text: `REPORTE DE ASISTENCIA`,
                        style: "subheader",
                        bold: true,
                      },
                      {
                        text: `Mes: ${mes_consulta
                          .toLowerCase()
                          .replace(/\b\w/g, (c) => c.toUpperCase())}`,
                        style: "info",
                      },
                      {
                        text: `Estudiante: ${estudiante_nombre
                          .toLowerCase()
                          .replace(/\b\w/g, (c) => c.toUpperCase())}`,
                        style: "info",
                      },
                      {
                        text: `Grado: ${estudiante_grado_seccion
                          .toLowerCase()
                          .replace(/\b\w/g, (c) => c.toUpperCase())}`,
                        style: "info",
                      },

                      {
                        text: `Generado el: ${fecha}`,
                        style: "info",
                      },
                    ],
                    alignment: "left",
                    margin: [10, 10, 10, 10],
                  },
                  {
                    image: logoBase64, // Asegúrate de que logoBase64 contiene el string base64 del logo
                    width: 55,
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
            "6%",
            "15%",
            "12%",
            "14%",
            "14%",
            "39%",
          ]; // Para 5 columnas

          // Estilos
          doc.styles.header = {
            fontSize: 14,
            margin: [0, 2, 0, 2],
            bold: true,
          };
          doc.styles.subheader = {
            fontSize: 10,
            margin: [0, 2, 0, 2],
          };
          doc.styles.info = {
            fontSize: 9,
            margin: [0, 1, 0, 1],
          };
        },
      },

      {
        extend: "print",
        text: '<i class="fas fa-print"></i> Imprimir',
        className: "btn btn-secondary mr-2 mb-1 rounded",
        customize: function (win) {
          const fecha = new Date().toLocaleDateString();
          const estudiante_nombre = $("#result-nombre-est").text();

          const estudiante_grado_seccion = $("#result-grado-est").text();
          const mes_consulta = $("#mes-buscado").text();

          // Cabecera personalizada con logo
          $(win.document.body).prepend(`
           <div style="position: relative; margin-bottom: 7px;">

              <!-- Logo en posición absoluta -->
              <img src="${base_url}/public/assets/img/static/logo_.png"
                  style="position: absolute; top: 5px; left: 40px; height: 100px; padding: 10px;">

              <!-- Título centrado -->
              <div style="text-align: center;">
                <h4 style="margin: 0; font-weight:bold;">${nombre_institucion}</h4>
                <h5 style="margin: 0;">REPORTE DE ASISTENCIA</h5>
              </div>

            </div>

            <!-- Datos adicionales -->
            <div style="text-align: center; margin-bottom: 14px;">
              <p style="margin: 0;">Mes: <strong>${mes_consulta}</strong></p>
              <p style="margin: 0;">Grado: <strong>${estudiante_grado_seccion}</strong></p>
              <p style="margin: 0;">Estudiante: <strong>${estudiante_nombre}</strong></p>
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
              width: 100% !important;
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
    Swal.fire({
      icon: "warning",
      title: "Código requerido",
      text: "Por favor, ingrese el código del estudiante.",
      confirmButtonColor: "#3085d6",
    });
    return;
  }

  if (nombreMes === "") {
    Swal.fire({
      icon: "warning",
      title: "Mes requerido",
      text: "Por favor, seleccione el mes a consultar.",
      confirmButtonColor: "#3085d6",
    });
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByStudent",
    type: "POST",
    data: { dni_est: studentDNI, mes: nombreMes.toUpperCase() },
    beforeSend: function () {
      table.clear().draw();
      table.buttons().disable();
      $("#searchStudentButton").prop("disabled", true);
      $("#table-resultado tbody").html(`
        <tr><td colspan="5" style="text-align:center;">
        <div class="spinner-border m-2" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </td></tr>`);

      $("#student-info-loader").show();
      $("#info-student-contain").hide();

      $(".left-text").html("");
      $("#result-nombre-est").html("-");
      $("#result-codigo-est").html("-");
      $("#result-grado-est").html("-");

      $(".total-asistencias").html("0");
      $(".total-inasistencias").html("0");
      $(".total-justificados").html("0");
      $(".total-tardanzas").html("0");
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

      Object.keys(OBJ_REP_ESTUDIANTE).forEach(
        (key) => (OBJ_REP_ESTUDIANTE[key] = "")
      );

      if (response.student) {
        OBJ_REP_ESTUDIANTE.nombres = response.student.nombres || "";
        OBJ_REP_ESTUDIANTE.apellidos = response.student.apellidos || "";
        OBJ_REP_ESTUDIANTE.codigo = response.student.codigo || "";
        OBJ_REP_ESTUDIANTE.grado = response.student.grado_nombre || "";
        OBJ_REP_ESTUDIANTE.grado_corto = response.student.grado || "";
        OBJ_REP_ESTUDIANTE.seccion = response.student.seccion || "";
        OBJ_REP_ESTUDIANTE.mes_consulta =
          $("#select-mes :selected").text() || "";
      }

      console.log(OBJ_REP_ESTUDIANTE);

      $("#result-nombre-est").html(
        (
          OBJ_REP_ESTUDIANTE.nombres +
          " " +
          OBJ_REP_ESTUDIANTE.apellidos
        ).toUpperCase()
      );
      $("#result-codigo-est").html(OBJ_REP_ESTUDIANTE.codigo.toUpperCase());

      $("#result-grado-est").html(
        OBJ_REP_ESTUDIANTE.grado + " - " + OBJ_REP_ESTUDIANTE.seccion
      );

      $(".left-text").html(
        `
         <span class="text-secondary" style="font-style: italic; font-size: 0.95rem;">
           ${OBJ_REP_ESTUDIANTE.nombres} ${
          OBJ_REP_ESTUDIANTE.apellidos
        } - MES DE <span id="mes-buscado">${$(
          "#select-mes option:selected"
        ).text()}</span> 
         </span>`
      );

      const attendance = response.attendance_records;
      console.log(attendance);
      

      if (!attendance.length) {
        $("#table-resultado tbody").html(`
          <tr><td colspan="6" class="text-center text-muted">No se encontraron registros de asistencia.</td></tr>
        `);
        return;
      }

      attendance.forEach((record, index) => {
        table.row.add([
          index + 1,
          formatearFechaDMY(record.fecha_asistencia),
          `${record.nombre_dia} `,
          `<span class="badge badge-${record.clase_boostrap}"><i class="${record.icon} mr-1"></i>${record.estado_asistencia}</span>`,
          formatearHoraAmPm(record.hora_entrada) || "",
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
        const abrev = registro.id_estado;

        if (abrev === 1) totalAsistencias++;
        else if (abrev === 2) totalTardanzas++;
        else if (abrev === 3) totalFaltas++;
        else if (abrev === 4) totalJustificados++;
      });

      $(".total-asistencias").html(totalAsistencias);
      $(".total-inasistencias").html(totalFaltas);
      $(".total-justificados").html(totalJustificados);
      $(".total-tardanzas").html(totalTardanzas);
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Error del servidor",
        text: "No se pudo obtener la información.",
      });
    },
    complete: function () {
      $("#searchStudentButton").prop("disabled", false);
      $("#student-info-loader").hide();
      $("#info-student-contain").show();
    },
  });
});
