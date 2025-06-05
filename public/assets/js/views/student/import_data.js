
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





const $dropZone = $('#dropZone');
const $fileInput = $('#dragFileInput');
const $fileLabel = $('.custom-file-label');

// Función para actualizar el nombre del archivo en el input y en el dropZone
function updateFileNameDisplay(file) {
  const fileName = file.name;

  // Cambia el texto del label del input
  $fileLabel.text(fileName);

  // Cambia el contenido del dropZone para mostrar el nombre
  $dropZone.html(`
      <i class="fas fa-file-alt fa-3x mb-3 text-primary"></i>
      <p class="h5 m-0">Archivo seleccionado:</p>
      <p class="font-weight-bold">${fileName}</p>
      <p class="small">(Puedes soltar otro archivo o hacer clic para cambiarlo)</p>
    `);
}

// Click en el área de dropZone abre el selector
$dropZone.on('click', () => $fileInput.click());

// Arrastrar archivo sobre el área: activar borde rojo (opcional)
$dropZone.on('dragenter dragover', e => {
  e.preventDefault();
  $dropZone.addClass('border-danger');
});

// Salir o soltar: quitar el estilo
$dropZone.on('dragleave drop', e => {
  e.preventDefault();
  $dropZone.removeClass('border-danger');
});

// Cuando se suelta un archivo
$dropZone.on('drop', e => {
  e.preventDefault();
  const file = e.originalEvent.dataTransfer.files[0];
  if (file) {
    // Asignar el archivo al input manualmente
    const dt = new DataTransfer();
    dt.items.add(file);
    $fileInput[0].files = dt.files;

    // Mostrar el nombre
    updateFileNameDisplay(file);
  }
});

// Cuando se selecciona desde el input
$fileInput.on('change', function () {
  const file = this.files[0];
  if (file) {
    updateFileNameDisplay(file);
  }
});





var table = new DataTable('#previewTableData', {
  searching: false,      // Oculta la caja de búsqueda
  pageLength: 20,         // Muestra 20 registros por página
  lengthChange: false,    // Oculta el menú desplegable de cantidad de registros
  paging: true,           // Asegura que la paginación esté activa
});



// Ya no necesitas checkbox manual ni #select-all,
// DataTables Select manejará la selección con click en celdas

