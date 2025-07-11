let table;

let OBJ_REP_DIARIO = {
  dia_consuta: 0,
  mes_consulta: 0,
  anio_consulta: 0,
}
let logoBase64 = "";

$(document).ready(function () {

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


  table = $("#table-result").DataTable({
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

    buttons: dtButtons,
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
      url: url_plugins_datable_esp,
    },
  });


  // Cargar datos iniciales con AJAX
  $.ajax({
    url: base_url + "/setting/getConfig",
    type: "POST",
    dataType: "json",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    success: function (response) {
      if (response.status === "success") {
        const setting = response.setting;
        $("#year-academic").val(setting.academic_year);
        OBJ_REP_DIARIO.anio_consulta = setting.academic_year;
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      alert("No se pudieron cargar el año academico.");
    },
  });

  $.ajax({
    url: base_url + "/student/getTotalStudents", // tu ruta correcta
    type: "GET",
    dataType: "json",
    beforeSend: function () {
      $("#total-estudiantes").html(
        '<div class="spinner-grow spinner-grow-sm" role="status">   <span class="sr-only">Loading...</span> </div>'
      );
    },
    success: function (response) {
      console.log("Total estudiantes:", response.total_estudiantes);
      $("#total-estudiantes").html(response.total);



    },
    error: function (xhr, status, error) { },
  });

  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});

$("#select-mes").on("change", function () {
  const mesSeleccionado = parseInt($(this).val());
  if (!mesSeleccionado) return;

  const diasDelMes = obtenerDiasDelMes(mesSeleccionado);
  const $selectDia = $("#select-dia");

  $selectDia.empty();
  $selectDia.append('<option value="">-- Seleccionar --</option>');

  diasDelMes.forEach((dia) => {
    const diaSemana = new Date(dia.fecha).getDay(); // Correcto: 0 = Domingo, 6 = Sábado

    if (diaSemana !== 5 && diaSemana !== 6) {
      // Excluye sábado y domingo
      const option = `<option value="${dia.diaNumero
        }">${dia.diaNombre.toUpperCase()} - ${dia.diaNumero}</option>`;
      $selectDia.append(option);
    }
  });
});

$("#btnSearchRecord").on("click", function () {
  const nombreMes = $("#select-mes").val().trim();
  const nombreDia = $("#select-dia").val().trim();

if (nombreMes === "") {
  Swal.fire({
    icon: "warning",
    title: "Mes requerido",
    text: "Por favor, ingrese el mes a consultar.",
    confirmButtonText: "Entendido",
  });
  return;
}

if (nombreDia === "") {
  Swal.fire({
    icon: "warning",
    title: "Día requerido",
    text: "Por favor, ingrese el día a consultar.",
    confirmButtonText: "Entendido",
  });
  return;
}


  $.ajax({
    url: base_url + "/report/RecordByResumeDay",
    type: "GET",
    data: {
      mes: parseInt(nombreMes),
      dia: parseInt(nombreDia),
    },
    beforeSend: function () {
      $("#table-result tbody").html(
        '<tr><td colspan="9">Cargando...</td></tr>'
      );
    },
    success: function (response) {
      const tbody = $("#table-result tbody");
      tbody.empty();

      if (response.status !== "success") {
        alert(response.message || "Ocurrió un error.");
        return;
      }

      const estudiantes = response.list_students || [];
      const registros = response.data || [];

      if (estudiantes.length === 0) {
        tbody.append(
          '<tr><td colspan="9">No hay grupos registrados.</td></tr>'
        );
        return;
      }

      OBJ_REP_DIARIO.dia_consuta = $('#select-dia').val();
      OBJ_REP_DIARIO.mes_consulta = $('#select-mes').val();
      // Mapear asistencia para acceso rápido
      const mapAsistencia = {};
      registros.forEach((item) => {
        const key = `${item.Grado}_${item.Sección}`;
        mapAsistencia[key] = item;
      });

      // Inicializar contadores
      let totalEstudiantes = 0;
      let totalPresentes = 0;
      let totalTardanzas = 0;
      let totalAusentes = 0;
      let totalJustificados = 0;

      // Recorremos los grupos
      table.clear();
      estudiantes.forEach((grupo, index) => {
        const key = `${grupo.Grado}_${grupo.Sección}`;
        const asistencia = mapAsistencia[key] || {};

        const presentes = parseInt(asistencia.Presentes || 0);
        const tardanzas = parseInt(asistencia.Tardanzas || 0);
        const ausentes = parseInt(asistencia.Ausentes || 0);
        const justificados = parseInt(asistencia.Justificados || 0);
        const totalGrupo = parseInt(grupo.Total_Estudiantes);

        totalEstudiantes += totalGrupo;
        totalPresentes += presentes;
        totalTardanzas += tardanzas;
        totalAusentes += ausentes;
        totalJustificados += justificados;



        table.row.add([
          index + 1,
          grupo.Grado,
          grupo.Sección,
          totalGrupo,
          presentes,
          ausentes,
          justificados,
          tardanzas,
          asistencia["% Asistencia"] || "0%"
        ]);

      });


      table.draw(false);
      table.buttons().enable();

      // Usar el total de estudiantes cargado previamente desde el DOM
      const totalEstudiantesGeneral =
        parseInt($("#total-estudiantes").text()) || 0;
      const totalAsistenciasEfectivas = totalPresentes + totalTardanzas;

      const porcentajeAsistencia =
        totalEstudiantesGeneral > 0
          ? (
            (totalAsistenciasEfectivas / totalEstudiantesGeneral) *
            100
          ).toFixed(2) + "%"
          : "0%";

      // Actualizar resumen general
      $("#total-estudiantes").text(totalEstudiantes);
      $("#total-asistencias").text(totalAsistenciasEfectivas);
      $("#total-tardanzas").text(totalTardanzas);
      $("#total-ausentes").text(totalAusentes);
      $("#total-justificados").text(totalJustificados);
      $("#total-porcentaje").text(porcentajeAsistencia);


    },

    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Ocurrió un error al consultar el reporte.");
    },
  });
});


const dtButtons = [
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
      return `REP_RESUMEN_GENERAL_${OBJ_REP_DIARIO.dia_consuta || ""}-${OBJ_REP_DIARIO.mes_consulta || ""
        }-${OBJ_REP_DIARIO.anio_consulta || ""}`;
    },
    messageTop: function () {
      const fecha = new Date().toLocaleDateString();
        const fecha_consulta = `${OBJ_REP_DIARIO.dia_consuta || ""}/${OBJ_REP_DIARIO.mes_consulta || ""
        }/${OBJ_REP_DIARIO.anio_consulta || ""}`;
      
      return `Fecha de Consulta: ${fecha_consulta}  | Generado el: ${fecha}`;
    },
  },

  {
    extend: "pdf",
    text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
    className: "btn btn-danger mr-2 mb-1 rounded",
    title: function () {
      return `REP_RESUMEN_GENERAL_${OBJ_REP_DIARIO.dia_consuta || ""}-${OBJ_REP_DIARIO.mes_consulta || ""
        }-${OBJ_REP_DIARIO.anio_consulta || ""}`;
    },
    customize: function (doc) {
      // Elimina cualquier título automático generado por DataTables/pdfMake
      if (
        doc.content[0]?.text ===
        `REP_RESUMEN_GENERAL_${OBJ_REP_DIARIO.dia_consuta || ""}-${OBJ_REP_DIARIO.mes_consulta || ""
        }-${OBJ_REP_DIARIO.anio_consulta || ""}`
      ) {
        doc.content.shift(); // Quita el primer elemento si es el título
      }

      const fecha = new Date().toLocaleDateString();

      const fecha_consulta = `${OBJ_REP_DIARIO.dia_consuta || ""}/${OBJ_REP_DIARIO.mes_consulta || ""
        }/${OBJ_REP_DIARIO.anio_consulta || ""}`;

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
                    text: `REPORTE DE ASISTENCIA - RESUMEN GENERAL`,
                    style: "subheader",
                    bold: true,
                  },
                  {
                    text: `Fecha de Consulta: ${fecha_consulta
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
        "7%",
        "11%",
        "9%",
        "12%",
        "12%",
        "12%",
        "12%",
        "12%",
        "12%",
      ]; // Para 5 columnas

      // Estilos
            // Reducir tamaño de fuente de toda la tabla (cabecera + filas)
      doc.content[doc.content.length - 1].style = {
        fontSize: 7, // o 6 si necesitas más compacto
      };
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
       doc.styles.tableHeader = {
        fontSize: 7, // Igual al resto
        bold: true,
        color: "#fff", // Negro
        fillColor: "#164363", // Fondo gris claro opcional
        alignment: "center",
      };
    },
  },

  {
    extend: "print",
    text: '<i class="fas fa-print"></i> Imprimir',
    className: "btn btn-secondary mr-2 mb-1 rounded",
    customize: function (win) {
      const fecha = new Date().toLocaleDateString();

      const fecha_consulta = OBJ_REP_DIARIO.dia_consuta + '/' + OBJ_REP_DIARIO.mes_consulta +'/'+ OBJ_REP_DIARIO.anio_consulta;

      // Cabecera personalizada con logo
      $(win.document.body).prepend(`
           <div style="position: relative; margin-bottom: 10px;">

              <!-- Logo en posición absoluta -->
              <img src="${base_url}/public/assets/img/static/logo_.png"
                  style="position: absolute; top: 5px; left: 40px; height: 100px; padding: 10px;">

              <!-- Título centrado -->
              <div style="text-align: center;">
                <h4 style="margin: 0; font-weight:bold;">${nombre_institucion}</h4>
                <h5 style="margin: 0;">REPORTE DE ASISTENCIA - RESUMEN DIARIO</h5>
              </div>

            </div>

            <!-- Datos adicionales -->
            <div style="text-align: center; margin-bottom: 24px;">
              <p style="margin: 0;">Fecha de Consulta: <strong>${fecha_consulta}</strong></p>

              <p style="margin: 0;">Generado el: ${fecha}</p>
            </div>

          `);

      // Pie de página
      $(win.document.body).append(`
            <div style="position:fixed; bottom:20px; left:0; right:0; text-align:center; font-size:10px;">
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
];