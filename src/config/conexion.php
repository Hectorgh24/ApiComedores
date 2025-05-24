<?php
$user = "rootapi";
$password = "password";
#$user = "root";
#$password = "P@ssw0rd";
$host = "localhost";
$database = "comedoresuv";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>