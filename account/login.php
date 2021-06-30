<html>
  <head>
	<base href="../" />
    <title>Log In</title>
	<?php
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
			$_COOKIE['login'] = $COOK;
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
  </form>