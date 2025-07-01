let ventana = null;
let verificador = null;

const ctx = document.getElementById("pieAsistencia").getContext("2d");

const pieAsistencia = new Chart(ctx, {
  type: "pie",
  data: {
    labels: ["Temprano", "Tardíos", "Justificados", "Restantes"],
    datasets: [
      {
        data: [95, 10, 15, 0], // datos ficticios
        backgroundColor: [
          "#28a745", // Temprano
          "#17a2b8", // Tardíos
          "#ffc107", // Justificados
          "#dc3545", // Restantes
        ],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    legend: {
      display: true,
      position: "right", // ✅ Leyenda a la derecha en Chart.js v2
    },
    tooltips: {
      callbacks: {
        label: function (tooltipItem, data) {
          const label = data.labels[tooltipItem.index] || "";
          const value = data.datasets[0].data[tooltipItem.index];
          const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
          const percentage = ((value / total) * 100).toFixed(1);
          return `${label}: ${value} (${percentage}%)`;
        },
      },
    },
  },
});

$(document).ready(function () {
  iniciarReloj("hora-actual", "fecha-actual");

  // -------------------------------
  // Cargar configuración inicial
  // -------------------------------
  $.ajax({
    url: base_url + "/attendance/getConfig",
    type: "POST",
    dataType: "json",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    success: function (response) {
      if (response.status === "success") {
        const s = response.setting;
        const d = response.day_active;
        const t = response.total_students;

        $("#time-entry").html(formatearHoraAmPm(s.entry_time));
        $("#time-tolerance").html(s.time_tolerance + " min");
        $("#time-finish").html(formatearHoraAmPm(s.exit_time));
        $("#total_estudiantes").html(t.total);


        if (d) {
          actualizarEstadoDia(1);
          $("#btnOpenDay").prop("disabled", true);
          $("#btnOpenRegister").prop("disabled", false);
          $("#btnOpenManualRegister").prop("disabled", false);
          $("#btnOpenEditor").prop("disabled", false);
          $("#btnOpenJustify").prop("disabled", false);
        } else {
          actualizarEstadoDia(0);
          $("#btnOpenDay").prop("disabled", false);
          $("#btnOpenAttendance").prop("disabled", true);
          $("#btnOpenRegister").prop("disabled", true);
          $("#btnOpenManualRegister").prop("disabled", true);
          $("#btnOpenEditor").prop("disabled", true);
          $("#btnOpenJustify").prop("disabled", true);
        }
      } else {
        mostrarError("Error", response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      mostrarError(
        "Error",
        "No se pudieron cargar los datos de configuración."
      );
    },
  });

  $("#btnOpenDay").click(function (e) {
    e.preventDefault();

    Swal.fire({
      title: "¿Aperturar el día de asistencia?",
      text: "Esto habilitará el día para registrar asistencias.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, aperturar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745", // Verde
      cancelButtonColor: "#d33",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/attendance/openNewDay",
          type: "POST",
          data: {},
          success: function (response) {
            console.log(response);
            if (response.status === "success") {
              actualizarEstadoDia(1);
              Swal.fire({
                icon: "success",
                title: "Éxito",
                text: "Día habilitado correctamente.",
              });

              $("#btnOpenDay").prop("disabled", true);
              $("#btnOpenAttendance").prop("disabled", false);
              $("#btnOpenRegister").prop("disabled", false);
              $("#btnOpenManualRegister").prop("disabled", false);
              $("#btnOpenEditor").prop("disabled", false);
              $("#btnOpenJustify").prop("disabled", false);


            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message,
              });
              console.log(response);
            }
          },
          error: function (xhr, status, error) {
            console.error("Error al habilitar el día:", error);
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "No se pudo habilitar el día.",
            });
          },
        });
      }
    });
  });
});

$("#btnOpenAttendance").click(function (e) {
  e.preventDefault();

  if (ventana && !ventana.closed) {
    ventana.focus(); // Si la ventana ya está abierta, solo la enfocamos
  } else {
    abrirVentana(); // Si no está abierta, la creamos
  }
});

function abrirVentana() {
  const ancho = screen.availWidth;
  const alto = screen.availHeight;
  const opciones = `width=${ancho},height=${alto},top=0,left=0`;

  const datos = {
    entry_time: "07:30",
    exit_time: "13:00",
    tolerance: 10,
  };

  ventana = window.open(
    base_url + "/attendance/openAttendance",
    "registro",
    opciones
  );

  $("#estadoVentana")
    .addClass("badge-success")
    .removeClass("badge-danger")
    .html("Abierta");

  // Verificar cada 500ms si la ventana fue cerrada
  verificador = setInterval(() => {
    if (ventana.closed) {
      clearInterval(verificador);
      $("#estadoVentana")
        .addClass("badge-danger")
        .removeClass("badge-success")
        .html("Cerrado");
    }
  }, 500);

  // Esperar a que cargue antes de enviar los datos
  const enviarDatos = () => {
    if (ventana && !ventana.closed) {
      ventana.postMessage(datos, "*"); // 🔐 Puedes cambiar * por tu origen exacto si quieres más seguridad
    } else {
      setTimeout(enviarDatos, 500);
    }
  };

  setTimeout(enviarDatos, 1000); // Da tiempo para que cargue el DOM de la ventana nueva
}


let contadorAsistencias = 0;
window.addEventListener("message", function (event) {
  // Asegurarse de que sea un objeto y tenga las propiedades requeridas
  if (typeof event.data === "object") {
    contadorAsistencias++; // Incrementar contador
    const lista = document.getElementById("listaAsistencia");

    // Crear nuevo item con clase list-group-item
    const nuevoItem = document.createElement("div");
    nuevoItem.className =
      "list-group-item list-group-item-action pt-2 pb-2 animate__animated animate__fadeInUp";

    nuevoItem.innerHTML = `
        <div class="container-fluid">
    <div class="row">
      <div class="col-md-1 font-weight-bold text-truncate">
        <small>${contadorAsistencias} </small>
      </div>
      <div class="col-md-2 text-muted">
        <span class="text-dark">${event.data.codigo}</span>
      </div>
      <div class="col-md-4">
        <span> ${event.data.nombres} ${event.data.apellidos}</span>
      </div>
      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.grado || "--"}</span>
      </div>

      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.seccion || "--"}</span>
      </div>
      <div class="col-md-2 text-muted">
        <span class="text-dark">00:00:00</span>
      </div>
      <div class="col-md-1 text-right">
        <span class="badge badge-success">Asistido</span>
      </div>
    </div>
  </div>
    `;

    lista.appendChild(nuevoItem);

    // Sumar 1 al contador de registrados
    let registrados = parseInt($("#totalRegistrados").text(), 10) || 0;
    $("#totalRegistrados").text(registrados + 1);
    actualizarRestantes(); // 🧠 aquí restamos automáticamente

    function actualizarRestantes() {
      const total = parseInt($("#totalEstudiantes").text(), 10) || 0;
      const registrados = parseInt($("#totalRegistrados").text(), 10) || 0;
      const restantes = total - registrados;
      $("#totalRestantes").text(restantes >= 0 ? restantes : 0);
    }
  } else {
    console.warn("Mensaje recibido inválido:", event.data);
  }
});

// Al cerrar o recargar la ventana principal, cerrar la secundaria si está abierta
window.addEventListener("beforeunload", function () {
  if (ventana && !ventana.closed) {
    ventana.close();
  }
});

// Inicializar con "No abierta"
$("#estadoVentana").addClass("badge-secondary").html("No Abierta");



function actualizarEstadoDia(estado) {
  const badge = $("#dia-activo");
  const btnOpenDay = $("#btnOpenDay");
  const botonesRegistro = [
    "#btnOpenRegister",
    "#btnOpenManualRegister",
    "#btnOpenEditor",
    "#btnOpenJustify",
  ];

  // Mapear valores numéricos a texto
  const estados = {
    0: "desactivado",
    1: "activo",
    2: "finalizado",
  };

  const estadoTexto = estados[estado];

  if (!estadoTexto) {
    console.warn("Estado de día no reconocido:", estado);
    return;
  }

  switch (estadoTexto) {
    case "activo":
      badge.html("Activo")
        .removeClass("badge-danger badge-secondary")
        .addClass("badge-success");

      btnOpenDay.prop("disabled", true);
      botonesRegistro.forEach(id => $(id).prop("disabled", false));
      break;

    case "desactivado":
      badge.html("Desactivado")
        .removeClass("badge-success badge-secondary")
        .addClass("badge-danger");

      btnOpenDay.prop("disabled", false);
      botonesRegistro.forEach(id => $(id).prop("disabled", true));
      break;

    case "finalizado":
      badge.html("Finalizado")
        .removeClass("badge-success badge-danger")
        .addClass("badge-secondary");

      btnOpenDay.prop("disabled", true);
      botonesRegistro.forEach(id => $(id).prop("disabled", true));
      break;
  }
}
