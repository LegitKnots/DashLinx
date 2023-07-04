<?php

if (isset($_POST['del'])) {
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

if (isset($_POST['title']) && isset($_POST['link']) && isset($_POST['image'])) {

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


?>



<div class="button-form">
	<h2>Add a New Button</h2>

	<form method="POST" action="">
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
	<h2>Delete Button</h2>

	<form method="POST" action="">
		<input type="hidden" name="del" value="true">
	<select name="button-id" required>
		<option disabled selected>--Select One--</option>
		<?php include 'button_list.php' ?>
	</select>
	<br>
	<input type="submit" name="submit" value="Delete">
	</form>
</div>