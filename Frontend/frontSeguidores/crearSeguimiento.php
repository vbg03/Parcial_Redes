<?php
$usuarioP = $_POST['usuarioP'];
$usuarioS = $_POST['usuarioS'];

$data = [
    'usuarioP' => $usuarioP,
    'usuarioS' => $usuarioS
];

$ch = curl_init("http://localhost:3003/seguidores");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

header("Location: /frontFeed/index.php?usuarioId=$usuarioS"); // ✅ CORRECTO
