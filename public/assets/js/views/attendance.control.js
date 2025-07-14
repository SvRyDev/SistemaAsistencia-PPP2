let ventana = null;
let verificador = null;

let estadosCargados = []; // Aquí se almacenarán los estados una sola vez
let estadosYaCargados = false;

// Variables de hora
let hora_entrada;
let min_tolerancia;
let hora_cierre;

let contadorAsistencias = 0;
// Contadores en memoria
const contadorEstados = {
  1: 0, // id_estado == 1 , Temprano
  2: 0, // id_estado == 2 , Tarde
  3: 0, // id_estado == 3 , Falta
  4: 0, // id_estado == 4 , Justificado
};

let totalEstudiantes = 0;
let total_restantes = 0;
//Funcion para Actualizar los datos de la Grafica de Torta
function actualizarGrafica(elemento) {
  elemento.data.datasets[0].data = [
    contadorEstados[1],
    contadorEstados[2],
    contadorEstados[4],
    total_restantes,
  ];
  elemento.update();
}

// -------------------------------
// Cargar Estados de Asistencia
// -------------------------------
$(document).ready(function () {
  startAutoRefreshAttendance();
  iniciarReloj("hora-actual", "fecha-actual");

  // Cargar los estados UNA SOLA VEZ
  $.ajax({
    url: base_url + "/attendance/getListStatusAttendance",
    type: "POST",
    dataType: "json",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    success: function (response) {
      if (response.status === "success") {
        estadosCargados = response.estados;
        estadosYaCargados = true;
      } else {
        console.error("Error al cargar estados:", response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
    },
  });

  // Evento al abrir el modal
  $("#btnRegisterManual").on("click", function () {
    const hora = getHoraMinuto();
    $("#mdlHoraEntrada").val(hora);

    const $estadoSelect = $("#estadoAsistencia");

    // Limpiar las opciones anteriores excepto la primera
    $estadoSelect.find("option:not(:first)").remove();

    // Solo si los estados ya fueron cargados
    if (estadosYaCargados) {
      estadosCargados.forEach(function (estado) {
        $estadoSelect.append(
          `<option value="${estado.id_estado}">${estado.nombre_estado}</option>`
        );
      });
    }

    // Seleccionar la primera opción (la que no tiene value)
    $estadoSelect.prop("selectedIndex", 0);

    // Mostrar el modal
    $("#modalAuxiliar").modal("show");
  });

  $("#btnCloseDay").click(function (e) {
    e.preventDefault();

    Swal.fire({
      title: "¿Concluir el día de asistencia?",
      text: "Esto finalizará el día y registrará como FALTA a los estudiantes restantes.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, concluir",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
    }).then((result) => {
      if (result.isConfirmed) {
        // Referencia al botón
        const $btn = $("#btnCloseDay");
        const originalHtml = $btn.html();

        // Mostrar spinner y desactivar botón
        $btn
          .html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...')
          .prop("disabled", true);

        $.ajax({
          url: base_url + "/attendance/closeDayAndRegisterAbsents",
          type: "POST",
          dataType: "json",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          success: function (response) {
            if (response.status === "success") {
              actualizarEstadoDia(2); // Finalizado
              Swal.fire({
                icon: "success",
                title: "Día concluido",
                text: "El día se ha finalizado y las faltas han sido registradas.",
              });
              refreshListAttendance();
              actualizarBotones(true, true, true, true, false);
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message || "No se pudo concluir el día.",
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "No se pudo procesar la solicitud.",
            });
          },
          complete: function () {
            // Restaurar botón original
            $btn.html(originalHtml).prop("disabled", false);
          },
        });
      }
    });
  });

  $("#btnNewDay").click(function (e) {
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
        const $btn = $("#btnNewDay");
        const originalHtml = $btn.html();

        // Mostrar spinner y desactivar botón
        $btn
          .html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...')
          .prop("disabled", true);

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
              actualizarBotones(true, false, false, false, true);
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message,
              });
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
          complete: function () {
            // Restaurar el botón
            $btn.html(originalHtml).prop("disabled", false);
          },
        });
      }
    });
  });

  $("#btnReopenAttendance").click(function (e) {
    e.preventDefault();

    Swal.fire({
      title: "¿Reabrir el día de asistencia?",
      text: "Esto permitirá volver a registrar asistencias del día.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, reabrir",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
    }).then((result) => {
      if (result.isConfirmed) {
        // Referencia al botón
        const $btn = $("#btnReopenAttendance");
        const originalHtml = $btn.html();

        // Mostrar spinner y desactivar botón
        $btn
          .html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...')
          .prop("disabled", true);

        $.ajax({
          url: base_url + "/attendance/reOpenDay",
          type: "POST",
          dataType: "json",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          success: function (response) {
            if (response.status === "success") {
              actualizarEstadoDia(1); // Estado Activo
              Swal.fire({
                icon: "success",
                title: "Día reabierto",
                text: "El día de asistencia ha sido reactivado correctamente.",
              });
              refreshListAttendance();
              actualizarBotones(true, false, false, false, true);
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message || "No se pudo reabrir el día.",
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "No se pudo procesar la solicitud.",
            });
          },
          complete: function () {
            // Restaurar el botón original
            $btn.html(originalHtml).prop("disabled", false);
          },
        });
      }
    });
  });

  /* ------------------------------------------------ */
  // Evento para busqueda de asistencia de estudiante
  /* ------------------------------------------------ */
  $("#buscarEstudiante").on("input", function () {
    const query = $(this).val().trim();

    if (query.length < 2) {
      $("#resultadoBusqueda").empty().hide();
      return;
    }

    $.ajax({
      url: base_url + "/student/searchByDniOrName", // AJUSTA TU RUTA
      type: "POST",
      data: { query: query },
      dataType: "json",
      success: function (response) {
        const results = response.estudiantes;
        const $resultados = $("#resultadoBusqueda");
        $resultados.empty();

        if (results.length === 0) {
          $resultados.append(
            '<div class="list-group-item disabled">No encontrado</div>'
          );
        } else {
          results.forEach((est) => {
            const item = `
                        <a href="#" class="list-group-item list-group-item-action"
                            data-id="${est.id}" data-nombre="${est.nombres} ${est.apellidos}">
                            ${est.nombres} ${est.apellidos} - <small>${est.dni}</small>
                        </a>`;
            $resultados.append(item);
          });
        }

        $resultados.show();
      },
      error: function () {
        $("#resultadoBusqueda")
          .html(
            '<div class="list-group-item text-danger">Error al buscar</div>'
          )
          .show();
      },
    });
  });

  // Seleccionar estudiante del autocompletado
  $("#resultadoBusqueda").on("click", "a", function (e) {
    e.preventDefault();
    const estudianteId = $(this).data("id");
    const nombreCompleto = $(this).data("nombre");

    $("#estudianteId").val(estudianteId);
    $("#estudianteNombre").val(nombreCompleto);
    $("#resultadoBusqueda").hide();

    $.ajax({
      url: base_url + "/attendance/EditIfRegistered",
      type: "POST",
      dataType: "json",
      data: {
        estudiante_id: estudianteId,
      },
      beforeSend: function () {
        $("#estadoAsistenciaMensaje")
          .removeClass("text-muted text-success text-danger text-warning")
          .addClass("text-muted") // o text-danger según el caso
          .text("Cargando...");
      },
      success: function (res) {
        if (res.status === "found") {
          // Modo edición
          actualizarFormularioSegunEstado(true, res);
        } else {
          // Modo nuevo
          actualizarFormularioSegunEstado(false, res);
        }
      },
    });
  });

  function actualizarFormularioSegunEstado(asistenciaExiste, res) {
    const $btn = $("#btnGuardarAsistencia");
    const $mensaje = $("#estadoAsistenciaMensaje");
    const $header = $("#modalAsistenciaHeader");
    const $input_entrada = $("");

    if (asistenciaExiste) {
      $("#mdlHoraEntrada").val(res.data.hora_entrada);
      $("#estadoAsistencia").val(res.data.estado_asistencia_id);
      $("#observacion").val(res.data.observacion || "");
      $("#formEditarAsistencia").attr("data-modo", "editar");

      // Cambiar a modo editar
      $btn
        .text("Actualizar")
        .removeClass("btn-primary")
        .addClass("btn-warning");

      $mensaje
        .removeClass()
        .addClass("form-text mt-1 font-weight-bold text-warning")
        .html(
          '<i class="fas fa-check-circle mr-1"></i> Ya hay una asistencia registrada.'
        );

      // Cambiar color del encabezado
      $header
        .removeClass("bg-primary bg-light")
        .addClass("bg-warning text-white");
    } else {
      const ahora = getHoraMinuto();
      $("#mdlHoraEntrada").val(ahora);
      $("#estadoAsistencia").val("");
      $("#observacion").val("");
      $("#formEditarAsistencia").attr("data-modo", "nuevo");

      // Cambiar a modo registrar
      $btn.text("Guardar").removeClass("btn-warning").addClass("btn-primary");

      $mensaje
        .removeClass()
        .addClass("form-text mt-1 font-weight-bold text-primary")
        .html(
          '<i class="fas fa-exclamation-circle mr-1"></i> Sin registro previo.'
        );

      // Cambiar color del encabezado
      $header
        .removeClass("bg-warning bg-light")
        .addClass("bg-primary text-white");
    }
  }

  $("#btnGuardarAsistencia").on("click", function (e) {
    e.preventDefault(); // Evita que el formulario se envíe automáticamente

    // Obtener el formulario y serializar los datos
    const form = $("#formEditarAsistencia");
    const formData = form.serialize();

    // Enviar por AJAX al backend
    $.ajax({
      url: base_url + "/attendance/saveAttendance", // Cambia esta URL según tu ruta real
      method: "POST",
      data: formData,
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: res.message,
          });
          refreshListAttendance();
          $("#modalAuxiliar").modal("hide"); // Cierra modal si deseas
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: res.message,
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Error de servidor",
          text: "No se pudo procesar la solicitud.",
        });
        console.log(formData);
      },
    });
  });

  $("#modalAuxiliar").on("hidden.bs.modal", function () {
    // Ejemplo: Limpiar todos los campos del formulario dentro del modal
    $(this).find("form")[0].reset();

    // Opcional: también puedes limpiar mensajes de error, alertas, etc.
    $(this).find("#buscarEstudiante").val("");
    $(this).find("#estadoAsistenciaMensaje").html("");

    // Cambiar a modo editar
    $("#btnGuadarAsistencia")
      .html("Guardar")
      .removeClass("btn-primary btn-success btn-warning")
      .addClass("btn-primary");

    // Cambiar color del encabezado
    $("#modalAsistenciaHeader")
      .removeClass("bg-primary bg-success bg-warning")
      .addClass("bg-light text-dark");

    $("#resultadoBusqueda").empty();
  });
});

//Funcion para actualizar los contadores (Presente, Tardanza, Justificados, Restantes)
function actualizarContadores() {
  $("#total_estudiantes").html(totalEstudiantes);
  $("#contadorTemprano").html(contadorEstados[1]);
  $("#contadorTardios").html(contadorEstados[2]);
  $("#contadorJustificados").html(contadorEstados[4]);
  $("#contadorRestantes").html(total_restantes);

  $("#porcentajeTemprano").html(
    ((contadorEstados[1] * 100) / totalEstudiantes).toFixed(2) + "%"
  );
  $("#porcentajeTardios").html(
    ((contadorEstados[2] * 100) / totalEstudiantes).toFixed(2) + "%"
  );
  $("#porcentajeJustificados").html(
    ((contadorEstados[4] * 100) / totalEstudiantes).toFixed(2) + "%"
  );
  $("#porcentajeRestantes").html(
    ((total_restantes * 100) / totalEstudiantes).toFixed(2) + "%"
  );
}
//actualizar mensaje de estado de hora

function actualizarMostrarGraficas(habilitado) {
  if (habilitado) {
    $("#pieAsistencia-alert").addClass("d-none");
    $("#pieAsistencia").removeClass("d-none");
    $("#contadores-container-alert").addClass("d-none");
    $("#contadores-container").removeClass("d-none");
    $("#lista-asistencia-container-alert").addClass("d-none");
    $("#lista-asistencia-container").removeClass("d-none");
  } else {
    $("#pieAsistencia-alert").removeClass("d-none");
    $("#pieAsistencia").addClass("d-none");
    $("#contadores-container-alert").removeClass("d-none");
    $("#contadores-container").addClass("d-none");
    $("#lista-asistencia-container-alert").removeClass("d-none");
    $("#lista-asistencia-container").addClass("d-none");
  }
}
function actualizarBotones(btn1, btn2, btn3, btn4, btn5) {
  $("#btnNewDay").prop("disabled", btn1).toggleClass("d-none", btn1);
  $("#btnCloseDay").prop("disabled", btn2).toggleClass("d-none", btn2); //Cerrar Dia
  $("#btnOpenAttendanceView")
    .prop("disabled", btn3)
    .toggleClass("d-none", btn3); //Abrir Venta de Registro
  $("#btnRegisterManual").prop("disabled", btn4).toggleClass("d-none", btn4); //Registro Manual
  $("#btnReopenAttendance").prop("disabled", btn5).toggleClass("d-none", btn5); //Reaperturar Dia
}


function refreshListAttendance() {
  contadorEstados[1] = 0;
  contadorEstados[2] = 0;
  contadorEstados[3] = 0;
  contadorEstados[4] = 0;

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
    beforeSend: function () {},
    success: function (response) {
      if (response.status === "success") {
        console.log("PASANDO PR EL CONFIG");

        $("#botonesAsistenciaLoading").hide();
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

        // Iniciar evaluación automática del estado

        console.log("PASANDO PR EL CONFIG, EL D ES " + d.estado);

        $('#botonesAsistenciaWrapper').removeClass('d-none').addClass('d-flex');
        $('#cargandoBotonesAsistencia').addClass('d-none');

        $('#estadoTiempoActual').removeClass('d-none').addClass('d-flex');
        $('#estadoTiempoActualLoading').addClass('d-none').removeClass('d-flex');

        if (d) {
          actualizarMostrarGraficas(true);
          const estado = d.estado;
          if (parseInt(estado) == 1) {
            console.log("espot pasando por qusoy 1");
            actualizarEstadoDia(1);
            manejarEstadoDia(1, hora_entrada, min_tolerancia, hora_cierre, "estadoDiaRegistro");
            actualizarBotones(true, false, false, false, true);
          } else if (parseInt(estado) == 0) {
      
            console.log("esta finalizado");
            console.log("espot pasando por qusoy 0");
            actualizarEstadoDia(2);
            manejarEstadoDia(2, hora_entrada, min_tolerancia, hora_cierre, "estadoDiaRegistro");
            actualizarBotones(true, true, true, true, false);
          }
        } else {
          // Día no aperturado
    
          actualizarMostrarGraficas(false);
          actualizarEstadoDia(0);
          manejarEstadoDia(0, hora_entrada, min_tolerancia, hora_cierre, "estadoDiaRegistro");
          actualizarBotones(false, true, true, true, true);
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
      $("#listaAsistencia").empty();
      if (
        response.status === "success" &&
        Array.isArray(response.list_attendance)
      ) {
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
              <div class="row">
                <div class="col-md-1 font-weight-bold text-truncate">
                  <small>${contadorAsistencias}</small>
                </div>
                <div class="col-md-2 text-muted">
                  <span class="text-dark">${student.codigo}</span>
                </div>
                <div class="col-md-5" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  <span>${student.nombres} ${student.apellidos}</span>
                </div>
                <div class="col-md-1 text-muted">
                  <span class="text-dark">${student.grado_orden || "--"}</span>
                </div>
                <div class="col-md-1 text-muted">
                  <span class="text-dark">${student.seccion || "--"}</span>
                </div>
                <div class="col-md-1 text-muted">
                  <span class="text-dark">${
                    getHoraMinuto(student.hora_actual) || "--:--"
                  }</span>
                </div>
                <div class="col-md-1 text-left">
                  <span data-id="${student.id_estado}" class="badge badge-${
            student.clase_boostrap
          }">
                    ${student.nombre_estado}
                  </span>
                </div>
              </div>
            </div>
          `;

          lista.appendChild(nuevoItem);

          // Contadores
          const estadoId = student.id_estado;
          if (contadorEstados[estadoId] !== undefined) {
            contadorEstados[estadoId]++;
          }
        });

        // Actualizar gráficos y contadores
        const total_registrados =
          contadorEstados[1] + contadorEstados[2] + contadorEstados[4];
        total_restantes = totalEstudiantes - total_registrados;

        console.log(total_registrados);
        console.log(total_restantes);

        actualizarGrafica(pieAsistencia);
        actualizarContadores();
      } else {
        console.warn("Respuesta vacía o inválida:", response);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar asistencia:", error);
    },
  });
}


// Variable global para controlar el intervalo del reloj
let _intervalReloj = null;
let _estadoDiaActual = null;

// ========================
// Función principal
// ========================
function manejarEstadoDia(estadoDia, hEntrada, toleranciaMin, hSalida, destinoId) {
  if (_estadoDiaActual === estadoDia) return; // No hacer nada si no cambió el estado
  _estadoDiaActual = estadoDia;

  actualizarEstadoDia(estadoDia); // Actualiza el badge visual

  detenerReloj(); // Detener siempre antes de decidir

  switch (estadoDia) {
    case 0: // Desactivado
      actualizarEstadoVisual(null, destinoId);
      break;

    case 1: // Activo
    iniciarRelojdeEstado(hEntrada, toleranciaMin, hSalida, destinoId);
      break;

    case 2: // Finalizado
      actualizarEstadoVisual({ tipo: "cerrado" }, destinoId);
      break;
  }
}

// ========================
// Inicia el reloj cada segundo
// ========================




function iniciarRelojdeEstado(hEntrada, toleranciaMin, hSalida, destinoId) {
  _intervalReloj = setInterval(() => {
    const estado = evaluarHoraEstadoDetalle(hEntrada, toleranciaMin, hSalida);
    actualizarEstadoVisual(estado, destinoId);
  }, 1000);
}

// ========================
// Detiene el reloj si está activo
// ========================
function detenerReloj() {
  if (_intervalReloj) {
    clearInterval(_intervalReloj);
    _intervalReloj = null;
  }
}

// ========================
// Evalúa el estado actual en función del reloj
// ========================
function evaluarHoraEstadoDetalle(hEntrada, toleranciaMin, hSalida) {
  const ahora = new Date();

  const [hEnt, mEnt] = hEntrada.split(":").map(Number);
  const entrada = new Date();
  entrada.setHours(hEnt, mEnt, 0, 0);

  const limite = new Date(entrada.getTime() + toleranciaMin * 60000);

  const [hSal, mSal] = hSalida.split(":").map(Number);
  const salida = new Date();
  salida.setHours(hSal, mSal, 0, 0);

  const diferenciaSegundos = (a, b) => Math.max(0, Math.round((a - b) / 1000));

  if (ahora < entrada) {
    const total = diferenciaSegundos(entrada, ahora);
    return { tipo: "temprano", minutos: Math.floor(total / 60), segundos: total % 60 };
  }

  if (ahora >= entrada && ahora <= limite) {
    const total = diferenciaSegundos(limite, ahora);
    return { tipo: "tolerancia", restantesMin: Math.floor(total / 60), restantesSeg: total % 60 };
  }

  if (ahora > limite && ahora <= salida) {
    const total = diferenciaSegundos(ahora, limite);
    return { tipo: "tarde", minutosTarde: Math.floor(total / 60), segundosTarde: total % 60 };
  }

  return { tipo: "fuera" };
}

// ========================
// Actualiza la interfaz visual según estado de asistencia
// ========================
function actualizarEstadoVisual(estado, destinoId) {
  const $texto = $(`#${destinoId}`);
  const $tempo_contador = $(`#tiempoDiferenciaEstado`);
  const $iconBox = $("#iconEstado");
  const $icono = $("#iconoAsistencia");

  let color = "secondary";
  let icono = "fa-ban fa-2x";
  let texto = "Día no aperturado";
  let tempo_contador = "";

  if (estado) {
    switch (estado.tipo) {
      case "temprano":
        color = "success";
        icono = "fa-check-circle fa-2x";
        texto = `Temprano`;
        tempo_contador = `(-${estado.minutos}m ${estado.segundos}s)`;
        break;
      case "tolerancia":
        color = "info";
        icono = "fa-clock fa-2x";
        texto = `Tolerancia`;
        tempo_contador = `(+${estado.restantesMin}m ${estado.restantesSeg}s)`;
        break;
      case "tarde":
        color = "warning";
        icono = "fa-exclamation-circle fa-2x";
        texto = `Tarde`;
        tempo_contador = `(+${estado.minutosTarde}m ${estado.segundosTarde}s)`;
        break;
      case "fuera":
        color = "danger";
        icono = "fa-times-circle fa-2x";
        texto = `Fuera de tiempo`;
        tempo_contador = `Fuera de tiempo`;
        break;
      case "cerrado":
        color = "dark";
        icono = "fa-lock fa-2x";
        texto = "Día cerrado";
        tempo_contador = "";
        break;
    }
  }

  $texto.html(texto);
  $tempo_contador.html(tempo_contador);
  $iconBox.removeClass(`bg-secondary bg-success bg-danger bg-warning bg-dark bg-secondary`).addClass(`bg-${color}`);
  $texto.removeClass(`'text-secondary text-success text-danger text-warning text-dark text-secondary'`).addClass(`text-${color}`);
  $icono.removeClass().addClass(`fas ${icono} text-white`);
}

// ========================
// Actualiza el badge del día
// ========================
function actualizarEstadoDia(estado) {
  const badge = $("#dia-activo");

  const estados = {
    0: "Desactivado",
    1: "Activo",
    2: "Finalizado",
  };

  const colores = {
    0: "danger",
    1: "success",
    2: "info",
  };

  badge
    .html(estados[estado] || "Desconocido")
    .removeClass("badge-success badge-danger badge-info badge-secondary")
    .addClass(`badge-${colores[estado] || "secondary"}`);
}







// --- AUTO REFRESH DE ASISTENCIA ---
let intervalRefreshAttendance = null;
refreshListAttendance();
function startAutoRefreshAttendance() {
  if (intervalRefreshAttendance) clearInterval(intervalRefreshAttendance);
  intervalRefreshAttendance = setInterval(refreshListAttendance, 3000); // cada 3 segundos
}
function stopAutoRefreshAttendance() {
  if (intervalRefreshAttendance) clearInterval(intervalRefreshAttendance);
}

const ctx = document.getElementById("pieAsistencia").getContext("2d");
const pieAsistencia = new Chart(ctx, {
  type: "pie",
  data: {
    labels: ["Temprano", "Tardíos", "Justificados", "Restantes"],
    datasets: [
      {
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
      onClick: null, // 👈 Esto desactiva el clic en la leyenda
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

$("#btnOpenAttendanceView").click(function (e) {
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
    entry_time: formatearHoraAmPm(hora_entrada),
    exit_time: formatearHoraAmPm(hora_cierre),
    tolerance: min_tolerancia,
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
      <div class="col-md-5" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        <span> ${event.data.student.nombres} ${
      event.data.student.apellidos
    }</span>
      </div>
      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.student.grado || "--"}</span>
      </div>

      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.student.seccion || "--"}</span>
      </div>
      <div class="col-md-1 text-muted">
        <span class="text-dark">${event.data.hora_actual}</span>
      </div>
      <div class="col-md-1 text-left">
        <span data-id="${event.data.estado.id_estado}" class="badge badge-${
      event.data.estado.clase_boostrap
    } ">${event.data.estado.nombre_estado}</span>
      </div>
    </div>
  </div>
    `;

    lista.appendChild(nuevoItem);

    const estadoId = event.data.estado.id_estado;

    // Actualizar contadores
    contadorEstados[estadoId]++;

    const total_registrados =
      contadorEstados[1] + contadorEstados[2] + contadorEstados[3];
    total_restantes = totalEstudiantes - total_registrados;

    actualizarGrafica(pieAsistencia);
    actualizarContadores();

    console.log("El contador por ahora de asistencia es " + contadorEstados[1]);
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
      badge
        .html("Activo")
        .removeClass("badge-danger badge-secondary badge-info")
        .addClass("badge-success");
      break;

    case "desactivado":
      badge
        .html("Desactivado")
        .removeClass("badge-success badge-secondary badge-info")
        .addClass("badge-danger");

      break;

    case "finalizado":
      badge
        .html("Finalizado")
        .removeClass("badge-success badge-danger badge-info")
        .addClass("badge-info");
      break;
  }
}

// Evento para concluir el día y registrar faltas
