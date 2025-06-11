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

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$item = $_POST['item'];
$date = $_POST['date'];
$location = $_POST['location'];
$description = $_POST['description'];

// Check if reporter exists 💻💕
$sql_check = "SELECT ReporterID FROM Reporter WHERE Email = '$email'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows == 0) {
    $sql = "INSERT INTO Reporter (Email, PhoneNo, ReporterName) VALUES('$email','$phone','$name')";
    $conn->query($sql);
}

$sql2 = "SELECT ReporterID FROM Reporter WHERE Email = '$email'";
$query_run = $conn->query($sql2);

if ($query_run) {
    $row = $query_run->fetch_assoc(); 
    
    $reporterid = $row['ReporterID'];
    $sql3 = "INSERT INTO Item (ItemName, Description, Found_Date, Found_Location, ReporterID) VALUES ('$item', '$description', '$date', '$location', '$reporterid')";
    $conn->query($sql3);

    $targetDir = "../Assets/Reported Images/" . $reporterid . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imageCount = count(glob($targetDir . "*.*"));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (isset($_FILES['images']) && isset($_FILES['images']['tmp_name']) && is_array($_FILES['images']['tmp_name'])) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $originalName = $_FILES['images']['name'][$index];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $imageCount++;
                $newFileName = $imageCount . "." . $extension;
                $targetFilePath = $targetDir . $newFileName;
                move_uploaded_file($tmpName, $targetFilePath);
            }
        }
    } else {
        echo "No images uploaded~ 😳📸";
    }
}

$conn->close();

?>