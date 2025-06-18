console.log("PAsarando por la js");

$("#searchStudentButton").on("click", function () {
  const studentCode = $("#studentCodeInput").val(); // Supongamos que tienes un campo de entrada con el ID del estudiante


  $.ajax({
    url: base_url + "/report/RecordByStudent", // Cambia esto a la URL de tu endpoint
    type: "POST",
    data: { code: studentCode },
    success: function (response) {

    },

    error: function (xhr, status, error) {
      // Manejo de errores
      console.error("Error al buscar estudiante:", error);
      $("#studentInfo").html("<p>Error al buscar estudiante.</p>");
    },
  });


});
