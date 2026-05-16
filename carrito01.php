<?php
// carrito01.php
session_start();
if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit;
}
require "funciones/conecta.php";
$con = conecta();
$id_cliente = $_SESSION['idCliente'];

// Traemos los detalles del pedido abierto del cliente
$sql = "SELECT dp.id_detalle, dp.cantidad, dp.precio_unitario, p.nombre, p.imagen_url, p.codigo
        FROM pedidos_productos dp
        JOIN pedidos pe ON dp.id_pedido = pe.id_pedido
        JOIN productos p ON dp.id_producto = p.id_producto
        WHERE pe.id_cliente = $id_cliente AND pe.status = 0";
$res = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito Paso 1 - Edición</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital@0;1&display=swap" rel="stylesheet">
    <script src="js/jquery-4.0.0.min.js"></script>
    <script src="js/funciones_carrito.js"></script>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="carrito-contenedor-main">
        <h2>CARRITO DE COMPRAS (PASO 1 / 2)</h2>
        <div id="notificacion-carrito"></div>

        <?php if (!$res || $res->num_rows == 0): ?>
            <div class="carrito-vacio">
                <p>Tu carrito está vacío. Explora el catálogo para agregar artículos.</p>
                <a href="productos.php">VER PRODUCTOS</a>
            </div>
        <?php else: ?>
            <div class="carrito-encabezado-tabla">
                <div class="c-prod">PRODUCTO</div>
                <div class="c-cod">CÓDIGO</div>
                <div class="c-precio">PRECIO</div>
                <div class="c-cant">CANTIDAD</div>
                <div class="c-sub">SUBTOTAL</div>
                <div class="c-acc">ACCION</div>
            </div>

            <div class="carrito-lista">
                <?php 
                $total_general = 0;
                while ($row = $res->fetch_assoc()): 
                    $subtotal = $row['cantidad'] * $row['precio_unitario'];
                    $total_general += $subtotal;
                ?>
                    <div class="renglon-carrito" id="fila_<?php echo $row['id_detalle']; ?>">
                        <div class="c-prod img-txt">
                            <img src="img/<?php echo $row['imagen_url']; ?>" alt="Preview">
                            <span><?php echo strtoupper($row['nombre']); ?></span>
                        </div>
                        <div class="c-cod"><?php echo $row['codigo']; ?></div>
                        <div class="c-precio" data-precio="<?php echo $row['precio_unitario']; ?>">
                            $<?php echo number_format($row['precio_unitario'], 2); ?>
                        </div>
                        <div class="c-cant">
                            <input type="number" value="<?php echo $row['cantidad']; ?>" min="1" 
                                   onchange="modificarCantidad(<?php echo $row['id_detalle']; ?>, this.value)">
                        </div>
                        <div class="c-sub subtotal-valor" id="sub_<?php echo $row['id_detalle']; ?>">
                            $<?php echo number_format($subtotal, 2); ?>
                        </div>
                        <div class="c-acc">
                            <button type="button" class="btn-eliminar-item" 
                                    onclick="confirmarEliminacion(<?php echo $row['id_detalle']; ?>)">ELIMINAR</button>
                        </div>
                        
                        <div class="confirmar-borrar-bloque" id="conf_<?php echo $row['id_detalle']; ?>" style="display:none;">
                            <span>¿Seguro que deseas eliminar este artículo?</span>
                            <button type="button" class="btn-si" onclick="ejecutarBorrado(<?php echo $row['id_detalle']; ?>)">SÍ</button>
                            <button type="button" class="btn-no" onclick="cancelarEliminacion(<?php echo $row['id_detalle']; ?>)">NO</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="carrito-pie-totales">
                <div class="total-caja">
                    TOTAL ESTIMADO: <span id="total-general-label">$<?php echo number_format($total_general, 2); ?></span>
                </div>
                <div class="botonera-pasos">
                    <a href="carrito02.php" class="btn-continuar">CONTINUAR AL PASO 2 →</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>