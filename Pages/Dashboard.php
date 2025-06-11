<?php
    session_start();
    if (!isset($_SESSION['Admin_ID'])) {
        header("Location: AdminLogin.html");
        exit();
    }
    $adminID = $_SESSION['Admin_ID'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="Dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="cursor" id="cursor"></div>
    <div class="main">
        <div class="sidebar">
            <ul class="sidebarList">
                <li class="sidebarValues" id="Logo">
                    <a href="Dashboard.html" class="ValuesLink">
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
                    <a href="FetchItem.html" class="ValuesLink">
                        <span class="icon"><i class="fa fa-box-open"></i></span>
                        <span class="text">Fetch Items</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="AddAdmin.html" class="ValuesLink">
                        <span class="icon"><i class="fa fa-user-plus"></i></span>
                        <span class="text">Add Admin</span>
                    </a>
                </li>
                <li class="sidebarValues">
                    <a href="#" class="ValuesLink">
                        <span class="icon"><i class="fa fa-history"></i></span>
                        <span class="text">History</span>
                    </a>
                </li>
                <li class="sidebarValues" id="LogoutButton">
                    <a href="AdminLogin.html" class="ValuesLink">
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
                $servername = "lovely-pug-36.telebit.io";
                $username = "abdullah";
                $password = "abdullah";
                $database = "LostFoundDB";
                $port = 46741;
                
                $conn = new mysqli($servername, $username, $password, $database, $port);

                $sql10 = "Select Name from Admin where Admin_ID = '$adminID'";
                $query_run10 = $conn->query($sql10);
                if ($query_run10) {
                    $row = $query_run10->fetch_assoc(); 
                }
            ?>
            <div class="profileDiv">
                <div class="status"><button class="profileIcon"><img src="../Assets/Icons/AdminIcon.png" alt=""
                            style="height: 100%;"></button>
                    <?= $row['Name']; ?>
                </div>
            </div>
            <div class="box">
                <div class="analyticsWrapper">
                    <?php
                        $sql1 = "Select Count(ClaimID) as claimCount from Claim_Request";
                        $query_run1 = $conn->query($sql1);
                        if ($query_run1) {
                            $row = $query_run1->fetch_assoc(); 
                        }
                    ?>
                    <div id="AnalyticsSmallBox1">
                        <h2>Claim Requests: <?= $row['claimCount']; ?></h2>
                    <?php
                        $sql2 = "Select Count(ItemID) as itemCount from Item";
                        $query_run2 = $conn->query($sql2);
                        if ($query_run2) {
                            $row = $query_run2->fetch_assoc(); 
                        }
                    ?>
                        <h2>Items: <?= $row['itemCount']; ?></h2>
                    </div>
                    <div id="Heading">System Overview</div>
                    <div id="AnalyticsSmallBox2">
                        <?php
                            $sql3 = "Select Count(ClaimID) as claimCount from Claim_Request where Claim_Status = 'Approved'";
                            $query_run3 = $conn->query($sql3);
                            if ($query_run3) {
                                $row = $query_run3->fetch_assoc(); 
                            }
                        ?>
                        <h2>Approved Claim Requests: <?= $row['claimCount']; ?></h2>
                        <?php
                            $sql4 = "Select Count(ClaimID) as claimCount from Claim_Request where Claim_Status = 'Disapproved'";
                            $query_run4 = $conn->query($sql4);
                            if ($query_run4) {
                                $row = $query_run4->fetch_assoc(); 
                            }
                        ?>
                        <h2>Rejected Claim Requests: <?= $row['claimCount']; ?></h2>
                        <?php
                            $sql5 = "Select Count(Admin_ID) as adminCount from Admin";
                            $query_run5 = $conn->query($sql5);
                            if ($query_run5) {
                                $row = $query_run5->fetch_assoc(); 
                            }
                        ?>
                        <h2>Total Admins: <?= $row['adminCount']; ?></h2>
                        <?php
                            $sql6 = "select count(*) as nonClaimCount from Item where ItemID not in(select ItemID from Claim_Request where Claim_Status = 'Approved' or Claim_Status = 'Disapproved')";
                            $query_run6 = $conn->query($sql6);
                            if ($query_run6) {
                                $row = $query_run6->fetch_assoc(); 
                            }
                        ?>
                        <h2>Unclaimed Items: <?= $row['nonClaimCount']; ?></h2>
                    </div>
                </div>
                <div id="AnalyticsBigBox">
                    <div id="AdminNameHeader">
                        <?php
                            $sql7 = "Select Name from Admin where Admin_ID = '$adminID'";
                            $query_run7 = $conn->query($sql7);
                            if ($query_run7) {
                                $row = $query_run7->fetch_assoc(); 
                            }
                        ?>
                        <h1><?= $row['Name']; ?></h1>
                    </div>
                    <?php
                            $sql8 = "Select count(*) as adminClaimCount from Claim_Request where Admin_ID = '$adminID' and Claim_Status = 'Approved'";
                            $query_run8 = $conn->query($sql8);
                            if ($query_run8) {
                                $row = $query_run8->fetch_assoc(); 
                            }
                    ?>
                    <h2 class="textShadow">Approved Requests: <?= $row['adminClaimCount']; ?></h2>
                    <?php
                            $sql9 = "Select count(*) as adminRejectCount from Claim_Request where Admin_ID = '$adminID' and Claim_Status = 'Disapproved'";
                            $query_run9 = $conn->query($sql9);
                            if ($query_run9) {
                                $row = $query_run9->fetch_assoc(); 
                            }
                    ?>
                    <h2 class="textShadow">Rejected Requests: <?= $row['adminRejectCount']; ?></h2>
                    <?php
                            $sql11 = "Select count(*) as adminFetchCount from Item where Admin_ID = '$adminID' and Status = 'Fetched'";
                            $query_run11 = $conn->query($sql11);
                            if ($query_run11) {
                                $row = $query_run11->fetch_assoc(); 
                            }
                    ?>
                    <h2 class="textShadow">Fetched Items: <?= $row['adminFetchCount']; ?></h2>
                    <h2 class="textShadow"></h2>
                </div>
            </div>
        </div>
    </div>
    <script src="../Cursor.js"></script>
</body>

</html>