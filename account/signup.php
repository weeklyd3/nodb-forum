<html>
  <head>
	<base href="../" />
    <title>Sign Up</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
  <form action="account/signup.php" method="post">
	<?php
	$page = $_POST['page'];
	if ($page == "1") {
		echo '<script>function change(){if(document.getElementById("psw").type=="password"){document.getElementById("psw").type="text";}else{document.getElementById("psw").type="password";}}</script>';
		echo '<input type="hidden" value="2" name="page" />';
		echo '<center><h2>Fill in information. This is step two of three.</h2>';
		echo '<big><strong>Be careful! You cannot change any of these fields after you sign up!</strong></big><br>';
		echo '<script>function random(min, max) {return Math.random() * (max - min) + min;}</script>';
		echo '<label for="usr"><strong>Enter your username (3-25 characters).</strong><br><small>It is recommended to not use your real name for privacy online. Anyone can screenshot or see your username, as it is available publicly.</small><br></label>';
		echo '<input minlength="3" maxlength="25" required="required" type="text" size="25" id="usr" name="usr" />';
		echo '&nbsp;';
		echo '<button type="button" onclick="document.getElementById(\'usr\').value=\'user\'+Math.round(random(1000000000,9999999999));">Generate random</button>';
		echo '<br><label for="psw">Enter a password:</label><br>';
		echo '<input spellcheck="false" type="password" id="psw" name="psw" required="required" />';
		echo '&nbsp;&nbsp;';
		echo '<button type="button" onclick="change()">Show/Hide Password</button>';
		echo '<br><br>';
		echo '<input type="submit" value="Continue" />';
	} else if ($page == "2") {
		$PASSWORD = $_POST['psw'];
		$USER = $_POST['usr'];
		$directory = cleanFilename($USER);
		if (!(file_exists('../data/accounts/'.$directory.'/user.txt'))) {
			echo "<center><h2>Hi, @".htmlspecialchars($USER)."! Just a second...</h2>";
			echo "<h3>We're setting up your account. This may take a long time. Please do not close this window, as all data will be lost if you do so. Thank you.</h3></center>";
			$hash = password_hash($PASSWORD, PASSWORD_DEFAULT);
			if (!$hash) {
				echo "Password hash not created. Try reloading.";
			} else {
				echo "Password hash created.";
			}
			$illegal = array(" ","?","/","\\","*","|","<",">",'"');
			// legal characters
			$legal = array("-","_","_","_","_","_","_","_","_");
			$directory = str_replace($illegal,$legal,$USER);
			$fullname = '../data/accounts/'.$directory;
			$dirstatus = mkdir('../data/accounts/'.$directory, 0777, true);
			if ($dirstatus) {
				echo "<br>Your personal directory was created.";
			} else {
				echo "<br>Bad bad bad. Your personal directory was not created.";
			}
			$hashfile = fopen($fullname . "/psw.txt", 'w+');
			$hashwrite = fwrite($hashfile, $hash);
			fclose($hashfile);
			if ($hashwrite) {
				echo "<br>Hash file generated.";
			} else {
				echo "<br>Houston, we have a really serious problem... We can't write the hash!";
			}
			$userfile = fopen($fullname . "/user.txt", 'w+');
			$userwrite = fwrite($userfile, $USER);
			fclose($userfile);
			if ($userwrite) {
				echo "<br>Account registration completed.";
			} else {
				echo "<br>This is <em>really</em> serious. Start again. Thanks!";
			}
		} else {
			echo "Username is taken";
		}
	} else if ($page != "1" && $page != "2") {
		echo '<center><h2>Welcome: This is step one of three.</h2>';
		echo "<h3>Please read this information, then state if you agree with it.</h3></center>";
		include('../tos_raw.php');
		echo '<center>';
		echo '<label for="stuff">I agree to these terms:<br></label>';
		echo '<input type="checkbox" id="stuff" required="required" style="width:20px;" />';
		echo '<br>';
		echo '<input type="hidden" value="1" name="page" />';
		echo '<input type="submit" value="Continue" />';
		echo '</center>';
	}
?>
<?php
	include('../public/footer.php');
	?>
	</form>
	</body>
	</html>