$('#logoutBtn').on('click', function (e) {
  e.preventDefault();

  cerrarSesion();
});



function cerrarSesion() {
  Swal.fire({
    title: '¿Cerrar sesión?',
    text: "Se cerrará tu sesión actual.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cerrar sesión',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: base_url + '/login/logout',
        type: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
          const res = JSON.parse(response);
          if (res.status === 'success') {
            Swal.fire({
              title: 'Sesión cerrada',
              html: `
                <div class="d-flex flex-column align-items-center">
                  <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                  <span>Redirigiendo al login...</span>
                </div>
              `,
     
              allowOutsideClick: false,
              showConfirmButton: false,
              didOpen: () => {
                setTimeout(() => {
                  window.location.href = res.redirect;
                }, 1000); // 2 segundos
              }
            });
            
          } else {
            Swal.fire('Error', res.message || 'No se pudo cerrar sesión.', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'Error en la petición AJAX', 'error');
        }
      });
    }
  });
}





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