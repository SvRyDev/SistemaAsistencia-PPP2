
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


  const dropZone = document.getElementById('dropZone');
  const input = document.getElementById('dragFileInput');

  // Permite hacer clic sobre el área para seleccionar archivo
  dropZone.addEventListener('click', () => input.click());

  // Highlight cuando arrastran algo encima
  ['dragenter', 'dragover'].forEach(evt =>
    dropZone.addEventListener(evt, e => {
      e.preventDefault();
      dropZone.classList.add('bg-light');
    })
  );

  // Quitar highlight cuando sale o se suelta
  ['dragleave', 'drop'].forEach(evt =>
    dropZone.addEventListener(evt, e => {
      e.preventDefault();
      dropZone.classList.remove('bg-light');
    })
  );

  // Manejo del archivo soltado
  dropZone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
      input.files = e.dataTransfer.files; // Asigna archivo al input
      // Aquí puedes llamar tu lógica de carga o preview
      alert(`Archivo recibido: ${file.name}`);
    }
  });

