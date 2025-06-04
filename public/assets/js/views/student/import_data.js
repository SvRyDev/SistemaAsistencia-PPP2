
$(document).ready(function () {
  $('#uploadForm').on('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: base_url + '/student/readExcel',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
        beforeSend: function () {
            $('#previewTable').html('<p>Procesando archivo, por favor espere...</p>');
        },
      success: function (response) {
        $('#previewTable').html(response);
      },
      error: function () {
        $('#previewTable').html('<p style="color:red;">Error al procesar el archivo.</p>');
      }
    });
  });
});

