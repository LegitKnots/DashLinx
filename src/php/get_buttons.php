<?php

// Create a connection to the MySQL database
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection was successful
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Check if the table exists, and create it if it doesn't
$tableName = 'buttons';
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$tableExists = mysqli_query($connection, $checkTableQuery);

if (mysqli_num_rows($tableExists) == 0) {
    // Table doesn't exist, so create it
    $createTableQuery = "CREATE TABLE $tableName (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL,
        image VARCHAR(255) NOT NULL,
        folder VARCHAR(255) NOT NULL
    )";

    if (mysqli_query($connection, $createTableQuery)) {

    } else {
        mysqli_close($connection);
        exit();
    }
}

// Create a SQL query to select the desired columns from the table
$query = "SELECT title, link, image, folder FROM $tableName";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row of the result set
    while ($row = mysqli_fetch_assoc($result)) {
        
        $title = $row['title'];
        $link = $row['link'];
        $image = $row['image'];
        $folder = $row['folder'];

        if ($folder == "button") {
            echo "<div class='shortcut' onclick=\"window.location.href='".$link."'\"><img src='".$image."'><h1>".$title."</h1></div>";
        }
    }
} else {
    echo "<div class='shortcut' onclick='window.location.href=\"/config\"'><img src='/src/images/add_icon.png'><h1>Click here to add buttons</h1></div>";
}

// Close the database connection
mysqli_close($connection);

?>