<?php
// index.php
session_start();
require "funciones/conecta.php";
$con = conecta();

// Consulta para obtener una promoción al azar
$sqlBanner = "SELECT imagen_url FROM promociones WHERE activa = 1 ORDER BY RAND() LIMIT 1";
$resBanner = $con->query($sqlBanner);
$banner = $resBanner->fetch_assoc();

// Consulta para obtener 6 productos al azar sin eliminados
$sqlProductos = "SELECT id_producto, nombre, codigo, precio, imagen_url FROM productos WHERE eliminado = 0 ORDER BY RAND() LIMIT 6";
$resProductos = $con->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Sistema de Pedidos</title>
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

    <div class="fila">
        <div class="col">
            <div id="mensaje-carrito"></div>
        </div>
    </div>
    
    <div class="banner-hero">
        <?php if ($banner): ?>
            <img src="img/<?php echo $banner['imagen_url']; ?>" alt="Promoción del Día">
        <?php else: ?>
            <p style="text-align: center; padding: 50px;">No hay promociones disponibles.</p>
        <?php endif; ?>
    </div>

    <div class="fila">
        <div class="col">
            <h2>Productos Destacados</h2>
        </div>
    </div>

    <div class="contenedor-productos">
        <?php while ($row = $resProductos->fetch_assoc()): ?>
            <div class="producto-bloque">
                <a href="productos_detalle.php?id=<?php echo $row['id_producto']; ?>">
                    <img src="img/<?php echo $row['imagen_url']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <h3><?php echo $row['nombre']; ?></h3>
                </a>
                <p>Código: <?php echo $row['codigo']; ?></p>
                <p>Precio: $<?php echo number_format($row['precio'], 2); ?></p>

                <?php 
                // Controles de cantidad y botón exclusivos para clientes logueados
                if (isset($_SESSION['idCliente'])): 
                ?>
                    <div class="compra-controles">
                        <input type="number" id="cant_<?php echo $row['id_producto']; ?>" value="1" min="1">
                        <button type="button" onclick="agregarAlCarrito(<?php echo $row['id_producto']; ?>)">Agregar al carrito</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>