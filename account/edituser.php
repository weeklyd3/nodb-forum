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
    <base href=".." />
    <title>Forums &mdash; Edit My Account</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	?>
  </head>
  <body>
	<?php 
		$dir = __DIR__ . '/../data/accounts/'.cleanFilename(getname());
		$obj = json_decode(file_get_contents($dir.'/user.json'));
		if (isset($_POST['text'])) {
			$obj = (object) $_POST;
			$enc = json_encode($obj);
			$dir = fopen($dir.'/user.json', 'w+');
			if (fwrite($dir, $enc)) {
				echo 'Profile saved, <a href="account/viewuser.php?user='.htmlspecialchars(getname()).'">view it now?</a>';
			} else {
				echo 'Cannot save profile';
			}
		} else {
			echo 'Edit your profile:';
		}
	?>
	<hr />
	<?php if (!isset($_COOKIE['login'])) die("Log in to edit your profile"); ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<label>About me:<br /><textarea name="text" rows="10" style="width:100%;" placeholder="Write about me."><?php if (isset($obj->text)) { echo htmlspecialchars($obj->text); } ?></textarea></label>
	<br /><br />
	<label>GitHub link: <span style="cursor:text;background-color:white;color:black;border:1px solid;padding:7px;">https://github.com/<input value="<?php if (isset($obj->github)) { echo htmlspecialchars($obj->github); } ?>" type="text" name="github" style="margin:none;border:0px solid;padding:0px;" /></span></label>
	<br /><br />
	<label>Personal site: <input value="<?php if (isset($obj->text)) { echo htmlspecialchars($obj->site); } ?>" type="url" name="site" /></label>
	<br />
	<p><strong>Note:</strong> All of these fields are optional. If you fill them out, they will be displayed when your profile is viewed.</p>
	<br />
	<input type="submit" value="Save profile" />
	</form>