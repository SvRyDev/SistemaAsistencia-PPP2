$(document).ready(function () {
  let datosDesdePrincipal = {};

  window.addEventListener("message", function (event) {
    if (typeof event.data === "object" && event.data.entry_time) {
      datosDesdePrincipal = event.data;

      console.log("Recibido desde ventana principal:", datosDesdePrincipal);

      // Puedes mostrarlo en el HTML si quieres:
      $("#horaEntrada").text("Entrada: " + datosDesdePrincipal.entry_time);
      $("#horaSalida").text("Salida: " + datosDesdePrincipal.exit_time);
      $("#tolerancia").text("Tolerancia: " + datosDesdePrincipal.tolerance + " min");
    }
  });


  const $input = $("#codigoEstudiante");
  const $warning = $("#focusWarning");

  // Manejo de formulario moderno y animado
  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    var codigo = $('#codigoEstudiante').val().trim();
    if (!codigo) {
      showStatus('Por favor ingresa tu código.', 'error');
      return;
    }
    showStatus('Registrando asistencia...', 'info');
    $.ajax({
      url: base_url + '/attendance/registerAttendance',
      method: 'POST',
      data: { codigo: codigo },
      dataType: 'json',
      success: function (res) {
        // Adaptar a la estructura real del backend
        let nombre = '', apellido = '', codigoEst = '', estado = '', hora = '', mensaje = '', tipo = 'info';
        if (res.student) {
          nombre = res.student.nombres || '';
          apellido = res.student.apellidos || '';
          codigoEst = res.student.codigo || '';
        }
        if (res.estado && res.estado.nombre_estado) {
          estado = res.estado.nombre_estado;
        }
        if (res.hora_actual) {
          hora = res.hora_actual;
        } else if (res.hora) {
          hora = res.hora;
        }
        if (res.message) {
          mensaje = res.message;
        } else if (res.mensaje) {
          mensaje = res.mensaje;
        }
        if (res.status === 'success') {
          tipo = 'success';
          mensaje = mensaje || '¡Asistencia registrada!';
        } else if (res.status === 'warning') {
          tipo = 'info';
          mensaje = mensaje || 'Ya tienes asistencia registrada.';
        } else {
          tipo = 'error';
          mensaje = mensaje || 'No se pudo registrar.';
        }
        showStatus(
          mensaje,
          tipo,
          hora,
          estado,
          nombre,
          apellido,
          codigoEst
        );
      },
      error: function () {
        showStatus('Error de conexión. Intenta de nuevo.', 'error');
      },
      complete: function () {
        $('#codigoEstudiante').val('')
      }
    });
  });

  // Animación de estado (igual que en el PHP)
function showStatus(msg, type, hora, estado, nombre, apellido, codigo) {
  var $status = $('#public-status');
  $status.removeClass('success error info').addClass(type || 'info');
  $status.html('');

  let html = '<div class="status-content">';

  if (msg) html += `<div class="status-msg">${msg}</div>`;
  if (estado) html += `<div class="estado-label">${estado}</div>`;
  if (hora) html += `<div><span class="label">Hora:</span> <strong>${hora}</strong></div>`;
  if (nombre) html += `<div><span class="label">Nombre:</span> <strong>${nombre}</strong></div>`;
  if (apellido) html += `<div><span class="label">Apellido:</span> <strong>${apellido}</strong></div>`;
  if (codigo) html += `<div><span class="label">Código:</span> <strong>${codigo}</strong></div>`;

  html += '</div>';

  $status.html(html);
  $status.show().addClass('animate__fadeInFast').removeClass('animate__fadeOutFast');
}



  // Mostrar advertencia si se pierde el foco
  $(window).on("blur", function () {
    $warning.show();
  });

  // Ocultar advertencia y enfocar input si se recupera el foco
  $(window).on("focus", function () {
    $warning.hide();
    $input.focus();
  });

  // Click sobre la advertencia vuelve a enfocar el input
  $warning.on("click", function () {
    window.focus();
    $input.focus();
    $warning.hide();
  });

  // Enfocar el input al inicio
  $input.focus();

  // Click en cualquier parte fuera del input o botón enfoca el input
  $(document).on("click", function (e) {
    if (
      !$(e.target).closest("#nombre").length &&
      !$(e.target).closest("button").length
    ) {
      $input.focus();
    }
  });

  $(document).ready(function () {
  const clock = $('#clock').FlipClock({
    clockFace: 'TwentyFourHourClock',
    showSeconds: true
  });

  // Función para obtener la hora exacta en zona Lima
  function getLimaTime() {
    const limaTime = new Date(
      new Intl.DateTimeFormat('en-US', {
        timeZone: 'America/Lima',
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      }).format(new Date())
    );

    // También puedes obtener la hora manualmente:
    const formatter = new Intl.DateTimeFormat('en-US', {
      timeZone: 'America/Lima',
      hour12: false,
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });

    const parts = formatter.formatToParts(new Date());
    const timeParts = {
      hour: parts.find(p => p.type === 'hour').value,
      minute: parts.find(p => p.type === 'minute').value,
      second: parts.find(p => p.type === 'second').value
    };

    return timeParts;
  }

  // Actualiza el reloj cada segundo
  setInterval(() => {
    const { hour, minute, second } = getLimaTime();
    const time = parseInt(hour + minute + second, 10);
    clock.setTime(time);
  }, 1000);
});

});
