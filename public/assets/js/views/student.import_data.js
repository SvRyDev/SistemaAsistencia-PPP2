let archivoCargado = false;

$(document).ready(function () {
  $('#excelFile').on('change', function () {
    $('#uploadForm').submit();
  });

  $('#uploadForm').on('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: base_url + '/student/readExcel',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function () {
          $('#previewTableData tbody').html(`
      <tr>
        <td colspan="6" style="text-align:center;">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </td>
      </tr>
    `);
      },
      success: function (response) {
        if (response.status === 'success' && Array.isArray(response.data)) {
          const rows = response.data;
          let html = '';

          rows.forEach((row, index) => {
            html += '<tr>';
            html += `<td>${index + 1}</td>`;
            html += `<td>${row.A || ''}</td>`;
            html += `<td>${row.B || ''}</td>`;
            html += `<td>${row.C || ''}</td>`;
            html += `<td>${row.D || ''}</td>`;
            html += `<td>${row.E || ''}</td>`;
            html += '</tr>';
          });

          $('#countData').text(rows.length);


          $('#div-button-import').removeClass('d-none');
          $('#importButton').removeClass('disabled');

          // Reiniciar DataTable si ya existe
          if ($.fn.DataTable.isDataTable('#previewTableData')) {
            $('#previewTableData').DataTable().clear().destroy();
          }

          $('#previewTableData tbody').html(html);

          $('#previewTableData').DataTable({
            searching: false,
            pageLength: 10,
            lengthChange: false,
            paging: true,
            info: true
          });

          // Marcar que ya hay un archivo cargado
          archivoCargado = true;
        } else {
          $('#previewTableData tbody').html(`
            <tr>
              <td colspan="6" style="color:red; text-align:center;">
                ${response.message || 'No se encontraron datos.'}
              </td>
            </tr>
          `);
        }
      },
      error: function (xhr) {
        let msg = 'Error al procesar el archivo.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }

        $('#previewTableData tbody').html(`
          <tr>
            <td colspan="6" style="color:red; text-align:center;">${msg}</td>
          </tr>
        `);

        $('#countData').text('0');
        $('#countDataFounded').removeClass('text-success').addClass('text-muted');
        $('#div-button-import').addClass('d-none');
        $('#importButton').addClass('disabled');


        archivoCargado = false; // Reiniciar estado si hay error
      }
    });
  });

  $('#btn-import-excel').on('click', function () {
    if (archivoCargado) {
      Swal.fire({
        title: '¿Quieres reemplazar el archivo cargado?',
        text: 'Ya hay un archivo procesado. Si continúas, se reemplazará.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, reemplazar',
        cancelButtonText: 'Cancelar',
        showClass: {
          popup: 'animate__animated animate__flipInX'
        },
        hideClass: {
          popup: 'animate__animated animate__flipOutX'
        },
        didOpen: () => {
          // Cambiar duración de animación al vuelo
          document.querySelector('.swal2-popup').style.animationDuration = '500ms';
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $('#excelFile').click();
        }
      });
    } else {
      $('#excelFile').click();
    }

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



// Función para activar el botón de importación
$('#importButton').on('click', function () {
  console.log('Botón de importación activado');
  if (archivoCargado) {
    Swal.fire({
      title: '¿Estás seguro de importar los datos?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'success',
      showCancelButton: true,
      confirmButtonText: 'Sí, importar',
      cancelButtonText: 'Cancelar',
      showClass: {
        popup: 'animate__animated animate__flipInX'
      },
      hideClass: {
        popup: 'animate__animated animate__flipOutX'
      },
      didOpen: () => {
        // Cambiar duración de animación al vuelo
        document.querySelector('.swal2-popup').style.animationDuration = '500ms';
      }
    }).then((result) => {
      if (result.isConfirmed) {

        // Obtener todos los datos del DataTable
        const table = $('#previewTableData').DataTable();
        const data = table.rows().data().toArray(); // Array con todos los registros



        // Opcional: transformar cada fila si es necesario
        const datosProcesados = data.map(row => ({
          dni: row[1],
          nombres: row[2],
          apellidos: row[3],
          grado: row[4],
          seccion: row[5]
        }));

        $.ajax({
          url: base_url + '/student/importData',
          type: 'POST',
          data: JSON.stringify({ alumnos: datosProcesados }),
          contentType: 'application/json',
          success: function (response) {
            if (response.status === 'success') {
              Swal.fire('Importación exitosa', response.message, 'success');
              // Aquí puedes recargar la tabla o realizar otras acciones
            } else {
              Swal.fire('Error', response.message, 'error');
            }
          },
          error: function (xhr) {
            let msg = 'Error al importar los datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire('Error', msg, 'error');
          }
        });

      }
    });
  } else {
    Swal.fire('No hay datos para importar', '', 'info');
  }
});