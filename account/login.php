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
		$expiry = is_numeric($_POST['expiry']) ? (int) $_POST['expiry'] : 72000;
		if (password_verify($PASS, $hash)) {
			$COOK = $USER . "\0" . $PASS;
			setcookie('login', $COOK, time() + $expiry, '/');
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
  <p><details id="hide"><summary id="more">Advanced</summary><label>Log out after seconds: <input type="number" name="expiry" /></label></details></p>
  <style>#hide[open] > #more {
	  display: none;
  }
  #hide { list-style: none; }</style>
  <input type="submit" value="Log In" />
  </form><small>Note: By default you will stay logged in for 20 hours. You can change this through "Advanced".</small></center>
  <h3>Forgetful?</h3>
  <ul>
  <li><a href="javascript:;" onclick="document.getElementById('overlay').style.display = 'block';">Forgot user name?</a></li>
  <li><a href="./account/resetpass.php">Forgot password?</a></li>
  </ul>
  To log in you need an account.

  <p>If you do not have one, you can register using the 
  "Sign Up" link on the top.</p>
  <div id="blanket" class="blanket">
	<div class="overlay" id="overlay">
		<h3>I lost my username</h3>
		If you lost your username...
		<ol>
			<li>Enter what you think it is. You might still remember it!</li>
			<li>If you can't...
				<ol>
					<li><a href="users.php">Head over to the list of users.</a></li>
					<li>Find what might be your username.</li>
					<li>See if it matches by entering your password.</li>
				</ol>
			</li>
		</ol>
		<button onclick="this.parentNode.setAttribute('style','');">Got it</button>
	</div>
  </div>