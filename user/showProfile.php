<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['usuario']['username'];
$userId = $_SESSION['usuario']['id'];
$idOculta = '';

// Obtener el ID del usuario que se está visualizando
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
    if (isset($_SESSION['usuario'])) { // Asegúrate de que la sesión está activa
        if ($isFollowing) {
            $unfollowQuery = "DELETE FROM follows WHERE users_id = (SELECT id FROM users WHERE username = '$user') AND userToFollowId = '$idOculta'";
            mysqli_query($connect, $unfollowQuery);
        } else {
            $followQuery = "INSERT INTO follows (users_id, userToFollowId) VALUES ((SELECT id FROM users WHERE username = '$user'), '$idOculta')";
            mysqli_query($connect, $followQuery);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?idOculta=$idOculta");
        exit();
    } else {
        header("Location: ../index.php");
        exit();
    }
}

// Consultar el número de seguidores
$followersCountQuery = "SELECT COUNT(*) AS followers_count FROM follows WHERE userToFollowId = '$idOculta'";
$followersCountResult = mysqli_query($connect, $followersCountQuery);
$followersCount = mysqli_fetch_assoc($followersCountResult)['followers_count'];

// Consultar el número de seguidos
$followingCountQuery = "SELECT COUNT(*) AS following_count FROM follows WHERE users_id = '$idOculta'";
$followingCountResult = mysqli_query($connect, $followingCountQuery);
$followingCount = mysqli_fetch_assoc($followingCountResult)['following_count'];

// Manejar la edición de la descripción
if (isset($_POST['edit_description']) && $idOculta == $userId) {
    $newDescription = mysqli_real_escape_string($connect, $_POST['description']);
    $updateDescriptionQuery = "UPDATE users SET description = '$newDescription' WHERE id = '$userId'";
    mysqli_query($connect, $updateDescriptionQuery);
    // Redireccionar después de editar para evitar el reenvío del formulario
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <b><a class="navbar-brand" href="../landing/landingPage.php">Twitta</a></b>
            <div class="ms-auto">
                <button id="theme-toggle" class="btn btn-outline-secondary mx-2">Modo Oscuro</button>
                <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Línea separadora -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <li class="list-group-item"><b><?php echo "Username: " . $rowQuery['username']; ?></b><br></li>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php echo "Email: " . $rowQuery['email']; ?><br></li>
                        <li class="list-group-item">
                            <?php echo "Description: " . $rowQuery['description']; ?><br>
                        </li>
                    </ul>
                    <div class="card-body">
                        <?php if ($idOculta == $userId): ?>
                            <form method="POST" class="mb-3">
                                <input type="hidden" name="idOculta" value="<?php echo $idOculta; ?>">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Editar Descripción</label>
                                    <textarea class="form-control" name="description" rows="2"><?php echo $rowQuery['description']; ?></textarea>
                                </div>
                                <button type="submit" name="edit_description" class="btn btn-primary">Guardar</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" class="d-flex justify-content-between align-items-center">
                            <input type="hidden" name="idOculta" value="<?php echo $idOculta; ?>">
                            <button type="submit" name="follow" class="btn <?php echo $isFollowing ? 'btn-danger' : 'btn-success'; ?>">
                                <?php echo $isFollowing ? 'Dejar de Seguir' : 'Seguir'; ?>
                            </button>
                            <div class="ms-2">
                                <a href="followers.php?id=<?= $idOculta ?>" class="btn btn-outline-secondary">
                                    Seguidores <span class="badge bg-secondary"><?php echo $followersCount; ?></span>
                                </a><br>
                                <a href="following.php?id=<?= $idOculta ?>" class="btn btn-outline-secondary ms-2">
                                    Seguidos <span class="badge bg-secondary"><?php echo $followingCount; ?></span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Publicaciones del usuario -->
            <div class="col-md-8">
                <h4>Publicaciones de <?php echo $rowQuery['username']; ?></h4>
                <?php while ($row = mysqli_fetch_assoc($publicationsQuery)) { ?>
                    <div class="card w-100 mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['text']) ?></h5>
                            <p class="card-text"><small class="text-muted"><?= date('d-m-Y', strtotime($row['createDate'])) ?></small></p>
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
