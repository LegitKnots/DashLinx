<?php
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$tableName = 'icons';
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$tableExists = mysqli_query($connection, $checkTableQuery);
$query = "SELECT id, link, name FROM $tableName";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $link = $row['link'];
        $name = $row['name'];

        echo "<option value='".$id."'>".$name."</option>";   
    }
}

mysqli_close($connection);
?>
