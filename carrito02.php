<?php
// carrito02.php
session_start();
if (!isset($_SESSION['idCliente'])) { header("Location: login.php"); exit; }

require "funciones/conecta.php";
$con = conecta();
$id_cliente = $_SESSION['idCliente'];

$sql = "SELECT dp.precio_unitario, dp.cantidad, p.nombre, p.codigo
        FROM pedidos_productos dp
        JOIN pedidos pe ON dp.id_pedido = pe.id_pedido
        JOIN productos p ON dp.id_producto = p.id_producto
        WHERE pe.id_cliente = $id_cliente AND pe.status = 0";
$res = $con->query($sql);

if (!$res || $res->num_rows == 0) {
    header("Location: carrito01.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito Paso 2 - Resumen</title>
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

    <div class="carrito-contenedor-main" id="pantalla-resumen">
        <h2>RESUMEN DEL PEDIDO (PASO 2 / 2)</h2>
        
        <div class="carrito-encabezado-tabla">
            <div class="c-prod">PRODUCTO</div>
            <div class="c-cod">CÓDIGO</div>
            <div class="c-precio">PRECIO UNITARIO</div>
            <div class="c-cant">CANTIDAD</div>
            <div class="c-sub">SUBTOTAL</div>
        </div>

        <div class="carrito-lista">
            <?php 
            $total_general = 0;
            while ($row = $res->fetch_assoc()): 
                $subtotal = $row['cantidad'] * $row['precio_unitario'];
                $total_general += $subtotal;
            ?>
                <div class="renglon-carrito lectura">
                    <div class="c-prod"><span><?php echo strtoupper($row['nombre']); ?></span></div>
                    <div class="c-cod"><?php echo $row['codigo']; ?></div>
                    <div class="c-precio">$<?php echo number_format($row['precio_unitario'], 2); ?></div>
                    <div class="c-cant"><?php echo $row['cantidad']; ?></div>
                    <div class="c-sub">$<?php echo number_format($subtotal, 2); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="carrito-pie-totales">
            <div class="total-caja">
                TOTAL FINAL: <span>$<?php echo number_format($total_general, 2); ?></span>
            </div>
            
            <div class="botonera-pasos flex-end">
                <a href="carrito01.php" class="btn-regresar">← REGRESAR AL PASO 1</a>
                <button type="button" class="btn-finalizar" onclick="confirmarPedidoFinal()">FINALIZAR PEDIDO</button>
            </div>

            <div class="confirmar-final-bloque" id="confirmar-cierre-pedido" style="display:none;">
                <p>¿Estás completamente seguro de que deseas cerrar y finalizar este pedido?</p>
                <button type="button" class="btn-si-final" onclick="ejecutarCierrePedido()">SÍ, FINALIZAR</button>
                <button type="button" class="btn-no-final" onclick="cancelarCierrePedido()">CANCELAR</button>
            </div>
        </div>
    </div>

    <div class="carrito-contenedor-main exito-pantalla" id="pantalla-exito" style="display:none;">
        <div class="exito-recuadro">
            <span class="icono-check">✓</span>
            <h3>¡PEDIDO FINALIZADO CON ÉXITO!</h3>
            <p>Tu orden ha sido procesada correctamente y el estatus cambió a cerrado.</p>
            <a href="productos.php">VOLVER A LA TIENDA</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>