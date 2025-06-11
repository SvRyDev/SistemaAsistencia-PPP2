$('#btnDownload').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: base_url + '/carnet/generateCarnet',
        method: 'GET',
        xhrFields: {
            responseType: 'blob' // Importante para manejar archivos binarios
        },
        success: function(data) {
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'carnet.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        },
        error: function() {
            alert('Error al generar el PDF');
        }
    });
});


console.log('carnet.generate.js loaded');

$(document).ready(function () {
    $('#generatePreviewCarnetButton').on('click', function () {

        // Arma la URL al script PHP que genera el PDF
        var previewUrl =  base_url + '/carnet/previewCarnet';

        // Asigna esa URL al iframe para mostrar el PDF generado
        $('#preview-carnet').attr('src', previewUrl );
    });
});
