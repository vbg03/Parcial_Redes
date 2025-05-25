<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seguidores - Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>👤 Consultar seguidos de un usuario</h2>

    <form method="get" class="mb-4">
        <label>ID del usuario:</label>
        <input type="number" name="usuarioS" class="form-control mb-2" required value="<?= isset($_GET['usuarioS']) ? $_GET['usuarioS'] : '' ?>">
        <button class="btn btn-primary" type="submit">Consultar</button>
    </form>

    <?php
    if (isset($_GET['usuarioS'])) {
        $usuarioS = $_GET['usuarioS'];
        $url = "http://192.168.100.2:3003/seguidores/$usuarioS";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $seguidos = json_decode($response);

        if (empty($seguidos)) {
            echo "<p>Este usuario no sigue a nadie aún.</p>";
        } else {
            echo "<h5>Usuarios que sigue el usuario $usuarioS:</h5>";
            echo "<ul class='list-group'>";
            foreach ($seguidos as $seg) {
                echo "<li class='list-group-item'>Usuario seguido: {$seg->usuarioP}</li>";
            }
            echo "</ul>";
        }

        // Mostrar botón para seguir a otro
        echo '<button class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#crearModal">➕ Seguir a otro usuario</button>';
    }
    ?>

    <!-- Modal para seguir -->
    <div class="modal fade" id="crearModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="crearSeguimiento.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Seguir usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="usuarioS" value="<?= isset($usuarioS) ? $usuarioS : '' ?>">
                    <label>ID del usuario que deseas seguir:</label>
                    <input type="number" name="usuarioP" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Seguir</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
