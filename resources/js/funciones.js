$(document).ready(function () {
    $('#form-insertar').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let url = $form.data('url');
        let formData = $form.serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function (response) {
                $('#mensaje')
                    .removeClass('error')
                    .addClass(response.class)
                    .html(response.mensaje)
                    .fadeIn();

                setTimeout(function () {
                    window.location.href = response.ruta;
                }, 2000);
            },
            error: function (response) {
                let res = response.responseJSON;
                let mensaje = '';

                if (typeof res.mensaje === 'object') {
                    for (let campo in res.mensaje) {
                        res.mensaje[campo].forEach(function (error) {
                            mensaje += `${error} <br>`;
                        });
                    }
                } else {
                    mensaje = res.mensaje;
                }

                $('#mensaje')
                    .removeClass('success')
                    .addClass(res.class)
                    .html(mensaje)
                    .fadeIn();

                setTimeout(function () {
                    $('#mensaje').fadeOut();
                }, 2000);
            }
        });
    });
});
