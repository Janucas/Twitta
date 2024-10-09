<?php

require_once "../connection/connection.php";
session_start();
$connection = connection();

$id=0;
$username = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$description = $_POST['description'];

$passSegura = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO users VALUES('$id','$username','$email','$passSegura','$description', CURDATE())";
$query = mysqli_query($connection, $sql);

if($query){
    Header("Location: ../index.php");
}else{
    Header("Location: ../auth/signup.php");

    
}

?>