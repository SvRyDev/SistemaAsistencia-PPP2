// Script Main.js
let isTransitioning = false;
const url_base = base_url;
const url_assets = base_url + "/public/assets/";


//▓▓Función para cargar una vista
function loadView(view) {
  module_name = view.split("/")[0];
  if (isTransitioning) return;

  isTransitioning = true;

  // Aplicar la animación de salida antes de vaciar el contenido

  // Esperar a que termine la animación de salida
  $("#main-content-space").fadeOut(100, function () {
    $("#main-content-space").off().empty();

    //AJAX
    $.ajax({
      url: base_url + "/" + view, // Endpoint
      type: "GET", //Método de la solicitud
      dataType: "html", //Formato HTML
      beforeSend: function () {
        $("#PFlow-wrape-content").html("Cargando..."); // Mensaje de carga
      },
      success: function (response) {
        $("#PFlow-wrape-content").html(response).fadeIn(200);
        window.history.pushState({view: view,},
          view,
          base_url + "/" + view
        ); // Actualizar la URL sin recargar

        // Asegurarse de que el script se carga después de la vista
        setTimeout(function () {
          loadViewScript(view);
          reforLinks();
        }, 50);
      },
      error: function () {
        $("#main-content-space").html("Error al cargar la vista."); // Manejar errores
      },
      complete: function () {
        isTransitioning = false; // Desbloquear cuando la transición haya terminado
      },
    });
  });
}

// Función para cargar y ejecutar el script específico de cada vista
function loadViewScript(view) {
  // Eliminar el script anterior si existe
  console.log("Eliminando script previo");
  $("#PFlow-wrape-scripts").remove();
  console.log("eliminando script, su rango es " + $("#dynamic-script").length);
  const moduleName = view.split("/")[0];
  const scriptUrl =
    base_url +
    "/public/assets/js/modules/" +
    moduleName +
    ".js?v=" +
    new Date().getTime();
  console.log("Intentando cargar el script: " + scriptUrl);

  // Agregar el nuevo script con un id único
  const scriptElement = document.createElement("script");
  scriptElement.src = scriptUrl;
  scriptElement.id = "dynamic-script";
  document.body.appendChild(scriptElement);

  scriptElement.onload = function () {
    console.log(
      "Script para la vista " +
        view +
        " cargado correctamente. Modulo " +
        moduleName +
        " cargado :D"
    );
  };
  scriptElement.onerror = function () {
    console.error("Error al cargar el script: " + scriptUrl);
  };

  console.log("el script ahora tiene " + $("#dynamic-script").length);

  
}

// Función para obtener la vista actual desde la URL
function getCurrentView() {
  const path = window.location.pathname;
  const segments = path.split("/");
  console.log(
    "la ruta completa es " +
      segments +
      " y el emento parcial es " +
      segments[segments.length - 1]
  );

  return module_name;
}


// Cargar vista inicial al cargar la página
$(document).ready(function () {
  const currentView = getCurrentView();
  loadView(module_name);
});

// Manejar el retroceso o avance en el navegador (historial)
$(window).on("popstate", function (event) {
  if (event.originalEvent.state && event.originalEvent.state.view) {
    loadView(event.originalEvent.state.view); // Cargar la vista que estaba en el historial
  }
});
