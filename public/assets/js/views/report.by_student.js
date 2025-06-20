$("#searchStudentButton").on("click", function () {
  const studentCode = $("#studentCodeInput").val();



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
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
  }

  // Llama esta función en tu inicialización:
  convertImgToBase64(`${base_url}/public/assets/img/static/logo_.png`, function (base64) {
    logoBase64 = base64;
  });




  $.ajax({
    url: base_url + "/report/RecordByStudent",
    type: "POST",
    data: { code: studentCode },
    success: function (response) {
      const attendance = response.attendance_records;
      let rows = "";
      let contadorAsistencias = 0;

      attendance.forEach((record) => {
        contadorAsistencias++;
        rows += `
          <tr>
            <td class="text-center">${contadorAsistencias}</td>
            <td>${record.nombre_dia} - ${record.fecha_asistencia}</td>
            <td>${record.condicion}</td>
            <td>${record.observacion || ""}</td>
          </tr>
        `;
      });

      // Insertar en el tbody
      $("#table-resultado tbody").html(rows);

      // Destruir si ya estaba inicializado
      if ($.fn.DataTable.isDataTable("#table-resultado")) {
        $("#table-resultado").DataTable().destroy();
      }

      // Inicializar nuevamente
      $("#table-resultado").DataTable({
        dom: 'Bfrtip',
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
            className: 'btn btn-success mr-1',
            title: 'Reporte de Asistencia',
            messageTop: function () {
              return `Generado el ${new Date().toLocaleDateString()} - Estudiante: ${$("#studentCodeInput").val()}`;
            }
          },
          {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-danger mr-1',
            title: '', // evita duplicación de título
            customize: function (doc) {
              const estudiante = $("#studentCodeInput").val();
              const fecha = new Date().toLocaleDateString();

              // Cabecera con logo y texto
              doc.content.splice(0, 0, {
                table: {
                  widths: ['20%', '80%'], // Distribución proporcional del ancho
                  body: [
                    [
                      {
                        image: logoBase64, // Asegúrate de que logoBase64 contiene el string base64 del logo
                        width: 45,
                        alignment: 'right',
                        margin: [0, 0, 0, 0]
                      },
                      {
                        stack: [
                          { text: 'INSTITUCIÓN EDUCATIVA Mx. SAN LUIS', style: 'header' },
                          { text: 'Reporte de Asistencia', style: 'subheader' },
                          { text: `Estudiante: ${estudiante}    Generado el: ${fecha}`, },
                        ],
                        alignment: 'right',
                        margin: [10, 10, 10, 10]
                      },
                      
                    ]
                  ]
                },
                layout: 'noBorders',
                margin: [0, 0, 0, 10]
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
                    { text: 'Fin del reporte - Generado por el sistema', alignment: 'center', fontSize: 9 }
                  ],
                  margin: [0, 10, 0, 0]
                };
              };

              // ✔ Agrega margen a las celdas de la tabla de datos
              doc.content[doc.content.length - 1].layout = {
                paddingLeft: function () { return 9; },
                paddingRight: function () { return 9; },
                paddingTop: function () { return 3; },
                paddingBottom: function () { return 3; },

                hLineColor: function () { return "#FFFFFF"; }, // líneas horizontales
                vLineColor: function () { return "#FFFFFF"; }, // líneas verticales

                hLineWidth: function () { return 1; }, // grosor de línea horizontal
                vLineWidth: function () { return 1; }  // grosor de línea vertical
              };

              doc.content[doc.content.length - 1].table.widths = ['5%', '25%', '25%', '45%']; // Para 4 columnas


              // Estilos
              doc.styles.header = {
                fontSize: 14,
                bold: true
              };
              doc.styles.subheader = {
                fontSize: 11,
                margin: [0, 2, 0, 2]
              };
            }
          }

          ,
          {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Imprimir',
            className: 'btn btn-secondary mr-1',
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

              $(win.document.body).find('h1, .page-title, .dashboard-title').remove();

              // Insertar CSS
              const style = document.createElement('style');
              style.innerHTML = css;
              win.document.head.appendChild(style);
            }
          }
        ],

        ordering: false,        // ❌ Desactiva ordenamiento
        searching: false,       // ❌ Desactiva búsqueda
        paging: false,          // ❌ Desactiva paginación
        info: false,            // ❌ Desactiva "Mostrando X de Y"
        responsive: true,
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
      });

    },

    error: function (xhr, status, error) {
      console.error("Error al buscar estudiante:", error);
      $("#studentInfo").html("<p>Error al buscar estudiante.</p>");
    },
  });
});


