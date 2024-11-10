<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();

// Verificar si se pasó un ID de usuario
if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$idUser = $_GET['id'];

// Consultar los usuarios que siguen al usuario actual
$sqlFollowers = "SELECT u.id, u.username, u.email, u.description
                 FROM social_network.users u
                 INNER JOIN social_network.follows f ON u.id = f.users_id
                 WHERE f.userToFollowId = $idUser";
$queryFollowers = mysqli_query($connect, $sqlFollowers);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <b><a class="navbar-brand" href="../landing/landingPage.php">Twitta</a></b>
            <div class="ms-auto">
                <button id="theme-toggle" class="btn btn-outline-secondary mx-2">Modo Oscuro</button>
                <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Línea separadora -->
    <hr class="my-2">
    <div class="container">
        <h2>Seguidores</h2>
        <form action="../user/showProfile.php" method="POST">
            <input type="hidden" name="idOculta" value="<?= $idUser ?>">
            <button type="submit" class="btn btn-outline-secondary mb-3">Volver</button>
        </form>        <?php while ($row = mysqli_fetch_assoc($queryFollowers)): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['username']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($row['email']) ?></p>
                    <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                    <form action="showProfile.php" method="POST">
                        <input type="hidden" name="idOculta" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-primary">Ver Perfil</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
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
