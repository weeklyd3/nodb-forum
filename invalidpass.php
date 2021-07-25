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
    <title>Forums</title>
	<?php
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <?php 
  include('./libraries/lib.php');
  if (isset($_POST['login'])) {
	if (file_exists('./data/accounts/'.cleanFilename($_POST['user']).'/psw.txt')) {
		$USER = $_POST['user'];
		$PASS = $_POST['psw'];
		$clean = cleanFilename($USER);
		$hashfile = './data/accounts/'.$clean.'/psw.txt';
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
  <h1>Invalid Login</h1>
  <p>You have an invalid login. Please <a href="./account/login.php">log in again.</a> If this keeps appearing please open an issue on GitHub.</p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <label>Username: <input type="text" name="user" /></label>
  <label>Password: <input type="password" name="psw" /></label>
  <input type="hidden" name="login" value="login" />
  <input type="submit" value="log in" />
  </form>
  </body></html>