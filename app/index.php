<?php
include('src/php/session.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=TAB_TITLE?></title>
  <link rel="stylesheet" type="text/css" href="src/css/main.css">
  <link rel="stylesheet" type="text/css" href="src/css/buttons.css">
  <link rel="stylesheet" type="text/css" href="src/css/search-bar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <script type="text/javascript" src="src/js/script.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <div class="title">
      <h2 class="titlecontent"><?=PAGE_TITLE?></h2>
      <h3 class="titlecontent" id="time"></h3>
  </div>
  <br class="top-spacer">
      <?php require_once("src/php/main/search-bar.php");?>
  <div class="shortcuttiles">
      <?php require_once("src/php/main/get_buttons.php");?>
  </div>

</body>
<footer class="blur-when-folderOpen">
  <button id="lower-button" onclick="window.location.href='/config'">Configure</button>
  <br>
  <p>Made by AJP Networks LLC :)</p>
</footer>
</html>