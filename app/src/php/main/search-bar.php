<?php
// Only gets provider from the database if it's not set in the POST array, i.e., user hasn't searched anything
if (!isset($_POST['searchprovider'])) {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$connection) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $tableName = 'general';

    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    $checkTableStatement = mysqli_query($connection, $checkTableQuery);

    if (mysqli_num_rows($checkTableStatement) == 0) {
        $createTableQuery = "CREATE TABLE $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            search_provider VARCHAR(255) NOT NULL DEFAULT 'turned_off'
        )";

        if (!mysqli_query($connection, $createTableQuery)) {
            mysqli_close($connection);
            exit();
        }
    }

    $query = "SELECT search_provider FROM $tableName";
    $selectStatement = mysqli_query($connection, $query);

    if ($row = mysqli_fetch_assoc($selectStatement)) {
        $searchprovider = $row['search_provider'];
    } else {
        $searchprovider = "turned_off";

        $insertDefaultRowQuery = "INSERT INTO $tableName (search_provider) VALUES (?)";
        $insertStatement = mysqli_prepare($connection, $insertDefaultRowQuery);
        mysqli_stmt_bind_param($insertStatement, "s", $searchprovider);
        mysqli_stmt_execute($insertStatement);
    }

    mysqli_close($connection);
} else {
    $searchprovider = $_POST['searchprovider'];
}

// Handle query appropriately with the specified provider
if (isset($_POST['query'])) {
    $query = $_POST['query'];


    $searchProviders = array(
        'Google' => 'https://www.google.com/search?q=',
        'Bing' => 'https://www.bing.com/search?q=',
        'Yahoo' => 'https://search.yahoo.com/search?p=',
        'DuckDuckGo' => 'https://duckduckgo.com/?q=',
        'Ask' => 'https://www.ask.com/web?q=',
        'Qwant' => 'https://www.qwant.com/?q=',
        'Startpage' => 'https://www.startpage.com/do/dsearch?query=',
        'Swisscows' => 'https://swisscows.com/web?query=',
        'OneSearch' => 'https://www.onesearch.com/search?q=',
        'Boardreader' => 'https://boardreader.com/s/'
    );


// starts with http:// or https://
if (preg_match('/^(https?:\/\/[\S]+)/', $query, $matches)) {
    header("Location: $query");
    exit;
}

// Check if the query is a valid domain
if (preg_match('/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/', $query) && checkdnsrr($query)) {
    header("Location: http://$query");
    exit;
}

// Check if the query is a valid address/path
if (preg_match('/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}\/([a-zA-Z0-9-\/]+)$/', $query)) {
    header("Location: http://$query");
    exit;
}

// address:port/path
if (preg_match('/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(:\d+)?\/([a-zA-Z0-9-\/]+)$/', $query)) {
    header("Location: http://$query");
    exit;
}

// valid IP
if (filter_var($query, FILTER_VALIDATE_IP)) {
    header("Location: http://$query");
    exit;
}

// address:port
if (preg_match('/^(.+):(\d+)$/', $query, $matches)) {
    header("Location: http://$query");
    exit;
}

// address:port/path
if (preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}):(\d+)(\/\S*)?$/', $query, $matches)) {
    header("Location: http://$query");
    exit;
}

$searchProviderURI = $searchProviders[$searchprovider];
$searchQuery = urlencode($query);
header("Location: $searchProviderURI$searchQuery");
exit;

}


// Only displays the search bar if the search provider is set in the database, i.e., not turned off in the settings
if ($searchprovider != "turned_off") {
    echo '
    <div class="search-bar">
        <form action="/src/php/main/search-bar.php" method="POST">
            <input type="hidden" name="searchprovider" value="' . $searchprovider . '">
            <input type="text" name="query" id="search-input" placeholder="Search with ' . $searchprovider . '" required>
            <button type="submit" class="search-button">
                <i class="fas fa-magnifying-glass search-icon"></i>
            </button>
        </form>
    </div>';
    
    echo '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById("search-input");
            searchInput.focus();
            searchInput.select();
        });
    </script>';
}
?>
