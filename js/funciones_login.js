// js/funciones_login.js
$(document).ready(function() {
    $('#formLogin').on('submit', function(e) {
        e.preventDefault();

        var correo = $('#correo').val().trim();
        var password = $('#password').val().trim();

        if (correo == '' || password == '') {
            $('#mensaje-login').html('<p class="error-msg">Todos los campos son obligatorios.</p>');
            setTimeout(function() { $('#mensaje-login').html(''); }, 3000);
            return;
        }

        $.ajax({
            url: 'validaUsuario.php',
            type: 'POST',
            dataType: 'text',
            data: {
                correo: correo,
                password: password
            },
            success: function(res) {
                if (res == 1) {
                    // Login correcto, redirige al home
                    window.location.href = 'index.php';
                } else {
                    // Datos incorrectos
                    $('#mensaje-login').html('<p class="error-msg">Correo o contraseña incorrectos.</p>');
                    setTimeout(function() { $('#mensaje-login').html(''); }, 3000);
                }
            },
            error: function() {
                $('#mensaje-login').html('<p class="error-msg">Error al conectar con el servidor.</p>');
                setTimeout(function() { $('#mensaje-login').html(''); }, 3000);
            }
        });
    });
});