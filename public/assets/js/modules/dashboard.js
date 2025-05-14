//MODULO dashboard.js

/////////////////////////////////////////////////////////////////////////////////////////////
(function () {
  /////////////////////////////////////////////////////////////////////////////////////////////
  const base_url_module = base_url + "/" + module_name;
  const form_module_id = "#COMPLETAR";
  /////////////////////////////////////////////////////////////////////////////////////////////

  // Código para obtener los datos y mostrar el gráfico
  $.ajax({
    url: base_url_module + "/getPeriodos",
    method: "GET",
    beforeSend: function () { },
    success: function (response) {
      response.periodos_anual.forEach(function (item) {
        var option1 = $("<option></option>")
          .val(item.id_periodo_anual) // El valor de la opción
          .text(item.nombre_año); // El texto que se mostrará en la opción

        $("#db_periodo_anual").append(option1);
      });


      // Inicializa los gráficos con un valor predeterminado
      const defaultAnioId = $("#db_periodo_anual").val(); // Obtiene el valor inicial del select

      console.log('el valir del año es ' + defaultAnioId);

      fetchAndRenderCharts(defaultAnioId);
    },

    error: function (xhr, status, error) { },
  });


  let myChart1; // Variable para almacenar la instancia del gráfico 1
  let myChart2; // Variable para almacenar la instancia del gráfico 2

  function fetchAndRenderCharts(anio_id) {

    $.ajax({
      url: base_url_module + "/getDataToCharts/" + anio_id, // Aquí debes poner la URL de tu API
      method: "GET",
      beforeSend: function () {
        $("#txt-loading").html("Cargando...");
        $(".form--placeholder").show();
        $(".contents-charts").hide();


        $('#data--num-matriculas').hide();
        $('#data--num-estudiantes').hide();
        $('#data--num-usuarios').hide();
        $('#data--num-cargos').hide();
      },
      success: function (data) {

        $("#txt-loading").html("");
        $(".form--placeholder").hide();
        $(".contents-charts").fadeIn();

        // Destruir el gráfico anterior si existe
        if (myChart1) myChart1.destroy();

        const chart_uno_años = data.matriculadosByYears.map((item) => item.Año);
        const chart_uno_matriculados = data.matriculadosByYears.map(
          (item) => item.Estudiantes_Matriculados
        );

        const ctx_uno = document
          .getElementById("chart_matriculados_by_anio")
          .getContext("2d");
        myChart1 = new Chart(ctx_uno, {
          type: "line", // Cambiar de 'bar' a 'line' para un gráfico de líneas
          data: {
            labels: chart_uno_años, // Los años
            datasets: [
              {
                label: "Estudiantes Matriculados",
                data: chart_uno_matriculados, // Los números de estudiantes matriculados
                backgroundColor: "rgba(75, 192, 192, 0.2)", // Color de fondo de la línea (ligera transparencia)
                borderColor: "rgba(75, 192, 192, 1)", // Color de la línea
                borderWidth: 2, // Grosor de la línea
                fill: true, // Rellenar debajo de la línea
                tension: 0.3, // Suavizar la línea
              },
            ],
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true, // Asegura que el eje Y empiece en 0
                ticks: {
                  stepSize: 10, // Controla el tamaño del paso entre los valores en el eje Y
                },
              },
            },
            plugins: {
              legend: {
                position: "top", // Mueve la leyenda a la parte superior del gráfico
              },
              tooltip: {
                callbacks: {
                  label: function (tooltipItem) {
                    return `Estudiantes: ${tooltipItem.raw}`; // Muestra el número de estudiantes en el tooltip
                  },
                },
              },
            },
          },
        });

        // Destruir el gráfico anterior si existe
        if (myChart2) myChart2.destroy();

        // Extraemos los datos de la API
        const chart_dos_grados = data.gradoYSeccionByYear.map(
          (item) => item.Grado
        );
        const chart_dos_niveles = data.gradoYSeccionByYear.map(
          (item) => item.Nivel
        );
        const chart_dos_matriculados = data.gradoYSeccionByYear.map(
          (item) => item.Estudiantes_Matriculados
        );

        // Filtrar grados de Primaria (1° a 6°)
        const gradosPrimaria = chart_dos_grados.filter(
          (grado, index) => chart_dos_niveles[index] === "Primaria"
        );
        const matriculadosPrimaria = chart_dos_matriculados.filter(
          (_, index) => chart_dos_niveles[index] === "Primaria"
        );

        // Filtrar grados de Secundaria (1° a 5°)
        const gradosSecundaria = chart_dos_grados.filter(
          (grado, index) => chart_dos_niveles[index] === "Secundaria"
        );
        const matriculadosSecundaria = chart_dos_matriculados.filter(
          (_, index) => chart_dos_niveles[index] === "Secundaria"
        );

        // Crear el gráfico de barras apiladas
        const ctx_dos = document
          .getElementById("chart_grado_seccion_by_anio")
          .getContext("2d");
        myChart2 = new Chart(ctx_dos, {
          type: "bar",
          data: {
            labels: gradosPrimaria.concat(gradosSecundaria), // Concatenar grados de Primaria y Secundaria
            datasets: [
              {
                label: "Primaria",
                data: matriculadosPrimaria,
                backgroundColor: "rgba(75, 192, 192, 0.5)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1,
              },
              {
                label: "Secundaria",
                data: matriculadosSecundaria,
                backgroundColor: "rgba(153, 102, 255, 0.5)",
                borderColor: "rgba(153, 102, 255, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
              },
            },
            plugins: {
              legend: {
                position: "top",
              },
            },
          },
        });

        $('#data--num-matriculas').fadeIn().html(data.gradoYSeccionByYear.length);
        $('#data--num-estudiantes').fadeIn().html(data.estudiantes.total_estudiantes);
        $('#data--num-usuarios').fadeIn().html(data.usuarios.total_usuarios);
        $('#data--num-cargos').fadeIn().html(data.cargos.total_roles);
      },
      error: function (xhr, status, error) {
        console.error("Hubo un problema al obtener los datos: ", error);
      },
    });
  }



  // Vuelve a renderizar cuando cambie el select
  $("#db_periodo_anual").on("change", function () {
    const anio_id = $(this).val();
    fetchAndRenderCharts(anio_id);
  });
  /////////////////////////////////////////////////////////////////////////////////////////////
})();
