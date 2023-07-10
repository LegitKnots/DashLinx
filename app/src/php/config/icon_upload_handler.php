<?php
require_once("../session.php");
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Check if the file was uploaded without errors
if ($_FILES['icontoUpload']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['icontoUpload']['tmp_name'];
    $originalName = $_FILES['icontoUpload']['name'];
    
    // Specify the directory where you want to store the uploaded files
    $uploadDir = ROOT_DIR . '/src/images/uploaded/';
    
    $newFileName = "Uploaded_" . uniqid() . "_" . $originalName;
    $destination = $uploadDir . $newFileName;
    $location = '/src/images/uploaded/' . $newFileName;
    
    // Move the uploaded file to the desired directory
    if (move_uploaded_file($tempFile, $destination)) {
        // File moved successfully
        
        // Store the file details in the database
        $iconName = $_POST['icon-name'] . " (Cutom Uploaded)";
        
        // Insert the file details into the database table
        $sql = "INSERT INTO icons (name, link) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $iconName, $location);
        mysqli_stmt_execute($stmt);
        
        // Return a response indicating success
        echo "Icon uploaded successfully.";
    } else {
        // Error moving the file
        echo "Error moving the uploaded icon to:  ";
        echo $destination;
    }
} else {
    // Error uploading the file
    echo "Error uploading the icon.";
}
?>
