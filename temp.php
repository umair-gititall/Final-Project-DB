<?php
$host = "5.5.5.5";
$user = "abdullah";
$password = "abdullah"; // update if your MySQL has a password
$database = "LostFoundDB"; // replace with your DB name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Oh nooo 💔 Connection failed: " . $conn->connect_error);
}

$hashedPassword = password_hash('Overlord@123', PASSWORD_DEFAULT);
$sql = "INSERT INTO Admin VALUES (1, 'overlord@overlord.pp.ua', 'OverLord', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "Yayyy! 💖 New record inserted successfully!";
} else {
    echo "Eeep 😣 Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
