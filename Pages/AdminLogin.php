<?php
    session_start();
    $servername = "5.5.5.5";
    $username = "abdullah";
    $password = "abdullah";
    $database = "LostFoundDB";
    $port = 3306;
                        
    $conn = new mysqli($servername, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM Admin WHERE Email = '$email'";
    $result = $conn->query($sql);
    
    if ($row = $result->fetch_assoc())
    {
        if (password_verify($password, $row['Password'])) 
        {
            $_SESSION['Admin_ID'] = $row['Admin_ID'];
            header("Location: Dashboard.php");
            exit();
        } 
        else 
        {
            echo "Incorrect password.";
        }
    } 
    else
    {
        echo "Email not found.";
    }
    
    $conn->close();    
?>