<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();
$idUser = $_SESSION['usuario']["id"];

//devuelve los datos de publicacion junto con el username
$sql = "SELECT *,
            (SELECT username
            FROM social_network.users 
            WHERE users.id = publications.userId) AS username
        FROM social_network.publications";
$query = mysqli_query($connect, $sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <b><a class="navbar-brand" href="#">Twitta</a></b>
    <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
  </div>
</nav>
<!-- Línea separadora -->
<hr class="my-2">
<!-- Barra de enlaces -->
<div class="bg-body-tertiary py-2">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-auto">
        <a href="#" class="btn btn-outline-secondary">Explorar todos</a>
      </div>
      <div class="col-auto">
        <a href="#" class="btn btn-outline-secondary">Siguiendo</a>
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
                    <b>Que estas pensando</b>
                </div>
                <div class="card-body">
                <form action="./tweet.php" method="POST">
                    <div class="mb-2">
                        <textarea class="form-control alert alert-light" id="tweet" name="tweet" rows="1"  placeholder="Escriba un nuevo tweet"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Twittar</button>
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
      <form action="../user/showProfile.php" method=POST>
      <input type="hidden" value="<?= $row['userId'] ?>" name="idOculta">
        <h3><button type="submit" class="btn btn-link text-blue"><b><?= $row['username'] ?></b></button></h3>
        <p class="card-text"><?= $row['text'] ?></p>
        <small class="text-center"><?= $row['createDate'] ?></small>
      
      </form>
    </div>
  </div>
<?php endwhile; ?> 
</body>
</html>
