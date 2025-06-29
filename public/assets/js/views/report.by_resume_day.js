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
    }},
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
    error: function (xhr, status, error) {},
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
      const option = `<option value="${
        dia.diaNumero
      }">${dia.diaNombre.toUpperCase()} - ${dia.diaNumero}</option>`;
      $selectDia.append(option);
    }
  });
});

$("#btnSearchRecord").on("click", function () {
  const nombreMes = $("#select-mes").val().trim();
  const nombreDia = $("#select-dia").val().trim();

  if (nombreMes === "") {
    alert("Por favor, ingrese el mes a consultar.");
    return;
  }

  if (nombreDia === "") {
    alert("Por favor, ingrese el día a consultar.");
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

        tbody.append(`
      <tr>
        <td>${index + 1}</td>
        <td>${grupo.Grado}</td>
        <td>${grupo.Sección}</td>
        <td>${totalGrupo}</td>
        <td>${presentes}</td>
        <td>${ausentes}</td>
        <td>${justificados}</td>
        <td>${tardanzas}</td>
        <td>${asistencia["% Asistencia"] || "0%"}</td>
      </tr>
    `);
      });

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
