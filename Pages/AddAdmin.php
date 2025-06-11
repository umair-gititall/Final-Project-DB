<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "5.5.5.5";
$username = "abdullah";
$password = "abdullah";
$database = "LostFoundDB";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Admin (Name, Email, Password) VALUES('$username', '$email','$password')";
$conn->query($sql);


$conn->close();
?>