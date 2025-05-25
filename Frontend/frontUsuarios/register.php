<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>➕ Registrar nuevo usuario</h2>
    <form action="crearUsuario.php" method="post">
        <input class="form-control mb-2" name="nombre_completo" placeholder="Nombre completo" required>
        <input class="form-control mb-2" name="usuario" placeholder="Usuario" required>
        <input class="form-control mb-2" type="password" name="password" placeholder="Contraseña" required>
        <select class="form-select mb-3" name="rol" required>
            <option value="">Selecciona el rol</option>
            <option value="admin">Administrador</option>
            <option value="usuario">Usuario</option>
        </select>
        <button class="btn btn-success" type="submit">Crear usuario</button>
        <a class="btn btn-secondary" href="index.php">Volver</a>
    </form>
</body>
</html>
