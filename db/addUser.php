<?php

include("connection.php");
$con = connection();

$id=0;
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$description = $_POST['description'];
$createDate = $_POST['createDate'];


$sql = "INSERT INTO users VALUES('$id','$username','$email','$password','$description', CURDATE())";
$query = mysqli_query($con, $sql);

if($query){
    Header("Location: ../login.php");
}else{
    
}

?>