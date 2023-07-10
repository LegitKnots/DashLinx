<?php
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$tableName = 'icons';
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$tableExists = mysqli_query($connection, $checkTableQuery);

if (mysqli_num_rows($tableExists) == 0) {
    $createTableQuery = "CREATE TABLE $tableName (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL
    )";

    if (!mysqli_query($connection, $createTableQuery)) {
        mysqli_close($connection);
        exit();
    }
}

$buttonId = $_POST['button-id'];
$query = "SELECT id, link FROM $tableName WHERE id=$buttonId";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];
    $link = $row['link'];
    
    $filePath = ROOT_DIR . $link;
	if (file_exists($filePath)) {
	    if (unlink($filePath)) {
	        // File deletion successful
	    } else {
	        // Error occurred while deleting the file
	        echo "Error: Unable to delete the file.";
	    }
	} else {
	    // File not found
	    echo "Error: File not found.";
	}

    $deleteQuery = "DELETE FROM $tableName WHERE id=$buttonId";
    if (mysqli_query($connection, $deleteQuery)) {
        echo "Icon Deleted!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    echo "Icon not found.";
}

mysqli_close($connection);
?>
