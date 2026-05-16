<?php
// pedidos_lista.php
session_start();

// Forzar que solo entren clientes logueados
if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit;
}

require "funciones/conecta.php";
$con = conecta();
$id_cliente = $_SESSION['idCliente'];

// Consulta para obtener las cabeceras de los pedidos ya finalizados (status = 1)
$sqlPedidos = "SELECT id_pedido, fecha FROM pedidos WHERE id_cliente = $id_cliente AND status = 1 ORDER BY fecha DESC";
$resPedidos = $con->query($sqlPedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos - Sistema de Pedidos</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital@0;1&display=swap" rel="stylesheet">
    <script src="js/jquery-4.0.0.min.js"></script>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="pedidos-contenedor-main">
        <h2>MIS PEDIDOS REALIZADOS</h2>

        <?php if (!$resPedidos || $resPedidos->num_rows == 0): ?>
            <div class="pedidos-vacio">
                <p>Aún no has realizado ningún pedido en la plataforma.</p>
                <a href="productos.php">IR AL CATÁLOGO</a>
            </div>
        <?php else: ?>
            
            <div class="pedidos-encabezado-tabla">
                <div class="col-id">FOLIO / ID PEDIDO</div>
                <div class="col-fecha">FECHA DE COMPRA</div>
                <div class="col-articulos">CANT. ARTÍCULOS</div>
                <div class="col-total">TOTAL DEL PEDIDO</div>
            </div>

            <div class="pedidos-lista">
                <?php 
                while ($pedido = $resPedidos->fetch_assoc()): 
                    $id_pedido = $pedido['id_pedido'];

                    // Consulta secundaria rápida para sacar el total de piezas y el costo de este pedido específico
                    $sqlTotales = "SELECT SUM(cantidad) as total_piezas, SUM(cantidad * precio_unitario) as total_dinero 
                                   FROM pedidos_productos WHERE id_pedido = $id_pedido";
                    $resTotales = $con->query($sqlTotales);
                    $totales = $resTotales->fetch_assoc();
                    
                    $piezas = isset($totales['total_piezas']) ? $totales['total_piezas'] : 0;
                    $monto  = isset($totales['total_dinero']) ? $totales['total_dinero'] : 0.00;
                ?>
                    <div class="renglon-pedido">
                        <div class="col-id">#<?php echo str_pad($id_pedido, 6, "0", STR_PAD_LEFT); ?></div>
                        <div class="col-fecha"><?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?></div>
                        <div class="col-articulos"><?php echo $piezas; ?> piezas</div>
                        <div class="col-total">$<?php echo number_format($monto, 2); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pedidos-pie">
                <a href="productos.php" class="btn-volver-tienda">← SEGUIR COMPRANDO</a>
            </div>

        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>