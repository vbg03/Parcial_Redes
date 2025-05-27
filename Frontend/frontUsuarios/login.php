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

    <style>
        body {
            background-color: #fafafa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-box h2 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #262626;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            width: 100%;
            border-radius: 10px;
        }

        .alert {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 Iniciar Sesión</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <input class="form-control mb-3" name="usuario" placeholder="Usuario" required>
            <input class="form-control mb-3" type="password" name="password" placeholder="Contraseña" required>
            <button class="btn btn-primary" type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>

