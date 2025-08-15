<?php

session_start();
if (!isset($_SESSION['Admin_Token'])) {
    header("Location:  index.php");
    exit();
}
$adminID = $_SESSION['Admin_ID'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "LostFoundSystem";
$password = "LostFoundManagementSystem";
$database = "LostFoundDB";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="AddAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="curzr" hidden>
        <div class="curzr-dot"></div>
    </div>
    <div class="main">
        <div class="sidebar">
            <ul class="sidebarList">
                <li class="sidebarValues" id="Logo">
                    <a href="Dashboard.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-sliders"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="ClaimRequests.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-envelope"></i></span>
                        <span class="text">Claim Requests</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="FetchItems.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-box-open"></i></span>
                        <span class="text">Fetch Items</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="AddAdmin.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-user-plus"></i></span>
                        <span class="text">Add Admin</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="History.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-history"></i></span>
                        <span class="text">History</span>
                    </a>
                </li>
                <li class="sidebarValues" id="LogoutButton">
                    <a href="Logout.php" class="ValuesLink">
                        <span class="icon"><i class="fa fa-right-from-bracket"></i></span>
                        <span class="text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="wrapper">
            <span class="header">
                <h1>Admin Panel</h1>
            </span>
            <?php
                $sql10 = "Select Name from Admin where Admin_ID = '$adminID'";
                $query_run10 = $conn->query($sql10);
                if ($query_run10) {
                    $row =  $query_run10->fetch_assoc    (); 
                }
            ?>
            <div class="profileDiv">
                <div class="status"><button class="profileIcon"><img src="../Assets/Icons/AdminIcon.png" alt=""
                            style="height: 100%;"></button>
                            <?= $row['Name']; ?>
                </div>
            </div>
            <div class="box">
                <div class="boxHeader">
                    <h1>Add Admin</h1>
                </div>
                <form action="" method="post">
                    <input name="username" type="text" placeholder="Name" id="Input" required autocomplete="off"><br>
                    <input name="email" type="email" placeholder="Email" id="Input" required autocomplete="off"><br>
                    <input name="password" type="password" placeholder="Password" id="Input" required><br>
                    <a href="AddAdmin.html"> <button type="submit" id="Button">Add</button>
                    </a>
                </form>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    {
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $username = $_POST['username'];
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO Admin (Name, Email, Password) VALUES('$username', '$email','$hashedPassword')";
                        $conn->query($sql);

                    }
                    $conn->close();
                ?>
            </div>
        </div>
    </div>
    <script src="../Cursor.js"></script>
</body>

</html>