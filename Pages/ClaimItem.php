<?php
    $servername = "5.5.5.5";
    $username = "LostFoundSystem";
    $password = "LostFoundManagementSystem";
    $database = "LostFoundDB";
    $port = 3306;
                
    $conn = new mysqli($servername, $username, $password, $database, $port);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item</title>
    <link rel="Icon" href="../assets/Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="ClaimItem.css" media="(min-width: 769px)">
</head>

<body>
    <div class="curzr" hidden>
        <div class="curzr-dot"></div>
    </div>
    <div class="main">
        <a href="../index.html"><button class="back"><img src="../Assets/Icons/Back.svg" alt=""
                    id="BackIcon"></button></a>
        <div class="header">Lost And Found Management System </div>
        <div class="container">
            <div class="box">
                <div class="field">Item</div>
                <div class="field">Location</div>
                <div class="field">Date</div>
                <div class="field">Apply</div>
            </div>
            <div id="DataBox">
                <div class="dataWrapper">
                    <div id="Loader">Loading...</div>
                    <?php
                        $sql1 = "SELECT * FROM Item WHERE ItemID Not IN(SELECT ItemID FROM Claim_Request WHERE Claim_Status = 'Approved') AND Status = 'Fetched'";
                        $query_run1 = mysqli_query($conn, $sql1);
                        
                        if (mysqli_num_rows($query_run1) > 0) {
                            foreach($query_run1 as $row)
                            {
                                ?>
                    <div id="Data">
                        <div class="dataValues">
                            <?= $row['ItemName']; ?>
                        </div>
                        <div class="dataValues">
                            <?= $row['Found_Location']; ?>
                        </div>
                        <div class="dataValues">
                            <?= $row['Found_Date']; ?>
                        </div>
                        <?php $popupId = uniqid('popup_'); ?>
                        <button class="apply-btn" data-popup-id="<?= $popupId ?>">Apply</button>
                    </div>
                    <?php $formId = uniqid('AnswerForm_'); ?>
                    <form id="<?= $formId ?>" method="post" action="" enctype="multipart/form-data">
                        <div class="ApplyPopUp" id="<?= $popupId ?>" style="display: none;">
                            <div class="close">
                                <button type="button" class="Cross"><img src="../Assets/Icons/Cross.svg" alt=""
                                        id="BackIcon"></button>
                            </div>
                            <div class="verificationInfo">
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
                                            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                                            <a class="next" onclick="changeSlide(1)">&#10095;</a>

                                        </div>
                                <input type="hidden" name="ItemID" value="<?= $row['ItemID'] ?>">
                                <input name="Answer" id="Answer"
                                    placeholder="<?= $row['Verification_Question']; ?>" autocomplete="off" required></input>
                                <div id="userData">
                                    <input name="Name" class="userField" placeholder="Name" autocomplete="off" required>
                                    <input name="Email" class="userField" placeholder="Email" autocomplete="off" required>
                                    <input name="PhoneNo" class="userField" placeholder="Phone No." autocomplete="off" required>
                                </div>
                            </div>
                            <div class="images">
                                <?php $previewid = uniqid('previewid_'); ?>
                                <div class="ImagesPreview" id=<?= $previewid ?> >
                                    <i>Click here to add files~ 💕</i>
                                </div>
                                <input name="files[]" type="file" id="ImagesInput" multiple
                                    accept=".png,.jpg,.jpeg,.gif" hidden />
                                <button type="submit" id="SubmitButton"
                                    style="width: 50%; margin: 25px;">Submit</button>
                            </div>
                    </form>
                </div>
                <?php
                            }
                        }
                    ?>
            </div>
        </div>
    </div>
    <script src="ClaimItem.js"></script>
    <script src="../Cursor.js"></script>
</body>

</html>