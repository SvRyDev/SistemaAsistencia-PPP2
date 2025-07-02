let ventana = null;
let verificador = null;


// Variables de hora
let hora_entrada;
let min_tolerancia;
let hora_cierre;
// Contadores en memoria
const contadorEstados = {
  1: 0, // id_estado == 1 , Temprano
  2: 0, // id_estado == 2 , Tarde
  3: 0  // id_estado == 3 , Justificado
};
let totalEstudiantes = 0;
let total_restantes = 0;
//Funcion para Actualizar los datos de la Grafica de Torta
function actualizarGrafica(elemento) {
  elemento.data.datasets[0].data = [
    contadorEstados[1],
    contadorEstados[2],
    contadorEstados[3],
    total_restantes
  ];
  elemento.update();
}
//Funcion para actualizar los contadores (Presente, Tardanza, Justificados, Restantes)
function actualizarContadores() {
  $('#contadorTemprano').html(contadorEstados[1]);
  $('#contadorTardios').html(contadorEstados[2]);
  $('#contadorJustificados').html(contadorEstados[3]);
  $('#contadorRestantes').html(total_restantes);
};
//actualizar mensaje de estado de hora 
function actualizarEstadoVisual(estado, destinoId) {
  const $texto = $(`#${destinoId}`);
  const $iconBox = $("#iconEstado");
  const $icono = $("#iconoAsistencia");

  let color = "";
  let icono = "";
  let texto = "";

  switch (estado.tipo) {
    case "temprano":
      color = "success";
      icono = "fa-check-circle";
      texto = `Temprano <small class="h6">(-${estado.minutos}m ${estado.segundos}s)</small>`;
      break;
    case "tolerancia":
      color = "info";
      icono = "fa-clock";
      texto = `Tolerancia <small class="h6">(+${estado.restantesMin}m ${estado.restantesSeg}s)</small>`;
      break;
    case "tarde":
      color = "warning";
      icono = "fa-exclamation-circle";
      texto = `Tarde <small class="h6 ">(+${estado.minutosTarde}m ${estado.segundosTarde}s)</small>`;
      break;
    case "fuera":
      color = "danger";
      icono = "fa-times-circle";
      texto = `Fuera de tiempo`;
      break;
  }


  $texto
    .removeClass("text-secondary text-success text-info text-warning text-danger")
    .addClass(`text-${color}`)
    .html(texto);

  $icono
    .removeClass()
    .addClass(`fas ${icono} fa-2x`);

  $iconBox
    .removeClass("bg-secondary bg-success bg-info bg-warning bg-danger")
    .addClass(`bg-${color}`);
}



function iniciarEvaluacionEstado(hEntrada, toleranciaMin, hSalida, destinoId) {
  if (window._intervalEstado) clearInterval(window._intervalEstado);

  window._intervalEstado = setInterval(() => {
    const resultado = evaluarHoraEstadoDetalle(hEntrada, toleranciaMin, hSalida);
    actualizarEstadoVisual(resultado, destinoId);
  }, 1000);
}

function evaluarHoraEstadoDetalle(hEntrada, toleranciaMin, hSalida) {
  const ahora = new Date();

  const [hEnt, mEnt] = hEntrada.split(":").map(Number);
  const entrada = new Date();
  entrada.setHours(hEnt, mEnt, 0, 0);

  const limite = new Date(entrada.getTime() + toleranciaMin * 60000);

  const [hSal, mSal] = hSalida.split(":").map(Number);
  const salida = new Date();
  salida.setHours(hSal, mSal, 0, 0);

  const segundosDiferencia = (tiempoFinal, tiempoInicio) =>
    Math.max(0, Math.round((tiempoFinal - tiempoInicio) / 1000));

  if (ahora < entrada) {
    const totalSegs = segundosDiferencia(entrada, ahora);
    const mins = Math.floor(totalSegs / 60);
    const segs = totalSegs % 60;
    return { tipo: "temprano", minutos: mins, segundos: segs };
  }

  if (ahora >= entrada && ahora <= limite) {
    const totalSegs = segundosDiferencia(limite, ahora);
    const mins = Math.floor(totalSegs / 60);
    const segs = totalSegs % 60;
    return { tipo: "tolerancia", restantesMin: mins, restantesSeg: segs };
  }

  if (ahora > limite && ahora <= salida) {
    const totalSegs = segundosDiferencia(ahora, limite);
    const mins = Math.floor(totalSegs / 60);
    const segs = totalSegs % 60;
    return { tipo: "tarde", minutosTarde: mins, segundosTarde: segs };
  }

  return { tipo: "fuera" };
}






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
        totalEstudiantes = t.total;
        total_restantes = totalEstudiantes;

        hora_entrada = s.entry_time;
        hora_cierre = s.exit_time;
        min_tolerancia = s.time_tolerance;

        const hora_actual = getHoraMinuto();
        actualizarGrafica(pieAsistencia);

        $("#time-entry").html(formatearHoraAmPm(hora_entrada));
        $("#time-tolerance").html(min_tolerancia + " min");
        $("#time-finish").html(formatearHoraAmPm(hora_cierre));
        $("#contadorRestantes").html(total_restantes);
        $("#total_estudiantes").html(totalEstudiantes);


        console.log('la hora actual es ' + hora_actual);
        // Iniciar evaluación automática del estado
        iniciarEvaluacionEstado(hora_entrada, min_tolerancia, hora_cierre, "estadoDiaRegistro");

        ;

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
      };




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


  $('#btnAbrirModal').on('click', function () {
    const hora = getHoraMinuto();
    $('#mdlHoraEntrada').val(hora);

    // Limpiar opciones anteriores excepto la primera
    $('#estadoAsistencia').find('option:not(:first)').remove();

    $.ajax({
      url: base_url + "/attendance/getListStatusAttendance",
      type: "POST",
      dataType: "json",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      success: function (response) {
        if (response.status === "success") {
          const estados = response.estados;
          estados.forEach(function (estado) {
            $('#estadoAsistencia').append(
              `<option value="${estado.id_estado}">${estado.nombre_estado}</option>`
            );
          });
        } else {
          console.error("Error al cargar estados:", response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error AJAX:", error);
      },
      complete: function () {
        // Mostrar el modal solo después de haber cargado todo
        $('#modalAuxiliar').modal('show');
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
        <span class="text-dark">${event.data.student.codigo}</span>
      </div>
      <div class="col-md-4">
        <span> ${event.data.student.nombres} ${event.data.student.apellidos}</span>
      </div>
      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.student.grado || "--"}</span>
      </div>

      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.student.seccion || "--"}</span>
      </div>
      <div class="col-md-2 text-muted">
        <span class="text-dark">${event.data.hora_actual}</span>
      </div>
      <div class="col-md-1 text-right">
        <span data-id="${event.data.estado.id_estado}" class="badge badge-${event.data.estado.clase_boostrap} ">${event.data.estado.nombre_estado}</span>
      </div>
    </div>
  </div>
    `;

    lista.appendChild(nuevoItem);






    const estadoId = event.data.estado.id_estado;

    // Actualizar contadores
    contadorEstados[estadoId]++;

    const total_registrados = contadorEstados[1] + contadorEstados[2] + contadorEstados[3];
    total_restantes = totalEstudiantes - total_registrados;


    actualizarGrafica(pieAsistencia);
    actualizarContadores();

    console.log('El contador por ahora de asistencia es ' + contadorEstados[1]);




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
