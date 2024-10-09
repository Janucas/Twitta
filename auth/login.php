<?php
require_once "../connection/connection.php";
session_start();
$connection = connection();


if (isset($_POST)) {
   $username = $_POST['name'];
   $password = $_POST['password'];
}

$sql = "SELECT * FROM users WHERE username = '$username'";
$res = mysqli_query($connection, $sql);

if ($res && mysqli_num_rows($res) == 1) {
   $user = mysqli_fetch_assoc($res);

   if (password_verify($password, $user["password"])) {
      $_SESSION["usuario"] = $user;
      header("Location: ../landing/landingPage.php");
  } else {
      $_SESSION["error"] = "Credenciales erroneas";
      header("Location: ../index.php");
  }
} else {
  $_SESSION["error"] = "Credenciales erroneas";
  header("Location: ../index.php");
}

?>