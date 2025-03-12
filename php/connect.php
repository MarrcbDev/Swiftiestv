<?php


$server = "localhost";
$user = "root";
$pass = "";
$database = "swiftiestv";

$conn = 
mysqli_connect($server, $user, $pass, $database);

if (!$conn){
    die("Error de conexion: ".mysqli_connect_error());
}


?>