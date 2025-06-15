<?php
    $servername = "5.5.5.5";
    $username = "LostFoundSystem";
    $password = "LostFoundManagementSystem";
    $database = "LostFoundDB";
    $port = 3306;  
    $conn = new mysqli($servername, $username, $password, $database, $port);

    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ItemID"]) && isset($_POST["Answer"]) && isset($_POST["Name"]) && isset($_POST["Email"]) && isset($_POST["PhoneNo"])){
        $itemid = $_POST['ItemID'];
        $answer = $_POST['Answer'];
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $phoneno= $_POST['PhoneNo'];

        $sql_check = "SELECT UserID FROM User WHERE Email = '$email'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            $sql2 = "INSERT INTO User (UserName, PhoneNo, Email) VALUES ('$name' ,'$phoneno', '$email')";
            $conn->query($sql2);
        }
        $sql3 = "SELECT UserID FROM User WHERE Email = '$email'";
        $query_run = $conn->query($sql3);

        if ($query_run) {
            $row = $query_run->fetch_assoc(); 

            $userid = $row['UserID'];

            $sql4 = "SELECT * FROM Claim_Request WHERE ItemID = '$itemid' AND Claim_Status = 'Approved'";
            $query_run5 = $conn->query($sql4);

            if($query_run5->num_rows != 0){
                    die("Item is already Claimed");
            }
            $sql5 = "INSERT INTO Claim_Request (Date_of_Claim, Answer, UserID, ItemID) VALUES (CURDATE(), '$answer', '$userid', '$itemid')";
            $conn->query($sql5);

            $sql6 = "SELECT ClaimID FROM Claim_Request ORDER BY ClaimID DESC LIMIT 1";
            $query_run6 = $conn->query($sql6);
            $row = $query_run6->fetch_assoc();

            $claimid = $row['ClaimID'];

            $targetDir = "../Assets/Claim Request Images/" . $claimid . "/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $uploadedFiles = isset($_FILES['files']) ? $_FILES['files'] : null;
            if ($uploadedFiles && isset($uploadedFiles['name'])){
            $totalFiles = count($uploadedFiles['name']);

            for ($i = 0; $i < $totalFiles; $i++) {
                $tmpName = $uploadedFiles['tmp_name'][$i];
                $originalName = basename($uploadedFiles['name'][$i]);
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $ext = strtolower($ext);
        
                // Only allow certain image types
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) continue;
        
                // Create unique filename based on report ID
                $newFileName = ($i + 1) . "." . $ext;
                $targetPath = $targetDir . $newFileName;
        
                if (move_uploaded_file($tmpName, $targetPath)) {
                    // Optional: Store filename in a separate images table
                    $imgSql = "INSERT INTO ClaimPhoto (ClaimID, Path, Description) VALUES ('$claimid', '$targetPath', '$newFileName')";
                    $imgStmt = $conn->query($imgSql);
                }
            }
        }
        echo "Claim Request submitted successfully!";
        }
        else {
            echo "Failed to submit report.";
        }
    }
?>