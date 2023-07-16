<?php
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}
if (isset($_POST['search_provider'])) {
    $searchProvider = mysqli_real_escape_string($connection, $_POST['search_provider']);

    $query = "UPDATE general SET search_provider = '$searchProvider'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Search engine updated successfully.";
    } else {
        echo "Error updating search engine: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request. Please provide the search provider.";
}
?>
