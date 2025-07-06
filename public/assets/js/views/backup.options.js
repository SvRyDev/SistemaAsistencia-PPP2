$(document).ready(function () {
  $.ajax({
    url: base_url + "/setting/getConfig", // Ajusta la URL si es necesario
    method: "POST",
    dataType: "json",
    success: function (data) {
      if (data.status === "success" && data.setting) {
        const fechas = data.setting;

        // Carga la fecha de importación
        if (fechas.last_date_import) {
          $("#fecha_importacion").text(formatearFechaLegible(fechas.last_date_import));
        } else {
          $("#fecha_importacion").text("Sin registro");
        }

        // Carga la fecha de exportación
        if (fechas.last_date_export) {
          $("#fecha_exportacion").text(formatearFechaLegible(fechas.last_date_export));
        } else {
          $("#fecha_exportacion").text("Sin registro");
        }
      }
    },
    error: function () {
      console.warn("No se pudieron cargar las fechas de importación/exportación.");
    }
  });
});




$("#btnExportBackup").on("click", function () {
  Swal.fire({
    title: "¿Exportar Backup?",
    text: "Se generará un archivo con la copia actual de la base de datos.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, exportar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#28a745",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      var $btn = $("#btnExportBackup");
      $btn
        .prop("disabled", true)
        .html('<i class="fas fa-spinner fa-spin mr-1"></i> Generando...');

      $.ajax({
        url: base_url + "/backup/export",
        method: "GET",
        xhrFields: {
          responseType: "blob", // Esperamos un archivo binario
        },
        success: function (data, status, xhr) {
          const blob = new Blob([data], {
            type: xhr.getResponseHeader("Content-Type"),
          });
          const url = window.URL.createObjectURL(blob);

          const a = document.createElement("a");
          a.href = url;

          const disposition = xhr.getResponseHeader("Content-Disposition");
          let filename =
            "backup_" + new Date().toISOString().replace(/[:.]/g, "-") + ".zip";
          if (disposition && disposition.indexOf("filename=") !== -1) {
            filename = disposition.split("filename=")[1].replace(/"/g, "").trim();
          }

          a.download = filename;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          window.URL.revokeObjectURL(url);

          Swal.fire({
            icon: "success",
            title: "Backup exportado",
            text: "La copia de seguridad esta listo para ser descargado.",
            confirmButtonColor: "#28a745"
          });
        },
        error: function () {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo generar el archivo de respaldo.",
            confirmButtonColor: "#dc3545"
          });
        },
        complete: function () {
          $btn
            .prop("disabled", false)
            .html('<i class="fas fa-download mr-1"></i> Descargar Backup (.zip)');
        }
      });
    }
  });
});


$("#btnImportBackup").on("click", function (e) {
  e.preventDefault(); // Evita el submit por defecto

  var fileInput = $("input[name='archivo_backup']")[0];
  if (!fileInput.files || fileInput.files.length === 0) {
    Swal.fire({
      icon: "info",
      title: "Archivo requerido",
      text: "Por favor selecciona un archivo de respaldo antes de continuar.",
      confirmButtonColor: "#3085d6"
    });
    return; // Detiene la ejecución
  }

  // Confirmación con Swal
  Swal.fire({
    title: "¿Estás seguro?",
    text: "Esta acción sobrescribirá completamente los datos actuales de la base de datos.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, restaurar backup",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      var form = $("#formImportBackup")[0];
      var formData = new FormData(form);

      var $btn = $("#btnImportBackup");
      $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Importando...');

      $.ajax({
        url: base_url + "/backup/import",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          Swal.fire({
            icon: "success",
            title: "Importación completada",
            text: response,
            confirmButtonColor: "#28a745"
          });
        },
        error: function (xhr) {
          Swal.fire({
            icon: "error",
            title: "Error al importar",
            html: xhr.responseText,
            confirmButtonColor: "#dc3545"
          });
        },
        complete: function () {
          $btn.prop("disabled", false).html('<i class="fas fa-sync-alt mr-1"></i> Restaurar desde Archivo');
        }
      });
    }
  });
});


$("#btnResetSystem").on("click", function (e) {
  e.preventDefault();

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Esto eliminará todos los registros de estudiantes, carnets y asistencias. No podrás deshacer esta acción.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, restaurar sistema",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      var $btn = $(this);
      $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Restaurando...');

      $.ajax({
        url: base_url + "/backup/reset", // Cambia según tu ruta/controlador
        method: "POST",
        dataType: "json",
        success: function (res) {
          Swal.fire({
            icon: "success",
            title: "Sistema restaurado",
            text: res.message,
            confirmButtonColor: "#28a745"
          });
        },
        error: function (xhr) {
          Swal.fire({
            icon: "error",
            title: "Error al restaurar",
            html: xhr.responseText || "Ocurrió un error inesperado.",
            confirmButtonColor: "#dc3545"
          });
        },
        complete: function () {
          $btn.prop("disabled", false).html('<i class="fas fa-undo-alt mr-1"></i> Restaurar sistema');
        }
      });
    }
  });
});
