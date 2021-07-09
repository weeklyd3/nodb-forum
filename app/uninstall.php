<html>
  <head>
	<base href="../" />
    <title>Forums &mdash; Uninstallation</title>
	<?php
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
		if (!file_exists('../config.json')) {
			header('Location: ..');
		}
	?>
	<h1><?php
		function cleanFilename($stuff) {
			$illegal = array(" ","?","/","\\","*","|","<",">",'"');
			// legal characters
			$legal = array("-","_","_","_","_","_","_","_","_");
			$cleaned = str_replace($illegal,$legal,$stuff);
			return $cleaned;
		}
		function verify() {
			$login = explode("\0", $_COOKIE['login']);
			$pass = $login[1];
			$firstlogin = $login[0];
			$actual = file_get_contents("../data/accounts/".cleanFilename($firstlogin) . '/psw.txt');
			if (password_verify($pass, $actual) && ($firstlogin == 'admin')) {
				return true;
			} else {
				return false;
			}
		}
		if (verify()) {
			$unlink = unlink("../config.json");
			if ($unlink) {
				echo "Uninstalled!</h1>Try opening Install again.<!--";
			} else {
				echo "Try reloading.";
			}
		} else {
			echo "You need access";
			echo "<br />Please <a href=\"account/login.php\">log in as 'admin'</a> to continue.";
		}
	?></h1>