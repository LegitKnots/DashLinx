<?php

// Create a connection to the MySQL database
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection was successful
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Check if the table exists, and create it if it doesn't
$tableName = 'buttons';

// Create a SQL query to select the desired columns from the table
$query = "SELECT id, title FROM $tableName";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row of the result set
    while ($row = mysqli_fetch_assoc($result)) {
        
        $id = $row['id'];
        $title = $row['title'];

        
        echo "<option value='".$id."'>".$title."</option>";
        
    }
} else {
    echo "<option disabled>No Buttons Added</option>";
}

// Close the database connection
mysqli_close($connection);

?>