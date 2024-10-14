<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();

if(!isset($_SESSION['usuario'])){
    header("Location: ../index.php");
}


$user = $_SESSION['usuario']['username'];

$idOculta = '';

if(isset($_POST['idOculta'])){
  $idOculta = $_POST['idOculta'];
}else{
  header("Location: ../index.php");
}


$sqlIdentityIDUser = "SELECT * FROM users WHERE id = '$idOculta'";
$query = mysqli_query($connect, $sqlIdentityIDUser);
$rowQuery = mysqli_fetch_assoc($query);

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
        <a href="#" class="btn btn-outline-secondary">Todos para ti</a>
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
                    $username = $rowQuery['username'];
                    $email = $rowQuery['email'];
                    $description = $rowQuery['description']; ?>
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
</body>
</html>