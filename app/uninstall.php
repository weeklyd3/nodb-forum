<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><html>
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