<html>
  <head>
	<base href="../" />
    <title>Logging Out...</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	echo '<em>Please wait...</em>';
	setcookie('login', '', time() - 3600, '/');
	echo '<p>Logged out!</p>';
	include('../public/footer.php');
	echo '<script>location.href="/";</script>';
	?>
	</body>
	</html>