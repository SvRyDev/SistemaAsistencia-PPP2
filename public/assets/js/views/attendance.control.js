let ventana = null;
let verificador = null;

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
        .html("Cerrada");
    }
  }, 500);
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
        ${contadorAsistencias}
      </div>
      <div class="col-md-2 text-muted">
        Código: <span class="text-dark">${event.data.codigo}</span>
      </div>
      <div class="col-md-4">
        <span class="text-muted"> Nombres: </span> <span> ${event.data.nombres} ${event.data.apellidos}</span>
      </div>

      <div class="col-md-1 text-muted">
        Grado: <span class="text-dark">${event.data.grado || "--"}</span>
      </div>

      <div class="col-md-1 text-muted">
        Sección: <span class="text-dark">${event.data.seccion || "--"}</span>
      </div>
      <div class="col-md-2 text-muted">
        Hora Llegada: <span class="text-dark">00:00:00</span>
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

$(document).ready(function () {
  // Ejecutar solicitud GET al cargar
  $.ajax({
    url: base_url + "/student/getTotalStudents", // cambia por tu ruta real
    method: "GET",
    beforeSend: function () {},
    success: function (response) {
      console.log("El total es : " + response.total);
      $("#totalEstudiantes").html(response.total);
      $("#totalRestantes").html(response.total);
    },
    error: function (xhr, status, error) {},
    complete: function () {},
  });
});
