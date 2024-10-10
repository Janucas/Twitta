<?php
require_once "../connection/connection.php";
session_start();

$connect = connection();

if(!isset($_SESSION['usuario'])){
    header("Location: ../index.php");
}

if(isset($_POST)){
    $tweet = $_POST['tweet'];
    $idUser = $_SESSION['usuario']["id"];
}

$idPublicacion = 0;

$sql = "INSERT INTO publications  VALUES ('$idPublicacion', '$idUser', '$tweet', CURDATE())";
$registro = mysqli_query($connect, $sql);
$_SESSION['add'] = 'Tweet añadido con exito ';
header("Location:../landing/landingPage.php");


?>