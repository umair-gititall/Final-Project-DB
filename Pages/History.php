<?php

session_start();
if (!isset($_SESSION['Admin_Token'])) {
    header("Location: AdminLogin.html");
    exit();
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
                    <a href="#" class="ValuesLink">
                        <span class="icon"><i class="fa fa-envelope"></i></span>
                        <span class="text">Claim Requests</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="FetchItem.php" class="ValuesLink">
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
                    Admin Name
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
                        <div id="Data">
                            <div class="dataValues">Hello</div>
                            <div class="dataValues">Hello</div>
                            <div class="dataValues">Hello</div>
                            <div class="dataValues">Hello</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../Cursor.js"></script>
</body>

</html>