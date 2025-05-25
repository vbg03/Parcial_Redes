<?php
session_start();

$id = $_POST['id'];
$token = $_POST['token'];

$ch = curl_init("http://localhost:3001/usuarios/$id");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Regresar al panel
header("Location: admin-panel.php");
exit;
