<?php
// php/finalizar_pedido.php
session_start();
require "../funciones/conecta.php";

if (!isset($_SESSION['idCliente'])) { echo 0; exit; }

$con = conecta();
$id_cliente = $_SESSION['idCliente'];

// Pasamos el estatus del pedido abierto (0) a Finalizado (1)
$sql = "UPDATE pedidos SET status = 1 WHERE id_cliente = $id_cliente AND status = 0";
echo $con->query($sql) ? 1 : 0;
?>