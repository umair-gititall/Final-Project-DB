<?php

session_start();
if (!isset($_SESSION['Admin_Token'])) {
    header("Location: AdminLogin.html");
    exit();
}
$adminID = $_SESSION['Admin_ID'];

$servername = "5.5.5.5";
$username = "LostFoundSystem";
$password = "LostFoundManagementSystem";
$database = "LostFoundDB";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

$sql10 = "Select Name from Admin where Admin_ID = '$adminID'";
$query_run10 = $conn->query($sql10);
if ($query_run10) {
    $row = $query_run10->fetch_assoc(); 
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item</title>
    <link rel="Icon" href="../assets/Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="History.css" media="(min-width: 769px)">
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
            <div class="profileDiv">
                <div class="status"><button class="profileIcon"><img src="../Assets/Icons/AdminIcon.png" alt=""
                            style="height: 100%;"></button>
                    <?= $row['Name']; ?>
                </div>
            </div>
            <div class="container">
                <div class="box">
                    <div class="field" style="margin-left: -30px;">Item</div>
                    <div class="field" style="margin-left: 10px;">Username</div>
                    <div class="field" style="margin-left: 5px;">Phone No.</div>
                    <div class="field" style="margin-right: -20px;">Date of Claim</div>
                </div>
                <div id="DataBox">
                    <div class="dataWrapper">
                        <div id="Loader">Loading...</div>
                         <?php
                        $sql1 = "SELECT * FROM Claim_Request WHERE Claim_Status = 'Approved'";
                        $query_run1 = mysqli_query($conn, $sql1);
                        
                        if (mysqli_num_rows($query_run1) > 0) {
                            foreach($query_run1 as $row)
                            {
                                $sql2 = "SELECT * FROM User WHERE UserID = ".$row['UserID'];
                                $query_run2 = mysqli_query($conn, $sql2)->fetch_assoc();

                                $sql3 = "SELECT * FROM Item WHERE ItemID = ".$row['ItemID'];
                                $query_run3 = mysqli_query($conn, $sql3)->fetch_assoc();
                        ?>
                        <div id="Data">
                            <div class="dataValues"><?=$query_run3['ItemName']?></div>
                            <div class="dataValues"><?=$query_run2['UserName']?></div>
                            <div class="dataValues"><?=$query_run2['PhoneNo']?></div>
                            <div class="dataValues"><?=$row['Date_of_Claim']?></div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../Cursor.js"></script>
</body>

</html>