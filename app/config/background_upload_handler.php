<?php
require_once("../src/php/session.php");

// Check if the file was uploaded without errors
if ($_FILES['BGtoUpload']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['BGtoUpload']['tmp_name'];
    
    // Specify the directory where you want to store the uploaded files
    $uploadDir = ROOT_DIR . '/src/images/';
    
    $newFileName = "background.png";
    $destination = $uploadDir . $newFileName;
    
    // Move the uploaded file to the desired directory
    if (move_uploaded_file($tempFile, $destination)) {
        
        // Return a response indicating success
        echo "Background updated successfully.";
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
