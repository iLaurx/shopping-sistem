// js/funciones_carrito.js

// Motor AJAX para agregar productos al carrito (Index, Catálogo y Detalles)
function agregarAlCarrito(id_producto) {
    var cantidad = $('#cant_' + id_producto).val();

    if (cantidad <= 0 || cantidad == '') {
        $('#mensaje-carrito').html('<p style="color: red; font-weight:bold;">Ingresa una cantidad válida.</p>');
        setTimeout(function() { $('#mensaje-carrito').html(''); }, 3000);
        return;
    }

    $.ajax({
        url: 'insertarProducto.php',
        type: 'POST',
        dataType: 'text',
        data: { id_producto: id_producto, cantidad: cantidad },
        success: function(res) {
            if (res == 1) {
                $('#mensaje-carrito').html('<p style="color: green; font-weight:bold;">Producto agregado con éxito.</p>');
            } else {
                $('#mensaje-carrito').html('<p style="color: red; font-weight:bold;">Error al agregar el producto.</p>');
            }
            setTimeout(function() { $('#mensaje-carrito').html(''); }, 3000);
        },
        error: function() {
            $('#mensaje-carrito').html('<p style="color: red; font-weight:bold;">Error de red en el servidor.</p>');
        }
    });
}

// Modificación dinámica en vivo mediante evento Change
function modificarCantidad(id_detalle, nueva_cantidad) {
    if(nueva_cantidad <= 0 || nueva_cantidad == '') return;

    $.ajax({
        url: 'php/actualiza_cantidad.php',
        type: 'POST',
        data: { id_detalle: id_detalle, cantidad: nueva_cantidad },
        success: function(res) {
            if(res == 1) {
                // Obtener el precio unitario del atributo data de la celda
                var precio_unitario = parseFloat($('#fila_' + id_detalle + ' .c-precio').data('precio'));
                var nuevo_subtotal = precio_unitario * parseInt(nueva_cantidad);
                
                // Actualizar subtotal de la fila modificada
                $('#sub_' + id_detalle).text('$' + nuevo_subtotal.toFixed(2));
                
                // Recalcular el total general leyendo todas las filas dinámicamente
                recalcularTotalGeneral();
            }
        }
    });
}

function recalcularTotalGeneral() {
    var suma = 0;
    $('.subtotal-valor').each(function() {
        var texto = $(this).text().replace('$', '').replace(',', '');
        suma += parseFloat(texto);
    });
    $('#total-general-label').text('$' + suma.toFixed(2));
}

// Confirmaciones incrustadas de eliminación de renglón
function confirmarEliminacion(id_detalle) {
    $('#conf_' + id_detalle).fadeIn(200);
}

function cancelarEliminacion(id_detalle) {
    $('#conf_' + id_detalle).fadeOut(200);
}

function ejecutarBorrado(id_detalle) {
    $.ajax({
        url: 'php/elimina_carrito.php',
        type: 'POST',
        data: { id_detalle: id_detalle },
        success: function(res) {
            if(res == 1) {
                // Removemos el nodo del DOM suavemente con animación
                $('#fila_' + id_detalle).slideUp(300, function() {
                    $(this).remove();
                    recalcularTotalGeneral();
                    
                    // Si se vacía el carrito por completo en caliente, recargamos para pintar vista vacía
                    if($('.renglon-carrito').length == 0) {
                        window.location.reload();
                    }
                });
            }
        }
    });
}

// Confirmación y cierre final de orden
function confirmarPedidoFinal() {
    $('#confirmar-cierre-pedido').fadeIn(200);
}

function cancelarCierrePedido() {
    $('#confirmar-cierre-pedido').fadeOut(200);
}

function ejecutarCierrePedido() {
    $.ajax({
        url: 'php/finalizar_pedido.php',
        type: 'POST',
        success: function(res) {
            if(res == 1) {
                // Escondemos la mesa de trabajo del resumen y revelamos el aviso de éxito
                $('#pantalla-resumen').hide();
                $('#pantalla-exito').fadeIn(400);
            } else {
                alert('Ocurrió un error al procesar el cierre del pedido.');
            }
        }
    });
}