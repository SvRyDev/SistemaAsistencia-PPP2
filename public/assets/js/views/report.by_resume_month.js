let table;

$(document).ready(function () {
  let logoBase64 = "";

/*
  
  table = $("#table-resultado").DataTable({
    dom: `t`,

    buttons: dtButtons,
    initComplete: function () {
      $(".left-text").html("");
      this.api().buttons().container().appendTo("#table-buttons-wrapper");
      this.api().buttons().disable(); // ✅ esto funciona
    },
    ordering: false,
    searching: false,
    paging: false,
    info: false,
    responsive: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
  });
*/


  $.ajax({
    url: base_url + "/student/getTotalStudents", // tu ruta correcta
    type: "GET",
    dataType: "json",
    beforeSend: function(){
      $('#total-estudiantes').html('<div class="spinner-grow spinner-grow-sm" role="status">   <span class="sr-only">Loading...</span> </div>');
    },
    success: function (response) {
      console.log("Total estudiantes:", response.total_estudiantes);
        $('#total-estudiantes').html(response.total);
      
    },
    error: function (xhr, status, error) {
    },
  });

  const selectMes = $("#select-mes");
  obtenerMeses().forEach((mes) => {
    selectMes.append(`<option value="${mes.id}">${mes.nombre}</option>`);
  });
});
