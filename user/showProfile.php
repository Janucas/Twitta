<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['usuario']['username'];
$idOculta = '';

if (isset($_POST['idOculta'])) {
    $idOculta = $_POST['idOculta'];
} else {
    header("Location: ../index.php");
    exit();
}

// Consultar datos del usuario
$sqlIdentityIDUser = "SELECT * FROM users WHERE id = '$idOculta'";
$query = mysqli_query($connect, $sqlIdentityIDUser);
$rowQuery = mysqli_fetch_assoc($query);

// Consultar todas las publicaciones del usuario
$sqlPublications = "SELECT * FROM publications WHERE userId = '$idOculta' ORDER BY createDate DESC";
$publicationsQuery = mysqli_query($connect, $sqlPublications);

// Verificar si el usuario actual sigue el perfil visitado
$followQuery = "SELECT * FROM follows WHERE users_id = (SELECT id FROM users WHERE username = '$user') AND userToFollowId = '$idOculta'";
$isFollowing = mysqli_num_rows(mysqli_query($connect, $followQuery)) > 0;

// Manejar la acción de seguir/dejar de seguir
if (isset($_POST['follow'])) {
    if ($isFollowing) {
        $unfollowQuery = "DELETE FROM follows WHERE users_id = (SELECT id FROM users WHERE username = '$user') AND userToFollowId = '$idOculta'";
        mysqli_query($connect, $unfollowQuery);
    } else {
        $followQuery = "INSERT INTO follows (users_id, userToFollowId) VALUES ((SELECT id FROM users WHERE username = '$user'), '$idOculta')";
        mysqli_query($connect, $followQuery);
    }
    // Recargar la página para reflejar el cambio
    header("Location: " . $_SERVER['PHP_SELF'] . "?idOculta=$idOculta");
    exit();
}

// Manejar la acción de editar descripción
if (isset($_POST['editDescription'])) {
    $newDescription = $_POST['description'];
    $updateDescriptionQuery = "UPDATE users SET description = '$newDescription' WHERE id = '$idOculta'";
    mysqli_query($connect, $updateDescriptionQuery);
    // Recargar la página para reflejar el cambio
    header("Location: " . $_SERVER['PHP_SELF'] . "?idOculta=$idOculta");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Estilos para el tema oscuro */
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode .card {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .dark-mode .btn-outline-secondary,
        .dark-mode .btn-outline-secondary:hover {
            background-color: #ffffff;
            color: #121212;
            border-color: #ffffff;
        }
        .dark-mode #theme-toggle,
        .dark-mode #theme-toggle:hover {
            background-color: #ffffff;
            color: #121212;
            border-color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <b><a class="navbar-brand" href="#">Twitta</a></b>
            <div class="ms-auto">
                <button id="theme-toggle" class="btn btn-outline-secondary mx-2">Modo Oscuro</button>
                <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Línea separadora -->
    <hr class="my-2">

    <div class="container">
        <div class="row">
            <!-- Información del usuario -->
            <div class="col-md-4">
                <?php
                if (isset($_SESSION["usuario"])) {
                    $username = $rowQuery['username'];
                    $email = $rowQuery['email'];
                    $description = $rowQuery['description']; ?>
                    <!-- Tarjeta con los datos del usuario -->
                    <div class="card">
                        <div class="card-header">
                            <li class="list-group-item"><b><?php echo "Username: $username"; ?></b><br></li>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><?php echo "Email: $email"; ?><br></li>
                            <li class="list-group-item">
                                <?php if ($idOculta == $_SESSION['usuario']['id']) { ?>
                                    <form method="POST" class="mb-2">
                                        <textarea name="description" class="form-control" rows="2"><?php echo $description; ?></textarea>
                                        <input type="hidden" name="idOculta" value="<?php echo $idOculta; ?>">
                                        <button type="submit" name="editDescription" class="btn btn-primary mt-2">Editar Descripción</button>
                                    </form>
                                <?php } ?>
                                <p><?php echo "Description: $description"; ?><br></p>
                            </li>
                        </ul>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="idOculta" value="<?php echo $idOculta; ?>">
                                <button type="submit" name="follow" class="btn <?php echo $isFollowing ? 'btn-danger' : 'btn-success'; ?>">
                                    <?php echo $isFollowing ? 'Dejar de Seguir' : 'Seguir'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                } else {
                    header("Location: ../index.php");
                }
                ?>
            </div>
            <!-- Publicaciones del usuario -->
            <div class="col-md-8">
                <h4>Publicaciones de <?php echo $username; ?></h4>
                <?php while ($row = mysqli_fetch_assoc($publicationsQuery)) { ?>
                    <div class="card w-100 mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['text']) ?></h5>
                            <p class="card-text"><small class="text-muted"><?= date('d-m-Y H:i', strtotime($row['createDate'])) ?></small></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Script para el cambio de tema -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggleButton = document.getElementById("theme-toggle");
            const darkMode = localStorage.getItem("theme") === "dark";

            // Aplicar el tema inicial
            document.body.classList.toggle("dark-mode", darkMode);
            toggleButton.textContent = darkMode ? "Modo Claro" : "Modo Oscuro";

            // Alternar tema y guardar preferencia
            toggleButton.onclick = () => {
                document.body.classList.toggle("dark-mode");
                const isDark = document.body.classList.contains("dark-mode");
                toggleButton.textContent = isDark ? "Modo Claro" : "Modo Oscuro";
                localStorage.setItem("theme", isDark ? "dark" : "light");
            };
        });
    </script>
</body>
</html>
