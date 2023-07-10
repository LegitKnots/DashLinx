<?php

if (isset($_POST['del']) && isset($_POST['button-id'])) {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$connection) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    $button_id = $_POST['button-id'];
    $deleteQuery = "DELETE FROM buttons WHERE id=?";
    $stmt = mysqli_prepare($connection, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $button_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Button Deleted!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
    
    mysqli_close($connection);
}

if (isset($_POST['title']) && isset($_POST['link']) && isset($_POST['image']) && isset($_POST['add'])) {

	$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if (!$connection) {
		die('Connection failed: ' . mysqli_connect_error());
	}
	
	$title = $_POST['title'];
	$link = $_POST['link'];
	$image = $_POST['image'];
	$folder = "button";
	
	$insertQuery = "INSERT INTO buttons (title, link, image, folder) VALUES (?, ?, ?, ?)";
	$stmt = mysqli_prepare($connection, $insertQuery);
	mysqli_stmt_bind_param($stmt, "ssss", $title, $link, $image, $folder);
	
	if (mysqli_stmt_execute($stmt)) {
		echo "Button Added!";
	} else {
		echo "Error: " . mysqli_error($connection);
	}
	
	mysqli_close($connection);

}

if (isset($_POST['edit']) && isset($_POST['button-id'])) {
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$buttonId = $_POST['button-id'];

	// Retrieve the existing button details from the database
	$selectQuery = "SELECT title, link, image, folder FROM buttons WHERE id = ?";
	$stmt = mysqli_prepare($connection, $selectQuery);
	mysqli_stmt_bind_param($stmt, "i", $buttonId);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $existingTitle, $existingLink, $existingImage, $existingFolder);
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);
	
	// Assign the existing values if the corresponding POST values are empty
	$title = $_POST['title'] !== '' ? $_POST['title'] : $existingTitle;
	$link = $_POST['link'] !== '' ? $_POST['link'] : $existingLink;
	$image = $_POST['image'] !== '' ? $_POST['image'] : $existingImage;
	$folder = $_POST['folder'] !== '' ? $_POST['folder'] : $existingFolder;
	
	// Prepare an array to store the column-value pairs
	$columnsToUpdate = array();
	
	// Check each variable and add the non-empty ones to the update array
	if ($title !== '') {
	    $columnsToUpdate['title'] = $title;
	}
	if ($link !== '') {
	    $columnsToUpdate['link'] = $link;
	}
	if ($image !== '') {
	    $columnsToUpdate['image'] = $image;
	} else {
		$columnsToUpdate['image'] = $image;
	}
	if ($folder !== '') {
	    $columnsToUpdate['folder'] = $folder = "button";
	}
	
	// Generate the SQL update statement
	$updateQuery = "UPDATE buttons SET ";
	$updateParams = array();
	foreach ($columnsToUpdate as $column => $value) {
	    $updateQuery .= $column . " = ?, ";
	    $updateParams[] = &$columnsToUpdate[$column];
	}
	$updateQuery = rtrim($updateQuery, ", ");
	$updateQuery .= " WHERE id = ?";
	$updateParams[] = &$buttonId;
	
	// Execute the update query
	$stmt = mysqli_prepare($connection, $updateQuery);
	mysqli_stmt_bind_param($stmt, str_repeat("s", count($columnsToUpdate)) . "i", ...$updateParams);
	
	if (mysqli_stmt_execute($stmt)) {
	    echo "Button Updated!";
	} else {
	    echo "Error: " . mysqli_error($connection);
	}
	
	mysqli_stmt_close($stmt);
	mysqli_close($connection);


}


?>



<div class="button-form">
	<h2>Add a New Button</h2>

	<form method="POST" action="">
		<input type="hidden" name="add">
		<input type="text" name="title" placeholder="Title" required>
		<br>
		<input type="text" name="link" placeholder="Link" required>
		<br>
	<select name="image">
		<?php include 'icon_list.php' ?>
	</select>
	<br>
	<input type="submit" name="submit" value="Add">
	</form>
</div>

<hr class="spacer-hr">


<div class="button-form">
	<h2>Edit Button</h2>

	<form method="POST" action="">
		<input type="hidden" name="edit" value="true">
		<select name="button-id" id="button-select" required>
			<option disabled selected>--Select Button--</option>
			<?php include 'button_list.php' ?>
		</select>
		<br>
		<input type="text" name="title" id="title-input" placeholder="Title" style="display: none;">
		<br>
		<input type="text" name="link" id="link-input" placeholder="Link" style="display: none;">
		<br>
		<select name="image" id="image-select" style="display: none;">
			<?php include 'icon_list.php' ?>
		</select>
		<br>
		<input type="submit" name="submit" value="Edit">
	</form>
</div>


<hr class="spacer-hr">

<div class="button-form">
	<h2>Delete Button</h2>

	<form method="POST" action="">
		<input type="hidden" name="del" value="true">
	<select name="button-id" required>
		<option disabled selected>--Select Button--</option>
		<?php include 'button_list.php' ?>
	</select>
	<br>
	<input type="submit" name="submit" value="Delete">
	</form>
</div>


<script>
	document.getElementById("button-select").addEventListener("change", function() {
		var selectedIndex = this.selectedIndex;
		if (selectedIndex > 0) {
			document.getElementById("title-input").style.display = "flex";
			document.getElementById("link-input").style.display = "flex";
			document.getElementById("image-select").style.display = "flex";
		} else {
			document.getElementById("title-input").style.display = "none";
			document.getElementById("link-input").style.display = "none";
			document.getElementById("image-select").style.display = "none";
		}
	});
</script>