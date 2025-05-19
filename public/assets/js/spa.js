$(document).ready(function() {

    function loadPage(url, pushState = true) {
        $.ajax({
            url: url,
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(data) {
                $('#app-content').html(data);
                if (pushState) {
                    history.pushState(null, '', url);
                }
            },
            error: function() {
                $('#app-content').html('<p>Error al cargar la página.</p>');
            }
        });
    }

    // Capturar clicks en enlaces con clase ajax-link
    $(document).on('click', 'a.ajax-link', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        loadPage(url);
    });

    // Manejar botones atrás/adelante
    window.onpopstate = function() {
        loadPage(location.pathname, false);
    };

    // Detectar recarga con F5 y cargar vista actual
    if (performance.navigation.type === 1) {
        loadPage(location.pathname, false);
    }

});