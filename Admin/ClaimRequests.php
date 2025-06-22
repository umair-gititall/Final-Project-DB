<?php

session_start();
if (!isset($_SESSION['Admin_Token'])) {
    header("Location:  index.html");
    exit();
}
$adminID = $_SESSION['Admin_ID'];

$servername = "5.5.5.5";
$username = "LostFoundSystem";
$password = "LostFoundManagementSystem";
$database = "LostFoundDB";
$port = 3306;
                
$conn = new mysqli($servername, $username, $password, $database, $port);


if (isset($_POST['approve'])){
    $claimID = $_POST['claimID'];

    $email = $_POST['Email'];
    $itemname = $_POST['ItemName'];

    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
    require_once '../../mailer.php';
    $htmlcontent = file_get_contents('../Email/ClaimApproved.html');
    $htmlcontent = str_replace('[Item Name Will Appear Here]', $itemname, $htmlcontent);
    $result = sendMail($email, 'Claim Request Approved', $htmlcontent);
    
    
    $sql4 = "UPDATE Claim_Request SET Claim_Status = 'Approved', Admin_ID = $adminID, Date_of_Claim = SYSDATE() WHERE ClaimID = $claimID AND ItemID NOT IN (SELECT ItemID FROM Claim_Request WHERE Claim_Status = 'Approved')";
    mysqli_query($conn, $sql4);
    
    $sql5 = "UPDATE Claim_Request SET Claim_Status = 'Disapproved', Admin_ID = $adminID, Date_of_Claim = SYSDATE() WHERE ItemID IN (SELECT ItemID FROM Claim_Request WHERE Claim_Status = 'Approved') AND Claim_Status = 'Not Approved'";
    mysqli_query($conn, $sql5);
    

    header("Refresh: 0");
    exit();
}
}

if (isset($_POST['reject'])){
    $claimID = $_POST['claimID'];
    $email = $_POST['Email'];
    $itemname = $_POST['ItemName'];

    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
    require_once '../../mailer.php';
    $htmlcontent = file_get_contents('../Email/ClaimRejected.html');
    $htmlcontent = str_replace('[Item Name Will Appear Here]', $itemname, $htmlcontent);
    sendMail($email, 'Claim Request Rejected', $htmlcontent);

    $sql6 = "UPDATE Claim_Request SET Claim_Status = 'Disapproved', Admin_ID = $adminID, Date_of_Claim = SYSDATE() WHERE ClaimID = $claimID";
    mysqli_query($conn, $sql6);
    
    header("Refresh: 0");
    exit();
}
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item</title>
    <link rel="Icon" href="../assets/Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="ClaimRequests.css" media="(min-width: 769px)">
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
                $sql0 = "Select Name from Admin where Admin_ID = '$adminID'";
                $query_run0 = $conn->query($sql0);
                if ($query_run0) {
                    $row = $query_run0->fetch_assoc(); 
                }
            ?>
            <div class="profileDiv">
                <div class="status"><button class="profileIcon"><img src="../Assets/Icons/AdminIcon.png" alt=""
                            style="height: 100%;"></button>
                    <?= $row['Name']; ?>
                </div>
            </div>
            <div class="container">
                <div class="box">
                    <div class="field" style="margin-left: -30px;">Item</div>
                    <div class="field" style="margin-left: 10px;">User Phone</div>
                    <div class="field" style="margin-left: 5px;">User Email</div>
                    <div class="field" style="margin-right: -20px;">Detail</div>
                </div>
                <div id="DataBox">
                    <div class="dataWrapper">
                        <div id="Loader">Loading...</div>

                        <?php
                        $sql1 = "SELECT * FROM Claim_Request WHERE Claim_Status = 'Not Approved'";
                        $query_run1 = mysqli_query($conn, $sql1);
                        
                        if (mysqli_num_rows($query_run1) > 0) {
                            foreach($query_run1 as $row)
                            {
                                $searchitem = $row['ItemID'];
                                $searchuser = $row['UserID'];

                                $sql2 = "SELECT ItemName FROM Item WHERE ItemID = '$searchitem'";
                                $query_run2 = mysqli_query($conn, $sql2)->fetch_assoc();
                                
                                $sql3 = "SELECT * FROM User WHERE UserID = '$searchuser'";
                                $query_run3 = mysqli_query($conn, $sql3)->fetch_assoc();
                                
                                ?>
                                 <div id="Data">
                                     <div class="dataValues">
                                        <?= $query_run2['ItemName'] ?>
                                    </div>
                                    <div class="dataValues">
                                        <?= $query_run3['PhoneNo'] ?>
                                    </div>
                                    <div class="dataValues">
                                        <?= $query_run3['Email'] ?>
                                    </div>
                                    <?php $popupId = uniqid('popup_'); ?>
                                        <button class="apply-btn" data-popup-id="<?= $popupId ?>">Action</button>
                                 </div>

                                 <div class="ApplyPopUp" id="<?= $popupId ?>" style="display: none;">
                                    <div class="close">
                                        <button class="Cross"><img src="../Assets/Icons/Cross.svg" alt="" id="BackIcon"></button>
                                    </div>
                                    <div class="verificationInfo">
                                        <div class="Photo" id="Photo" style="border: 2px solid black;">
                                        <?php
                                        $sql7 = "SELECT * FROM ClaimPhoto Where ClaimID = ".$row['ClaimID'];
                                        $query_run4 = mysqli_query($conn, $sql7);

                                        $i=0;
                                        foreach($query_run4 as $claimphoto)
                                        {
                                            if($i == 0){
                                        ?>
                                        <div class="mySlides active">
                                                <img src="<?=$claimphoto['Path']?>" alt="Slide <?=$i+1?>">
                                            </div>
                                        <?php
                                        }
                                        else{
                                        ?>
                                            <div class="mySlides">
                                            <img src="<?=$claimphoto['Path']?>" alt="Slide <?=$i+1?>">
                                            </div>
                                        <?php
                                        }
                                        $i++;
                                    }
                                        ?>
                                            <!-- Navigation buttons -->
                                            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                                            <a class="next" onclick="changeSlide(1)">&#10095;</a>

                                        </div>
                                        <?php
                                            $sql8 = "SELECT * FROM Item Where ItemID = ". $row['ItemID'];
                                            $query_run5 = mysqli_query($conn, $sql8)->fetch_assoc();

                                        ?>
                                        <?= $query_run5['Verification_Question'] ?>
                                        <textarea name="Answer" id="Answer"><?= $row['Answer'] ?></textarea>

                                        <form method="post" class="action-buttons">
                                            <input type="hidden" name="claimID" value="<?= $row['ClaimID'] ?>">
                                            <input type="hidden" name="Email" value="<?= $query_run3['Email'] ?>">
                                            <input type="hidden" name="ItemName" value="<?= $query_run5['ItemName'] ?>">
                                            <button type="submit" name="approve" class="approve-btn">&nbsp<i class="fa-solid fa-check"></i></button>
                                            <button type="submit" name="reject" class="reject-btn">&nbsp<i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                    </div>
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
    <script src="ClaimRequests.js"></script>
    <script src="../Cursor.js"></script>
</body>

</html>