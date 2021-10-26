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
?><html lang="en">
  <head>
    <base href="../" />
    <title>Change Account Name</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	if ($_COOKIE['login']) {
		if (!$_POST['pages']) {
			echo "<h2>Change Password for ".htmlspecialchars(getname()).'</h2>';
			echo '<strong>WARNING: The passwords are hashed, so we can&apos;t recover it if you lose it!</strong><br><form action="'. $_SERVER['PHP_SELF'].'" method="post"><input type="checkbox" required="required" id="confirm" />&nbsp;&nbsp;<label for="confirm">I know that.</label><input type="hidden" name="pages" value="confirm" /><br><input type="submit" value="Next >" /></form>';
		} if ($_POST['pages'] == 'confirm') {
			echo "<h2>Confirm access</h2>";
			echo '<br><form method="post" action="'.$_SERVER['PHP_SELF'].'"><label for="pass">Existing password: </label><input type="password" name="psw" id="pass" /> <input type="hidden" value="verify" name="pages" /><input type="submit" value="Continue" /></form>';
		}
		if ($_POST['pages'] == 'verify') {
			$psw = $_POST['psw'];
			$clean = cleanFilename(getname());
			$hashfile = '../data/accounts/'.$clean.'/psw.txt';
			$hash = file_get_contents($hashfile);
			echo "<br>";
			if (password_verify($psw, $hash)) {
				echo '<h2>Change password</h2><form action="'.$_SERVER['PHP_SELF'].'" method="post"><input type="hidden" name="pass" value="'. htmlspecialchars($_POST['psw']) .'" /><label for="newpass">New password: </label><input name="newpass" id="newpass" type="password" /><br /><input type="hidden" name="pages" value="change" /><input type="submit" value="Change!" /></form>';
			} else {
				echo "Bad password!<br>";
			}
		}
		if ($_POST['pages'] == 'change') {
			$folder = cleanFilename(getname());

			$newpass = $_POST['newpass'];
			if (!password_verify($_POST['pass'], file_get_contents("../data/accounts/".$folder."/psw.txt"))) {
				?>Bad request, may have been forged<?php
				exit(0);
			}
			$newhash = password_hash($newpass, PASSWORD_DEFAULT);
			if (!$newhash) {
				echo "Bad. no hash created";
			} else {
				$handle = fopen("../data/accounts/".$folder."/psw.txt", 'w+');
				$write = fwrite($handle, $newhash);
				if ($write) {
					echo "Password changed";
				} else {
					echo "Error. Reload!";
				}
			}
		}
	} else {
		echo "You are not logged in.";
	}
