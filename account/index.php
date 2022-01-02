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
    <base href=".." />
    <title>My Account</title>
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	if ($_COOKIE['login']) {
		echo "<h2>".htmlspecialchars(getname()).'</h2>';
	?>
	<div style="border-bottom:1px solid;">
		<span style="border-radius:3px 3px 0px 0px; text-decoration:none;border:1px solid;border-bottom:1px solid pink;">Account Options</span>
		<a href="messages/">Private Messages</a>
	</div>
	<details open="open">
		<summary>Account Options</summary>
		<button onclick="window.location.assign('account/changepass.php');">Change Password</button><br />
		You can edit your user profile:
		<form action="account/edituser.php" method="get"><input type="submit" value="Edit" /></form>You can view your user profile:
		<form action="account/viewuser.php" method="get"><input type="hidden" name="user" value="<?php echo htmlspecialchars(getname()); ?>" /><input type="submit" value="View profile" /></form>
		
		If you do not want your account anymore, click below to delete it: <br />
		<form action="account/deleteaccount.php">
		<input type="submit" value="Delete Account" />
		</form>
		<h3>Other settings</h3>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<?php if (isset($_POST['save-settings'])) {
		if (isset($_POST['mdmail'])) {
			fwrite(fopen("../data/accounts/" . cleanFilename(getname()) . "/mdmail.txt", "w+"), "");
		} else {
			unlink("../data/accounts/" . cleanFilename(getname()) . "/mdmail.txt");
		}
		if (isset($_POST['increasedfontsize'])) {
			fwrite(fopen("../data/accounts/" . cleanFilename(getname()) . "/increasedfontsize.txt", "w+"), "");
		} else {
			if (file_exists("../data/accounts/" . cleanFilename(getname()) . "/increasedfontsize.txt")) {unlink("../data/accounts/" . cleanFilename(getname()) . "/increasedfontsize.txt");}
		}
		?><div style="width:100%;background-color:gold;color:black;text-align:center;padding:7px;">All settings below this message have been saved. You can adjust them again or leave this page. If you adjusted any settings marked with <sup>*</sup>, you will have to reload the page to see the effects. <a class="fakebutton" href="account/" style="color:white;padding:0;">Reload now</a></div><?php
	} ?>
		<label id="mdmail"><input type="checkbox" name="mdmail" <?php if (file_exists("../data/accounts/" . cleanFilename(getname()) . "/mdmail.txt")) { ?>checked="checked" <?php } ?>/> Force mail to render as Markdown</label><br />
			<label id="increasedfontsize"><input type="checkbox" name="increasedfontsize" <?php if (file_exists("../data/accounts/" . cleanFilename(getname()) . "/increasedfontsize.txt")) { ?>checked="checked" <?php } ?>/> Increased font size</label><sup>*</sup><br />
			<input type="submit" name="save-settings" value="Save this batch" />
		</form>
		<sup>*: Requires additional reload after save</sup>
		</details>
	<?php
	} else {
		echo "You are not logged in.";
	}