<?php

session_start();

if (!isset($_SESSION['last_run']) || time() - $_SESSION['last_run'] > 15) {
    $_SESSION['last_run'] = time();



$host = "5.5.5.5";
$user = "abdullah";
$password = "abdullah";
$dbname = "LostFoundDB"; 

$conn = new mysqli($host, $user, $password, $dbname);
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

require_once '../../mailer.php';

$htmlcontent = "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Admin Panel Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #a8e6cf 0%, #dcedc1 50%, #ffd3a5 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #8B5A9B;
            font-size: 2.5em;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: linear-gradient(135deg, #FFB6C1 0%, #FFC0CB 100%);
            border-radius: 20px;
            padding: 25px;
            border: 3px solid #000;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #2C5E2E;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            color: #2C5E2E;
            font-size: 1.1em;
            font-weight: 600;
        }

        .status-badge {
            background: linear-gradient(135deg, #98FB98 0%, #90EE90 100%);
            color: #2C5E2E;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            border: 2px solid #000;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            margin-top: 20px;
        }

        .footer p {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
            border-radius: 50%;
            border: 3px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-weight: bold;
            color: white;
            font-size: 1.2em;
        }

        @media (max-width: 600px) {
            .email-container {
                padding: 20px;
                margin: 10px;
            }

            .header h1 {
                font-size: 2em;
            }

            .info-card {
                padding: 20px;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class=\"email-container\">
        <div class=\"header\">
            <div class=\"logo\">AP</div>
            <h1>Admin Panel</h1>
            <p>System Report Notification</p>
        </div>

        <div class=\"content-grid\">
            <div class=\"info-card\">
                <div class=\"info-item\">
                    <span class=\"info-label\">Item Reported:</span>
                    <span class=\"info-value\">" . htmlspecialchars($item) . "</span>
                </div>

                <div class=\"info-item\">
                    <span class=\"info-label\">Email:</span>
                    <span class=\"info-value\">overlord@overlord.pp.ua</span>
                </div>

                <div class=\"info-item\">
                    <span class=\"info-label\">Phone No:</span>
                    <span class=\"info-value\">" . htmlspecialchars($phone) . "</span>
                </div>

                <div class=\"info-item\">
                    <span class=\"info-label\">Location:</span>
                    <span class=\"info-value\">" . htmlspecialchars($location) . "</span>
                </div>

                <div class=\"info-item\">
                    <span class=\"info-label\">Status:</span>
                    <span class=\"status-badge\">Active</span>
                </div>
            </div>
        </div>

        <div class=\"footer\">
            <p>This is an automated notification from your Admin Panel System.</p>
            <p>Please do not reply to this email.</p>
            <p style=\"font-size: 0.8em; color: #999; margin-top: 15px;\">
                Generated on: <strong>June 15, 2025</strong>
            </p>
        </div>
    </div>
</body>
</html>";

$result = sendMail($email, 'Item Reported Successfully', $htmlcontent);

// Insert into main report table

if($result === true){
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
    $query_run2 = $conn->query($sql3);
    
    $sql4 = "SELECT ItemID FROM Item ORDER BY ItemID DESC LIMIT 1";
    $query_run3 = $conn->query($sql4);
    $row = $query_run3->fetch_assoc();

    $itemid = $row['ItemID'];

    
    $targetDir = "../Assets/Reported Images/" . $reporterid . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetDir2 = "../Assets/Reported Images/" . $reporterid . "/" . $itemid . "/";
    if (!is_dir($targetDir2)) {
        mkdir($targetDir2, 0777, true);
    }


    $uploadedFiles = $_FILES['files'];
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
        $targetPath = $targetDir2 . $newFileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            // Optional: Store filename in a separate images table
            $imgSql = "INSERT INTO ItemPhoto (ItemID, Path, Description) VALUES ('$itemid', '$targetPath', '$newFileName')";
            $imgStmt = $conn->query($imgSql);
        }
    }

    echo "Report submitted successfully!";
} else {
    echo "Failed to submit report.";
}
} else {
    echo "Invalid Email Address.";
}

$conn->close();
}
?>