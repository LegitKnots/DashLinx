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
$query = "SELECT title, link, image, folder, type FROM $tableName";

// Execute the query
$result = mysqli_query($connection, $query);
$folderResult = mysqli_query($connection, $query);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row of the result set
    while ($row = mysqli_fetch_assoc($result)) {
        
        $title = $row['title'];
        $link = $row['link'];
        $image = $row['image'];
        $type = $row['type'];
        $folder = $row['folder'];

        if ($type == "button" && $folder == "none") {
            echo "<div class='shortcut' onclick=\"window.location.href='".$link."'\"><img src='".$image."'><h1>".$title."</h1></div>";
        } elseif($type == "folder") {

            echo "<div class='shortcut blur-when-folderOpen' id='".$folder."_openFolder' onclick=\"openFolder('".$folder."')\"><img src='".$image."'><h1>".$title."</h1>";
            echo "</div>";
            echo "<div class='folderOpen' id='".$folder."'>";
            echo "<div class='folderOpen-dimBG'></div>";
            echo "<div class='folderOpen-header'><img src='".$image."'><h1>".$title."</h1>";
            echo "<div class='folderOpen-closeBtn' onclick=\"closeAllFolders()\"><i class='fa-solid fa-xmark'></i></div>";
            echo "</div>";

                while($folderRow = mysqli_fetch_assoc($folderResult)) {

                    if($folderRow['folder'] == $folder && $folderRow['type'] == "button") {

                        echo "<div style='display: flex;' class='shortcut' id='".$folderRow['folder']."' onclick=\"window.location.href='".$folderRow['link']."'\"><img src='".$folderRow['image']."'><h1>".$folderRow['title']."</h1></div>";
                    }
                }
            echo "</div>";
            

        }
    }
} else {
    echo "<div class='shortcut blur-when-folderOpen' onclick='window.location.href=\"/config\"'><img src='/src/images/icons/add_icon.png'><h1>Click here to add buttons</h1></div>";
}

// Close the database connection
mysqli_close($connection);

?>

<script>

function openFolder(folder) {

    var buttons = document.getElementById(folder);
    buttons.style.display="flex";


    var folderBtn = document.getElementById(folder + "_openFolder");
    folderBtn.setAttribute('style', 'transform: scale(1.1); background-color: rgba(255, 255, 255, 0.2) !important; box-shadow: rgba(0, 0, 0, 0.1) 0px 60px 40px -7px !important;');




    console.log("Folder Opened" + folder);

    // Creates blur effect on elements that require it
    var blurElement = document.getElementsByClassName('blur-when-folderOpen');
    for (var i = 0; i < blurElement.length; i++) {
        blurElement[i].style.filter = "blur(5px)";
    }


}


function closeAllFolders() {
    var folders = document.getElementsByClassName("folderOpen")

    // Hides the folder itself
    for (var i = 0; i < folders.length; i++) {
        folders[i].style.display = "none";
    }

    // Clears the blur effect
    var blurElement = document.getElementsByClassName('blur-when-folderOpen');
    for (var i = 0; i < blurElement.length; i++) {
        blurElement[i].style.filter = "none";
    }

    var folderBtns = document.querySelectorAll('[id$="_openFolder"]');
    folderBtns.forEach(function(folderBtn) {
        folderBtn.removeAttribute('style');
    });





    console.log("Folder Closed");
    


}

// Let's the Escape key also close the folder
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllFolders();
        }
    });
});


</script>