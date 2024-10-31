<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();
$idUser = $_SESSION['usuario']["id"];

// Determinar qué tipo de tweets mostrar
$showAll = isset($_GET['view']) && $_GET['view'] === 'all';

// Consulta SQL para mostrar los tweets
if ($showAll) {
    // Mostrar todos los tweets
    $sql = "SELECT *,
                (SELECT username
                 FROM social_network.users
                 WHERE users.id = publications.userId) AS username
            FROM social_network.publications";
} else {
    // Mostrar solo los tweets de las personas que el usuario sigue
    $sql = "SELECT *,
                (SELECT username
                 FROM social_network.users
                 WHERE users.id = publications.userId) AS username
            FROM social_network.publications
            WHERE userId IN (SELECT userToFollowId
                             FROM social_network.follows
                             WHERE users_id = $idUser)";
}

$query = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
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
            <b><a class="navbar-brand" href="#">Twitta</a></b>
            <div class="ms-auto">
                <button id="theme-toggle" class="btn btn-outline-secondary mx-2">Modo Oscuro</button>
                <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <!-- Línea separadora -->
    <hr class="my-2">
    <!-- Barra de enlaces -->
    <div class="bg-body-tertiary py-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="landingPage.php?view=all" class="btn btn-outline-secondary">Explorar todos</a>
                </div>
                <div class="col-auto">
                    <a href="landingPage.php?view=following" class="btn btn-outline-secondary">Siguiendo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla para organizar las tarjetas -->
    <table style="width: 100%;">
        <tr>
            <!-- Primera columna con la tarjeta de mostrar datos -->
            <td style="vertical-align: top;">
                <?php
                    if (isset($_SESSION["usuario"])) {
                        $username = $_SESSION["usuario"]["username"];
                        $email = $_SESSION["usuario"]["email"];
                        $description = $_SESSION["usuario"]["description"];
                ?>
                <!-- Tarjeta con los datos del usuario -->
                <div class="card" style="width: 18rem;">
                    <div class="card-header">
                        <li class="list-group-item"><b> <?php echo "Username: $username"; ?> </b><br></li>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> <?php echo "Email: $email"; ?> <br></li>
                        <li class="list-group-item"><?php echo "Description: $description"; ?> <br></li>
                    </ul>
                </div>
                <?php
                    } else {
                        header("Location: ../index.php");
                    }
                ?>
            </td>

            <!-- Segunda columna que ocupa el resto del espacio -->
            <td style="vertical-align: top; width: 100%;">

                <!-- Tarjeta para poner un tweet -->
                <div class="card" style="width: 100%;">
                    <div class="card-header">
                        <b>¿Qué estás pensando?</b>
                    </div>
                    <div class="card-body">
                    <form action="./tweet.php" method="POST">
                        <div class="mb-2">
                            <textarea class="form-control alert alert-light" id="tweet" name="tweet" rows="1"  placeholder="Escribe un nuevo tweet"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Twittear</button>
                        </div>
                    </form>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <h3>Tweets</h3>
    <!-- Listado de tweets -->
    <?php while ($row = mysqli_fetch_array($query)): ?>
        <div class="card w-100 mb-3">
            <div class="card-body">
                <form action="../user/showProfile.php" method="POST">
                    <input type="hidden" value="<?= $row['userId'] ?>" name="idOculta">
                    <h3><button type="submit" class="btn btn-link text-blue"><b><?= $row['username'] ?></b></button></h3>
                    <p class="card-text"><?= $row['text'] ?></p>
                    <small class="text-center"><?= $row['createDate'] ?></small>
                </form>
            </div>
        </div>
    <?php endwhile; ?>

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
