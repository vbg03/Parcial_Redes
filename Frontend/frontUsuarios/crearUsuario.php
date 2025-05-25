<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

$data = array(
    'nombre_completo' => $_POST["nombre_completo"],
    'usuario' => $_POST["usuario"],
    'password' => $_POST["password"],
    'rol' => $_POST["rol"]
);

$url = 'http://192.168.100.2:3001/usuarios';
$token = $_SESSION['token'];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    "Authorization: Bearer $token"
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Redirige con feedback básico
if ($status == 200) {
    header("Location: admin-panel.php");
} else {
    echo "❌ Error al crear el usuario. Verifica si tu token expiró.";
}
?>
