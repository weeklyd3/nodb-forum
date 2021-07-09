<html>
  <head>
    <base href="../" />
    <title>Forums &mdash; Change Account Name</title>
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
			echo '<br><form method="post" action="'.$_SERVER['PHP_SELF'].'"><label for="pass">Existing password: </label><input type="password" name="psw" required="required" id="pass" /> <input type="hidden" value="verify" name="pages" /><input type="submit" value="Continue" /></form>';
		}
		if ($_POST['pages'] == 'verify') {
			$psw = $_POST['psw'];
			$clean = cleanFilename(getname());
			$hashfile = '../data/accounts/'.$clean.'/psw.txt';
			$hash = file_get_contents($hashfile);
			echo "<br>";
			if (password_verify($psw, $hash)) {
				echo '<h2>Change password</h2><form action="'.$_SERVER['PHP_SELF'].'" method="post"><label for="newpass">New password: </label><input name="newpass" id="newpass" type="password" /><br /><input type="hidden" name="pages" value="change" /><input type="submit" value="Change!" /></form>';
			} else {
				echo "Bad password!<br>";
			}
		}
		if ($_POST['pages'] == 'change') {
			$newpass = $_POST['newpass'];
			$newhash = password_hash($newpass, PASSWORD_DEFAULT);
			if (!$newhash) {
				echo "Bad. no hash created";
			} else {
				$folder = cleanFilename(getname());
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
	include('../public/footer.php'); ?>