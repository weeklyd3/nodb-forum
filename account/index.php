<html>
  <head>
    <base href=".." />
    <title>Forums &mdash; My Account</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	if ($_COOKIE['login']) {
		echo "<h2>".htmlspecialchars(getname()).'</h2>';
		echo '<details open="open">';
		echo '<summary>Account Options</summary>';
		echo '<button onclick="window.location.assign(\'account/changepass.php\');">Change Password</button>';
	} else {
		echo "You are not logged in.";
	}
	include('../public/footer.php'); ?>