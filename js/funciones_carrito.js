function agregarAlCarrito(id_producto) {
    var cantidad = $('#cant_' + id_producto).val();

    if (cantidad <= 0 || cantidad == '') {
        $('#mensaje-carrito').html('<p style="color: red;">Por favor ingresa una cantidad válida.</p>');
        setTimeout(function() {
            $('#mensaje-carrito').html('');
        }, 3000);
        return;
    }

    $.ajax({
        url: 'insertar_carrito.php',
        type: 'POST',
        dataType: 'text',
        data: {
            id_producto: id_producto,
            cantidad: cantidad
        },
        success: function(res) {
            if (res == 1) {
                $('#mensaje-carrito').html('<p style="color: green;">Producto agregado con éxito.</p>');
            } else {
                $('#mensaje-carrito').html('<p style="color: red;">Error al agregar el producto al carrito.</p>');
            }
            
            // Ocultar la notificación automáticamente tras 3 segundos sin usar ventanas alert()
            setTimeout(function() {
                $('#mensaje-carrito').html('');
            }, 3000);
        },
        error: function() {
            $('#mensaje-carrito').html('<p style="color: red;">Error en la comunicación con el servidor.</p>');
        }
    });
}