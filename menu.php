<?php
// menu.php
$logueado = isset($_SESSION['idCliente']);
$nombreCliente = $logueado ? $_SESSION['nombreCliente'] : '';
?>
<header class="header-principal">
    <div class="nav-left">
        <a href="index.php">HOME</a>
        <a href="productos.php">PRODUCTOS</a>
        <a href="contacto.php">CONTACTO</a>
    </div>

    <div class="nav-center">
        <a href="index.php"><img src="img/logo.png" alt="Logo" class="logo-header"></a>
    </div>

    <div class="nav-right">
        <?php if (!$logueado): ?>
            <a href="login.php">LOGIN</a>
        <?php else: ?>
            <span class="bienvenida">BIENVENIDO <?php echo strtoupper($nombreCliente); ?></span>
            <a href="cerrar_sesion.php">SALIR</a>
            <a href="pedidos_lista.php">MIS PEDIDOS</a>
            <a href="carrito01.php" class="link-carrito">VER CARRITO 🛒</a>
        <?php endif; ?>
    </div>
</header>