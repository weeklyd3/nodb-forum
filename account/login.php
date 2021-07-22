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
    <title>Log In</title>
	<?php
	ob_start();
 include '../public/header.php';
 include '../styles/inject.php';
 if ($_POST['c'] == 'login') {
	 if (file_exists('../data/accounts/'.cleanFilename($_POST['user']).'/psw.txt')) {
		$USER = $_POST['user'];
		$PASS = $_POST['psw'];
		$clean = cleanFilename($USER);
		$hashfile = '../data/accounts/'.$clean.'/psw.txt';
		$hash = file_get_contents($hashfile);
		$PASSHASH = password_hash($PASS, PASSWORD_DEFAULT);
		echo "<br>";
		if (password_verify($PASS, $hash)) {
			$COOK = $USER . "\0" . $PASS;
			setcookie('login', $COOK, time() + 72000, '/');
			echo "<script>location.href='/';</script>";
		} else {
			echo "Bad password!<br>";
		}
 	} else {
		 echo "Bad username<br>";
	 }
 }
 ?>
  </head>
  <body>
  <center>
  <h2>Log in</h2>
  <form action="account/login.php" method="post">
  <input type="hidden" name="c" value="login" />
  <label for="user">Username:</label>
  <input type="text" id="user" name="user" />
  <br>
  <label for="psw">Password:</label>
  <input spellcheck="false" type="password" id="psw" name="psw" />
  <button type="button" onclick="if(document.getElementById('psw').type=='text'){document.getElementById('psw').type='password';}else{document.getElementById('psw').type='text';}">Show/Hide</button>
  <br>
  <br>
  <input type="submit" value="Log In" />
  </form></center>
  To log in you need an account.

  <p>If you do not have one, you can register using the 
  "Sign Up" link on the top.</p>