<?php
require_once("../src/php/session.php");

if (isset($_GET['tab'])) {
  if ($_GET['tab'] == "1") {
  	$page = "button_acts.php";
  	$tabs = '<div class="tab" id="active-tab">Buttons</div><div class="tab" onclick="location.href=\'?tab=2\'">Settings</div><div class="tab" onclick="location.href=\'?tab=3\'">Contribute</div>';
  } elseif ($_GET['tab'] == "2") {
  	$page = "settings.php";
  	$tabs = '<div class="tab" onclick="location.href=\'?tab=1\'">Buttons</div><div class="tab" id="active-tab">Settings</div><div class="tab" onclick="location.href=\'?tab=3\'">Contribute</div>';
  } elseif ($_GET['tab'] == "3") {
  	$page = "contrib.php";
  	$tabs = '<div class="tab" onclick="location.href=\'?tab=1\'">Buttons</div><div class="tab" onclick="location.href=\'?tab=2\'">Settings</div><div class="tab" id="active-tab">Contribute</div>';
  } else {
  	$page = "button_acts.php";
  	$tabs = '<div class="tab" id="active-tab">Buttons</div><div class="tab" onclick="location.href=\'?tab=2\'">Settings</div><div class="tab" onclick="location.href=\'?tab=3\'">Contribute</div>';
  }
} else {
    $page = "button_acts.php";
    $tabs = '<div class="tab" id="active-tab">Buttons</div><div class="tab" onclick="location.href=\'?tab=2\'">Settings</div><div class="tab" onclick="location.href=\'?tab=3\'">Contribute</div>';
}
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <div class="title">
      <h2 class="titlecontent"><?=PAGE_TITLE?></h2>
      <h3 class="titlecontent" id="time"></h3>
  </div>
  <button class="bth" onclick="location.href='/'">< Back</button>

  <br class="top-spacer">
  <div class="content">
  	<div class="tabbar">
  		<?= $tabs?>
  	</div>
    <div class="forms-content">
  	<?php require_once($page);?>
    </div>
  </div>

</body>
<footer>
  <p>Made by AJP Networks LLC :)</p>
</footer>
</html>

