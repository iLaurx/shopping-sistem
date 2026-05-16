<?php
// validaUsuario.php
session_start();
require "funciones/conecta.php";
$con = conecta();

$correo = isset($_POST['correo']) ? $con->real_escape_string(trim($_POST['correo'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($correo != '' && $password != '') {
    $sql = "SELECT id_cliente, nombre FROM clientes WHERE correo = '$correo' AND password = '$password'";
    $res = $con->query($sql);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $_SESSION['idCliente'] = $row['id_cliente'];
        $_SESSION['nombreCliente'] = $row['nombre'];
        echo 1; // Éxito
    } else {
        echo 0; // Credenciales inválidas
    }
} else {
    echo 0;
}
?>