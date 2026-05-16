<?php
// cerrar_sesion.php
session_start();
session_destroy();
header("Location: index.php");
exit;
?>