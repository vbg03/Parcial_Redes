<?php
$idMensaje = $_POST['id_mensaje'];
$idUsuario = $_POST['id_usuario'];

$ch = curl_init("http://192.168.100.2:3002/mensajes/$idMensaje");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

// Redirige de nuevo al feed
header("Location: /frontFeed/index.php?usuarioId=$idUsuario");
exit;
