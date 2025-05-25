<?php
session_start();
ob_start(); // Inicia el "output buffering"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'usuario' => $_POST['usuario'],
        'password' => $_POST['password']
    ];

    $ch = curl_init('http://localhost:3001/auth/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status == 200) {
    $json = json_decode($response);
    $usuario = $json->usuario;
    $token = $json->token;

    $_SESSION['usuario'] = $usuario->usuario;
    $_SESSION['id_usuario'] = $usuario->id;
    $_SESSION['rol'] = $usuario->rol;
    $_SESSION['token'] = $token; // ⭐ GUARDAR TOKEN


        // 👉 Redirigir según el rol
        if ($usuario->rol === 'admin') {
            
            header("Location: /frontUsuarios/admin-panel.php");
        } else {
            header("Location: /frontFeed/index.php?usuarioId=" . $usuario->id);
        }
        exit;
    } else {
        $error = "❌ Usuario o contraseña incorrectos";
    }
}
ob_end_flush(); // Envía el buffer al navegador
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>🔐 Iniciar Sesión</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input class="form-control mb-2" name="usuario" placeholder="Usuario" required>
        <input class="form-control mb-2" type="password" name="password" placeholder="password" required>
        <button class="btn btn-primary" type="submit">Entrar</button>
    </form>
</body>
</html>
