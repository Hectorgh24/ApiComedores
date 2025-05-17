<?php
$host = "localhost";
$user = "rootapi";
$password = "password";
$database = "comedoresuv";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>