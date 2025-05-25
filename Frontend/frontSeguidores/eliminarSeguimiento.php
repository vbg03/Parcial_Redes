<?php
$usuarioS = $_POST['usuarioS'];
$usuarioP = $_POST['usuarioP'];

$url = "http://192.168.100.2:3003/seguidores/eliminar";
$data = json_encode([
    'usuarioS' => $usuarioS,
    'usuarioP' => $usuarioP
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

header("Location: /frontFeed/index.php?usuarioId=$usuarioS");
exit;
