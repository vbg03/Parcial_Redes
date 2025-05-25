<?php
$id_usuario = $_POST["id_usuario"];
$contenido = $_POST["contenido"];

$data = [
    'id_usuario' => $id_usuario,
    'contenido' => $contenido
];

$ch = curl_init('http://localhost:3002/mensajes');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

header("Location: /frontFeed/index.php?usuarioId=$id_usuario"); // ✅ CORRECTO

