<html>
  <head>
    <title>Forums &mdash; Home</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
    <?php 
	$login = $_COOKIE['login'];
	if ($login != "") {
		echo "Welcome, ".htmlspecialchars(getname())."! We're glad to have you. Choose a room to join:";
	} else {
		echo "You are not logged in. You will not be able to post any messages.";
	}
	include('./public/footer.php');
	?>
  </body>
</html>