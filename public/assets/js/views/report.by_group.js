let table;

$(document).ready(function () {
  let logoBase64 = "";

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

  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});

$("#searchGroupButton").on("click", function () {
  const grado = $("#select-grado").val();
  const seccion = $("#select-seccion").val();
  const mesId = parseInt($("#select-mes").val());

  if (!grado || !seccion || !mesId) {
    alert("Por favor, selecciona grado, sección y mes.");
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByGroup",
    type: "POST",
    data: { grado_id: grado, seccion_id: seccion, mes: mesId },
    dataType: "json",
    beforeSend: () => {
      $('#searchGroupButton').prop('disabled', true);
      table.clear().draw();
      table.buttons().disable();
      table.destroy();


      $("#table-resultado tbody").html(
        `<tr><td colspan="30" class="text-center text-muted"><div class="spinner-grow" role="status">
  <span class="sr-only">Loading...</span>
</div></td></tr>`
      );
    },
    success: function (response) {
      if (response.status !== "success") {
        $("#table-resultado tbody").html(
          `<tr><td colspan="30" class="text-center text-muted">${response.message || "Sin datos"
          }</td></tr>`
        );
        return;
      }

      const diasMes = obtenerDiasDelMes(mesId);
      const students = response.students || [];
      const records = response.records || [];

      // 1. Cabecera
      let filaDias = `
  <tr class="bg-success text-center">
    <th class="small" rowspan="2">N°</th>
    <th class="small" rowspan="2">Código</th>
    <th class="small" rowspan="2">Nombre</th>
    <th class="small" rowspan="2">Grado</th>
    <th class="small" rowspan="2">Sección</th>
    ${diasMes
          .map((d) => `<th class="small text-center">${d.diaAbrev}</th>`)
          .join("")}
  </tr>
`;

      let filaFechas = `
      <tr class="bg-light text-center">
        ${diasMes
          .map(
            (d) =>
              `<th class="small">${d.diaNumero}/${("0" + mesId).slice(-2)}</th>`
          )
          .join("")}
      </tr>
    `;

      $("#table-resultado thead").html(filaDias + filaFechas);

      // 2. Indexar asistencias por estudiante_id + fecha
      const asistenciaMap = {};
      records.forEach((r) => {
        const key = `${r.estudiante_id}_${r.fecha_asistencia}`;
        asistenciaMap[key] = r.abreviatura || "";
      });

      // 3. Construir filas de estudiantes
      let htmlFilas = "";

      students.forEach((s, index) => {
        let fila = `
          <tr>
            <td class="small text-center">${index + 1}</td>
            <td class="small text-center">${s.codigo}</td>
            <td class="small" style="white-space: nowrap;">${s.nombres} ${s.apellidos
          }</td>
            <td class="small text-center">${s.grado_nombre}</td>
            <td class="small text-center">${s.seccion}</td>
            ${diasMes.map((d) => {
            const key = `${s.estudiante_id}_${d.fecha}`;
            const abrev = asistenciaMap[key] || "-";
            const isWeekend = d.diaAbrev === "S" || d.diaAbrev === "D";
            const clase = isWeekend ? "table-warning text-dark" : "";
            return `<td class="small text-center ${clase}">${abrev}</td>`;
          }).join("")}
          </tr>
        `;
        htmlFilas += fila;
      });

      $("#table-resultado tbody").html(htmlFilas);

      table = $("#table-resultado").DataTable({
        dom: `t`,

        buttons: dtButtons,
        initComplete: function () {
          $(".left-text").html("");
            this.api().buttons().container().appendTo("#table-buttons-wrapper");
      
          this.api().buttons().enable(); // ✅ esto funciona
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
    },
    error: () => { },
    complete: () => {
      $('#searchGroupButton').prop('disabled', false);
    },
  });
});

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

const dtButtons = [
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
    orientation: "landscape",
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
size: landscape;
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

      $(win.document.body).find("h1, .page-title, .dashboard-title").remove();

      // Insertar CSS
      const style = document.createElement("style");
      style.innerHTML = css;
      win.document.head.appendChild(style);
    },
  },
];
