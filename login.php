<?php
// login.php
session_start();
if (isset($_SESSION['idCliente'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Pedidos</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <script src="js/jquery-4.0.0.min.js"></script>
    <script src="js/funciones_login.js"></script>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="login-contenedor">
        <div class="login-bloque">
            <h2>INICIAR SESIÓN</h2>
            <form id="formLogin">
                <div class="login-campo">
                    <label for="correo">CORREO ELECTRÓNICO</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="login-campo">
                    <label for="password">CONTRASEÑA</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="login-controles">
                    <button type="submit">ENTRAR</button>
                </div>
            </form>
            <div id="mensaje-login"></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>