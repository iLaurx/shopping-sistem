<?php
// insertarProducto.php
session_start();
require "funciones/conecta.php";

if (!isset($_SESSION['idCliente'])) {
    echo 0;
    exit;
}

$con = conecta();
$id_cliente  = $_SESSION['idCliente'];
$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
$cantidad    = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

if ($id_producto <= 0 || $cantidad <= 0) {
    echo 0;
    exit;
}

// Verificar si el cliente ya tiene un pedido abierto (status = 0)
$sqlPedido = "SELECT id_pedido FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$resPedido = $con->query($sqlPedido);

if ($resPedido && $resPedido->num_rows > 0) {
    $pedido = $resPedido->fetch_assoc();
    $id_pedido = $pedido['id_pedido'];
} else {
    // Si no hay, se crea la cabecera del pedido
    $sqlNuevo = "INSERT INTO pedidos (id_cliente, fecha, status) VALUES ($id_cliente, NOW(), 0)";
    if ($con->query($sqlNuevo)) {
        $id_pedido = $con->insert_id;
    } else {
        echo 0;
        exit;
    }
}

// Obtener el precio unitario actual del producto
$sqlPrecio = "SELECT precio FROM productos WHERE id_producto = $id_producto AND eliminado = 0";
$resPrecio = $con->query($sqlPrecio);
if (!$resPrecio || $resPrecio->num_rows == 0) {
    echo 0;
    exit;
}
$prodData = $resPrecio->fetch_assoc();
$precio_unitario = $prodData['precio'];

// Verificar si el producto ya existe en los detalles de ese pedido abierto
$sqlDetalle = "SELECT id_detalle, cantidad FROM pedidos_productos WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
$resDetalle = $con->query($sqlDetalle);

if ($resDetalle && $resDetalle->num_rows > 0) {
    // Si ya existe, se acumula la cantidad solicitada
    $detalle = $resDetalle->fetch_assoc();
    $nueva_cantidad = $detalle['cantidad'] + $cantidad;
    $sqlUp = "UPDATE pedidos_productos SET cantidad = $nueva_cantidad WHERE id_detalle = " . $detalle['id_detalle'];
    echo $con->query($sqlUp) ? 1 : 0;
} else {
    // Si es nuevo en el carrito, se inserta el registro completo
    $sqlIn = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio_unitario) 
              VALUES ($id_pedido, $id_producto, $cantidad, $precio_unitario)";
    echo $con->query($sqlIn) ? 1 : 0;
}
?>