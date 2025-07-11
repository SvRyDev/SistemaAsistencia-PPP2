$(document).ready(function () {
  const fechaActual = getFechaActual(); // "YYYY-MM-DD"
  const fechaFormateada = formatearFechaCompleta(fechaActual); // "3 de julio"
  
  $("#fechaDistribucion").text(fechaFormateada);
  $("#fechaJustificaciones").text(fechaFormateada);
  $("#fechaRegistro").text(fechaFormateada);
  
  const chartColors = {
    puntual: "#28a745", // verde éxito (borde)
    puntualBg: "rgba(40, 167, 70, 0.63)", // fondo más claro

    tarde: "#17a2b8", // celeste info (borde)
    tardeBg: "rgba(23, 163, 184, 0.62)", // fondo más claro

    falta: "#dc3545",
    justificado: "#ffc107",

    asistido: "rgba(28, 112, 201, 0.7)", // línea azul
    asistidoBg: "rgba(0, 123, 255, 0.59)", // fondo claro línea
  };

  function fetchAttendanceData() {
    $.ajax({
      url: base_url + "/dashboard/getAttendancePercentDaily",
      method: "POST",
      dataType: "json",
      success: function (response) {
        renderBarChart(response);
        renderPieChart(response);
        renderMonthlyBarChart(response); // en vez de renderMonthlyPieChart
        renderJustificacionesDiarias(response); // ← aquí
        renderCards(response);
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar datos:", error);
      },
    });
      // -------------------------------
  // Cargar registro del dia
  // -------------------------------
  $.ajax({
    url: base_url + "/attendance/getListRegisteredLastDay",
    type: "POST",
    dataType: "json",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    success: function (response) {
      
  $('#listaAsistencia').empty();
      if (response.status === "success" && Array.isArray(response.list_attendance)) {
        const lista = document.getElementById("listaAsistencia");
        lista.innerHTML = ""; // Limpiar lista anterior
        contadorAsistencias = 0;

        response.list_attendance.forEach((student) => {
          contadorAsistencias++;
        
          const nuevoItem = document.createElement("div");
          nuevoItem.className =
            "list-group-item list-group-item-action pt-2 pb-2";
        
          nuevoItem.innerHTML = `
            <div class="container-fluid">
              <div class="row text-truncate">
                <div class="col-1 text-muted d-none d-sm-block">
                  <small>${contadorAsistencias}</small>
                </div>
        
                <div class="col-2 text-muted d-none d-sm-block">
                  <span class="text-dark">${student.codigo}</span>
                </div>
        
                <div class="col-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  <span>${student.nombres} ${student.apellidos}</span>
                </div>
        
                <div class="col-1 text-muted d-none d-sm-block">
                  <span class="text-dark">${student.grado_orden || "--"} ${student.seccion || "--"}</span>
                </div>
        
                <div class="col-1 text-muted d-none d-sm-block">
                  <span class="text-dark">${getHoraMinuto(student.hora_actual) || "--:--"}</span>
                </div>
        
                <div class="col-1 text-right">
                  <span data-id="${student.id_estado}" class="badge badge-${student.clase_boostrap}">
                    ${student.nombre_estado}
                  </span>
                </div>
              </div>
            </div>
          `;
        
          lista.appendChild(nuevoItem);
        });
        

      
      } else {
        console.warn("Respuesta vacía o inválida:", response);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar asistencia:", error);
    },
  });
  }

  function renderCards(data) {
    const summary = data.attendance_summary || [];
    if (summary.length === 0) return;
  
    const last = summary[summary.length - 1];
    const hoy = getFechaActual(); // "YYYY-MM-DD"
  
    const esHoy = last.fecha === hoy;
  
    if (esHoy) {
      $('#total-estudiantes').text(last.total_estudiantes);
      $('#asistencias-dia').text(last.total_puntual);
      $('#tardanzas-dia').text(last.total_tarde);
      $('#justificados-count').text(last.total_justificados);
  
      $('#asistencias-porcentaje').text(last.porcentaje_puntual);
      $('#tardanzas-porcentaje').text(last.porcentaje_tarde);
      $('#justificados-percent').text(last.porcentaje_justificado);
    } else {
      $('#total-estudiantes').text('0');
      $('#asistencias-dia').text('0');
      $('#tardanzas-dia').text('0');
      $('#justificados-count').text('0');
  
      $('#asistencias-porcentaje').text('0');
      $('#tardanzas-porcentaje').text('0');
      $('#justificados-percent').text('0');
    }
  
    const fechaFormateada = formatearFechaCorta(last.fecha);
    $('#asistencias-fecha').text(fechaFormateada);
    $('#tardanzas-fecha').text(fechaFormateada);
    $('#justificaciones-fecha').text(fechaFormateada);
  }
  


  function renderBarChart(data) {
    const summary = data.attendance_summary || [];
    const maxSlots = 15;
    const trimmed = summary.slice(-maxSlots);

    const fechas = trimmed.map((r) => r.fecha);
    const puntual = trimmed.map((r) => parseInt(r.total_puntual) || 0);
    const tarde = trimmed.map((r) => parseInt(r.total_tarde) || 0);
    const asistidos = puntual.map((v, i) => v + tarde[i]);

    const faltan = maxSlots - fechas.length;
    const dias = Array(faltan).fill("").concat(fechas);
    const puntualFull = Array(faltan).fill(null).concat(puntual);
    const tardeFull = Array(faltan).fill(null).concat(tarde);
    const asistidosFull = Array(faltan).fill(null).concat(asistidos);

    const datasets = [
      {
        type: "bar",
        label: "Puntual",
        data: puntualFull,
        backgroundColor: chartColors.puntualBg,
        borderColor: chartColors.puntual,
        borderWidth: 2,
      },
      {
        type: "bar",
        label: "Tarde",
        data: tardeFull,
        backgroundColor: chartColors.tardeBg,
        borderColor: chartColors.tarde,
        borderWidth: 2,
      },
      {
        type: "line",
        label: "Total Asistidos",
        data: asistidosFull,
        borderColor: chartColors.asistido,
        backgroundColor: chartColors.asistidoBg,
        fill: false,
        lineTension: 0.4,
        borderWidth: 2,
      },
    ];

    if (chart) {
      chart.data.labels = dias;
      chart.data.datasets.forEach((ds, i) => {
        ds.data = datasets[i].data;
      });
      chart.update();
    } else {
      chart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: dias,
          datasets: datasets,
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { position: "bottom" },
          tooltips: { mode: "index", intersect: false },
          scales: {
            yAxes: [
              {
                stacked: true,
                ticks: { beginAtZero: true, stepSize: 1, precision: 0 },
                scaleLabel: {
                  display: true,
                  labelString: "Número de estudiantes",
                },
              },
            ],
            xAxes: [
              {
                stacked: true,
                scaleLabel: { display: true, labelString: "Fecha" },
                ticks: {
                  callback: (value) => (value === "" ? "" : value),
                },
              },
            ],
          },
        },
      });
    }
  }

  function renderPieChart(data) {
    const summary = data.attendance_summary;
    if (!summary || summary.length === 0) return;
  
    const last = summary[summary.length - 1];
    const hoy = getFechaActual(); // "YYYY-MM-DD"
    const esHoy = last.fecha === hoy;
  
    let puntual = 0, tarde = 0, justificado = 0, totalEstudiantes = 0;
  
    if (esHoy) {
      puntual = parseInt(last.total_puntual) || 0;
      tarde = parseInt(last.total_tarde) || 0;
      justificado = parseInt(last.total_justificados) || 0;
      totalEstudiantes = parseInt(last.total_estudiantes) || 0;
    }
  
    const asistido = puntual + tarde;
    const registrados = asistido + justificado;
    const restantes = totalEstudiantes - registrados;
  
    const dataPie = [asistido, tarde, justificado, restantes];
  
    const labels = ["Temprano", "Tarde", "Justificado", "Restantes"];
    const backgroundColors = [
      chartColors.puntual,
      chartColors.tarde,
      chartColors.justificado,
      chartColors.restantes || "#dee2e6",
    ];
  
    if (pieChart) {
      pieChart.data.datasets[0].data = dataPie;
      pieChart.update();
    } else {
      pieChart = new Chart(ctxPie, {
        type: "pie",
        data: {
          labels: labels,
          datasets: [
            {
              data: dataPie,
              backgroundColor: backgroundColors,
              borderColor: "#fff",
              borderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: "bottom" },
            tooltip: {
              callbacks: {
                label: function (context) {
                  const value = context.parsed;
                  const total = dataPie.reduce((a, b) => a + b, 0);
                  const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                  return `${context.label}: ${value} (${percent}%)`;
                },
              },
            },
          },
        },
      });
    }
  }
  

  function renderMonthlyBarChart(data) {
    const summary = data.attendance_summary || [];
    if (!summary.length) return;
  
    // Agrupar por mes: YYYY-MM
    const grouped = {};
    summary.forEach((item) => {
      const mes = item.fecha.slice(0, 7);
      if (!grouped[mes]) {
        grouped[mes] = {
          puntual: 0,
          tarde: 0,
          justificado: 0,
        };
      }
      grouped[mes].puntual += parseInt(item.total_puntual || 0);
      grouped[mes].tarde += parseInt(item.total_tarde || 0);
      grouped[mes].justificado += parseInt(item.total_justificados || 0);
    });
  
    const meses = Object.keys(grouped).sort();
    const puntual = meses.map((m) => grouped[m].puntual);
    const tarde = meses.map((m) => grouped[m].tarde);
    const justificado = meses.map((m) => grouped[m].justificado);
  
    const datasets = [
      {
        label: "Puntual",
        data: puntual,
        backgroundColor: chartColors.puntual,
        borderColor: chartColors.puntual,
        fill: false,
        tension: 0.3,
      },
      {
        label: "Tarde",
        data: tarde,
        backgroundColor: chartColors.tarde,
        borderColor: chartColors.tarde,
        fill: false,
        tension: 0.3,
      },
      {
        label: "Justificado",
        data: justificado,
        backgroundColor: chartColors.justificado,
        borderColor: chartColors.justificado,
        fill: false,
        tension: 0.3,
      },
    ];
  
    if (monthlyBarChart) {
      monthlyBarChart.data.labels = meses;
      monthlyBarChart.data.datasets.forEach((ds, i) => {
        ds.data = datasets[i].data;
      });
      monthlyBarChart.update();
    } else {
      monthlyBarChart = new Chart(ctxMonthlyBar, {
        type: "line",
        data: {
          labels: meses,
          datasets: datasets,
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: "bottom" },
            title: {
              display: true,
              text: "Tendencia Mensual por Tipo de Asistencia",
              font: { size: 16 },
            },
            tooltip: {
              mode: "index",
              intersect: false,
            },
          },
          interaction: {
            mode: "index",
            intersect: false,
          },
          scales: {
            x: {
              title: { display: true, text: "Mes" },
            },
            y: {
              beginAtZero: true,
              title: { display: true, text: "Estudiantes" },
            },
          },
        },
      });
    }
  }
  
  
  

  function renderJustificacionesDiarias(data) {
    const summary = data.attendance_summary || [];
    const maxSlots = 15;
    const trimmed = summary.slice(-maxSlots);
  
    const fechas = trimmed.map((r) => r.fecha);
    const justificados = trimmed.map((r) => parseInt(r.total_justificados) || 0);
  
    const faltan = maxSlots - fechas.length;
    const dias = Array(faltan).fill("").concat(fechas);
    const justificadosFull = Array(faltan).fill(null).concat(justificados);
  
    const dataset = {
      label: "Justificados",
      data: justificadosFull,
      backgroundColor: chartColors.justificado,
      borderColor: "#555",
      borderWidth: 1,
    };
  
    if (justificacionesChart) {
      justificacionesChart.data.labels = dias;
      justificacionesChart.data.datasets[0].data = justificadosFull;
      justificacionesChart.update();
    } else {
      justificacionesChart = new Chart(ctxJustificaciones, {
        type: "bar",
        data: {
          labels: dias,
          datasets: [dataset],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: "bottom" },
            title: {
              display: true,
              text: "Justificaciones por Día",
              font: { size: 16 },
            },
          },
          scales: {
            yAxes: [
              {
                stacked: true,
                ticks: { beginAtZero: true, stepSize: 1, precision: 0 },
                scaleLabel: {
                  display: true,
                  labelString: "Número de estudiantes",
                },
              },
            ],
            xAxes: [
              {
                stacked: true,
                scaleLabel: { display: true, labelString: "Fecha" },
                ticks: {
                  callback: (value) => (value === "" ? "" : value),
                },
              },
            ],
          },
        },
      });
    }
  }

  

  const ctx = document.getElementById("lineAsistenciaGeneral").getContext("2d");
  let chart;

  const ctxPie = document
    .getElementById("pieAsistenciaDistribucion")
    .getContext("2d");
  let pieChart;

  const ctxMonthlyBar = document
    .getElementById("barAsistenciaMensual")
    .getContext("2d");
  let monthlyBarChart;

  const ctxJustificaciones = document
  .getElementById("barJustificacionesDiarias")
  .getContext("2d");
let justificacionesChart;


  fetchAttendanceData();
  setInterval(fetchAttendanceData, 3000);
});
