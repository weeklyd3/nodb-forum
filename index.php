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
	if ($login == "") {
		echo "<center><h2>You are not logged in. You may still browse the chat rooms in read-only mode.</h2></center>";
	} else {
		echo "Welcome, ".htmlspecialchars(getname())."! We're glad to have you. Choose a room to join:";
	}
	?> 
	<?php
	include('./public/footer.php');
	?>
  </body>
</html>