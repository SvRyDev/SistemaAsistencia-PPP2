let initialAcademicYear = "";
let initialStartDate = "";
let initialEndDate = "";

//horas 
let initialEntryTime = "";
let initialExitTime = "";


$(document).ready(function () {
  // Selección de elementos
  const $yearInput = $("#year-academic");
  const $startDate = $("#start-date-academic");
  const $endDate = $("#end-date-academic");
  const $editCheck = $("#edit-dates-check");


  // ------------------------------
  // Funcion para validar horas 
  // ------------------------------
  function validarHoras() {
    const entryVal = $("#entry-time").val();
    const exitVal = $("#exit-time").val();

    if (!entryVal || !exitVal) return;

    const entry = parseTime(entryVal);
    const exit = parseTime(exitVal);

    if (exit <= entry) {
      mostrarError(
        "Horario inválido",
        "La hora de salida debe ser posterior a la hora de entrada."
      );
      $("#exit-time").val("");
    }
  }

  function parseTime(timeStr) {
    const [hours, minutes] = timeStr.split(":").map(Number);
    return hours * 60 + minutes; // Convierte a minutos totales
  }



  // -------------------------------
  // Función para validar fechas
  // -------------------------------
  function validarFechas() {
    const startVal = $startDate.val();
    const endVal = $endDate.val();

    if (!startVal || !endVal) return;

    const start = new Date(startVal);
    const end = new Date(endVal);

    // Validar inicio menor o igual a fin
    if (start > end) {
      mostrarError(
        "Fechas inválidas",
        "La fecha de inicio no puede ser mayor que la de finalización."
      );
      limpiarFechas();
      return;
    }

    // Validar mismo año
    if (start.getFullYear() !== end.getFullYear()) {
      mostrarError(
        "Año inconsistente",
        "Ambas fechas deben estar dentro del mismo año académico."
      );
      limpiarFechas();
    }
  }

  // -------------------------------
  // Utilidades
  // -------------------------------
  function mostrarError(titulo, mensaje) {
    Swal.fire({ icon: "error", title: titulo, text: mensaje });
  }

  function limpiarFechas() {
    $startDate.val("");
    $endDate.val("");
  }

  function setFechasHabilitadas(habilitar) {
    $startDate.prop("disabled", !habilitar);
    $endDate.prop("disabled", !habilitar);
  }

  // -------------------------------
  // Evento checkbox: habilitar edición de fechas
  // -------------------------------
  $editCheck.on("change", function () {
    if (this.checked) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Modificar las fechas del periodo académico puede afectar reportes y registros.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          setFechasHabilitadas(true);
          // Restaurar valores anteriores
          limpiarFechas()
        } else {
          this.checked = false;
        }
      });
    } else {
      setFechasHabilitadas(false);
      // Restaurar valores al desactivar edición
      $startDate.val(initialStartDate);
      $endDate.val(initialEndDate);
    }
  });

  $("#edit-time-check").on("change", function () {
    if (this.checked) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Cambiar las horas de entrada y salida puede alterar la lógica de asistencia.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          $("#entry-time, #exit-time, #time-tolerance").prop("disabled", false);
        } else {
          this.checked = false;
        }
      });
    } else {
      $("#entry-time, #exit-time, #time-tolerance").prop("disabled", true);
      $("#entry-time").val(initialEntryTime);
      $("#exit-time").val(initialExitTime);
      $("#exit-time").val(initialExitTime);
    }
  });

  $("#entry-time, #exit-time").on("change", validarHoras);

  // -------------------------------
  // Validación dinámica de fechas
  // -------------------------------
  $startDate.on("change", validarFechas);
  $endDate.on("change", validarFechas);

  // -------------------------------
  // Cargar configuración inicial
  // -------------------------------
  $.ajax({
    url: base_url + "/setting/getConfig",
    type: "POST",
    dataType: "json",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    success: function (response) {
      if (response.status === "success") {
        const s = response.setting;

        $("#name-school").val(s.name_school);
        $yearInput.val(s.academic_year);
        $("#entry-time").val(s.entry_time);
        $("#time-tolerance").val(s.time_tolerance);
        initialStartDate = s.start_date;
        initialEndDate = s.end_date;
        $startDate.val(initialStartDate);
        $endDate.val(initialEndDate);
        $("#last-updated").html(formatearFechaLegible(s.updated_at));

        initialAcademicYear = s.academic_year;
        initialEntryTime = s.entry_time;
        initialExitTime = s.exit_time;
        $("#exit-time").val(initialExitTime);

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
  // Guardar configuración
  // -------------------------------
  $("#btnSaveSetting").on("click", function (e) {
    e.preventDefault();

    const formData = {
      id: 1, // ⚠️ Asegúrate de que sea dinámico si es necesario
      name_school: $("#name-school").val().trim(),
      entry_time: $("#entry-time").val(),
      exit_time: $("#exit-time").val(),
      time_tolerance: $("#time-tolerance").val(),
      start_date: $startDate.val(),
      end_date: $endDate.val(),
      time_zone: "America/Lima",
    };

    $.ajax({
      url: base_url + "/setting/saveConfig",
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      dataType: "json",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      beforeSend: function () {
        // Cierra cualquier modal anterior si aún está abierto
        if (Swal.isVisible()) Swal.close();

        Swal.fire({
          title: "Guardando configuración...",
          text: "Por favor, espera.",
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
      }


      ,

      success: function (response) {
        Swal.fire({
          title: response.status === "success" ? "Éxito" : "Error",
          text: response.message,
          icon: response.status,
          confirmButtonText: "Aceptar",
        });
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        mostrarError("Error", "No se pudo actualizar la configuración.");
      },
    });
  });
});
