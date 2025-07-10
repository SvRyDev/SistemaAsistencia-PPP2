let table;

let OBJ_REP_GRUPO = {
  grado: "",
  grado_corto: "",
  seccion: "",
  mes_consulta: "",
};

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
    Swal.fire({
      icon: "warning",
      title: "Campos incompletos",
      text: "Por favor, selecciona grado, sección y mes.",
      confirmButtonText: "Entendido",
    });
    return;
  }

  $.ajax({
    url: base_url + "/report/RecordByGroup",
    type: "POST",
    data: { grado_id: grado, seccion_id: seccion, mes: mesId },
    dataType: "json",
    beforeSend: () => {
      $("#searchGroupButton").prop("disabled", true);
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
          `<tr><td colspan="30" class="text-center text-muted">${
            response.message || "Sin datos"
          }</td></tr>`
        );
        return;
      }

      Object.keys(OBJ_REP_GRUPO).forEach((key) => (OBJ_REP_GRUPO[key] = ""));

      OBJ_REP_GRUPO.grado = $("#select-grado :selected").text();
      OBJ_REP_GRUPO.seccion = $("#select-seccion :selected").text();
      OBJ_REP_GRUPO.mes_consulta = $("#select-mes :selected").text();

      const diasMes = obtenerDiasDelMes(mesId);
      const students = response.students || [];
      const records = response.records || [];

      // 1. Cabecera
      let filaDias = `
      <tr class="bg-success text-center">
        <th class="small">N°</th>
        <th class="small">Código</th>
        <th class="small">Nombre</th>
        <th class="small">Grado</th>
        <th class="small">Sección</th>
        ${diasMes
          .map(
            (d) =>
              `<th class="small text-center" style="white-space: pre-line;">${d.diaAbrev}\n${d.diaNumero}</th>`
          )
          .join("")}
      </tr>
    `;

      $("#table-resultado thead").html(filaDias);

      // 2. Indexar asistencias por estudiante_id + fecha
      const asistenciaMap = {};
      const asistenciaCss = {};
      records.forEach((r) => {
        const key = `${r.estudiante_id}_${r.fecha_asistencia}`;
        asistenciaMap[key] = r.abreviatura || "";
        asistenciaCss[key] = r.clase_boostrap || "";
      });

      // 3. Construir filas de estudiantes
      let htmlFilas = "";

      students.forEach((s, index) => {
        let fila = `
          <tr>
            <td class="small text-center">${index + 1}</td>
            <td class="small text-center text-nowrap">${s.codigo}</td>
            <td class="small" style="white-space: nowrap;">${s.nombres} ${
          s.apellidos
        }</td>
            <td class="small text-center">${s.grado_nombre}</td>
            <td class="small text-center">${s.seccion}</td>
            ${diasMes
              .map((d) => {
                const key = `${s.estudiante_id}_${d.fecha}`;
                const abrev = asistenciaMap[key] || "";
                const clase_boostrap = asistenciaCss[key] || "";
                const isWeekend = d.diaAbrev === "S" || d.diaAbrev === "D";
                const clase = isWeekend ? "table-warning text-dark" : "";
                return `<td class="small text-center "><span class="badge badge-${clase_boostrap}">${abrev}</span></td>`;
              })
              .join("")}
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
    error: () => {},
    complete: () => {
      $("#searchGroupButton").prop("disabled", false);
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
    title: function () {
      return (
        "REPORTE" +
        "_" +
        OBJ_REP_GRUPO.mes_consulta +
        "_" +
        OBJ_REP_GRUPO.grado +
        "_" +
        OBJ_REP_GRUPO.seccion
      );
    },
    messageTop: function () {
      const fecha = new Date().toLocaleDateString();

      const mes = OBJ_REP_GRUPO.mes_consulta; // Captura el texto del mes

      return `Mes de Consulta: ${mes}  | Generado el: ${fecha}`;
    },
  },
  {
    extend: "pdf",
    text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
    className: "btn btn-danger mr-2 mb-1",
    title: function () {
      return (
        "REPORTE" +
        "_" +
        OBJ_REP_GRUPO.mes_consulta +
        "_" +
        OBJ_REP_GRUPO.grado +
        "_" +
        OBJ_REP_GRUPO.seccion
      );
    },
    orientation: "landscape",
    customize: function (doc) {
      // Elimina el título automático de la tabla en la parte superior del PDF (si existe)
      const expectedTitle = `REPORTE_${OBJ_REP_GRUPO.mes_consulta}_${OBJ_REP_GRUPO.grado}_${OBJ_REP_GRUPO.seccion}`;
      if (doc.content[0]?.text === expectedTitle) {
        doc.content.shift();
      }

      const grado_seccion = `${OBJ_REP_GRUPO.grado} - ${OBJ_REP_GRUPO.seccion}`;
      const fecha = new Date().toLocaleDateString();
      const mes_consulta = OBJ_REP_GRUPO.mes_consulta;
      const diasMes = 31;
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
                  { text: "REPORTE DE ASISTENCIA", style: "subheader" },
                  {
                    text: `Mes: ${mes_consulta
                      .toLowerCase()
                      .replace(/\b\w/g, (c) => c.toUpperCase())} `,
                    style: "info",
                  },
                  {
                    text: `Grado y Sección: ${grado_seccion
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
                width: 47,
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

      // Reducir tamaño de fuente de toda la tabla (cabecera + filas)
      doc.content[doc.content.length - 1].style = {
        fontSize: 6, // o 6 si necesitas más compacto
      };

      // ✔ Agrega margen a las celdas de la tabla de datos
      doc.content[doc.content.length - 1].layout = {
        paddingLeft: function () {
          return 3; // Puedes reducir a 1 si necesitas más compacto
        },
        paddingRight: function () {
          return 3;
        },
        paddingTop: function () {
          return 2;
        },
        paddingBottom: function () {
          return 2;
        },
        hLineWidth: function () {
          return 0.5; // líneas horizontales más delgadas
        },
        vLineWidth: function () {
          return 0.5; // líneas verticales más delgadas
        },
        hLineColor: function () {
          return "#ccc"; // color claro para líneas
        },
        vLineColor: function () {
          return "#ccc";
        },
      };

      doc.content[doc.content.length - 1].table.widths = [
        15, // N°
        40, // Código
        170, // Nombre
        30, // Grado
        25, // Sección
        ...Array(diasMes).fill("*"), // resto de columnas por cada día
      ];

      // Estilos
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
        fontSize: 8,
        margin: [0, 1, 0, 1],
      };
      doc.styles.tableHeader = {
        fontSize: 6, // Igual al resto
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
    className: "btn btn-secondary mr-2 mb-1",
    customize: function (win) {
      const estudiante = $("#studentCodeInput").val();
      const fecha = new Date().toLocaleDateString();

      const estudiantes_grado_seccion =
        OBJ_REP_GRUPO.grado + " " + OBJ_REP_GRUPO.seccion;
      const mes_consulta = OBJ_REP_GRUPO.mes_consulta;

      // Cabecera personalizada con logo
      // Cabecera personalizada con logo
      $(win.document.body).prepend(`
            <div style="position: relative; margin: 0; margin-bottom: 7px;">
 
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
               <p style="margin: 0;">Grado y Sección: <strong>${estudiantes_grado_seccion}</strong></p>

               <p style="margin: 0;">Generado el: ${fecha}</p>
             </div>
 
           `);

      // Pie de página
      $(win.document.body).append(`
<div style="text-align:center; font-size:10px; margin-top: 20px;">
  <hr style="width:90%; margin-bottom:4px;">
  <p style="margin:0;">Fin del reporte - Generado por el sistema</p>
</div>

      `);

      // Estilos CSS
      const css = `
@page {
  size: landscape;
  margin: 12mm 12mm 20mm 12mm; /* top right bottom left */
}

      body {
      font-size: 12px;
      }
      table {
      width: 99% !important;
  
      border-collapse: collapse !important;
      }
      table th{
      background-color:rgb(162, 222, 134) !important;
      text-align: center;
      font-weight: bold;
      }
      table thead th, table tbody td {
      padding: 4px 2px !important;
      font-size: 8px !important;
      }
      @media print {

  body {
    padding: 12mm 6mm 25mm 6mm !important; /* top right bottom left */
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
