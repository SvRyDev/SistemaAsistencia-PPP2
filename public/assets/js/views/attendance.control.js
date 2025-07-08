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
  3: 0, // id_estado == 3 , Falta
  4: 0  // id_estado == 4 , Justificado
};
let totalEstudiantes = 0;
let total_restantes = 0;
//Funcion para Actualizar los datos de la Grafica de Torta
function actualizarGrafica(elemento) {
  elemento.data.datasets[0].data = [
    contadorEstados[1],
    contadorEstados[2],
    contadorEstados[4],
    total_restantes
  ];
  elemento.update();
}
//Funcion para actualizar los contadores (Presente, Tardanza, Justificados, Restantes)
function actualizarContadores() {
  $('#total_estudiantes').html(totalEstudiantes);
  $('#contadorTemprano').html(contadorEstados[1]);
  $('#contadorTardios').html(contadorEstados[2]);
  $('#contadorJustificados').html(contadorEstados[4]);
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


function refreshListAttendance() {

  contadorEstados[1] = 0;
  contadorEstados[2] = 0;
  contadorEstados[3] = 0;
  contadorEstados[4] = 0;


  $('#listaAsistencia').empty();
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
      if (response.status === "success" && Array.isArray(response.list_attendance)) {
        const lista = document.getElementById("listaAsistencia");
        lista.innerHTML = ""; // Limpiar lista anterior
        contadorAsistencias = 0;

        response.list_attendance.forEach((student) => {
          contadorAsistencias++;

          const nuevoItem = document.createElement("div");
          nuevoItem.className =
            "list-group-item list-group-item-action pt-2 pb-2 animate__animated animate__fadeInUp";

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
                  <span class="text-dark">${getHoraMinuto(student.hora_actual) || "--"}</span>
                </div>
                <div class="col-md-1 text-left">
                  <span data-id="${student.id_estado}" class="badge badge-${student.clase_boostrap}">
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
        const total_registrados = contadorEstados[1] + contadorEstados[2] + contadorEstados[4];
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
    beforeSend: function(){

    },
    success: function (response) {
      if (response.status === "success") {
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


      refreshListAttendance();







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


  /* ------------------------------------------------ */
  // Evento para busqueda de asistencia de estudiante
  /* ------------------------------------------------ */
  $('#buscarEstudiante').on('input', function () {
    const query = $(this).val().trim();

    if (query.length < 2) {
      $('#resultadoBusqueda').empty().hide();
      return;
    }

    $.ajax({
      url: base_url + "/student/searchByDniOrName", // AJUSTA TU RUTA
      type: "POST",
      data: { query: query },
      dataType: "json",
      success: function (response) {
        const results = response.estudiantes;
        const $resultados = $('#resultadoBusqueda');
        $resultados.empty();

        if (results.length === 0) {
          $resultados.append('<div class="list-group-item disabled">No encontrado</div>');
        } else {
          results.forEach(est => {
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
        $('#resultadoBusqueda').html('<div class="list-group-item text-danger">Error al buscar</div>').show();
      }
    });
  });




  // Seleccionar estudiante del autocompletado
  $('#resultadoBusqueda').on('click', 'a', function (e) {
    e.preventDefault();
    const estudianteId = $(this).data('id');
    const nombreCompleto = $(this).data('nombre');

    $('#estudianteId').val(estudianteId);
    $('#estudianteNombre').val(nombreCompleto);
    $('#resultadoBusqueda').hide();

    $.ajax({
      url: base_url + "/attendance/EditIfRegistered",
      type: "POST",
      dataType: "json",
      data: {
        estudiante_id: estudianteId,
      },
      beforeSend: function () {
        $('#estadoAsistenciaMensaje')
          .removeClass('text-muted text-success text-danger text-warning')
          .addClass('text-muted') // o text-danger según el caso
          .text('Cargando...');

      },
      success: function (res) {
        if (res.status === 'found') {
          // Modo edición
          actualizarFormularioSegunEstado(true, res);
        } else {
          // Modo nuevo
          actualizarFormularioSegunEstado(false, res);
        }
      }
    });

  });

  function actualizarFormularioSegunEstado(asistenciaExiste, res) {
    const $btn = $('#btnGuardarAsistencia');
    const $mensaje = $('#estadoAsistenciaMensaje');
    const $header = $('#modalAsistenciaHeader');
    const $input_entrada = $('');

    if (asistenciaExiste) {
      $('#mdlHoraEntrada').val(res.data.hora_entrada);
      $('#estadoAsistencia').val(res.data.estado_asistencia_id);
      $('#observacion').val(res.data.observacion || '');
      $('#formEditarAsistencia').attr('data-modo', 'editar');

      // Cambiar a modo editar
      $btn.text('Actualizar')
        .removeClass('btn-primary')
        .addClass('btn-warning');

      $mensaje
        .removeClass()
        .addClass('form-text mt-1 font-weight-bold text-warning')
        .html('<i class="fas fa-check-circle mr-1"></i> Ya hay una asistencia registrada.');

      // Cambiar color del encabezado
      $header
        .removeClass('bg-primary bg-light')
        .addClass('bg-warning text-white');

    } else {
      const ahora = getHoraMinuto();
      $('#mdlHoraEntrada').val(ahora);
      $('#estadoAsistencia').val('');
      $('#observacion').val('');
      $('#formEditarAsistencia').attr('data-modo', 'nuevo');

      // Cambiar a modo registrar
      $btn.text('Guardar')
        .removeClass('btn-warning')
        .addClass('btn-primary');

      $mensaje
        .removeClass()
        .addClass('form-text mt-1 font-weight-bold text-primary')
        .html('<i class="fas fa-exclamation-circle mr-1"></i> Sin registro previo.');

      // Cambiar color del encabezado
      $header
        .removeClass('bg-warning bg-light')
        .addClass('bg-primary text-white');
    }
  }




  $('#btnGuardarAsistencia').on('click', function (e) {
    e.preventDefault(); // Evita que el formulario se envíe automáticamente

    // Obtener el formulario y serializar los datos
    const form = $('#formEditarAsistencia');
    const formData = form.serialize();

    // Enviar por AJAX al backend
    $.ajax({
      url: base_url + '/attendance/saveAttendance', // Cambia esta URL según tu ruta real
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function (res) {
        if (res.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: res.message
          });
          refreshListAttendance();
          $('#modalAuxiliar').modal('hide'); // Cierra modal si deseas
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.message
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Error de servidor',
          text: 'No se pudo procesar la solicitud.'
        });
        console.log(formData);

      }
    });


  });

  $('#modalAuxiliar').on('hidden.bs.modal', function () {
    // Ejemplo: Limpiar todos los campos del formulario dentro del modal
    $(this).find('form')[0].reset();

    // Opcional: también puedes limpiar mensajes de error, alertas, etc.
    $(this).find('#buscarEstudiante').val('');
    $(this).find('#estadoAsistenciaMensaje').html('');

    // Cambiar a modo editar
    $('#btnGuadarAsistencia').html('Guardar')
      .removeClass('btn-primary btn-success btn-warning')
      .addClass('btn-primary');

    // Cambiar color del encabezado
    $('#modalAsistenciaHeader')
      .removeClass('bg-primary bg-success bg-warning')
      .addClass('bg-light text-dark');

    $('#resultadoBusqueda').empty();

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
      <div class="col-md-5" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        <span> ${event.data.student.nombres} ${event.data.student.apellidos}</span>
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
  const btnCloseDay = $("#btnCloseDay");
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

  // Siempre ocultar ambos antes de mostrar el correcto
  btnOpenDay.addClass('d-none');
  btnCloseDay.addClass('d-none');

  switch (estadoTexto) {
    case "activo":
      badge.html("Activo")
        .removeClass("badge-danger badge-secondary")
        .addClass("badge-success");

      btnOpenDay.prop("disabled", true);
      btnOpenDay.addClass('d-none');
      btnCloseDay.prop("disabled", false);
      btnCloseDay.removeClass('d-none');
      botonesRegistro.forEach(id => $(id).prop("disabled", false));
      break;

    case "desactivado":
      badge.html("Desactivado")
        .removeClass("badge-success badge-secondary")
        .addClass("badge-danger");

      btnOpenDay.prop("disabled", false);
      btnOpenDay.removeClass('d-none');
      btnCloseDay.prop("disabled", true);
      btnCloseDay.addClass('d-none');
      botonesRegistro.forEach(id => $(id).prop("disabled", true));
      break;

    case "finalizado":
      badge.html("Finalizado")
        .removeClass("badge-success badge-danger")
        .addClass("badge-secondary");

      btnOpenDay.prop("disabled", true);
      btnOpenDay.addClass('d-none');
      btnCloseDay.prop("disabled", true);
      btnCloseDay.addClass('d-none');
      botonesRegistro.forEach(id => $(id).prop("disabled", true));
      break;
  }
}
// Mostrar/ocultar botones correctamente al cargar la página
$(document).ready(function () {
  // Siempre ocultar ambos al inicio (por si acaso)
 $("#btnOpenDay").addClass('d-none');
  $("#btnCloseDay").addClass('d-none');


});
// Evento para concluir el día y registrar faltas
$(document).ready(function () {



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
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message || "No se pudo concluir el día.",
              });
            }
          },
          error: function (xhr, status, error) {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "No se pudo procesar la solicitud.",
            });
          },
        });
      }
    });
  });
});



