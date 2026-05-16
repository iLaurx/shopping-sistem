<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Sistema de Pedidos</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital@0;1&display=swap" rel="stylesheet">
    <script src="js/jquery-4.0.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#formContacto').on('submit', function(e) {
            e.preventDefault();
            
            var nombre = $('#nombre').val().trim();
            var correo = $('#correo').val().trim();
            var asunto = $('#asunto').val().trim();
            var mensaje = $('#mensaje').val().trim();

            if (nombre == '' || correo == '' || asunto == '' || mensaje == '') {
                $('#mensaje-contacto').html('<p class="error-msg">Todos los campos son obligatorios.</p>');
                return;
            }

            // Cambiamos el texto del botón mientras procesa
            $('#btn-enviar').prop('disabled', true).text('ENVIANDO...');

            $.ajax({
                url: 'recibe.php',
                type: 'POST',
                data: {nombre: nombre, correo: correo, asunto: asunto, mensaje: mensaje},
                success: function(res) {
                    if (res == 1) {
                        $('#mensaje-contacto').html('<p style="color: green; font-weight: bold; margin-top: 15px;">Mensaje enviado con éxito.</p>');
                        $('#formContacto')[0].reset(); // Limpia el formulario
                    } else {
                        $('#mensaje-contacto').html('<p class="error-msg">Error al enviar. Revisa la configuración SMTP de XAMPP.</p>');
                    }
                    $('#btn-enviar').prop('disabled', false).text('ENVIAR MENSAJE');
                },
                error: function() {
                    $('#mensaje-contacto').html('<p class="error-msg">Error de comunicación con el servidor.</p>');
                    $('#btn-enviar').prop('disabled', false).text('ENVIAR MENSAJE');
                }
            });
        });
    });
    </script>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="contacto-contenedor">
        <div class="contacto-bloque">
            <h2>CONTÁCTANOS</h2>
            <form id="formContacto">
                <div class="contacto-campo">
                    <label for="nombre">NOMBRE</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="contacto-campo">
                    <label for="correo">CORREO ELECTRÓNICO</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="contacto-campo">
                    <label for="asunto">ASUNTO</label>
                    <input type="text" id="asunto" name="asunto" required>
                </div>
                <div class="contacto-campo">
                    <label for="mensaje">MENSAJE</label>
                    <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                </div>
                <div class="contacto-controles">
                    <button type="submit" id="btn-enviar">ENVIAR MENSAJE</button>
                </div>
            </form>
            <div id="mensaje-contacto"></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>