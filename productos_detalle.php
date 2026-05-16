<?php
// productos_detalle.php
session_start();
require "funciones/conecta.php";
$con = conecta();

// Captura y sanitización básica del ID recibido por la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta del producto específico validando que no esté eliminado
$sql = "SELECT id_producto, nombre, codigo, descripcion, precio, stock, imagen_url FROM productos WHERE id_producto = $id AND eliminado = 0";
$res = $con->query($sql);

if ($res->num_rows == 0) {
    header("Location: productos.php");
    exit;
}

$prod = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $prod['nombre']; ?> - Detalle</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
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

    <div class="detalle-contenedor">
        
        <div class="detalle-imagen">
            <img src="img/<?php echo $prod['imagen_url']; ?>" alt="<?php echo $prod['nombre']; ?>">
        </div>

        <div class="detalle-info">
            <h1><?php echo $prod['nombre']; ?></h1>
            <p class="detalle-codigo">CÓDIGO: <?php echo $prod['codigo']; ?></p>
            <p class="detalle-precio">$<?php echo number_format($prod['precio'], 2); ?></p>
            
            <div class="detalle-descripcion">
                <p class="txt-subtitulo">DESCRIPCIÓN</p>
                <p class="txt-cuerpo"><?php echo nl2br($prod['descripcion']); ?></p>
            </div>

            <p class="detalle-stock">STOCK DISPONIBLE: <span><?php echo $prod['stock']; ?> piezas</span></p>

            <?php 
            // El formulario de compra solo opera bajo sesión activa del cliente
            if (isset($_SESSION['idCliente'])): 
            ?>
                <div class="compra-controles-detalle">
                    <div class="control-fila">
                        <label>CANTIDAD:</label>
                        <input type="number" id="cant_<?php echo $prod['id_producto']; ?>" value="1" min="1" max="<?php echo $prod['stock']; ?>">
                    </div>
                    <button type="button" onclick="agregarAlCarrito(<?php echo $prod['id_producto']; ?>)">AGREGAR AL CARRITO</button>
                </div>
            <?php endif; ?>
            
            <div class="regresar-catalogo">
                <a href="productos.php">← VOLVER AL CATÁLOGO</a>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>