$("#btnExportBackup").on("click", function () {
  var $btn = $(this);
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
      const filename =
        "backup_" + new Date().toISOString().replace(/[:.]/g, "-") + ".sql";
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);
    },
    error: function () {
      alert("Error al generar el backup.");
    },
    complete: function () {
      $btn
        .prop("disabled", false)
        .html('<i class="fas fa-download mr-1"></i> Descargar Backup (.sql)');
    },
  });
});


$("#btnImportBackup").on("click", function (e) {
  e.preventDefault(); // Evita el comportamiento por defecto

  var form = $("#formImportBackup")[0]; // ID del form
  var formData = new FormData(form);

  // Desactivar botón durante la subida
  var $btn = $(this);
  $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Importando...');

  $.ajax({
    url: base_url + "/backup/import", // Ajusta la ruta según tu controlador
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      alert("✅ " + response); // O usar un modal de éxito
    },
    error: function (xhr) {
      alert("❌ Error: " + xhr.responseText);
    },
    complete: function () {
      $btn.prop("disabled", false).html('<i class="fas fa-sync-alt mr-1"></i> Restaurar desde Archivo');
    },
  });
});
