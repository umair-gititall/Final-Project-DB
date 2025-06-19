<?php

session_start();
if (!isset($_SESSION['Admin_Token'])) {
    header("Location:  index.html");
    exit();
}
$adminID = $_SESSION['Admin_ID'];
?>
<?php
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

<?php
    if(isset($_POST['Question']) && isset($_POST['ItemID']) && isset($_POST['ReporterName']) && isset($_POST['ReporterEmail']))
    {
        $itemID = $_POST['ItemID'];
        $question = $_POST['Question'];
        $sql5 = "UPDATE Item SET Verification_Question = '$question', Status = 'Fetched' WHERE ItemID = '$itemID'";
        mysqli_query($conn, $sql5);

        require_once '../../mailer.php';
        $name = $_POST['ReporterName'];
        $reporterEmail = $_POST['ReporterEmail'];
        $htmlcontent = file_get_contents('../Email/ItemFetched.html');
        htmlcontent = str_replace('Thank you ', 'Thank you '.$name.' ', $htmlcontent);
        $result = sendMail($reporterEmail, 'Item Fetched Successfully', $htmlcontent);

    }
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item</title>
    <link rel="Icon" href="../assets/Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="FetchItems.css"  >
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
                    <div class="field" style="margin-left: 10px;">Reporter Phone</div>
                    <div class="field" style="margin-left: 5px;">Reporter Email</div>
                    <div class="field" style="margin-right: -20px;">Fetch</div>
                </div>
                <div id="DataBox">
                    <div class="dataWrapper">
                        <div id="Loader">Loading...</div>
                        <?php
                            $sql1 = "SELECT * FROM Item AS i INNER JOIN Reporter AS r on r.ReporterID = i.ReporterID WHERE Status = 'Fetching'";
                            $query_run1 = mysqli_query($conn, $sql1);
                        
                            if (mysqli_num_rows($query_run1) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run1))
                            {
                                $itemid = $row['ItemID'];
                        ?>
                        <div class="item-block">
                            <div class="data-item" data-itemid="<?= $itemid ?>">
                                <div class="dataValues">
                                    <?= $row['ItemName'] ?>
                                </div>
                                <div class="dataValues">
                                    <?= $row['PhoneNo'] ?>
                                </div>
                                <div class="dataValues">
                                    <?= $row['Email'] ?>
                                </div>
                                <button type = "button" class="fetch-button" data-popupid="<?= $itemid ?>">Fetch</button>
                            </div>

                            <div class="apply-popup" data-popupid="<?= $itemid ?>" style="display: none;">
                                <div class="close">
                                    <button class="cross"><img src="../Assets/Icons/Cross.svg" alt=""
                                            id="BackIcon"></button>
                                </div>
                                <form class="verificationInfo" action="" method="post">
                                    <div class="Photo" id="Photo" style="border: 2px solid black;">
                                        <?php
                                $sql2 = "SELECT * FROM ItemPhoto Where ItemID = ".$row['ItemID'];
                                $query_run2 = mysqli_query($conn, $sql2);

                                $i=0;
                                foreach($query_run2 as $itemphoto)
                                {
                                    if($i == 0){
                                ?>
                                        <div class="mySlides active">
                                            <img src="<?=$itemphoto['Path']?>" alt="Slide <?=$i+1?>">
                                        </div>
                                        <?php
                                }
                                else{
                                ?>
                                        <div class="mySlides">
                                            <img src="<?=$itemphoto['Path']?>" alt="Slide <?=$i+1?>">
                                        </div>
                                        <?php
                                }
                                $i++;
                            }
                                ?>
                                        <!-- Navigation buttons -->
                                        <a class="prev">&#10094;</a>
                                        <a class="next">&#10095;</a>


                                    </div>
                                    <input type="hidden" name="ItemID" value="<?= $row['ItemID'] ?>">
                                    <input type="hidden" name="ReporterName" value="<?= $row['ReporterName'] ?>">
                                    <input type="hidden" name="ReporterEmail" value="<?= $row['Email'] ?>">
                                    <textarea name="Question" class="Answer"
                                        placeholder="Q. Enter Your Question Here?"></textarea>
                                    <button id="SubmitButton" style="width: 30%; margin: 25px;">Fetch</button>
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
    <script src="FetchItems.js"></script>
    <script src="../Cursor.js"></script>
</body>

</html>