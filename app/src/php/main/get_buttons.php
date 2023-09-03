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

$query = "SELECT title, link, image, folder, type FROM $tableName";

$result = mysqli_query($connection, $query);
$folderResult = mysqli_query($connection, $query);


$buttons = [];
$folders = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['type'] === 'button') {
            $buttons[] = $row;
        } elseif ($row['type'] === 'folder') {
            $folders[] = $row;
        }
    }
    $folders = array_reverse($folders); // reverses array so first created folders show first
    $buttons = array_reverse($buttons); // reverses array so first created buttons show first
    // loops through folders
    foreach ($folders as $folder) {
        echo "<div class='shortcut blur-when-folderOpen' id='".$folder['folder']."_openFolder' onclick=\"openFolder('".$folder['folder']."')\"><img src='".$folder['image']."'><h1>".$folder['title']."</h1></div>";
        echo "<div class='folderOpen' id='".$folder['folder']."'>";
        echo "<div class='folderOpen-dimBG'></div>";
        echo "<div class='folderOpen-header'><img src='".$folder['image']."'><h1>".$folder['title']."</h1>";
        echo "<div class='folderOpen-closeBtn' onclick=\"closeAllFolders()\"><i class='fa-solid fa-xmark'></i></div>";
        echo "</div>";
        echo "<div class='folder-shortcuttiles'>";

        // loops through the buttons for the folder
        foreach ($buttons as $button) {
            if ($button['folder'] === $folder['folder']) {
                echo "<div style='display: flex;' class='shortcut' id='".$button['folder']."' onclick=\"window.location.href='".$button['link']."'\"><img src='".$button['image']."'><h1>".$button['title']."</h1></div>";
            }
        }

        echo "</div>";
        echo "</div>";
    }
    foreach ($buttons as $button) {
        if ($button['folder'] === 'none') {
            echo "<div class='shortcut blur-when-folderOpen' onclick=\"window.location.href='".$button['link']."'\"><img src='".$button['image']."'><h1>".$button['title']."</h1></div>";
        }
    }
} else {
    echo "<div class='shortcut blur-when-folderOpen' onclick='window.location.href=\"/config\"'><img src='/src/images/icons/add_icon.png'><h1>Click here to add buttons</h1></div>";
}

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