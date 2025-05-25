<?php
session_start();

// ✅ Redirigir si no es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin' || !isset($_SESSION['token'])) {
    header("Location: login.php");
    exit;
}

// Obtener lista de usuarios desde el microservicio
$url = "http://localhost:3001/usuarios";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$usuarios = json_decode($response);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fafafa;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
            min-height: 100vh;
        }

        .admin-container {
            background-color: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 900px;
        }

        h2, h4 {
            font-weight: bold;
            color: #262626;
        }

        .table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-content {
            border-radius: 12px;
        }

        .btn {
            border-radius: 10px;
        }

        .btn-danger, .btn-outline-danger {
            font-weight: 500;
        }

        .btn-primary {
            font-weight: 500;
        }

        .text-muted {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <h2>🛠 Panel de Administración</h2>
    <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>

    <h4 class="mt-4">👥 Lista de usuarios registrados</h4>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u->id) ?></td>
                    <td><?= htmlspecialchars($u->nombre_completo) ?></td>
                    <td><?= htmlspecialchars($u->usuario) ?></td>
                    <td><?= htmlspecialchars($u->rol) ?></td>
                    <td>
                        <?php if ($u->usuario !== $_SESSION['usuario']): ?>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $u->id ?>">
                                🗑 Eliminar
                            </button>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="modalEliminar<?= $u->id ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="eliminarUsuario.php" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro que deseas eliminar al usuario <strong>@<?= htmlspecialchars($u->usuario) ?></strong>?</p>
                                            <input type="hidden" name="id" value="<?= $u->id ?>">
                                            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">No puedes eliminarte</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón para abrir el modal de crear usuario -->
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearModal">➕ Crear nuevo usuario</button>

    <!-- Modal de creación -->
    <div class="modal fade" id="crearModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="crearUsuario.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar nuevo usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control mb-2" name="nombre_completo" placeholder="Nombre completo" required>
                    <input class="form-control mb-2" name="usuario" placeholder="Usuario" required>
                    <input class="form-control mb-2" name="password" type="password" placeholder="Contraseña" required>
                    <select class="form-select" name="rol" required>
                        <option value="">Seleccionar rol</option>
                        <option value="admin">Administrador</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" type="submit">Crear</button>
                </div>
            </form>
        </div>
    </div>

    <a class="btn btn-outline-danger mt-4" href="logout.php">Cerrar sesión</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
