function iniciarRelojEnTiempoReal(selector, ubicacion = "San Luis") {
    const elemento = document.querySelector(selector);
  
    function actualizarReloj() {
      const ahora = new Date();
  
      const opcionesFecha = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      };
  
      const fechaFormateada = ahora.toLocaleDateString('es-ES', opcionesFecha);
      const horaFormateada = ahora.toLocaleTimeString('es-ES');
  
      elemento.textContent = `${ubicacion} - ${capitalizarPrimeraLetra(fechaFormateada)} - ${horaFormateada}`;
    }
  
    actualizarReloj();
    setInterval(actualizarReloj, 1000);
  }
  
  function capitalizarPrimeraLetra(texto) {
    return texto.charAt(0).toUpperCase() + texto.slice(1);
  }
  
  document.addEventListener("DOMContentLoaded", function () {
    iniciarRelojEnTiempoReal("#reloj-dinamico", "San Luis");
  });
  

  const url_plugins_datable_esp = base_url + "/public/assets/libs/data-table-js/languaje/Spanish.json"; 
  const nombre_institucion = "INSTITUCIÓN EDUCATIVA MX SAN LUIS"