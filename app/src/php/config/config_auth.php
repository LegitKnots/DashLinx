<?php

$msg = "";
$nodisplay = true;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno() ) {exit('Failed to connect to MySQL: ' . mysqli_connect_error());}
$stmt = $con->prepare('SELECT config_password, config_token FROM general');
$stmt->execute();
$stmt->bind_result($psw, $token);
$stmt->fetch();
$stmt->close();

if ($token !== null && isset($_COOKIE['config_token']) && $_COOKIE['config_token'] == $token) { // if token valid
	$nodisplay = false;

} elseif ($psw == null) { // if psw in db is null
	$nodisplay = false;

} 

if (isset($_POST['password'])) { // if psw was given from post
	
		if ($token == null) { 
			$token = bin2hex(random_bytes(32));
			$stmt = $con->prepare('UPDATE general SET config_token=?');
			$stmt->bind_param("s", $token);
			$stmt->execute();
			$stmt->close();
		}

		if (password_verify($_POST['password'], $psw)) { // if psw matches db

			$expire_time = time() + (60 * 60 * 24 * 14);
			setcookie('config_token', $token, $expire_time, '', '', false, false);
			
			header("Refresh: 0");	
			exit;
		} else {
			$msg = "Password Incorrect";
			$nodisplay = true;
		}
}

if ($nodisplay == true) { 

	?>
	<!DOCTYPE html>
		<html>
		<head>
		  <meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title><?=TAB_TITLE?></title>
		  <link rel="stylesheet" type="text/css" href="../src/css/main.css">
		  <link rel="stylesheet" type="text/css" href="../src/css/config.css">
		  <link rel="stylesheet" type="text/css" href="../src/css/forms.css">
		  <script type="text/javascript" src="../src/js/script.js"></script>
		</head>
		<body>
		  <div class="title">
		      <h2 class="titlecontent"><?=PAGE_TITLE?></h2>
		      <h3 class="titlecontent" id="time"></h3>
		  </div>
		  <button class="bth" onclick="location.href='/'">< Back</button>
		
		  <br class="top-spacer">
		  <div class="login_content">
		  	<h2>Enter Password to Configure DashLinx</h2>
		  	<form method="post" action="">
		  		<input type="password" name="password" placeholder="Password"><br>
		  		<input type="submit" name="submit" value="Login">
		  		<?=$msg?>
		  	</form>
		  </div>
		
		</body>
		<footer>
		  <p>Made by AJP Networks LLC :)</p>
		</footer>
		</html>


	<?php

}