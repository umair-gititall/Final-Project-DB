<?php
$host = "5.5.5.5";
$user = "LostFoundSystem";
$password = "LostFoundManagementSystem";
$dbname = "LostFoundDB"; 

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$area = $_POST['listbox'];
$phone = $area . $_POST['phone'];
$item = $_POST['item'];
$date = $_POST['date'];
$location = $_POST['location'];
$description = $_POST['description'];


function validateEmailViaNode($email) {
    $data = json_encode(["email" => $email]);

    $ch = curl_init('http://5.5.5.5:4001/validate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['valid'] ?? false;
}



if (validateEmailViaNode($email)) {
require_once '../../Requirements/LFMS/mailer.php';
$htmlcontent = file_get_contents('../Email/ReportSubmitted.html');

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
    $result = sendMail($email, 'Item Reported Successfully', $htmlcontent);
    echo "Report submitted successfully!";
} else {
    echo "Failed to submit report.";
}
} else {
    echo "Invalid Email Address.";
}

$conn->close();

?>