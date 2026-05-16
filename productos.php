<?php
// productos.php
session_start();
require "funciones/conecta.php";
$con = conecta();

// Consulta de TODOS los productos no eliminados de la tabla
$sql = "SELECT id_producto, nombre, codigo, precio, imagen_url FROM productos WHERE eliminado = 0";
$res = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Sistema de Pedidos</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-4.0.0.min.js"></script>
    <script src="js/funciones_carrito.js"></script>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="fila">
        <div class="col">
            <div id="mensaje-carrito"></div>
        </div>
    </div>

    <div class="fila">
        <div class="col">
            <h2>Todos los Productos</h2>
        </div>
    </div>

    <div class="contenedor-productos">
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="producto-bloque">
                <a href="productos_detalle.php?id=<?php echo $row['id_producto']; ?>">
                    <img src="img/<?php echo $row['imagen_url']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <h3><?php echo $row['nombre']; ?></h3>
                </a>
                <p class="prod-codigo">CÓDIGO: <?php echo $row['codigo']; ?></p>
                <p class="prod-precio">$<?php echo number_format($row['precio'], 2); ?></p>

                <?php 
                // Cajas de cantidad y botones condicionados al Login del cliente
                if (isset($_SESSION['idCliente'])): 
                ?>
                    <div class="compra-controles">
                        <input type="number" id="cant_<?php echo $row['id_producto']; ?>" value="1" min="1">
                        <button type="button" onclick="agregarAlCarrito(<?php echo $row['id_producto']; ?>)">AGREGAR AL CARRITO</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>