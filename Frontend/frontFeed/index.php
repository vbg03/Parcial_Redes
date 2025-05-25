<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📰 Mi Feed - Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fafafa;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 40px;
        }

        .feed-container {
            width: 100%;
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2, h4, h5 {
            color: #262626;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 12px;
            border: 1px solid #dbdbdb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card-header, .card-body {
            background-color: #fff;
        }

        .username {
            font-weight: bold;
            color: #262626;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }

        textarea.form-control {
            resize: none;
            border-radius: 10px;
            border: 1px solid #dbdbdb;
        }

        .text-muted {
            font-size: 14px;
        }

        .btn-outline-danger, .btn-primary {
            font-size: 14px;
        }

        .logout {
            float: right;
        }

    </style>
</head>
<body>
<div class="feed-container">

    <h2>📰 Mi Feed</h2>

    <?php
    session_start();
    $usuarioId = $_SESSION['id_usuario'] ?? $_GET['usuarioId'] ?? null;

    if (!$usuarioId) {
        header("Location: ../login.php");
        exit;
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></h4>
        <a href="/frontUsuarios/logout.php" class="btn btn-outline-danger logout">Cerrar sesión</a>
    </div>

    <!-- Formulario para crear un mensaje -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="/frontMensajes/crearMensaje.php" method="post">
                <input type="hidden" name="id_usuario" value="<?= $usuarioId ?>">
                <textarea name="contenido" class="form-control mb-2" placeholder="¿En qué estás pensando?" required></textarea>
                <button class="btn btn-success" type="submit">Publicar</button>
            </form>
        </div>
    </div>

    <!-- Mensajes propios -->
    <h4>📝 Tus mensajes</h4>
    <?php
    $misMensajesURL = "http://192.168.100.2:3002/mensajes/usuario/$usuarioId";
    $chPropios = curl_init($misMensajesURL);
    curl_setopt($chPropios, CURLOPT_RETURNTRANSFER, true);
    $respPropios = curl_exec($chPropios);
    curl_close($chPropios);
    $mensajesPropios = json_decode($respPropios);

    if (!empty($mensajesPropios)) {
        foreach ($mensajesPropios as $m) {
            echo "<div class='card'>
                    <div class='card-header d-flex justify-content-between align-items-center'>
                        <span><span class='username'>Tú</span> &bull; <small>{$m->fecha_creacion}</small></span>
                        <form method='post' action='/frontMensajes/eliminarMensaje.php' class='d-inline'>
                            <input type='hidden' name='id_mensaje' value='{$m->id}'>
                            <input type='hidden' name='id_usuario' value='$usuarioId'>
                            <button class='btn btn-sm btn-danger'>🗑</button>
                        </form>
                    </div>
                    <div class='card-body'>
                        <p class='card-text'>{$m->contenido}</p>
                    </div>
                </div>";
        }
    } else {
        echo "<p class='text-muted'>No has publicado ningún mensaje todavía.</p>";
    }
    ?>

    <!-- Lista de usuarios -->
    <h4 class="mt-5">👥 Todos los usuarios</h4>
    <table class="table table-bordered">
        <thead class="table-light">
        <tr>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $ch = curl_init("http://192.168.100.2:3001/usuarios");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);
        $usuariosLista = json_decode($resp);

        $ch = curl_init("http://192.168.100.2:3003/seguidores/$usuarioId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respSeg = curl_exec($ch);
        curl_close($ch);
        $yaSigo = array_column(json_decode($respSeg), 'usuarioP');

        foreach ($usuariosLista as $u) {
            if ($u->id == $usuarioId) continue;

            $yaSeguido = in_array($u->id, $yaSigo);
            echo "<tr>
                <td>{$u->nombre_completo}</td>
                <td>@{$u->usuario}</td>
                <td>";
            if ($yaSeguido) {
                echo "<form method='post' action='/frontSeguidores/eliminarSeguimiento.php' class='d-inline'>
                    <input type='hidden' name='usuarioS' value='$usuarioId'>
                    <input type='hidden' name='usuarioP' value='{$u->id}'>
                    <button class='btn btn-sm btn-outline-danger'>Dejar de seguir</button>
                </form>";
            } else {
                echo "<form method='post' action='/frontSeguidores/crearSeguimiento.php' class='d-inline'>
                    <input type='hidden' name='usuarioS' value='$usuarioId'>
                    <input type='hidden' name='usuarioP' value='{$u->id}'>
                    <button class='btn btn-sm btn-primary'>Seguir</button>
                </form>";
            }
            echo "</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Mensajes de seguidos -->
    <?php
    $seguidoresURL = "http://192.168.100.2:3003/seguidores/$usuarioId";
    $curl1 = curl_init($seguidoresURL);
    curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
    $response1 = curl_exec($curl1);
    curl_close($curl1);
    $seguidos = json_decode($response1);

    if (!empty($seguidos)) {
        echo "<h5 class='mt-5'>🧍‍♂️ Usuarios que sigues: " . count($seguidos) . "</h5><hr>";

        foreach ($seguidos as $s) {
            $usuarioP = $s->usuarioP;

            $userURL = "http://192.168.100.2:3001/usuarios/$usuarioP";
            $chU = curl_init($userURL);
            curl_setopt($chU, CURLOPT_RETURNTRANSFER, true);
            $userResp = curl_exec($chU);
            curl_close($chU);
            $userInfo = json_decode($userResp);
            $nombreCompleto = $userInfo->nombre_completo ?? "Usuario $usuarioP";

            $msgURL = "http://192.168.100.2:3002/mensajes/usuario/$usuarioP";
            $chM = curl_init($msgURL);
            curl_setopt($chM, CURLOPT_RETURNTRANSFER, true);
            $msgResp = curl_exec($chM);
            curl_close($chM);
            $mensajes = json_decode($msgResp);

            if (!empty($mensajes)) {
                foreach ($mensajes as $m) {
                    echo "<div class='card'>
                            <div class='card-header'>
                                <span class='username'>$nombreCompleto</span> &bull; <small>{$m->fecha_creacion}</small>
                            </div>
                            <div class='card-body'>
                                <p class='card-text'>{$m->contenido}</p>
                            </div>
                        </div>";
                }
            }
        }
    } else {
        echo "<p class='text-muted'>No sigues a nadie aún.</p>";
    }
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
