<?php
// php/actualiza_cantidad.php
session_start();
require "../funciones/conecta.php";

if (!isset($_SESSION['idCliente'])) { echo 0; exit; }

$con = conecta();
$id_detalle = isset($_POST['id_detalle']) ? intval($_POST['id_detalle']) : 0;
$cantidad   = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

if ($id_detalle > 0 && $cantidad > 0) {
    $sql = "UPDATE pedidos_productos SET cantidad = $cantidad WHERE id_detalle = $id_detalle";
    echo $con->query($sql) ? 1 : 0;
} else {
    echo 0;
}
?>