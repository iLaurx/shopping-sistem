<?php
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : '';
$mensaje_txt = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

if ($nombre != '' && $correo != '' && $asunto != '' && $mensaje_txt != '') {
    
    $destinatario = "retroGamesxampp@gmail.com"; 
    
    // Cabeceras para que soporte HTML y acentos
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: $nombre <$correo>\r\n";
    $headers .= "Reply-To: $correo\r\n";
    
    $cuerpo = "
    <html>
    <head><title>Nuevo Mensaje de Contacto</title></head>
    <body style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='border-bottom: 2px solid #000; padding-bottom: 10px;'>Has recibido un nuevo mensaje</h2>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Correo:</strong> $correo</p>
        <p><strong>Asunto:</strong> $asunto</p>
        <p><strong>Mensaje:</strong><br>" . nl2br($mensaje_txt) . "</p>
    </body>
    </html>
    ";

    // Ejecuta la función mail()
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        echo 1; 
    } else {
        echo 0; 
    }
} else {
    echo 0;
}
?>