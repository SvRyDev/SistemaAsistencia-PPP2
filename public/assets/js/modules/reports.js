(function () {
  //////////////////////////////////////////////////

  const base_url_module = base_url + "/reports";

  $.ajax({
    url: base_url_module + "/loadFormSearchStudents",
    method: "POST",

    beforeSend: function () {},
    success: function (response) {
      
      // Arrays de datos recibidos
      let array_periodo_anual = response.periodo_anuales;
      let array_grados = response.grados;
      let array_niveles = response.niveles;
      let array_secciones = response.secciones;
      // Función para agregar opciones a un <select>
      function appendOptionsToSelect(
        selector,
        items,
        valueKey,
        textKey,
        additionalText
      ) {
        $(selector).empty(); // Limpia las opciones existentes
        $(selector).append(
          '<option val="" selected> --- TODO --- </option>'
        );
        items.forEach(function (item) {
          var option = $("<option></option>")
            .val(item[valueKey])
            .text(
              item[textKey].toUpperCase() +
                (additionalText ? " - " + item[additionalText] : "")
            );
          $(selector).append(option);
        });
      }

      // Inicialización de combos
      appendOptionsToSelect(
        "#g_e_periodo",
        array_periodo_anual,
        "id_periodo_anual",
        "nombre_año"
      );

      appendOptionsToSelect(
        "#g_e_nivel",
        array_niveles,
        "id_nivel",
        "nombre_nivel"
      );

      appendOptionsToSelect("#g_e_grado", [], "id_grado", "nombre_grado");
      appendOptionsToSelect("#g_e_seccion", [], "id_seccion", "codigo_seccion");

      // Manejo de cambios en combos
      $("#g_e_periodo").on("change", function () {
        let selectedPeriodo = parseInt($(this).val());

        // Filtrar niveles disponibles según el periodo seleccionado
        let filteredNiveles = array_niveles.filter(
          (nivel) => nivel.id_periodo_anual === selectedPeriodo
        );

        // Actualizar niveles
        appendOptionsToSelect(
          "#g_e_nivel",
          array_niveles,
          "id_nivel",
          "nombre_nivel"
        );

        appendOptionsToSelect("#g_e_grado", [], "id_grado", "nombre_grado");
        appendOptionsToSelect(
          "#g_e_seccion",
          [],
          "id_seccion",
          "codigo_seccion"
        );

        $("#g_e_nivel").trigger("change"); // Forzar el cambio en el nivel para actualizar los grados
      });

      // Manejo de cambios en combos
      $("#g_e_nivel").on("change", function () {
        let selectedNivel = parseInt($(this).val());
        let filteredGrados = array_grados.filter(
          (grado) => grado.id_nivel === selectedNivel
        );
        appendOptionsToSelect(
          "#g_e_grado",
          filteredGrados,
          "id_grado",
          "nombre_grado"
        );
        $("#g_e_grado").trigger("change"); // Forzar cambio para actualizar secciones
      });

      $("#g_e_grado").on("change", function () {
        let selectedGrado = parseInt($(this).val());
        let filteredSecciones = array_secciones.filter(
          (seccion) => seccion.id_grado === selectedGrado
        );
        appendOptionsToSelect(
          "#g_e_seccion",
          filteredSecciones,
          "id_seccion",
          "codigo_seccion"
        );
      });

      // Seleccionar valores iniciales si es necesario
      $("#g_e_nivel").trigger("change");
    },

    error: function (xhr, status, error) {},
  });


  $('#grupo-est--details').hide();
  $('#loading--spinner').hide();
  $("#form-search-group-students").on("submit", function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario

    var form = $(this);

    // Validar el formulario antes de enviarlo
    if (!validateForm(form[0])) {
      return;
    }

    $.ajax({
      url: base_url_module + "/searchStudents",
      method: "POST",
      data: form.serialize(),
      beforeSend: function () {
        $('#grupo-est--details').hide();
        $('#loading--spinner').show();
      },
      success: function (response) {
        $('#loading--spinner').hide();
        $('#grupo-est--details').fadeIn();

        // Verificar si la respuesta tiene datos válidos
        if (!Array.isArray(response)) {
          console.error("La respuesta no tiene el formato esperado.");
          return;
        }

        // Destruir la tabla si ya está inicializada
        if ($.fn.DataTable.isDataTable("#tabla_search_estudiantes")) {
          $("#tabla_search_estudiantes").DataTable().destroy();
        }

        // Inicializar DataTable
        $("#tabla_search_estudiantes").DataTable({
          processing: true,
          serverSide: false,
          autoWidth: true,
          responsive: true,

          data: response, // Cargar los datos en la tabla

          language: {
            url:
              base_url +
              "/public/assets/libs/data-table-js/languaje/Spanish.json",
          },

          // Definir las columnas de la tabla
          columns: [
            { title: "ID del Estidiante", data: "id_estudiante" },
            { title: "Nivel", data: "nivel_desc" },
            { title: "Grado", data: "grado" },
            { title: "Seccion", data: "seccion" },
            { title: "Fecha de Matricula", data: "periodo_academico" },
          ],
        });

        // Habilitar el botón de exportación después de cargar los datos
        $("#btn--export-excel").removeClass("disabled");
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud:", error);
        alert("Hubo un problema al cargar los datos. Inténtalo de nuevo.");
      },
    });
  });

  $("#btn--export-excel").on("click", function (e) {
    // Cargar el archivo Excel
    var xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      base_url +
        "/public/assets/templates-exports/plantilla_nomina_matricula.xlsx",
      true
    );
    xhr.responseType = "arraybuffer";

    xhr.onload = function (e) {
      var data = new Uint8Array(xhr.response);

      // Crear una instancia de Workbook de ExcelJS
      var workbook = new ExcelJS.Workbook();
      workbook.xlsx.load(data).then(function () {
        var worksheet = workbook.getWorksheet(1); // Obtener la primera hoja de trabajo

        // Capturar los datos de la tabla HTML
        var table = document.getElementById("tabla_search_estudiantes");
        var rows = table.getElementsByTagName("tr");

        // Obtener la fila de plantilla (A12:AS12) que contiene la estructura
        var templateRow = worksheet.getRow(12); // Fila 12 (A12:AS12)
        var templateCells = templateRow._cells; // Obtener las celdas de la fila de plantilla

        // Obtener las celdas combinadas (rango de celdas combinadas) en la hoja
        var mergedRanges = worksheet.mergedCells;

        var init_row = 13; // Empezar a pegar datos a partir de la fila 13

        // Recorrer las filas de la tabla HTML
        for (var i = 1; i < rows.length; i++) {
          // Comenzar desde 1 para saltar el encabezado
          var cells = rows[i].getElementsByTagName(i === 0 ? "th" : "td"); // Obtener las celdas de la fila

          // Usar addRow para agregar una nueva fila en el archivo Excel
          var newRow = worksheet.addRow([]); // Añadir una fila vacía

          // Copiar el formato de la fila de plantilla para cada nueva fila de datos
          for (var colIndex = 0; colIndex < templateCells.length; colIndex++) {
            var templateCell = templateCells[colIndex];
            var newCell = newRow.getCell(colIndex + 1); // La columna empieza desde 1

            // Copiar el formato de la celda de plantilla a la nueva fila
            newCell.style = templateCell.style; // Copiar estilo (color, bordes, etc.)
          }

          // Asignar los valores de las celdas de la tabla HTML a las celdas correspondientes en Excel
          for (var j = 0; j < cells.length; j++) {
            // Asignar el valor de la celda HTML al valor de la celda Excel
            var excelCell = newRow.getCell(j + 1); // Columna comienza desde 1

            // Asignar el valor de la celda HTML
            excelCell.value = cells[j].innerText;

            // Asignar el borde negro a cada celda
            var borderStyle = {
              top: { style: "thin", color: { argb: "FF000000" } },
              left: { style: "thin", color: { argb: "FF000000" } },
              bottom: { style: "thin", color: { argb: "FF000000" } },
              right: { style: "thin", color: { argb: "FF000000" } },
            };
            excelCell.border = borderStyle;
          }

          // Aplicar las combinaciones de celdas a la nueva fila de datos
          if (mergedRanges && mergedRanges.length > 0) {
            mergedRanges.forEach(function (mergedRange) {
              // Verificar si el rango de celdas combina con la nueva fila
              var range = mergedRange.split(":");
              var startCell = range[0];
              var endCell = range[1];

              var startRow = parseInt(startCell.replace(/[A-Z]/g, "")); // Extraer el número de la fila
              var endRow = parseInt(endCell.replace(/[A-Z]/g, ""));
              var startCol = startCell.replace(/[0-9]/g, ""); // Extraer la letra de la columna
              var endCol = endCell.replace(/[0-9]/g, "");

              // Aplicar las combinaciones de celdas a la nueva fila
              if (startRow <= init_row + i && endRow >= init_row + i) {
                worksheet.mergeCells(
                  startCol + (init_row + i),
                  startRow,
                  endCol + (init_row + i),
                  endRow
                );
              }
            });
          }
        }

        // Exportar el archivo modificado
        workbook.xlsx.writeBuffer().then(function (buffer) {
          var blob = new Blob([buffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          var link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = "archivo_modificado.xlsx";
          link.click();
        });
      });
    };

    xhr.send();
  });

  $("#matricula--details").hide();

 

  $("#btn--export").on("click", function () {
    // Realizamos una solicitud AJAX

    const id_estud = $("#fm_est_id").html();
    window.location.href =
      base_url + "/export/exportFichaMatricula/" + id_estud;
  });

  //START - CONFIGURACION BUSCADOR ESTUDIANTE
  $("#form-search--student").on("submit", function (event) {
    event.preventDefault();

    var form = $(this);
    $.ajax({
      url: base_url + "/matricula/loadStudentBySearcher",
      method: "POST",
      data: form.serialize(),
      beforeSend: function () {
        $("#loading--spinner").html(
          '<div class="spinner-border m-auto" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
        $("#loading--spinner").show();
        $("#matricula--details").hide();
        $("#table-lista-matriculas-est tbody").empty();
      },
      success: function (response) {
        if (response.respuesta.estudiante.length != 0) {
          $("#matricula--details").fadeIn();
          $("#loading--spinner").hide();
    

          const estudiante = response.respuesta.estudiante;
          const matriculas = response.respuesta.matriculas;

          $("#fm_est_id").html(estudiante.id_estudiante);
          $("#fm_est_doc_identidad").html(estudiante.documento_identificacion);
          $("#fm_est_nombre_completo").html(
            estudiante.apellido_paterno +
              " " +
              estudiante.apellido_materno +
              " " +
              estudiante.nombres
          );
          $("#fm_est_fech_nacimiento").html(estudiante.fecha_nacimiento);

          matriculas.forEach((matricula) => {
            const fila = `
              <tr>
                <td class="fw-light" >${matricula.periodo_academico.toUpperCase()}</td>
                <td class="fw-light" >${matricula.nivel_desc.toUpperCase()}</td>
                <td class="fw-light" >${matricula.grado.toUpperCase()}</td>
                <td class="fw-light" >${matricula.situacion_final_anio_desc.toUpperCase()}</td>
              </tr>`;
            $("#table-lista-matriculas-est tbody").append(fila);
          });
        } else {
          $("#loading--spinner").html("Sin Resultados");
   
        }

        console.log(response);
      },

      error: function (xhr, status, error) {},
    });
  });

  //END - CONFIGURACION BUSCADOR ESTUDIANTE

  //////////////////////////////////////////////////
})();
