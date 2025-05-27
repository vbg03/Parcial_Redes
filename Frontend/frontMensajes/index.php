<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes - Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>📨 Ver mensajes por usuario</h2>

    <form method="get" class="mb-4">
        <label>ID del usuario:</label>
        <input type="number" name="id_usuario" required class="form-control mb-2" value="<?= isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '' ?>">
        <button class="btn btn-primary" type="submit">Consultar</button>
    </form>

    <?php
    if (isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];
        $url = "http://localhost:3002/mensajes/usuario/$id_usuario";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $mensajes = json_decode($response);

        if (empty($mensajes)) {
            echo "<p>No hay mensajes para este usuario.</p>";
        } else {
            echo "<table class='table table-bordered'>
                <thead>
                    <tr><th>ID</th><th>Contenido</th><th>Fecha</th></tr>
                </thead><tbody>";
            foreach ($mensajes as $mensaje) {
                echo "<tr>
                    <td>{$mensaje->id}</td>
                    <td>{$mensaje->contenido}</td>
                    <td>{$mensaje->fecha_creacion}</td>
                </tr>";
            }
            echo "</tbody></table>";
        }

        // Botón para crear mensaje
        echo '<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearModal">➕ Crear mensaje</button>';
    }
    ?>

    <!-- Modal para crear mensaje -->
    <div class="modal fade" id="crearModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="crearMensaje.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Crear mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" value="<?= isset($id_usuario) ? $id_usuario : '' ?>">
                    <textarea name="contenido" class="form-control" placeholder="Contenido del mensaje" required></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
