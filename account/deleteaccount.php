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
if (isset($_POST['username'])) {
	$GLOBALS['d'] = false;
	
	require_once '../libraries/lib.php';

	if ($_POST['username'] == getname()) {
		$GLOBALS['d'] = true;
		setcookie('login', null, -1, '/'); 
		delTree("../data/accounts/" . cleanFilename(getname()));
		?>Your account has been deleted. Please return home to continue.<?php
		exit(0);
	}
}
?><html lang="en">
  <head>
    <base href="../" />
    <title>Delete Account</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<h2>Delete Account</h2>
	<?php 
	if (!isset($_COOKIE['login'])) {
		?>How do you delete your account without an account?<?php 
		exit(0);
	}
	if (isset($GLOBALS['d'])) {
		if ($GLOBALS['d']) {
			?>Your account has been deleted.<?php
		} else {
			?>Data invalid. Please go to previous page.<?php 
		}
		exit(0);
	}
	?>
	<p><strong>Please make sure you are logged in to the account you want to delete.</strong></p>
	<p>Make sure you understand before deleting:</p>
	<div class="smaller box">
	<ul>
	<li>Deleting an account cannot be undone. Once account deletion is requested, the entire profile will be removed from the server, and you will be logged out. Once your account is deleted, there is no way to associate your posts, restore your account, or view your profile.</li>
	<li>Your posts will no longer be signed with your avatar or username. They will be disassociated, and there is no way to find out the original author of your posts once your profile is removed.</li>
	<li>Your profile page will go permanently offline as if it never existed in the first place.</li>
	<li>The following will not be deleted:
	<ul><li>Posts you made; they will be kept but will not be associated with your account,</li><li>Private messages you send</li><li>Names in replies and mentions</li></ul></li>
	<li>If you are happy with deleting your account, please type your name in the box below and click "Delete":</li>
	</ul>
	</div>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset<?php if (verifyAdmin()) { ?> disabled="disabled"<?php } ?>><legend>Delete account</legend>
	<label>Type username to confirm: <input type="text" name="username" /></label><br />
	<input type="submit" value="Delete" />
	<?php
	if (verifyAdmin()) {
		?><br />Error: You are an administrator. You must be removed from the list of administrators before your account is deleted.</fieldset></form><?php 
		exit(0);
	}
	?>
	</fieldset>
	</form>